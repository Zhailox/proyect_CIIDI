--
-- PostgreSQL database dump
--

\restrict Oceqi0w1MV9uxcWi0OKrxa2mbrrO8oLLgZqssI8x3s2kQHEO8mbgJZ9qcW75jbe

-- Dumped from database version 17.11 (Debian 17.11-0+deb13u1)
-- Dumped by pg_dump version 17.11 (Debian 17.11-0+deb13u1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_id_rol_fkey;
ALTER TABLE IF EXISTS ONLY public.trayectos DROP CONSTRAINT IF EXISTS trayectos_id_carrera_fkey;
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
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_id_usuario_fkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_id_carrera_fkey;
ALTER TABLE IF EXISTS ONLY public.historico_versiones_pst DROP CONSTRAINT IF EXISTS fk_version_recurso;
ALTER TABLE IF EXISTS ONLY public.recurso_investigadores DROP CONSTRAINT IF EXISTS fk_recurso_inv;
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_recurso_etiqueta;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_recurso;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_inv;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_estudiante;
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
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_id_docente_fkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_usuario_responsable_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_registro_actividad_fkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_id_recurso_fkey;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_update ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_insert ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_usuarios_delete ON public.usuarios;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_insert ON public.recursos;
DROP TRIGGER IF EXISTS tg_auditoria_recursos_delete ON public.recursos;
DROP INDEX IF EXISTS public.idx_trayectos_carrera;
DROP INDEX IF EXISTS public.idx_recurso_clasif_linea;
DROP INDEX IF EXISTS public.idx_recurso_clasif_dimension;
DROP INDEX IF EXISTS public.idx_pcv_recurso;
DROP INDEX IF EXISTS public.idx_pcv_carrera;
DROP INDEX IF EXISTS public.idx_detalles_vector_null;
DROP INDEX IF EXISTS public.idx_detalles_vector_hnsw;
DROP INDEX IF EXISTS public.idx_detalles_proyectos_trayecto;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
DROP INDEX IF EXISTS public.autores_cedula_key;
ALTER TABLE IF EXISTS ONLY public.waf_rate_limiter DROP CONSTRAINT IF EXISTS waf_rate_limiter_pkey;
ALTER TABLE IF EXISTS ONLY public.visitantes DROP CONSTRAINT IF EXISTS visitantes_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_email_key;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_cedula_key;
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
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_pkey;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS postulaciones_estudiantes_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_carreras_vinculadas DROP CONSTRAINT IF EXISTS pk_proyecto_carreras_vinculadas;
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
ALTER TABLE IF EXISTS ONLY public.cursos DROP CONSTRAINT IF EXISTS cursos_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.categorias DROP CONSTRAINT IF EXISTS categorias_nombre_key;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_pkey;
ALTER TABLE IF EXISTS ONLY public.carreras DROP CONSTRAINT IF EXISTS carreras_nombre_key;
ALTER TABLE IF EXISTS ONLY public.autores DROP CONSTRAINT IF EXISTS autores_pkey;
ALTER TABLE IF EXISTS ONLY public.auditoria DROP CONSTRAINT IF EXISTS auditoria_pkey;
ALTER TABLE IF EXISTS ONLY public.accesos_recursos DROP CONSTRAINT IF EXISTS accesos_recursos_pkey;
ALTER TABLE IF EXISTS public.visitantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.usuarios ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tutores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.trayectos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_tutor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.tipo_recurso ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.registro_actividad ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.recursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.propuestas_empresa ALTER COLUMN id DROP DEFAULT;
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
ALTER TABLE IF EXISTS public.cursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.categorias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.carreras ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.autores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.auditoria ALTER COLUMN id DROP DEFAULT;
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
DROP SEQUENCE IF EXISTS public.cursos_id_seq;
DROP TABLE IF EXISTS public.cursos;
DROP SEQUENCE IF EXISTS public.categorias_id_seq;
DROP TABLE IF EXISTS public.categorias;
DROP SEQUENCE IF EXISTS public.carreras_id_seq;
DROP TABLE IF EXISTS public.carreras;
DROP SEQUENCE IF EXISTS public.autores_id_seq;
DROP TABLE IF EXISTS public.autores;
DROP SEQUENCE IF EXISTS public.auditoria_id_seq;
DROP TABLE IF EXISTS public.auditoria;
DROP SEQUENCE IF EXISTS public.accesos_recursos_id_seq;
DROP TABLE IF EXISTS public.accesos_recursos;
DROP PROCEDURE IF EXISTS public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone);
DROP FUNCTION IF EXISTS public.fn_auditoria_usuarios();
DROP FUNCTION IF EXISTS public.fn_auditoria_recursos();
DROP TYPE IF EXISTS public.tipo_pregunta_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_usuario_enum;
DROP TYPE IF EXISTS public.tipo_interaccion_enum;
DROP TYPE IF EXISTS public.estado_propuesta_enum;
DROP TYPE IF EXISTS public.estado_curso_enum;
DROP TYPE IF EXISTS public.accion_auditoria_enum;
DROP TYPE IF EXISTS public.accion_acceso_enum;
DROP EXTENSION IF EXISTS vector;
--
-- Name: vector; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public;


--
-- Name: EXTENSION vector; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION vector IS 'vector data type and ivfflat and hnsw access methods';


--
-- Name: accion_acceso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_acceso_enum AS ENUM (
    'visualizacion',
    'descarga'
);


ALTER TYPE public.accion_acceso_enum OWNER TO postgres;

--
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);


ALTER TYPE public.accion_auditoria_enum OWNER TO postgres;

--
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);


ALTER TYPE public.estado_curso_enum OWNER TO postgres;

--
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);


ALTER TYPE public.estado_propuesta_enum OWNER TO postgres;

--
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);


ALTER TYPE public.tipo_interaccion_enum OWNER TO postgres;

--
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);


ALTER TYPE public.tipo_interaccion_usuario_enum OWNER TO postgres;

--
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);


ALTER TYPE public.tipo_pregunta_enum OWNER TO postgres;

--
-- Name: fn_auditoria_recursos(); Type: FUNCTION; Schema: public; Owner: postgres
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
        -- Aquí se removió la referencia a NEW.ejemplares_totales que hacía explotar la BD
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('titulo', NEW.titulo, 'id_tipo_recurso', NEW.id_tipo_recurso), CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;


ALTER FUNCTION public.fn_auditoria_recursos() OWNER TO postgres;

--
-- Name: fn_auditoria_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
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


ALTER FUNCTION public.fn_auditoria_usuarios() OWNER TO postgres;

--
-- Name: insertarproyectoaleatorio(timestamp without time zone); Type: PROCEDURE; Schema: public; Owner: postgres
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
    
    arr_tit_acc VARCHAR[] := ARRAY['Sistema', 'Aplicación', 'Plataforma', 'Prototipo', 'Análisis', 'Diseño', 'Implementación', 'Optimización', 'Automatización', 'Evaluación', 'Desarrollo', 'Modelo'];
    arr_tit_obj VARCHAR[] := ARRAY['Gestión', 'Monitoreo', 'Control', 'Predicción', 'Seguridad', 'Reconocimiento', 'Clasificación', 'Procesamiento', 'Visualización', 'Comunicación', 'Bajo Costo', 'Alto Rendimiento'];
    arr_tit_dest VARCHAR[] := ARRAY['la Comunidad', 'UPTTMBI', 'el Sector Agroalimentario', 'Zonas Rurales', 'Instituciones Educativas', 'Pequeñas Empresas', 'el Área de Salud', 'el Transporte Público', 'la Industria 4.0', 'la Transformación Digital'];
    
    arr_carreras VARCHAR[] := ARRAY['Informática', 'Electricidad', 'Administración', 'Agroalimentación', 'Construcción Civil'];
BEGIN
    mes_actual := EXTRACT(MONTH FROM fecha_creada);

    -- Asignar nivel académico
    IF mes_actual IN (4,8) THEN
        nivel_academico_aleatorio := arr_niveles1[floor(random() * 4 + 1)::int]::nivel_academico_enum;
    ELSIF mes_actual = 12 THEN
        nivel_academico_aleatorio := arr_niveles2[floor(random() * 3 + 1)::int]::nivel_academico_enum;
    ELSE
        nivel_academico_aleatorio := arr_niveles3[floor(random() * 5 + 1)::int]::nivel_academico_enum;
    END IF;

    -- Ajustar pesos específicos
    IF random() < 0.4 THEN 
        nivel_academico_aleatorio := 'Pregrado';
    ELSIF random() < 0.25 THEN 
        nivel_academico_aleatorio := 'TSU';
    END IF;

    -- Carrera aleatoria
    carrera_aleatoria := floor(random() * 5 + 1)::int;

    -- Título dinámico
    titulo_base := arr_tit_acc[floor(random() * 12 + 1)::int] || ' de ' || 
                   arr_tit_obj[floor(random() * 12 + 1)::int] || ' para ' || 
                   arr_tit_dest[floor(random() * 10 + 1)::int];

    -- Resumen
    resumen_texto := 'Proyecto desarrollado en ' || arr_carreras[carrera_aleatoria] || '. Aborda problemáticas reales con enfoque práctico.';

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
    
    -- Si no insertó (por no hallar autor en ese rango), fuerza el autor 1
    IF NOT FOUND THEN
        INSERT INTO recurso_autores (id_recurso, id_autor) VALUES (nuevo_id, 1);
    END IF;
END;
$$;


ALTER PROCEDURE public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: accesos_recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.accesos_recursos (
    id integer NOT NULL,
    id_registro_actividad integer NOT NULL,
    id_recurso integer NOT NULL,
    accion public.accion_acceso_enum DEFAULT 'visualizacion'::public.accion_acceso_enum,
    fecha_acceso timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.accesos_recursos OWNER TO postgres;

--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.accesos_recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.accesos_recursos_id_seq OWNER TO postgres;

--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.accesos_recursos_id_seq OWNED BY public.accesos_recursos.id;


--
-- Name: auditoria; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.auditoria OWNER TO postgres;

--
-- Name: auditoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.auditoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.auditoria_id_seq OWNER TO postgres;

--
-- Name: auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.auditoria_id_seq OWNED BY public.auditoria.id;


--
-- Name: autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.autores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20) DEFAULT NULL::character varying
);


ALTER TABLE public.autores OWNER TO postgres;

--
-- Name: autores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.autores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.autores_id_seq OWNER TO postgres;

--
-- Name: autores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.autores_id_seq OWNED BY public.autores.id;


--
-- Name: carreras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carreras (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);


ALTER TABLE public.carreras OWNER TO postgres;

--
-- Name: carreras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carreras_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carreras_id_seq OWNER TO postgres;

--
-- Name: carreras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carreras_id_seq OWNED BY public.carreras.id;


--
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: cursos; Type: TABLE; Schema: public; Owner: postgres
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
    nivel character varying(50) DEFAULT 'B sico'::character varying,
    duracion character varying(80),
    cupo_maximo integer,
    fecha_inicio date,
    fecha_fin date,
    url_video_preview text,
    estado_inscripcion character varying(50) DEFAULT 'Abierta'::character varying
);


ALTER TABLE public.cursos OWNER TO postgres;

--
-- Name: cursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cursos_id_seq OWNER TO postgres;

--
-- Name: cursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cursos_id_seq OWNED BY public.cursos.id;


--
-- Name: detalles_articulos; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.detalles_articulos OWNER TO postgres;

--
-- Name: detalles_investigaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_investigaciones (
    id_recurso integer NOT NULL,
    planteamiento_problema text NOT NULL,
    objetivo_general text NOT NULL,
    id_investigacion_ofertada integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.detalles_investigaciones OWNER TO postgres;

--
-- Name: detalles_proyectos; Type: TABLE; Schema: public; Owner: postgres
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
    vector_semantico public.vector(384),
    id_trayecto integer,
    vistas integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.detalles_proyectos OWNER TO postgres;

--
-- Name: COLUMN detalles_proyectos.vistas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.detalles_proyectos.vistas IS 'Contador de visualizaciones de la ficha técnica del proyecto PST';


--
-- Name: dimensiones_operativas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dimensiones_operativas (
    id integer NOT NULL,
    id_linea integer NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);


ALTER TABLE public.dimensiones_operativas OWNER TO postgres;

--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dimensiones_operativas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNER TO postgres;

--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNED BY public.dimensiones_operativas.id;


--
-- Name: editoriales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.editoriales (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.editoriales OWNER TO postgres;

--
-- Name: editoriales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.editoriales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.editoriales_id_seq OWNER TO postgres;

--
-- Name: editoriales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.editoriales_id_seq OWNED BY public.editoriales.id;


--
-- Name: etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etiquetas (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    color_hex character varying(7) DEFAULT '#0ea5e9'::character varying
);


ALTER TABLE public.etiquetas OWNER TO postgres;

--
-- Name: etiquetas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.etiquetas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.etiquetas_id_seq OWNER TO postgres;

--
-- Name: etiquetas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.etiquetas_id_seq OWNED BY public.etiquetas.id;


--
-- Name: historico_versiones_pst; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.historico_versiones_pst (
    id integer NOT NULL,
    id_recurso integer NOT NULL,
    archivo_pdf character varying(500) NOT NULL,
    usuario_id integer,
    motivo character varying(255) DEFAULT 'Actualizaci¢n'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.historico_versiones_pst OWNER TO postgres;

--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.historico_versiones_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNER TO postgres;

--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNED BY public.historico_versiones_pst.id;


--
-- Name: investigaciones_ofertadas; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.investigaciones_ofertadas OWNER TO postgres;

--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.investigaciones_ofertadas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNER TO postgres;

--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


--
-- Name: investigadores; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.investigadores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    orcid character varying(50) NOT NULL,
    biografia text,
    pagina_web character varying(255)
);


ALTER TABLE public.investigadores OWNER TO miki;

--
-- Name: investigadores_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.investigadores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.investigadores_id_seq OWNER TO miki;

--
-- Name: investigadores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.investigadores_id_seq OWNED BY public.investigadores.id;


--
-- Name: lineas_investigacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lineas_investigacion (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    id_carrera integer NOT NULL,
    descripcion text,
    activo boolean DEFAULT true
);


ALTER TABLE public.lineas_investigacion OWNER TO postgres;

--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lineas_investigacion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lineas_investigacion_id_seq OWNER TO postgres;

--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lineas_investigacion_id_seq OWNED BY public.lineas_investigacion.id;


--
-- Name: matriz_rbac; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matriz_rbac (
    nivel_privilegio integer NOT NULL,
    modulo character varying(100) NOT NULL,
    permisos jsonb
);


ALTER TABLE public.matriz_rbac OWNER TO postgres;

--
-- Name: niveles_academicos; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.niveles_academicos OWNER TO miki;

--
-- Name: TABLE niveles_academicos; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.niveles_academicos IS 'Catálogo administrativo dinámico de niveles académicos para proyectos del repositorio';


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.niveles_academicos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.niveles_academicos_id_seq OWNER TO miki;

--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.niveles_academicos_id_seq OWNED BY public.niveles_academicos.id;


--
-- Name: notificaciones; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.notificaciones OWNER TO postgres;

--
-- Name: notificaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notificaciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notificaciones_id_seq OWNER TO postgres;

--
-- Name: notificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_id_seq OWNED BY public.notificaciones.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    id integer NOT NULL,
    email character varying(100) NOT NULL,
    token_hash character varying(255) NOT NULL,
    expiracion timestamp without time zone NOT NULL,
    utilizado boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- Name: password_resets_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.password_resets_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.password_resets_id_seq OWNER TO postgres;

--
-- Name: password_resets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.password_resets_id_seq OWNED BY public.password_resets.id;


--
-- Name: postulaciones_estudiantes; Type: TABLE; Schema: public; Owner: postgres
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
    CONSTRAINT postulaciones_estudiantes_estado_check CHECK (((estado)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Aceptado'::character varying)::text, ('Rechazado'::character varying)::text])))
);


ALTER TABLE public.postulaciones_estudiantes OWNER TO postgres;

--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.postulaciones_estudiantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNER TO postgres;

--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNED BY public.postulaciones_estudiantes.id;


--
-- Name: preferencias_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.preferencias_usuario (
    id_usuario integer NOT NULL,
    tema character varying(50) DEFAULT 'light'::character varying,
    notificaciones_sistema boolean DEFAULT true
);


ALTER TABLE public.preferencias_usuario OWNER TO postgres;

--
-- Name: privilegios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.privilegios OWNER TO postgres;

--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.privilegios_privilegio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNER TO postgres;

--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNED BY public.privilegios.privilegio_id;


--
-- Name: propuestas_empresa; Type: TABLE; Schema: public; Owner: postgres
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
    motivo_rechazo text
);


ALTER TABLE public.propuestas_empresa OWNER TO postgres;

--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.propuestas_empresa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.propuestas_empresa_id_seq OWNER TO postgres;

--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- Name: proyecto_carreras_vinculadas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.proyecto_carreras_vinculadas (
    id_recurso integer NOT NULL,
    id_carrera integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.proyecto_carreras_vinculadas OWNER TO miki;

--
-- Name: TABLE proyecto_carreras_vinculadas; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'Relación N:M para vincular proyectos PST con carreras secundarias adicionales a su carrera base.';


--
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);


ALTER TABLE public.proyecto_tutores OWNER TO postgres;

--
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);


ALTER TABLE public.recurso_autores OWNER TO postgres;

--
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);


ALTER TABLE public.recurso_categorias OWNER TO postgres;

--
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);


ALTER TABLE public.recurso_clasificaciones OWNER TO postgres;

--
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);


ALTER TABLE public.recurso_etiquetas OWNER TO postgres;

--
-- Name: recurso_investigadores; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.recurso_investigadores (
    id_recurso integer NOT NULL,
    id_investigador integer NOT NULL
);


ALTER TABLE public.recurso_investigadores OWNER TO miki;

--
-- Name: recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recursos (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    id_tipo_recurso integer NOT NULL,
    anio_publicacion integer,
    archivo_pdf character varying(255)
);


ALTER TABLE public.recursos OWNER TO postgres;

--
-- Name: recursos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.recursos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.recursos_id_seq OWNER TO postgres;

--
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.recursos_id_seq OWNED BY public.recursos.id;


--
-- Name: registro_actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registro_actividad (
    id integer NOT NULL,
    id_usuario integer,
    id_visitante integer,
    fecha_inicial timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    ultima_actividad timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    conteo_accesos integer DEFAULT 1
);


ALTER TABLE public.registro_actividad OWNER TO postgres;

--
-- Name: registro_actividad_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registro_actividad_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registro_actividad_id_seq OWNER TO postgres;

--
-- Name: registro_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registro_actividad_id_seq OWNED BY public.registro_actividad.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    privilegio_id integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: system_audit_log; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.system_audit_log OWNER TO postgres;

--
-- Name: telemetria_cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.telemetria_cache (
    id integer DEFAULT 1 NOT NULL,
    datos jsonb
);


ALTER TABLE public.telemetria_cache OWNER TO postgres;

--
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_recurso OWNER TO postgres;

--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_recurso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_recurso_id_seq OWNER TO postgres;

--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_recurso_id_seq OWNED BY public.tipo_recurso.id;


--
-- Name: tipo_tutor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_tutor (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_tutor OWNER TO postgres;

--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_tutor_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipo_tutor_id_seq OWNER TO postgres;

--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- Name: trayectos; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.trayectos OWNER TO miki;

--
-- Name: trayectos_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.trayectos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.trayectos_id_seq OWNER TO miki;

--
-- Name: trayectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.trayectos_id_seq OWNED BY public.trayectos.id;


--
-- Name: tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.tutores OWNER TO postgres;

--
-- Name: tutores_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tutores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tutores_id_seq OWNER TO postgres;

--
-- Name: tutores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tutores_id_seq OWNED BY public.tutores.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
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
    activation_token character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuarios_id_seq OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: visitantes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.visitantes (
    id integer NOT NULL,
    ip_address character varying(45) NOT NULL,
    user_agent text,
    pagina_origen character varying(255)
);


ALTER TABLE public.visitantes OWNER TO postgres;

--
-- Name: visitantes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.visitantes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.visitantes_id_seq OWNER TO postgres;

--
-- Name: visitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.visitantes_id_seq OWNED BY public.visitantes.id;


--
-- Name: waf_rate_limiter; Type: TABLE; Schema: public; Owner: postgres
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


ALTER TABLE public.waf_rate_limiter OWNER TO postgres;

--
-- Name: accesos_recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos ALTER COLUMN id SET DEFAULT nextval('public.accesos_recursos_id_seq'::regclass);


--
-- Name: auditoria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria ALTER COLUMN id SET DEFAULT nextval('public.auditoria_id_seq'::regclass);


--
-- Name: autores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores ALTER COLUMN id SET DEFAULT nextval('public.autores_id_seq'::regclass);


--
-- Name: carreras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras ALTER COLUMN id SET DEFAULT nextval('public.carreras_id_seq'::regclass);


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: cursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos ALTER COLUMN id SET DEFAULT nextval('public.cursos_id_seq'::regclass);


--
-- Name: dimensiones_operativas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas ALTER COLUMN id SET DEFAULT nextval('public.dimensiones_operativas_id_seq'::regclass);


--
-- Name: editoriales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales ALTER COLUMN id SET DEFAULT nextval('public.editoriales_id_seq'::regclass);


--
-- Name: etiquetas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas ALTER COLUMN id SET DEFAULT nextval('public.etiquetas_id_seq'::regclass);


--
-- Name: historico_versiones_pst id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst ALTER COLUMN id SET DEFAULT nextval('public.historico_versiones_pst_id_seq'::regclass);


--
-- Name: investigaciones_ofertadas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas ALTER COLUMN id SET DEFAULT nextval('public.investigaciones_ofertadas_id_seq'::regclass);


--
-- Name: investigadores id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores ALTER COLUMN id SET DEFAULT nextval('public.investigadores_id_seq'::regclass);


--
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


--
-- Name: niveles_academicos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos ALTER COLUMN id SET DEFAULT nextval('public.niveles_academicos_id_seq'::regclass);


--
-- Name: notificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_id_seq'::regclass);


--
-- Name: password_resets id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets ALTER COLUMN id SET DEFAULT nextval('public.password_resets_id_seq'::regclass);


--
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


--
-- Name: propuestas_empresa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa ALTER COLUMN id SET DEFAULT nextval('public.propuestas_empresa_id_seq'::regclass);


--
-- Name: recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos ALTER COLUMN id SET DEFAULT nextval('public.recursos_id_seq'::regclass);


--
-- Name: registro_actividad id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad ALTER COLUMN id SET DEFAULT nextval('public.registro_actividad_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: tipo_recurso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso ALTER COLUMN id SET DEFAULT nextval('public.tipo_recurso_id_seq'::regclass);


--
-- Name: tipo_tutor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor ALTER COLUMN id SET DEFAULT nextval('public.tipo_tutor_id_seq'::regclass);


--
-- Name: trayectos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos ALTER COLUMN id SET DEFAULT nextval('public.trayectos_id_seq'::regclass);


--
-- Name: tutores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores ALTER COLUMN id SET DEFAULT nextval('public.tutores_id_seq'::regclass);


--
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Name: visitantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes ALTER COLUMN id SET DEFAULT nextval('public.visitantes_id_seq'::regclass);


--
-- Data for Name: accesos_recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: auditoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.auditoria VALUES (1, 'usuarios', 1, 'INSERT', NULL, NULL, NULL, '{"email": "andru@gmail.com", "id_rol": 1, "nombre": "Adrus"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (2, 'usuarios', 2, 'INSERT', NULL, NULL, NULL, '{"email": "lando@gmail.com", "id_rol": 2, "nombre": "lando"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (3, 'usuarios', 3, 'INSERT', NULL, NULL, NULL, '{"email": "miki@gmail.com", "id_rol": 3, "nombre": "miki"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (4, 'usuarios', 4, 'INSERT', NULL, NULL, NULL, '{"email": "ale@yaju.com", "id_rol": 3, "nombre": "ale"}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (5, 'recursos', 1, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (6, 'recursos', 2, 'INSERT', NULL, NULL, NULL, '{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (7, 'recursos', 3, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 14:09:42');
INSERT INTO public.auditoria VALUES (8, 'usuarios', 4, 'UPDATE', 1, NULL, '{"activo": 1, "id_rol": 3, "nombre": "ale"}', '{"activo": 1, "id_rol": 1, "nombre": "ale"}', '2026-03-23 16:14:24');
INSERT INTO public.auditoria VALUES (9, 'recursos', 4, 'INSERT', NULL, NULL, NULL, '{"titulo": "Impacto del Cambio Climático en Trujillo - Parte 8", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 16:56:13');
INSERT INTO public.auditoria VALUES (10, 'recursos', 5, 'INSERT', NULL, NULL, NULL, '{"titulo": "Simulación de Cargas Estáticas en Puentes - Parte 7", "id_tipo_recurso": 2, "ejemplares_totales": 1}', '2026-03-23 16:57:08');
INSERT INTO public.auditoria VALUES (11, 'recursos', 6, 'INSERT', NULL, NULL, NULL, '{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (12, 'recursos', 7, 'INSERT', NULL, NULL, NULL, '{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (13, 'recursos', 8, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-03-23 16:58:11');
INSERT INTO public.auditoria VALUES (14, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 18:46:17.662484');
INSERT INTO public.auditoria VALUES (15, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 19:05:42.587427');
INSERT INTO public.auditoria VALUES (16, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "piña"}', '2026-06-18 19:54:46.993547');
INSERT INTO public.auditoria VALUES (17, 'usuarios', 7, 'INSERT', NULL, NULL, NULL, '{"email": "erwazaaaa@gmail.com", "id_rol": 3, "nombre": "Migel González"}', '2026-06-18 20:58:57.768568');
INSERT INTO public.auditoria VALUES (18, 'usuarios', 8, 'INSERT', NULL, NULL, NULL, '{"email": "yisu@gmail.com", "id_rol": 3, "nombre": "Yisu Monte"}', '2026-06-18 20:59:49.682537');
INSERT INTO public.auditoria VALUES (19, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Migel González"}', '{"activo": true, "id_rol": 1, "nombre": "Migel González"}', '2026-06-18 21:38:28.663879');
INSERT INTO public.auditoria VALUES (20, 'usuarios', 9, 'INSERT', NULL, NULL, NULL, '{"email": "iaiaia@gmail.com", "id_rol": 3, "nombre": "Pedro Perez"}', '2026-06-24 23:07:53.05286');
INSERT INTO public.auditoria VALUES (21, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "piña"}', '{"activo": true, "id_rol": 3, "nombre": "Piñin"}', '2026-06-24 23:57:15.201582');
INSERT INTO public.auditoria VALUES (22, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-24 23:57:20.104368');
INSERT INTO public.auditoria VALUES (23, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 2, "nombre": "Piñin"}', '2026-06-24 23:57:31.726744');
INSERT INTO public.auditoria VALUES (24, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-24 23:57:37.330082');
INSERT INTO public.auditoria VALUES (25, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-25 00:01:40.353031');
INSERT INTO public.auditoria VALUES (26, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '2026-06-25 00:01:46.873155');
INSERT INTO public.auditoria VALUES (27, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:01:55.730238');
INSERT INTO public.auditoria VALUES (28, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:23:15.783421');
INSERT INTO public.auditoria VALUES (29, 'usuarios', 10, 'INSERT', NULL, NULL, NULL, '{"email": "wazaaa@gmail.com", "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:33:07.592137');
INSERT INTO public.auditoria VALUES (30, 'usuarios', 11, 'INSERT', NULL, NULL, NULL, '{"email": "123@gmail.com", "id_rol": 3, "nombre": "Juan"}', '2026-06-25 00:33:28.49575');
INSERT INTO public.auditoria VALUES (31, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:34:43.439159');
INSERT INTO public.auditoria VALUES (32, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:34:45.543113');
INSERT INTO public.auditoria VALUES (33, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:34:51.608311');
INSERT INTO public.auditoria VALUES (34, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-25 00:35:18.586051');
INSERT INTO public.auditoria VALUES (35, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:57:51.974985');
INSERT INTO public.auditoria VALUES (36, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:57:57.479012');
INSERT INTO public.auditoria VALUES (37, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:58:00.692516');
INSERT INTO public.auditoria VALUES (38, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-25 00:58:05.014824');
INSERT INTO public.auditoria VALUES (39, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '2026-06-25 00:58:32.420893');
INSERT INTO public.auditoria VALUES (40, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Migel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-06-25 01:22:04.451131');
INSERT INTO public.auditoria VALUES (41, 'usuarios', 6, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}', '{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}', '2026-06-29 11:45:47.198869');
INSERT INTO public.auditoria VALUES (238, 'recursos', 131, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 19:58:49.193407');
INSERT INTO public.auditoria VALUES (42, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '2026-06-29 12:10:37.626132');
INSERT INTO public.auditoria VALUES (43, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '2026-06-29 14:18:17.58523');
INSERT INTO public.auditoria VALUES (44, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '2026-06-29 14:18:34.834966');
INSERT INTO public.auditoria VALUES (45, 'usuarios', 10, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}', '2026-06-29 14:19:05.388306');
INSERT INTO public.auditoria VALUES (46, 'recursos', 21, 'INSERT', NULL, NULL, NULL, '{"titulo": "Betty yo a usted la amo", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-04 23:19:52.515406');
INSERT INTO public.auditoria VALUES (47, 'recursos', 22, 'INSERT', NULL, NULL, NULL, '{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 11:43:06.035947');
INSERT INTO public.auditoria VALUES (48, 'recursos', 23, 'INSERT', NULL, NULL, NULL, '{"titulo": "La Gran Verge", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 11:47:05.718779');
INSERT INTO public.auditoria VALUES (49, 'recursos', 24, 'INSERT', NULL, NULL, NULL, '{"titulo": "Pepe", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:02:34.181399');
INSERT INTO public.auditoria VALUES (50, 'recursos', 25, 'INSERT', NULL, NULL, NULL, '{"titulo": "Luisito comunicando", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:18:57.684684');
INSERT INTO public.auditoria VALUES (51, 'recursos', 26, 'INSERT', NULL, NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:24:53.394994');
INSERT INTO public.auditoria VALUES (52, 'recursos', 27, 'INSERT', NULL, NULL, NULL, '{"titulo": "Que la guagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:29:10.583616');
INSERT INTO public.auditoria VALUES (53, 'recursos', 28, 'INSERT', NULL, NULL, NULL, '{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:38:15.349753');
INSERT INTO public.auditoria VALUES (54, 'recursos', 22, 'DELETE', NULL, NULL, '{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3}', NULL, '2026-07-05 12:43:01.095251');
INSERT INTO public.auditoria VALUES (55, 'recursos', 26, 'DELETE', NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3}', NULL, '2026-07-05 12:43:29.378629');
INSERT INTO public.auditoria VALUES (58, 'recursos', 31, 'INSERT', NULL, NULL, NULL, '{"titulo": "Imitadora", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 12:57:22.541344');
INSERT INTO public.auditoria VALUES (59, 'recursos', 23, 'DELETE', NULL, NULL, '{"titulo": "La Gran Verge", "id_tipo_recurso": 3}', NULL, '2026-07-05 13:31:21.954982');
INSERT INTO public.auditoria VALUES (60, 'recursos', 32, 'INSERT', NULL, NULL, NULL, '{"titulo": "Manguagua 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 14:44:32.452702');
INSERT INTO public.auditoria VALUES (61, 'recursos', 33, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:04:26.857648');
INSERT INTO public.auditoria VALUES (62, 'recursos', 33, 'DELETE', NULL, NULL, '{"titulo": "Waos", "id_tipo_recurso": 3}', NULL, '2026-07-05 15:05:03.276405');
INSERT INTO public.auditoria VALUES (63, 'recursos', 34, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 1", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:24:54.658482');
INSERT INTO public.auditoria VALUES (64, 'recursos', 35, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:25:19.934157');
INSERT INTO public.auditoria VALUES (65, 'recursos', 36, 'INSERT', NULL, NULL, NULL, '{"titulo": "Waos 3", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:25:52.428559');
INSERT INTO public.auditoria VALUES (66, 'recursos', 37, 'INSERT', NULL, NULL, NULL, '{"titulo": "23123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:06.835471');
INSERT INTO public.auditoria VALUES (67, 'recursos', 38, 'INSERT', NULL, NULL, NULL, '{"titulo": "23", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:22.136069');
INSERT INTO public.auditoria VALUES (68, 'recursos', 39, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:32.188843');
INSERT INTO public.auditoria VALUES (69, 'recursos', 40, 'INSERT', NULL, NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:26:43.872553');
INSERT INTO public.auditoria VALUES (70, 'recursos', 41, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:28:49.036024');
INSERT INTO public.auditoria VALUES (71, 'recursos', 42, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:28:59.682383');
INSERT INTO public.auditoria VALUES (72, 'recursos', 43, 'INSERT', NULL, NULL, NULL, '{"titulo": "123123123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 15:29:13.986916');
INSERT INTO public.auditoria VALUES (73, 'recursos', 44, 'INSERT', NULL, NULL, NULL, '{"titulo": "auuuu", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-05 16:15:08.182516');
INSERT INTO public.auditoria VALUES (74, 'recursos', 45, 'INSERT', NULL, NULL, NULL, '{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1, "ejemplares_totales": 2}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (75, 'recursos', 46, 'INSERT', NULL, NULL, NULL, '{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (76, 'recursos', 47, 'INSERT', NULL, NULL, NULL, '{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1, "ejemplares_totales": 3}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (77, 'recursos', 48, 'INSERT', NULL, NULL, NULL, '{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:21:44.350197');
INSERT INTO public.auditoria VALUES (78, 'recursos', 49, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (79, 'recursos', 50, 'INSERT', NULL, NULL, NULL, '{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (80, 'recursos', 51, 'INSERT', NULL, NULL, NULL, '{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (81, 'recursos', 52, 'INSERT', NULL, NULL, NULL, '{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 17:39:35.498485');
INSERT INTO public.auditoria VALUES (85, 'recursos', 56, 'INSERT', NULL, NULL, NULL, '{"titulo": "hola", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 18:14:56.263164');
INSERT INTO public.auditoria VALUES (86, 'recursos', 57, 'INSERT', NULL, NULL, NULL, '{"titulo": "hola adios", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-05 18:21:33.639701');
INSERT INTO public.auditoria VALUES (87, 'recursos', 56, 'DELETE', NULL, NULL, '{"titulo": "hola", "id_tipo_recurso": 1}', NULL, '2026-07-05 18:29:50.693972');
INSERT INTO public.auditoria VALUES (185, 'recursos', 105, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:37.294398');
INSERT INTO public.auditoria VALUES (120, 'recursos', 58, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-07-07 00:01:54.74783');
INSERT INTO public.auditoria VALUES (234, 'recursos', 128, 'INSERT', NULL, NULL, NULL, '{"titulo": "Materia: Seguridad Informática", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 17:22:38.760639');
INSERT INTO public.auditoria VALUES (121, 'recursos', 59, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:22:58.539505');
INSERT INTO public.auditoria VALUES (122, 'recursos', 60, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.838831');
INSERT INTO public.auditoria VALUES (123, 'recursos', 61, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.876413');
INSERT INTO public.auditoria VALUES (124, 'recursos', 62, 'INSERT', NULL, NULL, NULL, '{"titulo": "", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:12.883112');
INSERT INTO public.auditoria VALUES (125, 'recursos', 60, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:12.899565');
INSERT INTO public.auditoria VALUES (126, 'recursos', 61, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:12.919034');
INSERT INTO public.auditoria VALUES (127, 'recursos', 63, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:34.622457');
INSERT INTO public.auditoria VALUES (128, 'recursos', 64, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:34.655711');
INSERT INTO public.auditoria VALUES (129, 'recursos', 63, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:34.679754');
INSERT INTO public.auditoria VALUES (130, 'recursos', 64, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:34.704588');
INSERT INTO public.auditoria VALUES (131, 'recursos', 65, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:59.650776');
INSERT INTO public.auditoria VALUES (132, 'recursos', 66, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:40:59.678997');
INSERT INTO public.auditoria VALUES (133, 'recursos', 65, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:59.693745');
INSERT INTO public.auditoria VALUES (134, 'recursos', 66, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:40:59.706894');
INSERT INTO public.auditoria VALUES (135, 'recursos', 67, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:49:20.583041');
INSERT INTO public.auditoria VALUES (136, 'recursos', 68, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:49:20.633257');
INSERT INTO public.auditoria VALUES (137, 'recursos', 67, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:49:20.648244');
INSERT INTO public.auditoria VALUES (138, 'recursos', 68, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:49:20.66095');
INSERT INTO public.auditoria VALUES (139, 'recursos', 69, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 09:58:54.904249');
INSERT INTO public.auditoria VALUES (140, 'recursos', 62, 'DELETE', NULL, NULL, '{"titulo": "", "id_tipo_recurso": 1}', NULL, '2026-08-04 09:59:05.308634');
INSERT INTO public.auditoria VALUES (141, 'recursos', 70, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:04:38.338495');
INSERT INTO public.auditoria VALUES (142, 'recursos', 71, 'INSERT', NULL, NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:04:38.361973');
INSERT INTO public.auditoria VALUES (143, 'recursos', 70, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:04:38.374265');
INSERT INTO public.auditoria VALUES (144, 'recursos', 71, 'DELETE', NULL, NULL, '{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:04:38.384732');
INSERT INTO public.auditoria VALUES (145, 'recursos', 72, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:11:11.510708');
INSERT INTO public.auditoria VALUES (146, 'recursos', 73, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:18:06.569838');
INSERT INTO public.auditoria VALUES (147, 'recursos', 73, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:18:06.593982');
INSERT INTO public.auditoria VALUES (148, 'recursos', 74, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:21:29.064851');
INSERT INTO public.auditoria VALUES (149, 'recursos', 74, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:21:29.089783');
INSERT INTO public.auditoria VALUES (150, 'recursos', 75, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:29:50.185276');
INSERT INTO public.auditoria VALUES (151, 'recursos', 75, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:29:50.218869');
INSERT INTO public.auditoria VALUES (152, 'recursos', 76, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:45:40.223902');
INSERT INTO public.auditoria VALUES (153, 'recursos', 76, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:45:40.245315');
INSERT INTO public.auditoria VALUES (154, 'recursos', 77, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-04 10:57:10.465552');
INSERT INTO public.auditoria VALUES (155, 'recursos', 77, 'DELETE', NULL, NULL, '{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}', NULL, '2026-08-04 10:57:10.485941');
INSERT INTO public.auditoria VALUES (156, 'recursos', 78, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:43:26.945146');
INSERT INTO public.auditoria VALUES (157, 'recursos', 79, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:45:15.201621');
INSERT INTO public.auditoria VALUES (158, 'recursos', 80, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.633265');
INSERT INTO public.auditoria VALUES (159, 'recursos', 81, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.72936');
INSERT INTO public.auditoria VALUES (160, 'recursos', 82, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.817964');
INSERT INTO public.auditoria VALUES (161, 'recursos', 83, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:05.91852');
INSERT INTO public.auditoria VALUES (162, 'recursos', 84, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:48:06.00888');
INSERT INTO public.auditoria VALUES (163, 'recursos', 85, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 09:56:42.188313');
INSERT INTO public.auditoria VALUES (164, 'recursos', 86, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 10:02:04.774776');
INSERT INTO public.auditoria VALUES (165, 'recursos', 87, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-05 10:34:46.219889');
INSERT INTO public.auditoria VALUES (166, 'recursos', 88, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:26:58.264555');
INSERT INTO public.auditoria VALUES (167, 'recursos', 89, 'INSERT', NULL, NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:35:39.007693');
INSERT INTO public.auditoria VALUES (168, 'recursos', 90, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:45:42.226083');
INSERT INTO public.auditoria VALUES (169, 'recursos', 91, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 10:52:47.26864');
INSERT INTO public.auditoria VALUES (170, 'recursos', 92, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:34:04.575523');
INSERT INTO public.auditoria VALUES (171, 'recursos', 93, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:34:42.145006');
INSERT INTO public.auditoria VALUES (172, 'recursos', 94, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 11:40:58.141559');
INSERT INTO public.auditoria VALUES (175, 'recursos', 97, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:03:37.275866');
INSERT INTO public.auditoria VALUES (176, 'recursos', 98, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:04:15.392022');
INSERT INTO public.auditoria VALUES (177, 'recursos', 98, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:10:31.909346');
INSERT INTO public.auditoria VALUES (178, 'recursos', 99, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:10:58.390178');
INSERT INTO public.auditoria VALUES (179, 'recursos', 100, 'INSERT', NULL, NULL, NULL, '{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:14:42.285533');
INSERT INTO public.auditoria VALUES (180, 'recursos', 101, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:16:11.271762');
INSERT INTO public.auditoria VALUES (181, 'recursos', 102, 'INSERT', NULL, NULL, NULL, '{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:16:36.642725');
INSERT INTO public.auditoria VALUES (182, 'recursos', 103, 'INSERT', NULL, NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:01.653978');
INSERT INTO public.auditoria VALUES (183, 'recursos', 104, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:32.045482');
INSERT INTO public.auditoria VALUES (184, 'recursos', 104, 'DELETE', NULL, NULL, '{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:17:32.05166');
INSERT INTO public.auditoria VALUES (186, 'recursos', 106, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:17:54.876228');
INSERT INTO public.auditoria VALUES (187, 'recursos', 107, 'INSERT', NULL, NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:18:15.151824');
INSERT INTO public.auditoria VALUES (188, 'recursos', 102, 'DELETE', NULL, NULL, '{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (189, 'recursos', 106, 'DELETE', NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (190, 'recursos', 107, 'DELETE', NULL, NULL, '{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:18:40.558792');
INSERT INTO public.auditoria VALUES (239, 'recursos', 130, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 19:58:51.059906');
INSERT INTO public.auditoria VALUES (191, 'recursos', 108, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-10 12:20:17.959675');
INSERT INTO public.auditoria VALUES (192, 'recursos', 101, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (193, 'recursos', 103, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (194, 'recursos', 105, 'DELETE', NULL, NULL, '{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1}', NULL, '2026-08-10 12:32:05.654115');
INSERT INTO public.auditoria VALUES (195, 'recursos', 109, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-11 10:10:03.006883');
INSERT INTO public.auditoria VALUES (196, 'recursos', 110, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.312899');
INSERT INTO public.auditoria VALUES (197, 'recursos', 111, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.640902');
INSERT INTO public.auditoria VALUES (198, 'recursos', 112, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-25 19:01:06.749048');
INSERT INTO public.auditoria VALUES (199, 'recursos', 113, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 09:08:37.073105');
INSERT INTO public.auditoria VALUES (200, 'recursos', 114, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 10:17:48.017574');
INSERT INTO public.auditoria VALUES (201, 'recursos', 115, 'INSERT', NULL, NULL, NULL, '{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-27 10:46:00.573241');
INSERT INTO public.auditoria VALUES (202, 'recursos', 115, 'DELETE', NULL, NULL, '{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1}', NULL, '2026-08-29 18:33:19.816106');
INSERT INTO public.auditoria VALUES (203, 'recursos', 116, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-31 09:47:06.862144');
INSERT INTO public.auditoria VALUES (204, 'recursos', 117, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-08-31 10:08:06.559751');
INSERT INTO public.auditoria VALUES (205, 'recursos', 44, 'DELETE', NULL, NULL, '{"titulo": "auuuu", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:33.675276');
INSERT INTO public.auditoria VALUES (206, 'recursos', 43, 'DELETE', NULL, NULL, '{"titulo": "123123123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:35.310918');
INSERT INTO public.auditoria VALUES (207, 'recursos', 42, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:36.983224');
INSERT INTO public.auditoria VALUES (208, 'recursos', 41, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:38.628174');
INSERT INTO public.auditoria VALUES (209, 'recursos', 40, 'DELETE', NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:40.044516');
INSERT INTO public.auditoria VALUES (210, 'recursos', 39, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:41.532657');
INSERT INTO public.auditoria VALUES (211, 'recursos', 21, 'DELETE', NULL, NULL, '{"titulo": "La Bebecita Bebelin", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:43.99534');
INSERT INTO public.auditoria VALUES (212, 'recursos', 38, 'DELETE', NULL, NULL, '{"titulo": "23", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:45.967422');
INSERT INTO public.auditoria VALUES (213, 'recursos', 37, 'DELETE', NULL, NULL, '{"titulo": "23123", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:47.549688');
INSERT INTO public.auditoria VALUES (214, 'recursos', 36, 'DELETE', NULL, NULL, '{"titulo": "Waos 3", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:49.179948');
INSERT INTO public.auditoria VALUES (215, 'recursos', 35, 'DELETE', NULL, NULL, '{"titulo": "Waos 2", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:50.605605');
INSERT INTO public.auditoria VALUES (216, 'recursos', 34, 'DELETE', NULL, NULL, '{"titulo": "Waos 1", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:52.414388');
INSERT INTO public.auditoria VALUES (217, 'recursos', 32, 'DELETE', NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:53.763311');
INSERT INTO public.auditoria VALUES (218, 'recursos', 31, 'DELETE', NULL, NULL, '{"titulo": "Imitadora", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:55.392376');
INSERT INTO public.auditoria VALUES (219, 'recursos', 28, 'DELETE', NULL, NULL, '{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:21:57.099607');
INSERT INTO public.auditoria VALUES (220, 'recursos', 27, 'DELETE', NULL, NULL, '{"titulo": "Que la guagua", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:29.21399');
INSERT INTO public.auditoria VALUES (221, 'recursos', 25, 'DELETE', NULL, NULL, '{"titulo": "Luisito comunicando", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:30.866944');
INSERT INTO public.auditoria VALUES (222, 'recursos', 24, 'DELETE', NULL, NULL, '{"titulo": "Pepe", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:22:32.369068');
INSERT INTO public.auditoria VALUES (223, 'recursos', 118, 'INSERT', NULL, NULL, NULL, '{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (224, 'recursos', 119, 'INSERT', NULL, NULL, NULL, '{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (225, 'recursos', 120, 'INSERT', NULL, NULL, NULL, '{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (226, 'recursos', 121, 'INSERT', NULL, NULL, NULL, '{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (227, 'recursos', 122, 'INSERT', NULL, NULL, NULL, '{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:23:21.823509');
INSERT INTO public.auditoria VALUES (228, 'recursos', 123, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 00:30:00.179124');
INSERT INTO public.auditoria VALUES (229, 'recursos', 123, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-02 00:38:17.131525');
INSERT INTO public.auditoria VALUES (233, 'recursos', 127, 'INSERT', NULL, NULL, NULL, '{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 15:10:22.258855');
INSERT INTO public.auditoria VALUES (235, 'recursos', 129, 'INSERT', NULL, NULL, NULL, '{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-02 17:24:52.328736');
INSERT INTO public.auditoria VALUES (236, 'recursos', 130, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 18:00:56.314983');
INSERT INTO public.auditoria VALUES (237, 'recursos', 131, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-02 18:02:41.164769');
INSERT INTO public.auditoria VALUES (240, 'recursos', 132, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-04 10:35:11.057661');
INSERT INTO public.auditoria VALUES (241, 'recursos', 133, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:50:26.015403');
INSERT INTO public.auditoria VALUES (242, 'recursos', 134, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:52:40.315328');
INSERT INTO public.auditoria VALUES (243, 'recursos', 134, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 13:53:04.485629');
INSERT INTO public.auditoria VALUES (244, 'recursos', 133, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 13:53:06.306206');
INSERT INTO public.auditoria VALUES (245, 'recursos', 135, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 13:54:05.876851');
INSERT INTO public.auditoria VALUES (246, 'recursos', 136, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:19:43.111334');
INSERT INTO public.auditoria VALUES (247, 'recursos', 137, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:22:18.231644');
INSERT INTO public.auditoria VALUES (248, 'recursos', 138, 'INSERT', NULL, NULL, NULL, '{"titulo": "w", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 14:23:15.04343');
INSERT INTO public.auditoria VALUES (249, 'recursos', 139, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 22:47:39.66015');
INSERT INTO public.auditoria VALUES (250, 'recursos', 140, 'INSERT', NULL, NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-04 23:26:52.51645');
INSERT INTO public.auditoria VALUES (251, 'recursos', 136, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 23:56:47.967712');
INSERT INTO public.auditoria VALUES (252, 'recursos', 135, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-04 23:57:41.917589');
INSERT INTO public.auditoria VALUES (253, 'recursos', 141, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-05 00:06:53.601656');
INSERT INTO public.auditoria VALUES (254, 'recursos', 141, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:32.107364');
INSERT INTO public.auditoria VALUES (255, 'recursos', 140, 'DELETE', NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:38.352733');
INSERT INTO public.auditoria VALUES (256, 'recursos', 139, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:40.723753');
INSERT INTO public.auditoria VALUES (257, 'recursos', 138, 'DELETE', NULL, NULL, '{"titulo": "w", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:43.029266');
INSERT INTO public.auditoria VALUES (258, 'recursos', 137, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-05 00:23:45.640379');
INSERT INTO public.auditoria VALUES (259, 'recursos', 142, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-07 15:37:00.278657');
INSERT INTO public.auditoria VALUES (260, 'recursos', 142, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-07 15:37:48.807771');
INSERT INTO public.auditoria VALUES (261, 'recursos', 143, 'INSERT', NULL, NULL, NULL, '{"titulo": "Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-07 15:40:32.098085');
INSERT INTO public.auditoria VALUES (262, 'recursos', 144, 'INSERT', NULL, NULL, NULL, '{"titulo": "Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:24:54.886595');
INSERT INTO public.auditoria VALUES (263, 'recursos', 145, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:36:32.789579');
INSERT INTO public.auditoria VALUES (264, 'recursos', 145, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-09 00:49:28.135237');
INSERT INTO public.auditoria VALUES (265, 'recursos', 146, 'INSERT', NULL, NULL, NULL, '{"titulo": "Modelamiento de confort adaptativo para un trapiche panelero", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-09 00:51:39.29162');
INSERT INTO public.auditoria VALUES (266, 'usuarios', 12, 'INSERT', NULL, NULL, NULL, '{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-09 19:26:21.824338');
INSERT INTO public.auditoria VALUES (267, 'recursos', 129, 'DELETE', NULL, NULL, '{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1}', NULL, '2026-09-09 23:34:35.112448');
INSERT INTO public.auditoria VALUES (268, 'usuarios', 1, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Adrus"}', '{"activo": true, "id_rol": 1, "nombre": "Adrus"}', '2026-09-10 11:09:57.334');
INSERT INTO public.auditoria VALUES (269, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '2026-09-10 11:33:11.894408');
INSERT INTO public.auditoria VALUES (270, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-10 11:39:27.139879');
INSERT INTO public.auditoria VALUES (271, 'recursos', 147, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:31.80467');
INSERT INTO public.auditoria VALUES (272, 'recursos', 148, 'INSERT', NULL, NULL, NULL, '{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:31.979685');
INSERT INTO public.auditoria VALUES (273, 'recursos', 149, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-10 21:02:32.217243');
INSERT INTO public.auditoria VALUES (274, 'recursos', 150, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-11 11:59:24.004409');
INSERT INTO public.auditoria VALUES (275, 'recursos', 151, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-09-11 15:27:57.58139');
INSERT INTO public.auditoria VALUES (276, 'usuarios', 13, 'INSERT', NULL, NULL, NULL, '{"email": "cepillin@gmail.com", "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 15:37:03.687571');
INSERT INTO public.auditoria VALUES (277, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 16:33:03.211027');
INSERT INTO public.auditoria VALUES (278, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:08.737789');
INSERT INTO public.auditoria VALUES (279, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:13.015607');
INSERT INTO public.auditoria VALUES (280, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:17.017251');
INSERT INTO public.auditoria VALUES (281, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:25.348502');
INSERT INTO public.auditoria VALUES (282, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:33:30.152351');
INSERT INTO public.auditoria VALUES (283, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 16:36:55.505126');
INSERT INTO public.auditoria VALUES (302, 'usuarios', 14, 'INSERT', NULL, NULL, NULL, '{"email": "676767@gmail.com", "id_rol": 3, "nombre": "Sixsevenaldo González"}', '2026-09-12 18:33:01.584383');
INSERT INTO public.auditoria VALUES (284, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 16:36:59.169874');
INSERT INTO public.auditoria VALUES (285, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:03.711934');
INSERT INTO public.auditoria VALUES (286, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:10.689659');
INSERT INTO public.auditoria VALUES (287, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 16:37:17.568688');
INSERT INTO public.auditoria VALUES (288, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 17:40:06.161413');
INSERT INTO public.auditoria VALUES (289, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '2026-09-11 17:41:47.64796');
INSERT INTO public.auditoria VALUES (290, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 17:41:50.689906');
INSERT INTO public.auditoria VALUES (291, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:49.699268');
INSERT INTO public.auditoria VALUES (292, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:53.227644');
INSERT INTO public.auditoria VALUES (293, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:55.198334');
INSERT INTO public.auditoria VALUES (294, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '2026-09-11 22:36:57.977504');
INSERT INTO public.auditoria VALUES (295, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": false, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 22:40:14.652503');
INSERT INTO public.auditoria VALUES (296, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '2026-09-11 22:40:16.809997');
INSERT INTO public.auditoria VALUES (297, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillín"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:49:57.897673');
INSERT INTO public.auditoria VALUES (298, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:01.579119');
INSERT INTO public.auditoria VALUES (299, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:11.080621');
INSERT INTO public.auditoria VALUES (300, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:16.912961');
INSERT INTO public.auditoria VALUES (301, 'usuarios', 13, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}', '{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}', '2026-09-11 22:50:18.90332');
INSERT INTO public.auditoria VALUES (303, 'usuarios', 15, 'INSERT', NULL, NULL, NULL, '{"email": "DIOS@gmail.com", "id_rol": 1, "nombre": "DIOS"}', '2026-09-13 23:57:20.408394');
INSERT INTO public.auditoria VALUES (304, 'usuarios', 16, 'INSERT', NULL, NULL, NULL, '{"email": "orlando5711666@gmail.com", "id_rol": 3, "nombre": "no soy miguel"}', '2026-09-16 22:56:54.613604');
INSERT INTO public.auditoria VALUES (305, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}', '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '2026-09-19 14:19:20.874131');
INSERT INTO public.auditoria VALUES (306, 'usuarios', 12, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}', '{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}', '2026-09-19 14:55:59.004653');
INSERT INTO public.auditoria VALUES (307, 'usuarios', 17, 'INSERT', NULL, NULL, NULL, '{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "30469331"}', '2026-09-19 14:57:10.314214');
INSERT INTO public.auditoria VALUES (308, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "30469331"}', '{"activo": true, "id_rol": 3, "nombre": "adru"}', '2026-09-19 14:59:29.871771');
INSERT INTO public.auditoria VALUES (309, 'usuarios', 15, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "DIOS"}', '{"activo": true, "id_rol": 1, "nombre": "DIOSs"}', '2026-09-19 15:13:32.480165');
INSERT INTO public.auditoria VALUES (310, 'usuarios', 15, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "DIOSs"}', '{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}', '2026-09-19 15:13:42.378327');
INSERT INTO public.auditoria VALUES (311, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "adru"}', '{"activo": true, "id_rol": 3, "nombre": "adruss"}', '2026-09-19 15:19:07.37854');
INSERT INTO public.auditoria VALUES (312, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "adruss"}', '{"activo": true, "id_rol": 2, "nombre": "adruss"}', '2026-09-19 15:19:14.229116');
INSERT INTO public.auditoria VALUES (313, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "adruss"}', '{"activo": true, "id_rol": 2, "nombre": "adrusss"}', '2026-09-20 16:36:49.932377');
INSERT INTO public.auditoria VALUES (314, 'recursos', 152, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-23 11:21:36.922266');
INSERT INTO public.auditoria VALUES (315, 'recursos', 152, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 11:22:57.677896');
INSERT INTO public.auditoria VALUES (316, 'recursos', 153, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}', '2026-09-23 11:39:58.402099');
INSERT INTO public.auditoria VALUES (317, 'recursos', 153, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 11:45:02.317989');
INSERT INTO public.auditoria VALUES (318, 'recursos', 156, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', '2026-09-23 12:09:57.549672');
INSERT INTO public.auditoria VALUES (319, 'recursos', 157, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', '2026-09-23 12:10:19.262043');
INSERT INTO public.auditoria VALUES (320, 'recursos', 157, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-23 12:29:33.377305');
INSERT INTO public.auditoria VALUES (321, 'recursos', 158, 'INSERT', NULL, NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', '2026-09-23 12:29:57.034447');
INSERT INTO public.auditoria VALUES (322, 'recursos', 159, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', '2026-09-25 23:45:29.603658');
INSERT INTO public.auditoria VALUES (323, 'recursos', 160, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}', '2026-09-25 23:47:32.841904');
INSERT INTO public.auditoria VALUES (324, 'recursos', 160, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}', NULL, '2026-09-25 23:47:32.870735');
INSERT INTO public.auditoria VALUES (325, 'recursos', 161, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-26 00:01:30.60975');
INSERT INTO public.auditoria VALUES (326, 'recursos', 162, 'INSERT', NULL, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', '2026-09-26 00:02:21.96766');
INSERT INTO public.auditoria VALUES (327, 'recursos', 163, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-26 00:03:12.854033');
INSERT INTO public.auditoria VALUES (328, 'usuarios', 19, 'INSERT', NULL, NULL, NULL, '{"email": "juanxzall0701@gmail.com", "id_rol": 2, "nombre": "Juan Salcedo"}', '2026-09-28 20:05:54.593867');
INSERT INTO public.auditoria VALUES (329, 'recursos', 162, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:20:08.135505');
INSERT INTO public.auditoria VALUES (330, 'recursos', 163, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:20:14.51703');
INSERT INTO public.auditoria VALUES (331, 'recursos', 147, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:22:34.941428');
INSERT INTO public.auditoria VALUES (332, 'recursos', 117, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:23:10.410984');
INSERT INTO public.auditoria VALUES (333, 'recursos', 159, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:27:16.267346');
INSERT INTO public.auditoria VALUES (334, 'recursos', 158, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-28 20:27:18.567257');
INSERT INTO public.auditoria VALUES (335, 'recursos', 164, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-28 23:19:51.125311');
INSERT INTO public.auditoria VALUES (336, 'recursos', 1, 'DELETE', NULL, NULL, '{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (337, 'recursos', 2, 'DELETE', NULL, NULL, '{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (338, 'recursos', 6, 'DELETE', NULL, NULL, '{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (339, 'recursos', 7, 'DELETE', NULL, NULL, '{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (340, 'recursos', 8, 'DELETE', NULL, NULL, '{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (341, 'recursos', 15, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 2", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (342, 'recursos', 17, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 5", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (343, 'recursos', 18, 'DELETE', NULL, NULL, '{"titulo": "Criptografía Cuántica Post-RSA - Parte 8", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (344, 'recursos', 19, 'DELETE', NULL, NULL, '{"titulo": "Software Libre para Bibliotecas - Parte 6", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (345, 'recursos', 45, 'DELETE', NULL, NULL, '{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (346, 'recursos', 46, 'DELETE', NULL, NULL, '{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (347, 'recursos', 47, 'DELETE', NULL, NULL, '{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (348, 'recursos', 48, 'DELETE', NULL, NULL, '{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (349, 'recursos', 50, 'DELETE', NULL, NULL, '{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (350, 'recursos', 51, 'DELETE', NULL, NULL, '{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (351, 'recursos', 52, 'DELETE', NULL, NULL, '{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (352, 'recursos', 57, 'DELETE', NULL, NULL, '{"titulo": "hola adios", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (353, 'recursos', 58, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (354, 'recursos', 59, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (355, 'recursos', 69, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (356, 'recursos', 72, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (357, 'recursos', 78, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (358, 'recursos', 79, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (359, 'recursos', 80, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (360, 'recursos', 81, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (426, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:41.676708');
INSERT INTO public.auditoria VALUES (361, 'recursos', 82, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (362, 'recursos', 83, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (363, 'recursos', 84, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (364, 'recursos', 85, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (365, 'recursos', 86, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (366, 'recursos', 87, 'DELETE', NULL, NULL, '{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (367, 'recursos', 88, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (368, 'recursos', 89, 'DELETE', NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (369, 'recursos', 90, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (370, 'recursos', 91, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (371, 'recursos', 94, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (372, 'recursos', 93, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (373, 'recursos', 92, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (374, 'recursos', 112, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (375, 'recursos', 97, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (376, 'recursos', 99, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (377, 'recursos', 100, 'DELETE', NULL, NULL, '{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (378, 'recursos', 108, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (379, 'recursos', 109, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (380, 'recursos', 110, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (381, 'recursos', 111, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (382, 'recursos', 113, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (383, 'recursos', 114, 'DELETE', NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (384, 'recursos', 116, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (385, 'recursos', 127, 'DELETE', NULL, NULL, '{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (386, 'recursos', 132, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (387, 'recursos', 148, 'DELETE', NULL, NULL, '{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (388, 'recursos', 149, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (389, 'recursos', 49, 'DELETE', NULL, NULL, '{"titulo": "Sistema de Información Automatizado para la Gestión de Inventario y Suministros Médicos", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (390, 'recursos', 161, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (391, 'recursos', 128, 'DELETE', NULL, NULL, '{"titulo": "Materia: Seguridad Informáticasssssss", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (392, 'recursos', 164, 'DELETE', NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (393, 'recursos', 173, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (394, 'recursos', 174, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (395, 'recursos', 175, 'INSERT', NULL, NULL, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (396, 'recursos', 176, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (397, 'recursos', 177, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (398, 'recursos', 178, 'INSERT', NULL, NULL, NULL, '{"titulo": "DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (399, 'recursos', 179, 'INSERT', NULL, NULL, NULL, '{"titulo": "CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (400, 'recursos', 180, 'INSERT', NULL, NULL, NULL, '{"titulo": "Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda ''Tuti'' del Cantón Vinces", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (401, 'recursos', 181, 'INSERT', NULL, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}', '2026-09-28 23:32:21.596096');
INSERT INTO public.auditoria VALUES (402, 'recursos', 181, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}', NULL, '2026-09-28 23:36:50.181089');
INSERT INTO public.auditoria VALUES (403, 'recursos', 182, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:55:27.29017');
INSERT INTO public.auditoria VALUES (404, 'recursos', 183, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-28 23:55:52.326738');
INSERT INTO public.auditoria VALUES (405, 'recursos', 183, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:18:32.939693');
INSERT INTO public.auditoria VALUES (406, 'recursos', 182, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:18:42.813407');
INSERT INTO public.auditoria VALUES (407, 'recursos', 184, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-29 00:48:27.834532');
INSERT INTO public.auditoria VALUES (408, 'recursos', 184, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:48:57.405027');
INSERT INTO public.auditoria VALUES (409, 'recursos', 185, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-09-29 00:55:08.390241');
INSERT INTO public.auditoria VALUES (410, 'recursos', 185, 'DELETE', NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-09-29 00:55:19.687702');
INSERT INTO public.auditoria VALUES (411, 'recursos', 186, 'INSERT', NULL, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-09-30 19:49:51.452795');
INSERT INTO public.auditoria VALUES (412, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}', '2026-10-03 12:40:18.137748');
INSERT INTO public.auditoria VALUES (413, 'usuarios', 2, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "lando"}', '{"activo": true, "id_rol": 2, "nombre": "lando"}', '2026-10-03 12:44:23.513489');
INSERT INTO public.auditoria VALUES (414, 'usuarios', 2, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "lando"}', '{"activo": true, "id_rol": 2, "nombre": "lando"}', '2026-10-03 12:44:23.520783');
INSERT INTO public.auditoria VALUES (415, 'usuarios', 20, 'INSERT', NULL, NULL, NULL, '{"email": "qa_test_ciidi_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:23.792227');
INSERT INTO public.auditoria VALUES (416, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:23.796502');
INSERT INTO public.auditoria VALUES (417, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:23.798405');
INSERT INTO public.auditoria VALUES (418, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:23.800171');
INSERT INTO public.auditoria VALUES (419, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:24.555246');
INSERT INTO public.auditoria VALUES (420, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', '2026-10-03 12:44:24.804573');
INSERT INTO public.auditoria VALUES (421, 'usuarios', 20, 'DELETE', NULL, NULL, '{"email": "qa_nuevo_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', NULL, '2026-10-03 12:44:24.813668');
INSERT INTO public.auditoria VALUES (422, 'usuarios', 3, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "miki"}', '{"activo": true, "id_rol": 3, "nombre": "miki"}', '2026-10-03 12:44:41.401758');
INSERT INTO public.auditoria VALUES (423, 'usuarios', 3, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "miki"}', '{"activo": true, "id_rol": 3, "nombre": "miki"}', '2026-10-03 12:44:41.408021');
INSERT INTO public.auditoria VALUES (424, 'usuarios', 21, 'INSERT', NULL, NULL, NULL, '{"email": "qa_test_ciidi_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:41.673185');
INSERT INTO public.auditoria VALUES (425, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}', '2026-10-03 12:44:41.674697');
INSERT INTO public.auditoria VALUES (427, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:41.679952');
INSERT INTO public.auditoria VALUES (428, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '2026-10-03 12:44:42.447266');
INSERT INTO public.auditoria VALUES (429, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}', '{"activo": true, "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', '2026-10-03 12:44:42.693185');
INSERT INTO public.auditoria VALUES (430, 'usuarios', 21, 'DELETE', NULL, NULL, '{"email": "qa_nuevo_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin''; DROP TABLE dummy_test; --"}', NULL, '2026-10-03 12:44:42.704726');
INSERT INTO public.auditoria VALUES (431, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:27:37.178154');
INSERT INTO public.auditoria VALUES (432, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:28:47.02622');
INSERT INTO public.auditoria VALUES (433, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:28:47.031874');
INSERT INTO public.auditoria VALUES (434, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:04.750162');
INSERT INTO public.auditoria VALUES (435, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:04.755525');
INSERT INTO public.auditoria VALUES (436, 'usuarios', 7, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '{"activo": true, "id_rol": 1, "nombre": "Miguel González"}', '2026-10-03 13:29:10.8743');
INSERT INTO public.auditoria VALUES (454, 'usuarios', 17, 'UPDATE', 7, NULL, '{"activo": true, "id_rol": 2, "nombre": "adrusss"}', '{"activo": true, "id_rol": 3, "nombre": "adrusss"}', '2026-10-16 20:13:30.803312');
INSERT INTO public.auditoria VALUES (438, 'recursos', 188, 'INSERT', NULL, NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}', '2026-10-04 14:20:31.26127');
INSERT INTO public.auditoria VALUES (439, 'recursos', 188, 'DELETE', NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}', NULL, '2026-10-04 14:20:31.322724');
INSERT INTO public.auditoria VALUES (440, 'recursos', 189, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 14:23:02.803018');
INSERT INTO public.auditoria VALUES (441, 'recursos', 190, 'INSERT', NULL, NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}', '2026-10-04 14:31:09.763754');
INSERT INTO public.auditoria VALUES (442, 'recursos', 190, 'DELETE', NULL, NULL, '{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}', NULL, '2026-10-04 14:31:09.821791');
INSERT INTO public.auditoria VALUES (443, 'recursos', 191, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 16:43:25.694943');
INSERT INTO public.auditoria VALUES (444, 'recursos', 192, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', '2026-10-04 17:14:02.756345');
INSERT INTO public.auditoria VALUES (455, 'recursos', 201, 'DELETE', 7, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la asdasdasdasdCoordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:09.98018');
INSERT INTO public.auditoria VALUES (456, 'recursos', 200, 'DELETE', 7, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dassadasdasdasdassadassadasasdasdasdase Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:14.557908');
INSERT INTO public.auditoria VALUES (457, 'recursos', 192, 'DELETE', 7, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:18.189329');
INSERT INTO public.auditoria VALUES (458, 'recursos', 186, 'DELETE', 7, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:21.492886');
INSERT INTO public.auditoria VALUES (459, 'recursos', 179, 'DELETE', 7, NULL, '{"titulo": "CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:25.604586');
INSERT INTO public.auditoria VALUES (460, 'recursos', 178, 'DELETE', 7, NULL, '{"titulo": "DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:28.864101');
INSERT INTO public.auditoria VALUES (461, 'recursos', 177, 'DELETE', 7, NULL, '{"titulo": "Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:32.044339');
INSERT INTO public.auditoria VALUES (452, 'recursos', 200, 'INSERT', NULL, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dasdasdase Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', '2026-10-10 00:00:49.200289');
INSERT INTO public.auditoria VALUES (453, 'recursos', 201, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la asdasdasdasdCoordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', '2026-10-13 22:11:55.770891');
INSERT INTO public.auditoria VALUES (462, 'recursos', 176, 'DELETE', 7, NULL, '{"titulo": "Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:35.579891');
INSERT INTO public.auditoria VALUES (463, 'recursos', 175, 'DELETE', 7, NULL, '{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:41.277284');
INSERT INTO public.auditoria VALUES (464, 'recursos', 174, 'DELETE', 7, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:45.112588');
INSERT INTO public.auditoria VALUES (465, 'recursos', 191, 'DELETE', 7, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:49.530636');
INSERT INTO public.auditoria VALUES (466, 'recursos', 189, 'DELETE', 7, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:53.100682');
INSERT INTO public.auditoria VALUES (467, 'recursos', 180, 'DELETE', 7, NULL, '{"titulo": "Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda ''Tuti'' del Cantón Vinces", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:56.263901');
INSERT INTO public.auditoria VALUES (468, 'recursos', 173, 'DELETE', 7, NULL, '{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}', NULL, '2026-10-17 09:53:59.950116');
INSERT INTO public.auditoria VALUES (469, 'recursos', 202, 'INSERT', 7, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-10-17 13:12:06.933664');
INSERT INTO public.auditoria VALUES (470, 'usuarios', 22, 'INSERT', NULL, NULL, NULL, '{"email": "josex@gmail.com", "id_rol": 1, "nombre": "Josex"}', '2026-10-17 14:03:52.085756');
INSERT INTO public.auditoria VALUES (471, 'recursos', 202, 'DELETE', 7, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', NULL, '2026-10-18 19:16:40.008056');
INSERT INTO public.auditoria VALUES (473, 'recursos', 204, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 20:33:22.427568');
INSERT INTO public.auditoria VALUES (474, 'recursos', 204, 'DELETE', 7, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 20:34:47.435694');
INSERT INTO public.auditoria VALUES (475, 'recursos', 205, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 20:35:32.631083');
INSERT INTO public.auditoria VALUES (476, 'recursos', 205, 'DELETE', 7, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:09:24.828584');
INSERT INTO public.auditoria VALUES (477, 'recursos', 206, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 21:09:39.684875');
INSERT INTO public.auditoria VALUES (478, 'recursos', 206, 'DELETE', 7, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:09:47.802713');
INSERT INTO public.auditoria VALUES (479, 'recursos', 207, 'INSERT', 7, NULL, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', '2026-10-19 21:13:56.24056');
INSERT INTO public.auditoria VALUES (480, 'recursos', 207, 'DELETE', 7, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:14:27.810834');
INSERT INTO public.auditoria VALUES (481, 'recursos', 208, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', '2026-10-19 21:31:39.28699');
INSERT INTO public.auditoria VALUES (482, 'recursos', 209, 'INSERT', 7, NULL, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', '2026-10-19 21:31:48.517089');
INSERT INTO public.auditoria VALUES (483, 'recursos', 210, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', '2026-10-19 21:32:25.000486');
INSERT INTO public.auditoria VALUES (484, 'recursos', 210, 'DELETE', 7, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:32:39.428566');
INSERT INTO public.auditoria VALUES (485, 'recursos', 209, 'DELETE', 7, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:32:39.469282');
INSERT INTO public.auditoria VALUES (486, 'recursos', 208, 'DELETE', 7, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:32:39.474869');
INSERT INTO public.auditoria VALUES (487, 'recursos', 211, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', '2026-10-19 21:45:22.288699');
INSERT INTO public.auditoria VALUES (488, 'recursos', 212, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 21:45:35.162439');
INSERT INTO public.auditoria VALUES (489, 'recursos', 213, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', '2026-10-19 21:45:45.112333');
INSERT INTO public.auditoria VALUES (490, 'recursos', 214, 'INSERT', 7, NULL, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', '2026-10-19 21:45:47.108782');
INSERT INTO public.auditoria VALUES (491, 'recursos', 215, 'INSERT', 7, NULL, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', '2026-10-19 21:45:49.232372');
INSERT INTO public.auditoria VALUES (492, 'recursos', 215, 'DELETE', 7, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:45:57.14579');
INSERT INTO public.auditoria VALUES (493, 'recursos', 214, 'DELETE', 7, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:45:57.188458');
INSERT INTO public.auditoria VALUES (494, 'recursos', 213, 'DELETE', 7, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:45:57.194575');
INSERT INTO public.auditoria VALUES (495, 'recursos', 212, 'DELETE', 7, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:45:57.201933');
INSERT INTO public.auditoria VALUES (496, 'recursos', 211, 'DELETE', 7, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:45:57.208165');
INSERT INTO public.auditoria VALUES (497, 'recursos', 216, 'INSERT', 7, NULL, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', '2026-10-19 21:46:30.983261');
INSERT INTO public.auditoria VALUES (498, 'recursos', 217, 'INSERT', 7, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 21:46:34.154264');
INSERT INTO public.auditoria VALUES (499, 'recursos', 218, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', '2026-10-19 21:46:39.030521');
INSERT INTO public.auditoria VALUES (500, 'recursos', 219, 'INSERT', 7, NULL, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', '2026-10-19 21:46:39.170435');
INSERT INTO public.auditoria VALUES (501, 'recursos', 220, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', '2026-10-19 21:46:39.317173');
INSERT INTO public.auditoria VALUES (502, 'recursos', 220, 'DELETE', 7, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:46:48.541856');
INSERT INTO public.auditoria VALUES (503, 'recursos', 219, 'DELETE', 7, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:46:48.585683');
INSERT INTO public.auditoria VALUES (504, 'recursos', 218, 'DELETE', 7, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:46:48.592913');
INSERT INTO public.auditoria VALUES (505, 'recursos', 217, 'DELETE', 7, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:46:48.601524');
INSERT INTO public.auditoria VALUES (506, 'recursos', 216, 'DELETE', 7, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:46:48.607264');
INSERT INTO public.auditoria VALUES (507, 'recursos', 221, 'INSERT', 7, NULL, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', '2026-10-19 21:47:40.589408');
INSERT INTO public.auditoria VALUES (508, 'recursos', 222, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', '2026-10-19 21:47:40.834959');
INSERT INTO public.auditoria VALUES (509, 'recursos', 223, 'INSERT', 7, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', '2026-10-19 21:47:40.984652');
INSERT INTO public.auditoria VALUES (510, 'recursos', 223, 'DELETE', 7, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:47:55.219662');
INSERT INTO public.auditoria VALUES (511, 'recursos', 222, 'DELETE', 7, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:47:55.258511');
INSERT INTO public.auditoria VALUES (512, 'recursos', 221, 'DELETE', 7, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', NULL, '2026-10-19 21:47:55.266024');
INSERT INTO public.auditoria VALUES (513, 'usuarios', 17, 'UPDATE', 7, NULL, '{"activo": true, "id_rol": 3, "nombre": "adrusss"}', '{"activo": true, "id_rol": 1, "nombre": "adrusss"}', '2026-10-19 22:17:35.855678');
INSERT INTO public.auditoria VALUES (514, 'usuarios', 4, 'UPDATE', 7, NULL, '{"activo": true, "id_rol": 3, "nombre": "ale"}', '{"activo": true, "id_rol": 1, "nombre": "ale"}', '2026-10-19 22:18:28.248593');
INSERT INTO public.auditoria VALUES (515, 'usuarios', 4, 'UPDATE', 4, NULL, '{"activo": true, "id_rol": 1, "nombre": "ale"}', '{"activo": true, "id_rol": 1, "nombre": "Ale"}', '2026-10-19 22:19:05.104959');
INSERT INTO public.auditoria VALUES (516, 'usuarios', 17, 'UPDATE', 17, NULL, '{"activo": true, "id_rol": 1, "nombre": "adrusss"}', '{"activo": true, "id_rol": 1, "nombre": "Andrus Ramirez"}', '2026-10-19 22:19:09.355756');
INSERT INTO public.auditoria VALUES (517, 'recursos', 224, 'INSERT', 17, NULL, NULL, '{"titulo": "FORTALECIMIENTO DEL APRENDIZAJE DE CASTELLANO Y LITERATURA MEDIANTE UNA PLATAFORMA EDUCATIVA PARA LOS ESTUDIANTES DE PRIMER AÑO DEL LICEO \"DR. REGULO BURELLI RIVAS", "id_tipo_recurso": 1}', '2026-10-19 22:30:39.179226');
INSERT INTO public.auditoria VALUES (518, 'recursos', 225, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}', '2026-10-19 22:32:08.310709');
INSERT INTO public.auditoria VALUES (519, 'recursos', 226, 'INSERT', 17, NULL, NULL, '{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}', '2026-10-19 22:36:58.090301');
INSERT INTO public.auditoria VALUES (520, 'recursos', 227, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}', '2026-10-19 22:37:05.825892');
INSERT INTO public.auditoria VALUES (521, 'recursos', 228, 'INSERT', 17, NULL, NULL, '{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}', '2026-10-19 22:37:13.034497');
INSERT INTO public.auditoria VALUES (522, 'recursos', 229, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}', '2026-10-19 22:37:13.125266');
INSERT INTO public.auditoria VALUES (523, 'recursos', 230, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO", "id_tipo_recurso": 1}', '2026-10-19 22:53:13.201708');
INSERT INTO public.auditoria VALUES (524, 'recursos', 231, 'INSERT', 17, NULL, NULL, '{"titulo": "IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1}', '2026-10-19 22:53:22.821568');
INSERT INTO public.auditoria VALUES (525, 'recursos', 232, 'INSERT', 17, NULL, NULL, '{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}', '2026-10-19 22:53:24.908561');
INSERT INTO public.auditoria VALUES (526, 'recursos', 233, 'INSERT', 17, NULL, NULL, '{"titulo": "Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega", "id_tipo_recurso": 1}', '2026-10-19 22:53:26.807178');
INSERT INTO public.auditoria VALUES (527, 'recursos', 234, 'INSERT', 17, NULL, NULL, '{"titulo": "Implementación de una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo", "id_tipo_recurso": 1}', '2026-10-19 22:53:29.323203');
INSERT INTO public.auditoria VALUES (528, 'recursos', 235, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}', '2026-10-19 22:55:36.412384');
INSERT INTO public.auditoria VALUES (529, 'recursos', 236, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema de Información Automatizado para la Gestión Académica del Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera", "id_tipo_recurso": 1}', '2026-10-19 22:56:48.560733');
INSERT INTO public.auditoria VALUES (530, 'recursos', 237, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF", "id_tipo_recurso": 1}', '2026-10-19 22:57:32.71912');
INSERT INTO public.auditoria VALUES (531, 'recursos', 238, 'INSERT', 17, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA OPTIMIZAR LA GESTIÓN DE PRODUCTOS Y SERVICIOS EN SUPER KAUCHOS GILSA, C.A", "id_tipo_recurso": 1}', '2026-10-19 22:58:21.059322');
INSERT INTO public.auditoria VALUES (532, 'recursos', 239, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE ADMINISTRACION DE CASOS (SIAC) EN LA PREFECTURA DEL MUNICIPIO ESCUQUE", "id_tipo_recurso": 1}', '2026-10-19 22:59:48.848881');
INSERT INTO public.auditoria VALUES (533, 'recursos', 240, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA INTEGRAL DE CONTROL DE PAGO DE ARANCELES DEL IUNE - VALERA", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.118571');
INSERT INTO public.auditoria VALUES (534, 'recursos', 241, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA PARA LA DIGITALIZACION Y GESTION DE SERVICIOS DE EQUIPOS EN TECNOSERVICE DE MIGUEL MORALES F.P", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.229604');
INSERT INTO public.auditoria VALUES (535, 'recursos', 242, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL\"", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.289094');
INSERT INTO public.auditoria VALUES (536, 'recursos', 243, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS)", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.359099');
INSERT INTO public.auditoria VALUES (537, 'recursos', 244, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA LA COOPERATIVA “LA ONCE CON LA ONCE ”, MUNICIPIO VALERA, ESTADO TRUJILLO", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.42327');
INSERT INTO public.auditoria VALUES (538, 'recursos', 245, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE GESTIÓN Y CONTROL DE DATOS EDUCATIVOS DE LA UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.494415');
INSERT INTO public.auditoria VALUES (539, 'recursos', 246, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE GESTIÓN ESCOLAR PARA LA ESCUELA “VICENTE DE LA TORRE", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.547376');
INSERT INTO public.auditoria VALUES (540, 'recursos', 247, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.607227');
INSERT INTO public.auditoria VALUES (541, 'recursos', 248, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA EL REGISTRO Y GESTIÓN LABORAL DEL INDET(SIRGL-INDET)", "id_tipo_recurso": 1}', '2026-10-19 23:01:57.767809');
INSERT INTO public.auditoria VALUES (542, 'recursos', 249, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema de gestión de inventario Web para INME C.A", "id_tipo_recurso": 1}', '2026-10-19 23:04:04.194917');
INSERT INTO public.auditoria VALUES (543, 'recursos', 250, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO", "id_tipo_recurso": 1}', '2026-10-19 23:04:44.164147');
INSERT INTO public.auditoria VALUES (544, 'recursos', 251, 'INSERT', 17, NULL, NULL, '{"titulo": "IMPLEMENTACIÓN DE PORTAL WEB PARA LA EMPRESA PUBLIART", "id_tipo_recurso": 1}', '2026-10-19 23:05:11.101813');
INSERT INTO public.auditoria VALUES (545, 'recursos', 252, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE GESTIÓN DIGITAL PARA EL INVENTARIO, TRAZABILIDAD Y MOVIMIENTO DE BIENES EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A", "id_tipo_recurso": 1}', '2026-10-19 23:06:50.622395');
INSERT INTO public.auditoria VALUES (546, 'recursos', 253, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}', '2026-10-19 23:06:57.092771');
INSERT INTO public.auditoria VALUES (547, 'recursos', 254, 'INSERT', 4, NULL, NULL, '{"titulo": "Sistema Web de gestión integral del Talento humano para el SAPNNAET", "id_tipo_recurso": 1}', '2026-10-19 23:06:57.170925');
INSERT INTO public.auditoria VALUES (548, 'recursos', 255, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMNISTRATIVA DEL PERSONAL ADSCRITO A LA VALFOR S.A", "id_tipo_recurso": 1}', '2026-10-19 23:08:32.454978');
INSERT INTO public.auditoria VALUES (549, 'recursos', 256, 'INSERT', 17, NULL, NULL, '{"titulo": "Implementación de una aplicación web para la Unidad Educativa Hna. María Sorrosal", "id_tipo_recurso": 1}', '2026-10-19 23:10:14.893313');
INSERT INTO public.auditoria VALUES (550, 'recursos', 257, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA WEB PARA LA GESTIÓN DE PROYECTOS DE LOS CONSEJOS COMUNALES EN EL CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA", "id_tipo_recurso": 1}', '2026-10-19 23:12:05.072795');
INSERT INTO public.auditoria VALUES (551, 'recursos', 258, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas", "id_tipo_recurso": 1}', '2026-10-19 23:14:05.279033');
INSERT INTO public.auditoria VALUES (552, 'recursos', 259, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo\"", "id_tipo_recurso": 1}', '2026-10-19 23:20:21.153746');
INSERT INTO public.auditoria VALUES (553, 'recursos', 260, 'INSERT', 17, NULL, NULL, '{"titulo": "Sistema web para la farmacia INSUMEDIC C.A", "id_tipo_recurso": 1}', '2026-10-19 23:21:49.859608');
INSERT INTO public.auditoria VALUES (554, 'recursos', 261, 'INSERT', 17, NULL, NULL, '{"titulo": "DESARROLLO DE APLICACIÓN WEB PARA EL LABORATORIO CLÍNICO GENÉSIS C.A., VALERA, ESTADO TRUJILLO", "id_tipo_recurso": 1}', '2026-10-19 23:22:40.994981');
INSERT INTO public.auditoria VALUES (555, 'recursos', 262, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA \"DR. HUMBERTO FERNÁNDEZ MORÁN\"", "id_tipo_recurso": 1}', '2026-10-19 23:23:20.301903');
INSERT INTO public.auditoria VALUES (556, 'recursos', 263, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA WEB PARA LA GESTION ADMINISTRATIVA DE LA PANTERA PC STORE", "id_tipo_recurso": 1}', '2026-10-19 23:24:24.616744');
INSERT INTO public.auditoria VALUES (557, 'recursos', 264, 'INSERT', 4, NULL, NULL, '{"titulo": "MANTENIMIENTO PREVENTIVO Y CORRECTIVO A LAS COMPUTADORAS DEL CBIT EN EL COMPLEJO EDUCATIVO ‘HILARIO PIZANI ANSELMI’", "id_tipo_recurso": 1}', '2026-10-19 23:24:58.424411');
INSERT INTO public.auditoria VALUES (558, 'recursos', 265, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS EN LA ALCALDIA MUNICIPAL RAFAEL RANGEL", "id_tipo_recurso": 1}', '2026-10-19 23:24:58.575086');
INSERT INTO public.auditoria VALUES (559, 'recursos', 266, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS Y USUARIOS PARA EL INFOCENTRO GRAN BASE DE MISIONES POR LA PAZ HUGO CHAVEZ FRIAS", "id_tipo_recurso": 1}', '2026-10-19 23:24:58.673105');
INSERT INTO public.auditoria VALUES (560, 'recursos', 267, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y A USUARIOS EN DUILIO DE JESUS VALERO ROMERO PANADERIA, PASTELERIA Y LUNCHERIA “LA ESTRELLA DE VALERA”. F. P", "id_tipo_recurso": 1}', '2026-10-19 23:24:58.763374');
INSERT INTO public.auditoria VALUES (561, 'recursos', 268, 'INSERT', 4, NULL, NULL, '{"titulo": "PABLO VILORIA\" - LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A LOS USUARIOS DE EL CIBER CAFÉ PONCHO S.A", "id_tipo_recurso": 1}', '2026-10-19 23:24:58.860703');
INSERT INTO public.auditoria VALUES (562, 'recursos', 269, 'INSERT', 4, NULL, NULL, '{"titulo": "NUES “DR. PABLO VILORIA” – LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A USUARIOS DE LA UPTTMBI NUES LA BEATRIZ EN EL LABORATORIO 2 DE INFORMÁTICA", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.01139');
INSERT INTO public.auditoria VALUES (563, 'recursos', 270, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.123033');
INSERT INTO public.auditoria VALUES (564, 'recursos', 271, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA NUEVA ELOISA FONSECA", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.21358');
INSERT INTO public.auditoria VALUES (565, 'recursos', 272, 'INSERT', 4, NULL, NULL, '{"titulo": "Soporte Técnico a Equipos de Computación y usuarios en la Empresa “IP SISTEMAS", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.308045');
INSERT INTO public.auditoria VALUES (566, 'recursos', 273, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TECNICO A EQUIPOS TECNOLOGICOS Y USUARIOS DEL DESPACHO DE CARGA SUROCCIDENTE", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.418966');
INSERT INTO public.auditoria VALUES (567, 'recursos', 274, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A EQUIPOS Y USUARIOS EN LA CLINICA UGA C.A", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.537726');
INSERT INTO public.auditoria VALUES (568, 'recursos', 275, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DE LA “ESCUELA TÉCNICA COMERCIAL PEDRO GARCÍA LEAL” DEL MUNICIPIO", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.65562');
INSERT INTO public.auditoria VALUES (569, 'recursos', 276, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A USUARIOS Y EQUIPOS EN EL MINISTERIO DEL PODER POPULAR PARA EL TRANSPORTE, SEDE VALERA - TRUJILLO", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.755223');
INSERT INTO public.auditoria VALUES (570, 'recursos', 277, 'INSERT', 4, NULL, NULL, '{"titulo": "Soporte Técnico de Equipos y Capacitación a Usuarios del Laboratorio 1 de PNFI de la Universidad Politécnica Territorial ¨Mario Briceño Iragorry¨", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.88587');
INSERT INTO public.auditoria VALUES (571, 'recursos', 278, 'INSERT', 4, NULL, NULL, '{"titulo": "SOPORTE TÉCNICO A LAS COMPUTADORAS Y CAPACITACIÓN A LOS USUARIOS DEL CBIT DE LA ESCUELA DE EMPRENDIMIENTO VALERA", "id_tipo_recurso": 1}', '2026-10-19 23:24:59.986036');
INSERT INTO public.auditoria VALUES (572, 'recursos', 279, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO\"", "id_tipo_recurso": 1}', '2026-10-19 23:26:33.99831');
INSERT INTO public.auditoria VALUES (573, 'recursos', 280, 'INSERT', 17, NULL, NULL, '{"titulo": "SOFTWARE EDUCATIVO PARA LA ENSEÑANZA DE MATEMATICAS DE PRIMER AÑO EN EL LICEO “HILARIO PIZANI ANSELMI", "id_tipo_recurso": 1}', '2026-10-19 23:33:45.603171');
INSERT INTO public.auditoria VALUES (574, 'recursos', 281, 'INSERT', 17, NULL, NULL, '{"titulo": "SOFTWARE EDUCATIVO PARA MEJORAR LA ENSEÑANZA DE PROGRAMACIÓN Y DISEÑO DE SOFTWARE Y REDES EN LA E.T. \"PEDRO GARCÍA LEAL\"", "id_tipo_recurso": 1}', '2026-10-19 23:36:15.396849');
INSERT INTO public.auditoria VALUES (575, 'recursos', 282, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO", "id_tipo_recurso": 1}', '2026-10-19 23:44:28.850666');
INSERT INTO public.auditoria VALUES (576, 'recursos', 283, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB INFORMATIVA PARA EL FORTALECIMIENTO TECNOLÓGICO DEL PARQUE DE RECREACIÓN BOTAICO “LA BEATRIZ", "id_tipo_recurso": 1}', '2026-10-19 23:44:28.921378');
INSERT INTO public.auditoria VALUES (577, 'recursos', 284, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA EL COMPLEJO “EDUCATIVO PEDRO PABLO PAREDES", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.004372');
INSERT INTO public.auditoria VALUES (578, 'recursos', 285, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.078593');
INSERT INTO public.auditoria VALUES (579, 'recursos', 286, 'INSERT', 4, NULL, NULL, '{"titulo": "APLICACION WEB PARA LA UNIDAD EDUCATIVA “EDUARDO BLANCO", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.1629');
INSERT INTO public.auditoria VALUES (580, 'recursos', 287, 'INSERT', 4, NULL, NULL, '{"titulo": "Página Web en el Grupo Educativo “Eloísa Fonseca", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.235168');
INSERT INTO public.auditoria VALUES (581, 'recursos', 288, 'INSERT', 4, NULL, NULL, '{"titulo": "Página Web Institucional para la Unidad Educativa Fé y Alegría “Gonzalo Senior", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.31214');
INSERT INTO public.auditoria VALUES (582, 'recursos', 289, 'INSERT', 4, NULL, NULL, '{"titulo": "PÁGINA WEB INSTITUCIONAL PARA EL COLEGIO LOS CEDROS", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.407981');
INSERT INTO public.auditoria VALUES (583, 'recursos', 290, 'INSERT', 4, NULL, NULL, '{"titulo": "página web municipal con sección al infocentro", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.503237');
INSERT INTO public.auditoria VALUES (584, 'recursos', 291, 'INSERT', 4, NULL, NULL, '{"titulo": "PÁGINA WEB PARA EL CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.576492');
INSERT INTO public.auditoria VALUES (585, 'recursos', 292, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.667762');
INSERT INTO public.auditoria VALUES (586, 'recursos', 293, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.760518');
INSERT INTO public.auditoria VALUES (587, 'recursos', 294, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.853154');
INSERT INTO public.auditoria VALUES (588, 'recursos', 295, 'INSERT', 4, NULL, NULL, '{"titulo": "Sitio Web en la Comercializadora Francoplast C.A", "id_tipo_recurso": 1}', '2026-10-19 23:44:29.93803');
INSERT INTO public.auditoria VALUES (589, 'recursos', 296, 'INSERT', 17, NULL, NULL, '{"titulo": "MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS\"", "id_tipo_recurso": 1}', '2026-10-19 23:48:55.213995');
INSERT INTO public.auditoria VALUES (590, 'recursos', 297, 'INSERT', 17, NULL, NULL, '{"titulo": "Optimización y modernización de la red de comunicación de CORPOELEC Sucursal: Despacho de Carga Suroccidente", "id_tipo_recurso": 1}', '2026-10-19 23:50:36.672787');
INSERT INTO public.auditoria VALUES (591, 'recursos', 298, 'INSERT', 4, NULL, NULL, '{"titulo": "Sistema de control de acceso al laboratorio informático de la E.T \"Pedro García Leal", "id_tipo_recurso": 1}', '2026-10-20 00:09:16.424521');
INSERT INTO public.auditoria VALUES (592, 'recursos', 299, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO", "id_tipo_recurso": 1}', '2026-10-20 00:09:16.495903');
INSERT INTO public.auditoria VALUES (593, 'recursos', 300, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA DE INFORMACION PARA LA OPTIMIZACION DEL ACCESO Y CONTROL DE HISTORIAS MÉDICAS EN EL C.C.C. DR. JOSE GREGORIO HERNANDEZ C.A", "id_tipo_recurso": 1}', '2026-10-20 00:09:16.610421');
INSERT INTO public.auditoria VALUES (594, 'recursos', 301, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA PARA CONTROL Y SEGUIMIENTO DE ASISTENCIAS DEL PROFESORADO EN LA ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}', '2026-10-20 00:09:16.704217');
INSERT INTO public.auditoria VALUES (595, 'recursos', 302, 'INSERT', 4, NULL, NULL, '{"titulo": "SISTEMA WEB DE GESTIÓN DE VENTAS PARA INVERSIONES M.Q. DE MIRLA QUINTERO", "id_tipo_recurso": 1}', '2026-10-20 00:09:16.782033');
INSERT INTO public.auditoria VALUES (596, 'recursos', 303, 'INSERT', 17, NULL, NULL, '{"titulo": "MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS", "id_tipo_recurso": 1}', '2026-10-20 00:09:56.340865');
INSERT INTO public.auditoria VALUES (597, 'recursos', 304, 'INSERT', 17, NULL, NULL, '{"titulo": "REESTRUCTURACIÓN Y MANTENIMIENTO DE LA RED LAN EN LA DIRECCIÓN GENERAL DE PLANIFICACIÓN Y PRESUPUESTO DE LA GOBERNACIÓN DEL ESTADO TRUJILLO", "id_tipo_recurso": 1}', '2026-10-20 00:14:39.526868');
INSERT INTO public.auditoria VALUES (598, 'recursos', 305, 'INSERT', 17, NULL, NULL, '{"titulo": "SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA", "id_tipo_recurso": 1}', '2026-10-20 00:16:09.46605');


--
-- Data for Name: autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.autores VALUES (1, 'Alexandro Rosales', 'V-30737939');
INSERT INTO public.autores VALUES (2, 'Kleyderman Caceres', 'V-30671984');
INSERT INTO public.autores VALUES (3, 'Albert Hernández', 'V-30302607');
INSERT INTO public.autores VALUES (4, 'Angel Sandrea', 'V-30600555');
INSERT INTO public.autores VALUES (5, 'Edixon Angel', 'V-26235671');
INSERT INTO public.autores VALUES (6, 'Franyer Bencomo', 'V-31521223');
INSERT INTO public.autores VALUES (7, 'Raybel Segovia', 'V-29585227');
INSERT INTO public.autores VALUES (8, 'Drover Briceño', 'V-30536269');
INSERT INTO public.autores VALUES (9, 'Delgado Jesús', 'V-30600922');
INSERT INTO public.autores VALUES (10, 'González Eudis', 'V-30600439');
INSERT INTO public.autores VALUES (11, 'Aldana Guillermo', 'V-30379719');
INSERT INTO public.autores VALUES (12, 'Morillo Alonso', 'V-29874501');
INSERT INTO public.autores VALUES (13, 'Zambrano Michel', 'V-31029728');
INSERT INTO public.autores VALUES (14, 'Paredes Viloria Leopoldo José', 'V-26962142');
INSERT INTO public.autores VALUES (15, 'Ramírez Estrada Yorleidi Carmen', 'V-17604975');
INSERT INTO public.autores VALUES (16, 'Barrios Luisanny', 'V-30600603');
INSERT INTO public.autores VALUES (17, 'Bastidas Harold', 'V-28096941');
INSERT INTO public.autores VALUES (18, 'Machado Norman', 'V-30302938');
INSERT INTO public.autores VALUES (19, 'Suárez Luis', 'V-30895831');
INSERT INTO public.autores VALUES (20, 'Angerly A Gudiño W', 'V-30190127');
INSERT INTO public.autores VALUES (21, 'Andrea A Graterol A', 'V-29652280');
INSERT INTO public.autores VALUES (22, 'Abrahan A Viloria D', 'V-30600727');
INSERT INTO public.autores VALUES (23, 'Eyber E Chacon G', 'V-29932639');
INSERT INTO public.autores VALUES (24, 'Darwin J Viera M', 'V-28485043');
INSERT INTO public.autores VALUES (25, 'Higuera Jennifer', 'V-21364744');
INSERT INTO public.autores VALUES (26, 'Moreno Carlos', 'V-28079273');
INSERT INTO public.autores VALUES (27, 'Perdomo Jeslibeth', 'V-30048325');
INSERT INTO public.autores VALUES (28, 'Ramírez Andrés', 'V-27070760');
INSERT INTO public.autores VALUES (29, 'Favian Herrera', 'V-30600230');
INSERT INTO public.autores VALUES (30, 'Yohelis Colmenares', 'V-29539073');
INSERT INTO public.autores VALUES (31, 'Luciana Graterol', 'V-30438421');
INSERT INTO public.autores VALUES (32, 'Zoraly Perdomo', 'V-30475316');
INSERT INTO public.autores VALUES (33, 'Araujo Rivas Isamar Andreina', 'V-31029609');
INSERT INTO public.autores VALUES (34, 'Collantes Peña José Manuel', 'V-31602776');
INSERT INTO public.autores VALUES (35, 'León Custode María Fernanda', 'V-31094982');
INSERT INTO public.autores VALUES (36, 'Ocanto Morales ángel David', 'V-31239885');
INSERT INTO public.autores VALUES (37, 'Sáez Albornoz Eduard Alexander', 'V-30256767');
INSERT INTO public.autores VALUES (38, 'Santiago Celis', 'V-30600647');
INSERT INTO public.autores VALUES (39, 'José Quintero', 'V-31095296');
INSERT INTO public.autores VALUES (40, 'Aarón Delgado', 'V-31520627');
INSERT INTO public.autores VALUES (41, 'Hebert Morillo', 'V-16634538');
INSERT INTO public.autores VALUES (42, 'Wilfredo González', 'V-30475446');
INSERT INTO public.autores VALUES (43, 'Enmanuel Rodríguez', 'V-19898759');
INSERT INTO public.autores VALUES (44, 'Leandro Abreu', 'V-30867672');
INSERT INTO public.autores VALUES (45, 'Quevedo María', 'V-30602507');
INSERT INTO public.autores VALUES (46, 'Ruiz Abraham', 'V-30259034');
INSERT INTO public.autores VALUES (47, 'Torres Diego', 'V-30671160');
INSERT INTO public.autores VALUES (48, 'Torres Edwar', 'V-30333971');
INSERT INTO public.autores VALUES (49, 'Urbina Omar', 'V-30190456');
INSERT INTO public.autores VALUES (50, 'Cristhian Hernández', 'V-31167852');
INSERT INTO public.autores VALUES (51, 'Daniel Paredes', 'V-31239028');
INSERT INTO public.autores VALUES (52, 'Harvely Valecillos', 'V-31239111');
INSERT INTO public.autores VALUES (53, 'José Santiago', 'V-31029806');
INSERT INTO public.autores VALUES (54, 'Aldana Yaneth', 'V-11614297');
INSERT INTO public.autores VALUES (55, 'Karla Villegas', 'V-30673506');
INSERT INTO public.autores VALUES (56, 'Yennifer Paredes', 'V-31979510');
INSERT INTO public.autores VALUES (57, 'Eulises Briceño', 'V-29933293');
INSERT INTO public.autores VALUES (58, 'Samuel Hernández', 'V-31168152');
INSERT INTO public.autores VALUES (59, 'Andy González', 'V-31320644');
INSERT INTO public.autores VALUES (60, 'Christian Marquez', 'V-31029322');
INSERT INTO public.autores VALUES (61, 'Franyer Montilla', 'V-31259634');
INSERT INTO public.autores VALUES (62, 'Ismaireth Rivera', 'V-30600496');
INSERT INTO public.autores VALUES (63, 'José Borrero', 'V-30214170');
INSERT INTO public.autores VALUES (64, 'Carlos Vallejo', 'V-30975678');
INSERT INTO public.autores VALUES (65, 'José Rojo', 'V-30536364');
INSERT INTO public.autores VALUES (66, 'Andrus Ramírez', 'V-30469331');
INSERT INTO public.autores VALUES (67, 'Miguel González', 'V-32621284');
INSERT INTO public.autores VALUES (68, 'José Pérez', 'V-31177398');
INSERT INTO public.autores VALUES (69, 'Yohan Estrada', 'V-31320604');
INSERT INTO public.autores VALUES (70, 'Fabian Matheus', 'V-31979751');
INSERT INTO public.autores VALUES (71, 'Jerferson Mendoza', 'V-31814137');
INSERT INTO public.autores VALUES (72, 'Jhoandry Hernández', 'V-31795273');
INSERT INTO public.autores VALUES (73, 'Jeremy Aldana', 'V-31814474');
INSERT INTO public.autores VALUES (74, 'Diego Salcedo', 'V-30976478');
INSERT INTO public.autores VALUES (75, 'Contreras Jaiver', 'V-30880014');
INSERT INTO public.autores VALUES (76, 'Hernández Rogers', 'V-31320587');
INSERT INTO public.autores VALUES (77, 'Mejia Dayalex', 'V-31320075');
INSERT INTO public.autores VALUES (78, 'Socarras Kevin', 'V-31095383');
INSERT INTO public.autores VALUES (79, 'Tovar Silvana', 'V-29874386');
INSERT INTO public.autores VALUES (80, 'Briceño Brandon', 'V-29814531');
INSERT INTO public.autores VALUES (81, 'Nava Frank', 'V-30600820');
INSERT INTO public.autores VALUES (82, 'Oviedo Yean Carlos', 'V-30737692');
INSERT INTO public.autores VALUES (83, 'Sue Maykel Leal', 'V-29933387');
INSERT INTO public.autores VALUES (84, 'Julio César García Alcalá', 'V-31898495');
INSERT INTO public.autores VALUES (85, 'Andrés Efraín Matheus Rivas', 'V-30867328');
INSERT INTO public.autores VALUES (86, 'álvaro Josué Leal Mejía', 'V-31239667');
INSERT INTO public.autores VALUES (87, 'Johana Karina Valera Briceño', 'V-30189791');
INSERT INTO public.autores VALUES (88, 'César Delgado', 'V-31426071');
INSERT INTO public.autores VALUES (89, 'Brandon Aguilar', 'V-30976197');
INSERT INTO public.autores VALUES (90, 'Amílcar Torres', 'V-31168311');
INSERT INTO public.autores VALUES (91, 'González Victoria', 'V-30931145');
INSERT INTO public.autores VALUES (92, 'Valero Alejandro', 'V-29814164');
INSERT INTO public.autores VALUES (93, 'Linares Yerfenson', 'V-14800106');
INSERT INTO public.autores VALUES (94, 'Rangel Jully', 'V-23252159');
INSERT INTO public.autores VALUES (95, 'Torres V Alfredo J', 'V-27152700');
INSERT INTO public.autores VALUES (96, 'Torres M Gabriel A', 'V-30047646');
INSERT INTO public.autores VALUES (97, 'Arandia S Samuel L', 'V-29739914');
INSERT INTO public.autores VALUES (98, 'Linares T Leonardo D', 'V-30931231');
INSERT INTO public.autores VALUES (99, 'Rojo P Derek D', 'V-26558826');
INSERT INTO public.autores VALUES (100, 'Chávez Rivero José Gabriel', 'V-30047608');
INSERT INTO public.autores VALUES (101, 'Cruz Cruz Anthony Manojlovich', 'V-30235965');
INSERT INTO public.autores VALUES (102, 'Ramírez Maria', 'V-31168449');
INSERT INTO public.autores VALUES (103, 'Molina Luis', 'V-32886322');
INSERT INTO public.autores VALUES (104, 'Delgado Juan', 'V-31897897');
INSERT INTO public.autores VALUES (105, 'José Cano', 'V-28323346');
INSERT INTO public.autores VALUES (106, 'Montilla Olmos', 'V-30866991');
INSERT INTO public.autores VALUES (107, 'Piña Materán', 'V-31413623');
INSERT INTO public.autores VALUES (108, 'Salcedo Angel', 'V-31008131');
INSERT INTO public.autores VALUES (109, 'Orlando Ortega', 'V-27889926');
INSERT INTO public.autores VALUES (110, 'Carrillo Alex', 'V-29932622');
INSERT INTO public.autores VALUES (111, 'Delgado Faniuska', 'V-29994227');
INSERT INTO public.autores VALUES (112, 'García Estefani', 'V-30302701');
INSERT INTO public.autores VALUES (113, 'Alburguez Shirly', 'V-30600111');
INSERT INTO public.autores VALUES (114, 'Conteras Jhoan', 'V-30380014');
INSERT INTO public.autores VALUES (115, 'Valera Aarón', 'V-30867453');
INSERT INTO public.autores VALUES (116, 'Pineda Andrés', 'V-30976384');
INSERT INTO public.autores VALUES (117, 'Gil Salomón', 'V-29539917');
INSERT INTO public.autores VALUES (118, 'Jesús Gutiérrez', 'V-28206934');
INSERT INTO public.autores VALUES (119, 'Angel Mascagnini', 'V-31368896');
INSERT INTO public.autores VALUES (120, 'Cárdenas Valera Alí Manuel', 'V-3073775');
INSERT INTO public.autores VALUES (121, 'Ruiz Pulido Jhon Maickol', 'V-3073817');
INSERT INTO public.autores VALUES (122, 'Araujo Maria', 'V-31979412');
INSERT INTO public.autores VALUES (123, 'Briceño Jean', 'V-30302949');
INSERT INTO public.autores VALUES (124, 'Carrasquero Hender', 'V-30738064');
INSERT INTO public.autores VALUES (125, 'Montilla Angeli', 'V-31383853');
INSERT INTO public.autores VALUES (126, 'Rivero Luis', 'V-30236073');
INSERT INTO public.autores VALUES (127, 'José Vazquez', 'V-31732185');
INSERT INTO public.autores VALUES (128, 'Douglas González', 'V-12796229');
INSERT INTO public.autores VALUES (129, 'Jorge Carrillo', 'V-26123371');
INSERT INTO public.autores VALUES (130, 'Luis Castellanos', 'V-30558543');
INSERT INTO public.autores VALUES (131, 'Alejandro Gil', 'V-30302249');
INSERT INTO public.autores VALUES (132, 'Andrés González', 'V-30880936');
INSERT INTO public.autores VALUES (133, 'Julio Granadino', 'V-30475679');
INSERT INTO public.autores VALUES (134, 'Daniel Villarreal', 'V-30600936');
INSERT INTO public.autores VALUES (135, 'María Pacheco', 'V-15752623');
INSERT INTO public.autores VALUES (136, 'Roberto Saavedra', 'V-30671594');
INSERT INTO public.autores VALUES (137, 'Jeremías Chiquito', 'V-31732427');
INSERT INTO public.autores VALUES (138, 'Adrián Briceño', 'V-32282913');
INSERT INTO public.autores VALUES (139, 'Esteban Duarte', 'V-32374189');
INSERT INTO public.autores VALUES (140, 'Alan Peña', 'V-32000179');
INSERT INTO public.autores VALUES (141, 'José álzate', 'V-33382712');
INSERT INTO public.autores VALUES (142, 'Gregory Rivero', 'V-33598166');
INSERT INTO public.autores VALUES (143, 'Gabriel Morillo', 'V-31095500');
INSERT INTO public.autores VALUES (144, 'Moreno Leiximar', 'V-32159124');
INSERT INTO public.autores VALUES (145, 'Rivas Freyber', 'V-32461998');
INSERT INTO public.autores VALUES (146, 'Torres Eimy', 'V-29585859');
INSERT INTO public.autores VALUES (147, 'Hernández Junaiver', 'V-30302583');
INSERT INTO public.autores VALUES (148, 'Matos Wilian', 'V-31980148');
INSERT INTO public.autores VALUES (149, 'Rincón Junior', 'V-26962838');
INSERT INTO public.autores VALUES (150, 'Vielma Fabiola', 'V-30437771');
INSERT INTO public.autores VALUES (151, 'Cabrita Joel', 'V-27896010');
INSERT INTO public.autores VALUES (152, 'Mejía José', 'V-32374692');
INSERT INTO public.autores VALUES (153, 'Viloria Santiago', 'V-32234255');
INSERT INTO public.autores VALUES (154, 'Araujo Andrés', 'V-32360642');
INSERT INTO public.autores VALUES (155, 'Guzmán Derick', 'V-31028932');
INSERT INTO public.autores VALUES (156, 'Rivero Oriana', 'V-32373897');
INSERT INTO public.autores VALUES (157, 'Terán Orlienderson', 'V-32007187');
INSERT INTO public.autores VALUES (158, 'Escobar Morales Gelany Paola', 'V-33573889');
INSERT INTO public.autores VALUES (159, 'Ruza Ferrebus Jhon David', 'V-32282366');
INSERT INTO public.autores VALUES (160, 'Moisés Carrillo', 'V-32282637');
INSERT INTO public.autores VALUES (161, 'Alexander Villegas', 'V-32620849');
INSERT INTO public.autores VALUES (162, 'Jared Albornoz', 'V-31732176');
INSERT INTO public.autores VALUES (163, 'Ezequiel Suárez', 'V-32084207');
INSERT INTO public.autores VALUES (164, 'Andrés Riveros', 'V-30475965');
INSERT INTO public.autores VALUES (165, 'Sebastián Saporito', 'V-33311397');
INSERT INTO public.autores VALUES (166, 'Derrick Rojo', 'V-28445791');
INSERT INTO public.autores VALUES (167, 'Edgardo Molina', 'V-29994895');
INSERT INTO public.autores VALUES (168, 'María Olivar', 'V-28542385');
INSERT INTO public.autores VALUES (169, 'Adriana Sánchez', 'V-31320176');
INSERT INTO public.autores VALUES (170, 'Mariana Pérez', 'V-32550856');
INSERT INTO public.autores VALUES (171, 'Adrian Jose Diaz Sifuentes', 'V-32461812');
INSERT INTO public.autores VALUES (172, 'Páez Jamko', 'V-13523529');
INSERT INTO public.autores VALUES (173, 'Rangel Eduardo', 'V-32159229');
INSERT INTO public.autores VALUES (174, 'Silva Miguel', 'V-26155069');
INSERT INTO public.autores VALUES (175, 'Venegas Francisco', 'V-11322039');
INSERT INTO public.autores VALUES (176, 'Andrade R Jorge A', 'V-31515045');
INSERT INTO public.autores VALUES (177, 'Cadenas V Diego A', 'V-33639626');
INSERT INTO public.autores VALUES (178, 'Quiroga B Sarai', 'V-32283208');
INSERT INTO public.autores VALUES (179, 'Terán S Pedro A', 'V-32116439');
INSERT INTO public.autores VALUES (180, 'Jorge Alarcón', 'V-30867685');
INSERT INTO public.autores VALUES (181, 'Miguel Barrios', 'V-30866953');
INSERT INTO public.autores VALUES (182, 'Ricardo Fontana', 'V-31666059');
INSERT INTO public.autores VALUES (183, 'Sebastián Uribe', 'V-33174441');
INSERT INTO public.autores VALUES (184, 'Rebeca Olivar', 'V-27778997');
INSERT INTO public.autores VALUES (185, 'Karen Albarrán', 'V-32159241');
INSERT INTO public.autores VALUES (186, 'Ariana Ruza', 'V-33290662');
INSERT INTO public.autores VALUES (187, 'Balza Roger', 'V-31728855');
INSERT INTO public.autores VALUES (188, 'Rodríguez Andreina', 'V-32000225');
INSERT INTO public.autores VALUES (189, 'Sulbaran Olay Torres Marco', 'V-31457137');
INSERT INTO public.autores VALUES (190, 'Sulbaran Olay', 'V-31732771');
INSERT INTO public.autores VALUES (191, 'Carliany Baptista', 'V-32971126');
INSERT INTO public.autores VALUES (192, 'Eilim Monsalve', 'V-32907046');
INSERT INTO public.autores VALUES (193, 'Marianly Villegas', 'V-31898056');
INSERT INTO public.autores VALUES (194, 'Baptista Luis', 'V-21364647');
INSERT INTO public.autores VALUES (195, 'Guerrero Andry', 'V-28096379');
INSERT INTO public.autores VALUES (196, 'Vásquez Ronald', 'V-28190249');
INSERT INTO public.autores VALUES (197, 'Tribiño Mendéz Celena Lisseth', 'V-31188138');
INSERT INTO public.autores VALUES (198, 'Barrios Samuel', 'V-29814838');
INSERT INTO public.autores VALUES (199, 'Garzo Yexon', 'V-28542191');
INSERT INTO public.autores VALUES (200, 'Ontiveros Emmanuel', 'V-30495057');
INSERT INTO public.autores VALUES (201, 'Rendon Jonathan', 'V-29814614');
INSERT INTO public.autores VALUES (202, 'Vale Jesús', 'V-30670829');
INSERT INTO public.autores VALUES (203, 'Amadeo Dacunto', 'V-30717343');
INSERT INTO public.autores VALUES (204, 'Junior Dávila', 'V-31168523');
INSERT INTO public.autores VALUES (205, 'Jesus Salcedo', 'V-31319862');
INSERT INTO public.autores VALUES (206, 'Martínez Edduin', 'V-31825153');
INSERT INTO public.autores VALUES (207, 'Molina Adrián', 'V-31167961');
INSERT INTO public.autores VALUES (208, 'Pérez Juan', 'V-31814987');
INSERT INTO public.autores VALUES (209, 'Pulido Gabriela', 'V-31665705');
INSERT INTO public.autores VALUES (210, 'Marian Andreina Hernández Terán', 'V-31168192');
INSERT INTO public.autores VALUES (211, 'Valentina Amelie Gutiérrez Terán', 'V-31732186');
INSERT INTO public.autores VALUES (212, 'Juan Pablo Barroeta González', 'V-31319720');
INSERT INTO public.autores VALUES (213, 'Bastidas Parra José Manuel', 'V-31413711');
INSERT INTO public.autores VALUES (214, 'Delgado Infante Mario José', 'V-32461801');
INSERT INTO public.autores VALUES (215, 'Mejia Ruiz Javier Jesus', 'V-31095153');
INSERT INTO public.autores VALUES (216, 'Ramos Valera Christian Andrés', 'V-28096179');
INSERT INTO public.autores VALUES (217, 'Viloria Rojas Fabian Andrés', 'V-31732673');
INSERT INTO public.autores VALUES (218, 'Valentina Sarais Castellanos Leal', 'V-31732652');
INSERT INTO public.autores VALUES (219, 'Luis Alejandro Fernández Briceño', 'V-32188577');
INSERT INTO public.autores VALUES (220, 'Sara Zoar Peña Itriago', 'V-31665730');
INSERT INTO public.autores VALUES (221, 'Yorlendis Annyely Peña Rodríguez', 'V-31464347');
INSERT INTO public.autores VALUES (222, 'Mariangy Alexandra Durán Jerez', 'V-31413049');
INSERT INTO public.autores VALUES (223, 'Franscheska Fabiana Pabón Contreras', 'V-32550534');
INSERT INTO public.autores VALUES (224, 'Camila De Los Angeles Sulbaran Blanco', 'V-31814330');
INSERT INTO public.autores VALUES (225, 'Ramón Enrique Peña Pérez', 'V-29874509');
INSERT INTO public.autores VALUES (226, 'Noreymig Rubio', 'V-32083701');
INSERT INTO public.autores VALUES (227, 'Mariangelis Maldonado', 'V-31980173');
INSERT INTO public.autores VALUES (228, 'Mariam Carreño', 'V-32084200');
INSERT INTO public.autores VALUES (229, 'Kenmary Blanco', 'V-31732001');
INSERT INTO public.autores VALUES (230, 'Yeisonk Pimentel', 'V-26967781');
INSERT INTO public.autores VALUES (231, 'Mariangel Andreina Sánchez Trejo', 'V-31524534');
INSERT INTO public.autores VALUES (232, 'Yacnely Carolina Rondón Moreno', 'V-32462345');
INSERT INTO public.autores VALUES (233, 'Evangelys Valentina Rivas Valecillos', 'V-31602760');
INSERT INTO public.autores VALUES (234, 'Diego Andres Villegas Rivero', 'V-30976505');
INSERT INTO public.autores VALUES (235, 'Albornoz Olivares Abdon Leonardo', 'V-31732548');
INSERT INTO public.autores VALUES (236, 'Atencio Marín Shergio Gabriel', 'V-31728829');
INSERT INTO public.autores VALUES (237, 'Cadenas Briceño Salvador José', 'V-32297314');
INSERT INTO public.autores VALUES (238, 'Moreno Araujo Juan Pablo Sebastian', 'V-31521358');
INSERT INTO public.autores VALUES (239, 'Camacho Gilneiber', 'V-28445972');
INSERT INTO public.autores VALUES (240, 'Nava Yeider', 'V-30536270');
INSERT INTO public.autores VALUES (241, 'Reyes Anderlyn', 'V-29814904');
INSERT INTO public.autores VALUES (242, 'Rondón Yosimar', 'V-25832509');
INSERT INTO public.autores VALUES (243, 'Blanco Jeriko', 'V-31731931');
INSERT INTO public.autores VALUES (244, 'Morales Reinaldo', 'V-31958901');
INSERT INTO public.autores VALUES (245, 'Romero Valeria', 'V-31666110');
INSERT INTO public.autores VALUES (246, 'Albany Layret Camargo Guillen', 'V-31814437');
INSERT INTO public.autores VALUES (247, 'Stefany Jhoulet Castrillón Gutierrez', 'V-31666050');
INSERT INTO public.autores VALUES (248, 'Yilfreddy Gabriel Duarte Valecillos', 'V-31735021');
INSERT INTO public.autores VALUES (249, 'Oscar Manuel Villareal Valera', 'V-30600212');
INSERT INTO public.autores VALUES (250, 'Analy De Los Angeles Hernández Cortéz', 'V-30601065');
INSERT INTO public.autores VALUES (251, 'Anyela Alejandra Briceño Guerra', 'V-31413272');
INSERT INTO public.autores VALUES (252, 'Valeria Alejandra Sulbarán Delgado', 'V-30976285');
INSERT INTO public.autores VALUES (253, 'Diosmary Nazareth Duran Rivas', 'V-31854894');
INSERT INTO public.autores VALUES (254, 'Diego Arellano', 'V-31029125');
INSERT INTO public.autores VALUES (255, 'Maria Linares', 'V-31898407');
INSERT INTO public.autores VALUES (256, 'Silvano Cadenas', 'V-32297313');
INSERT INTO public.autores VALUES (257, 'Adrián Méndez', 'V-28190041');
INSERT INTO public.autores VALUES (258, 'Abraham Graterol', 'V-31167863');
INSERT INTO public.autores VALUES (259, 'Isaac Figuera', 'V-31239364');
INSERT INTO public.autores VALUES (260, 'Wrayan Linares', 'V-31413308');
INSERT INTO public.autores VALUES (261, 'Alvarado Miranda', 'V-31732595');
INSERT INTO public.autores VALUES (262, 'Briceño Rafael', 'V-31732166');
INSERT INTO public.autores VALUES (263, 'Cañizalez Yonathan', 'V-31520663');
INSERT INTO public.autores VALUES (264, 'La Cruz Ismael', 'V-31234242');
INSERT INTO public.autores VALUES (265, 'Araujo Arianny', 'V-30116864');
INSERT INTO public.autores VALUES (266, 'Cardozo Eduard', 'V-26488002');
INSERT INTO public.autores VALUES (267, 'Guillén Jesús', 'V-31095010');
INSERT INTO public.autores VALUES (268, 'Valero Estefania', 'V-30116979');
INSERT INTO public.autores VALUES (269, 'César Herrera', 'V-27896653');
INSERT INTO public.autores VALUES (270, 'Pedro Salvucci', 'V-31958842');
INSERT INTO public.autores VALUES (271, 'Jesús Becerra', 'V-30600950');
INSERT INTO public.autores VALUES (272, 'José Giro', 'V-31665947');
INSERT INTO public.autores VALUES (273, 'Jorge Moreno', 'V-30808439');
INSERT INTO public.autores VALUES (274, 'Gonzalez Esnaider', 'V-31520502');
INSERT INTO public.autores VALUES (275, 'Montilla Pedro', 'V-30738272');
INSERT INTO public.autores VALUES (276, 'Moreno Israel', 'V-32159296');
INSERT INTO public.autores VALUES (277, 'Barrios Abreu Enio Enrique', 'V-16846566');
INSERT INTO public.autores VALUES (278, 'Dávila Araujo José Gonzalo', 'V-12041146');
INSERT INTO public.autores VALUES (279, 'Blanco Victor', 'V-31898245');
INSERT INTO public.autores VALUES (280, 'Ruiz David', 'V-31898257');


--
-- Data for Name: carreras; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.carreras VALUES (1, 'PNF en Informática', 'Ingeniería y TSU en Informática');
INSERT INTO public.carreras VALUES (2, 'PNF en Electricidad', 'Ingeniería y TSU en Electricidad');
INSERT INTO public.carreras VALUES (3, 'PNF en Administración', 'Licenciatura y TSU en Administración');
INSERT INTO public.carreras VALUES (4, 'PNF en Agroalimentación', 'Ingeniería y TSU Agroalimentario');
INSERT INTO public.carreras VALUES (5, 'PNF en Construcción Civil', 'Ingeniería y TSU en Construcción Civil');


--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categorias VALUES (1, 'Tecnología');
INSERT INTO public.categorias VALUES (3, 'Ingeniería');
INSERT INTO public.categorias VALUES (4, 'Sociales');
INSERT INTO public.categorias VALUES (5, 'Innovación');
INSERT INTO public.categorias VALUES (6, 'Ciencias Sociales');
INSERT INTO public.categorias VALUES (7, 'Salud y Biociencias');
INSERT INTO public.categorias VALUES (11, 'Salud');
INSERT INTO public.categorias VALUES (13, 'Matemáticas');
INSERT INTO public.categorias VALUES (14, 'Literatura');
INSERT INTO public.categorias VALUES (15, 'Psicología');
INSERT INTO public.categorias VALUES (16, 'Economía');
INSERT INTO public.categorias VALUES (17, 'Contaduría');
INSERT INTO public.categorias VALUES (18, 'Ingeniería Civil');
INSERT INTO public.categorias VALUES (10, 'Admin');


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cursos VALUES (1, 4, 'Introducción a la Metodología de la Investigación', 'Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (2, 4, 'Fundamentos de Inteligencia Artificial', 'Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (4, 1, 'tamaños de jose', 'los pn que jose ha tenido segun tamaño', NULL, 'borrador', 69.96, '2026-04-03 04:40:03', '2026-09-02 21:38:28.289267', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (3, 10, 'Normas APA y Redacción Científica', 'Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.', NULL, 'archivado', 67.00, '2026-04-03 03:28:04', '2026-09-02 21:39:04.419332', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (6, 9, 'e', 'e', NULL, 'publicado', 70.00, '2026-09-02 21:40:20.207518', '2026-09-02 21:40:27.256632', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');
INSERT INTO public.cursos VALUES (7, 7, 'e', 'e', 'public/uploads/cursos/curso_1789800856_34a50e7c.webp', 'borrador', 70.00, '2026-09-19 02:54:16.395792', '2026-09-19 02:54:16.395792', NULL, NULL, 'Virtual', 'B sico', NULL, NULL, NULL, NULL, NULL, 'Abierta');


--
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_articulos VALUES (120, NULL, '93', '241', '0012-7353', '2026-07-09 20:16:51.643727', 'art_1783642611_6a5039f396bec.png', 'La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada', true, NULL);
INSERT INTO public.detalles_articulos VALUES (118, 5, '87', '213', '0012-7353', '2026-07-09 15:19:57.897052', 'https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png', 'Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato', true, NULL);
INSERT INTO public.detalles_articulos VALUES (121, 8, '93', '241', '0012-7353', '2026-07-09 20:19:28.855723', 'art_1783642768_6a503a90ca313.png', 'La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (122, 8, '93', '241', '0012-7353', '2026-07-09 20:08:48.117741', 'art_1783642127_6a50380feed3b.png', 'La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (119, 8, '93', '241', '0012-7353', '2026-07-09 20:13:49.50881', 'art_1783642429_6a50393d74626.png', 'Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos', true, NULL);
INSERT INTO public.detalles_articulos VALUES (143, 7, '93', '242', '0012-7353', '2026-09-07 15:40:32.098085', 'https://revistas.unal.edu.co/public/journals/21/submission_124890_112809_coverImage_es_ES.png', 'En una línea de transmisión de alta tensión de circuito único de 400 kV, la proximidad de los conductores de fase induce una corriente circulante en el conductor de tierra. Esta corriente forma un circuito cerrado a través del sistema de puesta a tierra de la base de la torre, que proporciona su ruta de retorno [1]. En este estudio, se modela y analiza la corriente circulante inducida. El modelado se realizó en el entorno MATLAB.Los resultados indican que la corriente circulante y la tensión inducida en el conductor de tierra presentan una relación aproximadamente lineal con las corrientes de los conductores de fase, y sus magnitudes se ven influenciadas por la resistencia de puesta a tierra de la torre R_g y la resistividad del suelo ρ. Además, las pérdidas de potencia en el conductor de tierra alcanzan niveles significativos en condiciones de alta corriente. Estos hallazgos resaltan la importancia de un diseño óptimo del sistema de puesta a tierra, una selección adecuada de las características del conductor de tierra y la implementación de métodos para reducir las corrientes circulantes con el fin de mejorar el rendimiento y reducir las pérdidas en las líneas de transmisión de alta tensión.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (146, NULL, '91', '232', '0012-735', '2026-09-09 00:51:39.29162', 'https://revistas.unal.edu.co/public/journals/21/submission_112625_95079_coverImage_es_ES.png', 'La producción de azúcar de caña no centrifugada, en Colombia se realiza en instalaciones de poscosecha que generan alta cantidad de calor y vapor, producto de la evaporación de los jugos de caña del proceso. Este estudio tuvo como objetivo mejorar las condiciones de confort de una instalación de este tipo en el municipio de Pacho, Cundinamarca, Colombia, a través de simulación bioclimática, donde se modificó el cerramiento en las paredes y en la ventana cenital. Se evalúo el confort térmico adaptativo, donde el mejor comportamiento bioclimático se  presentó  en  las  configuraciones  con  perímetro  abierto  y  ventana  cenital,  esto  debido  a  que  una  mayor  área  de  ventilación  y  efecto chimenea optimizan  la  transferencia  de  calor  y  masa;  así  mismo,  se  observó  que  hay  un  comportamiento  generalizado  de  incomodidad  térmica para los trabajadores en la zona térmica hornilla, debido a las altas emisiones de calor y vapor en esta zona', true, NULL);
INSERT INTO public.detalles_articulos VALUES (144, 8, '93', '242', '0012-7353', '2026-09-09 00:24:54.886595', 'https://revistas.unal.edu.co/public/journals/21/submission_124428_112347_coverImage_es_ES.png', 'Esta investigación desarrolló y evaluó un modelo de aprendizaje automático para optimizar la contratación de profesionales de ingeniería en  una universidad pública, reduciendo el tiempo de evaluación, los errores y la subjetividad en el análisis de currículums. Se empleó un enfoque cuantitativo, aplicado y cuasiexperimental, utilizando procesamiento de lenguaje natural (TF-IDF), clasificación KNN bajo el esquema One vs-Rest y tres conjuntos de datos de 10, 20 y 30 CV. La información fue procesada en Google Colab mediante etapas de limpieza, vectorización, entrenamiento y evaluación. El modelo alcanzó una precisión del 82 % en la clasificación de candidatos, priorizando de manera consistente a los postulantes según su grado académico y experiencia profesional. Además, redujo el tiempo promedio de evaluación de 15 a 2,5 minutos por CV y disminuyó la tasa de error a menos del 2 %, demostrando ser una herramienta eficiente, objetiva y escalable.', true, NULL);
INSERT INTO public.detalles_articulos VALUES (150, 7, 'e', 'e', 'e', '2026-09-11 11:59:24.004409', 'https://i.pinimg.com/736x/34/63/e7/3463e729b17ec40b1c60c25e1d86af52.jpg', 'e', true, NULL);
INSERT INTO public.detalles_articulos VALUES (151, 8, 'e', 'e', 'e', '2026-09-11 15:27:57.58139', 'default_article.jpg', 'e', true, NULL);
INSERT INTO public.detalles_articulos VALUES (156, 8, 'e', 'e', 'e', '2026-09-23 12:09:57.549672', 'default_article.jpg', 'e', true, NULL);


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_proyectos VALUES (225, '2025-10-01', 'Pregrado', 'El presente proyecto socio-tecnológico tiene como propósito actualizar y ampliar la página web existente desarrollada en el Proyecto Socio-tecnológico II, incorporando un sistema administrativo y de facturación para el Centro Inmobiliario C.A., dedicado a la compra, venta, alquiler y administración de inmuebles. Durante su ejecución se realizaron visitas a la empresa para identificar las necesidades del personal administrativo, se recolectó información técnica y para organizar las fases del desarrollo. El proyecto se sustenta en el diagnóstico previo realizado en el Proyecto II, donde se identificaron limitaciones tecnológicas como la falta de herramientas automatizadas para la gestión de facturación y alquileres. En esta nueva fase, se propone implementar un sistema que permita al propietario gestionar transacciones, pagos y reportes, ofreciendo una administración más transparente, eficiente y moderna, además de mejorar la experiencia de los usuarios mediante una plataforma más accesible y funcional.', 1, 'Centro Inmobiliario C.A', 'Actualización web, ampliación de página web, sistema administrativo, sistema de facturación, gestión de inmuebles, compra, venta y alquiler, automatización de procesos, gestión de', '2026-10-19 22:32:08.310709', NULL, NULL, 'Diseñar un sistema integrado para la gestión de los procesos administrativos en el Centro Inmobiliario C.A.', true, '[0.00307091,-0.07677576,-0.07916076,-0.05732282,0.02261765,-0.01517067,-0.05599571,0.04133615,-0.02286624,0.13033223,0.08170108,-0.0168598,0.05010296,0.00598671,-0.07813446,0.01160073,-0.00018834,-0.02039618,0.04084457,-0.01196202,0.05144484,-0.11943343,0.04150075,0.04241893,-0.03400563,-0.03687134,-0.08091483,0.09460457,0.01717933,-0.00757598,-0.04911266,0.04865795,0.07865585,0.01751703,0.10358409,0.10095297,-0.03720617,-0.05271732,0.01128097,0.02813679,0.07119234,-0.03770107,-0.0276851,-0.11665236,-0.07603305,0.02956789,0.01112363,0.0046865,-0.12193139,-0.02946949,-0.05185006,-0.02512721,0.01089938,0.02883734,-0.09675493,0.03070491,0.02202153,-0.0150401,0.00820736,-0.0238195,0.06757573,0.01941777,0.02979994,0.0512107,0.0657799,0.01797458,0.0123329,0.03470388,0.01007535,-0.07004658,-0.01540206,-0.02644237,0.01991692,0.07302434,0.01548053,-0.07032231,-0.01021118,-0.02655885,0.00893461,-0.07007503,0.04022063,0.05930448,-0.04644307,0.07124752,-0.04772583,-0.03916414,-0.03681758,-0.02193713,0.05999089,0.01076467,0.01224053,0.0028027,0.01202672,-0.05766782,0.01495174,-0.03151018,0.02106679,-0.0351805,-0.00815774,-0.01989099,-0.00936163,-0.00100675,0.02137423,-0.0630609,-0.03580728,-0.07875708,-0.04320085,0.08435805,0.09855574,0.0646028,-0.08268827,-0.02096446,-0.01370291,-0.10585006,-0.07368492,-0.02795705,0.0034357,-0.0057232,0.06118948,-0.11520367,-0.0016045,0.00300023,-0.05090206,-0.02899179,0.08403354,0.06270084,-0.01473518,4.64e-06,-0.00388772,-0.03560907,-0.0142798,0.07628453,-0.02181032,0.07223728,0.01229046,-0.03807224,0.00741805,-0.12478091,-0.00403951,0.01019554,0.00747183,-0.06662567,0.04731487,0.00267667,-0.01756598,-0.02268123,-0.04018803,0.03678514,-0.04285985,0.02841488,-0.02920138,-0.04383868,0.03166922,0.01278043,-0.06209022,0.04216952,-0.07050979,0.02961088,0.08219988,0.00734334,-0.05718934,-0.04490521,0.08722393,-0.01049315,0.10885221,0.00281316,0.05618578,-0.03239333,-0.061923,-0.00317005,0.03221825,-0.02741192,0.02101351,-0.05797871,0.08256752,0.03086705,0.10025917,-0.03026695,0.06201474,0.00524322,0.00517108,-0.06146962,-0.0104178,0.03633458,-0.03356743,-0.05324105,0.02483759,0.0147005,0.05062777,-0.04870888,0.00104697,-0.01840422,0.01972639,-0.06965406,-0.04979846,-0.02218042,0.1116502,-0.01577036,-0.07019456,0.06835789,0.05913243,0.08630076,-0.07890496,0.09222796,-0.04640144,-0.02637699,-0.06428407,-0.01785627,0.00734476,-0.01902024,0.03581071,0.0405952,0.16941182,-0.01059489,0.00322068,0.01354175,0.0043938,0.02161341,0.01265544,-0.05892855,-0.00559002,0.1401669,-0.01484408,1.441e-05,-0.03338485,-0.00296993,-0.04098289,-0.00738029,-0.04816526,-0.00491947,-0.04393926,-0.09051416,-0.06791112,0.02884186,0.03937706,-0.03131707,-0.01175325,-0.00781986,0.02930743,0.01384466,0.00228444,-0.02475855,-0.06020446,0.00980962,0.04237362,-0.05750708,-0.01457057,-0.01469716,-0.02097915,0.0147897,-0.03880335,0.00671817,-0.00295989,0.04567444,0.00839986,-0.05305426,-0.09929089,0.02486637,-0.02297734,-0.07155858,-0.10723878,-0.03985618,-0.01681122,-0.02478716,0.03650749,0.02598817,-0.04091668,-0.09694514,-0.02832548,0.00716185,0.0064473,-0.10926633,-0.00166752,0.02886179,0.02929664,0.01293338,0.014486,-0.02178174,0.01659908,0.12674129,-0.09157851,0.05127351,-0.09333184,-0.10254587,0.05299132,0.03620312,-0.06525888,0.02387749,-0.00144882,0.14032935,-0.04634906,0.08974014,-0.01617154,-0.01613421,0.01931371,0.04361945,-0.11074553,-0.11372272,-0.01410304,-0.001922,-0.02227825,0.05680798,-0.00796121,0.02604861,-0.04547244,0.02597654,0.00088538,-0.14196093,0.00151088,0.05327095,-0.03093227,0.00963588,0.01349264,-0.00340422,-0.02629822,-0.04270587,-0.08413989,-0.04561041,-4.947e-05,1.54e-06,-0.0378801,0.01962629,0.01809826,0.01047338,0.06375344,-0.07539757,0.04740184,-0.05185714,0.04015158,0.08223794,-0.01833857,0.00470606,0.01106567,-0.04062903,0.03604049,-0.03562623,-0.02350239,0.07623057,-0.05309163,-0.0438984,-0.00187904,-0.08311854,-0.07056952,0.04314923,0.03605725,-0.04771536,-0.03343411,0.01224379,-0.04458824,0.0844952,-0.04623113,-0.02420636,-0.05943227,-0.01658526,0.02606083,0.0344413,-0.01765292,0.02069605,-0.05816727,-0.04797699,0.05232759,0.00074583,0.02338055,0.08100605,0.04134496,0.00580275,-0.01441233,0.04927015,0.04760025,-0.00652582,-0.01649391,-0.01623595,-0.05140585,0.05743875,-0.00456006,-0.03321563,0.06857952,0.04260766,-0.0898238,0.00150336,-0.00614628,0.03552009,0.07511565,-0.04955984]', 3, 0);
INSERT INTO public.detalles_proyectos VALUES (226, '2025-11-01', 'Pregrado', 'El desarrollo de este Proyecto abarcó un esfuerzo colaborativo por parte del equipo de desarrollo y el equipo administrativo de la universidad. Mediante la investigación y la lógica de programación se obtuvieron las respuestas a estas incógnitas, basado en esto, se generó la idea de dos sistemas que pudieran comunicarse entre sí mediante formatos originales para el manejo de datos. Primeramente, para manejo bruto de datos y procesos de depuración robusto se usó Python, mediante funciones y algoritmos inteligentes se pudo generar un listado de personas aprobadas y aptas para los certificados, purgando listas de más de 20 mil estudiantes en menos de 1 minuto. Así mismo, se diseñaron estrategias de software para generar un sistema capaz de manejar diferentes accesos, y con esto, diferentes funciones. A partir de esto, se desarrolló el sistema de gestión que va dirigido al servidor de la universidad, dicho sistema carga los datos y mediante funciones JavaScript robustas es capaz de generar certificados en formato .pptx masivamente, de 50 en 50, o de 100 en 100 perfectamente. Una vez culminados los sistemas, se han solucionado las problemáticas correspondientes al área de gestión y entrega de certificados que impulsaron la investigación, desarrollo y creación de dicho proyecto', 1, 'PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ', 'Sistema, Gestor, Certificados, Pensum, Validación, Datos, Calificaciones, Administrativo', '2026-10-19 22:36:58.090301', NULL, NULL, 'Desarrollar y proporcionar un Sistema de Gestión Administrativa para las Certificaciones del PNFI.', true, '[-0.05689364,-0.02773728,-0.00204643,-0.05207146,-0.07804996,0.00501449,-0.03030592,0.01079109,-0.0684029,-0.05343495,0.00864172,-0.03359409,0.00206124,-0.04548857,-0.07189463,-0.1357184,-0.03777308,-0.04746654,0.03508216,-0.02158549,-0.04588947,-0.06566403,0.02721271,0.09457979,-0.05182881,-0.0283355,0.02645979,0.07778592,0.00349356,-0.09594909,0.02872075,-0.02956786,0.10243671,0.03834426,0.04535526,-0.00994797,0.01046987,-0.03841149,-0.01915066,0.02145766,-0.01619248,-0.02184246,0.02996547,0.07462451,0.01234569,-0.00143575,-0.07390456,-0.08058282,-0.06369178,0.06971172,0.0265929,-0.06656411,0.04906818,-0.05140221,0.02706995,0.0273715,0.00687207,-0.02914159,0.02689437,0.10826853,0.02115127,0.07466429,-0.05514476,0.01156968,0.01531866,0.08616479,-0.01368051,-0.01559352,-0.04628321,-0.02349493,0.04002994,-0.01987161,-0.06856865,-0.00834449,-0.03067056,0.04965288,-0.08282497,0.02624098,0.0232493,-0.01654645,0.04307011,-0.01597477,-0.00285773,-0.03834929,-0.00845666,-0.06525692,0.02001213,0.00872089,0.05690486,0.0672616,0.08039901,-0.01074198,0.0639393,-0.01072955,-0.03855794,0.05352587,-0.02978165,-0.05329064,0.0303388,0.02745924,-0.08435487,0.00446561,0.06112367,0.00575726,0.02675954,0.02647097,0.05366201,0.01108234,0.06949182,0.01113192,-0.02605323,-0.0072288,-0.04215483,-0.03442803,-0.10337296,0.11244035,-0.0576112,0.00358153,-0.06690321,-0.00763587,-0.02528428,0.01324103,-0.06869759,-0.08333576,-0.06373278,-0.02354419,-0.02369067,4.09e-06,0.05453513,-0.05576553,-0.05362325,-0.01764397,0.01221631,0.03382895,0.00519609,0.07074516,-0.06575354,-0.02999378,0.01687162,0.05687802,-0.04154203,0.02460975,0.0165073,0.0040027,-0.05751814,0.0089552,-0.04779491,0.03212255,0.03954419,-0.11369826,0.02248244,-0.05549482,-0.03945365,0.02566401,-0.07688418,0.02209585,0.02347779,0.02470765,0.03932358,-0.10143013,-0.03969893,0.05081045,0.04038366,-0.04430806,0.02512009,-0.00384202,0.01499738,0.0439019,0.01119876,-0.02225702,0.01737208,0.01951282,-0.02593105,-0.05353249,0.06394358,-0.06947264,0.13793558,0.054168,0.05011156,-0.0502432,-0.13985264,-0.02750531,0.07851823,-0.02397999,-0.01265686,0.01600255,-0.03601635,-0.11389606,0.11763703,0.07835795,0.00399238,-0.06653678,-0.01855541,0.07429115,-0.02386301,-0.03221208,0.09923326,-0.09500572,-0.01973095,-0.07725208,0.01572138,0.06608924,0.00015435,0.02248014,-0.07598631,0.0449011,-0.00587424,0.02179541,-0.05720858,0.05972193,-0.08301562,0.03166252,0.0488604,0.0443476,0.02563517,0.08807338,-0.02486894,-0.01683913,-0.04312542,-0.01947933,0.02580189,0.01074023,-0.00560207,1.242e-05,0.03845977,-0.04493887,0.00195018,0.00081106,-0.05349377,-0.01753585,0.0523225,-0.09622161,0.05366748,0.07574963,-0.07928183,0.01233394,0.10405902,-0.10162726,0.04415152,-0.00082845,-0.04615115,0.00300671,-0.00268704,-0.08923252,0.01920154,0.00366926,-0.0080546,0.01277146,-0.00876867,-0.03164312,0.05796648,0.01317673,0.00480391,0.03316423,-0.1195962,-0.01875787,-0.08555958,0.06449469,-0.07249432,-0.06222091,0.11095462,0.07669659,-0.0697174,-0.09767418,-0.03523977,-0.02288683,-0.0449132,0.08073567,0.05846396,0.0466025,0.00353344,-0.06853501,0.08742084,0.06503111,0.07248315,0.07090102,-0.02918639,0.00277416,-0.02046518,0.10455795,-0.04268829,0.04005265,-0.03835745,0.01895141,0.08499872,0.05153398,-0.02318436,0.02187023,0.02834072,0.10879355,-0.05875322,0.02845966,0.01664621,-0.03761913,-0.02126742,-0.01056717,0.04966855,0.02693539,-0.03737079,0.06313714,-0.03065927,-0.02557448,-0.05114346,0.04853645,0.01757191,0.02316229,-0.09103068,0.01707618,0.00787259,-0.05629825,0.01392382,-0.09375609,0.0495118,-0.00284831,0.01596152,0.06808205,0.02528331,-0.06757458,-0.00837391,1.37e-06,0.00263686,-0.00918048,-0.03286936,-0.00367562,0.05254699,0.03148278,-0.00730626,-0.08603923,0.02355491,-0.04169264,0.01580752,0.0215386,-0.08466714,0.00337349,-0.0436099,-0.03804049,0.01126453,0.07704951,-0.0471331,0.0605009,-0.0024011,-0.01478503,0.0056065,0.0034027,-0.09618037,-0.0749498,-0.00085337,0.05279844,-0.06210083,0.00248653,0.03078735,0.02299331,0.00871691,-0.01837105,0.0369707,0.01739491,0.04882214,0.03558385,-0.02395135,0.05210032,-0.03598247,-0.05496296,-0.0495979,-0.0248019,-0.02294141,-0.00769554,-0.00273043,0.04212135,0.03936244,0.09640729,0.04460954,-0.070002,-0.0487087,0.03809807,-0.00497425,0.02658369,0.01221362,-0.10104394,-0.02602824,0.02939883,-0.01508602,-0.05702203,0.15641919,0.00391324]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (227, '2025-11-01', 'Pregrado', 'El presente proyecto tiene como finalidad el diseño e implementación de un Sistema de Información para la Gestión Administrativa de Gominova, una organización dedicada a la repostería, comercialización de detalles, venta de implementos especializados y servicios de papelería. Debido a la diversidad de sus actividades y productos, la organización enfrenta la necesidad de mejorar la gestión de procesos relacionados con el inventario, las ventas y el manejo de la información administrativa. Ante esta situación, se propone el desarrollo de una solución tecnológica que permita optimizar y organizar los procesos internos, reducir la dependencia de procedimientos manuales y facilitar el acceso a información precisa y oportuna. El sistema busca contribuir a una gestión más eficiente, proporcionando herramientas que apoyen el control de las operaciones y la toma de decisiones. Con la implementación de esta propuesta se pretende fortalecer la gestión administrativa de Gominova, mejorar la eficiencia de sus procesos y facilitar su adaptación a las actuales exigencias tecnológicas y comerciales, manteniendo la calidad del servicio y la atención cercana a sus clientes', 1, 'Gominova', 'Sistema de Información, gestión administrativa, inventario, ventas, TIC', '2026-10-19 22:37:05.825892', NULL, NULL, 'Implementar un sistema de información para la gestión administrativa de la empresa Gominova. Para alcanzar este objetivo que está estructurado en diversas fases lógicas, los objetivos intermedios son el siguiente paso para alcanzar nuestro propósito.', true, '[-0.01007371,-0.00432684,-0.02758655,-0.08752847,-0.03448634,0.02327593,-0.00274346,0.01484853,0.00373846,-0.03764429,0.09349098,0.07210377,0.04483355,0.03626615,-0.0744798,-0.1212817,-0.0339765,0.00885304,0.02123977,-0.04902185,0.06469266,0.00233296,0.04214864,0.03784007,-0.04888088,0.02268294,-0.02205599,-0.02269838,0.05017979,0.01774592,0.03895097,-0.0945721,-0.0167158,0.02980709,-0.05891746,0.05641002,0.00589554,-0.01383409,-0.01855071,0.02351839,-3.559e-05,0.00325872,-0.04552189,-0.04932639,-0.02042129,0.01659629,0.02434936,-0.03276476,-0.12814409,0.0047234,-0.01354202,-0.0840105,0.03447746,-0.0116463,-0.01286438,0.04519139,-0.00338157,-0.01964176,-0.02642798,-0.01931667,0.03527917,0.02424948,-0.01015796,-0.03587689,-0.00497826,0.06177112,-0.05655829,-0.07884258,0.02016539,-0.05603948,0.02905318,-0.04261805,0.0405264,0.00806029,-0.07466561,-0.00226079,-0.02591966,0.11578446,0.01513128,-0.03918864,0.09098211,0.00524597,-0.01769714,0.14951941,-0.08737865,-0.08658994,-0.1608221,0.08859016,0.10352139,-0.02615421,0.07695524,-0.02470419,0.11471306,-0.00647593,-0.00392343,-0.00607833,-0.07721502,-0.00906773,-0.00059303,-0.00409183,-0.00042839,-0.03332894,0.07110444,-0.03490091,-0.02485095,-0.02589111,0.03659698,0.01707017,0.02147386,-0.01566141,-0.02890656,0.02221654,-0.04921148,-0.13888274,-0.07582917,0.01892431,-0.03866183,-0.01500138,-0.06942965,-0.08394604,0.01362945,-0.00232717,-0.02310989,-0.03088202,0.07642827,-0.03453599,-0.08973079,2.33e-06,-0.02985057,0.01724318,0.03739493,0.01477226,0.03608301,0.08403427,0.00481807,-0.03550143,-0.00328689,-0.0449576,-0.02854835,0.06118688,-0.12015188,-0.03651332,0.02528884,0.03085553,-0.0212285,0.05613644,0.06535999,-0.03263246,0.09410466,-0.0616375,0.06837653,-0.02506321,0.16681924,-0.00443188,-0.04982672,-0.03953519,-0.03734768,-0.03046197,-0.00184061,-0.05117871,0.04100201,-0.03771582,0.02387724,-0.05483918,0.03213106,-0.01731711,0.05330122,-0.02583439,0.00564227,0.03656555,0.00341363,0.01808398,0.05203047,0.0262147,0.03320788,0.05562561,0.09139276,0.02165764,0.01736782,-0.05944949,-0.03902522,0.07729731,0.02372699,-0.0388418,0.0320659,0.01535334,0.06043088,-0.05145354,0.06013269,-0.00163307,-0.02804422,-0.00388717,0.02848883,0.014557,0.01806558,-0.0287785,0.10654236,-0.01864743,-0.06335968,-0.07247795,-0.07234298,0.11250772,-0.09578699,0.05381597,-0.06977982,-0.02919655,0.01919865,0.01094443,-0.07466987,-0.01838569,-0.10102746,0.04667117,0.06136182,0.08251123,0.02002263,0.09641614,-0.00020405,-0.01570021,0.01397705,0.02630806,-0.0068536,0.12468918,-0.02749268,9.53e-06,-0.0707569,-0.063018,-0.09111914,0.01559493,0.11931112,-0.04321367,-0.07364233,-0.02077792,-0.01419308,0.05984799,-0.04708893,-0.03764167,0.02300125,0.02359455,-0.04018813,0.13540658,0.02366298,-0.00240189,0.00402608,-0.0650957,-0.07057435,0.07854687,-0.02432751,-0.0235899,-0.0140501,0.01670051,0.07741584,0.061962,-0.01141522,0.06163255,-0.06695798,-0.12339372,-0.0615427,0.01096799,0.02923578,-0.03506625,0.06243505,0.06153588,0.01010417,-0.06724059,0.03650037,-0.01535503,0.03157301,0.04878784,0.01759505,0.01547641,-0.09798573,-0.07700512,-0.03879937,0.01373427,-0.01395826,-0.06910116,0.04791124,-0.04911605,-0.001312,0.00875629,-0.00126037,0.05602765,-0.05405784,-0.0251983,0.0579905,-0.01691999,-0.09190322,0.03261579,0.0018117,0.00102227,-0.02654641,0.05639405,-0.06379292,-0.03368804,-0.01860561,-0.02694527,-0.04401382,0.01821375,-0.05065481,0.04845406,-0.00604126,0.01663203,0.03761561,-0.00073623,-0.02184986,0.01512035,0.04636381,-0.11835621,0.02902297,0.00673788,0.0556949,-0.00273164,0.02898894,0.00736834,-0.02163202,-0.03828248,-0.03213925,-0.01834347,0.01545215,1.18e-06,-0.04760821,0.0035625,0.00510231,-0.1090493,0.02973468,-0.09205205,0.02278456,0.04416181,0.04503834,-0.06020985,0.04159578,-0.0968396,-0.02574944,0.00522027,0.02986567,-0.02264934,-0.02315592,0.08836705,-0.02359998,-0.09461567,0.05372279,-0.0545237,-0.0017929,-0.03937388,0.05063887,-0.06927009,-0.03145149,0.00085241,-0.0759966,0.03225748,0.02107459,-0.01012144,0.04294174,0.03000419,0.03079574,-0.0006938,-0.01311022,0.00844375,0.0206824,-0.06331607,-0.07682496,-0.06474521,0.0113663,0.024097,-0.03294518,0.00762742,0.05127814,-0.06843278,0.07361536,5.936e-05,0.0350983,-0.01324472,0.04985158,0.00730365,-0.00261397,-0.06878603,0.06522667,0.05089879,-0.04130639,-0.00643581,0.00564161,0.00024488,0.05368614,0.01754296]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (228, '2025-06-01', 'Pregrado', 'El proyecto consiste en el diseño, desarrollo e implementación de un Sistema de Gestión Informativa y Administrativa para el Concejo Municipal de Valera, estado Trujillo, con el objetivo primordial de modernizar la estructura comunicacional y administrativa de la institución mediante la sustitución de procesos analógicos por un ecosistema digital robusto basado en software libre y en estricto cumplimiento con la Ley de Infogobierno, centralizando la gestión de documentos oficiales, gacetas, noticias y atención al ciudadano para permitir una interacción fluida entre el ente legislativo municipal y la comunidad a través de metodologías de Investigación-Acción Participativa y tecnologías como PHP, MySQL, HTML y JavaScript que garantizan la soberanía tecnológica, la transparencia administrativa y la preservación digital de la memoria institucional del municipio, integrando de manera cohesiva sus componentes operativos que incluyen el Módulo de Inicio, Módulo de Noticias, Módulo de Gacetas Municipales, Módulo de Participación Ciudadana, Módulo de Gestión de Usuarios, Módulo Administrativo (Dashboard), Módulo de Seguridad y Módulo de Reportes para optimizar los tiempos de respuesta y la eficiencia en el manejo de la información pública', 1, 'Concejo Municipal de Valera', 'Concejo Municipal de Valera, Participación Ciudadana', '2026-10-19 22:37:13.034497', NULL, NULL, 'Desarrollar un Sistema de Gestión Informativa y Administrativa bajo estándares de Software Libre para el Concejo Municipal de Valera, estado Trujillo, con el fin de optimizar el resguardo de la memoria legislativa y fortalecer los mecanismos de participación ciudadana a través de un entorno web seguro y eficiente.', true, '[0.07819028,0.08105722,0.00426964,-0.08926959,0.02258009,-0.02031332,0.01962984,0.05373757,-0.020948,0.03765104,0.03548792,-0.01581751,-0.02637129,0.02470871,-0.06601699,-0.00573534,0.0353636,-0.03411867,0.02427155,-0.03434717,-0.00856214,-0.08421431,-0.03188438,-0.00352048,-0.01112065,-0.02204176,0.00586185,0.04148907,0.00166045,0.01661092,-0.01311753,0.02347128,0.05637007,-0.00172705,0.01846288,0.06242594,0.07334887,0.00568764,0.04071069,0.07950599,0.06119768,-0.00149596,-0.06945275,-0.0888511,-0.00130178,0.0114692,0.09659099,-0.10126519,-0.05804147,-0.05770351,-0.00601926,-0.00087869,0.04157025,-0.03424589,-0.08357642,-0.02131292,0.00133809,0.02281085,-0.0266632,0.01028634,0.08009583,0.05006356,-0.02113239,0.02271123,0.02959974,0.07346146,-0.03104113,0.02295427,0.00579273,-0.14446038,0.01442581,0.01238478,0.02673473,0.00028171,-0.07243697,-0.02090819,0.02067358,0.04799597,0.05128873,-0.10536333,0.03957039,0.08593382,-0.03012024,-0.02563668,-0.03366165,0.00171585,-0.10116456,0.00398506,0.06965246,0.02266926,0.08688988,0.03818362,0.01064365,-0.04823944,0.04610391,0.04001005,-0.02962493,-0.06998111,0.04782291,0.04663842,-0.07537317,-0.01348713,-0.02532042,-0.06194493,0.02884086,-0.01351624,0.08286477,-0.00071129,0.05392367,-0.00704221,-0.0068207,-0.04219682,-0.05898681,-0.0437308,-0.04883956,-0.01928723,-0.10314072,-0.01217743,0.0368406,-0.10018312,-0.06004478,-0.04581519,-0.04399551,-0.06617437,0.09997027,0.07065364,-0.02003758,3.72e-06,0.03267689,0.01551409,-0.00156118,0.06101062,-0.00244794,-0.00588031,-0.00319069,-0.06520792,-0.00015904,-0.06406025,-0.06666654,-0.00426251,-0.06631451,-0.13739014,0.04110035,-0.03916586,-0.04195458,0.02971602,0.07089164,0.04623001,-0.06592898,0.00027329,-0.02732547,-0.06855992,0.02644258,0.05209787,0.01752333,-0.0311919,-0.00038156,0.02286007,-0.01260122,0.06959138,0.05883163,-0.02117962,0.04629637,-0.01247535,0.00181618,0.06417643,0.01562292,-0.01030921,-0.07265448,0.01811935,0.07169291,-0.04582876,0.0578552,0.00873797,0.04550501,-0.03169799,0.06700051,0.02719523,0.03501331,-0.0055879,-0.08658201,0.02404326,-0.00748028,-0.05130095,0.00169004,-0.02433413,0.05325435,0.00637146,-0.01233166,-0.0068219,-0.08953171,-0.00422572,0.07326964,-0.01928016,-0.01198765,-0.05206594,0.11241298,0.04183228,-0.02399058,-0.05119684,0.01590722,0.06080443,-0.04573438,0.06173978,-0.01969319,-0.02204628,0.00255494,-0.00074947,-0.07512543,0.01537558,-0.0216779,-0.02080232,0.1803626,0.01856373,0.03612136,0.06751841,0.05790999,0.04944448,-0.02100491,0.00295149,-0.07647481,0.08403872,-0.01379434,1.25e-05,0.02488351,0.02462198,-0.09503459,-0.00286114,-0.07133658,0.036978,-0.10584133,-0.07925034,-0.04790065,0.01037609,0.0656535,-0.11743701,0.05970268,0.00404461,-0.02187824,0.05125699,-0.05138868,-0.08141285,-0.04178763,-0.00416837,-0.04196833,0.03393815,-0.08681709,-0.01033933,-0.02010862,-0.00378007,0.00407307,0.02775642,0.00940788,-0.01823186,-0.09546404,-0.13239014,-0.0905916,0.05237139,-0.03928451,-0.04674527,0.07387829,0.02461494,0.02881839,-0.00328131,0.01507558,-0.00945172,0.0231652,0.00412322,-0.06319192,0.03955248,-0.06404424,-0.07376009,0.01539094,0.00411671,0.04957749,-0.05877744,-0.05663112,-0.12348379,0.00376901,0.03613794,-0.01883942,0.05332355,-0.16208193,-0.02723738,0.05629929,-0.02633277,0.09341019,0.03411966,0.01149647,0.07098163,-0.03265578,0.00271209,-0.01560908,0.00209161,0.04525211,0.00763819,-0.10960605,-0.06424029,-0.01064282,-0.00382197,-0.02532563,-0.02082029,0.01427069,0.00661419,-0.0735943,0.07611935,-0.02836915,-0.149873,-0.02585124,0.04710622,0.06151349,0.0279681,0.08024551,0.03180337,0.00936922,0.04825601,-0.03182795,0.06314349,0.02218656,1.52e-06,-0.04529103,0.06478298,-0.05242507,-0.02986692,-0.02491964,-0.052177,0.01443059,-0.00189338,0.05371446,-0.03348849,0.02093916,-0.02819911,-0.06687096,-0.08820083,0.06412426,-0.04641572,-0.08528968,0.00938372,-0.05565659,-0.06277892,0.06103334,-0.0838279,-0.08689325,-0.00224475,0.02725843,-0.04887494,-0.01548872,0.00278566,0.04148186,0.04154317,0.02856583,0.06576906,0.00818318,-0.00691215,0.10186926,-0.01202408,-0.01052063,0.04470683,0.01400418,-0.00622923,0.01812928,-0.08572125,-0.07515501,0.00314807,-0.07539124,-0.01082364,0.03190605,0.01542673,0.05449339,-0.02669312,-0.0176276,0.01747697,-0.03605636,0.07084031,0.02099463,-0.0197782,0.05659749,0.03136453,-0.070826,-0.06968989,-0.01460495,-0.01521038,0.03506,0.01098464]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (230, '2026-10-19', 'Pregrado', 'El Programa Nacional de Formación (PNF) en Turísmo, en su búsqueda por optimizar la planificación y gestión de las actividades turísticas, ha implementado el uso de un Sistema de Información Geográfica (SIG) enfocado en las potencialidades del sector. Este SIG se presenta como una herramienta fundamental para la identificación, análisis y visualización espacial de los recursos y atractivos turísticos existentes en el territorio. Mediante este sistema, se logra una recopilación y organización eficiente de datos georreferenciados, que incluyen información sobre sitios de interés natural y cultural, infraestructura turística, servicios disponibles, vías de acceso y patrones de flujo de visitantes, entre otros. La integración de esta información en un entorno geográfico que permite a los estudiantes y profesionales del Programa Nacional de Formación en Turismo (PNF Turismo), una comprensión más profunda de la distribución espacial de los recursos y las interrelaciones entre ellos. En el ámbito de la gestión de proyectos, el SIG juega un papel crucial al facilitar la toma de decisiones informadas. Permite la evaluación de la viabilidad de nuevos proyectos turísticos al considerar factores geográficos como la accesibilidad, la proximidad a otros atractivos y la disponibilidad de servicios. Asimismo, ayuda en la planificación de rutas turísticas, la identificación de áreas con alto potencial de desarrollo y la minimización de impactos ambientales negativos. La capacidad de sobreponer diferentes capas de información geográfica brinda una perspectiva integral para la formulación, ejecución y seguimiento de proyectos turísticos de manera más eficiente y sostenible, contribuyendo así al desarrollo estratégico del sector.', 1, 'Programa Nacional de Formación (PNF Turísmo) UPTTMBI "La Beatríz" G-20005902-8', 'SIG, PNF Turísmo, gestión de proyectos, potencialidades turísticas, Sistema de información, Modelado de Datos, planificación sostenible, georreferenciación', '2026-10-19 22:53:13.201708', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO.', true, '[-0.03266859,-0.05481557,-0.01500835,-0.05664782,0.04091458,-0.00777088,0.02399533,0.01976924,0.02065715,0.02395406,0.00911608,0.05134427,-0.05429787,0.01590052,-0.0305716,-0.10051427,-0.14117587,-0.01948768,0.01576998,-0.04295548,-0.07814847,-0.07149598,0.05013549,0.01097328,-0.08345335,-0.04612983,-0.01676197,0.10678096,0.02128215,-0.02876729,0.02487973,0.05612829,-0.01613973,0.04431897,0.03380598,0.09709823,-0.07636278,-0.00857974,-0.0068833,-0.02098312,0.07485809,-0.11652703,0.02997407,-0.01776399,0.03988179,0.03434804,0.02512232,-0.02420357,-0.03105028,-0.04494475,-0.0163694,0.01731667,0.00228982,-0.04931305,0.01713068,-0.03699953,0.01022702,-0.08951874,0.00862117,0.00027686,0.04775099,0.00871569,-0.06539006,-0.03030038,-0.02757938,0.08672159,0.00549834,-0.04467345,0.01917215,-0.0723447,-0.01407828,0.04451193,-0.08605868,-0.03105763,-0.05555062,0.02954867,0.00337645,-0.00463024,-0.05764127,-0.06651009,0.09011631,0.15236676,0.02349242,-0.00257436,-0.01777649,-0.11353958,-0.08409864,0.01607337,0.08924613,-0.01924348,0.10196439,0.04723179,0.01891916,0.09148492,-0.03377896,0.01791867,-0.03965631,-0.05563015,-0.03529097,0.02604593,0.0113647,-0.05615569,0.055524,0.01242595,0.00835094,-0.01215471,-0.01231504,0.02914377,0.05332609,0.06339917,-0.05170958,0.00874606,0.01141541,-0.09185595,-0.04438553,-0.0072481,-0.10512991,-0.00782779,-0.01053947,-0.09106806,-0.06002698,-0.0724122,-0.04368505,-0.05898706,-0.01818502,-0.01759,-0.00363017,3.85e-06,-0.02486991,0.04194179,0.08040651,-0.04158858,0.08837443,-0.00061498,0.01987559,-0.05263278,0.02327287,-0.03461594,-0.04425803,0.01162682,-0.09883853,0.01524734,0.03817225,-0.09506087,-0.00528205,0.03660425,-0.04152716,0.04918613,0.02287305,-0.05535353,0.01844861,-0.06862915,0.09853647,0.0188266,-0.04168571,0.0378296,-0.13776799,-0.03298313,0.09639727,-0.048787,0.0145295,0.02653048,0.02261752,-0.1356183,-0.01717978,0.00079551,0.07195402,-0.01507808,0.02165842,0.01590133,0.05505887,-0.01188152,0.0126415,-0.00742576,-0.01349372,0.06306623,0.01129308,-0.00542859,0.09794403,0.06106098,-0.08598254,0.03187351,0.14493209,0.0143424,0.06561318,-0.04510239,0.01759776,0.04782199,0.05942462,0.01865397,0.0388931,-0.03584156,0.03754611,-0.07218126,0.03391259,-0.02447695,0.07191157,-0.05740655,0.00270791,0.04316529,-0.04869494,0.0363035,0.10024954,-0.08922831,-0.02906042,0.05854895,0.02998675,0.00540941,-0.02867342,0.05631537,-0.00243343,0.02742557,-0.07685944,0.03572543,0.0347381,0.11703881,-0.02762775,-0.0579261,-0.01820707,-0.0771479,-0.03009968,-0.00330799,-0.011416,1.216e-05,-0.04407038,-0.02878478,-0.05439917,-0.03647002,-0.0571322,-0.01021063,0.00795222,-0.03545458,-0.00897223,0.07003138,0.01416386,-0.0111363,0.05854963,-0.05901057,0.05639051,-0.00803223,0.04040967,0.01032726,-0.10661688,-0.01589068,-0.0324788,-0.00723993,-0.07428296,0.0343954,-0.02223795,-0.03550749,0.05436419,0.07236759,-0.03939158,0.09740277,-0.08464311,0.01512519,-0.05337198,0.02661889,0.00360459,-0.12623969,0.06106018,-0.01013526,-0.06256681,-0.04980559,0.07674106,0.04653826,0.00903876,-0.01727625,-0.07414659,0.01397805,-0.01372694,-0.05103368,-0.04017667,0.04778844,0.01871261,0.07788889,-0.01196533,-0.02277494,0.03019241,0.08393379,0.00948035,0.02972258,-0.08046342,-0.05616148,0.03273635,-0.00452142,0.03984019,0.0284952,0.03821485,0.0371907,-0.04577001,0.01268389,0.11429595,-0.06071339,-0.01824368,0.0822757,0.00484592,-0.0395363,0.00228833,0.0425891,0.05162881,0.10779798,0.01942356,0.02879104,0.05761775,-0.04058722,0.00312417,-0.04129923,0.00595055,-0.01891209,0.07456253,-0.06673353,0.0139114,-0.00272756,0.02067023,-0.01928939,0.01084628,0.03408503,-0.02126645,1.32e-06,-0.11238482,-0.00138377,-0.03462357,-0.12952413,-0.05862784,-0.03096302,0.10359288,-0.01107297,-0.01191677,-0.03922995,0.0507744,-0.03042132,-0.02809609,-0.03514614,-0.00596848,-0.02244835,0.02267701,0.09006093,-0.02837138,0.03848759,0.0383084,0.00605661,-0.11524963,0.03184065,0.02682506,-0.00974362,-0.00241109,-0.030198,0.02314014,0.01424828,0.04173432,-0.09953936,-0.00912212,0.08799762,0.03615496,0.06950657,-0.0105969,-0.03542807,-0.02645984,0.01856081,-0.03208818,-0.07626469,-0.04024109,-0.02194967,-0.06297683,0.05305121,-0.04512774,-0.00624685,0.03164847,0.01406636,0.02636648,0.02173487,-0.0203033,-0.05023521,-0.01848288,0.03584537,0.00263999,0.02977978,-0.01764462,0.03323152,-0.06701786,-0.0450238,0.00530235,0.04640057]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (231, '2025-07-01', 'Pregrado', 'Este informe detalla la importancia de las aplicaciones web en el entorno digital actual, destacando cómo potencian la visibilidad, credibilidad, comunicación y promoción de empresas.Se propone el desarrollo de una aplicación web para la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. El objetivo es crear un sitio web atractivo, funcional, informativo y efectivo que satisfaga las necesidades de su público y objetivos específicos.Para lograrlo, se emplearán tecnologías como PHP, CSS, HTML, MySQL y JavaScript. El proyecto se gestionará bajo la metodología XP (Programación Extrema), utilizando iteraciones o ciclos de desarrollo cortos. Esto permitirá priorizar funcionalidades clave valoradas por la empresa, asegurando entregas incrementales y un producto de calidad. Se mantendrá una comunicación constante con la stakeholder de la empresa para alinear el desarrollo con sus expectativas.El proceso se enfocará en las mejores prácticas de diseño, programación y seguridad web, priorizando la experiencia del usuario (UX), la accesibilidad y la funcionalidad para ofrecer un sitio web profesional y fácil de usar', 1, 'EMPRESA TRUJILLANA DE PROYECTOS', 'Sistema de Información Web, Metodología XP, Programación, Base de Datos, PHPMyAdmin', '2026-10-19 22:53:22.821568', NULL, NULL, 'Implementar uNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO.', true, '[-0.08947936,-0.05444922,-0.09383405,-0.07431947,-0.00199883,0.01472857,0.04310104,0.08251339,-0.06179394,-0.02053205,0.07786464,-0.00219394,-0.00026472,0.01777704,-0.02134172,0.04397387,0.04366298,0.02432778,0.05518765,0.04293027,0.0546795,-0.08533489,0.00571589,0.06067509,0.0107392,0.01583799,-0.0391143,0.07632867,0.01892451,-0.08172945,-0.02794152,-0.03037092,0.11782266,0.06150804,0.02506287,0.07108928,-0.03916039,-0.03179685,-0.01468579,0.01185591,0.00103282,-0.04911231,-0.06004904,-0.03088515,-0.02496867,-0.09010173,0.06379049,0.1161484,-0.09304413,0.06118575,0.01250917,-0.0741429,0.03010365,-0.07584315,-0.04774102,-0.05013709,-0.06974279,-0.04663298,0.02785317,-0.00918606,0.08785386,0.10510894,0.05324986,0.05450155,-0.00223919,0.02911888,0.00519363,0.02371937,-0.07663217,-0.0460235,0.00696795,-0.02412731,-0.04376789,0.03541076,-0.05315779,0.02280149,-0.00600015,0.0286881,-0.04603543,-0.02930448,0.05768269,-0.05707103,0.00287678,0.06461727,-0.00655497,-0.00684172,0.00806953,0.00910347,0.0868476,0.02475447,-0.03547863,0.00503944,0.02787498,-0.05305306,-0.01557419,0.04585181,0.02110362,-0.01344723,0.0020614,0.05871013,-0.0417621,-0.04508612,0.00922158,-0.08240738,-0.05414518,-0.09594045,0.0323114,0.07894097,0.09305618,0.02949842,-0.08134985,-0.00792167,0.01107127,-0.047758,-0.05313798,-0.00181213,-0.12349984,-0.0145663,0.09697556,-0.0300324,-0.00661373,0.08824965,-0.02647169,-0.01798419,-0.00842498,-0.09383534,-0.000993,4.8e-06,0.01533692,0.05059552,0.00759235,0.02066884,0.04204201,0.02802294,0.02264576,-0.0176354,-0.02949525,-0.12445073,-0.09188221,0.08727086,-0.00745545,0.0609324,-0.02292029,-0.0173738,-0.06228904,0.03976226,0.07992735,-0.0081101,-0.02626875,-0.02629956,-0.04532816,0.05774701,-0.12792267,0.06995551,-0.01000416,0.16590872,-0.02215644,0.04428385,0.03779252,-0.08135054,0.04118322,-0.06757284,0.09067738,-0.04600936,0.02703827,-0.03117145,-0.01761628,0.04353205,-0.04903574,-0.00523041,-0.04468279,0.03366419,-0.0434185,0.04548985,-0.01139011,0.01284066,-0.02537189,-0.00244589,-0.01941834,0.03782961,-0.02372399,0.01370125,-0.02912661,0.00380277,-0.02232445,-0.00237745,-0.02428737,-0.05987295,0.03682926,0.04568124,0.02138711,-0.06693547,-0.13096514,-0.00419156,0.02137821,-0.04560611,0.09951053,-0.08752422,-0.02752703,0.03553073,0.02566413,0.00781889,-0.02470621,0.00825038,-0.00386171,0.00319569,0.03251074,0.00506893,-0.05711053,0.01275683,0.10367279,-0.02809889,0.02092539,-0.052282,0.01323557,-0.01764325,-0.02703795,0.05583035,-0.00670232,0.01108468,-0.05013723,-0.01201025,0.10174526,1.44e-05,-0.07113949,-0.00668832,0.0417622,-0.00227698,-0.02292458,0.06808012,0.02476436,0.02597669,0.06510377,-0.02566165,0.06389256,-0.05244986,-0.05766733,-0.08551574,-0.032413,0.04338897,0.05458951,-0.05259453,-0.04197289,-0.07146935,0.03098011,-0.06789929,-0.04234151,-0.00913963,0.04153016,0.01274194,0.06850029,-0.0168514,-0.01512401,0.07418368,0.04675361,-0.06645414,-0.02011631,0.03772708,-0.03198376,-0.02207066,0.04234051,-0.00165272,0.02087466,-0.05959335,0.06167671,0.01511764,-0.00713716,-0.04719961,-0.08520624,0.04012964,-0.05044439,-0.09206722,0.02366813,-0.04923251,0.08019874,-0.00834119,0.0172648,-0.08224292,-0.02822841,0.08131553,-0.01235129,0.0556413,-0.05206913,0.00386408,-0.03249244,0.01742102,-0.03489679,0.05166479,0.0007072,0.05082371,-0.03290075,0.02872631,-0.04918149,-0.07109599,0.05191138,-0.03097847,-0.03922723,0.00178333,-0.00451527,-0.06926898,0.01011514,0.03026974,-0.02838238,-0.00965166,-0.03276855,0.02289856,0.04286087,-0.12098327,0.04117426,-0.00394334,-0.02868959,-0.0204866,-0.02945652,0.02921588,-0.02393543,0.03312753,-0.09246565,0.03505235,0.03670538,1.52e-06,-0.03701342,-0.04904486,-0.01103518,-0.05024046,0.02602908,0.01798248,0.04156889,0.02147029,0.07241672,-0.06628784,-0.03339778,-0.05453679,-0.06871243,0.02739034,0.04838681,0.00605789,0.04363057,0.03708947,-0.05531321,-0.03258645,0.01123919,-0.00102468,0.00159509,-0.07413057,0.01933307,0.01493916,-0.11110627,-0.04031511,-0.08943142,0.06606462,-0.00867979,0.04858961,-0.06051183,-0.05242167,-0.01846051,0.03798546,0.01403161,-0.05594742,-0.12281137,0.05648204,0.02090466,-0.02177138,-0.01945703,-0.0242885,0.08250369,0.02683009,-0.03356882,0.0678854,0.01480423,0.00760957,0.00413287,-0.01996458,-0.0203427,0.01265948,-0.05787877,-0.11238152,0.07525691,0.00071997,-0.07826686,0.02599438,0.05113411,-0.05206229,0.19875129,0.00958577]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (232, '2026-10-19', 'Pregrado', 'La presente investigación se enfoca en la Optimización del Sistema de Información para el Control de Matrícula en el CAIPA Trujillo Versión 2.0, un proyecto sociotecnológico desarrollado bajo el paradigma cualitativo, la Investigación-Acción Participativa (IAP) y la metodología ágil de Programación Extrema (XP). Con el fin de superar las limitaciones de gestión de datos detectadas en la institución, el equipo de investigadores de la UPTT "Mario Briceño Iragorry" reestructuró el sistema utilizando una arquitectura Modelo-Vista-Controlador (MVC) nativa en PHP, con persistencia en una base de datos relacional MySQL normalizada en Tercera Forma Normal (3FN). A lo largo de cuatro iteraciones basadas en 11 Historias de Usuario, se rediseñó por completo la interfaz gráfica bajo un estilo Glassmorphism con hojas de estilo CSS puras, se optimizaron las consultas SQL mediante PDO incorporando búsquedas inteligentes con paginación, y se desarrolló el nuevo módulo de Gestión Integral del Personal a través de un asistente visual escalonado. El aseguramiento del sistema fue verificado mediante matrices de prueba de caja negra y caja blanca, consolidando una solución tecnológica local, rápida y segura de alta pertinencia social que optimiza integralmente el control de estudio y personal en el centro educativo.', 1, 'CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', '', '2026-10-19 22:53:24.908561', NULL, NULL, 'Optimizar el sISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0.', true, '[0.00525249,-0.03014527,-0.05873073,-0.01234172,-0.0331612,0.02228377,0.05521128,0.05264781,0.00080507,0.01091531,0.09030666,-0.0063512,0.02884738,0.00375086,-0.0636935,-0.01920271,-0.00526715,0.02880041,0.03182225,0.01539216,0.01784064,-0.00856551,0.04521498,-0.0059031,-0.10161181,0.01049776,-0.02205692,-0.0652743,0.05820256,0.03057413,0.03259029,-0.07390113,0.12371252,-0.01733212,-0.10551349,0.04136803,0.01508781,0.01636511,-0.05178712,-0.0223373,-0.0088409,0.01373096,0.04774292,-0.08080682,0.01583239,-0.02050522,0.05091538,-0.09562438,-0.04857554,0.00766392,-0.1117059,-0.0129221,0.10079923,-0.02988627,-0.01306235,-0.07520388,0.0016582,0.02157997,0.02404946,0.03987955,-0.02611629,-0.00635894,0.06863957,-0.01894619,0.09867984,0.0862783,-0.05720236,-0.04596882,0.02657846,0.00567022,-0.01591148,0.02646216,0.02258833,0.04612564,-0.0123023,-0.07734987,-0.01766891,-0.00571283,0.06207876,-0.04840043,0.06917857,0.03433422,-0.02046585,0.04290792,-0.02224988,-0.0107394,-0.05868659,0.03270987,0.09272465,0.04527988,0.10857284,0.07850095,0.01192897,-0.05348815,-0.01620428,-0.05564994,-0.00478326,-0.02140763,-0.09535065,-0.01852564,-0.01211689,-0.0591609,0.01345305,0.02374526,-0.02689832,-0.07280819,0.05107308,-0.01525284,-0.01704077,0.01884151,-0.02341046,0.03222462,-0.05818675,-0.02247337,0.03265487,0.0203809,-0.03104436,0.00472992,0.0552023,0.06316242,0.01174808,-0.06457293,-0.00354243,-0.02036676,0.09703836,-0.05488743,-0.09006657,3.48e-06,-0.10544359,-0.03172397,-0.00080661,0.02606048,-0.0025999,0.00237882,-0.02445805,-0.05006359,0.0633358,-0.05023762,-0.00152681,-0.0041834,0.00030656,0.08456687,0.06993721,-0.07703507,-0.0223885,-0.00542322,0.03346112,-0.05924612,0.04171147,-0.03323137,0.00240394,0.01646247,0.01811549,-0.0097041,0.02432748,0.03299367,0.0324242,-0.00411951,0.01098538,-0.00689047,-0.078932,-0.08490656,0.03926137,0.04313205,0.10522517,0.0374508,0.04992972,0.04030491,0.02946488,0.00745365,0.05598436,-0.01957484,-0.05516353,0.01390286,0.04399922,0.00405767,0.01480792,-0.09790161,0.0199835,0.00797673,-0.08982537,-0.10769418,0.04054702,-0.09037222,-0.02547714,-0.07477898,-0.06447649,0.02455038,0.12089564,-0.13606896,0.02922038,-0.05506954,-0.01408898,-0.05867683,0.08205788,-0.04039967,0.15208349,-0.05888976,-0.07917382,0.02873195,0.0506148,0.00070189,-0.01106861,-0.00889189,-0.10219626,0.03717439,-0.0465786,-0.01944704,-0.01978007,0.03884054,-0.05188564,-0.00140708,0.07056005,0.01330775,-0.02905746,0.11817083,-0.07676782,-0.00216084,0.03323519,-0.00474704,-0.0202259,0.08335614,-0.07006323,1.189e-05,-0.06263604,-0.07169929,-0.03060463,-0.04133392,-0.05051423,-0.01832712,0.00599554,-0.0144099,0.0290901,-0.00027009,0.07545049,-0.08547653,0.03442541,-0.06216161,0.04433318,0.06106136,-0.05174917,0.01707287,-0.03544139,-0.12250135,-0.03320974,0.00886843,-0.02447359,0.0114761,-0.01051629,-0.00362118,-0.05050143,0.131589,0.01856208,0.0648994,-0.15453751,-0.07589003,-0.01794543,0.08158175,0.01276323,-0.02479531,-0.03301247,-0.05979332,-0.06283419,0.00347804,0.06807247,0.0580852,-0.09294204,-0.00654707,0.024461,0.00817731,0.02011027,-0.0559974,-0.05707749,0.03616335,0.11309937,-0.03019412,0.02179803,-0.05000302,-0.02217957,0.08011856,-0.01154313,-0.05527431,-0.02314466,0.01658797,-0.00169331,-0.01814226,-0.07182129,-0.00351927,0.00466726,0.06505019,-0.04600872,0.08517047,0.0485438,-0.04804565,0.02572953,-0.03628873,0.02031178,-0.04672545,-0.06102326,-0.00059246,-0.02133612,0.04378407,-0.03977402,-0.0205288,-0.03607888,0.04102243,0.05431916,-0.01061801,-0.03131074,0.07852435,0.00184925,0.00392969,0.01459857,-0.02571644,-0.0443811,-0.02092518,0.03080759,-0.02297012,-0.07021257,1.37e-06,-0.08802737,-0.04435941,-0.04717878,-0.01978087,0.04726201,0.09505194,-0.05614064,-0.00708508,-0.05915808,0.0534234,-0.01114869,-0.01786125,-0.0254119,-0.01270459,0.02220919,-0.02132873,0.0421117,0.06318601,0.02612977,-0.07859515,0.08471721,-0.04944552,-0.11105694,0.00769072,-0.0373795,0.01081515,-0.09019353,-0.01013803,-0.02537627,0.02976664,-0.01725409,-0.07137058,-0.01364662,0.01941032,0.04405463,-0.00558785,-0.05195187,-0.04062951,0.01847194,0.08755383,0.11540557,-0.03893924,0.02810821,0.01986194,-0.02225224,0.03100202,-0.03083354,-0.05136589,0.03744751,-0.00564337,0.05403604,0.00870095,0.01157975,0.06290792,0.00239145,-0.07520028,0.00823142,0.0224121,-0.10563594,0.08200739,0.06588846,0.06349012,-0.02976463,-0.01565195]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (233, '2025-11-01', 'Pregrado', 'Este proyecto consiste en el diseño, desarrollo e implementación de una aplicación web administrativa robusta y centralizada, optimizada para gestionar los procesos operativos, el control de registros y la comunicación interna dentro de la comunidad o establecimiento. La página web funcionará como un ecosistema digital integral que digitalizará tareas tradicionalmente manuales, tales como la gestión de usuarios, el inventario de recursos, el procesamiento de solicitudes y la generación de reportes detallados en tiempo real. Mediante una interfaz intuitiva, segura y de fácil acceso, el sistema optimizará la toma de decisiones basada en datos, reducirá los tiempos de respuesta administrativa y garantizará la transparencia en el manejo de la información, adaptándose a las necesidades estructurales del entorno y dejando una infraestructura tecnológica sostenible y escalable para el beneficio a largo plazo de la organización.', 1, 'Consejo Comunal de la Comunidad “la Ciénega"', 'Ciénaga, Autogobierno, Desarrollo Web, Capacitación, Tecnología.', '2026-10-19 22:53:26.807178', NULL, NULL, 'Desarrollar e implementar el Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega.', true, '[-0.04649912,-0.04314424,-0.11006546,-0.08568046,-0.08066212,0.00862768,0.02503459,0.01249419,-0.10332695,0.00687089,0.08047842,-0.00170108,0.0275646,0.01626474,-0.01502144,0.01332385,-0.01440224,-0.0414169,-0.03497408,-0.06062341,0.01583752,-0.06507224,-0.01399313,0.06798043,-0.08167981,-0.04049673,-0.01250942,0.00745016,0.07626014,-0.00463565,-0.01604384,-0.07880585,0.08183817,0.08874787,0.0134883,0.03973241,-0.0171644,-0.06728638,0.03258354,0.02117258,-0.00027014,0.05000894,-0.07493508,-0.07809679,-0.05854879,-0.02867139,-0.01451799,-0.00890774,-0.15313192,-0.02857973,0.05482386,-0.04308201,0.02001499,-0.08386085,-0.01310314,0.02540056,-0.01065495,-0.04461091,0.02733772,-0.02494752,0.06868989,0.0232307,-0.01899766,0.05644634,0.00442807,0.03751214,0.03306325,-0.02950182,0.0072229,-0.09300986,-0.06610754,0.00212198,-0.07606119,0.0168296,-0.00153369,-0.02594217,-0.03505554,0.05606126,-0.05738638,-0.07164787,0.11503414,0.04866664,0.00096577,0.10165675,0.01471457,-0.03910043,-0.05433782,0.04506242,0.06835237,-0.04911384,-0.01190461,0.02933461,0.04454198,-0.02275096,-0.0514633,0.03554941,0.0223249,-0.02627979,-0.09614365,0.07195397,0.00611344,-0.02949959,0.04952589,-0.06050689,-0.03291914,-0.02030549,0.05229311,0.0322481,0.15188175,0.01147378,-0.0930686,-0.02445537,-0.03979752,-0.11938397,-0.10805105,0.00501858,-0.08113597,0.0014066,0.12097884,-0.04591666,0.04461683,-0.01221207,-0.03264536,-0.02127891,0.12378687,-0.05259703,-0.00260233,4.23e-06,0.049192,0.00056022,-0.03799126,0.00110573,0.08943816,0.02603456,0.01278004,-0.06009154,-0.0298915,-0.09288187,-0.00554521,0.03625141,-0.00526645,-0.05387134,0.06911015,0.00749905,0.02779164,0.0126333,0.07500218,0.0502132,0.00837839,-0.00986068,-0.00066527,0.03259531,0.04652044,0.03103281,-0.06798222,0.06567815,0.04700873,0.04326065,0.08654144,-0.0379293,-0.024403,0.05808287,0.03511325,-0.00447917,-0.02032289,-0.00491901,0.06170306,0.01432878,-0.1010567,-0.04210098,-0.02344363,0.06049841,0.03486075,-0.05094489,-0.02600166,-0.03719208,0.02159173,-0.00548542,-0.04939319,-0.00418143,0.00720247,0.06283585,-0.02661834,-0.02608555,0.00135299,0.03414916,0.00168359,0.01449265,0.06910767,-0.01431439,-0.04522787,0.01165412,0.00106579,-0.02451174,-0.00233872,0.00429988,0.1503085,-0.00382266,-0.06208476,-0.00320808,-0.01580544,0.06154852,-0.07561197,0.03205602,0.06881206,-0.00276917,-0.09154209,-0.00169765,-0.02029159,0.01111889,0.04150407,0.00035524,0.00435142,0.0161458,-0.00280909,0.11456207,0.0019874,0.06941968,0.01864331,0.06159134,0.00302081,0.1378322,0.02260147,1.321e-05,-0.04447304,-0.01646485,-0.03601159,0.00099333,0.01670186,0.00986744,-0.02636831,-0.11457954,-0.05873608,0.00555641,0.03128945,-0.06818839,-0.00890628,0.00454725,0.00662086,0.06435948,0.04532187,-0.00553564,0.00279701,-0.01945172,-0.00092552,0.0198702,-0.07545602,-0.04473731,0.00390019,-0.01901357,-0.02882045,-0.01185178,0.05787306,0.00354852,-0.06633955,-0.06885169,-0.01650588,0.01288091,-0.04817112,-0.03796607,-0.07619726,0.04554587,-0.05194133,-0.04685023,0.02336451,0.01224111,-0.01642432,-0.02137572,0.02348142,0.09980012,-0.06907458,-0.12827678,-0.0559244,0.06820956,0.01357393,0.01144372,0.07764114,-0.09649572,-0.01541227,0.06900196,0.03069874,-0.03342374,-0.01536449,-0.01915477,-0.00868937,-0.00783411,-0.04766533,0.1211655,0.00963918,0.09874087,-0.02110527,0.10444062,-0.02539763,0.00707885,0.04950069,-0.02182751,-0.10223831,-0.04143729,-0.07956776,-0.00273718,-0.050711,-0.00231237,-0.03219557,0.02890057,-0.03551856,0.06249664,0.04738311,-0.12246357,-0.01959515,0.04771802,0.03456575,0.00714775,0.0033648,-0.02819422,-0.00326212,-0.02118426,-0.06476855,-0.10387945,-0.03078322,1.46e-06,-0.05409696,-0.01101732,-0.03988389,-0.02885174,0.10301699,-0.02654631,0.03025959,0.00566228,0.02773014,-0.00057952,-0.03379431,-0.04564485,-0.03638084,0.00690753,-0.01037957,0.02555903,-0.00380935,0.08567524,0.00511156,-0.0466611,-0.00244816,-0.07463584,-0.06030271,-0.00692317,0.00995348,-0.01138956,-0.06599402,0.09749997,-0.06432196,0.01825936,-0.05289737,-6.307e-05,-0.04190343,-0.0353469,0.02095018,0.04914809,-0.05640721,-0.05112104,-0.06399028,-0.11460909,0.0395101,0.02008572,0.05944412,0.04394321,-0.00210751,0.01987753,0.05523205,-0.0445348,0.10159097,-0.02275303,0.03217983,-0.05764946,0.01288167,0.05740196,-0.00139144,-0.05053697,0.08877419,-0.02330089,-0.07014112,0.02950056,-0.00576444,0.01462291,0.07484305,-0.00164806]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (237, '2025-11-01', 'Pregrado', 'El presente trabajo de investigación abordó la problemática de la gestión administrativa ineficiente del Servicio Nacional de Medicina y Ciencias Forenses SENAMECF caracterizada por el uso predominante de procesos manuales y la consecuente dispersión de información vital para la operación forense. Es por ello que implantar un Sistema de Información para Automatizar la Gestión de los procesos administrativos y lógicos del SENAMECF garantizando la trazabilidad y eficiencia del servicio como logros. El proyecto se centró en analizar los procesos críticos, diseñar una arquitectura robusta, desarrollar los módulos funcionales y realizar pruebas exhaustivas del sistema para su validación operativa. La necesidad se detectó en los errores de registro, la lentitud en la búsqueda de expedientes y la gestión manual del inventario de insumos forenses lo cual impacta la calidad de los dictámenes periciales. La pertinencia de la solución radica en su capacidad de estandarizar los flujos de trabajo, automatizar tareas repetitivas convirtiendo la gestión administrativa en un soporte ágil y fiable para las operaciones periciales. El sistema fue desarrollado utilizando la metodología Programación Extrema XP, este enfoque ágil priorizó la entrega continua de valor, la retroalimentación constante con los usuarios finales del SENAMECF y la alta calidad del código a través de técnicas como el desarrollo guiado por pruebas unitarias. La flexibilidad de XP fue crucial para adaptarse a los requerimientos institucionales. El sistema de información implantado generó un impacto significativo al mejorar la eficiencia operacional, los beneficios obtenidos incluyen una alta reducción en el tiempo de procesamiento de documentos y la eliminación de duplicidad de datos, las mejoras implantadas se centran en la automatización del control, generación de reportes gerenciales en tiempo real lo que permitió a la dirección del SENAMECF una toma de decisiones proactivas en esencia. El sistema transformó la gestión administrativa fortaleciendo la capacidad operativa del servicio forense a nivel nacional.', 1, 'SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF', 'Sistema de Información, Gestión Administrativa, SENAMECF, Programación', '2026-10-19 22:57:32.71912', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (238, '2025-11-01', 'Pregrado', 'Este proyecto se centra en la evolución y mejora de la aplicación web para optimizar la gestión de productos y servicios previamente desarrollada en Super Kauchos Gilsa, C.A., empresa ubicada en Valera, Estado Trujillo. Partiendo del sistema ya implementado, el objetivo principal de esta es potenciar la eficiencia operativa, calidad del servicio al cliente y la comercialización de su catálogo de productos y servicios, mediante la incorporación de nuevas funcionalidades avanzadas junto con mejoras en la arquitectura del sistema, que permitan ampliar aún más su alcance y usabilidad. A partir de un diagnóstico técnico de la plataforma existente y la recolección de nuevos requerimientos con el personal clave, se determinó la continuidad de la metodología ágil Extreme Programming (XP). Esta elección se fundamenta en su enfoque iterativo, ideal para la integración continua de módulos y el refactorizado de código. El proceso de mejora del software siguió las etapas de planificación, diseño, codificación, pruebas y lanzamiento, garantizando una transición fluida hacia una versión del sistema más robusta, segura y adaptada a las demandas actuales de la empresa.', 1, 'SUPER KAUCHOS GILSA, C.A', 'Aplicación web, optimizar, gestión, catálogo, metodología, Extreme Programming (XP), codificación, mejora, evolución, potenciar', '2026-10-19 22:58:21.059322', NULL, NULL, 'Optimizar la gestión comercial de la empresa.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (234, '2026-07-01', 'Pregrado', 'En el mundo digital actual, una aplicación web es una herramienta esencial para cualquier empresa, organización o individuo que desee tener una presencia en línea sólida y efectiva. Una aplicación web bien diseñada y funcional puede brindar una serie de beneficios, como:Aumento de la visibilidad y el alcance.

Mejora de la credibilidad y la confianza.

Canal de comunicación directo.

Promoción de productos y servicios.

En este contexto, en la comunidad seleccionada, Instituto Nacional de Capacitación y Educación Socialista (INCES) nos embarcaremos en la creación de una aplicación web que cumpla con sus objetivos específicos y satisfaga las necesidades de sus usuarios. Trabajaremos juntos para desarrollar un sitio web funcional, informativo y efectivo que ayude a dar el siguiente paso en la escala innovadora.', 1, 'Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo', '', '2026-10-19 22:53:29.323203', NULL, NULL, 'Implementar una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo.', true, '[-0.12848125,-0.06903836,-0.0419468,-0.07098429,-0.0788971,-0.00339668,0.02625583,0.00341298,-0.00798401,0.02313581,0.09876085,0.02523538,0.04815195,-0.01472204,-0.10193804,0.01872398,-0.04300206,-0.03431876,0.01406301,-0.03468716,0.04849919,-0.06982058,0.0810353,0.04873743,-0.0097401,0.00156688,0.01917991,-0.00109451,0.05408093,-0.08617743,0.03217459,-0.05095058,0.05517205,0.05265324,-0.03789825,0.03360454,0.06337138,-0.04598868,0.02553741,-0.042279,-0.01412786,-0.02408639,-0.00952791,-0.03502111,-0.02144667,-0.03610964,0.02095563,-0.00356327,-0.11798587,0.02311198,0.0476025,-0.11152619,0.09094813,-0.05415482,0.00416833,-0.05243392,-0.01434748,-0.01883873,0.01639389,-0.02608285,0.02022984,0.0336787,0.02568694,0.0430002,-0.00812712,0.09619223,0.01064026,-0.06435595,-0.02968815,-0.03463305,0.01600793,-0.01709317,-0.0851294,0.00047034,-0.0458823,0.0067611,-0.01249049,0.0376166,-0.03574237,0.02842359,0.04363504,0.01781489,0.00535884,0.08476959,-0.03568252,-0.00016871,-0.1070535,0.05747272,0.10903286,-0.04684338,-0.02974545,-0.05862011,0.1095291,-0.0067187,0.00241823,0.03992609,0.04867741,0.00069555,-0.08649985,0.00529959,-0.01420972,0.00516061,-0.00146512,-0.05427063,0.01773983,-0.00806029,0.03590287,0.0228227,0.10313497,-0.08482549,-0.05806932,-0.04547362,-0.06275049,-0.04619043,-0.05599183,0.03142202,-0.06818024,-0.00611965,0.07845835,-0.06562628,-0.02151451,-0.02546859,-0.1156425,0.01370419,0.05647604,-0.1129347,-0.05313677,4.38e-06,0.08193576,0.05757147,-0.0479774,0.02153095,0.09507117,0.00998814,0.09350811,-0.02669417,-0.05839454,-0.06150085,-0.04167228,0.03723144,-0.05759488,0.05564613,0.04544459,-0.0606019,-0.04992625,-0.00971623,0.13501728,0.02856364,0.01829471,-0.04778164,0.01976161,-0.0190551,0.00159184,0.04231393,-0.1005704,0.11172338,0.04526131,0.03204078,0.03928385,-0.00763306,-0.06004174,-0.01274806,0.02714554,-0.09432212,0.0093023,-0.00338275,0.07373287,0.03064681,0.00816436,-0.01111531,-0.04672984,-0.01530931,0.01685351,-0.07955648,-0.05653311,-0.01028927,-0.00107529,-0.00109453,-0.03971624,0.03619818,-0.00264992,0.02181871,0.06980454,-0.05150144,-0.00786592,0.06853328,0.02946232,0.03114222,0.08799006,-0.0047062,-0.0251383,0.05420147,-0.01702008,0.04774683,0.11076468,-0.0140906,0.11338325,-0.04810184,-0.00104325,-0.05428996,-0.08752032,0.05598206,-0.03455638,0.01391105,-0.02542577,0.00614205,-0.03106437,0.03886811,0.00863276,0.03055807,-0.01848244,0.03439741,0.02958115,-0.01676674,0.03154178,0.06052815,-0.019579,-0.02668642,-0.00081855,-0.04879872,-0.02085365,0.01912565,-0.01692868,1.345e-05,-0.05375039,-0.04541277,-0.02759113,-0.00577216,-0.03596718,-0.01552462,0.03014518,-0.04753349,-0.00337856,0.04127478,0.08499249,-0.05729944,-0.04914354,-0.04532964,0.02942122,0.05924057,-0.01004559,-0.06242606,0.00677399,-0.0725709,-0.00612792,-0.07128748,-0.03390055,-0.01353275,0.02797247,-0.02255168,-0.00872429,0.05249239,0.02609703,0.0413923,-0.10953319,-0.07287016,0.02395191,0.05786832,-0.01883338,-0.0498136,-0.00040228,0.0422551,0.03806359,-0.10390312,0.12705165,-0.04932439,-0.03526622,0.03364551,0.00824796,0.04663954,-0.17542984,-0.06289069,0.00219067,0.01094937,0.0847947,0.06115268,0.09652757,-0.08121193,-0.02719457,0.01103469,0.04337765,0.04170799,0.0180963,-0.05403826,-0.0277727,-0.05019623,-0.01215953,0.1303153,-0.00240352,0.03943121,-0.05963513,0.10704883,-0.07693677,-0.07858649,0.06619104,-0.05423529,0.05610744,-0.02140028,-0.02699194,-0.02226881,-0.05712065,0.05008137,0.04401933,0.01105076,-0.03804642,0.07090961,0.03160618,-0.08305605,0.05192461,0.02519965,0.0033603,0.0117329,-0.03180279,-0.02741469,0.03256245,0.02240109,-0.06815648,0.02452872,-0.00028537,1.47e-06,-0.01348702,-0.01144438,0.02199969,-0.0615832,0.05924602,0.01127676,0.04902834,-0.02244473,-0.00782332,-0.04033113,0.0209871,-0.01496403,-0.04030538,0.04500478,-0.00949462,0.03573726,0.01767591,0.02583759,0.02864568,-0.06660977,0.08956359,-0.00274972,0.01789485,-0.06501357,0.00255896,0.01539115,-0.11790609,0.00284278,-0.09732771,0.02167969,0.0005167,-0.02637091,-0.0514607,-0.05632542,0.00697755,0.06059922,-0.02979714,-0.07235298,-0.06398776,-0.05788332,-0.0626781,-0.02011597,-0.02092533,-0.05843349,0.05172715,0.00714766,-0.01579877,0.01557429,0.03154477,0.04957756,-0.00947047,-0.01592247,0.0251818,-0.03396177,-0.04321282,-0.08751025,0.06904229,-0.03313891,-0.07769654,0.05913757,0.05457661,0.0276067,0.09830966,-0.02307621]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (235, '2025-11-01', 'Pregrado', 'El proyecto tuvo como propósito principal la implementación de un Sistema de Información para la Gestión Académico-Administrativa en la Escuela Técnica Comercial "Pedro García Leal". La necesidad del sistema se identificó mediante un análisis FODA y CAME lo que permitió jerarquizar las prioridades y determinar el problema central. La ejecución se gestionó bajo la metodología SCRUM, donde se realizó la delegación de roles y una definición exhaustiva de los requerimientos, funcionalidades y reglas del nuevo sistema. Posteriormente, esta etapa de planificación se materializó en el Diseño de la Base de Datos y el Diseño de la Interfaz Gráfica de Usuario (GUI), cuyo prototipo visual fue presentado a la comunidad para validación. En consecuencia, el desarrollo se articuló mediante una secuencia de objetivos específicos que abarcaron desde el análisis de procesos de asistencia y registro hasta el desarrollo, la realización de pruebas de integración y aceptación. El desarrollo incluyó la construcción del sistema, pruebas rigurosas de integración y aceptación, y su despliegue en producción. El proyecto culminó con la entrega de un Manual de Usuario y la realización de sesiones de capacitación presenciales para asegurar la correcta adopción del software, logrando así una solución funcional que optimiza integralmente la gestión de la institución.', 1, 'ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL"', '', '2026-10-19 22:55:36.412384', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL.', true, '[-0.04397505,-0.01493651,-0.0789001,-0.03697335,0.00155744,0.02481438,0.00450441,0.05635128,0.06838182,0.00430596,0.09082255,-0.00829636,0.07736365,-3.827e-05,-0.09729368,-0.04008082,0.05511204,-0.07971959,0.01638536,-0.02182689,0.0233471,0.0053881,-0.02214459,0.05468876,-0.07060703,-0.02090063,0.02512948,-0.05288277,0.02653625,-0.04391253,0.02902743,-0.0722477,0.02909714,0.07521385,-0.04626295,-0.03845136,0.03484179,0.00328139,0.04116238,0.01416338,-0.00607902,-0.01797453,-0.02553294,0.05928819,-0.03351293,-0.01338521,0.01527734,-0.0450783,-0.10251425,0.06533073,-0.02589776,0.01498625,0.038486,-0.01297759,-0.06581452,0.02191061,-0.05931727,-0.02031822,0.00936929,0.0632854,0.05117314,0.04768848,0.00016479,0.00303071,-0.04155934,0.04785562,-0.02356742,-0.05268527,-0.02518688,-0.01115813,-0.01087087,0.02231098,0.06145031,-0.00368509,0.00069023,0.00036754,-0.02947422,0.02053251,-0.01097803,-0.06731046,0.03846837,0.10860199,-0.01595063,0.05955764,-0.02161546,-0.06796777,-0.00267129,0.05994805,0.06404474,0.00870334,0.14316796,-0.03808032,0.11491311,0.00129388,0.03728099,-0.00376511,0.00197901,-0.04056031,-0.05706287,-0.01816703,-0.01988682,0.0163286,0.01186901,0.07557757,-0.10996913,-0.04441046,0.01999592,0.01151303,0.03725241,-0.01952753,-0.01990495,0.00307067,-0.09296699,-0.12489692,-0.06548721,0.00401926,-0.05485861,-0.0135192,-0.01294071,-0.05013573,0.00345484,-0.06613491,-0.09011351,-0.00600269,0.100346,-0.04543074,0.06383063,3.62e-06,-0.03989618,0.04088,-0.0517004,0.0527259,0.03921776,0.05994433,0.00203003,-0.05801017,0.00711578,-0.0054605,0.01817054,0.0624975,-0.10525354,0.01366556,0.00840265,0.0757767,-0.02698576,-0.02377693,0.05979701,-0.00071185,0.02282176,-0.02206648,0.0737744,0.0341621,0.09261728,0.03414838,-0.07189928,-0.05991628,-0.04685697,0.02495911,-0.03729393,-0.00391977,0.03730196,0.08895838,0.02915,0.03511696,-0.01515041,-0.00119919,0.04284071,-0.02202383,0.07970188,0.08858327,0.04191862,0.0117603,-0.01375636,-0.04125455,0.06251886,0.05720566,0.09322439,0.0170649,0.04507845,0.0015241,-0.06762377,-0.00227171,0.10573982,-0.08724417,-0.0682936,0.05834183,0.07692176,-0.09484292,0.04091523,0.01669446,0.0249302,0.07168418,-0.0451927,-0.02747931,-0.04366195,-0.09958476,0.13115619,-0.04831975,-0.04400629,0.01001756,-0.0499317,0.14103322,-0.06451417,0.08544653,-0.05089956,-0.12042181,0.01050157,0.02198135,-0.08039935,-0.00917783,0.0146602,0.0271464,0.09973201,0.06217972,-0.00379184,0.15790541,-0.01911175,0.01168648,-0.00504085,0.0599772,-0.02394804,0.06434851,0.04703733,1.17e-05,-0.01909413,-0.12463491,-0.03096749,-0.0265115,-0.03380963,-0.05106782,-0.09393927,-0.05732503,-0.02035827,0.01920892,0.02766558,-0.0362139,0.00535648,0.01452479,0.05740298,0.02880121,0.04034573,-0.07606394,0.00830644,-0.03208909,-0.06479225,0.03996235,0.02546807,-0.04148728,0.04488982,0.02415083,0.00897109,0.09248622,0.00376977,-0.01173565,-0.12761696,-0.05710083,-0.03656008,0.04297155,-0.09464324,-0.03905118,0.04286639,0.04849646,-0.08036177,-0.04620336,0.02966571,-0.01412533,-0.03889108,0.01289344,-0.02426794,-0.03217802,0.00655092,-0.09064451,0.05638613,0.00173351,0.03215561,-0.06468905,0.05110883,-0.07838211,0.01982091,0.05563182,-0.01922551,0.0297931,-0.02286301,-0.0994932,0.0803385,0.05175604,-0.07631391,-0.01516253,0.03688984,0.043504,-0.00163483,0.0472358,-0.01603519,-0.05915413,0.068198,-0.04107568,-0.09647419,-0.07456536,0.02747806,0.01045625,-0.03608322,0.00546172,-0.0245052,-0.01262653,-0.08891456,0.03051327,-0.00088485,-0.08534502,0.00545917,0.02605195,0.04564514,-0.06232996,0.04634736,0.02020192,-0.04721704,-0.0089014,-0.04536366,-0.06938192,-0.02967363,1.27e-06,-0.05733054,-0.02338415,-0.02690335,-0.12181302,0.0421488,-0.06473759,-0.02628679,0.031336,0.0094308,-0.01227121,0.04701354,-0.0249095,0.02537935,0.05344626,0.02988621,-0.06256122,0.00566502,0.06604379,-0.02401842,-0.07405218,-0.04761236,-0.05387805,0.03239124,-0.05207163,-0.00371518,-0.04083521,0.00624255,-0.01078413,-0.01398123,0.0373776,-0.00971049,-0.02278998,0.06093988,-0.03539856,0.03132525,-0.012949,0.03412912,-0.0010927,-0.00170367,-0.07760021,0.03290679,0.01814386,-0.05199474,0.04195728,-0.01525099,-0.0395754,-0.01931548,-0.02613876,0.03605073,-0.04368613,-0.0044036,-0.05160319,0.00834356,0.044699,0.0260205,-0.1051419,0.07255465,-0.00704821,-0.00370671,-0.03630189,-0.09148107,0.01481909,0.00563501,-0.07760581]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (239, '2025-11-01', 'Pregrado', 'El presente proyecto, titulado “Sistema Integral de Administración de Casos (SIAC) para la Prefectura del Municipio Escuque”, se desarrolló como parte del Proyecto Socio-Tecnológico IV del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo (UPTT) Núcleo La Beatriz, durante el año 2025. Fue elaborado por los estudiantes Christian Márquez, Franyer Montilla, Ismaireth Rivera, José Borrero y Carlos Vallejo. El propósito principal del proyecto fue implementar un sistema integral que optimizara la gestión de los casos procesados en la Prefectura del Municipio Escuque, sustituyendo los métodos manuales y físicos utilizados anteriormente. El problema identificado radicaba en la falta de una herramienta tecnológica que permitiera registrar, consultar y administrar los casos de manera eficiente, lo que generaba retrasos, duplicidad de información y dificultades en el resguardo de los expedientes. El sistema fue desarrollado utilizando HTML, CSS, PHP, JavaScript y MySQL como tecnologías base, garantizando una plataforma funcional, segura y adaptable a las necesidades de la institución. El SIAC está compuesto por diversos módulos, entre los que destacan: Gestión de Casos, Denuncias, Cauciones, Contactos, Planillas, Registro Fotográfico, Estadísticas, Búsqueda Avanzada y Administración de Usuarios. Cada uno de estos módulos cumple una función específica orientada a mejorar la eficiencia administrativa y el control de información. Entre los beneficios obtenidos se destacan la agilización en el manejo de casos, la digitalización de los registros, y una mejor protección y respaldo de los datos institucionales. Además, el sistema promueve la modernización tecnológica de la Prefectura y contribuye al fortalecimiento del vínculo comunidad–universidad, al ofrecer una solución informática útil y sostenible que responde a una necesidad real de la comunidad.', 1, 'PREFECTURA DEL MUNICIPIO ESCUQUE', '', '2026-10-19 22:59:48.848881', NULL, NULL, 'Implementar un sistema integral que optimizara la gestión de los casos procesados en la Prefectura del Municipio Escuque, sustituyendo los métodos manuales y físicos utilizados anteriormente.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (236, '2026-10-19', 'Pregrado', 'El presente proyecto tuvo como finalidad optimizar el Sistema de Información Automatizado para la Gestión Académica utilizado por el Centro Bolivariano de Informática y Telemática “Ing. Eli Rojas”, mediante la incorporación de nuevas funcionalidades y mejoras orientadas a fortalecer su funcionamiento. A partir del análisis realizado en la institución, se identificaron oportunidades de mejora relacionadas con la gestión de usuarios, el control y organización de la información. Como respuesta a estas necesidades, se desarrollaron mejoras visuales y funcionales, incorporando un sistema multiusuario, control de permisos según el tipo de usuario, nuevos módulos para la gestión de recursos del área, certificaciones, evaluaciones y estadísticas de asistencia. Para el desarrollo de la propuesta se empleó la metodología Investigación Acción Participativa (IAP) junto con la metodología ágil Extreme Programming (XP), permitiendo la participación de la comunidad durante las diferentes fases del proyecto. Los resultados obtenidos contribuyen a optimizar la gestión de la información y apoyar los procesos desarrollados dentro de la institución. De esta manera, se logró cumplir con los objetivos planteados, obteniendo una solución tecnológica más organizada, funcional y adaptada a las necesidades actuales.', 1, 'Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera', 'Sistema de información, gestión académica, optimización, sistema multiusuario, automatización, XP', '2026-10-19 22:56:48.560733', NULL, NULL, 'Optimizar sistema de información automatizado para la gestión académica del Centro Bolivariano de Informática y Telemática Ing. Eli Rojas de la Escuela de Emprendimiento Valera.', true, NULL, 4, 1);
INSERT INTO public.detalles_proyectos VALUES (240, '2025-11-29', 'Pregrado', 'El presente proyecto busca resolver el problema detectado en la comunidad del Instituto Universitario de Educación Especializada (IUNE) que consistió en la ausencia de un sistema integral para el control de pago de aranceles en el Instituto Universitario de Educación Especializada (IUNE) en Valera, estado Trujillo, cuya carencia genera demoras en los procesos de las oficinas centrales. La solución propuesta por el equipo investigador fue la implantación de un Sistema Integral de Control de Pagos de Aranceles, empleando la metodología de desarrollo XP (Programación Extrema) para asegurar la planificación, codificación, validación y mantenimiento del software. Este trabajo se enmarca en la línea de desarrollo de sistemas del PNF en Informática, específicamente en las líneas de investigación de Sistema de Información y Modelado de Datos. Con la implementación de este sistema, se busca optimizar el control de pago de aranceles y generar un impacto positivo y sostenible en la eficiencia operativa de la institución.', 1, 'IUNE Valera', 'Sistema Integral, Control de Pagos, Aranceles, Metodología de Desarrollo XP, IUNE, Sistema de Información, Modelado de Datos, Eficiencia Operativa', '2026-10-19 23:01:57.118571', NULL, NULL, 'Implementar un Sistema Integral de Control de Pago de Aranceles del Instituto Universitario de Educación Especializada (Valera).', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (241, '2026-06-01', 'Pregrado', 'El presente Proyecto Socio-Tecnológico tiene como propósito diseñar e implementar un sistema web para digitalizar los servicios técnicos y registros de equipos en la microempresa TecnoService de Miguel Morales F.P. ubicada en Valera, Estado Trujillo la problemática central diagnosticada fue la falta de digitalización en los flujos de entrada y salida, lo cual generaba desorden en los equipos que ingresaban, el estudio se fundamenta en la Investigación-Acción Participativa (IAP) Esta metodología promueve la colaboración activa entre los estudiantes y la comunidad abordada, facilitando un proceso en el que todos los involucrados pueden contribuir a la identificación de problemas y a la búsqueda de soluciones, para el desarrollo tecnológico, se empleó la metodología ágil Scrum, permitiendo el desarrollo progresivo y estructurado, asegurando que el resultado final sea una herramienta útil para la comunidad', 1, 'TECNOSERVICE DE MIGUEL MORALES F.P', 'Sistema Web, Gestión Digital, SCRUM, Base de Datos', '2026-10-19 23:01:57.229604', NULL, NULL, 'Diseñar e implementar un sistema web para digitalizar los servicios técnicos y registros de equipos en la microempresa TecnoService de Miguel Morales F.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (242, '2026-07-23', 'Pregrado', 'El presente informe aborda el análisis, diseño y desarrollo de un sistema de información Web administrativo–académico para la Escuela Técnica “Pedro García Leal”. El proyecto surge ante la necesidad institucional de modernizar y centralizar los procesos relacionados con el registro de estudiantes, gestión de notas, elaboración de listados y control interno de datos académicos. A través del desarrollo de una aplicación web administrativo personalizado, se busca optimizar las actividades administrativas, reducir errores manuales y garantizar un acceso rápido, organizado y seguro a la información. La propuesta tecnológica se fundamenta en principios de ingeniería de software y en metodologías de desarrollo orientadas a la mejora continua. Este sistema permitirá a la institución fortalecer su capacidad operativa y avanzar hacia una gestión más eficiente, digital y alineada con las exigencias actuales del entorno educativo. El proyecto contribuye directamente al fortalecimiento tecnológico de la escuela, promoviendo una cultura institucional basada en la innovación, la digitalización y el uso responsable de herramientas informáticas como MySQL en phpMyAdmin, usando lenguajes tales como PHP, HTML y CSS mediante la metodología agil: XP', 1, 'ESCUELA TÉCNICA “PEDRO GARCÍA LEAL', '', '2026-10-19 23:01:57.289094', NULL, NULL, 'Desarrollar un sistema de información administrativo-académico para la Escuela Técnica “Pedro García Leal”, que permita digitalizar, organizar y agilizar los procesos de gestión de estudiantes y notas, a partir del análisis de la información existente y los requerimientos institucionales, optimizando el trabajo administrativo mediante una solución tecnológica eficiente y adaptada a las necesidades reales de la institución.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (243, '2025-11-01', 'Pregrado', 'El proyecto abordó la falta de un sistema de inventario para el control de Bienes Nacionales en la Oficina Administrativa Valera del IVSS, un problema generado por la dependencia del proceso manual, la limitación de personal y la escasez de recursos tecnológicos. Esta situación causaba dificultad en el seguimiento de los bienes, frustración en el personal y retraso en el envío de información a la sede principal. Para resolver esto, se implementó el Sistema de Gestión de Bienes Nacionales (SIGEBI-OAV), utilizando la metodología Proceso Unificado de Rational (RUP). La ejecución fue rigurosa, iniciando con la determinación precisa de requerimientos mediante Casos de Uso, y diseñando una arquitectura robusta centrada en la integridad de los datos y la aplicación estricta de la lógica de negocio. Seguidamente, la fase de construcción del software incluyó funcionalidades críticas como la reasignación automática de bienes y un estricto Control de Acceso por Roles para garantizar la seguridad. La calidad del producto fue validada con pruebas exhaustivas de Caja Negra y Caja Blanca. Finalmente, el proyecto culminó con la entrega exitosa del SIGEBI-OAV, lo que permitió automatizar y optimizar el registro y consulta de activos. Las conclusiones señalan que la nueva herramienta no solo moderniza la gestión, sino que también fortalece la transparencia y la rendición de cuentas en el manejo de los recursos públicos, tras haber asegurado su correcta adopción mediante la capacitación del personal directivo y administrativo', 1, 'OFICINA ADMINISTRATIVA VALERA (IVSS)', 'sistema, inventario, IVSS', '2026-10-19 23:01:57.359099', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS).', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (244, '2024-11-01', 'Pregrado', 'Implementación de una Aplicación Web para la Cooperativa “La Once con La Once” Parroquia Juan Ignacio Montilla, Municipio Valera, Estado Trujillo. El presente proyecto tiene como finalidad la implementación de una aplicación web para la Cooperativa “La Once con La Once”, ubicados en la parroquia Juan Ignacio Montilla, municipio Valera, estado Trujillo. Como tal, el objetivo de dicho proyecto es buscar solución a la problemática que presenta la Cooperativa “La Once con La Once” con respecto a la falta de una plataforma o medio digital para darse a conocer. Se realizara en base a la experiencia y conocimiento que poseen los autores que realizan este proyecto, y poniendo en práctica todo lo aprendido hasta ahora de lo que han visto en la carrera de informática, siempre cumpliendo los lineamientos establecidos por la ley y por el PNFI adjunto a la U.P.T.T “Mario Briceño Iragorry”-La Beatriz (de la cual los integrantes de este proyecto pertenecen)', 1, 'COOPERATIVA “LA ONCE CON LA ONCE ”', 'Aplicación web – internet - información -metodologías', '2026-10-19 23:01:57.42327', NULL, NULL, 'Desarrollar una aplicación web para la gestión de la', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (245, '2026-07-01', 'Pregrado', 'El objetivo de este proyecto, es llevar a cabo, un sistema de gestión y control de datos educativos mediante una aplicación web en la institución U.E Hugo Rafael Chávez Frías Municipio Valera Estado Trujillo. Parte del diagnóstico situacional, es proporcionar una carta a través de solicitar un permiso para llevar a cabo un proyecto socio tecnológico. A través de esto, se quiere saber, resolver los problemas en relación al área de informática A partir de este punto de vista, el propósito general de este proyecto es la actualización de esta página informativa de la institución Para que el servicio sea más eficiente, dado que los usuarios necesitan la intercomunicación, ya que no tenía cómo actualizar la información de la institución. El estudio se basa en la modalidad socio tecnológica cuantitativa del proyecto, los métodos que se utilizan para desarrollarlos son la observación, la entrevista, anotaciones al preparar un diagnóstico previo de investigación. Para realizar la observación, fue necesario utilizar como instrumento una libreta de campo la cual permitió recolectar datos resultantes del estudio como también, el uso de la cámara fotográfica para dar sustentación a la investigación. Para el desarrollo del proyecto, se utilizan diferentes programas para efectuar la debida actualización técnica. A nivel teórico se fundamenta en diferentes percepciones de autores de gran relevancia. El desarrollo técnico del proyecto se abordará bajo los principios de la Ingeniería de Software, utilizando la metodología ágil Extreme Programming (XP). Esta elección metodológica garantiza la entrega temprana de valor y la alta calidad del software, al basarse en la retroalimentación constante del usuario', 1, 'UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS', 'página web, intercomunicación, información, usuarios', '2026-10-19 23:01:57.494415', NULL, NULL, 'Actualizar a un sistema para la gestión y control de datos educativos en la Unidad Educativa “Hugo Rafael Chávez Frías” “Antonio Nicolás Briceño” Sector Santa Cruz, parroquia San Luis, Municipio Valera del Estado Trujillo., que incorpore múltiples módulos funcionales, tales como registro y control académico, gestión de asistencia, administración de usuarios, con el propósito de optimizar los procesos administrativos, fortalecer la organización de la institución.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (246, '2026-07-01', 'Pregrado', 'Este proyecto socio-tecnológico, titulado "Sistema Local de Gestión
Escolar para la Escuela ''Vicente de la Torre''" y desarrollado en el marco del
PNF en Informática, consiste en el diseño e implementación de un software
local basado en PHP y MySQL para automatizar y centralizar los registros
administrativos y estudiantiles de dicha institución. Su objetivo principal es
optimizar el control de inscripciones, asistencias y calificaciones, sustituyendo
los procesos manuales en físico para mitigar la pérdida de documentos, agilizar
las consultas del personal directivo y facilitar la toma de decisiones mediante
reportes estadísticos automatizados.', 1, 'ESCUELA “VICENTE DE LA TORRE', '', '2026-10-19 23:01:57.547376', NULL, NULL, 'Implementar un sistema de gestión local escolar para la Escuela “Vicente de la Torre” como como respaldo de información y automatización de esta, lo que facilitara el trabajo del secretario(a) al momento de hacer estadísticas e inscripción de los estudiantes.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (247, '2025-11-01', 'Pregrado', 'El presente proyecto, desarrollado para Musart Academia C.A., tuvo como propósito principal la implementación del Sistema de Información Académico/Administrativo (SIACAD); dicho sistema fue diseñado específicamente para modernizar los procesos educativos junto con las operaciones de esta institución especializada en formación artística además de refuerzo académico. Cabe destacar que, dado que la academia atiende a estudiantes de diversas edades mediante metodologías innovadoras en instalaciones de primer nivel, el sistema fue concebido para responder a sus necesidades particulares. En cuanto a su desarrollo, se adoptó la metodología ágil Kanban, la cual permitió organizar el trabajo en etapas claramente definidas durante el período comprendido entre enero y noviembre de 2025. Por otra parte, el marco teórico que sustenta esta iniciativa integra conceptos clave de sistemas de información, gestión académica, gestión administrativa, así como optimización de procesos. Respecto a sus beneficios, la implementación del SIACAD generará importantes ventajas operacionales: automatización de tareas repetitivas, producción sistemática de informes estratégicos, agilización de los flujos comunicacionales junto con acceso inmediato a datos actualizados. En consecuencia, estas mejoras fortalecerán tanto la operatividad diaria como la capacidad de crecimiento institucional de Musart Academia C.A., posicionándola como referente en innovación educativa.', 1, '"Musart Academia, C.A', 'Sistema, Información, Gestión Académica, Gestión Administrativa, Metodología Kanban, Musart Academia', '2026-10-19 23:01:57.607227', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (248, '2025-11-01', 'Pregrado', 'El presente trabajo de investigación tuvo como objetivo general implementar un sistema de información para el registro y gestión laboral del Instituto del Deporte del Estado Trujillo (INDET), utilizando la modalidad de investigación-acción participativa (IAP) y la metodología SCRUM para el desarrollo tecnológico. Entre los objetivos específicos se destacan: determinar los requerimientos funcionales y técnicos para la gestión de datos de los empleados del INDET, diseñar y desarrollar el Sistema de Información para el Registro y Gestión Laboral del INDET (SIRGL-INDET), realizar pruebas de funcionamiento para garantizar la estabilidad y confiabilidad del sistema, instaurar el sistema en el entorno operativo y capacitar al personal del departamento de recursos humanos en su uso eficiente. En la etapa de diagnóstico, se identificaron las necesidades y desafíos institucionales, promoviendo la participación activa de los miembros y fortaleciendo su sentido de pertenencia y compromiso con los procesos organizacionales. Durante la etapa de análisis, se fundamentaron las decisiones estratégicas orientadas a la solución de la problemática detectada. En la fase de diseño y desarrollo, se elaboró el SIRGL-INDET, automatizando los procesos manuales que generaban retrasos en la atención de solicitudes, lo que optimizó la gestión de información y mejoró los tiempos de respuesta y la calidad del servicio ofrecido por la institución. Finalmente, se ejecutó un proceso de capacitación dirigido al personal de recursos humanos, facilitando la apropiación y uso eficiente del sistema. El proyecto logró transformar la gestión laboral del INDET mediante la incorporación de tecnologías de información, promoviendo la participación activa de los miembros y mejorando la eficiencia y transparencia de los procesos institucionales, lo que representa un avance significativo en la modernización y profesionalización de la organización', 1, 'INDET(SIRGL-INDET)', 'sistema, información, registro, gestión laboral, INDET', '2026-10-19 23:01:57.767809', NULL, NULL, 'Implementar un sistema de información para el registro y gestión laboral del INDET(SIRGL-INDET)', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (249, '2025-10-10', 'Pregrado', 'El presente proyecto socio-tecnológico tiene como finalidad desarrollar un sistema de gestión de inventario web para la empresa INME C.A., dedicada a la fabricación de tableros eléctricos, cajas de distribución y centros de medición. Surge como respuesta a la necesidad de optimizar los procesos administrativos y operativos que actualmente se ejecutan de forma manual, generando retrasos, errores en los registros y pérdida de información.

Dicho estudio se fundamenta en la metodología de Investigación-Acción Participativa (IAP), permitiendo una interacción directa entre los investigadores y la organización, con el fin de identificar problemas reales, diseñar una propuesta tecnológica adecuada y aplicar soluciones efectivas orientadas al mejoramiento continuo.

La investigación aborda un diagnóstico situacional, la identificación de necesidades, el análisis de alternativas de solución y el desarrollo de un sistema automatizado para la gestión integral de inventarios, facturación, clientes y órdenes de producción. La implementación del sistema permitirá reducir errores, centralizar la información, optimizar tiempos de respuesta y aumentar la productividad empresarial.

Finalmente, se espera que el proyecto contribuya al fortalecimiento tecnológico de INME C.A., mejore su capacidad competitiva en el mercado nacional y sirva como modelo de referencia para otras organizaciones con necesidades similares de transformación digital', 1, 'INME C.A', '', '2026-10-19 23:04:04.194917', NULL, NULL, 'Desarrollar un sistema de gestión de inventario web para la empresa INME C.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (250, '2025-11-01', 'Pregrado', 'Este proyecto de investigación desarrolla e implementa un Sistema de Información Zoocéntrico para el Centro Veterinario Integral "Dr. Diego Hidalgo". Bajo el paradigma de la Investigación Acción Participativa (IAP), se identificó como problemática central la ausencia de un sistema unificado para gestionar historias médicas veterinarias y procesos de exportación, generando redundancia de datos, retrasos operativos y riesgos de incumplimiento normativo. Mediante un diagnóstico participativo y herramientas de análisis estratégico (matriz FODA, matriz de priorización), se determinó que la necesidad más crítica era una solución tecnológica integrada. La evaluación de alternativas mediante una matriz de decisión seleccionó el desarrollo del Sistema de Información Zoocéntrico como la opción óptima, por su equilibrio superior en costo, facilidad de uso, seguridad, eficiencia y cumplimiento legal. El objetivo general es implementar dicho sistema en el centro veterinario. Para ello, se estructuraron objetivos específicos que abarcan el análisis de requerimientos, el diseño de una arquitectura modular, el desarrollo basado en la metodología de Programación Extrema (XP), la implementación en la infraestructura existente y la capacitación del personal. El sistema integrará módulos para la gestión digitalizada de historias médicas y la automatización de los procesos de exportación, centralizando la información, eliminando redundancias y garantizando la trazabilidad integral del paciente, introduciendo mejoras sustanciales en seguridad informática, eficiencia operativa y cumplimiento normativo', 1, 'CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO"', 'Sistema de Información Zoocéntrico, Centro Veterinario Integral, Programación Extrema (XP), historias médicas veterinarias, exportación de mascotas, Node', '2026-10-19 23:04:44.164147', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (251, '2026-06-01', 'Pregrado', 'El presente Proyecto Socio-Tecnológico consistió en el diseño e implantación de un portal web integral para la automatización operativa de la agencia publicitaria PubliArt. El estudio se fundamentó en la Metodología de Investigación-Acción Participativa, diagnosticando debilidades críticas en el manejo manual de registros de datos, el control de la agenda interna y la proyección de la identidad corporativa. Como solución de ingeniería de software, los investigadores desarrollaron una plataforma dinámica estructurada en diez módulos independientes empleando el lenguaje de programación PHP y un sistema de gestión de bases de datos relacionales en MySQL. El sistema incorpora principios de diseño web responsivo, garantizando una óptima usabilidad (UI/UX) tanto en computadores como en dispositivos móviles. La implementación técnica sustituyó los flujos informales por procesos digitalizados y centralizados, optimizando la administración del portafolio digital y agilizando el agendamiento de actividades de la organización. Las jornadas de capacitación dictadas al personal aseguraron la correcta adopción tecnológica y el mantenimiento de la herramienta. Los resultados demostraron que la articulación entre la teoría académica y el desarrollo de software libre consolida soluciones eficientes que elevan la productividad, seguridad y el crecimiento institucional en el entorno digital.', 1, 'EMPRESA PUBLIART', 'Proyecto Socio-Tecnológico, Portal web, Automatización, Implementación, Scrum', '2026-10-19 23:05:11.101813', NULL, NULL, 'Implementar un Portal web que permita mostrar los servicios de la empresa junto a una base de datos que permita registrar posibles clientes en los diferentes servicios para la empresa Publiart.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (224, '2026-07-01', 'Pregrado', 'El presente proyecto sociotecnológico surge como una continuación y evolución de la plataforma educativa previamente desarrollada e implementada en el Liceo “Dr. Régulo Burelli Rivas”. Esta herramienta fue diseñada originalmente con la finalidad de fortalecer las competencias básicas en comprensión lectora y redacción de los estudiantes de primer año.Durante el proceso de aplicación de la plataforma educativa, se evidenciaron resultados positivos en el rendimiento académico de los estudiantes. Sin embargo, también se identificaron limitaciones técnicas que dificultaron su óptimo aprovechamiento, especialmente en lo referente al proceso de despliegue y configuración en los equipos, así como a la ausencia de funciones complementarias planificadas en su fase inicial. Con base en estas observaciones, se plantea la necesidad de desarrollar una nueva versión de la plataforma, optimizada en cuanto a su accesibilidad web, estabilidad y enriquecida con herramientas interactivas que amplíen su potencial educativo dentro del Liceo.', 1, 'LICEO "DR. REGULO BURELLI RIVAS"', '', '2026-10-19 22:30:39.179226', NULL, NULL, 'Actualizar la plataforma educativa existente mediante la simplificación de su instalación, la incorporación de nuevas funcionalidades interactivas y la capacitación docente, con el fin de fortalecer las competencias de lectura, escritura y razonamiento lógico-matemático de los estudiantes del Liceo “Dr. Régulo Burelli Rivas”.', true, '[-0.02443639,-0.00069769,-0.03806122,-0.00718013,0.00535261,0.04528152,-0.02403804,0.06455743,-0.12911555,-0.03920002,0.01643119,0.07089168,-0.00877692,-0.01650998,-0.061236,0.00876167,-0.02839487,0.08598824,-0.05256608,0.04808213,0.05672983,-0.00501489,0.04106459,0.14905806,0.02356271,0.04271513,0.09288794,-0.01095791,0.00998189,-0.03977056,0.02781444,0.06969877,-0.01225856,-0.08672287,0.02932397,-0.01833244,0.00945082,0.036206,-0.00587822,-0.02149121,0.0487424,-0.11448383,-0.08900551,0.01548144,-0.05410888,-0.09191135,-0.00461125,0.0598379,0.03144126,0.027541,0.02306708,-0.08192679,0.0515528,-0.02386192,-0.0598037,0.02534051,-0.04127295,0.0232895,0.01210576,0.0216678,-0.06307766,0.06150641,0.01133665,0.08311213,-0.06633203,0.01738974,-0.04373659,-0.04201577,0.05306384,-0.03061455,0.06429537,-0.02686859,0.07075165,-0.02567759,0.03593793,0.00537891,-0.02019079,-0.01769746,-0.04161357,-0.06043808,0.05150263,-0.03574929,0.07856886,0.04820451,-0.01150709,-0.05651193,0.03408683,-0.02994987,0.04743606,-0.00278402,0.06834235,0.04842179,0.01115182,-0.04052941,-0.01397699,0.03603465,-0.04796822,-0.07183826,0.03912338,0.02297387,-0.07764123,-0.00125446,-0.03099155,-0.00524421,-0.03382214,-0.03286302,-0.03414164,-0.00471734,-0.04395782,-0.01568465,0.02031015,-0.01367783,0.01911086,0.04931642,-0.06914829,-0.10113762,0.0250976,-0.06648712,-0.06305107,-0.00117264,-0.01726097,0.0589613,-0.04352908,-0.10410595,0.03157526,-0.01991675,-0.00405759,3.9e-06,-0.06754954,-0.03268037,-0.06685875,-0.03781994,0.0217957,0.02330987,-0.01004469,0.01537759,-0.00074744,-0.10318734,0.08836042,0.05331387,-0.03987723,0.06140702,-0.01051612,0.0146896,0.03576214,0.03752158,0.01533084,0.01667716,-0.11251099,-0.04362966,0.01229575,-0.022107,-0.09883893,0.1070936,0.07718315,0.01736655,0.09713324,0.02057317,0.05015525,-0.07920369,-0.02838671,-0.01513998,-0.0540786,0.04399218,-0.01193382,-0.06106551,-0.07112484,0.01266136,0.01284336,-0.00082118,0.00331292,-0.00361509,0.0086873,-0.02680964,-0.04601266,0.07178854,-0.01679427,0.00101214,-0.02895232,-0.00792263,-0.09237465,-0.04729067,0.03164367,0.00073879,-0.08142273,0.0638845,-0.02427387,-0.07455855,0.07122864,0.12167482,0.00629001,0.02867086,0.06792949,-0.06004595,-0.04282948,-0.03705423,0.02466091,-0.08124362,0.01454851,-0.01463181,-0.04431236,0.08114225,0.0103932,-0.01833305,0.02717725,-0.00673637,0.03632704,-0.02623986,-0.01462156,-0.02825857,0.06881038,0.03165293,0.01244246,0.01353089,-0.04723359,0.01980081,0.04105756,0.00340926,0.03392946,-0.09140119,-0.06362307,0.02961361,-0.01819025,1.179e-05,0.01854314,0.02023122,0.01391786,0.0114547,0.05660967,-0.00141934,-0.08294394,-0.03997573,0.05308574,-0.0206696,-0.02201765,0.00767105,0.01823151,-0.02587468,0.05056467,0.01762643,0.06418609,-0.01307897,-0.0716907,-0.03480857,-0.04762545,0.02233266,0.01277508,0.01919457,-0.01795472,-0.094496,0.02282661,0.09870023,-0.08506001,0.0862486,0.07263689,-0.01711684,-0.02253789,0.02782803,-0.03955329,0.05389141,-0.00179801,0.02042192,-0.01996299,0.04356619,-0.03535379,-0.11835328,-0.01085681,-0.14666712,-0.01361608,-0.03810461,-0.0336101,-0.12078571,0.07288981,0.01950399,0.05048059,0.05695589,0.04700834,-0.02450358,0.02449123,-0.02637062,0.04399884,-0.07129958,0.00427223,-0.0632506,0.05163573,0.08891375,-0.03026086,0.07368381,-0.02800931,-0.03151959,-0.07672038,-0.04382461,-0.04037614,0.01388334,0.06574389,0.00893571,0.01429178,0.02837634,-0.09254619,0.03707048,-0.00551424,-0.01243916,0.02931568,-0.02113791,0.03161109,-0.1013858,-0.00274763,0.06561643,-0.04357147,-0.07462724,0.00337929,-0.03883679,0.05271952,-0.00417806,0.11143039,-0.01402213,-0.06845417,-0.01879103,0.11991594,1.31e-06,0.06508487,-0.05361229,-0.08090326,0.0265104,0.08016308,-0.07550141,-0.06022866,-0.04502506,-0.07513067,0.02873778,-0.05365631,-0.04558763,0.03580938,-0.01929547,0.04597196,0.04501142,0.16696668,0.06618615,-0.05257251,0.00949989,-0.01814677,-0.00979817,0.00908008,-0.05337028,-0.02538115,-0.00291841,0.03399691,-0.08673541,0.0241896,0.0379657,0.01615715,0.08706683,-0.00681151,-0.04775185,0.02860986,0.0513305,0.01018436,0.03558476,-0.00707918,0.08773236,0.00639912,-0.04185054,0.01747683,-0.02212804,-0.0330578,-0.04761962,-0.00654832,0.05461166,-0.00738419,0.01713119,0.02782196,-0.02164092,0.05389902,-0.12326062,-0.00531947,0.01131819,0.01032998,-0.03198591,-0.11316501,-0.03213718,-0.0144743,0.0942577,0.12249186,-0.00849635]', 3, 0);
INSERT INTO public.detalles_proyectos VALUES (252, '2026-07-01', 'Pregrado', 'El presente proyecto sociotecnológico tuvo como objetivo implantar un Sistema de Gestión Digital para el Inventario, Trazabilidad y Movimiento de Bienes en la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. (ETPROINVI S.A.), ubicada en Valera, estado Trujillo. El diagnóstico participativo identificó la dispersión de registros físicos y hojas de cálculo, así como dificultades para consultar el estado y los movimientos de los bienes institucionales. Se empleó la investigación acción participativa para recopilar y validar las necesidades de la Unidad de Bienes, y Scrum para organizar el levantamiento de requerimientos, el diseño, la construcción y la revisión progresiva del producto. La solución web se desarrolló con C#, ASP.NET MVC, MySQL, HTML, CSS y JavaScript. Comprende acceso por roles, registro y consulta de bienes, solicitudes de traspaso y desincorporación, historial de movimientos, etiquetas con códigos QR, notificaciones y reportes. El sistema se presentó a los usuarios, se sometió a verificaciones funcionales y de uso, y se documentaron actividades de instalación, entrega del manual digital y capacitación. El producto centraliza la información y permite consultar la trazabilidad de los bienes, con una base para fortalecer el control institucional. La evidencia del informe describe resultados cualitativos y no establece una medición comparativa de tiempos, errores o costos.', 1, 'EMPRESA TRUJILLANA DE PROYECTOS', 'gestión de bienes, inventario digital, trazabilidad, sistema web, Scrum', '2026-10-19 23:06:50.622395', NULL, NULL, 'Implantar un sistema de gestión digital para el inventario, trazabilidad y movimiento de bienes en la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. (ETPROINVI S.A.).', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (253, '2026-07-23', 'Pregrado', 'El presente informe aborda el análisis, diseño y desarrollo de un sistema de información Web administrativo–académico para la Escuela Técnica “Pedro García Leal”. El proyecto surge ante la necesidad institucional de modernizar y centralizar los procesos relacionados con el registro de estudiantes, gestión de notas, elaboración de listados y control interno de datos académicos. A través del desarrollo de una aplicación web administrativo personalizado, se busca optimizar las actividades administrativas, reducir errores manuales y garantizar un acceso rápido, organizado y seguro a la información. La propuesta tecnológica se fundamenta en principios de ingeniería de software y en metodologías de desarrollo orientadas a la mejora continua. Este sistema permitirá a la institución fortalecer su capacidad operativa y avanzar hacia una gestión más eficiente, digital y alineada con las exigencias actuales del entorno educativo. El proyecto contribuye directamente al fortalecimiento tecnológico de la escuela, promoviendo una cultura institucional basada en la innovación, la digitalización y el uso responsable de herramientas informáticas como MySQL en phpMyAdmin, usando lenguajes tales como PHP, HTML y CSS mediante la metodología agil: XP', 1, 'ESCUELA TÉCNICA “PEDRO GARCÍA LEAL', '', '2026-10-19 23:06:57.092771', NULL, NULL, 'Desarrollar un sistema de información administrativo-académico para la Escuela Técnica “Pedro García Leal”, que permita digitalizar, organizar y agilizar los procesos de gestión de estudiantes y notas, a partir del análisis de la información existente y los requerimientos institucionales, optimizando el trabajo administrativo mediante una solución tecnológica eficiente y adaptada a las necesidades reales de la institución.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (254, '2025-11-26', 'Pregrado', 'Este proyecto propone el desarrollo e implementación de un sistema web para la gestión del talento humano del SAPNNAET, con el fin de modernizar y optimizar los procesos administrativos de la institución. A partir de un diagnóstico situacional, se identificaron problemáticas como el uso de sistemas obsoletos, la falta de centralización de la información y la limitada capacitación del personal. La solución planteada consiste en una herramienta web que mejora la eficiencia, organización y seguridad en la gestión de datos del personal. Este proyecto se enmarca en la línea de desarrollo de sistemas del PNF en Informática con las líneas de investigación: Sistema de Información y Modelado de Datos. Con esto busca generar un impacto positivo en la operatividad institucional y en la atención a la comunidad', 1, 'SAPNNAET', 'Sistema Web, Gestión Integral, Talento humano, SAPNNAET', '2026-10-19 23:06:57.170925', NULL, NULL, 'Desarrollar e implementar el Sistema Web de gestión integral del Talento humano para el SAPNNAET.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (255, '2025-12-02', 'Pregrado', 'El proyecto titulado Sistema de Información para la Gestión Administrativa del Personal Adscrito a la VALFOR S.A. se desarrolló en la empresa VALFOR S.A., concesionario autorizado de la marca Ford ubicado en Valera, Estado Trujillo, Venezuela, con el objetivo general de implementar un sistema de información que optimice los procesos administrativos relacionados con la gestión del personal, mejorando la eficiencia, precisión y control en el cálculo de nómina y manejo de datos; entre sus objetivos específicos se incluyó el diseño, desarrollo e implementación del sistema, así como la capacitación del personal administrativo para su uso adecuado; la necesidad detectada se centró en la ausencia de un software propio para la gestión de nómina, lo que obligaba a la empresa a realizar procesos manuales en hojas de cálculo, generando errores y reduciendo la productividad, por lo que la solución tecnológica propuesta resultó pertinente al automatizar procesos clave y centralizar la información administrativa; la metodología aplicada fue de tipo Investigación Acción Participativa (IAP) con enfoque en la metodología ágil Scrum, favoreciendo la colaboración continua entre los desarrolladores y la comunidad empresarial; los beneficios obtenidos incluyen la reducción de errores, el ahorro de tiempo operativo y la mejora en la toma de decisiones mediante información precisa y actualizada; en conclusión, el proyecto fortaleció la gestión administrativa de VALFOR S.A., promoviendo la modernización tecnológica de sus procesos y reafirmando la importancia de las herramientas informáticas para la eficiencia organizacional.', 1, 'VALFOR S.A', '', '2026-10-19 23:08:32.454978', NULL, NULL, 'Implementar un sistema de información que optimice los procesos administrativos relacionados con la gestión del personal, mejorando la eficiencia, precisión y control en el cálculo de nómina y manejo de datos.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (270, '2026-04-01', 'Pregrado', 'En el presente proyecto Socio tecnológico se tiene como propósito la ejecución de un soporte técnico abarcando un mantenimiento preventivo y correctivo, así como la capacitación a los usuarios dentro del departamento de Automatización de las Tecnologías de Información y Telecomunicaciones (ATIT) en CORPOELEC, este se aplicó a cinco equipos de computación los cuales presentaban fallas. Todo esto se llevó una metodología la cual fue Investigación-Acción Participativa (IAP) y apoyada en un enfoque mixto, con componentes de Investigación Tecnológica-Aplicada y Descriptiva, utilizando herramientas que nos permitirían llevar a cabo todo el trabajo, como la matriz ROLA y el Árbol de problemas, con los cuales pudimos hacer identificación de problemas como acumulación de polvo y falta de optimación de los sistemas. En este trabajo observaremos como llevamos a cabo todo el soporte tanto a equipos como a usuarios y que obtuvimos como resultado y como llevamos una charla/participativa sobre cómo tratar los equipos para tener una vida útil más prolongada al hardware. Esto permitiéndonos fortalecer todos los procesos informáticos y administrativos de la empresa', 1, 'Corporación Eléctrica Nacional (CORPOELEC) de Venezuela', '', '2026-10-19 23:24:59.123033', NULL, NULL, NULL, true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (256, '2025-07-01', 'Pregrado', 'El presente proyecto socio-tecnológico expone la implementación de una propuesta estratégica para la creación de una aplicación web con el fin de mejorar tanto la experiencia de los usuarios como la gestión de la institución, el cual tendrá como ambiente la Unidad Educativa Hermana. María Sorrosal, ubicada en la comunidad José Humberto Contreras (Morón) Municipio Valera, Estado Trujillo. Por otro lado, el mismo se va a constituir de un marco metodológico el cual nos proporcionó un estudio y recopilación de los puntos más importantes para el desarrollo de la presente investigación, ya que de esta manera se pudo orientar toda la información que será relevante, para construir criterios y fundamentar las razones que llevaron a la realización de dicho proyecto socio-tecnológico, de este modo se utilizó la herramienta de la entrevista para el diagnóstico situacional, en donde la misma nos permitió conocer los problemas y las variables en estudio a analizar, para que de esta forma se logré determinar el punto de inflexión en el cual se ejecutaron los objetivos de una forma eficiente y a su vez tener un punto en específico de inicio, en donde el mismo sería clave para la distribución y aprovechamiento de los recursos propios de la institución, por consiguiente se emplearon tres elementos, dentro de los cuales se destacan, el diagrama de causa-efecto y el árbol de problemas, de tal manera dichos elementos nos ayudaron a visualizar aquellas causas y consecuencias del problema principal, que era la falta de una aplicación web en el laboratorio de computación , aunado a esto se haría uso del árbol de objetivos, que expondría las alternativas de solución a aquellas problemáticas, generando de este modo soluciones concretas para garantizar una ejecución exitosa, todo ello se logró gracias a la participación del personal administrativo del colegio, generando así beneficios en los involucrados tanto de forma directa como indirecta. MEMORIA DESCRIPTIVA Identificación y objetivo del proyecto: El presente proyecto se redacta de acuerdo a la finalidad de la asignatura “Proyecto Socio Tecnológico (PST)” del PNFI en informática impartida en la Universidad Politécnica Territorial Mario Briceño Iragorry (UPTT). La guía y supervisión de mismo le corresponden a la docente asesor del proyecto Marzzia Gil, y dicho proyecto tiene como objetivo el realizar la implementación de una aplicación web, en la Unidad Educativa Hermana María Sorrosal. Antecedentes del proyecto: El presente proyecto ha de servir como un documento por medio del cual se han buscado soluciones prácticas y adecuadas reflejando de este modo su objetivo.', 1, 'Unidad Educativa Hna. María Sorrosal', '', '2026-10-19 23:10:14.893313', NULL, NULL, 'Implementar una aplicación web para la Unidad Educativa Hna. María Sorrosal.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (257, '2025-04-01', 'Pregrado', 'El siguiente proyecto presenta una propuesta para implantar un Sistema Web para la gestión de proyectos de los Consejos Comunales en el Consejo Local de Planificación Pública (CLPP), Del Municipio Valera, parroquia Mercedes Díaz, Estado Trujillo. Para el logro de las metas se desarrollaron los siguientes objetivos específicos: Determinar los requerimientos del sistema web, Diseñar la arquitectura del sistema, incluyendo base de datos y módulos, Establecer un plan de mantenimiento y actualización del sistema, Implementar procedimientos de respaldo y seguridad de datos, Capacitar al personal técnico para el soporte continuo del sistema, Elaborar el manual de usuario del sistema web, Capacitar al personal administrativo del CLPP en el uso del sistema. Así mismo, para el diseño y modelado de la página se utilizó los lenguajes de programación CSS PHP, MYSQL, y HTML, en la estructura de las bases de datos se usó PhpMyAdmin. Elaboración del plan de acción para la puesta en marcha del sistema web, se procedió a subirlo en internet. Desde el punto de vista metodológico este estudio es de tipo positivista cualitativo y se corresponde con diseño de trabajo factible, con características documental y de campo. La importancia de este proyecto está representada en los beneficios otorgados a los ciudadanos y ciudadanas del municipio Valera, así como también a los consejos comunales, que amerite información sobre los proyectos, planes o acontecimientos del (CLPP), para vincularse al gobierno electrónico que sirve de medio para la toma de decisiones en la planificación municipal.', 1, 'CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA', 'Sistema web, gestión, consejos comunales', '2026-10-19 23:12:05.072795', NULL, NULL, 'Implementar un sistema web para la gestión de proyectos de los consejos comunales en el Consejo Local Planificación Pública.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (258, '2025-11-19', 'Pregrado', 'El proyecto socio tecnológico que se desarrolló, junto con los autores mencionados, consiste en la creación de un Sistema de información para el registro académico y administrativo. La recolección de información incluyó tanto datos provenientes de la coordinación del colegio y de los responsables de la institución, como también investigaciones propias de los autores. Además, se obtuvieron antecedentes relevantes (Cabrera J, Rojo D (2019), Briceño, L (2022), Torres, D (2022)), mediante la consulta de la biblioteca de la universidad y gracias a la orientación proporcionada por el guía del proyecto. Este sistema permite agilizar y organizar de manera eficiente los procesos de registro y matriculación de personal y estudiantes, para la realización del proyecto, hemos utilizado la metodología XP (Programación Extrema), la cual se caracteriza por su enfoque ágil y flexible. La implementación de esta metodología nos ha permitido adaptarnos rápidamente a los cambios y requerimientos del cliente, garantizando así la satisfacción de sus necesidades. En cuanto a la tecnología utilizada, hemos creado una base de datos empleando MySQL, una herramienta de gestión de bases de datos relacionales muy robusta y popular. Además, hemos desarrollado una interfaz de usuario a través de PHP, HTML y CSS, permitiendo una conexión fluida y eficaz con la base de datos. El resultado ha sido un sistema efectivo y adaptable, que cumple con las necesidades de la institución y proporciona beneficios tanto para los desarrolladores como para sus usuarios.', 1, 'C.E.I Enriqueta Sánchez Vivas', '', '2026-10-19 23:14:05.279033', NULL, NULL, 'Implementar un sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (259, '2026-10-19', 'Pregrado', 'En resumen, este PSTI tiene como objetivo de implementar un Sistema de Información de Gestión Administrativo para la “Asociación de Taekwondo del Estado Trujillo” (ASOTKDET), y dicho Sistema de Información, que lleva por nombre de “ASOTKNET”, se trata de un Sistema administrativo que permite facilitar los procesos e información necesaria dentro de la asociación, facilitando así el trabajo de los miembros de la comunidad, especialmente al Presidente y Vicepresidente de la Asociación, también podemos contar aquellos miembros especialistas, es decir, con conocimientos previos en Informática, y estos miembros serían los Árbitros principales de la Asociación. Dicho Sistema, está únicamente creado para la Asociación de Taekwondo del Estado Trujillo” (ASOTKDET), comunidad ubicada en la Avenida las Ferias de la Parroquia San Luis, Municipio Valera del Estado Trujillo, y se trata de una Asociación Civil sin Fines de Lucro organizada para otorgar las herramientas holísticas sobre el Taekwondo a través de sus distinguidos modos actuales de enseñanza, práctica y entrenamiento de tan aclamado Arte Marcial desde el año 1987. Volviendo con el Sistema de Información, este sistema fue realizado por medio de lenguajes de programación, los cuales sería el lenguaje PHP (Hypertext Preprocessor) en el cual fue utilizado para programar el lado del servidor y de todas las funciones necesarias del sistema, también tenemos el lenguaje de Javascript utilizado para diseñar y para escribir las partes Front End (de la parte del cliente) y Back End (de la parte del servidor) del Sistema por medio de consultas AJAX, también no olvidemos de la estructura y el estilo visual que llevara el sistema, siendo los lenguajes HTML5 (Estructura) y CSS3 (Estilo visual). La metodología utilizada para el desarrollo e implementación del sistema, se trata de la metodología XP o Extreme Programming, y se trata de un método que permite la gestión como tal del equipo del PSTI, pautando fechas de entregas o visitas constantes a la comunidad, pero evitando descontentos de parte de esta, además de cambios repentinos en las funciones o requisitos del sistema. Como tal el desarrollo del sistema fue largo y tortuoso, pero al fin y al cabo se logró realizar un sistema exitoso que se adaptara a las peticiones de la comunidad.', 1, 'Asociación de Taekwondo del Estado Trujillo (ASOTKDET)', 'Sistema de Información, Lenguajes de Programación y Gestión Administrativa', '2026-10-19 23:20:21.153746', NULL, NULL, 'Desarrollar e implementar el Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (299, '2026-07-01', 'Pregrado', 'Desarrollar e implementar el SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO.', 1, 'ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO', 'Sistema de Gestión Web, Investigación-Acción', '2026-10-20 00:09:16.495903', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (260, '2026-10-19', 'Pregrado', 'En primer lugar, se presenta el resumen para el proyecto socio- tecnológico titulado “Sistema Web para la Farmacia INSUMEDIC”, realizado por los autores Araujo María, Carrasquero Hender, Briceño Jean Luis, Montilla Angeli y Rivero Luis. Específicamente, este proyecto socio-tecnológico se enfoca en la modernización digital de la farmacia “INSUMEDIC C.A”, una empresa con múltiples sedes operativas en el estado Trujillo. Sin embargo, en el contexto actual, la farmacia carecía de una presencia en línea y un catálogo digital, lo cual limitaba su visibilidad, su capacidad para interactuar eficientemente con los clientes y dificultaba el acceso oportuno a información crucial sobre la disponibilidad de medicamentos y los servicios ofrecidos. De este modo, el problema central identificado fue la ausencia de un sistema web y un catálogo en línea para la promoción de productos y servicios. Por consiguiente, la solución seleccionada y objetivo principal del proyecto fue el desarrollo de un Sistema Web para la farmacia “INSUMEDIC C.A”. En cuanto a la metodología, el sistema fue desarrollado empleando la Metodología de Programación Extrema (Extreme Programming - XP). Esta metodología ágil priorizó la adaptabilidad al cambio y se basó en ciclos de desarrollo cortos (iteraciones) definidos por la priorización de historias de usuario, con el fin de entregar el máximo valor al cliente en el menor tiempo posible. Finalmente, el producto terminado es una aplicación web responsive, diseñada para ser compatible con diversos dispositivos móviles, ofreciendo las siguientes funcionalidades clave: Catálogo Completo y Funcionalidad de Búsqueda: Permite la búsqueda avanzada de medicamentos y productos por nombre, proveedores y categorías, facilitando a los clientes encontrar rápidamente lo que necesitan. En conclusión, el proyecto dotó a INSUMEDIC de una plataforma tecnológica robusta y escalable que le permite optimizar la promoción de sus productos, mejorar la experiencia del cliente y posicionarse de manera competitiva en el sector farmacéutico.', 1, 'farmacia INSUMEDIC C.A', '', '2026-10-19 23:21:49.859608', NULL, NULL, 'Diseño y desarrollo de un sistema web para la farmacia “INSUMEDIC C.A”.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (261, '2026-07-01', 'Pregrado', 'El proyecto Socio Integrador tiene como eje central la creación de una aplicación web que funcione no solo como un medio de comunicación, sino también como una herramienta estratégica que permita gestionar servicios como la consulta y descarga en línea de resultados, el acceso a información actualizada sobre pruebas disponibles, horarios e indicaciones, y la consolidación de una presencia digital sólida. la entrega de información relevante, el contacto directo con pacientes y la promoción de los servicios del laboratorio. Para el desarrollo de la solución informática, se utilizó una metodología que incluyó técnicas de recolección de información como la observación, la encuesta, la entrevista y la revisión documental, lo que permitió identificar las necesidades específicas del laboratorio y sus pacientes. En cuanto a las herramientas de desarrollo, se emplearon lenguajes de programación como HTML para la estructura del sitio, CSS para el diseño y estilización, y PHP para la lógica del servidor y la gestión de bases de datos. Estas herramientas, combinadas con un enfoque metodológico basado en la recopilación de datos precisa y el análisis de requerimientos, permitieron crear una aplicación web robusta y funcional que garantiza el liderazgo del laboratorio en el sector de salud local, alineándose con las tendencias tecnológicas actuales y mejorando significativamente la calidad del servicio ofrecido a la comunidad. La implementación de la aplicación web generará múltiples beneficios: fortalece la imagen corporativa, facilita el acceso a la información oportuna y a los resultados, mejorando así la satisfacción y confianza del cliente, lo que reforzará el compromiso del laboratorio con la excelencia, la innovación y el bienestar de sus pacientes.', 1, 'LABORATORIO CLÍNICO GENÉSIS C.A', '', '2026-10-19 23:22:40.994981', NULL, NULL, 'Desarrollar una aplicación web integral para el Laboratorio Clínico Génesis C.A. en Valera, Estado Trujillo, que optimice su presencia digital, facilite la gestión de servicios clínicos y mejore la interacción con los pacientes.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (262, '2025-11-01', 'Pregrado', 'Este proyecto de investigación, inscrito en el Programa Nacional de Formación en Informática, aborda la crítica problemática de gestión administrativa identificada en el Centro de Alta Tecnología "Dr. Humberto Fernández Morán", donde la dependencia de procesos manuales para el registro y control de pacientes generaba ineficiencias operativas, riesgo de pérdida de información y errores en la transcripción de datos. Mediante un riguroso diagnóstico basado en la Investigación Acción Participativa (IAP), que incluyó múltiples visitas comunitarias y el uso de matrices de análisis FODA y priorización, se diseñó y desarrolló como solución tecnológica el Sistema de Información Integral para la Gestión de Pacientes (SIGP), una aplicación web robusta que, aplicando la metodología DevOps, centraliza y automatiza los procesos de citas, historiales médicos y generación de estadísticas. La implementación exitosa del SIGP no solo optimizó sustancialmente la eficiencia administrativa y la seguridad de los datos, sino que también se posiciona como un aporte significativo al fortalecimiento del sistema de salud pública, reafirmando el compromiso de la universidad con la transferencia tecnológica y la innovación al servicio de la comunidad.', 1, 'CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN"', '', '2026-10-19 23:23:20.301903', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (263, '2026-07-01', 'Pregrado', 'El presente proyecto socio-tecnológico abordó el diseño e implementación de PANTERA-GEST, un sistema web desarrollado para optimizar la gestión administrativa de la empresa "La Pantera PC Store", ubicada en Valera, Estado Trujillo. El diagnóstico situacional inicial evidenció que el control de inventarios, la facturación y el registro de servicios técnicos se ejecutaban de manera empírica y manual, lo que generaba pérdida de información, errores operativos y demoras en la atención al cliente. Para solucionar estas fallas, se aplicó una metodología cualitativa bajo el enfoque de Investigación-Acción Participativa (IAP) combinada con el marco de trabajo ágil Scrum, organizando el desarrollo en ciclos iterativos o sprints. El software se estructuró bajo el patrón arquitectónico Modelo-Vista-Controlador (MVC), garantizando un código modular, seguro y escalable. Las tecnologías empleadas para su construcción fueron de código abierto, destacando HTML5, CSS3 y JavaScript para la interfaz responsiva del frontend, PHP nativo para la lógica del backend y MySQL para la persistencia en el sistema de gestión de bases de datos. Como producto final, se consolidaron módulos operativos para el inventario bimonetario, facturación, atención al cliente (CRM), servicio técnico con trazabilidad de tickets mediante fases, y un módulo analítico de reportes gerenciales en tiempo real con capacidades predictivas de stock. Las pruebas unitarias y de usabilidad de caja negra validaron la estabilidad del sistema con los usuarios reales. Finalmente, el despliegue local y los talleres de transferencia tecnológica aseguraron la adopción exitosa de la plataforma por parte del personal, logrando la modernización, eficiencia operativa y automatización de los procesos comerciales del establecimiento.', 1, 'La Pantera PC STORE', 'Sistema web, gestión administrativa, control de inventario, servicio técnico, Scrum', '2026-10-19 23:24:24.616744', NULL, NULL, 'Implementar un sistema web para la gestión administrativa de la Pantera PC Store.', true, NULL, 4, 0);
INSERT INTO public.detalles_proyectos VALUES (264, '2026-03-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en CBIT del Complejo Educativo “Hilario Pizani Anselmi”. Se utilizó la metodología del Investigación-Acción Participativa (IAP) con un enfoque cualitativo y descriptivo, permitiendo la interacción directa con el personal y los estudiantes de la institución. Como técnica de recolección de datos se utilizó la entrevista a la tutora encargada, la Ing. Marlene Sosa. La fase de la elaboración de la propuesta, consistió en un plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: De un total de 20 computadoras, solo 8 se encontraban operativos, mientras que los 12 restantes presentaban fallas críticas. Entre los problemas detectados están disco duros y fuentes de poder dañados, memoria RAM insuficiente y software desactualizado. A pesar de las restricciones al acceso al hardware del CBIT de la institución este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador, realizando mantenimiento preventivo y correctivo a equipos propios del grupo de PST, además de la elaboración de un taller de capacitación técnica dirigida a los estudiantes de la institución para fomentar el cuidado y buen uso de los recursos', 1, 'El Complejo Educativo “Hilario Pizani Anselmi”', 'Soporte Técnico, Mantenimiento, hardware, software', '2026-10-19 23:24:58.424411', NULL, NULL, 'Actividades Fecha Duración Recursos/Herramientas Diagnosticar los problemas que presenta el CBIT de la institución Elaboración y Presentación de la propuesta de proyecto 13/06/25 2 horas Hojas, Lápiz Realización del inventario de los equipos del CBIT y toma de nota de los problemas que presentaban', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (265, '2026-04-01', 'Pregrado', 'Este proyecto socio-tecnológico surge como una solución integral ante la necesidad de optimizar la infraestructura tecnológica de la Alcaldía Municipal Rafael Rangel. El objetivo general es realizar el servicio de soporte técnico, mantenimiento preventivo y correctivo a los equipos de computación, además de capacitar a los usuarios en el manejo eficiente de los recursos informáticos. Se enmarca en la Investigación-Acción Participativa (IAP), permitiendo una interacción directa entre los investigadores y el personal administrativo. Se utilizaron técnicas de observación directa, entrevistas y diagnósticos técnicos para identificar las fallas recurrentes en los sistemas.. A través de la ejecución del plan de acción, se logró mejorar la operatividad de los equipos, garantizando un entorno digital estable para las funciones administrativas de la institución. Esta intervención no solo resuelve deficiencias técnicas, sino que fortalece la soberanía tecnológica y el compromiso social de los autores, dejando capacidades instaladas en el personal de la alcaldía', 1, 'Alcaldía del Municipio Rafael Rangel', 'Socio-tecnológico, soporte técnico, mantenimiento preventivo, mantenimiento correctivo, capacitación, infraestructura', '2026-10-19 23:24:58.575086', NULL, NULL, 'Desarrollar un soporte técnico a equipos de computación y usuarios en la institución señalada.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (266, '2025-06-01', 'Pregrado', 'Este proyecto sociotecnológico se ejecutó en el Infocentro de la Gran Base de Misiones "Hugo Chávez Frías" se encuentra ubicado en La Floresta estado Trujillo, nuestro objetivo central fue optimizar la operatividad de 25 equipos informáticos para garantizar un servicio de calidad mediante un diagnóstico participativo, se identificaron fallas de rendimiento y equipos inoperativos causados por la falta de mantenimiento preventivo. Para solventar esta problemática se implementó un plan de acción que incluyó limpieza física de componentes, reinstalación de sistemas operativos y la migración estratégica a software libre en equipos de bajos recursos Complementariamente, se dictaron talleres de formación técnica para facilitadores y usuarios. Como resultado, se logró recuperar el 96% de la infraestructura tecnológica, beneficiando directamente a más de 500 usuarios mensuales. Para asegurar la sostenibilidad del servicio, se entregó un manual de procedimientos y cronogramas de revisiones periódicas además la activa participación comunitaria fortaleció la apropiación tecnológica y la corresponsabilidad en el cuidado del hardware. En conclusión, esté proyecto no solo restauró la funcionalidad técnica, sino que sentó las bases para una gestión autónoma y duradera, promoviendo la inclusión social mediante el acceso efectivo a las tecnologías de información. DEDICATORIA Este logro va dedicado con todo nuestro corazón a nuestras familias que con su amor y sacrificio nos han sostenido en cada paso de este camino, a la comunidad de La Floresta que nos abrió sus puertas y nos permitió aprender de su realidad, al personal del Infocentro por su confianza y disposición para trabajar juntos, y a todos aquellos que creen en el poder de la tecnología para transformar vidas. También dedicamos este esfuerzo a nuestros compañeros de estudio y a quienes día a día luchan por una educación inclusiva y de calidad, porque este proyecto es fruto del trabajo en equipo y de la convicción de que otro mundo es posible cuando unimos conocimientos y voluntades', 1, 'Fundación Infocentro Gran Base de Misiones por la Paz Hugo Chávez Frías La Floresta', '', '2026-10-19 23:24:58.673105', NULL, NULL, 'Realizar soporte técnico a los equipos y brindar capacitación a los usuarios de la comunidad de La Floresta.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (267, '2025-05-01', 'Pregrado', 'La dependencia tecnológica en empresas exige personal cualificado en soporte y mantenimiento informático para evitar ineficiencias y fallas. En este contexto, el Programa Nacional de Formación en Informática (PNFI) de la UPTTMBI capacita a futuros profesionales en dicha área. Como parte de la formación, el Proyecto Socio Tecnológico Integrador sirve como herramienta para lograr adquirir y aplicar el conocimiento en un entorno real y monitoreado. Siendo la empresa privada Duilio De Jesús Valero Romero Panadería, Pastelería Y Lunchería “La Estrella De Valera” en la localidad de Valera, Estado Trujillo. Un sitio pertinente para proporcionar un servicio técnico de mantenimiento y correctivo en el hardware y software en los equipos activos dentro de la empresa, buscando satisfacer las demandas reales en los equipos con el mantenimiento, y en los usuarios con la alfabetización tecnológica, con el fin de brindar conocimientos tangibles al equipo de estudiantes que llevara a cabo dicha práctica', 1, 'Duilio De Jesús Valero Romero Panadería', 'Soporte técnico a usuarios y a equipos, Hardware, Software', '2026-10-19 23:24:58.763374', NULL, NULL, 'Proporcionar soporte técnico a usuarios y equipos informáticos de la Panadería "La Estrella" para optimizar su rendimiento.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (268, '2026-04-01', 'Pregrado', 'El presente proyecto socio-tecnológico se centró en recuperar y optimizar la infraestructura tecnológica del Ciber Café Poncho S.A., la cual se encontraba en un estado crítico que afectaba su funcionamiento. Al mismo tiempo, organizamos una jornada de capacitación técnica para los compañeros de nuestra universidad, buscando fomentar la transferencia de conocimiento sobre cómo cuidar y mantener sus propios equipos. Durante el diagnóstico en el local, notamos que el 80% de las computadoras no funcionaban, por lo que aplicamos un plan de mantenimiento preventivo profundo de hardware y limpieza de sistemas operativos, logrando una optimización que dejó todas las unidades operativas antes del cierre del establecimiento. Todo este trabajo se realizó bajo una metodología socio- tecnológica con un paradigma cualitativo, utilizando un diseño de campo no experimental y el enfoque de investigación-acción participativa (IAP), lo que nos permitió aplicar la teoría en un entorno real y brindar un soporte técnico efectivo ante imprevistos. En conclusión, demostramos que combinar el mantenimiento profesional con la educación de los usuarios es la mejor forma de garantizar la sostenibilidad técnica tanto en los negocios como en la universidad', 1, 'Ciber Café Poncho S.A', 'Mantenimiento preventivo, infraestructura', '2026-10-19 23:24:58.860703', NULL, NULL, 'Implementar un plan de soporte técnico integral en el Ciber Café Poncho S.A., enfocado en el mantenimiento, diagnóstico y optimización de los equipos de computación, para subsanar las fallas técnicas y desactualizaciones, garantizando así un servicio digital estable, eficiente y de alto rendimiento que satisfaga las necesidades informáticas de la comunidad de Plata 3.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (269, '2026-03-01', 'Pregrado', 'El Proyecto Socio-Tecnológico (PST) se fundamenta como el núcleo de integración de saberes teóricos y prácticos orientados a resolver problemas reales. Este proyecto tiene como objetivo general desarrollar el soporte técnico, alargar la vida útil de los equipos computacionales del Laboratorio 2 de Informática (UPTTMBI, Núcleo La Beatriz) y capacitar a sus usuarios. La metodología se basó en la Investigación-Acción Participativa (IAP) con un diseño de campo no experimental. Destaca el uso de la investigación aplicada, cuyo propósito fue utilizar los conocimientos técnicos del PNFI para abordar un problema práctico, ejecutando protocolos de mantenimiento preventivo y correctivo junto con capacitaciones. El diagnóstico evidenció que los 14 equipos de muestra presentaban deficiencias (86% lógicas y 14% físicas) por polvo, sistemas obsoletos y desconocimiento operativo. Con la aplicación directa de estas soluciones teóricas a la realidad del laboratorio, se logró restaurar la operatividad total de las computadoras. Esto garantizó la optimización de los recursos informáticos, prolongó su vida útil y mejoró significativamente el desarrollo de los procesos académicos', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo Dr.  Pablo Viloria', 'Soporte técnico, Mantenimiento preventivo, Mantenimiento correctivo, Capacitación, Hardware, Software', '2026-10-19 23:24:59.01139', NULL, NULL, 'Dotar a los usuarios de las habilidades y los conocimientos necesarios para navegar por el sistema, aprovechar al máximo sus funciones y resolver problemas comunes que puedan surgir.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (271, '2026-03-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en la Escuela Nueva Generación Eloisa Fonseca. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó el árbol de problemas el cual explica el problema central junto con sus causas y consecuencias .Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: Se identificó solo 6 equipos operativos de un total de 12, se ejecutó un plan de acción enfocado en las 6 unidades restantes que se encontraban fuera de servicio. Gracias a estas labores, se logró una recuperación operativa 4 de 6 equipos críticos, reintegrando 4 de ellos al sistema en óptimo estado funcional .Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'ESCUELA NUEVA ELOISA FONSECA', '', '2026-10-19 23:24:59.21358', NULL, NULL, 'Desarrollar Mantenimiento Preventivo y Correctivo a Equipos de Computación Dictar taller teórico-práctico sobre Hardware y Software.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (272, '2025-07-01', 'Pregrado', 'Las tecnologías de información están presentes en todos los ámbitos de la sociedad, son herramientas que apoyan las actividades de un analista de sistemas quien es un profesional capacitado para planificar, desarrollar sistemas de información automatizados, diseñar aplicaciones Web, móviles, según las necesidades y requerimientos de las instituciones del gobierno, instituciones financieras, empresas u organizaciones. El analista en una empresa puede asumir diferentes roles, por ejemplo, puede trabajar como programador, con el tiempo cuando haya adquirido mucha experiencia puede ser un líder de proyecto, jefe del área de sistemas o computación, utiliza la tecnología, es decir lenguajes de programación, diferentes entornos operativos, por consiguiente, debe estar actualizado con los nuevos avances. Las nuevas tecnologías de información son herramientas o recursos que permiten hacer las actividades de manera diferente, más rápida y eficiente, facilitan cualquier trabajo, actividad o tarea, disminuyen esfuerzo, costos, tiempo. Entre las nuevas tecnologías se encuentra la inteligencia artificial, metaverso, blockchain, cloud, big data, entre los lenguajes de programación se encuentra Python, Java Script, etc. Hoy día el analista de sistemas debe actualizar sus conocimientos para adquirir competencias necesarias que le permitan ingresar y mantenerse en el mercado laboral bien sea presencial u online, el analista debe investigar hoy más que nunca para implementar los cambios necesarios ya que se observa cómo cambia y evoluciona la tecnología tan rápido, y así no quedar obsoletos en conocimiento, y ser un profesional competitivo. En el caso específico del analista de sistemas, las tecnologías de información apoyan o ayudan al analista en sus funciones o en las actividades, porque permiten mejorar o innovar los sistemas de información existentes, también permiten mejorar los procesos administrativos, por ejemplo, incorporando nuevo hardware y software. El analista puede trabajar desde su casa utilizando la tecnología de manera online, se puede comunicar con su equipo de trabajo, en cualquier parte el mundo, a través de videos llamadas, a través de las aplicaciones que permiten hacer conferencias. Almacenar gran cantidad de información en la nube y compartirla. Los analistas pueden desarrollar aplicaciones Web, móviles, utilizando diferentes lenguajes de programación, diseñar aplicaciones basadas en la nube, diseñar sistemas de aprendizaje utilizando la inteligencia artificial, muy importante la robótica que permite crear robots y sistemas automatizados para facilitar y hacer más eficientes las tareas, actividades, la tecnología ayuda automatizar los procesos de las empresas o instituciones por ejemplo en Venezuela, la banca móvil, Seniat, SAIME,CNE, INTT, etc, que permiten hacer las transacciones y tramites vía celular, facilitando la comunicación, rompiendo barreras de espacio y tiempo, ofrece información en tiempo real, ayuda al analista hacer más productivo ,eficiente en el trabajo. Muy importante o a manera de cierre, en mi opinión, la tecnología es una herramienta muy importante, valiosa en la actualidad, el analista de sistemas tiene que adaptarse a los cambios tecnológicos, especializarse, innovar, investigar y crear soluciones informáticas, innovadoras, utilizando las nuevas tecnologías de acuerdo a los requerimientos y exigencias actuales, para que la empresa y uno como analista ser competitivos y mantenerse en el mercado laboral presencial u online', 1, 'Empresa “IP SISTEMAS', '', '2026-10-19 23:24:59.308045', NULL, NULL, 'Analizar y mejorar el área de soporte técnico de la empresa IP Sistemas, mediante la identificación de fallas en los equipos de computación y en la gestión tecnológica.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (273, '2026-04-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en el Despacho de Carga Suroccidente. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, la cual se complementó con la metodología cualitativa bajo el enfoque de investigación acción participativa. Proyecto factible, de carácter descriptivo con una acción transformadora, se realizó en tres fases. Como técnica de recolección de datos se utilizó la entrevista no estructurada y como instrumento un cuestionario de preguntas cerradas. La fase de la elaboración de la propuesta consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: los equipos presentaban saturación por acumulación de polvo y degradación de la pasta térmica, lo que genera un aumento en la temperatura interna del hardware; asimismo, se detectó lentitud por la saturación de archivos temporales y falta de hábitos preventivos en los usuarios para el cuidado de los componentes. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Despacho de Carga Sur Occidente', 'Mantenimiento, hardware, software', '2026-10-19 23:24:59.418966', NULL, NULL, NULL, true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (274, '2026-04-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue aplicar Soporte técnico a equipos y usuarios en la Clinica Uga , Valera Estado Trujillo. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cualitativa. Proyecto de investigación acción participativa, de carácter descriptiva, se realizó en cuatro fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: No existe un sistema de seguimiento y control de las computadoras, ocasionando el deterioro de algunos equipos por falta de mantenimiento preventivo y dotación de equipos actualizados. También se observó el desconocimiento de los usuarios en el manejo de los equipos. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Clinica Uga', 'Computadoras, mantenimiento, usuarios, hardware, software', '2026-10-19 23:24:59.537726', NULL, NULL, 'Aplicar el Soporte técnico a equipo y usuarios de la Clínica UGA C.A.,', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (275, '2026-04-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue la realización del Soporte técnico a usuarios y equipos de computación en la Escuela Técnica Comercial “Pedro García Leal”. Se empleó la metodología del marco lógico para establecer los problemas, causas y consecuencias, se utilizó la metodología cuantitativa, proyecto factible, de carácter descriptivo, se realizó en tres fases. Como técnica de recolección de datos se utilizaron la entrevista y la encuesta, como instrumento el cuestionario. La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, se dictó el taller de capacitación a los usuarios. Los resultados obtenidos evidencian falta de mantenimiento en los equipos de computación, pasta térmica deteriorada y batería del CMOS/BIOS vencido. Se realizó la instalación de antivirus, instalación de software de propietario (Windows y Microsoft Office), del mismo modo se hizo necesario reemplazar componentes a los equipos de computación. Este proyecto permitió a los estudiantes aplicar los conocimientos adquiridos en el área de arquitectura del computador, Algoritmo y Programación y Matematicas I', 1, 'Institución Educativa Técnica Comercial', 'hardware, mantenimiento a equipos, software, soporte a usuarios', '2026-10-19 23:24:59.65562', NULL, NULL, 'Brindar soporte técnico a usuarios y equipos de computación de la Escuela Técnica Comercial “Pedro García Leal” del municipio Valera del Estado Trujillo.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (276, '2026-04-01', 'Pregrado', 'El objetivo general del proyecto socio tecnológico es brindar un plan de soporte técnico a equipos de computación y a usuarios del Ministerio del Poder Popular para el Transporte, Sede Trujillo, con el fin de mejorar la eficiencia operativa y la calidad de los servicios prestados por la institución. Se utilizó la metodología del marco lógico para determinar los problemas, causas, consecuencias, presentando las propuestas en un plan de acción como herramienta guía para la ejecución de actividades de soporte técnico a usuarios y equipos que permitieran el cumplimiento de objetivos en el tiempo estimado. Se tomó la Investigación Acción Participativa (IAP) enfocada a la acción transformadora, apoyada en la Investigación Tecnológica Aplicada, optando por un diseño de Investigación No experimental descriptivo con un enfoque cualitativo y dentro de la IAP. Como técnica de recolección de datos se utilizó la entrevista, además de la observación directa en las oficinas. Los resultados obtenidos evidencian las necesidades de atención en equipos técnicos y desempeño humano, además de la oportunidad de contribuir con la solución a las fallas detectadas y la capacitación personalizada al personal de la institución, evidenciando que la gestión tecnológica requiere un ciclo constante de revisión y actualización para no recaer en la inoperatividad. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Comunidad / Organización No Específicamente Nombrada', 'Soporte técnico, soporte a usuarios', '2026-10-19 23:24:59.755223', NULL, NULL, 'Brindar un plan de soporte técnico a equipos de computación y a usuarios del Ministerio del Poder Popular para el Transporte, sede Trujillo, con el fin de mejorar la eficiencia operativa y la calidad de los servicios prestados por la institución.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (278, '2025-04-20', 'Pregrado', 'El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en la Escuela de Emprendimiento Valera del Estado Trujillo. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cualitativa. El proyecto, que tiene una naturaleza descriptiva y es factible, se llevó a cabo en tres fases. Como técnica de recolección de datos se utilizó la entrevista y como instrumento la toma de notas, y de manera adicional la observación de participantes, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: las computadoras del lugar son modelos antiguos frecuentando su mantenimiento, falta de actualizaciones de los componentes de Windows/Linux ya que por la poca capacidad de los equipos no es recomendable las actualizaciones continuas, monitores con poca visualización, computadoras inactivas y que los equipos tienen poca capacidad. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'ESCUELA DE EMPRENDIMIENTO VALERA', 'Mantenimiento, hardware, software', '2026-10-19 23:24:59.986036', NULL, NULL, 'Desarrollar soporte técnico a las computadoras y taller de capacitación a los usuarios de la Escuela de Emprendimiento Valera.', true, NULL, 1, 0);
INSERT INTO public.detalles_proyectos VALUES (279, '2026-07-01', 'Pregrado', 'El presente proyecto se inserta en esta dinámica de transformación, orientando sus esfuerzos hacia el diseño, desarrollo e implantación de un Sistema Didáctico e Interactivo para la Enseñanza de Matemática Básica en la Unidad Educativa “José Antonio Velasco”, ubicada en la Calle Principal de San Genaro, Parroquia Carvajal del Municipio San Rafael de Carvajal, en el Estado Trujillo. Esta institución posee una trayectoria histórica que se remonta formalmente a la década de 1940 y que ha evolucionado significativamente hasta convertirse en una organización educativa eficiente, orientada a la formación integral de niños desde la educación inicial hasta el sexto grado de educación primaria; a pesar de contar con una estructura organizativa sólida y un Centro de Informática y Telemática (CBIT).', 1, 'ESCUELA “JOSE ANTONIO VELASCO"', 'Matemática básica, Metodología Scrum, Sistema interactivo, Software educativo', '2026-10-19 23:26:33.99831', NULL, NULL, 'Desarrollar e implementar el SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (280, '2026-10-19', 'Pregrado', 'El objetivo principal de este proyecto es fortalecer las enseñanzas de matemáticas en estudiantes de primer año del Liceo “Hilario Pizani Anselmi”, mediante el desarrollo de un Software Educativo offline que será implementado en el Centro Bolivariano de Informática y Telemática (CBIT). Esta herramienta busca mejorar el proceso de enseñanza-aprendizaje en el área de matemática, ofreciendo contenidos estructurados, interactivos y accesibles sin necesidad de conexión a internet, especialmente en contextos donde la red wifi presenta limitaciones. El software estará compuesto por módulos interactivos alineados al currículo nacional, con énfasis en operaciones básicas, lógica matemática y resolución de problemas. Incluirá ejercicios autocorregibles, material complementario extraído de los libros de la Colección Bicentenaria —en especial el texto “Matemática para la Vida” de primer año—, así como recursos multimedia y guías pedagógicas para docentes que faciliten su integración en el aula. El proyecto contempla un diagnóstico situacional, justificación, impacto social, fundamentos teóricos y un plan de acción detallado. Se espera que esta iniciativa incremente la disponibilidad de herramientas digitales adaptadas al nivel académico, promueva el aprendizaje autónomo y guiado, y fomente una cultura de cuidado y aprovechamiento de los recursos tecnológicos disponibles.', 1, 'LICEO “HILARIO PIZANI ANSELMI', '', '2026-10-19 23:33:45.603171', NULL, NULL, 'Implementar un Software Educativo Offline para la enseñanza de Matemáticas de Primer año en el Liceo “Hilario Pizani Anselmi”', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (281, '2025-11-01', 'Pregrado', 'El presente proyecto socio-tecnológico consistió en la implementación de un software educativo para optimizar la enseñanza de Programación y Diseño de Software y Redes en los estudiantes de 4to año de Telemática de la E.T. "Pedro García Leal". La investigación, bajo la metodología de Investigación Acción Participativa, empleó entrevistas, observación y encuestas que identificaron necesidades como escasez de recursos didácticos, equipos obsoletos y falta de materiales interactivos. Como solución, se desarrolló una herramienta digital que integra contenidos teóricos, evaluaciones, retos de conocimiento y herramientas de autoaprendizaje. El desarrollo técnico utilizó Python como lenguaje de programación, Tkinter para la interfaz gráfica, SQLite3 como gestor de base de datos y Visual Studio Code como entorno de desarrollo, siguiendo las fases de la metodología de Álvaro Galvis (diseño, implementación, prueba y evaluación participativa). Las pruebas funcionales en el laboratorio de informática contaron con la participación de docentes y estudiantes, quienes manifestaron su satisfacción con la utilidad y facilidad de uso del software. Paralelamente, se realizó un taller de capacitación y se entregó el manual de usuario en formato digital. Los logros obtenidos incluyeron el fortalecimiento del aprendizaje autónomo, el aumento de la motivación estudiantil mediante recursos interactivos y la mejora de las estrategias pedagógicas docentes. Como recomendaciones finales, se sugiere mantener y actualizar periódicamente el software, incorporar una red local con servidor central para permitir su uso simultáneo, y extender su aplicación a otras menciones y años académicos dentro de la institución educativa.', 1, 'E.T. “Pedro García Leal”', '', '2026-10-19 23:36:15.396849', NULL, NULL, 'Implementar un software educativo para la enseñanza de los RTP de Programación y Diseño de Software y Redes dirigido a los estudiantes de 4to año Telemática A de la E.T. “Pedro García Leal”.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (277, '2026-04-01', 'Pregrado', 'Este proyecto socio tecnológico es la búsqueda de una solución integrada comunidad – universidad que tiene como objetivo general realizar el servicio del soporte técnico en el Laboratorio 1 del Programa Nacional en Informática de la Universidad Politécnica Territorial Mario Briceño Iragorry, mediante la introducción de estrategias efectivas que garanticen el correcto funcionamiento de los equipos computacionales y la capacitación efectiva de los usuarios. Además, se llevó a cabo bajo el enfoque metodológico de investigación de acción participativa con un diseño mixto que integra técnicas tecnológicas y descriptivas para el abordaje sistemático de las fallas de hardware y software detectadas en el contexto universitario. Asimismo, en función de esto y bajo los requerimientos de la oportunidad el alcance del proyecto cumplió sus objetivos mediante la ejecución de un plan de mantenimiento preventivo y correctivo que optimizó la infraestructura tecnológica, asegurando la estabilidad de los sistemas y fortaleciendo el proceso de enseñanza para la comunidad del PNF en Informática, en las capacitaciones se brindó la información necesaria para los usuarios en el mantenimiento preventivo y correctivo. En este sentido, este proyecto permite consolidar un entorno digital seguro, eficiente y actualizado según los estándares institucionales', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” (UPTTMBI)', 'Socio tecnológico, soporte técnico, mantenimiento preventivo y correctivo, capacitación usuarios', '2026-10-19 23:24:59.88587', NULL, NULL, 'Realizar el servicio de soporte técnico integral en el Laboratorio 1, garantizando el correcto funcionamiento de los equipos.', true, NULL, 1, 1);
INSERT INTO public.detalles_proyectos VALUES (282, '2025-11-01', 'Pregrado', 'La investigación, alineada con el PNF en Informática (UPT-Trujillo), tuvo como objetivo la implementación de una aplicación web modular para optimizar la gestión académica de calificaciones en el Liceo Andrés Bello. Mediante un diagnóstico situacional in situ (triangulación de datos, observación y análisis documental), se identificó la problemática central: latencia operativa e inconsistencia en la data debido a procesos manuales y obsolescencia tecnológica.La metodología IAP (Investigación-Acción Participativa) guio la co-construcción iterativa del sistema, involucrando al stakeholder clave. El desarrollo técnico incluyó la conceptualización de la arquitectura Cliente-Servidor, la programación orientada a objetos de módulos transaccionales (registro/consulta), la integración de algoritmos de cálculo automatizado, y la implementación de protocolos de seguridad (autenticación basada en roles y cifrado SSL/TLS). Los resultados demuestran empíricamente que el sistema reduce los tiempos de procesamiento (hasta en un $X\%$), minimiza el margen de error en el data entry y fortalece la trazabilidad y transparencia de la información institucional. Se concluye que esta solución tecnológica constituye un modelo de referencia (benchmark) para la modernización de la infraestructura digital educativa en entornos rurales, impulsando la eficiencia y calidad de la gestión escolar', 1, 'LICEO ANDRÉS BELLO', 'Aplicación Web, Gestión Académica, Optimizacion de Procesos, IAP, Trazabilidad de Datos', '2026-10-19 23:44:28.850666', NULL, NULL, 'Desarrollar e implementar el APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (283, '2025-11-01', 'Pregrado', 'El presente informe demuestra todo el desarrollo llevado a cabo para la elaboración de una Aplicación Web Informativa a base del uso de distintas herramientas y métodos para su logro, tomando como base de dicha Aplicación, el contenido del Parque Botánico, Flora, Fauna y sus Áreas, para la creación de un sitio web que esté disponible para el público general y pueda informarse desde la comodidad de sus casas o escritorios, todo lo que la comunidad de INPARQUES en el municipio Valera tiene para ofrecer, sumando también un importante acceso de Administración para el personal de la comunidad, con el objetivo de permitir que la página sea actualizable cada vez que sea necesario, aplicando incluso herramientas fuera de lo común con el desarrollo web como Canva para lograr la meta de ofrecer un producto de calidad', 1, 'Parque de Recreación Botánico La Beatriz', '', '2026-10-19 23:44:28.921378', NULL, NULL, 'Implementar en el Parque de Recreación Botánico La Beatriz una Aplicación Web que conserve el contenido a mostrar de la comunidad.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (284, '2025-11-01', 'Pregrado', 'El proyecto socio tecnológico “Aplicación Web para el Complejo Educativo Pedro Pablo Paredes” tuvo como objetivo desarrollar un portal institucional que facilitara la comunicación entre la institución y la comunidad educativa. La ausencia de un sitio web oficial dificulta la difusión de información y la visibilidad de las actividades. Se aplicó la metodología del marco lógico, complementada con un enfoque cuantitativo, y se ejecutaron tres fases: diagnóstico de necesidades, diseño y desarrollo de la aplicación, además de capacitar a los usuarios. La propuesta incluyó la creación de una aplicación web para publicar noticias, actividades e imágenes, junto con un taller formativo. Los resultados demostraron que la implementación fortaleció la comunicación y contribuyó a la modernización tecnológica del Complejo Educativo', 1, 'Institución educativa de nivel medio general y técnica “Complejo Educativo Pedro Pablo Paredes”', 'Aplicación web, Comunicación, Educación, Información, Tecnología', '2026-10-19 23:44:29.004372', NULL, NULL, 'Implementar una aplicación web para el Complejo Educativo Pedro Pablo Paredes.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (285, '2026-07-01', 'Pregrado', 'El presente Proyecto Socio-Tecnológico tuvo como objetivo desarrollar una aplicación web para la gestión de ventas del Auto Servicio El Triángulo C.A., ubicado en Valera, Estado Trujillo, con el fin de automatizar los procesos de inventario, ventas y atención al cliente, los cuales se realizaban de manera manual y generaban inconsistencias en los registros y baja eficiencia operativa. La investigación se enmarcó en un paradigma mixto, utilizando la Investigación-Acción Participativa (IAP) como enfoque metodológico, complementada con la metodología ágil Extreme Programming (XP) para el desarrollo del software, lo que permitió una adaptación continua a los requerimientos de la comunidad. El sistema fue construido con tecnologías de código abierto (PHP, MySQL, JavaScript, HTML5 y CSS3) e integra módulos de catálogo digital, gestión de inventario, proceso de compras, historial de clientes, panel administrativo y reportes estadísticos. Los resultados obtenidos, validados mediante pruebas de usabilidad con el personal y clientes frecuentes, evidenciaron una percepción altamente favorable, confirmando que la aplicación cumple con los principios de facilidad de aprendizaje, eficiencia y satisfacción del usuario. Se concluye que el proyecto cumplió con todos los objetivos planteados, generando un impacto positivo en la comunidad al proporcionar una herramienta tecnológica que optimiza los procesos comerciales del autoservicio', 1, 'Auto Servicio El Triángulo C.A', 'Aplicación web, comercio electrónico, gestión de ventas, inventario', '2026-10-19 23:44:29.078593', NULL, NULL, 'Desarrollar e implementar el APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (286, '2025-11-01', 'Pregrado', 'El presente proyecto tiene como finalidad desarrollar e implementar una aplicación web para la U.E. "Eduardo Blanco ubicada en la parroquia Escuque, municipio Escuque, en respuesta a la carencia de un sitio web en la institución. Por lo tanto, la solución pertinente fue la implementación de una aplicación web institucional diseñada para centralizar la información, mejorar la visibilidad y facilitar el acceso a la comunidad educativa. Se utilizó el Diseño Centrado en el Usuario (UCD). Esta metodología fue crucial para asegurar que la aplicación respondiera directamente a las necesidades y expectativas de los usuarios finales (estudiantes, padres y personal), priorizando la usabilidad y una experiencia de usuario positiva. La implementación generó beneficios tangibles, como una notable mejora en la imagen institucional y la optimización de los canales de comunicación. El proyecto confirma la pertinencia de las soluciones web para instituciones educativas. El principal aprendizaje fue la validación de la metodología UCD como garantía de relevancia y adopción de la herramienta. La aplicación web no solo solventa la necesidad inicial, sino que aporta un canal de comunicación moderno y escalable, fortaleciendo la presencia digital de la U.E. “Eduardo Blanco” y mejorando la interacción con su comunidad. Asimismo, facilita el acceso a la comunidad a información clave y, su desarrollo responde a la necesidad de consolidar una identidad digital institucional clara, segura y alineada con los valores de la U.E. "Eduardo Blanco"', 1, 'UNIDAD EDUCATIVA “EDUARDO BLANCO', 'Aplicación web, Gestión, Comunidad, UCD, identidad digital', '2026-10-19 23:44:29.1629', NULL, NULL, 'Desarrollar e implementar una aplicación web para la U.E. "Eduardo Blanco ubicada en la parroquia Escuque, municipio Escuque, en respuesta a la carencia de un sitio web en la institución.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (287, '2025-11-01', 'Pregrado', 'El proyecto titulado “Pagina Web en el Grupo Educativo Eloísa Fonseca” tiene como misión principal el desarrollo de un portal web institucional para el Grupo Educativo “Eloísa Fonseca”, con el objetivo de facilitar la gestión de la información oficial entre la institución y la comunidad que forma parte de la misma. Este, siguiendo los parámetros de recolección de información y de acción de una metodología cuantitativa. En la actualidad la escuela sufre de diferentes problemáticas, como el deterioro de la infraestructura, equipos desactualizados y la inexistencia de un sitio web oficial que sea de fácil acceso al cuerpo directivo, limitando la capacidad de distribución de información. Esta propuesta tiene como fin implementar un portal web que permita la transmisión de información relevante, como lo son las actividades semanales, asambleas y simulacros. La realización de este proyecto no solo mejorará la comunicación institución-comunidad sino que hará posible una optimización de la organización interna y aumentará la participación comunitaria. La creación del sitio web será considerado como el primer paso hacia la resolución de las deficiencias tecnológicas a las que se enfrenta la escuela; garantizando el cumplimiento de la visión de la Eloisa Fonseca de impartir una educación de calidad', 1, 'Grupo Educativo “Eloisa Fonseca”', 'portal web, información, comunicación, educación, tecnología', '2026-10-19 23:44:29.235168', NULL, NULL, 'Implementar una página web para el Grupo educativo “Eloísa Fonseca”', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (288, '2025-11-01', 'Pregrado', 'El proyecto se centra en el desarrollo de una página web institucional para la Unidad Educativa Fe y Alegría “Gonzalo Senior”, con el objetivo de mejorar la comunicación y la gestión de información entre la institución y su comunidad educativa. Actualmente, la institución enfrenta diversas problemáticas, entre las que destacan el deterioro de su infraestructura, los mínimos conocimientos tecnológicos que tiene el personal de la institución y la ausencia de una página web informativa, lo que limita su capacidad para informar a estudiantes y representantes sobre actividades académicas y administrativas. La propuesta busca implementar un sitio web que permite publicar información relevante como la misión, visión, ubicación e historia, procesos de preinscripción y eventos, facilitando así el acceso a la información sin necesidad de depender de sitios externos. Este desarrollo no solo optimizará la comunicación, sino que también contribuirá a una mejor organización interna y a un incremento en la participación comunitaria. La implementación de la página web se considera una solución clave para abordar las deficiencias en el uso de herramientas tecnológicas en la institución, garantizando un espacio digital seguro y personalizado que refleje la identidad de la Unidad Educativa Fe y Alegría “Gonzalo Senior”', 1, 'Unidad Educativa Fé y Alegría “Gonzalo Senior', 'página web, educativa, comunicación, tecnología, gestión', '2026-10-19 23:44:29.31214', NULL, NULL, 'Implementar una Página Web Institucional para la U.E Fe y Alegría “Gonzalo Senior”', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (289, '2025-11-01', 'Pregrado', 'Este proyecto consistió en el diseño e implementación de una página web institucional para el Colegio Los Cedros, con el objetivo de fortalecer su presencia digital y mejorar la comunicación con la comunidad educativa. El diagnóstico situacional identificó que la página web existente presentaba deficiencias de actualización y diseño, limitando el acceso a información importante. Como solución, se desarrolló un sitio web moderno que incluye información institucional, eventos y una galería de imágenes. Para lograrlo, se establecieron objetivos específicos como diagnosticar las problemáticas, determinar requerimientos, diseñar módulos, codificar, implantar y capacitar al personal. La metodología Extreme Programming (XP) fue adoptada por su flexibilidad y enfoque colaborativo, facilitando una respuesta rápida a cambios mediante prácticas como desarrollo iterativo, integración continua y retroalimentación constante. Esto permitió a los estudiantes aplicar sus conocimientos en informática, creando una solución tecnológica útil que fortalece el vínculo entre la universidad y la institución educativa, garantizando un producto final alineado con las expectativas de los usuarios', 1, 'COLEGIO LOS CEDROS', 'Página Web, programación, software', '2026-10-19 23:44:29.407981', NULL, NULL, 'Implementar una Página Web para el Colegio Los Cedros.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (305, '2025-11-01', 'Pregrado', 'El desarrollo del proyecto tiene como principal objetivo diseñar e implementar una infraestructura de red inalámbrica (WLAN), que permita una conectividad eficiente, segura y escalable para estudiantes, docentes y personal administrativo. Por otra parte, permite ampliar la cobertura de internet a toda la institución además, ayudar a los profesores a reducir el tiempo de transmisión de información. En este sentido y, tomando la topología hibrida WLAN como mejor alternativa para la solución de los problemas de conexión en la institución, se determinaron un punto estratégico del router para su máximo aprovechamiento en cuanto al alcance y rendimiento de la conexión. De igual manera, con la ayuda de simuladores (cisco packet tracer), se obtiene resultados más específicos en cuanto a la conexión de cada uno de los equipos que se encuentren conectados en la red, realizando pruebas de cobertura y velocidad para garantizar una conexión estable y trayendo grandes beneficios para el personal y estudiantes de la institución. Además, asegurando un acceso equitativo para toda la comunidad, mejoras en la calidad pedagógica gracias a la utilización de herramientas educativas y plataformas digitales.', 1, 'ESCUELA “TOSTA GARCIA"', 'Red, WLAN, Inalámbrica, Conexión', '2026-10-20 00:16:09.46605', NULL, NULL, 'Desarrollar e implementar el SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA".', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (290, '2025-11-01', 'Pregrado', 'El presente proyecto tuvo como objetivo general fortalecer la conectividad digital del municipio Escuque, facilitar el acceso a información sobre su dinámica social y cultural, y fomentar la participación ciudadana mediante la creación de una página web municipal con sección dedicada al Infocentro. Los objetivos específicos incluyeron: determinar los requerimientos técnicos, diseñar y desarrollar la plataforma, realizar pruebas de funcionamiento, elaborar el manual de usuario y capacitar al personal del Infocentro para su uso eficiente. La comunidad atendida fue el Infocentro de Escuque, institución que carecía de un canal digital oficial para centralizar y difundir contenidos relevantes. La necesidad detectada se centró en la ausencia de una herramienta tecnológica que permitiera visibilizar la gestión municipal, el patrimonio cultural y las actividades comunitarias, lo cual limitaba la comunicación institucional y ampliaba la brecha digital. La solución tecnológica consistió en implementar una página web como plataforma estratégica de gestión informativa, turística y comunitaria. Se utilizó la metodología ágil Scrum, permitiendo una planificación iterativa, colaborativa y orientada a resultados. Entre los beneficios obtenidos destacan la modernización digital del municipio, el fortalecimiento del Infocentro como ente transparente y activo, y el posicionamiento de Escuque como destino conectado. La experiencia evidenció que el desarrollo de plataformas digitales inclusivas promueve la interacción social, mejora la calidad de vida y contribuye al desarrollo local sostenible, consolidando el vínculo entre tecnología, comunidad e innovación', 1, 'Nombre de la organización', '', '2026-10-19 23:44:29.503237', NULL, NULL, 'Fortalecer la conectividad digital del municipio, simplificar la obtención de información sobre su dinámica social y cultural, y promover una mayor participación ciudadana.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (291, '2025-04-03', 'Pregrado', 'El objetivo general de este proyecto socio-tecnológico se centra en la implementación de una página web para el Centro de Educación Inicial “Consuelo Navas Tovar”, con el propósito de mejorar la comunicación y la gestión de información entre la institución y su comunidad educativa. Actualmente, el centro enfrenta diversas problemáticas, entre las que destacan el deterioro de su infraestructura, especialmente en el salón de usos múltiples, la falta de un laboratorio de informática y la ausencia de una página web, lo que limita su capacidad para informar a los representantes sobre actividades académicas y administrativas del preescolar. La propuesta busca desarrollar una plataforma digital que permite publicar información relevante, como datos generales de la institución, eventos y actividades académicas, facilitando así el acceso sin necesidad de visitas presenciales. Este desarrollo no sólo optimizará la comunicación, sino que también contribuirá a una mejor organización interna y a un incremento en la participación comunitaria. La implementación de la página web se considera una solución clave para abordar las deficiencias en el uso de herramientas tecnológicas en la institución, garantizando un espacio digital seguro y personalizado que refleje la identidad del Centro de Educación Inicial” Consuelo Navas Tovar”', 1, 'CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR', 'página web, tecnología, gestión, institucional, comunicación', '2026-10-19 23:44:29.576492', NULL, NULL, 'Implementar una página web para el Centro de Educación Inicial “Consuelo Navas Tovar” del Municipio Valera, Estado Trujillo.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (292, '2026-07-01', 'Pregrado', 'En virtud de lo expuesto, el presente proyecto tiene como objetivo general el desarrollo de un sistema web que integre la gestión de compra de usuarios para la empresa Publisys, con la finalidad de optimizar tanto la experiencia del cliente como los procesos internos relacionados con ventas, presupuestos y servicios. Para ello, se emplearon tecnologías como HTML, JavaScript y CSS3 en la construcción de la interfaz de usuario (frontend), junto con el desarrollo de la arquitectura del servidor (backend, utilizando MySQL para la base de datos y Apache como servidor web. Esta infraestructura permite la creación de un catálogo digital de productos, así como la habilitación de opciones de permitiendo la creación de un catálogo digital de productos y servicios, opciones de customización para el administrador del sistema, entre otras funcionalidades. Cabe destacar que este proyecto se desarrolló bajo la modalidad de aplicación tecnológica, apoyado en un diseño de campo con nivel descriptivo cuyo propósito es implementar soluciones a través de herramientas informáticas. Para el diagnóstico situacional y el levantamiento de los requerimientos funcionales y no funcionales, el trabajo se ejecutó de manera directa en el entorno operativo. En esta fase, se emplearon técnicas de observación y entrevistas no estructuradas dirigidas al personal de la organización. A nivel metodológico, la investigación se enmarcó bajo la modalidad de proyecto sociotecnológico con enfoque mixto, sustentada en la Investigación-Acción Participativa (IAP). Por su parte, la construcción del software se llevó a cabo bajo los principios de las metodologías ágiles, específicamente SCRUM. Este enfoque dual garantizó la entrega de un producto digital capaz de satisfacer plenamente las necesidades de la empresa. A continuación, se detalla la arquitectura de la investigación, la cual está estructurada en cinco bloques que sustentan el desarrollo del proyecto. El presente documento se estructura en cinco partes. La Parte I: Descripción del Proyecto expone el diagnóstico situacional, la priorización de necesidades, los objetivos y la justificación de la propuesta. Seguidamente, la Parte II: Marco Referencial reúne los antecedentes y las bases teóricas. La Parte III: Marco Metodológico detalla la modalidad de investigación, el diseño y las técnicas de recolección de datos. A continuación, la Parte IV: Producto Final describe el desarrollo tecnológico del sistema web, su validación y los recursos empleados. Finalmente, la Parte V: Conclusiones y Recomendaciones presenta las reflexiones de la implementación, sugerencias, bibliografía y anexos correspondientes', 1, 'EMPRESA PUBLISYS', 'Automatización de Procesos, Catálogo Digital, Gestión de Compras, Metodología SCRUM, Sistema Web', '2026-10-19 23:44:29.667762', NULL, NULL, 'Desarrollar e implementar el SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (293, '2026-07-01', 'Pregrado', 'El presente proyecto socio-tecnológico se basa en la creación de un sistema de gestión web para los recursos pedagógicos en el liceo “Libertador” ubicado en el municipio Valera del estado Trujillo. Con la finalidad de eliminar la brecha existente en cuanto a la relación entre tecnología y educación, además de derribar la barrera comunicacional entre los estudiantes y profesores que se encontraba limitada solo al horario presencial, también brindar un portal informativo para que la institución pueda mostrar noticias y actividades de interés. Este proyecto se enmarco bajo la metodología de investigación acción participativa, enmarcado en una forma de trabajo ágil específicamente SCRUM, ya que el mismo fue realizado en constante participación de la población institucional y comunidad vinculada a la misma para generar una solución de manera agil, practica y eficiente. El desarrollo se sustentó en tecnologías de código abierto como Node.js, Express.js, MySQL y Bootstrap 5, garantizando seguridad, escalabilidad y viabilidad económica. Mostrando en pruebas de usabilidad en muestras aplicadas una aceptación del 100%, por lo que se puede concluir que la solución implementada mejora y optimiza la gestión pedagógica y académica de la institución y brinda una oportunidad de avance, modernización y mejoras tecnológicas para el liceo “Libertador”', 1, 'LICEO “LIBERTADOR', 'gestión pedagógica, sistema web, tecnologías educativas', '2026-10-19 23:44:29.760518', NULL, NULL, 'Desarrollar e implementar el SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (294, '2026-07-01', 'Pregrado', 'El presente proyecto socio-tecnológico tiene por nombre sistema web para la gestión de ventas de Natural Nei F.P, que tuvo como objetivo general desarrollar un sistema web para la gestión de ventas de la firma personal Natural Nei F.P., con el fin de optimizar el procesamiento transaccional, mitigar la pérdida de registros comerciales y centralizar los flujos de información operativa. La problemática detectada se centró en la existencia de procesos manuales que generaban lentitud en la atención al cliente y cuellos de botella en el control del inventario de productos. Para el levantamiento de información, se aplicaron técnicas de observación directa y entrevistas semiestructuradas dirigidas al personal administrativo de la organización. El desarrollo del software se guio por la metodología XP ágil de ingeniería de software, articulando una arquitectura de base de datos relacional para el Backend y una interfaz de usuario fluida y responsiva para el Frontend. Como resultado principal, se logró el diseño e implantación de una plataforma informática robusta capaz de automatizar las órdenes de compra, procesar pagos digitales de forma segura y generar reportes financieros detallados en tiempo real. Se concluye que la integración de esta infraestructura tecnológica reduce significativamente los tiempos de respuesta comercial, eleva la confiabilidad en el manejo de datos transaccionales y dota a la empresa de una herramienta escalable que se alinea con las demandas socio-productivas y de conectividad del entorno actual', 1, 'Natural Nei Firma Personal', 'Automatización de Procesos, Base de Datos, Gestión de', '2026-10-19 23:44:29.853154', NULL, NULL, 'Desarrollar e implementar el SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (295, '2025-03-01', 'Pregrado', 'El presente proyecto se desarrollará en las instalaciones de la Comercializadora FrancoPlast C.A. con el objetivo de implementar un sitio web de esta para alcanzar una mayor visibilidad a nivel comercial-informativo. Será ejecutado bajo la metodología Scrum, siendo esta una metodología agil y flexible al momento del desarrollo del sitio web y la coordinación de los estudiantes; será llevada a cabo por varias etapas las cuales son: 1- La planificación Al comienzo del trabajo, se empezó la creación del Product Backlog, donde se dio prioridad a todas las funcionalidades, características y los requerimientos del sitio web. En nuestro caso, el Product Backlog inicial incluyó: Sección Funcionalidades, características y requerimientos Página principal o Inicio Información esencial de la comercializadora, como ubicación y número de contacto Catálogo', 1, 'Comercializadora Francoplast  C.A', '', '2026-10-19 23:44:29.93803', NULL, NULL, 'Implementar una página web en la Comercializadora FrancoPlast C.A.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (229, '2025-11-01', 'Pregrado', 'Implementación de un Sistema Integral de Gestión Administrativa para el taller Automotriz Inversiones “Dios es Amor 31” C.A, Municipio Valera, Estado Trujillo. El presente proyecto tiene como finalidad la implementación de un Sistema Integral de Gestión Administrativa para el Taller Automotriz “Dios es Amor 31” C.A, Municipio Valera, Estado Trujillo. Como tal, el objetivo de dicho proyecto es buscar solución a la problemática que presenta el taller automotriz inversiones “Dios es Amor 31” C.A, con respecto a la falta de un sistema administrativo, con la experiencia y conocimiento que poseen los autores que realizan este proyecto, y poniendo en práctica todo lo aprendido hasta ahora de lo que han visto en la carrera de informática, siempre cumpliendo los lineamientos establecidos por la ley y por el PNFI adjunto a la U.P.T.T “Mario Briceño Iragorry”-La Beatriz (de la cual los integrantes de este proyecto pertenecen)', 1, 'TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A', 'Sistema – internet - información -metodologías', '2026-10-19 22:37:13.125266', NULL, NULL, 'Desarrollar e implementar el SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A.', true, '[-0.03950482,0.08621026,0.04032304,-0.09355976,-0.06128964,-0.01695865,-0.04257434,0.10184249,-0.00635069,0.06172114,0.10236817,0.08555273,0.01739265,0.00974808,-0.05162894,-0.00240733,-0.01559894,0.09986131,-0.07796339,0.06139412,0.03646993,-0.10433263,-0.02032545,0.06961304,0.03905851,-0.08075578,-0.0506417,0.0342003,0.00156116,-0.00436251,-0.00238323,0.02544247,0.13840204,0.0349309,-0.00191856,-0.0153173,-0.02808491,-0.06585467,-0.05098033,-0.05112219,0.04589461,-0.0627594,0.01155948,-0.04754066,-0.04599788,-0.01034197,-0.02440849,-0.08284943,-0.05683556,0.05173612,-0.02456875,0.06495211,0.01960587,-0.02841348,0.01019094,0.08633014,-0.03739283,-0.04732433,0.06302444,-0.00652112,0.0944363,0.04728836,-0.03152741,-0.00639351,-0.0746583,-0.03909856,0.04139702,-0.05808016,-0.04051519,0.01000412,0.04021645,-0.07416935,-0.02155534,0.01457908,0.01440814,-0.07220434,-0.01736685,-0.02224584,0.03709493,-0.11121167,0.04444751,0.00069779,-0.03632572,0.08659793,-0.02042862,-0.00391385,-0.09976118,0.04582225,0.05869195,0.03255928,0.02484409,-0.14225283,-0.07041461,-0.01474308,0.03356593,-0.03434579,0.00630125,-0.06109801,-0.08616395,0.01216177,0.01974712,0.04755814,-0.00931274,0.10997827,-0.04678328,-0.00775318,0.01857758,0.03237733,-0.03958659,-0.03529542,-0.07541444,-0.02312561,0.08692623,-0.07286253,-0.07939917,0.03646377,0.01570553,-0.00869395,0.02326959,-0.04407242,0.02325246,0.00649281,-0.00246532,-0.02348432,0.08293974,-0.06339435,-0.03366866,3.56e-06,-0.04527316,0.01944833,-0.00803467,0.02672424,-0.00905883,0.00362477,-0.04349613,-0.04126273,-0.03297821,0.00784833,0.00803779,0.06348233,-0.04282264,-0.05154144,-0.07308231,0.01555851,0.05756585,0.02222458,0.00473649,-0.03801244,-0.0095112,-0.06365658,-0.0311158,-0.04228166,0.038467,-0.07237924,-0.02122609,0.04002135,-0.00979069,0.04985434,0.00421581,-0.03415221,0.04830219,-0.02985128,-0.02279201,-0.09059558,0.00234356,-0.05844851,-0.01318903,-0.0202939,-0.06963131,0.04605778,0.04350436,-0.08944975,-0.04891205,0.03039745,0.11777913,0.06559851,0.12164609,0.05258746,0.06241049,-0.02349887,-0.07100387,-0.03888792,0.03979037,0.00771122,-0.03203267,0.00755166,-0.01142766,0.04415487,-0.02454646,-0.01653756,-0.01900242,-0.01833989,0.04958124,0.02030681,0.00921128,-0.03321451,0.033833,0.06640493,-0.07763275,-0.08212367,-0.08945125,0.08670869,-0.0161892,0.02939443,0.0210272,0.00279726,0.03488198,-0.09860583,-0.04275138,-0.03879136,0.04479486,0.04953719,0.05642344,-0.00834358,-0.03787307,0.02102707,0.04235168,-0.03059108,-0.04265923,-0.09919891,0.00380826,0.0491479,0.03743146,1.136e-05,0.02069133,-0.03875398,-0.02293628,-0.03608547,-0.02157297,-0.00737864,-0.0159302,-0.03225498,-0.0340819,-0.0194047,-0.01194667,0.06206164,0.04045692,-0.00266419,0.0717866,0.04091775,0.04880474,-0.02987776,0.03001407,0.04039573,-0.01640697,0.11470344,0.04609475,-0.00646342,0.01130728,-0.0184967,0.00453383,0.13962284,-0.02120544,0.00025371,-0.04112721,-0.06047056,-0.04619997,0.05493165,0.03484086,-0.09745984,-0.00734339,0.02684909,-0.01404344,-0.12569597,0.04382484,-0.08441763,0.03960201,-0.04045417,-0.00336509,-0.03938292,-0.04432933,-0.00639671,0.05898333,-0.00205586,-0.09562954,-0.06552643,0.07576549,0.02933179,0.02885713,0.00458697,0.07765809,0.02260279,0.07347488,-0.01819321,0.07441511,0.01648456,0.0433623,-0.11955195,-0.00970902,0.11476723,-0.02844045,0.0450886,-0.08755027,0.00661662,0.07597152,0.03013988,0.00510595,0.00408194,-0.05557301,-0.0542799,0.01151621,0.02652768,0.07819443,-0.0685923,-0.03431331,0.02182978,0.01637331,-0.01098489,-0.17773645,0.01759582,-0.08215508,0.09124262,0.04326361,0.02980977,-0.04382121,0.04917323,0.02820261,0.00590747,-0.02664128,1.29e-06,-0.09034875,0.01651971,0.01860561,-0.08013682,-0.03787486,0.00262353,-0.02985523,0.00627848,0.01957714,-0.0222073,0.0047568,0.01018568,0.11897375,-0.01935496,-0.03349338,-0.06903479,0.011796,0.04747397,-0.06665511,-0.00659905,0.04297114,-0.00115091,0.10174313,-0.02084921,-0.00485085,-0.05487274,-0.09418372,-0.01385628,-0.03781698,0.0117525,0.01988163,0.04282871,0.08442264,0.03879071,-0.00058383,0.03181072,-0.00714403,-0.05090165,-0.06434625,-0.04965251,0.06832886,-0.06137394,-0.05719603,0.00076203,0.01917603,-0.03168996,0.04026574,-0.06954524,-0.03255028,0.03524953,0.03005853,0.01258953,-0.02233539,0.0542038,0.02895533,-0.05669929,-0.00153876,0.04594309,0.02452679,0.04202244,0.01635835,-0.02254626,0.01916511,-0.0370022]', 4, 0);
INSERT INTO public.detalles_proyectos VALUES (296, '2025-11-01', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”, y proponer mejoras mediante el diseño de una solución técnica. Se aplicó una investigación de campo, de carácter aplicativo y descriptivo, utilizando el método de análisis para caracterizar el entorno y ordenar los elementos involucrados. Se emplearon técnicas como la entrevista, el cuestionario y la observación directa. El proyecto se desarrolló con la metodología Cisco, la cual está constituida por seis fases de realización: Preparación, Planeación, Diseño, Implementación, Operación y Optimización. Los resultados evidenciaron la necesidad de mantenimiento en el laboratorio II y permitieron aplicar conocimientos adquiridos en redes.', 1, 'ESCUELA TÉCNICA “MADRE RAFOLS', 'ethernet, conexión, red, cables, laboratorio, mantenimiento', '2026-10-19 23:48:55.213995', NULL, NULL, 'Realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (297, '2025-10-01', 'Pregrado', 'El objetivo principal de este proyecto es mejorar y modernizar la infraestructura de la red de comunicación de CORPOELEC. Se utiliza la metodología PPDIOO de Cisco, que incluye seis fases del ciclo de vida de la red. La iniciativa comenzó con las fases de Preparación y Planificación, donde se elaboró un caso de negocio tras un diagnóstico que reveló problemas críticos, como falta de sistemas de refrigeración, obsolescencia de la red y necesidad de energía de respaldo por cortes frecuentes. Se utilizó un análisis (FODA, CAME) para definir requerimientos y el plan del proyecto. La fase de Diseño creó una nueva arquitectura de red que prioriza la resiliencia y la seguridad, integrando tecnologías avanzadas como protocolos eficientes y switches y routers de alta capacidad. En la fase de Implementación, se realizó la instalación física y configuración lógica de la solución, integrando nuevos dispositivos y sistemas de apoyo. También se llevó a cabo una capacitación integral al personal para asegurar el uso autónomo y mantenimiento. Finalmente, las fases de Operación y Optimización son continuas. La Operación maneja el mantenimiento diario, mientras que la Optimización aborda problemas y mejora el rendimiento a largo plazo, fomentando el desarrollo social y sostenible.', 1, 'Corporación Eléctrica Nacional C.A.(CORPOELEC). Sucursal: despacho', 'Optimización, Modernización, Red, Comunicación, CORPOELEC', '2026-10-19 23:50:36.672787', NULL, NULL, 'Optimización y
Modernización
de la red de
comunicación
de la empresa
nacional
CORPOELEC
sucursal:
despacho
suroccidente.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (298, '2025-11-01', 'Pregrado', 'Se llevó a cabo un proyecto titulado "Sistema de Control de Acceso al Laboratorio Informático de la E.T. Pedro García Leal", en el cual se analizaron y priorizaron las necesidades tecnológicas de la institución mediante un diagnóstico situacional, donde se procuró resolver el problema principal de acceso no autorizado al laboratorio informático, creando una solución viable con los recursos disponibles que se tenían. Se implementó el proyecto haciendo uso de la metodología RUP (Rational Unified Process), mediante el diagnóstico situacional y la entrevista realizada a la directora de la institución, se llegó a la conclusión de desarrollar un programa en Python con CustomTkinter como interfaz gráfica y el uso de SQLite3 como gestor de base de datos, permitiendo registros y la generación de reportes automatizados', 1, 'Institución: Escuela Técnica “Pedro García Leal”', 'Sistema, Laboratorio, Metodología, Python, Base de Datos', '2026-10-20 00:09:16.424521', NULL, NULL, 'Implementar un sistema de información para controlar el acceso al laboratorio informático de la E.T ''Pedro García Leal''.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (300, '2025-11-01', 'Pregrado', 'El presente proyecto que tiene por nombre, fue realizado en la C.C.C DR.JOSE GREGORIO HERNADEZ C.A. ubicada en el municipio Valera del Estado Trujillo. En primera instancia, se dio inicio con un diagnostico situacional para detectar los problemas actuales que presenta la comunidad en cuanto a la deficiencia en el área de la informática. Por otra parte, abordando la realización de un sistema de información el cual se encargaría en convertir los registros médicos de papel en archivos electrónicos. Además, el objetivo principal fue desarrollar una herramienta que facilite la documentación de los pacientes atendidos por la comunidad agilizando el proceso, así tanto el personal de historias médicas como la comunidad en general y pacientes se beneficien de tener una historia resguardada tanto en físico como en digital. De igual manera, guiados por las fases de la metodología ágil DSDM basada en el estudio, la interacción, diseño, el desarrollo y la implementación, como también una capacitación al personal que allí labora para dar aplicar los conocimientos del sistema para su debido uso. En consecuencia y, gracias a la realización de este proyecto, se ha logrado avances positivos el área y especialmente en el departamento de registro de historias médicas de la institución, ya que mejoro la rapidez del proceso y el resguardo de información importante con la automatización del registro', 1, 'El Centro Clínico de Cirugía Doctor José Gregorio Hernández C.A consisten principalmente en la atención de pacientes internos y se realizan bajo la su', 'Diagnostico, elaboración, información, registro, sistema, historias médicas, metodología, planteamiento, desarrollo, pruebas, implementación, proceso, metas alcanzadas', '2026-10-20 00:09:16.610421', NULL, NULL, 'Implementar un sistema de información seguro para la gestión de historias médicas, que permita mejorar la eficiencia en la atención al paciente, facilitar el acceso a la información clínica y garantizar la confidencialidad de los datos.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (301, '2025-11-01', 'Pregrado', 'El Proyecto Socio Tecnológico desarrollado en la Escuela Técnica “Pedro García Leal” tiene el propósito de implantar un Sistema de Información destinado a solucionar las diversas problemáticas encontradas en el área de informática, comenzando con el diagnóstico situacional, donde a través de una entrevista estructurada se nos puso al tanto de los diversos desafíos que atraviesa la institución. Mediante el desarrollo de una jerarquización e identificación de necesidades se seleccionó la alternativa más factible, con la ayuda de los diferentes proyectos en el marco referencial se tuvo la idea de usar la metodología ágil SCRUM, en el desarrollo de la propuesta, tomando en cuenta lo antes expresado, se hizo una lista de requisitos funcionales la cual se utilizó durante los diversos sprints realizados en el transcurso del proyecto, finalmente se ejecutó exitosamente la implantación del sistema', 1, 'ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL', 'programación, base de datos, sistema de información, SCRUM, informática, entrevista, sprint, implantación', '2026-10-20 00:09:16.704217', NULL, NULL, 'Brindar una educación técnica de la calidad de 1ero a 6to año.', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (302, '2026-07-01', 'Pregrado', 'Se detalla el diseño de un Sistema Informático para el minimarket Inversiones M.Q., ubicado en el sector Buenos Aires de Valera, estado Trujillo. El proyecto fue desarrollado por los estudiantes Esnaider González, Pedro Montilla e Israel Moreno para el Programa Nacional de Formación en Informática de la UPTT "Mario Briceño Iragorry". El comercio operaba originalmente bajo un esquema manual donde el registro de ventas y el control de mercancía se realizaban en cuadernos, lo que generaba lentitud en caja, pérdida de datos financieros y desorganización general. Mediante la adopción de la Metodología Scrum, se planificó y construyó una aplicación web robusta utilizando PHP, JavaScript, HTML, CSS y MySQL. El software implementa procesos de Automatización comercial e incluye módulos especializados para la Facturación ágil con conversión dinámica de divisas y una eficiente Gestión de Inventario con alertas de stock mínimo para evitar el desabastecimiento. La validación técnica del producto final arrojó un 100% de cumplimiento en la matriz de requerimientos del sistema, logrando reducir los tiempos de atención en caja a menos de 30 segundos, eliminando los errores humanos en los cálculos monetarios y optimizando con éxito la operatividad del negocio', 1, 'Inversiones M.Q', 'Automatización, Facturación, Gestión de Inventario, Metodología Scrum, Sistema Informático', '2026-10-20 00:09:16.782033', NULL, NULL, 'Desarrollar un sistema de gestión web de ventas hecho para la mejor facilidad y atención al cliente como también para el control de ventas e inventario, con el fin de digitalizar las ventas y productos, y alcanzar un mejor rendimiento de la empresa', true, NULL, 3, 0);
INSERT INTO public.detalles_proyectos VALUES (303, '2025-11-01', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”, y proponer mejoras mediante el diseño de una solución técnica. Se aplicó una investigación de campo, de carácter aplicativo y descriptivo, utilizando el método de análisis para caracterizar el entorno y ordenar los elementos involucrados. Se emplearon técnicas como la entrevista, el cuestionario y la observación directa. El proyecto se desarrolló con la metodología Cisco, la cual está constituida por seis fases de realización: Preparación, Planeación, Diseño, Implementación, Operación y Optimización. Los resultados evidenciaron la necesidad de mantenimiento en el laboratorio II y permitieron aplicar conocimientos adquiridos en redes', 1, 'ESCUELA TÉCNICA “MADRE RAFOLS', 'ethernet, conexión, red, cables, laboratorio, mantenimiento', '2026-10-20 00:09:56.340865', NULL, NULL, 'Realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”.', true, NULL, 2, 0);
INSERT INTO public.detalles_proyectos VALUES (304, '2025-11-01', 'Pregrado', 'El presente trabajo especial de grado tiene como objetivo principal la Reestructuración y Mantenimiento de la Red LAN en la Dirección General de Planificación y Presupuesto de la Gobernación del Estado Trujillo. El siguiente estudio se desarrolla en respuesta a la necesidad de mejorar el rendimiento, la seguridad y la estabilidad de la red local (LAN) de la mencionada comunidad, institución clave en la gestión organizacional y administrativa. A través de un diagnóstico técnico y la aplicación de estrategias de mantenimiento correctivo y preventivo, esta propuesta busca optimizar los recursos existentes y garantizar un entorno tecnológico confiable y eficiente para lograr este objetivo, este trabajo se realizó bajo el tipo de investigación práctica o aplicada, ya que dicha investigación busca aplicar los conocimientos adquiridos en la metodología para la reestructuración de redes de Cisco , un análisis exhaustivo de la red actual a nivel lógico y físico, identificando posibles problemas y deficiencias. Se utilizo la observación y la entrevista para la recolección de datos. Esto implica considerar aspectos como la aceptación por parte de los usuarios, la viabilidad técnica de implementar los cambios propuestos y el cumplimiento de las regulaciones legales pertinentes', 1, 'La   Dirección General de Planificación y Presupuesto adscrita a la Gobernación del Estado Trujillo', 'Red LAN, Cisco, Cable UTP, Topología', '2026-10-20 00:14:39.526868', NULL, NULL, 'Reestructurar y hacer Mantenimiento de la Red LAN en la Dirección General de Planificación y Presupuesto adscrita a la Gobernación del Estado Trujillo.', true, NULL, 2, 0);


--
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.dimensiones_operativas VALUES (7, 7, 'Sistemas de información web', 'Son primeramente sistemas de información que para su desarrollo se debe considerar la misma disciplina de construcción de sistemas de información no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van m s all  de ser un conjunto de p ginas Web.', true);
INSERT INTO public.dimensiones_operativas VALUES (8, 7, 'Sistemas de información colaborativos', 'Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribución de la información en una organización o entre organizaciones.', true);
INSERT INTO public.dimensiones_operativas VALUES (9, 7, 'Gestión tecnológica', 'Procesos relacionados con la implantación de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos módulos, adem s de comprobar el correcto funcionamiento de los componentes de un sistema de información que puede abarcar auditorías, t‚cnicas de control, evaluación de la calidad.', true);
INSERT INTO public.dimensiones_operativas VALUES (10, 8, 'Software educativo', 'Programas para el computador creados con la finalidad especáfica de ser utilizados como medio did ctico, es decir, para facilitar los procesos de ense¤anza y de aprendizaje. Combina conocimiento educacional, comunicacional e inform tico.', true);
INSERT INTO public.dimensiones_operativas VALUES (11, 8, 'Guáas de estudio web', 'Representan un material instruccional utilizados para cursos de educación a distancia y como complemento a la educación presencial, lo cual provee una estructura para un curso.', true);
INSERT INTO public.dimensiones_operativas VALUES (12, 8, 'Tutoriales', 'Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realización de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.', true);
INSERT INTO public.dimensiones_operativas VALUES (14, 8, 'Entornos interactivos de ense¤anza', 'Proyectos donde el profesor y los alumnos se encuentran en lugares fásicamente distintos. El proceso de ense¤anza-aprendizaje se lleva a cabo a trav‚s de Internet, en cualquier momento y en cualquier lugar.', true);
INSERT INTO public.dimensiones_operativas VALUES (15, 8, 'Sistemas e-learning', 'Programas que faciliten la creación, adopción y distribución de contenidos, asá como la adaptación del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de lámites horarios o geogr ficos.', true);
INSERT INTO public.dimensiones_operativas VALUES (16, 9, 'Aplicaciones cliente - servidor', 'Sistema distribuido entre múltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma m s adecuada.', true);
INSERT INTO public.dimensiones_operativas VALUES (17, 9, 'Servicios de integración para aplicaciones web', 'Medio para exponer y hacer disponible la funcionalidad de los sistemas de información mediante las tecnologáas est ndar Web, permitiendo reducción de la heterogeneidad por uso de tecnologáas est ndar.', true);
INSERT INTO public.dimensiones_operativas VALUES (18, 10, 'Simulación y herramientas de simulación', 'Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulación o test donde sea posible modelizar y probar el sistema que est  desarrollando. Reduce tiempo y chequea decisiones a priori.', true);
INSERT INTO public.dimensiones_operativas VALUES (19, 10, 'Modelos de transmisión de datos', 'Se discute la conceptualización integral de un sistema de transmisión desde un marco común a diferentes tecnologáas, tales como: sistemas de comunicación por cable, radio enlaces fijos, móviles y satelitales.', true);
INSERT INTO public.dimensiones_operativas VALUES (20, 9, 'Aplicaciones multiplataforma', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', true);
INSERT INTO public.dimensiones_operativas VALUES (21, 9, 'Aplicaciones web interactivas', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.', true);
INSERT INTO public.dimensiones_operativas VALUES (22, 9, 'Aplicaciones móviles y ubicuas', 'desarrollo de soluciones adaptadas a dispositivos móviles y contextos de movilidad, integrando sensores, geolocalización, notificaciones y conectividad. Se promueve la experiencia de usuario y el acceso remoto a servicios en tiempo real.', true);
INSERT INTO public.dimensiones_operativas VALUES (23, 9, 'Modelado y gestión de datos', 'diseño conceptual, lógico y físico de estructuras de datos que sustentan el funcionamiento de las aplicaciones, Incluye el uso de modelos entidad-relación, normalización, diseño de esquemas relacionales y no relacionales, así como la implementación en sistemas gestores de bases de datos. Esta dimensión garantiza la integridad, consistencia y eficiencia en el almacenamiento, recuperación y procesamiento de la información.', true);
INSERT INTO public.dimensiones_operativas VALUES (24, 9, 'Seguridad y auditoría de aplicaciones', 'incorporación de prácticas de desarrollo seguro, autenticación, autorización, cifrado y trazabilidad. Se abordan normativas como ISO/IEC 27001 y principios de privacidad por diseño, garantizando la integridad y confidencialidad de los sistemas.', true);
INSERT INTO public.dimensiones_operativas VALUES (5, 7, 'Sistemas de información tradicionales', 'Est  constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e información y programas y aplicaciones; que interactúan entre si dentro de una organización con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.', true);
INSERT INTO public.dimensiones_operativas VALUES (6, 7, 'Sistemas de información con propiedades geogr ficas', 'Son sistemas que permiten evaluar propiedades geogr ficas de un entorno, generando información referente a una entidad geogr fica desplegando im genes e información en un hipermapa.', true);
INSERT INTO public.dimensiones_operativas VALUES (13, 8, 'Juegos did cticos', 'El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploración y expresión, un instrumento para la organización y aplicación de habilidades y, un factor de socialización e integración.', true);


--
-- Data for Name: editoriales; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.editoriales VALUES (1, 'IEEE');
INSERT INTO public.editoriales VALUES (3, 'Springer');
INSERT INTO public.editoriales VALUES (4, 'Elsevier');
INSERT INTO public.editoriales VALUES (5, 'UPTTMBI Ediciones');
INSERT INTO public.editoriales VALUES (6, 'UNESCO');
INSERT INTO public.editoriales VALUES (7, 'SciELO Venezuela');
INSERT INTO public.editoriales VALUES (8, 'DYNA');


--
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.etiquetas VALUES (1, 'Inteligencia Artificial', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (3, 'Educación', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (4, 'Redes Neuronales', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (5, 'Desarrollo Web', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (8, 'Teoría Matemática', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (9, 'Modelo Económico', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (10, 'Construcción', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (17, 'Machine Learning', '#0ea5e9');


--
-- Data for Name: historico_versiones_pst; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.investigaciones_ofertadas VALUES (1, 7, 'asdasdasdasd', 'asdasdasdasdasdasd', 'asdasdasdasdasdasd', 9, NULL, 3, 'Abierta', '2026-09-09 23:15:22.674054', NULL);
INSERT INTO public.investigaciones_ofertadas VALUES (2, 7, 'Requerimiento: Sistema de Inventario', 'isuuuuuuu ordeña a carmencita

(Nivel Requerido: Trayecto I)', 'Dar respuesta y solución tecnológica a los requerimientos de Megacell', 7, 9, 3, 'Abierta', '2026-09-17 00:01:42.595736', 16);
INSERT INTO public.investigaciones_ofertadas VALUES (4, 7, 'asdasdasdasdsadadasdsa', 'asdasdasdasd', 'asdasdadasdas', 10, NULL, 3, 'En Desarrollo', '2026-09-29 00:05:26.495721', NULL);


--
-- Data for Name: investigadores; Type: TABLE DATA; Schema: public; Owner: miki
--



--
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.lineas_investigacion VALUES (8, 'EDUMATICA', 1, 'Aplicar las Tecnologías de la Información y Comunicación (TIC) para apoyar el proceso de aprendizaje, y asá contribuir al mejoramiento de la educación en todos sus niveles.', true);
INSERT INTO public.lineas_investigacion VALUES (10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisión de datos, como tambi‚n la detección de fallas dentro de una red.', true);
INSERT INTO public.lineas_investigacion VALUES (9, 'DESARROLLO DE APLICACIONES', 1, 'Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.', true);
INSERT INTO public.lineas_investigacion VALUES (7, 'SISTEMAS DE INFORMACION Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de información dentro del  ámbito social. Aplicando soluciones efectivas para el uso adecuado y óptimo de los sistemas de información.', true);


--
-- Data for Name: matriz_rbac; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.matriz_rbac VALUES (1, 'Articulos', '{"crear": true, "editar": true, "auditar": true, "eliminar": true}');
INSERT INTO public.matriz_rbac VALUES (1, 'Cursos', '{"crear": true, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (1, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (2, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (3, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (4, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (5, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (6, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');


--
-- Data for Name: niveles_academicos; Type: TABLE DATA; Schema: public; Owner: miki
--

INSERT INTO public.niveles_academicos VALUES (1, 'Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', true, 1, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (2, 'Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', false, 2, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:53:32.699637');
INSERT INTO public.niveles_academicos VALUES (3, 'Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', false, 3, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (4, 'Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', false, 4, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');


--
-- Data for Name: notificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.notificaciones VALUES (1, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 16:14:24', '2026-03-23 20:40:28');
INSERT INTO public.notificaciones VALUES (2, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 4. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 20:10:36', '2026-03-23 20:40:28');
INSERT INTO public.notificaciones VALUES (3, 5, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.', true, '2026-03-23 21:42:42', '2026-03-23 21:42:42');
INSERT INTO public.notificaciones VALUES (4, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: Suspendida por completo.', true, '2026-04-02 02:13:17', '2026-04-02 02:13:17');
INSERT INTO public.notificaciones VALUES (5, 4, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.', true, '2026-04-02 02:13:22', '2026-04-02 02:13:22');
INSERT INTO public.notificaciones VALUES (6, 5, 'Actualización Moderada de Cuenta', 'Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.', true, '2026-04-04 16:13:51', '2026-04-04 16:13:51');


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.password_resets VALUES (1, 'orlando5711666@gmail.com', '98ae6a718e630e4dffb2c144bbb36b095f55358c7299904798709c244db29659', '2026-09-17 00:03:43.94943', false, '2026-09-16 23:48:43.94943');


--
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.postulaciones_estudiantes VALUES (1, 1, 7, 'asdasdasd', 'Rechazado', '2026-09-09 23:16:44.679115', '2026-09-19 02:07:44.686527', NULL);
INSERT INTO public.postulaciones_estudiantes VALUES (2, 2, 7, '', 'Rechazado', '2026-09-18 23:33:33.698004', '2026-09-19 02:08:00.249562', '[]');


--
-- Data for Name: preferencias_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.preferencias_usuario VALUES (1, 'ocean', true);
INSERT INTO public.preferencias_usuario VALUES (3, 'sunset', true);
INSERT INTO public.preferencias_usuario VALUES (4, 'ocean', true);
INSERT INTO public.preferencias_usuario VALUES (6, 'sunset', true);


--
-- Data for Name: privilegios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.privilegios VALUES (1, 0);
INSERT INTO public.privilegios VALUES (2, 1);
INSERT INTO public.privilegios VALUES (3, 2);
INSERT INTO public.privilegios VALUES (4, 3);
INSERT INTO public.privilegios VALUES (5, 4);
INSERT INTO public.privilegios VALUES (6, 5);
INSERT INTO public.privilegios VALUES (10, 6);


--
-- Data for Name: propuestas_empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.propuestas_empresa VALUES (14, 'Punto Yali', '27889926', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'vbnchngfhgjfhgj', 'aceptada', '2026-09-13 22:30:07.723734', 'Trayecto I', 'CIIDI-2026-29E32', NULL);
INSERT INTO public.propuestas_empresa VALUES (3, 'Punto Yali', '123', 'Yohan Estrada', '0416-6777467', 'yohan@gmail.com', 'facturacion', 'IAIAIAIA', 'rechazada', '2026-09-11 22:13:08.195366', NULL, '', 'CIIDI-2026-F421F');
INSERT INTO public.propuestas_empresa VALUES (5, 'Megacell', 'J-12045552-', 'Miki waza', '04121609721', 'lando1609721@gmail.com', 'inventario', 'Necesito un sistema que haga inventario', 'rechazada', '2026-08-25 17:16:56.513754', 'Trayecto II', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (6, 'Megacell', 'J20789378', 'Miki waza', '0414-7573234', 'lando1609721@gmail.com', 'inventario', 'necesito un sistema para mi alacen', 'aceptada', '2026-09-09 21:47:51.4141', 'Trayecto I', 'CIIDI-2026-AF997', NULL);
INSERT INTO public.propuestas_empresa VALUES (7, 'Megacellll', 'J20789378', 'Miki waza', '04121609721', 'lando16097211@gmail.com', 'redes', '11111111111111', 'aceptada', '2026-09-10 23:12:34.730038', 'Trayecto I', 'CIIDI-2026-4050F', NULL);
INSERT INTO public.propuestas_empresa VALUES (8, 'Punto Yali', '27889926', 'Miki waza', '04121609721', 'orlando5711666@gmail.com', 'inventario', 'necesito ayuda con respecto a un sistema que me controle el inventario de los productos que vendo aca en el local', 'aceptada', '2026-09-13 18:02:49.521927', 'Trayecto I', 'CIIDI-2026-84BAE', NULL);
INSERT INTO public.propuestas_empresa VALUES (9, 'gregoria', '123456789', 'Miki waza', '04121609721', 'orlando5711666@gmail.com', 'facturacion', 'hola', 'aceptada', '2026-09-13 18:42:50.07038', 'Trayecto II', 'CIIDI-2026-4DDC5', NULL);
INSERT INTO public.propuestas_empresa VALUES (11, 'zambrano cell', '27889926', 'chailon', '04121609721', 'lando1609721@gmail.com', 'inventario', 'ailberth deje de robarse las pantallas pa su primo chamo', 'aceptada', '2026-09-13 18:55:13.333522', 'Trayecto III', 'CIIDI-2026-E5EAE', NULL);
INSERT INTO public.propuestas_empresa VALUES (10, 'carmencita coito', '12345567889', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'datos', 'yisus porfa ordeña a carmencita sisisisi', 'aceptada', '2026-09-13 18:53:14.455601', 'Trayecto IV', 'CIIDI-2026-D55D0', NULL);
INSERT INTO public.propuestas_empresa VALUES (4, 'Punto G De Yali', 'G-30676767-0', 'Iojan', '4147755888', 'Puntogdeyali@gmail.com', 'redes', 'Quiero un sistema de clasificacion de los pelos de mi anito riko mmm sisisii', 'aceptada', '2026-07-10 14:02:25.411413', 'Trayecto I', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (1, 'Megacell', 'J-12045552-', 'ELLL PRIMOOOO', '04121609721', 'lando1609721@gmail.com', 'facturacion', 'NECESITAMOS UN SISTEMA PARA CLASIFICAR FEMBOY, FURROS Y KPOPERAS ', 'aceptada', '2026-07-10 00:05:36.297999', 'Trayecto I', NULL, NULL);
INSERT INTO public.propuestas_empresa VALUES (12, 'Megacell', 'J20789378', 'chailon', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'necesito un sistema para poder registrar los telefonos que estamo haciendo', 'aceptada', '2026-09-13 19:46:05.970023', 'Trayecto I', 'CIIDI-2026-F5A16', NULL);
INSERT INTO public.propuestas_empresa VALUES (13, 'Punto Yali', '20789378', 'Miki boss', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'caafagsdfddsfdsfdsf', 'aceptada', '2026-09-13 22:28:56.260924', 'Trayecto I', 'CIIDI-2026-EC9AC', NULL);
INSERT INTO public.propuestas_empresa VALUES (16, 'Megacell', '27889926', 'Miki waza', '04121609721', 'lando1609721@gmail.com', 'Sistema de Inventario', 'isuuuuuuu ordeña a carmencita', 'aceptada', '2026-09-16 23:57:18.499486', 'Trayecto I', 'CIIDI-2026-CAA12', NULL);
INSERT INTO public.propuestas_empresa VALUES (15, 'zambrano cell', '20789378', 'chailon', '04121609721', 'lando1609721@gmail.com', 'Mejora de sistema', 'ghfjhmfhjfgdjhfd', 'aceptada', '2026-09-13 22:30:21.539904', 'Trayecto I', 'CIIDI-2026-C8F0D', NULL);
INSERT INTO public.propuestas_empresa VALUES (2, '123', '123', '123', '123', '123@gmail.com', 'inventario', '123', 'aceptada', '2026-09-02 21:42:33.312089', 'Trayecto IV', NULL, NULL);


--
-- Data for Name: proyecto_carreras_vinculadas; Type: TABLE DATA; Schema: public; Owner: miki
--



--
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.proyecto_tutores VALUES (224, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (224, 41, 2);
INSERT INTO public.proyecto_tutores VALUES (224, 47, 4);
INSERT INTO public.proyecto_tutores VALUES (225, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (225, 41, 2);
INSERT INTO public.proyecto_tutores VALUES (225, 42, 4);
INSERT INTO public.proyecto_tutores VALUES (226, 28, 3);
INSERT INTO public.proyecto_tutores VALUES (226, 48, 2);
INSERT INTO public.proyecto_tutores VALUES (226, 39, 4);
INSERT INTO public.proyecto_tutores VALUES (227, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (228, 43, 3);
INSERT INTO public.proyecto_tutores VALUES (228, 17, 2);
INSERT INTO public.proyecto_tutores VALUES (228, 44, 4);
INSERT INTO public.proyecto_tutores VALUES (229, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (229, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (229, 46, 4);
INSERT INTO public.proyecto_tutores VALUES (230, 49, 3);
INSERT INTO public.proyecto_tutores VALUES (230, 50, 2);
INSERT INTO public.proyecto_tutores VALUES (230, 51, 4);
INSERT INTO public.proyecto_tutores VALUES (231, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (231, 53, 2);
INSERT INTO public.proyecto_tutores VALUES (231, 54, 4);
INSERT INTO public.proyecto_tutores VALUES (232, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (232, 18, 2);
INSERT INTO public.proyecto_tutores VALUES (232, 19, 4);
INSERT INTO public.proyecto_tutores VALUES (233, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (233, 55, 4);
INSERT INTO public.proyecto_tutores VALUES (234, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (234, 41, 2);
INSERT INTO public.proyecto_tutores VALUES (234, 56, 4);
INSERT INTO public.proyecto_tutores VALUES (235, 45, 2);
INSERT INTO public.proyecto_tutores VALUES (236, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (236, 57, 2);
INSERT INTO public.proyecto_tutores VALUES (236, 58, 4);
INSERT INTO public.proyecto_tutores VALUES (237, 59, 3);
INSERT INTO public.proyecto_tutores VALUES (237, 17, 2);
INSERT INTO public.proyecto_tutores VALUES (237, 60, 4);
INSERT INTO public.proyecto_tutores VALUES (238, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (238, 57, 2);
INSERT INTO public.proyecto_tutores VALUES (238, 61, 4);
INSERT INTO public.proyecto_tutores VALUES (239, 45, 2);
INSERT INTO public.proyecto_tutores VALUES (239, 62, 4);
INSERT INTO public.proyecto_tutores VALUES (240, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (240, 22, 2);
INSERT INTO public.proyecto_tutores VALUES (240, 63, 4);
INSERT INTO public.proyecto_tutores VALUES (241, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (241, 37, 2);
INSERT INTO public.proyecto_tutores VALUES (241, 64, 4);
INSERT INTO public.proyecto_tutores VALUES (242, 65, 3);
INSERT INTO public.proyecto_tutores VALUES (242, 66, 2);
INSERT INTO public.proyecto_tutores VALUES (242, 67, 4);
INSERT INTO public.proyecto_tutores VALUES (243, 49, 3);
INSERT INTO public.proyecto_tutores VALUES (243, 68, 2);
INSERT INTO public.proyecto_tutores VALUES (243, 69, 4);
INSERT INTO public.proyecto_tutores VALUES (244, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (245, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (245, 41, 2);
INSERT INTO public.proyecto_tutores VALUES (245, 70, 4);
INSERT INTO public.proyecto_tutores VALUES (246, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (246, 71, 4);
INSERT INTO public.proyecto_tutores VALUES (247, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (247, 35, 2);
INSERT INTO public.proyecto_tutores VALUES (247, 72, 4);
INSERT INTO public.proyecto_tutores VALUES (248, 45, 2);
INSERT INTO public.proyecto_tutores VALUES (248, 73, 4);
INSERT INTO public.proyecto_tutores VALUES (249, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (249, 74, 2);
INSERT INTO public.proyecto_tutores VALUES (249, 75, 4);
INSERT INTO public.proyecto_tutores VALUES (250, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (250, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (250, 76, 4);
INSERT INTO public.proyecto_tutores VALUES (251, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (251, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (251, 77, 4);
INSERT INTO public.proyecto_tutores VALUES (252, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (252, 78, 2);
INSERT INTO public.proyecto_tutores VALUES (252, 54, 4);
INSERT INTO public.proyecto_tutores VALUES (253, 65, 3);
INSERT INTO public.proyecto_tutores VALUES (253, 66, 2);
INSERT INTO public.proyecto_tutores VALUES (253, 67, 4);
INSERT INTO public.proyecto_tutores VALUES (254, 45, 2);
INSERT INTO public.proyecto_tutores VALUES (254, 79, 4);
INSERT INTO public.proyecto_tutores VALUES (255, 18, 3);
INSERT INTO public.proyecto_tutores VALUES (255, 45, 2);
INSERT INTO public.proyecto_tutores VALUES (255, 80, 4);
INSERT INTO public.proyecto_tutores VALUES (256, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (256, 81, 4);
INSERT INTO public.proyecto_tutores VALUES (257, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (257, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (257, 82, 4);
INSERT INTO public.proyecto_tutores VALUES (258, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (258, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (258, 83, 4);
INSERT INTO public.proyecto_tutores VALUES (259, 84, 3);
INSERT INTO public.proyecto_tutores VALUES (259, 85, 2);
INSERT INTO public.proyecto_tutores VALUES (259, 86, 4);
INSERT INTO public.proyecto_tutores VALUES (260, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (260, 22, 2);
INSERT INTO public.proyecto_tutores VALUES (260, 87, 4);
INSERT INTO public.proyecto_tutores VALUES (261, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (261, 41, 2);
INSERT INTO public.proyecto_tutores VALUES (261, 88, 4);
INSERT INTO public.proyecto_tutores VALUES (262, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (262, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (262, 89, 4);
INSERT INTO public.proyecto_tutores VALUES (263, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (264, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (264, 90, 2);
INSERT INTO public.proyecto_tutores VALUES (264, 91, 4);
INSERT INTO public.proyecto_tutores VALUES (265, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (265, 92, 2);
INSERT INTO public.proyecto_tutores VALUES (265, 93, 4);
INSERT INTO public.proyecto_tutores VALUES (266, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (266, 94, 2);
INSERT INTO public.proyecto_tutores VALUES (266, 95, 4);
INSERT INTO public.proyecto_tutores VALUES (267, 18, 3);
INSERT INTO public.proyecto_tutores VALUES (267, 90, 2);
INSERT INTO public.proyecto_tutores VALUES (267, 96, 4);
INSERT INTO public.proyecto_tutores VALUES (268, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (268, 90, 2);
INSERT INTO public.proyecto_tutores VALUES (268, 97, 4);
INSERT INTO public.proyecto_tutores VALUES (269, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (269, 92, 2);
INSERT INTO public.proyecto_tutores VALUES (269, 98, 4);
INSERT INTO public.proyecto_tutores VALUES (270, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (270, 99, 2);
INSERT INTO public.proyecto_tutores VALUES (270, 100, 4);
INSERT INTO public.proyecto_tutores VALUES (271, 101, 3);
INSERT INTO public.proyecto_tutores VALUES (271, 90, 2);
INSERT INTO public.proyecto_tutores VALUES (271, 102, 4);
INSERT INTO public.proyecto_tutores VALUES (272, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (272, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (272, 103, 4);
INSERT INTO public.proyecto_tutores VALUES (273, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (273, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (273, 104, 4);
INSERT INTO public.proyecto_tutores VALUES (274, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (274, 105, 2);
INSERT INTO public.proyecto_tutores VALUES (274, 106, 4);
INSERT INTO public.proyecto_tutores VALUES (275, 18, 3);
INSERT INTO public.proyecto_tutores VALUES (275, 90, 2);
INSERT INTO public.proyecto_tutores VALUES (275, 107, 4);
INSERT INTO public.proyecto_tutores VALUES (276, 18, 3);
INSERT INTO public.proyecto_tutores VALUES (276, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (276, 108, 4);
INSERT INTO public.proyecto_tutores VALUES (278, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (278, 106, 2);
INSERT INTO public.proyecto_tutores VALUES (278, 58, 4);
INSERT INTO public.proyecto_tutores VALUES (279, 110, 3);
INSERT INTO public.proyecto_tutores VALUES (279, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (279, 111, 4);
INSERT INTO public.proyecto_tutores VALUES (280, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (280, 29, 2);
INSERT INTO public.proyecto_tutores VALUES (280, 91, 4);
INSERT INTO public.proyecto_tutores VALUES (281, 112, 3);
INSERT INTO public.proyecto_tutores VALUES (281, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (281, 107, 4);
INSERT INTO public.proyecto_tutores VALUES (277, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (277, 109, 4);
INSERT INTO public.proyecto_tutores VALUES (282, 113, 3);
INSERT INTO public.proyecto_tutores VALUES (282, 114, 2);
INSERT INTO public.proyecto_tutores VALUES (282, 115, 4);
INSERT INTO public.proyecto_tutores VALUES (283, 112, 3);
INSERT INTO public.proyecto_tutores VALUES (283, 116, 2);
INSERT INTO public.proyecto_tutores VALUES (283, 117, 4);
INSERT INTO public.proyecto_tutores VALUES (284, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (284, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (284, 118, 4);
INSERT INTO public.proyecto_tutores VALUES (285, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (285, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (285, 119, 4);
INSERT INTO public.proyecto_tutores VALUES (286, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (286, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (286, 120, 4);
INSERT INTO public.proyecto_tutores VALUES (287, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (287, 37, 2);
INSERT INTO public.proyecto_tutores VALUES (287, 121, 4);
INSERT INTO public.proyecto_tutores VALUES (288, 122, 3);
INSERT INTO public.proyecto_tutores VALUES (288, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (288, 123, 4);
INSERT INTO public.proyecto_tutores VALUES (289, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (289, 37, 2);
INSERT INTO public.proyecto_tutores VALUES (289, 124, 4);
INSERT INTO public.proyecto_tutores VALUES (290, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (290, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (290, 125, 4);
INSERT INTO public.proyecto_tutores VALUES (291, 126, 3);
INSERT INTO public.proyecto_tutores VALUES (291, 106, 2);
INSERT INTO public.proyecto_tutores VALUES (291, 127, 4);
INSERT INTO public.proyecto_tutores VALUES (292, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (292, 37, 2);
INSERT INTO public.proyecto_tutores VALUES (292, 128, 4);
INSERT INTO public.proyecto_tutores VALUES (293, 129, 3);
INSERT INTO public.proyecto_tutores VALUES (293, 130, 2);
INSERT INTO public.proyecto_tutores VALUES (294, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (294, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (294, 131, 4);
INSERT INTO public.proyecto_tutores VALUES (295, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (295, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (295, 132, 4);
INSERT INTO public.proyecto_tutores VALUES (296, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (296, 92, 2);
INSERT INTO public.proyecto_tutores VALUES (296, 38, 4);
INSERT INTO public.proyecto_tutores VALUES (297, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (297, 92, 2);
INSERT INTO public.proyecto_tutores VALUES (297, 133, 4);
INSERT INTO public.proyecto_tutores VALUES (298, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (298, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (298, 134, 4);
INSERT INTO public.proyecto_tutores VALUES (299, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (299, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (299, 135, 4);
INSERT INTO public.proyecto_tutores VALUES (300, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (300, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (300, 136, 4);
INSERT INTO public.proyecto_tutores VALUES (301, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (301, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (301, 134, 4);
INSERT INTO public.proyecto_tutores VALUES (302, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (302, 66, 2);
INSERT INTO public.proyecto_tutores VALUES (302, 137, 4);
INSERT INTO public.proyecto_tutores VALUES (303, 52, 3);
INSERT INTO public.proyecto_tutores VALUES (303, 92, 2);
INSERT INTO public.proyecto_tutores VALUES (303, 38, 4);
INSERT INTO public.proyecto_tutores VALUES (304, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (304, 39, 2);
INSERT INTO public.proyecto_tutores VALUES (304, 138, 4);
INSERT INTO public.proyecto_tutores VALUES (305, 45, 3);
INSERT INTO public.proyecto_tutores VALUES (305, 22, 2);
INSERT INTO public.proyecto_tutores VALUES (305, 139, 4);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (224, 20);
INSERT INTO public.recurso_autores VALUES (224, 21);
INSERT INTO public.recurso_autores VALUES (224, 22);
INSERT INTO public.recurso_autores VALUES (224, 23);
INSERT INTO public.recurso_autores VALUES (224, 24);
INSERT INTO public.recurso_autores VALUES (225, 1);
INSERT INTO public.recurso_autores VALUES (225, 2);
INSERT INTO public.recurso_autores VALUES (225, 3);
INSERT INTO public.recurso_autores VALUES (225, 4);
INSERT INTO public.recurso_autores VALUES (225, 5);
INSERT INTO public.recurso_autores VALUES (226, 16);
INSERT INTO public.recurso_autores VALUES (226, 17);
INSERT INTO public.recurso_autores VALUES (226, 18);
INSERT INTO public.recurso_autores VALUES (226, 19);
INSERT INTO public.recurso_autores VALUES (227, 9);
INSERT INTO public.recurso_autores VALUES (227, 10);
INSERT INTO public.recurso_autores VALUES (227, 11);
INSERT INTO public.recurso_autores VALUES (227, 12);
INSERT INTO public.recurso_autores VALUES (227, 13);
INSERT INTO public.recurso_autores VALUES (228, 6);
INSERT INTO public.recurso_autores VALUES (228, 7);
INSERT INTO public.recurso_autores VALUES (228, 8);
INSERT INTO public.recurso_autores VALUES (229, 14);
INSERT INTO public.recurso_autores VALUES (229, 15);
INSERT INTO public.recurso_autores VALUES (230, 25);
INSERT INTO public.recurso_autores VALUES (230, 26);
INSERT INTO public.recurso_autores VALUES (230, 27);
INSERT INTO public.recurso_autores VALUES (230, 28);
INSERT INTO public.recurso_autores VALUES (231, 29);
INSERT INTO public.recurso_autores VALUES (231, 30);
INSERT INTO public.recurso_autores VALUES (231, 31);
INSERT INTO public.recurso_autores VALUES (231, 32);
INSERT INTO public.recurso_autores VALUES (232, 33);
INSERT INTO public.recurso_autores VALUES (232, 34);
INSERT INTO public.recurso_autores VALUES (232, 35);
INSERT INTO public.recurso_autores VALUES (232, 36);
INSERT INTO public.recurso_autores VALUES (232, 37);
INSERT INTO public.recurso_autores VALUES (233, 38);
INSERT INTO public.recurso_autores VALUES (233, 39);
INSERT INTO public.recurso_autores VALUES (233, 40);
INSERT INTO public.recurso_autores VALUES (233, 41);
INSERT INTO public.recurso_autores VALUES (234, 42);
INSERT INTO public.recurso_autores VALUES (234, 43);
INSERT INTO public.recurso_autores VALUES (234, 44);
INSERT INTO public.recurso_autores VALUES (235, 45);
INSERT INTO public.recurso_autores VALUES (235, 46);
INSERT INTO public.recurso_autores VALUES (235, 47);
INSERT INTO public.recurso_autores VALUES (235, 48);
INSERT INTO public.recurso_autores VALUES (235, 49);
INSERT INTO public.recurso_autores VALUES (236, 50);
INSERT INTO public.recurso_autores VALUES (236, 51);
INSERT INTO public.recurso_autores VALUES (236, 52);
INSERT INTO public.recurso_autores VALUES (236, 53);
INSERT INTO public.recurso_autores VALUES (237, 54);
INSERT INTO public.recurso_autores VALUES (238, 55);
INSERT INTO public.recurso_autores VALUES (238, 56);
INSERT INTO public.recurso_autores VALUES (238, 57);
INSERT INTO public.recurso_autores VALUES (238, 58);
INSERT INTO public.recurso_autores VALUES (238, 59);
INSERT INTO public.recurso_autores VALUES (239, 60);
INSERT INTO public.recurso_autores VALUES (239, 61);
INSERT INTO public.recurso_autores VALUES (239, 62);
INSERT INTO public.recurso_autores VALUES (239, 63);
INSERT INTO public.recurso_autores VALUES (239, 64);
INSERT INTO public.recurso_autores VALUES (240, 65);
INSERT INTO public.recurso_autores VALUES (240, 66);
INSERT INTO public.recurso_autores VALUES (240, 67);
INSERT INTO public.recurso_autores VALUES (240, 68);
INSERT INTO public.recurso_autores VALUES (240, 69);
INSERT INTO public.recurso_autores VALUES (241, 70);
INSERT INTO public.recurso_autores VALUES (241, 71);
INSERT INTO public.recurso_autores VALUES (241, 72);
INSERT INTO public.recurso_autores VALUES (241, 73);
INSERT INTO public.recurso_autores VALUES (241, 74);
INSERT INTO public.recurso_autores VALUES (242, 75);
INSERT INTO public.recurso_autores VALUES (242, 76);
INSERT INTO public.recurso_autores VALUES (242, 77);
INSERT INTO public.recurso_autores VALUES (242, 78);
INSERT INTO public.recurso_autores VALUES (242, 79);
INSERT INTO public.recurso_autores VALUES (243, 80);
INSERT INTO public.recurso_autores VALUES (244, 81);
INSERT INTO public.recurso_autores VALUES (244, 82);
INSERT INTO public.recurso_autores VALUES (244, 14);
INSERT INTO public.recurso_autores VALUES (244, 83);
INSERT INTO public.recurso_autores VALUES (245, 84);
INSERT INTO public.recurso_autores VALUES (245, 85);
INSERT INTO public.recurso_autores VALUES (245, 86);
INSERT INTO public.recurso_autores VALUES (245, 87);
INSERT INTO public.recurso_autores VALUES (246, 88);
INSERT INTO public.recurso_autores VALUES (246, 89);
INSERT INTO public.recurso_autores VALUES (246, 90);
INSERT INTO public.recurso_autores VALUES (247, 91);
INSERT INTO public.recurso_autores VALUES (247, 92);
INSERT INTO public.recurso_autores VALUES (248, 93);
INSERT INTO public.recurso_autores VALUES (248, 94);
INSERT INTO public.recurso_autores VALUES (249, 95);
INSERT INTO public.recurso_autores VALUES (249, 96);
INSERT INTO public.recurso_autores VALUES (249, 97);
INSERT INTO public.recurso_autores VALUES (249, 98);
INSERT INTO public.recurso_autores VALUES (249, 99);
INSERT INTO public.recurso_autores VALUES (250, 100);
INSERT INTO public.recurso_autores VALUES (250, 101);
INSERT INTO public.recurso_autores VALUES (251, 102);
INSERT INTO public.recurso_autores VALUES (251, 103);
INSERT INTO public.recurso_autores VALUES (251, 104);
INSERT INTO public.recurso_autores VALUES (252, 29);
INSERT INTO public.recurso_autores VALUES (252, 105);
INSERT INTO public.recurso_autores VALUES (252, 31);
INSERT INTO public.recurso_autores VALUES (252, 30);
INSERT INTO public.recurso_autores VALUES (252, 32);
INSERT INTO public.recurso_autores VALUES (253, 75);
INSERT INTO public.recurso_autores VALUES (253, 76);
INSERT INTO public.recurso_autores VALUES (253, 77);
INSERT INTO public.recurso_autores VALUES (253, 78);
INSERT INTO public.recurso_autores VALUES (253, 79);
INSERT INTO public.recurso_autores VALUES (254, 106);
INSERT INTO public.recurso_autores VALUES (254, 107);
INSERT INTO public.recurso_autores VALUES (254, 108);
INSERT INTO public.recurso_autores VALUES (254, 109);
INSERT INTO public.recurso_autores VALUES (255, 110);
INSERT INTO public.recurso_autores VALUES (255, 111);
INSERT INTO public.recurso_autores VALUES (255, 112);
INSERT INTO public.recurso_autores VALUES (256, 11);
INSERT INTO public.recurso_autores VALUES (256, 9);
INSERT INTO public.recurso_autores VALUES (256, 10);
INSERT INTO public.recurso_autores VALUES (257, 113);
INSERT INTO public.recurso_autores VALUES (257, 114);
INSERT INTO public.recurso_autores VALUES (257, 115);
INSERT INTO public.recurso_autores VALUES (257, 116);
INSERT INTO public.recurso_autores VALUES (257, 117);
INSERT INTO public.recurso_autores VALUES (258, 118);
INSERT INTO public.recurso_autores VALUES (258, 119);
INSERT INTO public.recurso_autores VALUES (259, 120);
INSERT INTO public.recurso_autores VALUES (259, 121);
INSERT INTO public.recurso_autores VALUES (260, 122);
INSERT INTO public.recurso_autores VALUES (260, 123);
INSERT INTO public.recurso_autores VALUES (260, 124);
INSERT INTO public.recurso_autores VALUES (260, 125);
INSERT INTO public.recurso_autores VALUES (260, 126);
INSERT INTO public.recurso_autores VALUES (261, 127);
INSERT INTO public.recurso_autores VALUES (261, 128);
INSERT INTO public.recurso_autores VALUES (261, 129);
INSERT INTO public.recurso_autores VALUES (262, 130);
INSERT INTO public.recurso_autores VALUES (262, 131);
INSERT INTO public.recurso_autores VALUES (262, 132);
INSERT INTO public.recurso_autores VALUES (262, 133);
INSERT INTO public.recurso_autores VALUES (262, 134);
INSERT INTO public.recurso_autores VALUES (263, 81);
INSERT INTO public.recurso_autores VALUES (263, 135);
INSERT INTO public.recurso_autores VALUES (263, 82);
INSERT INTO public.recurso_autores VALUES (263, 136);
INSERT INTO public.recurso_autores VALUES (264, 137);
INSERT INTO public.recurso_autores VALUES (264, 138);
INSERT INTO public.recurso_autores VALUES (264, 139);
INSERT INTO public.recurso_autores VALUES (265, 140);
INSERT INTO public.recurso_autores VALUES (265, 141);
INSERT INTO public.recurso_autores VALUES (265, 142);
INSERT INTO public.recurso_autores VALUES (265, 143);
INSERT INTO public.recurso_autores VALUES (266, 144);
INSERT INTO public.recurso_autores VALUES (266, 145);
INSERT INTO public.recurso_autores VALUES (266, 146);
INSERT INTO public.recurso_autores VALUES (267, 147);
INSERT INTO public.recurso_autores VALUES (267, 148);
INSERT INTO public.recurso_autores VALUES (267, 149);
INSERT INTO public.recurso_autores VALUES (267, 150);
INSERT INTO public.recurso_autores VALUES (268, 151);
INSERT INTO public.recurso_autores VALUES (268, 152);
INSERT INTO public.recurso_autores VALUES (268, 153);
INSERT INTO public.recurso_autores VALUES (269, 154);
INSERT INTO public.recurso_autores VALUES (269, 155);
INSERT INTO public.recurso_autores VALUES (269, 156);
INSERT INTO public.recurso_autores VALUES (269, 157);
INSERT INTO public.recurso_autores VALUES (270, 158);
INSERT INTO public.recurso_autores VALUES (270, 159);
INSERT INTO public.recurso_autores VALUES (271, 160);
INSERT INTO public.recurso_autores VALUES (271, 161);
INSERT INTO public.recurso_autores VALUES (271, 162);
INSERT INTO public.recurso_autores VALUES (271, 163);
INSERT INTO public.recurso_autores VALUES (272, 164);
INSERT INTO public.recurso_autores VALUES (272, 165);
INSERT INTO public.recurso_autores VALUES (272, 166);
INSERT INTO public.recurso_autores VALUES (272, 167);
INSERT INTO public.recurso_autores VALUES (273, 168);
INSERT INTO public.recurso_autores VALUES (273, 169);
INSERT INTO public.recurso_autores VALUES (273, 170);
INSERT INTO public.recurso_autores VALUES (274, 171);
INSERT INTO public.recurso_autores VALUES (275, 172);
INSERT INTO public.recurso_autores VALUES (275, 173);
INSERT INTO public.recurso_autores VALUES (275, 174);
INSERT INTO public.recurso_autores VALUES (275, 175);
INSERT INTO public.recurso_autores VALUES (276, 176);
INSERT INTO public.recurso_autores VALUES (276, 177);
INSERT INTO public.recurso_autores VALUES (276, 178);
INSERT INTO public.recurso_autores VALUES (276, 179);
INSERT INTO public.recurso_autores VALUES (278, 184);
INSERT INTO public.recurso_autores VALUES (278, 185);
INSERT INTO public.recurso_autores VALUES (278, 186);
INSERT INTO public.recurso_autores VALUES (279, 187);
INSERT INTO public.recurso_autores VALUES (279, 188);
INSERT INTO public.recurso_autores VALUES (279, 189);
INSERT INTO public.recurso_autores VALUES (279, 190);
INSERT INTO public.recurso_autores VALUES (280, 191);
INSERT INTO public.recurso_autores VALUES (280, 192);
INSERT INTO public.recurso_autores VALUES (280, 193);
INSERT INTO public.recurso_autores VALUES (281, 194);
INSERT INTO public.recurso_autores VALUES (281, 195);
INSERT INTO public.recurso_autores VALUES (281, 196);
INSERT INTO public.recurso_autores VALUES (277, 180);
INSERT INTO public.recurso_autores VALUES (277, 181);
INSERT INTO public.recurso_autores VALUES (277, 182);
INSERT INTO public.recurso_autores VALUES (277, 183);
INSERT INTO public.recurso_autores VALUES (282, 197);
INSERT INTO public.recurso_autores VALUES (283, 198);
INSERT INTO public.recurso_autores VALUES (283, 199);
INSERT INTO public.recurso_autores VALUES (283, 200);
INSERT INTO public.recurso_autores VALUES (283, 201);
INSERT INTO public.recurso_autores VALUES (283, 202);
INSERT INTO public.recurso_autores VALUES (284, 203);
INSERT INTO public.recurso_autores VALUES (284, 204);
INSERT INTO public.recurso_autores VALUES (284, 205);
INSERT INTO public.recurso_autores VALUES (285, 206);
INSERT INTO public.recurso_autores VALUES (285, 207);
INSERT INTO public.recurso_autores VALUES (285, 208);
INSERT INTO public.recurso_autores VALUES (285, 209);
INSERT INTO public.recurso_autores VALUES (286, 210);
INSERT INTO public.recurso_autores VALUES (286, 211);
INSERT INTO public.recurso_autores VALUES (286, 212);
INSERT INTO public.recurso_autores VALUES (287, 213);
INSERT INTO public.recurso_autores VALUES (287, 214);
INSERT INTO public.recurso_autores VALUES (287, 215);
INSERT INTO public.recurso_autores VALUES (287, 216);
INSERT INTO public.recurso_autores VALUES (287, 217);
INSERT INTO public.recurso_autores VALUES (288, 218);
INSERT INTO public.recurso_autores VALUES (288, 219);
INSERT INTO public.recurso_autores VALUES (288, 220);
INSERT INTO public.recurso_autores VALUES (288, 221);
INSERT INTO public.recurso_autores VALUES (289, 222);
INSERT INTO public.recurso_autores VALUES (289, 223);
INSERT INTO public.recurso_autores VALUES (289, 224);
INSERT INTO public.recurso_autores VALUES (289, 225);
INSERT INTO public.recurso_autores VALUES (290, 226);
INSERT INTO public.recurso_autores VALUES (290, 227);
INSERT INTO public.recurso_autores VALUES (290, 228);
INSERT INTO public.recurso_autores VALUES (290, 229);
INSERT INTO public.recurso_autores VALUES (290, 230);
INSERT INTO public.recurso_autores VALUES (291, 231);
INSERT INTO public.recurso_autores VALUES (291, 232);
INSERT INTO public.recurso_autores VALUES (291, 233);
INSERT INTO public.recurso_autores VALUES (291, 234);
INSERT INTO public.recurso_autores VALUES (292, 235);
INSERT INTO public.recurso_autores VALUES (292, 236);
INSERT INTO public.recurso_autores VALUES (292, 237);
INSERT INTO public.recurso_autores VALUES (292, 238);
INSERT INTO public.recurso_autores VALUES (293, 239);
INSERT INTO public.recurso_autores VALUES (293, 240);
INSERT INTO public.recurso_autores VALUES (293, 241);
INSERT INTO public.recurso_autores VALUES (293, 242);
INSERT INTO public.recurso_autores VALUES (294, 243);
INSERT INTO public.recurso_autores VALUES (294, 244);
INSERT INTO public.recurso_autores VALUES (294, 245);
INSERT INTO public.recurso_autores VALUES (295, 246);
INSERT INTO public.recurso_autores VALUES (295, 247);
INSERT INTO public.recurso_autores VALUES (295, 248);
INSERT INTO public.recurso_autores VALUES (295, 249);
INSERT INTO public.recurso_autores VALUES (296, 250);
INSERT INTO public.recurso_autores VALUES (296, 251);
INSERT INTO public.recurso_autores VALUES (296, 252);
INSERT INTO public.recurso_autores VALUES (296, 253);
INSERT INTO public.recurso_autores VALUES (297, 254);
INSERT INTO public.recurso_autores VALUES (297, 255);
INSERT INTO public.recurso_autores VALUES (297, 256);
INSERT INTO public.recurso_autores VALUES (298, 257);
INSERT INTO public.recurso_autores VALUES (298, 258);
INSERT INTO public.recurso_autores VALUES (298, 259);
INSERT INTO public.recurso_autores VALUES (298, 260);
INSERT INTO public.recurso_autores VALUES (299, 261);
INSERT INTO public.recurso_autores VALUES (299, 262);
INSERT INTO public.recurso_autores VALUES (299, 263);
INSERT INTO public.recurso_autores VALUES (299, 264);
INSERT INTO public.recurso_autores VALUES (300, 265);
INSERT INTO public.recurso_autores VALUES (300, 266);
INSERT INTO public.recurso_autores VALUES (300, 267);
INSERT INTO public.recurso_autores VALUES (300, 268);
INSERT INTO public.recurso_autores VALUES (301, 269);
INSERT INTO public.recurso_autores VALUES (301, 270);
INSERT INTO public.recurso_autores VALUES (301, 271);
INSERT INTO public.recurso_autores VALUES (301, 272);
INSERT INTO public.recurso_autores VALUES (301, 273);
INSERT INTO public.recurso_autores VALUES (302, 274);
INSERT INTO public.recurso_autores VALUES (302, 275);
INSERT INTO public.recurso_autores VALUES (302, 276);
INSERT INTO public.recurso_autores VALUES (303, 250);
INSERT INTO public.recurso_autores VALUES (303, 251);
INSERT INTO public.recurso_autores VALUES (303, 252);
INSERT INTO public.recurso_autores VALUES (303, 253);
INSERT INTO public.recurso_autores VALUES (304, 277);
INSERT INTO public.recurso_autores VALUES (304, 278);
INSERT INTO public.recurso_autores VALUES (305, 279);
INSERT INTO public.recurso_autores VALUES (305, 280);


--
-- Data for Name: recurso_categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_categorias VALUES (122, 6);
INSERT INTO public.recurso_categorias VALUES (122, 5);
INSERT INTO public.recurso_categorias VALUES (122, 7);
INSERT INTO public.recurso_categorias VALUES (122, 1);
INSERT INTO public.recurso_categorias VALUES (121, 18);
INSERT INTO public.recurso_categorias VALUES (121, 5);
INSERT INTO public.recurso_categorias VALUES (121, 7);
INSERT INTO public.recurso_categorias VALUES (120, 3);
INSERT INTO public.recurso_categorias VALUES (120, 13);
INSERT INTO public.recurso_categorias VALUES (120, 7);
INSERT INTO public.recurso_categorias VALUES (119, 7);
INSERT INTO public.recurso_categorias VALUES (119, 1);
INSERT INTO public.recurso_categorias VALUES (118, 7);
INSERT INTO public.recurso_categorias VALUES (118, 4);
INSERT INTO public.recurso_categorias VALUES (143, 3);
INSERT INTO public.recurso_categorias VALUES (143, 13);
INSERT INTO public.recurso_categorias VALUES (144, 3);
INSERT INTO public.recurso_categorias VALUES (144, 13);
INSERT INTO public.recurso_categorias VALUES (144, 1);
INSERT INTO public.recurso_categorias VALUES (146, 3);
INSERT INTO public.recurso_categorias VALUES (150, 14);
INSERT INTO public.recurso_categorias VALUES (151, 3);
INSERT INTO public.recurso_categorias VALUES (156, 3);


--
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_clasificaciones VALUES (224, 8, 14);
INSERT INTO public.recurso_clasificaciones VALUES (225, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (226, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (227, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (228, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (229, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (230, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (231, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (232, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (233, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (234, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (235, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (236, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (237, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (238, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (239, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (240, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (241, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (242, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (243, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (244, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (245, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (246, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (247, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (248, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (249, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (250, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (251, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (252, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (253, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (254, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (255, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (256, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (257, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (258, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (259, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (260, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (261, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (262, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (263, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (264, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (265, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (266, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (267, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (268, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (269, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (270, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (271, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (272, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (273, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (274, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (275, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (276, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (278, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (279, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (280, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (281, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (277, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (282, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (283, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (284, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (285, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (286, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (287, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (288, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (289, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (290, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (291, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (292, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (293, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (294, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (295, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (296, 10, 19);
INSERT INTO public.recurso_clasificaciones VALUES (297, 10, 19);
INSERT INTO public.recurso_clasificaciones VALUES (298, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (299, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (300, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (301, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (302, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (303, 10, 19);
INSERT INTO public.recurso_clasificaciones VALUES (304, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (305, 10, 19);


--
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_etiquetas VALUES (143, 10);
INSERT INTO public.recurso_etiquetas VALUES (143, 1);
INSERT INTO public.recurso_etiquetas VALUES (122, 1);
INSERT INTO public.recurso_etiquetas VALUES (122, 4);
INSERT INTO public.recurso_etiquetas VALUES (121, 10);
INSERT INTO public.recurso_etiquetas VALUES (120, 8);
INSERT INTO public.recurso_etiquetas VALUES (119, 3);
INSERT INTO public.recurso_etiquetas VALUES (119, 1);
INSERT INTO public.recurso_etiquetas VALUES (118, 1);
INSERT INTO public.recurso_etiquetas VALUES (144, 3);
INSERT INTO public.recurso_etiquetas VALUES (144, 1);
INSERT INTO public.recurso_etiquetas VALUES (146, 10);
INSERT INTO public.recurso_etiquetas VALUES (150, 5);
INSERT INTO public.recurso_etiquetas VALUES (151, 3);
INSERT INTO public.recurso_etiquetas VALUES (156, 1);


--
-- Data for Name: recurso_investigadores; Type: TABLE DATA; Schema: public; Owner: miki
--



--
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recursos VALUES (3, 'Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos', 2, 2026, NULL);
INSERT INTO public.recursos VALUES (4, 'Impacto del Cambio Climático en Trujillo - Parte 8', 2, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (5, 'Simulación de Cargas Estáticas en Puentes - Parte 7', 2, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (9, 'Bioinformática y Análisis de ADN - Parte 5', 3, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (10, 'Inteligencia Artificial en Diagnóstico Médico - Parte 6', 2, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (11, 'Robótica Educativa para Escuelas - Parte 5', 3, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (12, 'Software Libre para Bibliotecas - Parte 1', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (13, 'E-Learning para Zonas Desfavorecidas - Parte 1', 3, 2018, 'dummy.pdf');
INSERT INTO public.recursos VALUES (14, 'Telecomunicaciones de Fibra Óptica Rural - Parte 1', 2, 2022, 'dummy.pdf');
INSERT INTO public.recursos VALUES (16, 'Criptografía Cuántica Post-RSA - Parte 7', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (20, 'Inteligencia Artificial en Diagnóstico Médico - Parte 1', 3, 2020, 'dummy.pdf');
INSERT INTO public.recursos VALUES (122, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');
INSERT INTO public.recursos VALUES (121, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (120, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (119, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (118, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');
INSERT INTO public.recursos VALUES (143, 'Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124890/98825');
INSERT INTO public.recursos VALUES (146, 'Modelamiento de confort adaptativo para un trapiche panelero', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/112625/91645');
INSERT INTO public.recursos VALUES (144, 'Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124428/98826');
INSERT INTO public.recursos VALUES (150, 'e', 3, 2026, 'https://www.youtube.com/');
INSERT INTO public.recursos VALUES (151, 'e', 3, 2026, 'https://www.wikipedia.org/');
INSERT INTO public.recursos VALUES (156, 'e', 3, 2026, 'https://www.wikipedia.org/');
INSERT INTO public.recursos VALUES (224, 'FORTALECIMIENTO DEL APRENDIZAJE DE CASTELLANO Y LITERATURA MEDIANTE UNA PLATAFORMA EDUCATIVA PARA LOS ESTUDIANTES DE PRIMER AÑO DEL LICEO "DR. REGULO BURELLI RIVAS', 1, 2026, 'storage/documentos/pst/pst_fortalecimiento_del_aprendizaj_1792463439_408.docx');
INSERT INTO public.recursos VALUES (225, 'Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A', 1, 2025, 'storage/documentos/pst/pst_sistema_integrado_para_la_gest_1792463528_989.docx');
INSERT INTO public.recursos VALUES (226, 'GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)', 1, 2025, 'storage/documentos/pst/pst_gestor_administrativo_de_certi_1792463818_742.docx');
INSERT INTO public.recursos VALUES (227, 'SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792463825_895.docx');
INSERT INTO public.recursos VALUES (228, 'PROYECTO T4 Sistema Consejo Municipal', 1, 2025, 'storage/documentos/pst/pst_proyecto_t4_sistema_consejo_mu_1792463833_258.pdf');
INSERT INTO public.recursos VALUES (229, 'SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_a_1792463833_282.docx');
INSERT INTO public.recursos VALUES (230, 'SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO', 1, 2026, 'storage/documentos/pst/pst_sistema_de_informaci__n_geogr__1792464793_692.docx');
INSERT INTO public.recursos VALUES (231, 'IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO', 1, 2025, 'storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792464802_764.docx');
INSERT INTO public.recursos VALUES (232, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 'storage/documentos/pst/pst_optimizaci__n_del_sistema_de_i_1792464804_268.docx');
INSERT INTO public.recursos VALUES (233, 'Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_para_la_gesti__1792464806_425.docx');
INSERT INTO public.recursos VALUES (234, 'Implementación de una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo', 1, 2026, 'storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792464809_346.docx');
INSERT INTO public.recursos VALUES (235, 'SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792464936_959.docx');
INSERT INTO public.recursos VALUES (236, 'Sistema de Información Automatizado para la Gestión Académica del Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera', 1, 2026, 'storage/documentos/pst/pst_sistema_de_informaci__n_automa_1792465008_899.docx');
INSERT INTO public.recursos VALUES (237, 'SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792465052_313.pdf');
INSERT INTO public.recursos VALUES (238, 'APLICACIÓN WEB PARA OPTIMIZAR LA GESTIÓN DE PRODUCTOS Y SERVICIOS EN SUPER KAUCHOS GILSA, C.A', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_para_optimizar_1792465101_719.docx');
INSERT INTO public.recursos VALUES (239, 'SISTEMA INTEGRAL DE ADMINISTRACION DE CASOS (SIAC) EN LA PREFECTURA DEL MUNICIPIO ESCUQUE', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_administra_1792465188_372.docx');
INSERT INTO public.recursos VALUES (240, 'SISTEMA INTEGRAL DE CONTROL DE PAGO DE ARANCELES DEL IUNE - VALERA', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_control_de_1792465317_949.docx');
INSERT INTO public.recursos VALUES (241, 'SISTEMA PARA LA DIGITALIZACION Y GESTION DE SERVICIOS DE EQUIPOS EN TECNOSERVICE DE MIGUEL MORALES F.P', 1, 2026, 'storage/documentos/pst/pst_sistema_para_la_digitalizacion_1792465317_297.docx');
INSERT INTO public.recursos VALUES (242, 'APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL"', 1, 2026, 'storage/documentos/pst/pst_aplicaci__n_web_para_el_proces_1792465317_468.docx');
INSERT INTO public.recursos VALUES (243, 'SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS)', 1, 2025, 'storage/documentos/pst/pst_sistema_de_inventario_para_el__1792465317_371.docx');
INSERT INTO public.recursos VALUES (244, 'APLICACIÓN WEB PARA LA COOPERATIVA “LA ONCE CON LA ONCE ”, MUNICIPIO VALERA, ESTADO TRUJILLO', 1, 2024, 'storage/documentos/pst/pst_aplicaci__n_web_para_la_cooper_1792465317_831.pdf');
INSERT INTO public.recursos VALUES (245, 'SISTEMA DE GESTIÓN Y CONTROL DE DATOS EDUCATIVOS DE LA UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS', 1, 2026, 'storage/documentos/pst/pst_sistema_de_gesti__n_y_control__1792465317_952.docx');
INSERT INTO public.recursos VALUES (246, 'SISTEMA DE GESTIÓN ESCOLAR PARA LA ESCUELA “VICENTE DE LA TORRE', 1, 2026, 'storage/documentos/pst/pst_sistema_de_gesti__n_escolar_pa_1792465317_592.pdf');
INSERT INTO public.recursos VALUES (247, 'SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_acad___1792465317_908.docx');
INSERT INTO public.recursos VALUES (248, 'SISTEMA DE INFORMACIÓN PARA EL REGISTRO Y GESTIÓN LABORAL DEL INDET(SIRGL-INDET)', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_e_1792465317_937.doc');
INSERT INTO public.recursos VALUES (249, 'Sistema de gestión de inventario Web para INME C.A', 1, 2025, 'storage/documentos/pst/pst_sistema_de_gesti__n_de_inventa_1792465444_199.docx');
INSERT INTO public.recursos VALUES (250, 'SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_zooc___1792465484_982.docx');
INSERT INTO public.recursos VALUES (251, 'IMPLEMENTACIÓN DE PORTAL WEB PARA LA EMPRESA PUBLIART', 1, 2026, 'storage/documentos/pst/pst_implementaci__n_de_portal_web__1792465511_534.docx');
INSERT INTO public.recursos VALUES (252, 'SISTEMA DE GESTIÓN DIGITAL PARA EL INVENTARIO, TRAZABILIDAD Y MOVIMIENTO DE BIENES EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A', 1, 2026, 'storage/documentos/pst/pst_sistema_de_gesti__n_digital_pa_1792465610_953.docx');
INSERT INTO public.recursos VALUES (253, 'APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL', 1, 2026, 'storage/documentos/pst/pst_aplicaci__n_web_para_el_proces_1792465617_587.docx');
INSERT INTO public.recursos VALUES (254, 'Sistema Web de gestión integral del Talento humano para el SAPNNAET', 1, 2026, 'storage/documentos/pst/pst_sistema_web_de_gesti__n_integr_1792465617_932.pdf');
INSERT INTO public.recursos VALUES (255, 'SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMNISTRATIVA DEL PERSONAL ADSCRITO A LA VALFOR S.A', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792465712_837.doc');
INSERT INTO public.recursos VALUES (256, 'Implementación de una aplicación web para la Unidad Educativa Hna. María Sorrosal', 1, 2025, 'storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792465814_784.docx');
INSERT INTO public.recursos VALUES (257, 'SISTEMA WEB PARA LA GESTIÓN DE PROYECTOS DE LOS CONSEJOS COMUNALES EN EL CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA', 1, 2025, 'storage/documentos/pst/pst_sistema_web_para_la_gesti__n_d_1792465925_304.docx');
INSERT INTO public.recursos VALUES (258, 'Sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_e_1792466045_640.docx');
INSERT INTO public.recursos VALUES (259, 'Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo"', 1, 2026, 'storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792466421_468.docx');
INSERT INTO public.recursos VALUES (260, 'Sistema web para la farmacia INSUMEDIC C.A', 1, 2026, 'storage/documentos/pst/pst_sistema_web_para_la_farmacia_i_1792466509_133.docx');
INSERT INTO public.recursos VALUES (261, 'DESARROLLO DE APLICACIÓN WEB PARA EL LABORATORIO CLÍNICO GENÉSIS C.A., VALERA, ESTADO TRUJILLO', 1, 2026, 'storage/documentos/pst/pst_desarrollo_de_aplicaci__n_web__1792466560_824.doc');
INSERT INTO public.recursos VALUES (262, 'SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN"', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informaci__n_integr_1792466600_140.docx');
INSERT INTO public.recursos VALUES (263, 'SISTEMA WEB PARA LA GESTION ADMINISTRATIVA DE LA PANTERA PC STORE', 1, 2026, 'storage/documentos/pst/pst_sistema_web_para_la_gestion_ad_1792466664_177.docx');
INSERT INTO public.recursos VALUES (264, 'MANTENIMIENTO PREVENTIVO Y CORRECTIVO A LAS COMPUTADORAS DEL CBIT EN EL COMPLEJO EDUCATIVO ‘HILARIO PIZANI ANSELMI’', 1, 2026, 'storage/documentos/pst/pst_mantenimiento_preventivo_y_cor_1792466698_878.docx');
INSERT INTO public.recursos VALUES (265, 'SOPORTE TECNICO A EQUIPOS Y USUARIOS EN LA ALCALDIA MUNICIPAL RAFAEL RANGEL', 1, 2026, 'storage/documentos/pst/pst_soporte_tecnico_a_equipos_y_us_1792466698_240.docx');
INSERT INTO public.recursos VALUES (266, 'SOPORTE TÉCNICO A EQUIPOS Y USUARIOS PARA EL INFOCENTRO GRAN BASE DE MISIONES POR LA PAZ HUGO CHAVEZ FRIAS', 1, 2025, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_y_u_1792466698_292.pdf');
INSERT INTO public.recursos VALUES (267, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y A USUARIOS EN DUILIO DE JESUS VALERO ROMERO PANADERIA, PASTELERIA Y LUNCHERIA “LA ESTRELLA DE VALERA”. F. P', 1, 2025, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466698_639.pdf');
INSERT INTO public.recursos VALUES (268, 'PABLO VILORIA" - LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A LOS USUARIOS DE EL CIBER CAFÉ PONCHO S.A', 1, 2026, 'storage/documentos/pst/pst_pablo_viloria____la_beatriz__s_1792466698_931.pdf');
INSERT INTO public.recursos VALUES (269, 'NUES “DR. PABLO VILORIA” – LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A USUARIOS DE LA UPTTMBI NUES LA BEATRIZ EN EL LABORATORIO 2 DE INFORMÁTICA', 1, 2026, 'storage/documentos/pst/pst_nues____dr__pablo_viloria______1792466699_462.docx');
INSERT INTO public.recursos VALUES (270, 'SOPORTE TECNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CORPOELEC', 1, 2026, 'storage/documentos/pst/pst_soporte_tecnico_a_equipos_de_c_1792466699_830.docx');
INSERT INTO public.recursos VALUES (271, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA NUEVA ELOISA FONSECA', 1, 2026, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466699_969.docx');
INSERT INTO public.recursos VALUES (272, 'Soporte Técnico a Equipos de Computación y usuarios en la Empresa “IP SISTEMAS', 1, 2025, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466699_714.docx');
INSERT INTO public.recursos VALUES (273, 'SOPORTE TECNICO A EQUIPOS TECNOLOGICOS Y USUARIOS DEL DESPACHO DE CARGA SUROCCIDENTE', 1, 2026, 'storage/documentos/pst/pst_soporte_tecnico_a_equipos_tecn_1792466699_954.docx');
INSERT INTO public.recursos VALUES (274, 'SOPORTE TÉCNICO A EQUIPOS Y USUARIOS EN LA CLINICA UGA C.A', 1, 2026, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_y_u_1792466699_621.docx');
INSERT INTO public.recursos VALUES (275, 'SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DE LA “ESCUELA TÉCNICA COMERCIAL PEDRO GARCÍA LEAL” DEL MUNICIPIO', 1, 2026, 'storage/documentos/pst/pst_soporte_t__cnico_a_usuarios_y__1792466699_227.pdf');
INSERT INTO public.recursos VALUES (276, 'SOPORTE TÉCNICO A USUARIOS Y EQUIPOS EN EL MINISTERIO DEL PODER POPULAR PARA EL TRANSPORTE, SEDE VALERA - TRUJILLO', 1, 2026, 'storage/documentos/pst/pst_soporte_t__cnico_a_usuarios_y__1792466699_832.pdf');
INSERT INTO public.recursos VALUES (278, 'SOPORTE TÉCNICO A LAS COMPUTADORAS Y CAPACITACIÓN A LOS USUARIOS DEL CBIT DE LA ESCUELA DE EMPRENDIMIENTO VALERA', 1, 2025, 'storage/documentos/pst/pst_soporte_t__cnico_a_las_computa_1792466699_339.pdf');
INSERT INTO public.recursos VALUES (279, 'SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO"', 1, 2026, 'storage/documentos/pst/pst_sistema_did__ctico_e_interacti_1792466793_970.docx');
INSERT INTO public.recursos VALUES (280, 'SOFTWARE EDUCATIVO PARA LA ENSEÑANZA DE MATEMATICAS DE PRIMER AÑO EN EL LICEO “HILARIO PIZANI ANSELMI', 1, 2026, 'storage/documentos/pst/pst_software_educativo_para_la_ens_1792467225_223.docx');
INSERT INTO public.recursos VALUES (281, 'SOFTWARE EDUCATIVO PARA MEJORAR LA ENSEÑANZA DE PROGRAMACIÓN Y DISEÑO DE SOFTWARE Y REDES EN LA E.T. "PEDRO GARCÍA LEAL"', 1, 2025, 'storage/documentos/pst/pst_software_educativo_para_mejora_1792467375_320.docx');
INSERT INTO public.recursos VALUES (277, 'Soporte Técnico de Equipos y Capacitación a Usuarios del Laboratorio 1 de PNFI de la Universidad Politécnica Territorial ¨Mario Briceño Iragorry¨', 1, 2026, 'storage/documentos/pst/pst_soporte_t__cnico_de_equipos_y__1792466699_390.docx');
INSERT INTO public.recursos VALUES (282, 'APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_acad__mica_par_1792467868_602.docx');
INSERT INTO public.recursos VALUES (283, 'APLICACIÓN WEB INFORMATIVA PARA EL FORTALECIMIENTO TECNOLÓGICO DEL PARQUE DE RECREACIÓN BOTAICO “LA BEATRIZ', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_informativa_pa_1792467868_987.pdf');
INSERT INTO public.recursos VALUES (284, 'APLICACIÓN WEB PARA EL COMPLEJO “EDUCATIVO PEDRO PABLO PAREDES', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_para_el_comple_1792467869_791.docx');
INSERT INTO public.recursos VALUES (285, 'APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A', 1, 2026, 'storage/documentos/pst/pst_aplicaci__n_web_para_la_gesti__1792467869_493.pdf');
INSERT INTO public.recursos VALUES (286, 'APLICACION WEB PARA LA UNIDAD EDUCATIVA “EDUARDO BLANCO', 1, 2025, 'storage/documentos/pst/pst_aplicacion_web_para_la_unidad__1792467869_393.docx');
INSERT INTO public.recursos VALUES (287, 'Página Web en el Grupo Educativo “Eloísa Fonseca', 1, 2025, 'storage/documentos/pst/pst_p__gina_web_en_el_grupo_educat_1792467869_197.docx');
INSERT INTO public.recursos VALUES (288, 'Página Web Institucional para la Unidad Educativa Fé y Alegría “Gonzalo Senior', 1, 2025, 'storage/documentos/pst/pst_p__gina_web_institucional_para_1792467869_682.docx');
INSERT INTO public.recursos VALUES (289, 'PÁGINA WEB INSTITUCIONAL PARA EL COLEGIO LOS CEDROS', 1, 2025, 'storage/documentos/pst/pst_p__gina_web_institucional_para_1792467869_791.docx');
INSERT INTO public.recursos VALUES (290, 'página web municipal con sección al infocentro', 1, 2025, 'storage/documentos/pst/pst_p__gina_web_municipal_con_secc_1792467869_869.docx');
INSERT INTO public.recursos VALUES (291, 'PÁGINA WEB PARA EL CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR', 1, 2025, 'storage/documentos/pst/pst_p__gina_web_para_el_centro_de__1792467869_467.docx');
INSERT INTO public.recursos VALUES (292, 'SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS', 1, 2026, 'storage/documentos/pst/pst_sistema_web_integral_para_la_e_1792467869_561.docx');
INSERT INTO public.recursos VALUES (293, 'SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR', 1, 2026, 'storage/documentos/pst/pst_sistema_web_para_la_gestion_de_1792467869_432.docx');
INSERT INTO public.recursos VALUES (294, 'SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P', 1, 2026, 'storage/documentos/pst/pst_sistema_web_para_la_getion_de__1792467869_148.pdf');
INSERT INTO public.recursos VALUES (295, 'Sitio Web en la Comercializadora Francoplast C.A', 1, 2025, 'storage/documentos/pst/pst_sitio_web_en_la_comercializado_1792467869_802.docx');
INSERT INTO public.recursos VALUES (296, 'MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS"', 1, 2025, 'storage/documentos/pst/pst_mantenimiento_de_la_red_local__1792468135_366.docx');
INSERT INTO public.recursos VALUES (297, 'Optimización y modernización de la red de comunicación de CORPOELEC Sucursal: Despacho de Carga Suroccidente', 1, 2025, 'storage/documentos/pst/pst_optimizaci__n_y_modernizaci__n_1792468236_689.pdf');
INSERT INTO public.recursos VALUES (298, 'Sistema de control de acceso al laboratorio informático de la E.T "Pedro García Leal', 1, 2025, 'storage/documentos/pst/pst_sistema_de_control_de_acceso_a_1792469356_655.docx');
INSERT INTO public.recursos VALUES (299, 'SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO', 1, 2026, 'storage/documentos/pst/pst_sistema_de_gestion_web_para_la_1792469356_888.pdf');
INSERT INTO public.recursos VALUES (300, 'SISTEMA DE INFORMACION PARA LA OPTIMIZACION DEL ACCESO Y CONTROL DE HISTORIAS MÉDICAS EN EL C.C.C. DR. JOSE GREGORIO HERNANDEZ C.A', 1, 2025, 'storage/documentos/pst/pst_sistema_de_informacion_para_la_1792469356_848.docx');
INSERT INTO public.recursos VALUES (301, 'SISTEMA PARA CONTROL Y SEGUIMIENTO DE ASISTENCIAS DEL PROFESORADO EN LA ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL', 1, 2025, 'storage/documentos/pst/pst_sistema_para_control_y_seguimi_1792469356_585.docx');
INSERT INTO public.recursos VALUES (302, 'SISTEMA WEB DE GESTIÓN DE VENTAS PARA INVERSIONES M.Q. DE MIRLA QUINTERO', 1, 2026, 'storage/documentos/pst/pst_sistema_web_de_gesti__n_de_ven_1792469356_550.docx');
INSERT INTO public.recursos VALUES (303, 'MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS', 1, 2025, 'storage/documentos/pst/pst_mantenimiento_de_la_red_local__1792469396_397.docx');
INSERT INTO public.recursos VALUES (304, 'REESTRUCTURACIÓN Y MANTENIMIENTO DE LA RED LAN EN LA DIRECCIÓN GENERAL DE PLANIFICACIÓN Y PRESUPUESTO DE LA GOBERNACIÓN DEL ESTADO TRUJILLO', 1, 2025, 'storage/documentos/pst/pst_reestructuraci__n_y_mantenimie_1792469679_613.docx');
INSERT INTO public.recursos VALUES (305, 'SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA', 1, 2025, 'storage/documentos/pst/pst_sistema_de_red_wlan_para_la_co_1792469769_917.docx');


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);
INSERT INTO public.registro_actividad VALUES (3, 12, NULL, '2026-09-19 14:19:44.068925', '2026-09-19 14:20:25.834661', 2);
INSERT INTO public.registro_actividad VALUES (5, 4, NULL, '2026-10-19 22:18:39.479108', '2026-10-19 22:18:39.479108', 1);
INSERT INTO public.registro_actividad VALUES (4, 17, NULL, '2026-09-19 14:57:43.371674', '2026-10-19 22:18:53.623749', 4);
INSERT INTO public.registro_actividad VALUES (2, 7, NULL, '2026-09-19 13:23:18.589777', '2026-10-19 22:21:27.700323', 79);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (1, 'Super Administrador', 1);
INSERT INTO public.roles VALUES (3, 'Estudiantes', 6);
INSERT INTO public.roles VALUES (2, 'Comité', 2);
INSERT INTO public.roles VALUES (4, 'Docentes', 3);


--
-- Data for Name: system_audit_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.system_audit_log VALUES ('log_6aae33579b38a', '2026-09-19 07:01:43', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', 'GENESIS_CIIDI_V1', '7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5');
INSERT INTO public.system_audit_log VALUES ('log_6aae335f0804a', '2026-09-19 07:01:51', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', '7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5', 'b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2');
INSERT INTO public.system_audit_log VALUES ('log_6aae3381958c6', '2026-09-19 07:02:25', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', 'b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2', '92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da');
INSERT INTO public.system_audit_log VALUES ('log_6aae33853f3ff', '2026-09-19 07:02:29', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel.', 'Miguel González (ID: 7)', '::1', '92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da', '10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8');
INSERT INTO public.system_audit_log VALUES ('log_6aae346b666a8', '2026-09-19 07:06:19', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_06-32-52.sql.gz', 'Miguel González (ID: 7)', '::1', '10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8', '7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b');
INSERT INTO public.system_audit_log VALUES ('log_6aae346e42a0f', '2026-09-19 07:06:22', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_06-32-55.sql', 'Miguel González (ID: 7)', '::1', '7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b', '989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740');
INSERT INTO public.system_audit_log VALUES ('log_6aae3470d43bb', '2026-09-19 07:06:24', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_07-06-24.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740', '63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe659d9cc', '2026-09-19 16:55:01', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0', '349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe6f1390f', '2026-09-19 16:55:11', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-19_16-54-59.sql.gz', 'Miguel González (ID: 7)', '::1', '349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b', '801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c');
INSERT INTO public.system_audit_log VALUES ('log_6aaebe75516f3', '2026-09-19 16:55:17', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_16-55-16.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c', 'b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0c63a16d', '2026-09-19 17:05:10', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_07-06-27.sql', 'Miguel González (ID: 7)', '::1', 'b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53', 'ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0c9790a9', '2026-09-19 17:05:13', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-19_16-55-16.sql', 'Miguel González (ID: 7)', '::1', 'ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b', 'dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3');
INSERT INTO public.system_audit_log VALUES ('log_6aaec0cea62f3', '2026-09-19 17:05:18', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-19_17-05-18.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3', '65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973');
INSERT INTO public.system_audit_log VALUES ('log_6aaeca163cca5', '2026-09-19 17:44:54', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973', '4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b');
INSERT INTO public.system_audit_log VALUES ('log_6aaecf3f2e00d', '2026-09-19 18:06:55', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b', '8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2');
INSERT INTO public.system_audit_log VALUES ('log_6aaecf464d72a', '2026-09-19 18:07:02', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', '8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2', 'd337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2');
INSERT INTO public.system_audit_log VALUES ('log_6aaed1b55d6b7', '2026-09-19 18:17:25', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', 'd337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2', 'e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f');
INSERT INTO public.system_audit_log VALUES ('log_6aaed233578db', '2026-09-19 18:19:31', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (ANDRUS). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f', 'f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24');
INSERT INTO public.system_audit_log VALUES ('log_6aaed96f372d3', '2026-09-19 18:50:23', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', 'f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24', 'c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a');
INSERT INTO public.system_audit_log VALUES ('log_6aaedabf05687', '2026-09-19 18:55:59', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #12 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', 'c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a', 'bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2');
INSERT INTO public.system_audit_log VALUES ('log_6aaedb9bdf105', '2026-09-19 18:59:39', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adru). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2', '5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c');
INSERT INTO public.system_audit_log VALUES ('log_6aaedc24ecf08', '2026-09-19 19:01:56', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'Miguel González (ID: 7)', '::1', '5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c', 'eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b');
INSERT INTO public.system_audit_log VALUES ('log_6aaedeb86eab2', '2026-09-19 19:12:56', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder', 'Miguel González (ID: 7)', '::1', 'eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b', 'bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe');
INSERT INTO public.system_audit_log VALUES ('log_6aaededc7c8cf', '2026-09-19 19:13:32', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 12000000 (DIOSs). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe', '242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99');
INSERT INTO public.system_audit_log VALUES ('log_6aaedee65ff6d', '2026-09-19 19:13:42', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #15 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99', '8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381');
INSERT INTO public.system_audit_log VALUES ('log_6aaee02b5ef05', '2026-09-19 19:19:07', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381', '2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270');
INSERT INTO public.system_audit_log VALUES ('log_6aaee0323afa9', '2026-09-19 19:19:14', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270', '507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80');
INSERT INTO public.system_audit_log VALUES ('log_6ab593eecb01f4.27328092', '2026-09-24 17:19:42', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80', 'c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54');
INSERT INTO public.system_audit_log VALUES ('log_6ab59ed3238506.36285869', '2026-09-24 18:06:11', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54', '5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a4aa2a2137.51481159', '2026-09-24 18:31:06', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528', '6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a500ea1546.02583757', '2026-09-24 18:32:32', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0', 'b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5088d0500.29959545', '2026-09-24 18:32:40', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44', '38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a541bc6647.30407607', '2026-09-24 18:33:37', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd', '9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5cb411a84.57589918', '2026-09-24 18:35:55', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Usuario'' (ID: 0) cerró su sesión voluntariamente.', 'Anónimo / Sistema', '::1', '9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8', 'e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5d5d60719.47566487', '2026-09-24 18:36:05', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8', '31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a5e98d5860.20797335', '2026-09-24 18:36:25', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95', '8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a60fd00c08.81125505', '2026-09-24 18:37:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '127.0.0.1', '8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3', '8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05');
INSERT INTO public.system_audit_log VALUES ('log_6ab5a69a3cf6f8.92796130', '2026-09-24 18:39:22', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05', '37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ab824ef6e1.77162492', '2026-09-24 19:00:18', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5', '17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ab9f7dc311.02229887', '2026-09-24 19:00:47', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53', '94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208');
INSERT INTO public.system_audit_log VALUES ('log_6ab5abba3afc76.06917239', '2026-09-24 19:01:14', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208', '1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c20c587658.74181273', '2026-09-24 20:36:28', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6', 'fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c2119c0646.75356991', '2026-09-24 20:36:33', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82', '45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c23b56c262.01662172', '2026-09-24 20:37:15', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7', '191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c497d80b16.73582852', '2026-09-24 20:47:19', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687', '555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5c49c012169.70473067', '2026-09-24 20:47:24', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d', 'b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d571944a33.87726796', '2026-09-24 21:59:13', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91', 'e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dcb5b7e0d3.81594761', '2026-09-24 22:30:13', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895', '60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dd88ba4952.54635639', '2026-09-24 22:33:44', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (325.13ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63', '46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ebe769d5f8.32585648', '2026-09-24 23:35:03', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305', 'bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ebef7dc0c5.68948338', '2026-09-24 23:35:11', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248', 'a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ee9f6acaa8.80378739', '2026-09-24 23:46:39', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-20_21-00-03.sql', 'Miguel González (ID: 7)', '::1', 'a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20', '9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eea5350548.04228995', '2026-09-24 23:46:45', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-23_12-31-32.sql', 'Miguel González (ID: 7)', '::1', '9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209', 'a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eea88755c2.71231592', '2026-09-24 23:46:48', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-24_17-19-41.sql.gz', 'Miguel González (ID: 7)', '::1', 'a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698', 'a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eeabaf89e3.77502777', '2026-09-24 23:46:51', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-24_17-02-25.sql.gz', 'Miguel González (ID: 7)', '::1', 'a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0', '523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eeb4554ee6.59306678', '2026-09-24 23:47:00', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-24_23-46-59.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923', '7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704');
INSERT INTO public.system_audit_log VALUES ('log_6ab610b6a541b2.81849834', '2026-09-25 02:12:06', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-24_23-46-59.sql', 'Miguel González (ID: 7)', '::1', '7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704', '6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a');
INSERT INTO public.system_audit_log VALUES ('log_6ab70b912729a0.27256257', '2026-09-25 20:02:25', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a', '3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69');
INSERT INTO public.system_audit_log VALUES ('log_6ab71c65db02e8.54816092', '2026-09-25 21:14:13', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69', '19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b');
INSERT INTO public.system_audit_log VALUES ('log_6ab71c756d2f31.50517848', '2026-09-25 21:14:29', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b', 'd0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a');
INSERT INTO public.system_audit_log VALUES ('log_6ab73a17887213.71832384', '2026-09-25 23:20:55', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a', '42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f');
INSERT INTO public.system_audit_log VALUES ('log_6ab73a1baa8920.77773463', '2026-09-25 23:20:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f', 'ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e');
INSERT INTO public.system_audit_log VALUES ('log_6ab73d5913fb96.78987378', '2026-09-25 23:34:49', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', 'ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e', 'c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4');
INSERT INTO public.system_audit_log VALUES ('log_6ab73fd9a749b1.66865439', '2026-09-25 23:45:29', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'' (ID: #159).', 'Miguel González (ID: 7)', '::1', 'c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4', '0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307');
INSERT INTO public.system_audit_log VALUES ('log_6ab743ce003df8.01116084', '2026-09-26 00:02:22', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'' (ID: #162).', 'Miguel González (ID: 7)', '::1', '0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307', '68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757');
INSERT INTO public.system_audit_log VALUES ('log_6ab74400d95f48.22006442', '2026-09-26 00:03:12', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #163).', 'Miguel González (ID: 7)', '::1', '68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757', 'eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5');
INSERT INTO public.system_audit_log VALUES ('log_6ab74420405498.05037611', '2026-09-26 00:03:44', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5', 'f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b');
INSERT INTO public.system_audit_log VALUES ('log_6ab74424edf762.62531298', '2026-09-26 00:03:48', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b', '30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670');
INSERT INTO public.system_audit_log VALUES ('log_6ab8130a6ed6c0.41201158', '2026-09-26 14:46:34', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670', '18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26');
INSERT INTO public.system_audit_log VALUES ('log_6ab813205a1172.16639687', '2026-09-26 14:46:56', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26', 'b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4');
INSERT INTO public.system_audit_log VALUES ('log_6ab814627139a2.17495957', '2026-09-26 14:52:18', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4', '07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b');
INSERT INTO public.system_audit_log VALUES ('log_6ab850ed39c7d0.55124032', '2026-09-26 19:10:37', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b', 'a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e5cd0946.14674728', '2026-09-26 19:14:45', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813', 'c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e7c0e2d4.82296632', '2026-09-26 19:14:47', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e', '0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b');
INSERT INTO public.system_audit_log VALUES ('log_6ab851e94472b4.95421959', '2026-09-26 19:14:49', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b', 'fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681');
INSERT INTO public.system_audit_log VALUES ('log_6ab851ea611c46.80912087', '2026-09-26 19:14:50', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681', '0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd');
INSERT INTO public.system_audit_log VALUES ('log_6ab852ab554c79.73686163', '2026-09-26 19:18:03', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd', '1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136');
INSERT INTO public.system_audit_log VALUES ('log_6ab852adc206f8.64334637', '2026-09-26 19:18:05', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136', '3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3');
INSERT INTO public.system_audit_log VALUES ('log_6ab852bb98a3d2.31949010', '2026-09-26 19:18:19', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3', 'ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c');
INSERT INTO public.system_audit_log VALUES ('log_6ab852c6e3d2d4.83193988', '2026-09-26 19:18:30', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', 'ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c', 'cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd');
INSERT INTO public.system_audit_log VALUES ('log_6abaf653d69cd4.91449578', '2026-09-28 19:20:51', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Usuario'' (ID: 7) cerró su sesión voluntariamente.', 'Anónimo / Sistema', '::1', 'cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd', '9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec');
INSERT INTO public.system_audit_log VALUES ('log_6abaf659e980f7.77464928', '2026-09-28 19:20:57', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec', 'de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada');
INSERT INTO public.system_audit_log VALUES ('log_6abb00739e9ad6.86798317', '2026-09-28 20:04:03', 'INFO', 'SuperAdmin', 'Configurar SMTP', 'Se actualizaron las credenciales del servidor SMTP institucional (cifrado AES-256).', 'Miguel González (ID: 7)', '::1', 'de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada', 'ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1');
INSERT INTO public.system_audit_log VALUES ('log_6abb0086ba4219.64363834', '2026-09-28 20:04:22', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]', 'Miguel González (ID: 7)', '::1', 'ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1', '5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863');
INSERT INTO public.system_audit_log VALUES ('log_6abb009e0fa4d4.93609207', '2026-09-28 20:04:46', 'ERROR', 'SuperAdmin', 'Falla de Prueba SMTP', 'Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]', 'Miguel González (ID: 7)', '::1', '5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863', 'bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c');
INSERT INTO public.system_audit_log VALUES ('log_6abb00e29e3920.44297895', '2026-09-28 20:05:54', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Juan Salcedo (C.I: V-31008131)', 'Miguel González (ID: 7)', '::1', 'bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c', '2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c');
INSERT INTO public.system_audit_log VALUES ('log_6abb0445b7fc21.13482923', '2026-09-28 20:20:21', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #163 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c', '5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd');
INSERT INTO public.system_audit_log VALUES ('log_6abb05e4488908.23013098', '2026-09-28 20:27:16', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #159 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd', '3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52');
INSERT INTO public.system_audit_log VALUES ('log_6abb05e690a664.94528393', '2026-09-28 20:27:18', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #158 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52', '1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c');
INSERT INTO public.system_audit_log VALUES ('log_6abb14f1e8b789.04226522', '2026-09-28 21:31:29', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #128 modificado exitosamente: ''Materia: Seguridad Informáticasss''.', 'Miguel González (ID: 7)', '::1', '1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c', '634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93');
INSERT INTO public.system_audit_log VALUES ('log_6abb14fedf6bd4.59270778', '2026-09-28 21:31:42', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #128 modificado exitosamente: ''Materia: Seguridad Informáticasssssss''.', 'Miguel González (ID: 7)', '::1', '634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93', '18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031');
INSERT INTO public.system_audit_log VALUES ('log_6abb25014d0803.91919015', '2026-09-28 22:40:01', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (429.43ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031', '56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38');
INSERT INTO public.system_audit_log VALUES ('log_6abb28a869ee84.05577904', '2026-09-28 22:55:36', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-28_22-55-35.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38', '36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769');
INSERT INTO public.system_audit_log VALUES ('log_6abb2e572ef199.35321358', '2026-09-28 23:19:51', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0'' (ID: #164).', 'Miguel González (ID: 7)', '::1', '36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769', 'b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db');
INSERT INTO public.system_audit_log VALUES ('log_6abb3252338456.46689830', '2026-09-28 23:36:50', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #181 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db', '31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34');
INSERT INTO public.system_audit_log VALUES ('log_6abb32692036f8.48741519', '2026-09-28 23:37:13', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #179 modificado exitosamente: ''CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO''.', 'Miguel González (ID: 7)', '::1', '31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34', '9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c');
INSERT INTO public.system_audit_log VALUES ('log_6abb36af4e8757.34762637', '2026-09-28 23:55:27', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #182).', 'Miguel González (ID: 7)', '::1', '9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c', 'c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299');
INSERT INTO public.system_audit_log VALUES ('log_6abb36c856a079.23449967', '2026-09-28 23:55:52', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #183).', 'Miguel González (ID: 7)', '::1', 'c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299', 'e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9');
INSERT INTO public.system_audit_log VALUES ('log_6abb3c18ecb3c9.50352832', '2026-09-29 00:18:32', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #183 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9', '83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc');
INSERT INTO public.system_audit_log VALUES ('log_6abb3c22cddb53.18114712', '2026-09-29 00:18:42', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #182 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc', '0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97');
INSERT INTO public.system_audit_log VALUES ('log_6abb431bd4e935.20488688', '2026-09-29 00:48:27', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #184).', 'Miguel González (ID: 7)', '::1', '0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97', '75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e');
INSERT INTO public.system_audit_log VALUES ('log_6abb433969d1e8.66693670', '2026-09-29 00:48:57', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #184 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e', '267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0');
INSERT INTO public.system_audit_log VALUES ('log_6abb44ac675bb9.68582634', '2026-09-29 00:55:08', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #185).', 'Miguel González (ID: 7)', '::1', '267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0', '09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b');
INSERT INTO public.system_audit_log VALUES ('log_6abb44b7afb3d0.87024796', '2026-09-29 00:55:19', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #185 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b', '120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26');
INSERT INTO public.system_audit_log VALUES ('log_6abb4882ba9ba3.07735541', '2026-09-29 01:11:30', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-29_01-11-30.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26', 'f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2');
INSERT INTO public.system_audit_log VALUES ('log_6abb4ffb730527.24406994', '2026-09-29 01:43:23', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (426.37ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2', 'ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405');
INSERT INTO public.system_audit_log VALUES ('log_6abb50140092f9.61063580', '2026-09-29 01:43:48', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405', '1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06');
INSERT INTO public.system_audit_log VALUES ('log_6abb501c9cc977.29822340', '2026-09-29 01:43:56', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (446.92ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06', '884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708');
INSERT INTO public.system_audit_log VALUES ('log_6abb502fefc296.31272536', '2026-09-29 01:44:15', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (423.66ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708', 'a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c');
INSERT INTO public.system_audit_log VALUES ('log_6abb5048360852.15348832', '2026-09-29 01:44:40', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c', 'ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e');
INSERT INTO public.system_audit_log VALUES ('log_6abb505267f512.32865247', '2026-09-29 01:44:50', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (542.28ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e', '3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b');
INSERT INTO public.system_audit_log VALUES ('log_6abb50563e6010.66502540', '2026-09-29 01:44:54', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Limpieza de Archivos Temporales & Caché'': ÉXITO (55.37ms): Limpieza completada. Depurados 1 archivos temporales y cachés.', 'Miguel González (ID: 7)', '::1', '3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b', 'a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e');
INSERT INTO public.system_audit_log VALUES ('log_6abb5062f16395.04386794', '2026-09-29 01:45:06', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (441.05ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e', '86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761');
INSERT INTO public.system_audit_log VALUES ('log_6abb508a02b552.38635244', '2026-09-29 01:45:46', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1790301553_528) guardada correctamente.', 'Miguel González (ID: 7)', '::1', '86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761', '3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384');
INSERT INTO public.system_audit_log VALUES ('log_6abb50925ea7b6.59113930', '2026-09-29 01:45:54', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (404.51ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', '3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384', 'd7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170');
INSERT INTO public.system_audit_log VALUES ('log_6abb513542c6a4.96699430', '2026-09-29 01:48:37', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''hola'' (tarea_1790660917_352) guardada correctamente.', 'Miguel González (ID: 7)', '::1', 'd7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170', 'e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c');
INSERT INTO public.system_audit_log VALUES ('log_6abb513c3ca385.57807938', '2026-09-29 01:48:44', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''hola'': ERROR (440.94ms): Error de ejecución CLI (Código 3).', 'Miguel González (ID: 7)', '::1', 'e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c', 'd6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843');
INSERT INTO public.system_audit_log VALUES ('log_6abb5398dfd1a1.13820354', '2026-09-29 01:58:48', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''hola'': ÉXITO (171.64ms): Comando ejecutado con éxito (Código 0).', 'Anónimo / Sistema', '::1', 'd6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843', '7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657');
INSERT INTO public.system_audit_log VALUES ('log_6abb53992629a5.36578563', '2026-09-29 01:58:49', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (187.27ms): Comando ejecutado con éxito (Código 0).', 'Anónimo / Sistema', '::1', '7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657', '2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6');
INSERT INTO public.system_audit_log VALUES ('log_6abbc823dee0d0.34124357', '2026-09-29 10:16:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6', 'e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15');
INSERT INTO public.system_audit_log VALUES ('log_6abbc8d0bc21f4.91419125', '2026-09-29 10:18:56', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15', 'd137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868');
INSERT INTO public.system_audit_log VALUES ('log_6abbc8d41eec09.95002671', '2026-09-29 10:19:00', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'd137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868', 'ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d');
INSERT INTO public.system_audit_log VALUES ('log_6abbc9265355d9.01353952', '2026-09-29 10:20:22', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d', 'bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b');
INSERT INTO public.system_audit_log VALUES ('log_6abd9d7ba34596.72048568', '2026-09-30 19:38:35', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b', '0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40');
INSERT INTO public.system_audit_log VALUES ('log_6abda01f864d07.15614187', '2026-09-30 19:49:51', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0'' (ID: #186).', 'Miguel González (ID: 7)', '::1', '0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40', 'f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d');
INSERT INTO public.system_audit_log VALUES ('log_6abda27cbe1144.87841358', '2026-09-30 19:59:56', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.13ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', 'f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d', '938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79');
INSERT INTO public.system_audit_log VALUES ('log_6abda2f203f4c2.41849811', '2026-09-30 20:01:54', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-30_20-01-53.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79', 'f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc');
INSERT INTO public.system_audit_log VALUES ('log_6abda32829d946.58940367', '2026-09-30 20:02:48', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc', '8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b');
INSERT INTO public.system_audit_log VALUES ('log_6abda32f3a6772.91320492', '2026-09-30 20:02:55', 'CRITICAL', 'WAF_Security', 'Inyección SQL Interceptada', 'IP ::1 bloqueada por SQLi en parámetro ''password'': admin&#039; OR 1=1 --', 'Anónimo / Sistema', '::1', '8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b', '3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31');
INSERT INTO public.system_audit_log VALUES ('log_6abda65b750c05.40259276', '2026-09-30 20:16:27', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31', '47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e');
INSERT INTO public.system_audit_log VALUES ('log_6abda7d5a26db4.79353606', '2026-09-30 20:22:45', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e', 'efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646');
INSERT INTO public.system_audit_log VALUES ('log_6ac12828467c24.98604997', '2026-10-03 12:07:04', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646', 'edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27');
INSERT INTO public.system_audit_log VALUES ('log_6ac12ff22e6ad2.23847671', '2026-10-03 12:40:18', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Miguel Gonzálezzzzz'' (ID: 7).', 'Miguel Gonzálezzzzz (ID: 7)', '::1', 'edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27', 'a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197');
INSERT INTO public.system_audit_log VALUES ('log_6ac130e8c4df47.38223243', '2026-10-03 12:44:24', 'SECURITY', 'Autenticacion', 'Test Seguridad QA', 'Prueba automatizada de registro criptográfico.', 'Anónimo / Sistema', '127.0.0.1', 'a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197', 'edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14');
INSERT INTO public.system_audit_log VALUES ('log_6ac130faa9ef13.77888729', '2026-10-03 12:44:42', 'SECURITY', 'Autenticacion', 'Test Seguridad QA', 'Prueba automatizada de registro criptográfico.', 'Anónimo / Sistema', '127.0.0.1', 'edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14', 'f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f');
INSERT INTO public.system_audit_log VALUES ('log_6ac131494b62b0.95522024', '2026-10-03 12:46:01', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel Gonzálezzzzz'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel Gonzálezzzzz (ID: 7)', '::1', 'f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f', '80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5');
INSERT INTO public.system_audit_log VALUES ('log_6ac13877298687.66874114', '2026-10-03 13:16:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel Gonzálezzzzz'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel Gonzálezzzzz (ID: 7)', '::1', '80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5', '1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b093afc00.43901612', '2026-10-03 13:27:37', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Miguel González'' (ID: 7).', 'Miguel González (ID: 7)', '::1', '1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf', '971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b4f07fca9.12965797', '2026-10-03 13:28:47', 'INFO', 'Autenticacion', 'Correo Modificado', 'El usuario ID #7 actualizó su correo a ''erwazaaa@gmail.com''. Se envió enlace de verificación.', 'Miguel González (ID: 7)', '::1', '971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806', '5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b60b8a348.04621923', '2026-10-03 13:29:04', 'INFO', 'Autenticacion', 'Correo Modificado', 'El usuario ID #7 actualizó su correo a ''erwesitodepollo@gmail.com''. Se envió enlace de verificación.', 'Miguel González (ID: 7)', '::1', '5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171', '1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c');
INSERT INTO public.system_audit_log VALUES ('log_6ac13b66d6a3b3.39434338', '2026-10-03 13:29:10', 'INFO', 'Autenticacion', 'Verificación de Correo Solicitada', 'Enlace de verificación enviado a ''erwesitodepollo@gmail.com'' para usuario ID #7.', 'Miguel González (ID: 7)', '::1', '1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c', 'd3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e5bc96068.22485896', '2026-10-03 13:41:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86', 'f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e7ee99724.88249222', '2026-10-03 13:42:22', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf', '9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6');
INSERT INTO public.system_audit_log VALUES ('log_6ac13e8f294e28.07445518', '2026-10-03 13:42:39', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6', 'e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e');
INSERT INTO public.system_audit_log VALUES ('log_6ac13eb4a15e31.77988144', '2026-10-03 13:43:16', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e', '9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb');
INSERT INTO public.system_audit_log VALUES ('log_6ac13f64de6a85.11671560', '2026-10-03 13:46:12', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''no soy miguel'' (C.I: V-1234567).', 'Anónimo / Sistema', '::1', '9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb', '0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846');
INSERT INTO public.system_audit_log VALUES ('log_6ac13fc2b079e8.57780152', '2026-10-03 13:47:46', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846', '178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8');
INSERT INTO public.system_audit_log VALUES ('log_6ac13fcf644a83.29317983', '2026-10-03 13:47:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8', 'e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c');
INSERT INTO public.system_audit_log VALUES ('log_6ac1400c7d21c2.36177170', '2026-10-03 13:49:00', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c', '21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b');
INSERT INTO public.system_audit_log VALUES ('log_6ac140353a1e02.77789108', '2026-10-03 13:49:41', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345678''.', 'Anónimo / Sistema', '::1', '21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b', '2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18');
INSERT INTO public.system_audit_log VALUES ('log_6ac1404be5aea6.42915402', '2026-10-03 13:50:03', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18', '88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825');
INSERT INTO public.system_audit_log VALUES ('log_6ac140694bbc43.57753093', '2026-10-03 13:50:33', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825', '45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f');
INSERT INTO public.system_audit_log VALUES ('log_6ac140838b6c09.31008577', '2026-10-03 13:50:59', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f', 'b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f');
INSERT INTO public.system_audit_log VALUES ('log_6ac140c6df7b33.58916003', '2026-10-03 13:52:06', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345667''.', 'Anónimo / Sistema', '::1', 'b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f', 'd671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616');
INSERT INTO public.system_audit_log VALUES ('log_6ac140dfba3a66.37695673', '2026-10-03 13:52:31', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12345567''.', 'Anónimo / Sistema', '::1', 'd671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616', '425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879');
INSERT INTO public.system_audit_log VALUES ('log_6ac1410c318ee8.12576183', '2026-10-03 13:53:16', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879', '06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd');
INSERT INTO public.system_audit_log VALUES ('log_6ac141235905d9.76423907', '2026-10-03 13:53:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd', '2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d');
INSERT INTO public.system_audit_log VALUES ('log_6ac141b66375e1.41482376', '2026-10-03 13:56:06', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d', '05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4');
INSERT INTO public.system_audit_log VALUES ('log_6ac141be53d3f9.97944045', '2026-10-03 13:56:14', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4', '5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c');
INSERT INTO public.system_audit_log VALUES ('log_6ac141c612a8e7.73657758', '2026-10-03 13:56:22', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c', '9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88');
INSERT INTO public.system_audit_log VALUES ('log_6ac14283b15563.91767580', '2026-10-03 13:59:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88', '08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6');
INSERT INTO public.system_audit_log VALUES ('log_6ac1428d24f946.56878883', '2026-10-03 13:59:41', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6', '26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5');
INSERT INTO public.system_audit_log VALUES ('log_6ac142965eccd4.57785406', '2026-10-03 13:59:50', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5', '070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b');
INSERT INTO public.system_audit_log VALUES ('log_6ac1429c97d6e1.59083773', '2026-10-03 13:59:56', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 1.64ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', '070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b', 'ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177');
INSERT INTO public.system_audit_log VALUES ('log_6ac142f633ae61.82043725', '2026-10-03 14:01:26', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177', '8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034');
INSERT INTO public.system_audit_log VALUES ('log_6ac14310748685.52547574', '2026-10-03 14:01:52', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034', 'dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9');
INSERT INTO public.system_audit_log VALUES ('log_6ac14351beb5d7.13362571', '2026-10-03 14:02:57', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9', '223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428');
INSERT INTO public.system_audit_log VALUES ('log_6ac143855b9691.65506208', '2026-10-03 14:03:49', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428', '98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100');
INSERT INTO public.system_audit_log VALUES ('log_6ac144082ef9e1.16827141', '2026-10-03 14:06:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100', 'd2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569');
INSERT INTO public.system_audit_log VALUES ('log_6ac144e48b8447.33270683', '2026-10-03 14:09:40', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569', 'a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da');
INSERT INTO public.system_audit_log VALUES ('log_6ac1450cb24579.82197712', '2026-10-03 14:10:20', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', 'a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da', '81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc');
INSERT INTO public.system_audit_log VALUES ('log_6ac14681285926.78836938', '2026-10-03 14:16:33', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc', 'a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0');
INSERT INTO public.system_audit_log VALUES ('log_6ac146b0ae9965.21887296', '2026-10-03 14:17:20', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0', 'f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b');
INSERT INTO public.system_audit_log VALUES ('log_6ac146cc15a824.74334312', '2026-10-03 14:17:48', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b', '743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53');
INSERT INTO public.system_audit_log VALUES ('log_6ac1492e3bc309.07890168', '2026-10-03 14:27:58', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53', '43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac1494129f614.76362695', '2026-10-03 14:28:17', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Miguel González'' (C.I: V-32621284).', 'Anónimo / Sistema', '::1', '43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd', 'e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b');
INSERT INTO public.system_audit_log VALUES ('log_6ac14c4986d566.91856480', '2026-10-03 14:41:13', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b', 'ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5');
INSERT INTO public.system_audit_log VALUES ('log_6ac290242a51b7.18332813', '2026-10-04 13:43:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5', '03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824');
INSERT INTO public.system_audit_log VALUES ('log_6ac2911ef16e10.78511822', '2026-10-04 13:47:10', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824', 'f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc');
INSERT INTO public.system_audit_log VALUES ('log_6ac29731984260.15880698', '2026-10-04 14:13:05', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc', '0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988');
INSERT INTO public.system_audit_log VALUES ('log_6ac297340360a6.85677947', '2026-10-04 14:13:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988', 'aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11');
INSERT INTO public.system_audit_log VALUES ('log_6ac29734eeea69.29357751', '2026-10-04 14:13:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11', '26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b');
INSERT INTO public.system_audit_log VALUES ('log_6ac297391c07f7.58886272', '2026-10-04 14:13:13', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b', 'ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00');
INSERT INTO public.system_audit_log VALUES ('log_6ac29986ccd537.36845720', '2026-10-04 14:23:02', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #189).', 'Miguel González (ID: 7)', '::1', 'ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00', 'dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029');
INSERT INTO public.system_audit_log VALUES ('log_6ac2b113ef3042.01181958', '2026-10-04 16:03:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029', '9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac2b118ce98f2.42047090', '2026-10-04 16:03:36', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd', '3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ba6db1d628.29952574', '2026-10-04 16:43:25', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales'' (ID: #191).', 'Miguel González (ID: 7)', '::1', '3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783', '3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59');
INSERT INTO public.system_audit_log VALUES ('log_6ac2bc6db08428.55733278', '2026-10-04 16:51:57', 'INFO', 'RepositorioPST', 'Editar Nivel Académico', 'Se actualizó el nivel académico ID 2.', 'Miguel González (ID: 7)', '::1', '3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59', 'ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0');
INSERT INTO public.system_audit_log VALUES ('log_6ac2bcccab46d8.98270583', '2026-10-04 16:53:32', 'INFO', 'RepositorioPST', 'Editar Nivel Académico', 'Se actualizó el nivel académico ID 2.', 'Miguel González (ID: 7)', '::1', 'ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0', 'f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804');
INSERT INTO public.system_audit_log VALUES ('log_6ac2c19ac0c805.51009240', '2026-10-04 17:14:02', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales'' (ID: #192).', 'Miguel González (ID: 7)', '::1', 'f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804', '9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421');
INSERT INTO public.system_audit_log VALUES ('log_6ac2e9775836d0.04371946', '2026-10-04 20:04:07', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421', 'f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ea533d0ee2.65428414', '2026-10-04 20:07:47', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3', 'a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ea95d8edd8.61477690', '2026-10-04 20:08:53', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3', '5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eade037e09.34485946', '2026-10-04 20:10:06', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5', 'cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eadecdaf28.99002115', '2026-10-04 20:10:06', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4', '94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eadfbca4d7.74874338', '2026-10-04 20:10:07', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae', '0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c');
INSERT INTO public.system_audit_log VALUES ('log_6ac2eae0a654b1.94476951', '2026-10-04 20:10:08', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c', '9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec5601cdb9.62461585', '2026-10-04 20:16:22', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5', '2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec5d8bb483.27381483', '2026-10-04 20:16:29', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e', 'fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec687a6a52.51050372', '2026-10-04 20:16:40', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05', '855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec69a91fc5.76197883', '2026-10-04 20:16:41', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034', '371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec6a953041.71829326', '2026-10-04 20:16:42', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9', '916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768');
INSERT INTO public.system_audit_log VALUES ('log_6ac2ec6d032786.51154961', '2026-10-04 20:16:45', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768', 'e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec262fd721.24836059', '2026-10-05 14:27:50', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1', '1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec91266b08.85328438', '2026-10-05 14:29:37', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-10-05_14-29-36.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665', '3f952f8bc0c0055ff7f1b1a1f2b45d10dbd4163ab50f0897e4844c6190f8ed9d');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec98497d76.34827531', '2026-10-05 14:29:44', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-29_01-11-30.sql', 'Miguel González (ID: 7)', '::1', '3f952f8bc0c0055ff7f1b1a1f2b45d10dbd4163ab50f0897e4844c6190f8ed9d', 'f908ebf223ee2abcb7032793a1d55545ef46e0af6b8e811022828966edaff02e');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec9b89cf17.09967153', '2026-10-05 14:29:47', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-09-30_20-01-53.sql.gz', 'Miguel González (ID: 7)', '::1', 'f908ebf223ee2abcb7032793a1d55545ef46e0af6b8e811022828966edaff02e', 'e158d63bc615676ba7d3f81e618fb4fc76f38733455f23231616f794d178b070');
INSERT INTO public.system_audit_log VALUES ('log_6ac3ec9e2cf8c9.43547874', '2026-10-05 14:29:50', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-04_23-56-43.sql.gz', 'Miguel González (ID: 7)', '::1', 'e158d63bc615676ba7d3f81e618fb4fc76f38733455f23231616f794d178b070', '2f451489bf847d8ced60912f42a6072d58390d2affb03b022af3ab3c4effc8f9');
INSERT INTO public.system_audit_log VALUES ('log_6ac504fd9fcd12.90258639', '2026-10-06 10:26:05', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2f451489bf847d8ced60912f42a6072d58390d2affb03b022af3ab3c4effc8f9', '1f9dd73ecbb26cc6a6a47728aa1cb7bc924da7a2308951690ed04203db8ce514');
INSERT INTO public.system_audit_log VALUES ('log_6ac50b5f9de507.56076043', '2026-10-06 10:53:19', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '1f9dd73ecbb26cc6a6a47728aa1cb7bc924da7a2308951690ed04203db8ce514', '720a88401ea0dbedcfd613e884e9d3a0ac0a1d19b66c5556e5aabf775f5511c8');
INSERT INTO public.system_audit_log VALUES ('log_6ac50b75478373.62861792', '2026-10-06 10:53:41', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '720a88401ea0dbedcfd613e884e9d3a0ac0a1d19b66c5556e5aabf775f5511c8', '377a5e9353503df135791b1b62dfd2b0c113385c7d3889a8695ed99d0d31c1dd');
INSERT INTO public.system_audit_log VALUES ('log_6ac979343affe0.38714832', '2026-10-09 19:31:00', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '377a5e9353503df135791b1b62dfd2b0c113385c7d3889a8695ed99d0d31c1dd', '92d48746a48ff531888a0e2d4d9f48fcb8ae034fae23a70eb765525670cc59d9');
INSERT INTO public.system_audit_log VALUES ('log_6ac9798708df24.32604951', '2026-10-09 19:32:23', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo pre_restore_checkpoint_2026-10-09_19-32-20.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '92d48746a48ff531888a0e2d4d9f48fcb8ae034fae23a70eb765525670cc59d9', '8e95f5a92d4c11e96495d244d0fd338d8d9bd5228c1fff641a681cb23feb7bf0');
INSERT INTO public.system_audit_log VALUES ('log_6ac97987a9aad7.31544594', '2026-10-09 19:32:23', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '8e95f5a92d4c11e96495d244d0fd338d8d9bd5228c1fff641a681cb23feb7bf0', 'd9d80a66d4988e1bc0bd1a85b7ee5d60e564bd137d94e83fa7b8e28ab25f15a7');
INSERT INTO public.system_audit_log VALUES ('log_6ac9ab70a28e01.20857263', '2026-10-09 23:05:20', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'd9d80a66d4988e1bc0bd1a85b7ee5d60e564bd137d94e83fa7b8e28ab25f15a7', '9f9850a848e35e5e98d1671e0530cebf0d2bd66bd9fe6cf217d732f3c88c6a74');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b5f79df500.53282447', '2026-10-09 23:50:15', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '9f9850a848e35e5e98d1671e0530cebf0d2bd66bd9fe6cf217d732f3c88c6a74', 'c43a9049de3391783c54b1054d7df77d64d8a8dcd058af0c559ec47a294e862a');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64962d632.68641383', '2026-10-09 23:51:37', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', 'c43a9049de3391783c54b1054d7df77d64d8a8dcd058af0c559ec47a294e862a', 'e67be910dcb20debf3341a48f440c928383d7fa260e31cc25a2d1646fe987814');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64a16e1a9.75282571', '2026-10-09 23:51:38', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', 'e67be910dcb20debf3341a48f440c928383d7fa260e31cc25a2d1646fe987814', '1992d74b0025260870e358a8bd40477362a8bfd1d4376190dd0fa52311d973a2');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b64a320d37.17914146', '2026-10-09 23:51:38', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '1992d74b0025260870e358a8bd40477362a8bfd1d4376190dd0fa52311d973a2', '41bd184276a823a92447a856e59a19f9a9375363e923e1a067e3db2fcc25386f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b660c4ce27.59338818', '2026-10-09 23:52:00', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', '41bd184276a823a92447a856e59a19f9a9375363e923e1a067e3db2fcc25386f', '727ee25f9eb555cdd84ba8b5970462621de7bda3ca7847e91c0423dea1ff1f4f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67ccf2045.72642442', '2026-10-09 23:52:28', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '727ee25f9eb555cdd84ba8b5970462621de7bda3ca7847e91c0423dea1ff1f4f', '35892454cad819007e3000b92d91c6a94ec1cc50c05946020d243e8e4276845a');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67d6c94f2.58970066', '2026-10-09 23:52:29', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '35892454cad819007e3000b92d91c6a94ec1cc50c05946020d243e8e4276845a', '2719ec60c88cad17696f5092e23cfc81fb7edf9bf9cd9b4d7d40dade86eec5db');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b67da24f28.27563813', '2026-10-09 23:52:29', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'': SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «cedula»
LINE 1: SELECT id FROM public.autores WHERE LOWER(TRIM(cedula)) IN (...
                                                       ^', 'Miguel González (ID: 7)', '::1', '2719ec60c88cad17696f5092e23cfc81fb7edf9bf9cd9b4d7d40dade86eec5db', 'fa0644f33eb5d0a430dbb84fafbbae73cbcf48feebfa79650e1599eb97758ccf');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8454b22d9.58265842', '2026-10-10 00:00:05', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo pre_restore_checkpoint_2026-10-10_00-00-04.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'fa0644f33eb5d0a430dbb84fafbbae73cbcf48feebfa79650e1599eb97758ccf', 'dcf6556150e89f8a03051a0af0c56a8eedb1652ae36b33b6fc30c6ae86ad3822');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b845994c68.35650090', '2026-10-10 00:00:05', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', 'dcf6556150e89f8a03051a0af0c56a8eedb1652ae36b33b6fc30c6ae86ad3822', '6c2e6bae64273d5396dde10b2595420312ee3df6a8a8f46b04c387a45102e3d1');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8713fddd7.55190235', '2026-10-10 00:00:49', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dasdasdase Contaduría Pública UPTT Mario Briceño Iragorry'' (ID: #200).', 'Miguel González (ID: 7)', '::1', '6c2e6bae64273d5396dde10b2595420312ee3df6a8a8f46b04c387a45102e3d1', '899a8ae2aa89780a17aed2098c6e4e47c705322daddbe1e689f986161defa10f');
INSERT INTO public.system_audit_log VALUES ('log_6ac9b8be20ffb7.45647653', '2026-10-10 00:02:06', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #200 modificado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF dassadasdasdasdassadassadasasdasdasdase Contaduría Pública UPTT Mario Briceño Iragorry''.', 'Miguel González (ID: 7)', '::1', '899a8ae2aa89780a17aed2098c6e4e47c705322daddbe1e689f986161defa10f', 'e561144bccb40e329f768747d4e910f104f5a0ab6604bf432472da6fc9c15da9');
INSERT INTO public.system_audit_log VALUES ('log_6ac9c6a3836d12.59650077', '2026-10-10 01:01:23', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-10-10_01-01-23.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'e561144bccb40e329f768747d4e910f104f5a0ab6604bf432472da6fc9c15da9', '4f85f187769aac5ee41914688633093723a88c46e6db28dd7b583b1fa84a9bb5');
INSERT INTO public.system_audit_log VALUES ('log_6ac9cc9de00642.40639774', '2026-10-10 01:26:53', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '4f85f187769aac5ee41914688633093723a88c46e6db28dd7b583b1fa84a9bb5', '2f000f4ec7dd7b3503fb2041078c77c035b66f12f54091ce48c776f16f391608');
INSERT INTO public.system_audit_log VALUES ('log_6ac9cca26bb835.71607013', '2026-10-10 01:26:58', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '2f000f4ec7dd7b3503fb2041078c77c035b66f12f54091ce48c776f16f391608', 'f7a70e38336c5b9f40ca2eddaca3b071d39f44c8eca7758d207997639cf2d016');
INSERT INTO public.system_audit_log VALUES ('log_6aca5cd69fda30.54957050', '2026-10-10 11:42:14', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'f7a70e38336c5b9f40ca2eddaca3b071d39f44c8eca7758d207997639cf2d016', '929abc03da036376bd43baf405143e74491cf29259eaa3e159e5bc9458ed2dfb');
INSERT INTO public.system_audit_log VALUES ('log_6aca5d69dc4365.72860864', '2026-10-10 11:44:41', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-10_01-01-23.sql', 'Miguel González (ID: 7)', '::1', '929abc03da036376bd43baf405143e74491cf29259eaa3e159e5bc9458ed2dfb', 'dd2f20873a73bbec4b821b15cc8140d5b85364ec35cc7378b59e63d5a7f1fa76');
INSERT INTO public.system_audit_log VALUES ('log_6aca5d97add2f5.65270810', '2026-10-10 11:45:27', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-10_00-00-04.sql.gz', 'Miguel González (ID: 7)', '::1', 'dd2f20873a73bbec4b821b15cc8140d5b85364ec35cc7378b59e63d5a7f1fa76', '9c35e99aeb394d78c3f006aeca813e6406f8a81880eadf118fde54d122d43dc2');
INSERT INTO public.system_audit_log VALUES ('log_6aca5db2419a88.20587939', '2026-10-10 11:45:54', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-09_19-32-20.sql.gz', 'Miguel González (ID: 7)', '::1', '9c35e99aeb394d78c3f006aeca813e6406f8a81880eadf118fde54d122d43dc2', 'c58a7ab70fe10a1e599b740a9ac74122b174fc1aae41819b5df7f189ea45530f');
INSERT INTO public.system_audit_log VALUES ('log_6aca5e0c834188.89055586', '2026-10-10 11:47:24', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-05_14-29-36.sql', 'Miguel González (ID: 7)', '::1', 'c58a7ab70fe10a1e599b740a9ac74122b174fc1aae41819b5df7f189ea45530f', '5b92ff2e0a2eabb42cbbc062ede4ffad308faa9474da7b44a2dc468e7267968c');
INSERT INTO public.system_audit_log VALUES ('log_6aca5e27c72fd2.95758587', '2026-10-10 11:47:51', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-10-10_11-47-51.sql generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '5b92ff2e0a2eabb42cbbc062ede4ffad308faa9474da7b44a2dc468e7267968c', '3c178d25e89ad49008e586ee5c3ef849d6c575c890c07f986e9f6eac42c96442');
INSERT INTO public.system_audit_log VALUES ('log_6aca6b073f13c9.04299511', '2026-10-10 12:42:47', 'ERROR', 'Autenticacion', 'Falla Conexión Login', 'Error de base de datos al autenticar cédula ''V-32621284'': SQLSTATE[42501]: Insufficient privilege: 7 ERROR:  permiso denegado a la tabla usuarios', 'Anónimo / Sistema', '::1', '3c178d25e89ad49008e586ee5c3ef849d6c575c890c07f986e9f6eac42c96442', '68298bd33a0bcc5bf888a05dfef7cd387dd0af9ff2d42dbd04cf56597eb6b9c0');
INSERT INTO public.system_audit_log VALUES ('log_6aca6b2d360937.71588432', '2026-10-10 12:43:25', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '68298bd33a0bcc5bf888a05dfef7cd387dd0af9ff2d42dbd04cf56597eb6b9c0', 'd2bc780aa41ca53b424624418c00c153e4b862b551364ac3628d81822c6c3274');
INSERT INTO public.system_audit_log VALUES ('log_6aca6b79702116.76726929', '2026-10-10 12:44:41', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'd2bc780aa41ca53b424624418c00c153e4b862b551364ac3628d81822c6c3274', '31e1dcb1623340e20749dbce87bfeaecda726e04f049fccd90d8fd4d90c86927');
INSERT INTO public.system_audit_log VALUES ('log_6aca6b7f7b06a8.77417885', '2026-10-10 12:44:47', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '31e1dcb1623340e20749dbce87bfeaecda726e04f049fccd90d8fd4d90c86927', 'c96d504368dc299310400960f8a39638658d3f95eae5cb311078d95f5a8304b8');
INSERT INTO public.system_audit_log VALUES ('log_6acda1846c70f0.82106464', '2026-10-12 23:12:04', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'c96d504368dc299310400960f8a39638658d3f95eae5cb311078d95f5a8304b8', '936dbb0fabc8cdf1ee50fc7aec9efee15905a04a28501c2977bc9d9febebe8b4');
INSERT INTO public.system_audit_log VALUES ('log_6acee3d6e98c15.26754438', '2026-10-13 22:07:18', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '936dbb0fabc8cdf1ee50fc7aec9efee15905a04a28501c2977bc9d9febebe8b4', '17006ac5206b34c2116fe4a155a0e8a78134e26b89c7a95f53e1a7feb6b0f110');
INSERT INTO public.system_audit_log VALUES ('log_6acee4ebdcdf45.92243150', '2026-10-13 22:11:55', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema Inteligente de Redes Neuronales para la Gestión Integral de la asdasdasdasdCoordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry'' (ID: #201).', 'Miguel González (ID: 7)', '::1', '17006ac5206b34c2116fe4a155a0e8a78134e26b89c7a95f53e1a7feb6b0f110', 'a9d8197fc7af25c20e584692338d93baebb5b86dde0832cd1fcfda54e827caee');
INSERT INTO public.system_audit_log VALUES ('log_6acee51247d2a8.17313702', '2026-10-13 22:12:34', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', 'a9d8197fc7af25c20e584692338d93baebb5b86dde0832cd1fcfda54e827caee', 'd25d4b62de46a1dbf4a3ab8a1fe7d87361a9e12213846e22655f022c05349900');
INSERT INTO public.system_audit_log VALUES ('log_6acee52c236677.82879060', '2026-10-13 22:13:00', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', 'd25d4b62de46a1dbf4a3ab8a1fe7d87361a9e12213846e22655f022c05349900', '7f34de135395556fcef2238e28a1453c78fb533039653f55b66902bedbcd3626');
INSERT INTO public.system_audit_log VALUES ('log_6acee53626b702.17032974', '2026-10-13 22:13:10', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', '7f34de135395556fcef2238e28a1453c78fb533039653f55b66902bedbcd3626', '730f7e249c6095681739760c2d5994b7d460fdb2b5015b8b6eef7db0cebc3ac0');
INSERT INTO public.system_audit_log VALUES ('log_6acee53d38fe36.85945677', '2026-10-13 22:13:17', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', '730f7e249c6095681739760c2d5994b7d460fdb2b5015b8b6eef7db0cebc3ac0', '5da8f215495e8e752d8feadc2add1fbd6b61a3d61bcbe489788b7a55c6359242');
INSERT INTO public.system_audit_log VALUES ('log_6acee541259568.77080189', '2026-10-13 22:13:21', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', '5da8f215495e8e752d8feadc2add1fbd6b61a3d61bcbe489788b7a55c6359242', '9c33eff9de03121a7e428488c615b030558dc32a154b1248bc6d3d512fbc06d3');
INSERT INTO public.system_audit_log VALUES ('log_6acee54229d9d7.81304981', '2026-10-13 22:13:22', 'ERROR', 'Core_Dispatcher', 'Excepción Interna HTTP 500', 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  no existe la relación «recurso_investigadores»
LINE 22:                     FROM recurso_investigadores ri2
                                  ^ en ArticuloModel.php:152', 'Miguel González (ID: 7)', '::1', '9c33eff9de03121a7e428488c615b030558dc32a154b1248bc6d3d512fbc06d3', 'f79953c24481812f01bcfc86cfbeb62e31406552e7955a32b645d2e6c49463cf');
INSERT INTO public.system_audit_log VALUES ('log_6aceeb43a28243.56568321', '2026-10-13 22:38:59', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP 198.51.100.99 bloqueada por 15 min tras 5 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '127.0.0.1', 'f79953c24481812f01bcfc86cfbeb62e31406552e7955a32b645d2e6c49463cf', 'a6ecb8605ac0b77d78fd8fc6fb1c29b4bf9bfb9e979d23ef0b1f5bc7d7f6195b');
INSERT INTO public.system_audit_log VALUES ('log_6aceec374f30b2.11963625', '2026-10-13 22:43:03', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP 198.51.100.99 bloqueada por 15 min tras 5 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '127.0.0.1', 'a6ecb8605ac0b77d78fd8fc6fb1c29b4bf9bfb9e979d23ef0b1f5bc7d7f6195b', '1d6f180cd6432b58bec5f6ef6a006d95be4aa67c960df36ccbad0f52ca92be42');
INSERT INTO public.system_audit_log VALUES ('log_6aceec3957d536.63722602', '2026-10-13 22:43:05', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo fat_test_schema_2026-10-13_22-43-04.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Anónimo / Sistema', '127.0.0.1', '1d6f180cd6432b58bec5f6ef6a006d95be4aa67c960df36ccbad0f52ca92be42', '331f456180b453c6eccfac2696ed781817d744f94ddb788f4ef3d3bc74ad4f19');
INSERT INTO public.system_audit_log VALUES ('log_6aceec395b1528.53856479', '2026-10-13 22:43:05', 'INFO', 'SuperAdmin', 'Prueba FAT-023', 'Verificación de cadena de bloques SHA-256 durante pruebas FAT.', 'Anónimo / Sistema', '127.0.0.1', '331f456180b453c6eccfac2696ed781817d744f94ddb788f4ef3d3bc74ad4f19', '0ae15ba284ee1293c0d6130515084a7ee747549660d658e4dfd4af4ac492f2e4');
INSERT INTO public.system_audit_log VALUES ('log_6aceed10ee7e38.02770645', '2026-10-13 22:46:40', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP 198.51.100.99 bloqueada por 15 min tras 5 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '127.0.0.1', '0ae15ba284ee1293c0d6130515084a7ee747549660d658e4dfd4af4ac492f2e4', '6d8bd874e462f23453d82f56520a6f1cb9e3e2081aea67e255b5fb192a6ecfb4');
INSERT INTO public.system_audit_log VALUES ('log_6aceed10f0c0e0.94517080', '2026-10-13 22:46:40', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP 198.51.100.99 bloqueada por 15 min tras 6 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '127.0.0.1', '6d8bd874e462f23453d82f56520a6f1cb9e3e2081aea67e255b5fb192a6ecfb4', '42f0c1b6a575177c3416b4d4d7e51a2e5ffb5c41b416ccb1dac07fad51ac2b00');
INSERT INTO public.system_audit_log VALUES ('log_6aceed11040fb2.67989531', '2026-10-13 22:46:41', 'CRITICAL', 'WAF_Security', 'Ataque XSS Interceptado', 'IP 198.51.100.99 detectada por XSS en parámetro ''test_waf''', 'Anónimo / Sistema', '198.51.100.99', '42f0c1b6a575177c3416b4d4d7e51a2e5ffb5c41b416ccb1dac07fad51ac2b00', 'a016ea1e49ed996ce7152a84d670cc42fbbf1dcba3decacfa0a4822f4a7b897f');
INSERT INTO public.system_audit_log VALUES ('log_6aceed11e69ed7.90835733', '2026-10-13 22:46:41', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo fat_test_schema_2026-10-13_22-46-41.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Anónimo / Sistema', '127.0.0.1', 'a016ea1e49ed996ce7152a84d670cc42fbbf1dcba3decacfa0a4822f4a7b897f', '41fc58db8b63353af61c9b9e48c22ac05673ef0d39824ed61391eda34978d088');
INSERT INTO public.system_audit_log VALUES ('log_6aceed11e87430.81925247', '2026-10-13 22:46:41', 'INFO', 'SuperAdmin', 'Prueba FAT-023', 'Verificación de cadena de bloques SHA-256 durante pruebas FAT.', 'Anónimo / Sistema', '127.0.0.1', '41fc58db8b63353af61c9b9e48c22ac05673ef0d39824ed61391eda34978d088', '9f64b8d5fee69ecf8657bac80fcdc95c26226c0e01b6158d944547ef5c3c3bf2');
INSERT INTO public.system_audit_log VALUES ('log_6acfb2a7573653.31347839', '2026-10-14 12:49:43', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '9f64b8d5fee69ecf8657bac80fcdc95c26226c0e01b6158d944547ef5c3c3bf2', '4afd057bd9e12c20670e40ff89a3c7a10aad7cd78224b26f7994955ed8442eaf');
INSERT INTO public.system_audit_log VALUES ('log_6acfb402b8a5c9.97253483', '2026-10-14 12:55:30', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '4afd057bd9e12c20670e40ff89a3c7a10aad7cd78224b26f7994955ed8442eaf', 'fee1ab1a868a6bf0307ac3ee5cf4371b8c9a84fdc0816b69bd7ee380fdfbebc9');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cce4aff34.75378764', '2026-10-16 17:53:18', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'fee1ab1a868a6bf0307ac3ee5cf4371b8c9a84fdc0816b69bd7ee380fdfbebc9', '88dd18452fe8112029c57160a15decae660a9cf6fa9d63bb3222b1a0fcc9601d');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cf0dc2990.09814163', '2026-10-16 17:53:52', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-05_14-29-36.sql', 'Miguel González (ID: 7)', '::1', '88dd18452fe8112029c57160a15decae660a9cf6fa9d63bb3222b1a0fcc9601d', '3ac3fb81c4665703896a50faa79932e9d6056f09369274de83ba36ebfc87986b');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cf3283e00.02882826', '2026-10-16 17:53:55', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-10_01-01-23.sql', 'Miguel González (ID: 7)', '::1', '3ac3fb81c4665703896a50faa79932e9d6056f09369274de83ba36ebfc87986b', '58562ed8523350f2d687ebab858049576e56ac0cebb553dad4af7ed19e60ff3b');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cf5e47aa0.09675897', '2026-10-16 17:53:57', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-09_19-32-20.sql.gz', 'Miguel González (ID: 7)', '::1', '58562ed8523350f2d687ebab858049576e56ac0cebb553dad4af7ed19e60ff3b', '1d9d4acdaaba8c2a98c16aa0e79bacbc5df51df50137caad3f81112f051958af');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cf80dcf53.08842564', '2026-10-16 17:54:00', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-10_00-00-04.sql.gz', 'Miguel González (ID: 7)', '::1', '1d9d4acdaaba8c2a98c16aa0e79bacbc5df51df50137caad3f81112f051958af', '2df116f11b012ad7f57f3009bf0fbd85519320073c93b79116dacc5afff49a60');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cfa769148.99404822', '2026-10-16 17:54:02', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-10-12_12-41-14.sql.gz', 'Miguel González (ID: 7)', '::1', '2df116f11b012ad7f57f3009bf0fbd85519320073c93b79116dacc5afff49a60', '5839f519bfa434ba78310cd58969debeb8edd2a78902d61898011ebc1d8c269d');
INSERT INTO public.system_audit_log VALUES ('log_6ad29cfca79333.36969842', '2026-10-16 17:54:04', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-10_11-47-51.sql', 'Miguel González (ID: 7)', '::1', '5839f519bfa434ba78310cd58969debeb8edd2a78902d61898011ebc1d8c269d', '245ce62caca9441099692aec0f4ae7e41e131bcd68dde8e24901139eb830f328');
INSERT INTO public.system_audit_log VALUES ('log_6ad29d014830e4.62888936', '2026-10-16 17:54:09', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-10-16_17-54-08.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '245ce62caca9441099692aec0f4ae7e41e131bcd68dde8e24901139eb830f328', 'd8227655a0d7eb75e49d44d73467f0f34fc4ef775687a24c898fd98a7d87db81');
INSERT INTO public.system_audit_log VALUES ('log_6ad2a6944dcc33.05593404', '2026-10-16 18:35:00', 'INFO', 'SuperAdmin', 'Optimizar BD', 'VACUUM ANALYZE ejecutado exitosamente.', 'Miguel González (ID: 7)', '::1', 'd8227655a0d7eb75e49d44d73467f0f34fc4ef775687a24c898fd98a7d87db81', 'b07d7b952bf6ae3b41d1edb37e81cbdceba2ba3bf03bb96161fb2a32cac09959');
INSERT INTO public.system_audit_log VALUES ('log_6ad2a9cb377d05.71235389', '2026-10-16 18:48:43', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b07d7b952bf6ae3b41d1edb37e81cbdceba2ba3bf03bb96161fb2a32cac09959', 'e6b4eb93f32e3365d375701322a24b111a0a75903db1ca57638543902d19b810');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb5e50f449.25277303', '2026-10-16 20:03:42', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'e6b4eb93f32e3365d375701322a24b111a0a75903db1ca57638543902d19b810', '8dd6fce5d037ecc8fda3581c4f44ff35727d626096a34863a30afebae6afedd3');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb67af5f08.55049685', '2026-10-16 20:03:51', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-2222''.', 'Anónimo / Sistema', '::1', '8dd6fce5d037ecc8fda3581c4f44ff35727d626096a34863a30afebae6afedd3', '0cc0202a46f5c53babebee4826c3359d74f640163dd3f7509c3934f6ecbd3c0d');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb737491f7.04302721', '2026-10-16 20:04:03', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-123123''.', 'Anónimo / Sistema', '::1', '0cc0202a46f5c53babebee4826c3359d74f640163dd3f7509c3934f6ecbd3c0d', 'cb4ea67c6e461027bf78694fb550f406ac383d5f36c70c477d3cd73b8f0a66a0');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb7d05daa9.96391566', '2026-10-16 20:04:13', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-121312''.', 'Anónimo / Sistema', '::1', 'cb4ea67c6e461027bf78694fb550f406ac383d5f36c70c477d3cd73b8f0a66a0', '0cc9524cc5ee210e7d666ba2308644d0fbe5b7079b0acaffd5838147ae609f65');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb830fe5e9.02114912', '2026-10-16 20:04:19', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-12312312''.', 'Anónimo / Sistema', '::1', '0cc9524cc5ee210e7d666ba2308644d0fbe5b7079b0acaffd5838147ae609f65', 'c9bbb7faa5b15f2992fb27184e3b7ddf5da8746ab94534753cae1491f1de31cd');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb8ad1ad72.22161321', '2026-10-16 20:04:26', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP ::1 bloqueada por 15 min tras 5 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '::1', 'c9bbb7faa5b15f2992fb27184e3b7ddf5da8746ab94534753cae1491f1de31cd', '2319dccf97b4180d50d8510372a30eb5bd60ed4d4739db53fec0ced14aafe2d0');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb8ad39c64.32405042', '2026-10-16 20:04:26', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-132321312''.', 'Anónimo / Sistema', '::1', '2319dccf97b4180d50d8510372a30eb5bd60ed4d4739db53fec0ced14aafe2d0', 'bc794ed2d8f9940b7a8a2c77208777856b9c95318748066e9480a460de9bd8d2');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb981a3dd0.56860604', '2026-10-16 20:04:40', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP ::1 bloqueada por 15 min tras 6 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '::1', 'bc794ed2d8f9940b7a8a2c77208777856b9c95318748066e9480a460de9bd8d2', '8b4eb951d46e5fafc94cb7efe6b56d6134bad489f203d02a4fb62ca6b25e5f0e');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bb981c8364.12885218', '2026-10-16 20:04:40', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-123123''.', 'Anónimo / Sistema', '::1', '8b4eb951d46e5fafc94cb7efe6b56d6134bad489f203d02a4fb62ca6b25e5f0e', 'f17d2a06a00ec7a1c8a718addc5ee7dc2e7b5c624f33b5fc5835f1fd8ab937d4');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bbb3b01818.93390970', '2026-10-16 20:05:07', 'CRITICAL', 'WAF_Security', 'Bloqueo Automático IP por Fuerza Bruta', 'IP ::1 bloqueada por 15 min tras 7 intentos fallidos (Límite: 5).', 'Anónimo / Sistema', '::1', 'f17d2a06a00ec7a1c8a718addc5ee7dc2e7b5c624f33b5fc5835f1fd8ab937d4', '11a39b8578ba0658c014b7e625f5ee69a8456feb369b363fbff7891841aa3ac2');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bbb3b32981.26507786', '2026-10-16 20:05:07', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-213121223''.', 'Anónimo / Sistema', '::1', '11a39b8578ba0658c014b7e625f5ee69a8456feb369b363fbff7891841aa3ac2', '72461fd30e4686bbfe777e206ac7fb69ed997290b613fb4a89e6198f12625672');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bc4236cd39.80226960', '2026-10-16 20:07:30', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '72461fd30e4686bbfe777e206ac7fb69ed997290b613fb4a89e6198f12625672', '4af8eb13f64839c2b3f1534bae3628dc01901d54a41ac0ebfb0978f1c7173317');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bd70aa3c67.78051914', '2026-10-16 20:12:32', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '4af8eb13f64839c2b3f1534bae3628dc01901d54a41ac0ebfb0978f1c7173317', 'eef581e995cc5cc7a540838e40ef93abb0547befefee1321d9f7a1c169107c8e');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bd755d86f0.96210542', '2026-10-16 20:12:37', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''adrusss'' (C.I: V-30469331).', 'Anónimo / Sistema', '::1', 'eef581e995cc5cc7a540838e40ef93abb0547befefee1321d9f7a1c169107c8e', 'b3185c1a76284b910969adc17a1dbca43927df68bf3f8d9d3e45649e90e9fa58');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bd839c2d94.82355852', '2026-10-16 20:12:51', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-32618284''.', 'Anónimo / Sistema', '::1', 'b3185c1a76284b910969adc17a1dbca43927df68bf3f8d9d3e45649e90e9fa58', 'ecd24cdb5909f272149fa9394f88c6398a28c9d13830840d5092c5094d1cac13');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bd890bac23.71599490', '2026-10-16 20:12:57', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'ecd24cdb5909f272149fa9394f88c6398a28c9d13830840d5092c5094d1cac13', 'e96c8eb4ddfa71228197e2344aeb70bac68ea5fca60bb6f62ee55f7d7d9971e5');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bdaad0f129.96876188', '2026-10-16 20:13:30', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. V-30469331 (adrusss). Se forzó cambio de contraseña. Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'e96c8eb4ddfa71228197e2344aeb70bac68ea5fca60bb6f62ee55f7d7d9971e5', '78499526dabe8353353f6b22a8dcc73372d34429aaaf576bc3fc26d2441b3920');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bdb03b7ee7.34925658', '2026-10-16 20:13:36', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '78499526dabe8353353f6b22a8dcc73372d34429aaaf576bc3fc26d2441b3920', '194b165999c28287bcd5e18b8392a4144b4581743e32e019fe12db33e6220145');
INSERT INTO public.system_audit_log VALUES ('log_6ad2bdbad1de48.02780242', '2026-10-16 20:13:46', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''adrusss'' (C.I: V-30469331, Rol: Estudiantes).', 'adrusss (ID: 17)', '::1', '194b165999c28287bcd5e18b8392a4144b4581743e32e019fe12db33e6220145', '94196a4cc108731b8ea1c231a4187d3058c9fafb1b3225ff1865c6936ce15672');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c5580b3c67.34284882', '2026-10-16 20:46:16', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''adrusss'' (ID: 17) cerró su sesión voluntariamente.', 'adrusss (ID: 17)', '::1', '94196a4cc108731b8ea1c231a4187d3058c9fafb1b3225ff1865c6936ce15672', 'd730f3cd83559885424d734755e3052c5f6609597885b8512c130414e9efbb48');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c55cbe9bb3.16363486', '2026-10-16 20:46:20', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-32618284''.', 'Anónimo / Sistema', '::1', 'd730f3cd83559885424d734755e3052c5f6609597885b8512c130414e9efbb48', '679dc2532ae3ca6d05d3387d7f35d8baa26183f7704487be9da43ce6dec7bca3');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c562c11546.93575883', '2026-10-16 20:46:26', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '679dc2532ae3ca6d05d3387d7f35d8baa26183f7704487be9da43ce6dec7bca3', '02bd47c5ddba7cff3c9444def9f9b3fc299709afdd0885217029d6d3d03b6d35');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c916a96ec1.90966202', '2026-10-16 21:02:14', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '02bd47c5ddba7cff3c9444def9f9b3fc299709afdd0885217029d6d3d03b6d35', 'f12af62c6c4094a4a4e60f361c3e98a8e7c6edf7df2f7c7488536d8f9c3c4d78');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c91acb99f2.89759033', '2026-10-16 21:02:18', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'f12af62c6c4094a4a4e60f361c3e98a8e7c6edf7df2f7c7488536d8f9c3c4d78', 'b797515d0225f72e9e152a0c78e3a42bac2f5616a041da9aee8af227f12de494');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c940a49b02.36494893', '2026-10-16 21:02:56', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'b797515d0225f72e9e152a0c78e3a42bac2f5616a041da9aee8af227f12de494', '4a3d50016b3708a1ad20878df9a899ef6f2964baa4104aa69e6e1795df114489');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c94b06ae56.28586468', '2026-10-16 21:03:07', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', '4a3d50016b3708a1ad20878df9a899ef6f2964baa4104aa69e6e1795df114489', '6ca53dc4de108faed34d1b64674a954faa2649b9e58a3b2e22fc278831b679bd');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c9c2f34e96.73116550', '2026-10-16 21:05:06', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '6ca53dc4de108faed34d1b64674a954faa2649b9e58a3b2e22fc278831b679bd', '90ccdb9b1a60a1be5b270807db710543326d3f7a9042ed7d9617a0ce6e90d23c');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c9c6c64a15.88880113', '2026-10-16 21:05:10', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '90ccdb9b1a60a1be5b270807db710543326d3f7a9042ed7d9617a0ce6e90d23c', 'de7461d600d873d2387d824ab2b4df1793991941b39b8ab3eb0893452f1ba4d1');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c9d20cb519.86511983', '2026-10-16 21:05:22', 'WARNING', 'Autenticacion', 'Acceso Denegado Mantenimiento', 'Acceso denegado por mantenimiento para ''adrusss'' (C.I: V-30469331).', 'Anónimo / Sistema', '::1', 'de7461d600d873d2387d824ab2b4df1793991941b39b8ab3eb0893452f1ba4d1', 'fb780617ec395ee966402702c2108a47a033398211c22d8f031937026b2afb4b');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c9dfa79a47.43055438', '2026-10-16 21:05:35', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-32618284''.', 'Anónimo / Sistema', '::1', 'fb780617ec395ee966402702c2108a47a033398211c22d8f031937026b2afb4b', 'afd2cafbe28d27b5e40197a4247bbe74fb6bf84ec62a27024c4ed1f3864546d5');
INSERT INTO public.system_audit_log VALUES ('log_6ad2c9e846fa85.98632143', '2026-10-16 21:05:44', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'afd2cafbe28d27b5e40197a4247bbe74fb6bf84ec62a27024c4ed1f3864546d5', '87e2eec2483db1645632d54221c1e7add90712a060c27bd3a137d181a8b0eccd');
INSERT INTO public.system_audit_log VALUES ('log_6ad2ca2b922116.12044732', '2026-10-16 21:06:51', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '87e2eec2483db1645632d54221c1e7add90712a060c27bd3a137d181a8b0eccd', '6fc1f90b1c783ba18c4599f0284ef1617d545e71f559653f5dd5f0c084f2fc8e');
INSERT INTO public.system_audit_log VALUES ('log_6ad37da97681c8.15530723', '2026-10-17 09:52:41', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '6fc1f90b1c783ba18c4599f0284ef1617d545e71f559653f5dd5f0c084f2fc8e', '9b754f6eaaa4e9ee2ed6d3a22f2c295dc325fa6488fb8038c698986c12ab095b');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dc61a8a43.25928935', '2026-10-17 09:53:10', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #201 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '9b754f6eaaa4e9ee2ed6d3a22f2c295dc325fa6488fb8038c698986c12ab095b', '3dc8dc5dddf21c8fa5284a175c7b2aaeb940001129a35a910be7781b33f4298f');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dca94fba2.08119769', '2026-10-17 09:53:14', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #200 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '3dc8dc5dddf21c8fa5284a175c7b2aaeb940001129a35a910be7781b33f4298f', '34435f4db496c4323c1815c3cc868c6192b3ac4089f7b991b644be732e5df0c7');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dce3a0594.75155903', '2026-10-17 09:53:18', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #192 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '34435f4db496c4323c1815c3cc868c6192b3ac4089f7b991b644be732e5df0c7', '8191def86424604ad9d2189075c4832a6bd9d35b5b9e7f25aef5ca48c27faf61');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dd184ce23.75328579', '2026-10-17 09:53:21', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #186 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '8191def86424604ad9d2189075c4832a6bd9d35b5b9e7f25aef5ca48c27faf61', '35b9d0a3da9c02b4e2c0b98312512b767e8bfe02caf598e0ac38d2b409d8db1a');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dd59ed355.78229903', '2026-10-17 09:53:25', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #179 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '35b9d0a3da9c02b4e2c0b98312512b767e8bfe02caf598e0ac38d2b409d8db1a', '6adfc60226f22fa6612ebd45bea1fc1854734174e5ff78d2efd90992ea0877f4');
INSERT INTO public.system_audit_log VALUES ('log_6ad37dd8dee1a2.06567559', '2026-10-17 09:53:28', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #178 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '6adfc60226f22fa6612ebd45bea1fc1854734174e5ff78d2efd90992ea0877f4', '7003412fb26c4883fbc798a721233ea64cc05da5aab7eb8c33385d8c4d8ca91f');
INSERT INTO public.system_audit_log VALUES ('log_6ad37ddc16ce85.07553924', '2026-10-17 09:53:32', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #177 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '7003412fb26c4883fbc798a721233ea64cc05da5aab7eb8c33385d8c4d8ca91f', '8599283a1593c81c09c4d0f15188bb0406cf628fdb66e39b118e60bf4d654ec4');
INSERT INTO public.system_audit_log VALUES ('log_6ad37ddf979d17.39522816', '2026-10-17 09:53:35', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #176 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '8599283a1593c81c09c4d0f15188bb0406cf628fdb66e39b118e60bf4d654ec4', '7b253dd9621bfb68536e868d0e4fe820eba7dd4be03f9aa12ece9cab65ebdde6');
INSERT INTO public.system_audit_log VALUES ('log_6ad37de54f1e74.90589871', '2026-10-17 09:53:41', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #175 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '7b253dd9621bfb68536e868d0e4fe820eba7dd4be03f9aa12ece9cab65ebdde6', 'a4ca01156786c57ec6c6b06f90990beea970beec226e82ee9e0c27d39e5e4694');
INSERT INTO public.system_audit_log VALUES ('log_6ad37de9293d42.48391073', '2026-10-17 09:53:45', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #174 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'a4ca01156786c57ec6c6b06f90990beea970beec226e82ee9e0c27d39e5e4694', '4a4f9cd15d5784eb29dd6d2c5d15d3284ab839714555b0c3cc67e7a509d38532');
INSERT INTO public.system_audit_log VALUES ('log_6ad37ded8f8920.89704587', '2026-10-17 09:53:49', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #191 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '4a4f9cd15d5784eb29dd6d2c5d15d3284ab839714555b0c3cc67e7a509d38532', '7788a62239d57a491fbb420e8d71a010827cbe5a4f2eac2857595691c9a63013');
INSERT INTO public.system_audit_log VALUES ('log_6ad37df1259985.91923684', '2026-10-17 09:53:53', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #189 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '7788a62239d57a491fbb420e8d71a010827cbe5a4f2eac2857595691c9a63013', '9023f3bdf300e49558d1edb33cca45b001da52f823689844af015cdf81725311');
INSERT INTO public.system_audit_log VALUES ('log_6ad37df44baac7.63120450', '2026-10-17 09:53:56', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #180 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '9023f3bdf300e49558d1edb33cca45b001da52f823689844af015cdf81725311', 'c69a55deaac35b19c60e4c84462f12fbe6df13dd254f3b58d871b5c6001d41f3');
INSERT INTO public.system_audit_log VALUES ('log_6ad37df7f1cc04.25837417', '2026-10-17 09:53:59', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #173 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'c69a55deaac35b19c60e4c84462f12fbe6df13dd254f3b58d871b5c6001d41f3', '8b4807d22ca429e2559e313cc8beef04efc870569e10998101f8773074a9ff32');
INSERT INTO public.system_audit_log VALUES ('log_6ad3aa320a5552.24563493', '2026-10-17 13:02:42', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '8b4807d22ca429e2559e313cc8beef04efc870569e10998101f8773074a9ff32', '63b32f6e4cb347fa09173bccca5f36eb425eea68fce9331394e7d4cc5f0e3627');
INSERT INTO public.system_audit_log VALUES ('log_6ad3aa40845084.28305819', '2026-10-17 13:02:56', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', '63b32f6e4cb347fa09173bccca5f36eb425eea68fce9331394e7d4cc5f0e3627', '709da9dea5028cc7c8cacb7d4980e2c95baf8b375b1851940acb343c8819ceb4');
INSERT INTO public.system_audit_log VALUES ('log_6ad3ac67086742.81622925', '2026-10-17 13:12:07', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0'' (ID: #202).', 'Miguel González (ID: 7)', '::1', '709da9dea5028cc7c8cacb7d4980e2c95baf8b375b1851940acb343c8819ceb4', 'fc603e6cc615038f5fc8c84ca81c0d7c57ea8e8b1f33a2748de35f8b562b63c3');
INSERT INTO public.system_audit_log VALUES ('log_6ad3b0d5c09013.53750342', '2026-10-17 13:31:01', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', 'fc603e6cc615038f5fc8c84ca81c0d7c57ea8e8b1f33a2748de35f8b562b63c3', '812d22c1050ea77688cbf033daac453e1c18d9ff7c45c7aed63cbe8ac8ece932');
INSERT INTO public.system_audit_log VALUES ('log_6ad3bc089e0652.63638507', '2026-10-17 14:18:48', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '812d22c1050ea77688cbf033daac453e1c18d9ff7c45c7aed63cbe8ac8ece932', '05f8e49a481eadafe4260d315e3508929d422e790b214d90ce18f2d0dadfcc7a');
INSERT INTO public.system_audit_log VALUES ('log_6ad3bc31642613.04180463', '2026-10-17 14:19:29', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '05f8e49a481eadafe4260d315e3508929d422e790b214d90ce18f2d0dadfcc7a', '350363f7e0384c4a431ab5d41b14744f0654a7233677cc3f43e93dc0cc62c0fe');
INSERT INTO public.system_audit_log VALUES ('log_6ad53ec1f2e046.48011707', '2026-10-18 17:48:49', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '350363f7e0384c4a431ab5d41b14744f0654a7233677cc3f43e93dc0cc62c0fe', 'd4e583207cb1fe85758eead84f3d86e839eb60a1e03210bb352e15af9b39f224');
INSERT INTO public.system_audit_log VALUES ('log_6ad53ef71d6345.89766420', '2026-10-18 17:49:43', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'd4e583207cb1fe85758eead84f3d86e839eb60a1e03210bb352e15af9b39f224', 'e110be2bc8e47ae7ecf7b9ef70c925746f4077a210c882923d1d9cae8458cdb1');
INSERT INTO public.system_audit_log VALUES ('log_6ad53ef829d642.62228730', '2026-10-18 17:49:44', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'e110be2bc8e47ae7ecf7b9ef70c925746f4077a210c882923d1d9cae8458cdb1', '6f802a78bf29b994d29989407b7ec0601beeb678aa6f270ce154cd74c74f4e38');
INSERT INTO public.system_audit_log VALUES ('log_6ad53ef8d9df54.04484582', '2026-10-18 17:49:44', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '6f802a78bf29b994d29989407b7ec0601beeb678aa6f270ce154cd74c74f4e38', '309f6d2cbe886eca1bc96abf0f9b847e7c41bcd3560a4398109d54125b49cde6');
INSERT INTO public.system_audit_log VALUES ('log_6ad5515907cc08.97129299', '2026-10-18 19:08:09', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '309f6d2cbe886eca1bc96abf0f9b847e7c41bcd3560a4398109d54125b49cde6', '52a8a8aea1335280a6df8e454ca66659923c92097ad62f36ea745eda785b5298');
INSERT INTO public.system_audit_log VALUES ('log_6ad5535819e446.86288344', '2026-10-18 19:16:40', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #202 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '52a8a8aea1335280a6df8e454ca66659923c92097ad62f36ea745eda785b5298', '2ba024663aaf069babf9f57f6b662d40eca00fb929d6bb024e89076879847371');
INSERT INTO public.system_audit_log VALUES ('log_6ad5595fbc6500.61620719', '2026-10-18 19:42:23', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '2ba024663aaf069babf9f57f6b662d40eca00fb929d6bb024e89076879847371', 'e36357d7c63babef8ee701161f16afa4805e6643e72ee78dd1b44af17016983e');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b4ef1cd626.86825564', '2026-10-19 20:25:19', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'e36357d7c63babef8ee701161f16afa4805e6643e72ee78dd1b44af17016983e', 'f4cd0848d02e39ee714c89718bf312d92710403a15576f8ca945f3ab998b3fc1');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b6d2931ac4.88979879', '2026-10-19 20:33:22', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A'' (ID: #204).', 'Miguel González (ID: 7)', '::1', 'f4cd0848d02e39ee714c89718bf312d92710403a15576f8ca945f3ab998b3fc1', '6fddba431309f0281f02d1ecb876d210d9a6648b9c18112b80d86f2b309f3306');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b7098d6379.25339448', '2026-10-19 20:34:17', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 1 proyectos con la operación: activar.', 'Miguel González (ID: 7)', '::1', '6fddba431309f0281f02d1ecb876d210d9a6648b9c18112b80d86f2b309f3306', '5684aff1bd62f6af60bc0bdfdfa4dedc81ece37c1ae39cc81ae877f6049a256f');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b70ed91945.19203168', '2026-10-19 20:34:22', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 1 proyectos con la operación: ocultar.', 'Miguel González (ID: 7)', '::1', '5684aff1bd62f6af60bc0bdfdfa4dedc81ece37c1ae39cc81ae877f6049a256f', '009647470c0eb1502786649e204ecf9bb7b1a71caacda4a1c6ad602a5b8596b1');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b712ce13b8.24160772', '2026-10-19 20:34:26', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #204 cambiado a estado: Visible.', 'Miguel González (ID: 7)', '::1', '009647470c0eb1502786649e204ecf9bb7b1a71caacda4a1c6ad602a5b8596b1', 'cff8dd16682e36bada4cafe92dece15aaf885e697945970b2d95c3290dd31313');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b727766f40.57301145', '2026-10-19 20:34:47', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 1 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', 'cff8dd16682e36bada4cafe92dece15aaf885e697945970b2d95c3290dd31313', '7cfa02fbdb8fed1052f643c1a36da413909d0f28ebd2dabd7fc3ce730e1e9093');
INSERT INTO public.system_audit_log VALUES ('log_6ad6b754a7c9f2.94660053', '2026-10-19 20:35:32', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A'' (ID: #205).', 'Miguel González (ID: 7)', '::1', '7cfa02fbdb8fed1052f643c1a36da413909d0f28ebd2dabd7fc3ce730e1e9093', '2295440d64d1ce945a08c2128acd57eb2a6ec3e0e037c3f675433dc0dd4c5c55');
INSERT INTO public.system_audit_log VALUES ('log_6ad6bf44db7e20.58261661', '2026-10-19 21:09:24', 'CRITICAL', 'RepositorioPST', 'Purga Total del Catálogo PST', 'Catálogo vaciado con autorización de SuperAdmin Miguel González con cédula 32621284. Se eliminaron 1 proyectos y se liberaron 2.02 MB.', 'Miguel González (ID: 7)', '::1', '2295440d64d1ce945a08c2128acd57eb2a6ec3e0e037c3f675433dc0dd4c5c55', '4f16910d2e67a2bb63410176a748951c329819e695bd959c689cb3fcaa84cf5e');
INSERT INTO public.system_audit_log VALUES ('log_6ad6bf53bce302.24806094', '2026-10-19 21:09:39', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A'' (ID: #206).', 'Miguel González (ID: 7)', '::1', '4f16910d2e67a2bb63410176a748951c329819e695bd959c689cb3fcaa84cf5e', 'd2561afe9124127b03df5e660b564f35097c1bdf397974b0e48e8e6dd71ed3b1');
INSERT INTO public.system_audit_log VALUES ('log_6ad6bf5bcadc59.87423954', '2026-10-19 21:09:47', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 1 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', 'd2561afe9124127b03df5e660b564f35097c1bdf397974b0e48e8e6dd71ed3b1', '48dc44e82843717b77232d0001f8b59352502bbc58e201f5448eb93ef45c9059');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c05448bd42.85179917', '2026-10-19 21:13:56', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''PROYECTO T4 Sistema Consejo Municipal'' (ID: #207).', 'Miguel González (ID: 7)', '::1', '48dc44e82843717b77232d0001f8b59352502bbc58e201f5448eb93ef45c9059', '0a79f79ab94360112413e69a2dba0fadce2c958b957f0ce1c207207727a3c887');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c073cd5708.97738243', '2026-10-19 21:14:27', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 1 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', '0a79f79ab94360112413e69a2dba0fadce2c958b957f0ce1c207207727a3c887', 'e38bc91b5d9698572cf305b1570b046af3ec18c7bc8c6c9ba6f13acd7cd7f12f');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c4b7759c91.29219398', '2026-10-19 21:32:39', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 3 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', 'e38bc91b5d9698572cf305b1570b046af3ec18c7bc8c6c9ba6f13acd7cd7f12f', 'd04a034a08f3d18949a57bcb19e9a0faec6623b2fe1a404984a5d70aea686777');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c4f9aea3e7.39083940', '2026-10-19 21:33:45', 'INFO', 'RepositorioPST', 'Limpieza de Temporales', 'Se eliminaron 6 archivos temporales liberando 45.46 MB.', 'Miguel González (ID: 7)', '::1', 'd04a034a08f3d18949a57bcb19e9a0faec6623b2fe1a404984a5d70aea686777', 'db396137385b76a8a5dc31fde1a0b03882db8fb61eb0f8eb9fc89b5b8c8989ce');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c7d53451f4.10111587', '2026-10-19 21:45:57', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 5 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', 'db396137385b76a8a5dc31fde1a0b03882db8fb61eb0f8eb9fc89b5b8c8989ce', '18cf387a0191fe4505382bc8c395908f76c799855a9c8a3521cae59be00d1117');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c80895c972.63356618', '2026-10-19 21:46:48', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 5 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', '18cf387a0191fe4505382bc8c395908f76c799855a9c8a3521cae59be00d1117', 'ce53087f5c3df1f7500b7d9c786c3983f03b3a24318c9e9a866d5119325698bc');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c84b428792.40140352', '2026-10-19 21:47:55', 'INFO', 'RepositorioPST', 'Acción por Lote', 'Se procesaron 3 proyectos con la operación: eliminar.', 'Miguel González (ID: 7)', '::1', 'ce53087f5c3df1f7500b7d9c786c3983f03b3a24318c9e9a866d5119325698bc', '182f6500e036d99c0c82a392321713a4b47adce8f28ca66ed0b5349b7c167ca4');
INSERT INTO public.system_audit_log VALUES ('log_6ad6c8528e96a9.84251749', '2026-10-19 21:48:02', 'INFO', 'RepositorioPST', 'Limpieza de Temporales', 'Se eliminaron 1 archivos temporales liberando 1.63 MB.', 'Miguel González (ID: 7)', '::1', '182f6500e036d99c0c82a392321713a4b47adce8f28ca66ed0b5349b7c167ca4', '4f7606f6f71495b974e4f8fe111606b9919b0ebdeba8ab6878269fd76d2f8788');
INSERT INTO public.system_audit_log VALUES ('log_6ad6ca44b8a012.63216679', '2026-10-19 21:56:20', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: backup_ciidi_2026-10-16_17-54-08.sql.gz', 'Miguel González (ID: 7)', '::1', '4f7606f6f71495b974e4f8fe111606b9919b0ebdeba8ab6878269fd76d2f8788', '51015c6e2ed671ceea933513e0daf196de86ee51861b30ac352695ee253d9e56');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cedf660dc7.34162762', '2026-10-19 22:15:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '127.0.0.1', '51015c6e2ed671ceea933513e0daf196de86ee51861b30ac352695ee253d9e56', 'acc30bac0d87d6175e6b490975e583fd5c44c66f63d9af0af44d3cd16b44196d');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cef8290581.46844022', '2026-10-19 22:16:24', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '127.0.0.1', 'acc30bac0d87d6175e6b490975e583fd5c44c66f63d9af0af44d3cd16b44196d', '1c72007129182469e6d247cbc37907709abfdb1a1048c32198bc4f3204d7c4fd');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf0763c5a3.56079415', '2026-10-19 22:16:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '100.76.167.24', '1c72007129182469e6d247cbc37907709abfdb1a1048c32198bc4f3204d7c4fd', 'ce71f97b5d3ef54ef64009e5eefe8b192680c567d4b337a2c1c234db7dbbd2dc');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf0c59efa0.76744287', '2026-10-19 22:16:44', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '100.76.167.24', 'ce71f97b5d3ef54ef64009e5eefe8b192680c567d4b337a2c1c234db7dbbd2dc', 'e3996f620b4e42b67bb75a7fccab27f84dc2b63fd7bb4bb5be31f0a81ced307d');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf19dbd782.34164965', '2026-10-19 22:16:57', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''adrusss'' (C.I: V-30469331).', 'Anónimo / Sistema', '100.76.167.24', 'e3996f620b4e42b67bb75a7fccab27f84dc2b63fd7bb4bb5be31f0a81ced307d', '40002bfa3fa3039eb1de59ad81cd5580659e9b229b99e9bf29bb07fb55794831');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf1e389ab3.68647217', '2026-10-19 22:17:02', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''V-30536364''.', 'Anónimo / Sistema', '100.73.154.97', '40002bfa3fa3039eb1de59ad81cd5580659e9b229b99e9bf29bb07fb55794831', '745d252ca533315fbc2c189a489ae48b1f80b3ae8faf9cd3d0571ba026a6db2c');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf26d0b0c3.02030193', '2026-10-19 22:17:10', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '100.76.167.24', '745d252ca533315fbc2c189a489ae48b1f80b3ae8faf9cd3d0571ba026a6db2c', '2836f0d7f024267fff8ba42bdc89f32081e33c36724d7edb7b7a43976503c19f');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf3fd18238.02467412', '2026-10-19 22:17:35', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. V-30469331 (adrusss). Se forzó cambio de contraseña. Sesión remota revocada.', 'Miguel González (ID: 7)', '100.76.167.24', '2836f0d7f024267fff8ba42bdc89f32081e33c36724d7edb7b7a43976503c19f', 'bdf76efc2ca6f52f3fb2843d801595ae53a558dd809fef745a896228d9ab14c4');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf743d81a4.59274431', '2026-10-19 22:18:28', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. V-30536364 (ale). Se forzó cambio de contraseña. Sesión remota revocada.', 'Miguel González (ID: 7)', '100.76.167.24', 'bdf76efc2ca6f52f3fb2843d801595ae53a558dd809fef745a896228d9ab14c4', '035d6c98ca789143af471d50d103660be8ea4ee225e034270edc8eb2fbfb88bc');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf77e06ce0.60141660', '2026-10-19 22:18:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '100.76.167.24', '035d6c98ca789143af471d50d103660be8ea4ee225e034270edc8eb2fbfb88bc', '7ff94ff08a046e969a17b4dcff436b51e7cdf7b70fe70772b3446d402dc9d379');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf7f752d64.57542116', '2026-10-19 22:18:39', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''ale'' (C.I: V-30536364, Rol: Super Administrador).', 'ale (ID: 4)', '100.73.154.97', '7ff94ff08a046e969a17b4dcff436b51e7cdf7b70fe70772b3446d402dc9d379', 'c31a9a879f678a5848bc9b6e67f864557cfb38cc76e58f197f28576ebc307d89');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf80a15c52.71926910', '2026-10-19 22:18:40', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''adrusss'' (C.I: V-30469331).', 'Anónimo / Sistema', '100.76.167.24', 'c31a9a879f678a5848bc9b6e67f864557cfb38cc76e58f197f28576ebc307d89', 'eb9e16d3b3c5df0f59cd6c41241bb4c4a5751d132ba02b833cdb9c35c555cef4');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf8d987046.45565272', '2026-10-19 22:18:53', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''adrusss'' (C.I: V-30469331, Rol: Super Administrador).', 'adrusss (ID: 17)', '100.76.167.24', 'eb9e16d3b3c5df0f59cd6c41241bb4c4a5751d132ba02b833cdb9c35c555cef4', '5e69b7ff77b724357130b891b8163a60c9c097c8486b2e5b75dbf013a098cd35');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf991a6859.68585047', '2026-10-19 22:19:05', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Ale'' (ID: 4).', 'Ale (ID: 4)', '100.73.154.97', '5e69b7ff77b724357130b891b8163a60c9c097c8486b2e5b75dbf013a098cd35', 'e427a5f02a7698f29e78182b534ce05c8b42e6fdcda6cc67134c45e301290c13');
INSERT INTO public.system_audit_log VALUES ('log_6ad6cf9d576168.11835332', '2026-10-19 22:19:09', 'INFO', 'Autenticacion', 'Nombre Modificado por SuperAdmin', 'El superusuario actualizó su nombre a ''Andrus Ramirez'' (ID: 17).', 'Andrus Ramirez (ID: 17)', '100.76.167.24', 'e427a5f02a7698f29e78182b534ce05c8b42e6fdcda6cc67134c45e301290c13', 'ad0f0872a08617e43740a8e18021100ebe01a0daf63cec5fe3d199c43560317b');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d027ab2b73.02789717', '2026-10-19 22:21:27', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '127.0.0.1', 'ad0f0872a08617e43740a8e18021100ebe01a0daf63cec5fe3d199c43560317b', '53b4eaaa47521e59abae42a1c15e4cf57a7021e0bf3cf6136b994b14502426db');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d032579150.06843341', '2026-10-19 22:21:38', 'ERROR', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ERROR (0.02ms): El archivo de script ''script_tarea_1790301553_528.php'' no existe en storage/scheduler/scripts/.', 'Miguel González (ID: 7)', '127.0.0.1', '53b4eaaa47521e59abae42a1c15e4cf57a7021e0bf3cf6136b994b14502426db', '814250fffc5cba2b14e09e3907a7d529ee17d62c09bac5e5eacd4e05d1f8c517');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d0b367a355.25086538', '2026-10-19 22:23:47', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1792463027_413) guardada correctamente.', 'Miguel González (ID: 7)', '127.0.0.1', '814250fffc5cba2b14e09e3907a7d529ee17d62c09bac5e5eacd4e05d1f8c517', '09f2cec8fa0ef0be84c5c35ce73a34ef8a4e8b167043682d3d27898e5ae50fd6');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d0b8c44e77.91622438', '2026-10-19 22:23:52', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (60.33ms): Comando ejecutado con éxito (Código 0).', 'Miguel González (ID: 7)', '127.0.0.1', '09f2cec8fa0ef0be84c5c35ce73a34ef8a4e8b167043682d3d27898e5ae50fd6', 'f3ac7274058215bd0a20a5398c0d9de1aeedab7c690ddbf40fcdb436cde04c75');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d46d889003.68244536', '2026-10-19 22:39:41', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Ale (ID: 4)', '100.73.154.97', 'f3ac7274058215bd0a20a5398c0d9de1aeedab7c690ddbf40fcdb436cde04c75', '6db492b5e256b2eec2539d5a63efd671b05c8c72200e4079b1f7c7c5c36a8710');
INSERT INTO public.system_audit_log VALUES ('log_6ad6d9a52ca3e8.90644690', '2026-10-19 23:01:57', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO'': Ya existe un proyecto registrado con el título: ''IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO''', 'Ale (ID: 4)', '100.73.154.97', '6db492b5e256b2eec2539d5a63efd671b05c8c72200e4079b1f7c7c5c36a8710', '27a036e6e370a15091ad455182d3ee41bc5367ec235de1d2b134675c2e3d3d95');
INSERT INTO public.system_audit_log VALUES ('log_6ad6db32745bd3.15000346', '2026-10-19 23:08:34', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (314.68ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', '27a036e6e370a15091ad455182d3ee41bc5367ec235de1d2b134675c2e3d3d95', '0cc1526c0aac22bcb1f9e017a3630c42602fc79000ccc1979a1f7a66697d7b2e');
INSERT INTO public.system_audit_log VALUES ('log_6ad6dce1a30a68.62917786', '2026-10-19 23:15:45', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Ale (ID: 4)', '100.73.154.97', '0cc1526c0aac22bcb1f9e017a3630c42602fc79000ccc1979a1f7a66697d7b2e', '1a46b0fb99176bed802f30c06ffbf9a72fb6f5c28a6801e0f4c9ed357672c422');
INSERT INTO public.system_audit_log VALUES ('log_6ad6dd246cb0b7.91367325', '2026-10-19 23:16:52', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'No se pudo extraer texto del documento. Asegúrese de que el archivo no esté protegido o escaneado como imagen pura sin OCR.', 'Ale (ID: 4)', '100.73.154.97', '1a46b0fb99176bed802f30c06ffbf9a72fb6f5c28a6801e0f4c9ed357672c422', '75398f9a937c0eedfa414c96a9d81089201bc8960f1e14b3f28dea7fa676053a');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e2a2799324.04083795', '2026-10-19 23:40:18', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #277 modificado exitosamente: ''Soporte Técnico de Equipos y Capacitación a Usuarios del Laboratorio 1 de PNFI de la Universidad Politécnica Territorial ¨Mario Briceño Iragorry¨''.', 'Andrus Ramirez (ID: 17)', '100.76.167.24', '75398f9a937c0eedfa414c96a9d81089201bc8960f1e14b3f28dea7fa676053a', '5e3d8cc1e842eaf5ba67ebe450b6f5876091e74266fdbe475f273647b4c88b47');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e3e7a03910.06189637', '2026-10-19 23:45:43', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (417.43ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', '5e3d8cc1e842eaf5ba67ebe450b6f5876091e74266fdbe475f273647b4c88b47', '06f38e1dea87c1abced1ee0e4bcf74dfeb5c37422a6b3109d79d9889d3eb51fa');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e3ffee8e49.20683821', '2026-10-19 23:46:07', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (206.93ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', '06f38e1dea87c1abced1ee0e4bcf74dfeb5c37422a6b3109d79d9889d3eb51fa', 'a004c3be478aff71d197df4774ab504085ee51ca20046d9f55a27faa61eaa693');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e4128e2723.34373795', '2026-10-19 23:46:26', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (216.48ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', 'a004c3be478aff71d197df4774ab504085ee51ca20046d9f55a27faa61eaa693', '157e843cfe7cd82684893c615ee96392cbb8e1b5c54c5c504c22d9c11b9fdfb8');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e429760d93.01441514', '2026-10-19 23:46:49', 'INFO', 'SuperAdmin', 'Guardar Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'' (tarea_1792463027_413) guardada correctamente.', 'Ale (ID: 4)', '100.73.154.97', '157e843cfe7cd82684893c615ee96392cbb8e1b5c54c5c504c22d9c11b9fdfb8', '065ce9765469e488b54166a2704975fc9b54f0059f431cc0ed09e2a836a1e6be');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e4356ea176.00575619', '2026-10-19 23:47:01', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (204.44ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', '065ce9765469e488b54166a2704975fc9b54f0059f431cc0ed09e2a836a1e6be', 'fc8e7bfd08ba3407ed088b806b2b9bce088f423f6f4a3c4526593928b6d8c503');
INSERT INTO public.system_audit_log VALUES ('log_6ad6e9bb292d50.21722776', '2026-10-20 00:10:35', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Generar Embeddings Semánticos'': ÉXITO (241.21ms): Comando ejecutado con éxito (Código 0).', 'Ale (ID: 4)', '100.73.154.97', 'fc8e7bfd08ba3407ed088b806b2b9bce088f423f6f4a3c4526593928b6d8c503', '027eef74ff111663dfc533709634de7f2423e9133db86fe1bfacc250009be571');


--
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.telemetria_cache VALUES (1, '{"timestamp": 1792465891, "storage_mb": 612.42, "files_count": 106}');


--
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tipo_recurso VALUES (1, 'PST / Trabajo de Grado', 'Proyectos Socio-Tecnológicos y Tesis');
INSERT INTO public.tipo_recurso VALUES (2, 'Investigación Docente', 'Papers y artículos de investigación del personal académico');
INSERT INTO public.tipo_recurso VALUES (3, 'Material de Apoyo / Didáctico', 'Recursos adicionales para estudiantes');


--
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tipo_tutor VALUES (1, 'Director', 'Director principal del proyecto');
INSERT INTO public.tipo_tutor VALUES (2, 'Coordinador', 'Asesor metodológico');
INSERT INTO public.tipo_tutor VALUES (3, 'Tutor Académico', 'Especialista en el área');
INSERT INTO public.tipo_tutor VALUES (4, 'Tutor Comunitario', 'Representante de la comunidad');


--
-- Data for Name: trayectos; Type: TABLE DATA; Schema: public; Owner: miki
--

INSERT INTO public.trayectos VALUES (1, 1, 'Trayecto I', 1, 'Trayecto I del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (2, 1, 'Trayecto II', 2, 'Trayecto II del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (3, 1, 'Trayecto III', 3, 'Trayecto III del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (4, 1, 'Trayecto IV', 4, 'Trayecto IV del PNF en Informática', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (5, 2, 'Trayecto I', 1, 'Trayecto I del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (6, 2, 'Trayecto II', 2, 'Trayecto II del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (7, 2, 'Trayecto III', 3, 'Trayecto III del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (8, 2, 'Trayecto IV', 4, 'Trayecto IV del PNF en Electricidad', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (9, 3, 'Trayecto I', 1, 'Trayecto I del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (10, 3, 'Trayecto II', 2, 'Trayecto II del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (11, 3, 'Trayecto III', 3, 'Trayecto III del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (12, 3, 'Trayecto IV', 4, 'Trayecto IV del PNF en Administración', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (13, 4, 'Trayecto I', 1, 'Trayecto I del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (14, 4, 'Trayecto II', 2, 'Trayecto II del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (15, 4, 'Trayecto III', 3, 'Trayecto III del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (16, 4, 'Trayecto IV', 4, 'Trayecto IV del PNF en Agroalimentación', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (17, 5, 'Trayecto I', 1, 'Trayecto I del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (18, 5, 'Trayecto II', 2, 'Trayecto II del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (19, 5, 'Trayecto III', 3, 'Trayecto III del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');
INSERT INTO public.trayectos VALUES (20, 5, 'Trayecto IV', 4, 'Trayecto IV del PNF en Construcción Civil', true, '2026-09-28 23:01:27.263246');


--
-- Data for Name: tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tutores VALUES (1, 'Lando', 'V-12345678');
INSERT INTO public.tutores VALUES (2, 'Mikeyisito', 'V-18765432');
INSERT INTO public.tutores VALUES (3, 'María Antonieta Pérez', 'V-15444333');
INSERT INTO public.tutores VALUES (7, 'aaaaa aaa', '22222');
INSERT INTO public.tutores VALUES (8, 'aaa aaaa', '33333');
INSERT INTO public.tutores VALUES (9, 'aaaaa aaaaaa', '444444');
INSERT INTO public.tutores VALUES (10, 'Karina Gutiérrez', '2222231312');
INSERT INTO public.tutores VALUES (11, 'asdasdas faasdas', '12312312');
INSERT INTO public.tutores VALUES (12, 'Karina Gutiérrez', '3123123');
INSERT INTO public.tutores VALUES (13, 'Prof. Tutor Académico Prueba', 'V-15888999');
INSERT INTO public.tutores VALUES (14, 'Dr. Asesor Edumático', 'V-12000333');
INSERT INTO public.tutores VALUES (15, 'Prof. Asesor', 'V-14555666');
INSERT INTO public.tutores VALUES (16, 'Prof. Asesor Prueba', 'V-11223344');
INSERT INTO public.tutores VALUES (17, 'Karla Rodríguez', NULL);
INSERT INTO public.tutores VALUES (18, 'Karina Araujo', NULL);
INSERT INTO public.tutores VALUES (19, 'Helen Gonzales', NULL);
INSERT INTO public.tutores VALUES (20, 'Msc Néstor Araujo', NULL);
INSERT INTO public.tutores VALUES (21, 'Msc Julio Abreu', NULL);
INSERT INTO public.tutores VALUES (22, 'Karina Gutierrez', NULL);
INSERT INTO public.tutores VALUES (25, 'asdasd', NULL);
INSERT INTO public.tutores VALUES (26, 'Ricardo Dos Santosss', NULL);
INSERT INTO public.tutores VALUES (27, 'Karina Gutiérrezxczxc', NULL);
INSERT INTO public.tutores VALUES (28, 'Ricardo Dos Santos', NULL);
INSERT INTO public.tutores VALUES (29, 'María Luisa Colmenares', NULL);
INSERT INTO public.tutores VALUES (30, 'Rossana Virgilio', NULL);
INSERT INTO public.tutores VALUES (31, 'Carlos Simancas', NULL);
INSERT INTO public.tutores VALUES (32, 'Tutor Prueba Academico', 'V-11111111');
INSERT INTO public.tutores VALUES (33, 'Tutor Prueba Institucional', 'V-22222222');
INSERT INTO public.tutores VALUES (34, 'Tutor Prueba Comunitario', 'V-33333333');
INSERT INTO public.tutores VALUES (35, 'Winston Méndez', NULL);
INSERT INTO public.tutores VALUES (36, 'Carmen Muchacho', NULL);
INSERT INTO public.tutores VALUES (37, 'Yajaira Franco', NULL);
INSERT INTO public.tutores VALUES (38, 'Mary Moreno', NULL);
INSERT INTO public.tutores VALUES (39, 'Estella Berríos', NULL);
INSERT INTO public.tutores VALUES (40, 'KarinAI', 'Karina');
INSERT INTO public.tutores VALUES (41, 'Gusman Muchacho', NULL);
INSERT INTO public.tutores VALUES (42, 'Diana Hernández', NULL);
INSERT INTO public.tutores VALUES (43, 'Drover Briceño', 'V-13896043');
INSERT INTO public.tutores VALUES (44, 'Rodolfo Blanco', 'V-15584551');
INSERT INTO public.tutores VALUES (45, 'Wesley Bentacourt', NULL);
INSERT INTO public.tutores VALUES (46, 'Jean Carlos Villarreal', NULL);
INSERT INTO public.tutores VALUES (47, 'Maria Leida Graterol', NULL);
INSERT INTO public.tutores VALUES (48, 'Dra. Wesley Betancourt', NULL);
INSERT INTO public.tutores VALUES (49, 'Bentancourt Wesley', NULL);
INSERT INTO public.tutores VALUES (50, 'Castillo Marily', NULL);
INSERT INTO public.tutores VALUES (51, 'Benítez Ledgin', NULL);
INSERT INTO public.tutores VALUES (52, 'Marzzia Gil', NULL);
INSERT INTO public.tutores VALUES (53, 'Ing. Karina Araujo', NULL);
INSERT INTO public.tutores VALUES (54, 'Yenny Araujo', NULL);
INSERT INTO public.tutores VALUES (55, 'Grisseth Araujo', NULL);
INSERT INTO public.tutores VALUES (56, 'Janneth Palomares', NULL);
INSERT INTO public.tutores VALUES (57, 'Dr. Ricardo Dos Santos', NULL);
INSERT INTO public.tutores VALUES (58, 'Lilia Flores', NULL);
INSERT INTO public.tutores VALUES (59, 'Wesley Bentancourt Gonzales', NULL);
INSERT INTO public.tutores VALUES (60, 'Homero De J Urbina R', NULL);
INSERT INTO public.tutores VALUES (61, 'Yoximar Rangel', NULL);
INSERT INTO public.tutores VALUES (62, 'María Mónica Mendoza', NULL);
INSERT INTO public.tutores VALUES (63, 'Jorge López', NULL);
INSERT INTO public.tutores VALUES (64, 'Miguel Morales', NULL);
INSERT INTO public.tutores VALUES (65, 'Rodríguez Karla', NULL);
INSERT INTO public.tutores VALUES (66, 'Dos Santos Ricardo', NULL);
INSERT INTO public.tutores VALUES (67, 'Durán Mariely', NULL);
INSERT INTO public.tutores VALUES (68, 'Gutiérrez Karina', NULL);
INSERT INTO public.tutores VALUES (69, 'Montilla Carlos', NULL);
INSERT INTO public.tutores VALUES (70, 'Yenni Hernández', NULL);
INSERT INTO public.tutores VALUES (71, 'José Gregorio Lugo', NULL);
INSERT INTO public.tutores VALUES (72, 'María Valecillos', NULL);
INSERT INTO public.tutores VALUES (73, 'Gabriel García', NULL);
INSERT INTO public.tutores VALUES (74, 'Yelitza Terán', NULL);
INSERT INTO public.tutores VALUES (75, 'Luis Mogollón', NULL);
INSERT INTO public.tutores VALUES (76, 'Yadisneidis Reverol', NULL);
INSERT INTO public.tutores VALUES (77, 'José Leonardo Fernández', NULL);
INSERT INTO public.tutores VALUES (78, 'Karina Rodriguez', NULL);
INSERT INTO public.tutores VALUES (79, 'Eduardo Peñaloza', NULL);
INSERT INTO public.tutores VALUES (80, 'Militza Victorá', NULL);
INSERT INTO public.tutores VALUES (81, 'Jenifer Rojas', NULL);
INSERT INTO public.tutores VALUES (82, 'Yajaira Torres', NULL);
INSERT INTO public.tutores VALUES (83, 'Yazmine Castellanos', NULL);
INSERT INTO public.tutores VALUES (84, 'Dra. Betancourt González Wesley', NULL);
INSERT INTO public.tutores VALUES (85, 'Méndez A. Winston', NULL);
INSERT INTO public.tutores VALUES (86, 'Lic. Rivera Morillo Walquirian del Valle', NULL);
INSERT INTO public.tutores VALUES (87, 'Montilla Elías', NULL);
INSERT INTO public.tutores VALUES (88, 'Marielys Torres', NULL);
INSERT INTO public.tutores VALUES (89, 'Eleida Blanco', NULL);
INSERT INTO public.tutores VALUES (90, 'Rubén Jiménez', NULL);
INSERT INTO public.tutores VALUES (91, 'Marlene Sosa', NULL);
INSERT INTO public.tutores VALUES (92, 'Jhonder Duran', NULL);
INSERT INTO public.tutores VALUES (93, 'Rosa Quintero', NULL);
INSERT INTO public.tutores VALUES (94, 'Araujo Darlin', NULL);
INSERT INTO public.tutores VALUES (95, 'Juan Daboin', NULL);
INSERT INTO public.tutores VALUES (96, 'Henrichs Gonzalez', NULL);
INSERT INTO public.tutores VALUES (97, 'Hernán Valera', NULL);
INSERT INTO public.tutores VALUES (98, 'Maily Nava', NULL);
INSERT INTO public.tutores VALUES (99, 'Darling Araujo', NULL);
INSERT INTO public.tutores VALUES (100, 'María Carmen Briceño', NULL);
INSERT INTO public.tutores VALUES (101, 'Doctora Yajaira Franco', NULL);
INSERT INTO public.tutores VALUES (102, 'Licenciada Egluibet Vásquez', NULL);
INSERT INTO public.tutores VALUES (103, 'Alvaro Castillo', NULL);
INSERT INTO public.tutores VALUES (104, 'Ray Arias', NULL);
INSERT INTO public.tutores VALUES (105, 'Jose Caldera', NULL);
INSERT INTO public.tutores VALUES (106, 'Juleiba Abreu', NULL);
INSERT INTO public.tutores VALUES (107, 'José Páez', NULL);
INSERT INTO public.tutores VALUES (108, 'Sugheil Araujo', NULL);
INSERT INTO public.tutores VALUES (109, 'Freddy Segovia', NULL);
INSERT INTO public.tutores VALUES (110, 'Rodríguez Andreina', 'V-32000225');
INSERT INTO public.tutores VALUES (111, 'Balza Roger', 'V-31728855');
INSERT INTO public.tutores VALUES (112, 'Vásquez Ronald', 'V-17346213');
INSERT INTO public.tutores VALUES (113, 'Bentacourt Wesley', NULL);
INSERT INTO public.tutores VALUES (114, 'Duran Jhonder', NULL);
INSERT INTO public.tutores VALUES (115, 'Eiber Palomares', NULL);
INSERT INTO public.tutores VALUES (116, 'Briceño Anmary', 'V-17093751');
INSERT INTO public.tutores VALUES (117, 'Briceño José', 'V-23250536');
INSERT INTO public.tutores VALUES (118, 'Karelis Briceño', NULL);
INSERT INTO public.tutores VALUES (119, 'Yesenia Toro', NULL);
INSERT INTO public.tutores VALUES (120, 'Nancy Gavidia', NULL);
INSERT INTO public.tutores VALUES (121, 'Egluibet Vásquez', NULL);
INSERT INTO public.tutores VALUES (122, 'Gil Marzzia', NULL);
INSERT INTO public.tutores VALUES (123, 'Lilia Laguna', NULL);
INSERT INTO public.tutores VALUES (124, 'Kelly Rivas', NULL);
INSERT INTO public.tutores VALUES (125, 'Fidel Balestrini', NULL);
INSERT INTO public.tutores VALUES (126, 'Diego Andres Villegas Rivero V', 'V-15408726');
INSERT INTO public.tutores VALUES (127, 'Engriz Azuaje De Chirinos V', NULL);
INSERT INTO public.tutores VALUES (128, 'Norelis Matheus', NULL);
INSERT INTO public.tutores VALUES (129, 'Rondón Yosimar', 'V-17321336');
INSERT INTO public.tutores VALUES (130, 'Reinaldo Rivas', 'V-11898586');
INSERT INTO public.tutores VALUES (131, 'Neiza Romero', NULL);
INSERT INTO public.tutores VALUES (132, 'Carlos García', NULL);
INSERT INTO public.tutores VALUES (133, 'Richard Páez Olmos', NULL);
INSERT INTO public.tutores VALUES (134, 'Jackelin Ramirez', NULL);
INSERT INTO public.tutores VALUES (135, 'Yenifer Araujo', NULL);
INSERT INTO public.tutores VALUES (136, 'Jesús Medina', NULL);
INSERT INTO public.tutores VALUES (137, 'Ninoska Villareal', NULL);
INSERT INTO public.tutores VALUES (138, 'Licda Lorena Abreu', NULL);
INSERT INTO public.tutores VALUES (139, 'Zulay Torres', NULL);


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios VALUES (5, 'bibi', 'bibi@gmail.com', '4444111', NULL, 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (8, 'Yisu Monte', 'yisu@gmail.com', '30866991', '$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (9, 'Pedro Perez', 'iaiaia@gmail.com', '4123123', '$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (11, 'Juan', '123@gmail.com', '1234', '$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (6, 'Piñin Piña', 'pina@hotmail.com', '1', '$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a', 4, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (10, 'Wazaaaa', 'wazaaa@gmail.com', '123', '$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O', 4, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (1, 'Adrus', 'andru@gmail.com', '11111111', '$2y$10$1sBy413YpJ9MQGlRt/g6y.OGkfno7aRuKxShONKSeWrvhQNS53YDO', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (14, 'Sixsevenaldo González', '676767@gmail.com', '67', '$2y$10$XIdtQdP6d.bZSGCINdEbIuTtuqee9E3EEKYp5Ogbm/I2JW5XQbP/O', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (13, 'Cepillíno', 'cepillin@gmail.com', '80', '$2y$10$ML6M4RYmR0f2yCoxOpRvaONIE/nUvwOmcsmGySeBOOBGjy9xUmbk6', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (16, 'no soy miguel', 'orlando5711666@gmail.com', '1234567', '$2y$10$g1LIDvziBpq3KrEilAhp3.RC1ziliq9hOpkdhWFISEjtJ.Y00SUHS', 3, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (12, '[Archivado] ANDRUS', 'andrusramirez2020@gmail.com_deleted_1789844158', '30469331_x9844158', '$2y$10$Vii.OSXjYhxOk.Xq.dx2EODQb3U9cahqa401C48PNPR3mlXdi85Ii', 2, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (15, '[Archivado] DIOSs', 'DIOS@gmail.com_deleted_1789845222', '12000000_x9845222', '$2y$10$eaICZWMAWr11BS/RL0ju0O49pEw.3lQIpEGrLoe6FweXprGxkjttu', 1, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (22, 'Josex', 'josex@gmail.com', '676767', '$2y$12$zutLeN3gyvhbfFMpNV2hmux9ECTkSE4kr2yCuzA3D0mE0FYPZUbu.', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (19, 'Juan Salcedo', 'juanxzall0701@gmail.com', 'V-31008131', '$2y$12$tjD8yhgVIWvwuJiEPZnYguCyS5EukGmORWC3srlfCHIAE0KQFr1ZG', 2, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (2, 'lando', 'lando@gmail.com', '22222222', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 2, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (3, 'miki', 'miki@gmail.com', '33333333', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (7, 'Miguel González', 'erwesitodepollo@gmail.com', '32621284', '$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.', 1, true, NULL, NULL, NULL, false, 'dc1c777c1cdc20c388ae51c653f684a4c5e4673e509adc494000642ed4e1cd0d');
INSERT INTO public.usuarios VALUES (4, 'Ale', 'ale@yaju.com', 'V-30536364', '$2y$12$zJItGIyhfXZWOiSguA6k6OTxAeAsp6zw8R4QIbx46WxzglCeZtQJC', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (17, 'Andrus Ramirez', 'andrusramirez2020@gmail.com', 'V-30469331', '$2y$12$TSHtbHbV8Y0ESGYbD32Yo.u0GJARF0iqVUprdminbjKMwUv37BR6W', 1, true, NULL, NULL, NULL, true, NULL);


--
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: waf_rate_limiter; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.waf_rate_limiter VALUES ('127.0.0.1', 'whitelist', 0, NULL, '2026-09-30 20:14:31.796931', NULL, 'Desarrollador Local (Localhost IPv4)', '{"responsable": "SuperAdmin"}', '2026-09-30 20:14:31.796931');
INSERT INTO public.waf_rate_limiter VALUES ('::1', 'whitelist', 0, NULL, '2026-09-30 20:14:31.797469', NULL, 'Desarrollador Local (Localhost IPv6)', '{"responsable": "SuperAdmin"}', '2026-09-30 20:14:31.797469');


--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, true);


--
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 598, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 280, true);


--
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 18, true);


--
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 7, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 24, true);


--
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 8, true);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 17, true);


--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historico_versiones_pst_id_seq', 1, true);


--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 4, true);


--
-- Name: investigadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.investigadores_id_seq', 1, false);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 11, true);


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.niveles_academicos_id_seq', 10, true);


--
-- Name: notificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_id_seq', 6, true);


--
-- Name: password_resets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.password_resets_id_seq', 1, true);


--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.postulaciones_estudiantes_id_seq', 2, true);


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 10, true);


--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 16, true);


--
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 305, true);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 5, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 3, true);


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- Name: trayectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.trayectos_id_seq', 20, true);


--
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 139, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 22, true);


--
-- Name: visitantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visitantes_id_seq', 1, true);


--
-- Name: accesos_recursos accesos_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_pkey PRIMARY KEY (id);


--
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- Name: autores autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_pkey PRIMARY KEY (id);


--
-- Name: carreras carreras_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_nombre_key UNIQUE (nombre);


--
-- Name: carreras carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id);


--
-- Name: categorias categorias_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nombre_key UNIQUE (nombre);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_slug_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_slug_key UNIQUE (slug);


--
-- Name: detalles_investigaciones detalles_investigaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT detalles_investigaciones_pkey PRIMARY KEY (id_recurso);


--
-- Name: detalles_proyectos detalles_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_pkey PRIMARY KEY (id_recurso);


--
-- Name: detalles_articulos detalles_revistas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_pkey PRIMARY KEY (id_recurso);


--
-- Name: dimensiones_operativas dimensiones_operativas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT dimensiones_operativas_pkey PRIMARY KEY (id);


--
-- Name: editoriales editoriales_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_nombre_key UNIQUE (nombre);


--
-- Name: editoriales editoriales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_pkey PRIMARY KEY (id);


--
-- Name: etiquetas etiquetas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_nombre_key UNIQUE (nombre);


--
-- Name: etiquetas etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_pkey PRIMARY KEY (id);


--
-- Name: historico_versiones_pst historico_versiones_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT historico_versiones_pst_pkey PRIMARY KEY (id);


--
-- Name: investigaciones_ofertadas investigaciones_ofertadas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT investigaciones_ofertadas_pkey PRIMARY KEY (id);


--
-- Name: investigadores investigadores_orcid_key; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_orcid_key UNIQUE (orcid);


--
-- Name: investigadores investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_pkey PRIMARY KEY (id);


--
-- Name: lineas_investigacion lineas_investigacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_pkey PRIMARY KEY (id);


--
-- Name: matriz_rbac matriz_rbac_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matriz_rbac
    ADD CONSTRAINT matriz_rbac_pkey PRIMARY KEY (nivel_privilegio, modulo);


--
-- Name: niveles_academicos niveles_academicos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_codigo_key UNIQUE (codigo);


--
-- Name: niveles_academicos niveles_academicos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_pkey PRIMARY KEY (id);


--
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


--
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (id);


--
-- Name: proyecto_carreras_vinculadas pk_proyecto_carreras_vinculadas; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera);


--
-- Name: postulaciones_estudiantes postulaciones_estudiantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT postulaciones_estudiantes_pkey PRIMARY KEY (id);


--
-- Name: preferencias_usuario preferencias_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: propuestas_empresa propuestas_empresa_codigo_seguimiento_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_codigo_seguimiento_key UNIQUE (codigo_seguimiento);


--
-- Name: propuestas_empresa propuestas_empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_pkey PRIMARY KEY (id);


--
-- Name: proyecto_tutores proyecto_tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_pkey PRIMARY KEY (id_recurso, id_tutor);


--
-- Name: recurso_autores recurso_autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_pkey PRIMARY KEY (id_recurso, id_autor);


--
-- Name: recurso_categorias recurso_categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria);


--
-- Name: recurso_clasificaciones recurso_clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT recurso_clasificaciones_pkey PRIMARY KEY (id_recurso, id_linea_investigacion);


--
-- Name: recurso_etiquetas recurso_etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT recurso_etiquetas_pkey PRIMARY KEY (id_recurso, id_etiqueta);


--
-- Name: recurso_investigadores recurso_investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT recurso_investigadores_pkey PRIMARY KEY (id_recurso, id_investigador);


--
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- Name: registro_actividad registro_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_pkey PRIMARY KEY (id);


--
-- Name: roles roles_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_key UNIQUE (nombre);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: system_audit_log system_audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.system_audit_log
    ADD CONSTRAINT system_audit_log_pkey PRIMARY KEY (id);


--
-- Name: telemetria_cache telemetria_cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.telemetria_cache
    ADD CONSTRAINT telemetria_cache_pkey PRIMARY KEY (id);


--
-- Name: tipo_recurso tipo_recurso_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_nombre_key UNIQUE (nombre);


--
-- Name: tipo_recurso tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_pkey PRIMARY KEY (id);


--
-- Name: tipo_tutor tipo_tutor_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_nombre_key UNIQUE (nombre);


--
-- Name: tipo_tutor tipo_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_pkey PRIMARY KEY (id);


--
-- Name: trayectos trayectos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_pkey PRIMARY KEY (id);


--
-- Name: tutores tutores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_cedula_key UNIQUE (cedula);


--
-- Name: tutores tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_pkey PRIMARY KEY (id);


--
-- Name: privilegios unique_nivel_privilegio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT unique_nivel_privilegio UNIQUE (nivel_privilegio);


--
-- Name: postulaciones_estudiantes unique_postulacion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT unique_postulacion UNIQUE (id_investigacion, id_estudiante);


--
-- Name: trayectos uq_carrera_trayecto; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT uq_carrera_trayecto UNIQUE (id_carrera, numero);


--
-- Name: usuarios usuarios_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cedula_key UNIQUE (cedula);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: visitantes visitantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes
    ADD CONSTRAINT visitantes_pkey PRIMARY KEY (id);


--
-- Name: waf_rate_limiter waf_rate_limiter_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.waf_rate_limiter
    ADD CONSTRAINT waf_rate_limiter_pkey PRIMARY KEY (ip, tipo);


--
-- Name: autores_cedula_key; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX autores_cedula_key ON public.autores USING btree (cedula);


--
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- Name: idx_detalles_proyectos_trayecto; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_proyectos_trayecto ON public.detalles_proyectos USING btree (id_trayecto);


--
-- Name: idx_detalles_vector_hnsw; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');


--
-- Name: idx_detalles_vector_null; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_vector_null ON public.detalles_proyectos USING btree (id_recurso) WHERE (vector_semantico IS NULL);


--
-- Name: idx_pcv_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_carrera ON public.proyecto_carreras_vinculadas USING btree (id_carrera);


--
-- Name: idx_pcv_recurso; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_recurso ON public.proyecto_carreras_vinculadas USING btree (id_recurso);


--
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


--
-- Name: idx_trayectos_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_trayectos_carrera ON public.trayectos USING btree (id_carrera);


--
-- Name: recursos tg_auditoria_recursos_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_delete BEFORE DELETE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: recursos tg_auditoria_recursos_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_insert AFTER INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- Name: usuarios tg_auditoria_usuarios_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_delete BEFORE DELETE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_insert AFTER INSERT ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: usuarios tg_auditoria_usuarios_update; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_update AFTER UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- Name: accesos_recursos accesos_recursos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: accesos_recursos accesos_recursos_id_registro_actividad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_registro_actividad_fkey FOREIGN KEY (id_registro_actividad) REFERENCES public.registro_actividad(id) ON DELETE CASCADE;


--
-- Name: auditoria auditoria_usuario_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_usuario_responsable_fkey FOREIGN KEY (usuario_responsable) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: detalles_proyectos detalles_proyectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_investigacion_padre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_investigacion_padre_fkey FOREIGN KEY (id_investigacion_padre) REFERENCES public.recursos(id) ON DELETE SET NULL;


--
-- Name: detalles_proyectos detalles_proyectos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: detalles_proyectos detalles_proyectos_id_trayecto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_trayecto_fkey FOREIGN KEY (id_trayecto) REFERENCES public.trayectos(id) ON DELETE SET NULL;


--
-- Name: detalles_articulos detalles_revistas_id_editorial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_editorial_fkey FOREIGN KEY (id_editorial) REFERENCES public.editoriales(id) ON DELETE SET NULL;


--
-- Name: detalles_articulos detalles_revistas_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_ofertada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_ofertada FOREIGN KEY (id_investigacion_ofertada) REFERENCES public.investigaciones_ofertadas(id) ON DELETE SET NULL;


--
-- Name: detalles_investigaciones fk_detalles_investigaciones_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: dimensiones_operativas fk_dimension_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT fk_dimension_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_dimension_operativa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_dimension_operativa FOREIGN KEY (id_dimension_operativa) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: recurso_etiquetas fk_etiqueta_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_etiqueta_recurso FOREIGN KEY (id_etiqueta) REFERENCES public.etiquetas(id) ON DELETE CASCADE;


--
-- Name: investigaciones_ofertadas fk_inv_dimension; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_dimension FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- Name: investigaciones_ofertadas fk_inv_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE RESTRICT;


--
-- Name: investigaciones_ofertadas fk_inv_profesor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_profesor FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_investigador; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_carrera; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: proyecto_carreras_vinculadas fk_pcv_recurso; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: postulaciones_estudiantes fk_postulacion_inv; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- Name: recurso_clasificaciones fk_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_etiquetas fk_recurso_etiqueta; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_recurso_etiqueta FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_investigadores fk_recurso_inv; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: historico_versiones_pst fk_version_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: lineas_investigacion lineas_investigacion_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: notificaciones notificaciones_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: preferencias_usuario preferencias_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.detalles_proyectos(id_recurso) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_id_tutor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_tutor_fkey FOREIGN KEY (id_tutor) REFERENCES public.tutores(id) ON DELETE CASCADE;


--
-- Name: proyecto_tutores proyecto_tutores_tipo_tutor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey FOREIGN KEY (tipo_tutor_id) REFERENCES public.tipo_tutor(id) ON DELETE SET NULL;


--
-- Name: recurso_autores recurso_autores_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES public.autores(id) ON DELETE CASCADE;


--
-- Name: recurso_autores recurso_autores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- Name: recurso_categorias recurso_categorias_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- Name: recursos recursos_id_tipo_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_id_tipo_recurso_fkey FOREIGN KEY (id_tipo_recurso) REFERENCES public.tipo_recurso(id) ON DELETE RESTRICT;


--
-- Name: registro_actividad registro_actividad_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- Name: registro_actividad registro_actividad_id_visitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_visitante_fkey FOREIGN KEY (id_visitante) REFERENCES public.visitantes(id) ON DELETE SET NULL;


--
-- Name: trayectos trayectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


--
-- Name: TABLE accesos_recursos; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.accesos_recursos TO miki;


--
-- Name: SEQUENCE accesos_recursos_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.accesos_recursos_id_seq TO miki;


--
-- Name: TABLE auditoria; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.auditoria TO miki;


--
-- Name: SEQUENCE auditoria_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.auditoria_id_seq TO miki;


--
-- Name: TABLE autores; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.autores TO miki;


--
-- Name: SEQUENCE autores_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.autores_id_seq TO miki;


--
-- Name: TABLE carreras; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.carreras TO miki;


--
-- Name: SEQUENCE carreras_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.carreras_id_seq TO miki;


--
-- Name: TABLE categorias; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.categorias TO miki;


--
-- Name: SEQUENCE categorias_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.categorias_id_seq TO miki;


--
-- Name: TABLE cursos; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.cursos TO miki;


--
-- Name: SEQUENCE cursos_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.cursos_id_seq TO miki;


--
-- Name: TABLE detalles_articulos; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.detalles_articulos TO miki;


--
-- Name: TABLE detalles_investigaciones; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.detalles_investigaciones TO miki;


--
-- Name: TABLE detalles_proyectos; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.detalles_proyectos TO miki;


--
-- Name: TABLE dimensiones_operativas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.dimensiones_operativas TO miki;


--
-- Name: SEQUENCE dimensiones_operativas_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.dimensiones_operativas_id_seq TO miki;


--
-- Name: TABLE editoriales; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.editoriales TO miki;


--
-- Name: SEQUENCE editoriales_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.editoriales_id_seq TO miki;


--
-- Name: TABLE etiquetas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.etiquetas TO miki;


--
-- Name: SEQUENCE etiquetas_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.etiquetas_id_seq TO miki;


--
-- Name: TABLE historico_versiones_pst; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.historico_versiones_pst TO miki;


--
-- Name: SEQUENCE historico_versiones_pst_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.historico_versiones_pst_id_seq TO miki;


--
-- Name: TABLE investigaciones_ofertadas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.investigaciones_ofertadas TO miki;


--
-- Name: SEQUENCE investigaciones_ofertadas_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.investigaciones_ofertadas_id_seq TO miki;


--
-- Name: TABLE lineas_investigacion; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.lineas_investigacion TO miki;


--
-- Name: SEQUENCE lineas_investigacion_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.lineas_investigacion_id_seq TO miki;


--
-- Name: TABLE matriz_rbac; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.matriz_rbac TO miki;


--
-- Name: TABLE notificaciones; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.notificaciones TO miki;


--
-- Name: SEQUENCE notificaciones_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.notificaciones_id_seq TO miki;


--
-- Name: TABLE password_resets; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.password_resets TO miki;


--
-- Name: SEQUENCE password_resets_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.password_resets_id_seq TO miki;


--
-- Name: TABLE postulaciones_estudiantes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.postulaciones_estudiantes TO miki;


--
-- Name: SEQUENCE postulaciones_estudiantes_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.postulaciones_estudiantes_id_seq TO miki;


--
-- Name: TABLE preferencias_usuario; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.preferencias_usuario TO miki;


--
-- Name: TABLE privilegios; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.privilegios TO miki;


--
-- Name: SEQUENCE privilegios_privilegio_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.privilegios_privilegio_id_seq TO miki;


--
-- Name: TABLE propuestas_empresa; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.propuestas_empresa TO miki;


--
-- Name: SEQUENCE propuestas_empresa_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.propuestas_empresa_id_seq TO miki;


--
-- Name: TABLE proyecto_tutores; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.proyecto_tutores TO miki;


--
-- Name: TABLE recurso_autores; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recurso_autores TO miki;


--
-- Name: TABLE recurso_categorias; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recurso_categorias TO miki;


--
-- Name: TABLE recurso_clasificaciones; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recurso_clasificaciones TO miki;


--
-- Name: TABLE recurso_etiquetas; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recurso_etiquetas TO miki;


--
-- Name: TABLE recursos; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.recursos TO miki;


--
-- Name: SEQUENCE recursos_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.recursos_id_seq TO miki;


--
-- Name: TABLE registro_actividad; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.registro_actividad TO miki;


--
-- Name: SEQUENCE registro_actividad_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.registro_actividad_id_seq TO miki;


--
-- Name: TABLE roles; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.roles TO miki;


--
-- Name: SEQUENCE roles_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.roles_id_seq TO miki;


--
-- Name: TABLE system_audit_log; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.system_audit_log TO miki;


--
-- Name: TABLE telemetria_cache; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.telemetria_cache TO miki;


--
-- Name: TABLE tipo_recurso; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tipo_recurso TO miki;


--
-- Name: SEQUENCE tipo_recurso_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tipo_recurso_id_seq TO miki;


--
-- Name: TABLE tipo_tutor; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tipo_tutor TO miki;


--
-- Name: SEQUENCE tipo_tutor_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tipo_tutor_id_seq TO miki;


--
-- Name: TABLE tutores; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.tutores TO miki;


--
-- Name: SEQUENCE tutores_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.tutores_id_seq TO miki;


--
-- Name: TABLE usuarios; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.usuarios TO miki;


--
-- Name: COLUMN usuarios.contrasena; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT(contrasena) ON TABLE public.usuarios TO miki;


--
-- Name: SEQUENCE usuarios_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.usuarios_id_seq TO miki;


--
-- Name: TABLE visitantes; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.visitantes TO miki;


--
-- Name: SEQUENCE visitantes_id_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.visitantes_id_seq TO miki;


--
-- Name: TABLE waf_rate_limiter; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.waf_rate_limiter TO miki;


--
-- PostgreSQL database dump complete
--

\unrestrict Oceqi0w1MV9uxcWi0OKrxa2mbrrO8oLLgZqssI8x3s2kQHEO8mbgJZ9qcW75jbe

