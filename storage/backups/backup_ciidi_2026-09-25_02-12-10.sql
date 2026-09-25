--
-- PostgreSQL database dump
--

\restrict hqahC2YFJNzxtbzqQGlj8szXTc2zQyHRH4ydnvCrVZDpFriwNScgWFV7HZlhwrk

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

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
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS fk_recurso_etiqueta;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_recurso;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_inv;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS fk_postulacion_estudiante;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS fk_linea_investigacion;
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
DROP INDEX IF EXISTS public.idx_recurso_clasif_linea;
DROP INDEX IF EXISTS public.idx_recurso_clasif_dimension;
DROP INDEX IF EXISTS public.idx_detalles_vector_null;
DROP INDEX IF EXISTS public.idx_detalles_vector_hnsw;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
ALTER TABLE IF EXISTS ONLY public.waf_rate_limiter DROP CONSTRAINT IF EXISTS waf_rate_limiter_pkey;
ALTER TABLE IF EXISTS ONLY public.visitantes DROP CONSTRAINT IF EXISTS visitantes_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_pkey;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_email_key;
ALTER TABLE IF EXISTS ONLY public.usuarios DROP CONSTRAINT IF EXISTS usuarios_cedula_key;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS unique_postulacion;
ALTER TABLE IF EXISTS ONLY public.privilegios DROP CONSTRAINT IF EXISTS unique_nivel_privilegio;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.tutores DROP CONSTRAINT IF EXISTS tutores_cedula_key;
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
ALTER TABLE IF EXISTS ONLY public.recurso_etiquetas DROP CONSTRAINT IF EXISTS recurso_etiquetas_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_clasificaciones DROP CONSTRAINT IF EXISTS recurso_clasificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_categorias DROP CONSTRAINT IF EXISTS recurso_categorias_pkey;
ALTER TABLE IF EXISTS ONLY public.recurso_autores DROP CONSTRAINT IF EXISTS recurso_autores_pkey;
ALTER TABLE IF EXISTS ONLY public.proyecto_tutores DROP CONSTRAINT IF EXISTS proyecto_tutores_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_pkey;
ALTER TABLE IF EXISTS ONLY public.propuestas_empresa DROP CONSTRAINT IF EXISTS propuestas_empresa_codigo_seguimiento_key;
ALTER TABLE IF EXISTS ONLY public.preferencias_usuario DROP CONSTRAINT IF EXISTS preferencias_usuario_pkey;
ALTER TABLE IF EXISTS ONLY public.postulaciones_estudiantes DROP CONSTRAINT IF EXISTS postulaciones_estudiantes_pkey;
ALTER TABLE IF EXISTS ONLY public.password_resets DROP CONSTRAINT IF EXISTS password_resets_pkey;
ALTER TABLE IF EXISTS ONLY public.notificaciones DROP CONSTRAINT IF EXISTS notificaciones_pkey;
ALTER TABLE IF EXISTS ONLY public.matriz_rbac DROP CONSTRAINT IF EXISTS matriz_rbac_pkey;
ALTER TABLE IF EXISTS ONLY public.lineas_investigacion DROP CONSTRAINT IF EXISTS lineas_investigacion_pkey;
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
ALTER TABLE IF EXISTS public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
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
DROP TABLE IF EXISTS public.recurso_etiquetas;
DROP TABLE IF EXISTS public.recurso_clasificaciones;
DROP TABLE IF EXISTS public.recurso_categorias;
DROP TABLE IF EXISTS public.recurso_autores;
DROP TABLE IF EXISTS public.proyecto_tutores;
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
DROP TABLE IF EXISTS public.matriz_rbac;
DROP SEQUENCE IF EXISTS public.lineas_investigacion_id_seq;
DROP TABLE IF EXISTS public.lineas_investigacion;
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
DROP TYPE IF EXISTS public.nivel_academico_enum;
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
-- Name: nivel_academico_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.nivel_academico_enum AS ENUM (
    'TSU',
    'Pregrado',
    'Especializacion',
    'Maestria',
    'Doctorado'
);


ALTER TYPE public.nivel_academico_enum OWNER TO postgres;

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
    cedula character varying(20)
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
    id_recurso integer CONSTRAINT detalles_revistas_id_recurso_not_null NOT NULL,
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
    nivel_academico public.nivel_academico_enum DEFAULT 'Pregrado'::public.nivel_academico_enum,
    resumen text,
    id_carrera integer,
    comunidad_beneficiada text,
    palabras_clave text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_investigacion_padre integer,
    trayecto character varying(50) DEFAULT 'Trayecto I'::character varying,
    url_repositorio text,
    obj_general text,
    activo boolean DEFAULT true,
    vector_semantico public.vector(384)
);


ALTER TABLE public.detalles_proyectos OWNER TO postgres;

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
    privilegio_id integer DEFAULT 1 CONSTRAINT roles_privilegios_id_not_null NOT NULL
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
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


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


--
-- Data for Name: autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.autores VALUES (1, 'Prof. Andrus', 'V-11223344');
INSERT INTO public.autores VALUES (2, 'Estudiante Dev', 'V-27000111');
INSERT INTO public.autores VALUES (3, 'Estudiante Electrónica', 'V-28000222');
INSERT INTO public.autores VALUES (4, 'Juan Pérez', NULL);
INSERT INTO public.autores VALUES (5, 'María García', NULL);
INSERT INTO public.autores VALUES (6, 'Ing. Pedro Díaz', NULL);
INSERT INTO public.autores VALUES (7, 'Carlos López', NULL);
INSERT INTO public.autores VALUES (8, 'Ana Martínez', NULL);
INSERT INTO public.autores VALUES (9, 'Dra. Sofía Rojas', NULL);
INSERT INTO public.autores VALUES (14, 'Dr. Ramón Fuentes', 'V-10111213');
INSERT INTO public.autores VALUES (15, 'Dra. Clara Vásquez', 'V-10222333');
INSERT INTO public.autores VALUES (16, 'Ing. Luis Morelo', 'V-10333444');
INSERT INTO public.autores VALUES (17, 'Prof. Yolanda Díaz', 'V-10444555');
INSERT INTO public.autores VALUES (18, 'Ing. Pedro Ríos', 'V-10555666');
INSERT INTO public.autores VALUES (19, 'Prof. Ana Suárez', 'V-10666777');
INSERT INTO public.autores VALUES (21, 'Mariela Colón', 'V-27100002');
INSERT INTO public.autores VALUES (22, 'Javier Navas', 'V-27100003');
INSERT INTO public.autores VALUES (23, 'Luisa Paredes', 'V-27100004');
INSERT INTO public.autores VALUES (24, 'Tomás Guerrero', 'V-27100005');
INSERT INTO public.autores VALUES (25, 'Valentina Soto', 'V-27100006');
INSERT INTO public.autores VALUES (26, 'Rodrigo Méndez', 'V-27100007');
INSERT INTO public.autores VALUES (27, 'Gabriela López', 'V-27100008');
INSERT INTO public.autores VALUES (28, 'Hernán Castro', 'V-27100009');
INSERT INTO public.autores VALUES (29, 'Isabel Ramos', 'V-27100010');
INSERT INTO public.autores VALUES (32, 'Fernando Carmino', 'V-12312313');
INSERT INTO public.autores VALUES (30, 'Mariano Rajoy', 'V-9857492');
INSERT INTO public.autores VALUES (31, 'Alejandro Alicante', 'V-12312391');
INSERT INTO public.autores VALUES (33, 'Luis Enrique Morelos', 'E-5184865');
INSERT INTO public.autores VALUES (34, 'Jesús Montilla', 'V-30866991');
INSERT INTO public.autores VALUES (35, 'Luis Miguel', 'V-17855689');
INSERT INTO public.autores VALUES (36, 'Fausto Hernandez', 'V-21314132');
INSERT INTO public.autores VALUES (37, 'miki', 'V-1234');
INSERT INTO public.autores VALUES (42, 'González González Miguel Alejandro', 'V-32621284');
INSERT INTO public.autores VALUES (43, 'Rojo Ramírez José Alejandro', 'V-30536364');
INSERT INTO public.autores VALUES (44, 'Ramírez Duarte Andrus Ruben', 'V-30469331');
INSERT INTO public.autores VALUES (45, 'Pérez Marín José Gregorio', 'V-31177398');
INSERT INTO public.autores VALUES (46, 'González Victoria', 'V-30931145');
INSERT INTO public.autores VALUES (47, 'Estudiante Prueba Uno', 'V-30111222');
INSERT INTO public.autores VALUES (48, 'Estudiante Prueba Dos', 'V-30333444');
INSERT INTO public.autores VALUES (49, 'María Autor Prueba', 'V-31000111');
INSERT INTO public.autores VALUES (50, 'Favian Herrera', 'V-30600230');
INSERT INTO public.autores VALUES (51, 'Jesús Linares', 'V-30600950');
INSERT INTO public.autores VALUES (52, 'Araujo Oliver', 'V-30866964');
INSERT INTO public.autores VALUES (53, 'Nava Ailberth', 'V-30738034');
INSERT INTO public.autores VALUES (54, 'David Lidmar', 'V-25111222');
INSERT INTO public.autores VALUES (55, 'Estudiante Pruebas Uno', 'V-99887766');
INSERT INTO public.autores VALUES (56, 'Estudiante Pruebas Dos', 'V-99887767');
INSERT INTO public.autores VALUES (57, 'Daniel ángel', 'V-30379710');
INSERT INTO public.autores VALUES (58, 'Araujo Rivas Isamar Andreina', 'V-31029609');
INSERT INTO public.autores VALUES (59, 'Collantes Peña José Manuel', 'V-31602776');
INSERT INTO public.autores VALUES (60, 'León Custode María Fernanda', 'V-31094982');
INSERT INTO public.autores VALUES (61, 'Ocanto Morales ángel David', 'V-31239885');
INSERT INTO public.autores VALUES (62, 'Briceño Brandon', 'V-29814531');
INSERT INTO public.autores VALUES (63, 'Carrizo Franyeski', 'V-31602854');
INSERT INTO public.autores VALUES (64, 'Ramírez Oriana', 'V-30671745');
INSERT INTO public.autores VALUES (65, 'Valero Alejandro', 'V-29814164');
INSERT INTO public.autores VALUES (66, 'Roberto Saavedra', 'V-30671594');
INSERT INTO public.autores VALUES (67, 'Adrian Maldonado', 'V-30600276');
INSERT INTO public.autores VALUES (68, 'Alberth Barreto', 'V-30438316');
INSERT INTO public.autores VALUES (69, 'Escobar Morales Gelany Paola', 'V-33573889');
INSERT INTO public.autores VALUES (70, 'Ruza Ferrebus Jhon David', 'V-32282366');
INSERT INTO public.autores VALUES (71, 'Ortega Gonzalez Orlando Manuel', 'V-27889926');
INSERT INTO public.autores VALUES (72, 'Piña Materan Juan Diego', 'V-31413623');
INSERT INTO public.autores VALUES (73, 'Salcedo Angel Juan Diego', 'V-31008131');
INSERT INTO public.autores VALUES (74, 'Andrés David Parra Cabrera', 'V-31029492');
INSERT INTO public.autores VALUES (75, 'Jesús Alejandro Lobo Briceño', 'V-27677098');
INSERT INTO public.autores VALUES (76, 'Orlando José González Moreno', 'V-31168262');
INSERT INTO public.autores VALUES (77, 'Sebastián Jesús Blanco Rojas', 'V-30600412');
INSERT INTO public.autores VALUES (78, 'Tsu David Galíndez', '1231323');
INSERT INTO public.autores VALUES (79, 'Estudiante Pruebas', 'V-99999999');
INSERT INTO public.autores VALUES (80, 'Test Author', 'V-88888888');
INSERT INTO public.autores VALUES (82, 'Anyela Alejandra Briceño Guerra', 'V-31413272');
INSERT INTO public.autores VALUES (83, 'Abraham David Graterol Villamizar', 'V-31167863');
INSERT INTO public.autores VALUES (84, 'Isaac José Figuera García', 'V-31239364');
INSERT INTO public.autores VALUES (88, 'Jesus Francisco Montilla Olmos', 'V-30886991');
INSERT INTO public.autores VALUES (89, 'Miguel Alejandro Gonzalez Gonzalez', 'V-32621283');
INSERT INTO public.autores VALUES (90, 'Juan Piña', 'V-8398');
INSERT INTO public.autores VALUES (99, 'José José', NULL);
INSERT INTO public.autores VALUES (100, 'JuanJo', NULL);
INSERT INTO public.autores VALUES (101, 'Jaliscos', NULL);
INSERT INTO public.autores VALUES (102, 'Jorge Lira-Camargo', NULL);
INSERT INTO public.autores VALUES (92, 'José Antonio Ogosi-Auqui', NULL);
INSERT INTO public.autores VALUES (96, 'Guillermo Pastor Morales-Romero', NULL);
INSERT INTO public.autores VALUES (97, 'César Gerardo León-Velarde', NULL);
INSERT INTO public.autores VALUES (103, 'Giovanni Andrés Cortés-Tovar', NULL);
INSERT INTO public.autores VALUES (104, 'Robinson Osorio-Hernández', NULL);
INSERT INTO public.autores VALUES (105, 'Jairo Alexander Osorio-Saráz', NULL);
INSERT INTO public.autores VALUES (13, 'Alejandro', 'E-22231231');


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

INSERT INTO public.detalles_proyectos VALUES (49, '2026-03-15', 'Pregrado', 'Desarrollo de un sistema tradicional para optimizar los métodos y procedimientos del inventario médico. Sigue un patrón arquitectónico modular para agilizar los procesos organizacionales.', 1, 'Ambulatorio Urbano Tipo II', 'Sistemas de Información, PostgreSQL, Gestión, Inventario', '2026-07-05 17:39:35.498485', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.05731583,-0.03336475,-0.08886621,-0.05460096,0.03898835,0.00046852,0.11113966,0.05400636,-0.01741614,-0.03047406,0.0390615,0.07353149,0.10046915,0.00627469,-0.09194207,0.00222401,0.00467476,0.04665747,0.01799402,-0.06379952,0.03984975,-0.09600575,0.00312482,0.04183621,0.03693933,-0.02959623,-0.00872208,0.0022185,0.03386965,-0.07550273,-0.02099945,0.0311969,0.07500709,0.06381709,0.01945021,0.00688952,0.04419655,0.00474972,-0.02157377,-0.07449683,0.02830216,-0.0447631,0.00442922,-0.02405149,-0.01950712,-0.02297054,-0.00089351,-0.05146974,-0.07338268,0.05651589,-0.08987771,0.0167203,0.05140945,-0.0339893,-0.00729554,-0.03732943,-0.05061381,-0.02416531,-0.05899847,-0.07079021,0.05984617,0.03694507,-0.04677462,-0.0703738,0.00596403,0.10121617,0.02798941,-0.04528012,0.10364497,-0.06984487,0.0421511,0.02220686,-0.00865553,0.04045001,-0.08957117,0.08180496,-0.02824596,-0.02629421,-0.03325792,0.0156429,0.04221389,0.08043943,0.0292761,0.09003997,-0.02909793,0.04054161,-0.1129025,0.08704938,0.10976771,-0.05791481,0.03830433,-0.02008615,0.04414072,-0.00329266,0.02781639,0.04944851,-0.0398883,-0.01879999,-0.01215764,0.03592015,0.05816086,-0.00121256,-0.00188515,0.00636852,-0.02491127,0.03182802,-0.03656657,-0.01753488,0.0427866,-0.03707663,-0.01780859,-0.02369113,-0.00623763,-0.1246239,-0.06395621,0.06166063,-0.00183281,0.0061978,-0.00063201,-0.08107033,0.02679783,-0.0170004,-0.10359022,-0.06723708,0.06251148,-0.02033398,-0.04745705,5.06e-06,-0.03047186,0.06722086,0.08392333,0.0269097,0.00687486,-0.00414827,0.05603761,0.04115836,0.03049861,-0.01525599,-0.0897388,0.06107989,-0.15605322,-0.01434043,0.02931477,-0.06994656,-0.00783702,0.01559114,0.09299506,-0.03305725,-0.0082101,-0.01189854,0.0408661,0.00085399,0.10293365,0.03465423,0.02793418,0.00688829,0.01941396,0.02600144,-0.00725431,0.03877377,-0.01934537,0.03025442,-0.01417834,-0.06173779,-0.08672932,-0.01449821,0.07518131,0.02260193,0.02547865,0.06878946,-0.01508908,-0.08630709,-0.04800294,-0.01800283,-0.04475712,0.08414443,0.029393,-0.0622973,0.06657644,0.00147741,-0.09424495,0.03315107,0.13852891,0.00264598,-0.00455834,0.02932522,0.0552912,0.05652247,-0.03488734,-0.07471648,-0.00750391,0.06569429,0.02322103,0.04239032,-0.01270427,0.00140395,0.11284511,0.03798357,-0.02241813,-0.03215969,-0.03730487,0.07383352,0.04278465,-0.0282968,-0.03771505,-0.01019774,-0.03068268,0.01643629,-0.02814088,0.02359607,-0.07137131,0.03187497,0.0192934,0.10735596,0.03790912,0.07927841,-0.01737604,-0.04221888,0.02692162,-0.03598364,0.01192007,0.10348803,-0.05021304,1.38e-05,-0.05858865,-0.04234103,-0.03104664,0.02903558,-0.03168526,-0.08462823,-0.03957395,-0.02663252,-0.02224432,0.08305554,-0.02181096,-0.05622329,0.03070545,-0.00514754,-0.02530688,0.00611282,0.03798034,0.00919136,0.02417664,-0.00056703,-0.1164191,0.05307986,-0.03355354,-0.02754243,0.0371145,-0.04394936,-0.02652649,0.03474068,0.00996165,0.10044734,-0.0486866,-0.00984516,0.0155939,0.04890144,-0.03564482,-0.05747719,0.05815488,-0.00282696,0.02756271,-0.06322882,0.01033592,-0.04283584,0.00073399,-0.03913053,-0.01465152,-0.03027913,-0.06495599,-0.05134275,0.08411376,-0.02382685,0.01909812,-0.05168322,0.0118297,-0.08174042,0.01989147,0.05701749,0.01852904,0.06313321,-0.02955819,-0.04920327,-0.02771515,0.01692933,0.00463765,-0.0592599,0.07100533,0.05574053,-0.01076673,0.04001091,-0.06028694,-0.08449904,0.08056357,0.06880764,0.04183782,0.01523574,-0.02343621,-0.00078959,-0.05291149,0.02222219,0.02736533,-0.02955473,-0.0390933,0.00700903,0.01020466,-0.03209275,-0.04611187,0.03668264,0.07639232,-0.00666301,0.04470844,0.01536593,-0.00782384,0.05052482,-0.00882872,0.01631608,-0.00118273,1.32e-06,-0.04788115,-0.01747482,-0.10170536,-0.12476195,0.0050662,-0.0546327,-0.0240917,0.10182592,0.0803923,-0.05726384,0.03613475,-0.1084645,-0.0254794,0.00778835,-0.03826245,-0.10085338,0.01627091,0.01297097,-0.06482624,-0.08182427,0.0859159,-0.01866207,0.00439441,-0.00865888,-0.03216825,-0.06297054,-0.00330536,-0.03073501,0.00021687,0.10939257,-0.02841644,-0.07722595,0.02236202,0.02447342,0.0354249,0.03650841,0.05982381,-0.0158006,0.05026323,-0.1108258,0.02386989,-0.12150557,-0.11010923,0.00993144,-0.04964381,-0.04807167,-0.07210328,-0.08824923,-0.01009044,0.03780032,0.06407956,-0.00857162,0.00877198,-0.00556442,0.02994161,-0.03444262,0.02365341,-0.02195836,0.04200217,-0.03446202,-0.03230774,-0.03008366,0.08827107,-0.02426048]');
INSERT INTO public.detalles_proyectos VALUES (57, '2026-07-05', 'Pregrado', 'ahsdhajsdhahakjfhafggfjhgfkjh', 1, 'asdasdasdasd', 'asdasdasdasdasd', '2026-07-05 18:21:33.639701', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.02322753,0.13822852,-0.06485066,0.09797764,0.00155804,-0.02484597,0.1430978,-0.05783203,0.00486376,0.00908724,0.04680731,0.03201176,-0.05619663,-0.02865091,0.04700505,-0.03998839,-0.07369348,-0.01997298,-0.11996852,0.02208529,0.07323764,0.0318581,-0.01521952,0.1063,-0.02392011,-0.01356458,-0.00936542,0.01393152,-0.00732716,-0.01249907,-0.01323702,0.01183527,0.08307561,-0.0420792,0.03326791,-0.00471068,0.01728583,-0.0725316,-0.03342097,-0.01792797,0.02216206,-0.0447486,0.0099613,0.00885004,-0.04006861,-0.01234343,-0.079306,0.04201591,0.08184453,0.00634884,-0.05163396,-0.02510875,-0.01899761,-0.03802259,0.00811297,0.02203849,-0.05172615,-0.05675547,0.00857998,-0.10527378,-0.02357098,0.02656178,-0.07698194,0.09525495,-0.01008316,-0.01643409,-0.03685811,-0.05955309,-0.01042471,0.03591203,0.00740488,-0.06560472,-0.01215646,0.03787691,-0.0932996,0.04767085,-0.00729717,-0.02455464,0.0536576,0.01082899,-0.01975611,-0.03449879,0.03400153,0.03208173,-0.04856262,0.01369184,-0.0658022,-0.03213095,-0.04222532,-0.05059701,-0.12208027,-0.07161372,0.01529742,0.01171448,-0.0495046,-0.03390696,0.03927976,-0.02112179,-0.09700032,0.06734547,0.02941724,0.11730336,0.01397422,0.04991572,0.0222088,0.00501904,-0.00694427,0.03448101,0.02978802,0.03482078,-0.08445134,-0.03154116,0.01695771,-0.05800535,0.01201901,0.00890276,0.00464824,0.00933579,0.00774803,-0.02822998,-0.03772188,0.01176806,0.08699032,-0.08007589,-0.01762384,-0.09342091,-0.04694681,3.54e-06,-0.04168777,-0.0344844,0.07753353,-0.01780925,0.03654011,-0.07492683,-0.03531509,-0.045515,0.00847578,0.04411575,-0.01130633,-0.03731254,-0.02617777,0.02733458,0.02267288,0.02181514,0.05659403,0.05949219,-0.01998155,0.04678024,-0.05942101,0.03540259,0.05792241,0.03004907,-0.01366114,-0.04973014,0.02567444,-0.04023835,-0.03649104,0.08932512,0.1124114,0.03743417,-0.05357631,-0.06353406,-0.10244012,-0.0325168,0.06865067,-0.06458244,-0.06422022,-0.05421479,0.08256028,0.00583241,0.02134155,-0.00842152,0.01468078,0.15106808,0.06238857,0.01044637,-0.0042352,0.05100241,-0.06480833,-0.01675222,-0.07704516,0.0218057,0.03196722,-0.06972494,0.00795697,-0.02713712,0.06037385,-0.00744909,0.00510417,0.03158512,-0.07675873,-0.03802062,-0.07944154,-0.08516215,-0.01759154,-0.03891458,-0.04542377,0.02618608,0.09555195,-0.03287262,-0.00070872,0.12452596,-0.02826656,0.00408697,0.01742725,-0.00531119,0.00502423,0.04352475,0.00605281,0.03678353,0.05498212,-0.01825105,0.05491819,-0.00891824,0.02247061,-0.03818549,-0.03212928,0.00387123,-0.15272409,0.02804863,0.06798315,-0.01378185,-0.05696766,1.016e-05,0.09001523,-0.06316895,0.01298934,0.00476555,0.04013623,-0.00634923,-0.07612968,0.05830479,0.10637362,0.04711224,0.08228921,-0.03903875,0.00976794,-0.02832477,0.03935945,-0.01815403,0.05568312,0.00203427,-0.017544,0.00714131,-0.0289532,0.00621593,-0.05584972,0.01996391,0.01534194,-0.02926228,0.05361666,0.04259738,-0.14015807,0.00823977,-0.02150232,-0.03780418,-0.0482053,0.06107851,-0.04920279,-0.03359722,0.04377386,1.569e-05,-0.05774729,-0.00031798,0.00265191,-0.00659483,0.05749241,-0.01788806,0.05945637,-0.05525747,-0.11937608,-0.06615897,-0.00641192,-0.02150154,0.06907449,-0.03809107,-0.00760761,0.05992961,0.0383883,0.02994274,-0.00438898,0.03235372,-0.03727935,-0.05544949,0.04868282,-4.444e-05,-0.0410918,0.0736016,0.01924301,0.02772544,-0.05823097,-0.10452515,-0.03720769,0.01747897,-0.09548426,-0.05807517,-0.04430033,0.1288246,-0.02693197,-0.04261602,-0.10013122,-0.01538903,0.01333186,-0.04330877,-0.02836761,-0.08401951,-0.05136725,0.02704333,0.05737518,-0.02204184,0.08968733,0.04463015,-0.02476052,0.04017453,0.03407366,0.0452445,-0.00743121,0.02028952,0.01595593,8.9e-07,-0.0477644,0.00658541,-0.00650795,0.0055786,0.04482809,0.059374,-0.05137903,-0.04120926,0.07403037,-0.02566155,0.04022981,0.15052412,0.00740589,0.04477223,-0.04333017,-0.01989499,-0.04363213,0.07013659,0.02652538,-0.06103128,0.05845824,0.01011263,0.01078045,0.00971134,0.0236605,-0.03313344,0.06212437,0.01016083,0.00940626,-0.01212167,-0.0298079,0.03903487,-0.01034826,-0.04465735,0.05783712,0.02000956,0.03799728,-0.10338834,-0.02953588,0.02501431,0.00532555,-0.02094636,0.05853012,-0.05601557,-0.00754901,-0.05138752,0.04743613,0.01165859,-0.0898499,-0.07298252,-0.00593199,0.0458106,0.02465905,0.00825362,-0.01966882,-0.00996433,-0.00066181,0.01741722,-0.00970059,0.07134504,0.18366584,0.02428828,-0.02115155,0.03153135]');
INSERT INTO public.detalles_proyectos VALUES (69, '2026-08-04', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.', 1, 'Centro Clínico “María Edelmira Araujo”', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-04 09:58:54.904249', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.03482301,-0.0511314,-0.03498223,-0.00384492,-0.03424594,-0.03291514,-0.04187362,-0.03011711,0.03822904,0.01337112,-0.00225304,0.02821508,0.04532943,-0.09136165,-0.06034093,-0.02703596,-0.0311053,-0.00747885,-0.00853233,0.07994567,0.04153795,-0.0768302,-0.04291943,0.06224649,-0.02817489,0.06435907,-0.0200178,0.03542714,-0.05799962,-0.00165773,-0.04442082,0.10314108,0.10520855,0.01501195,-0.01498969,0.04526936,0.04178364,-0.01537622,-0.02921864,0.03974377,-0.06168643,-0.0415804,0.05087307,-0.0336436,-0.10590254,-0.03655488,-0.0322046,-0.04663027,-0.04522838,-0.0691078,-0.09668752,-0.02809252,-0.00742435,-0.00448068,-0.03755506,0.0253594,0.0739074,-0.03434919,0.04635023,-0.02079227,0.06102284,0.01243913,0.04486309,0.05319423,0.02461842,0.00294235,0.03718556,0.0177744,0.05747779,-0.04760209,-0.01358196,0.00358279,0.00589012,0.1094748,0.04272691,-0.01126134,-0.06831491,-0.03320648,0.11177598,-0.0906062,0.02772985,0.02279229,-0.0629608,0.05749147,-0.02897948,0.06742685,-0.04108953,-0.01776725,0.16840035,-0.03226426,0.09100827,0.06416997,-0.03585674,-0.0495359,0.04485235,-0.02211359,0.08720332,-0.08323255,-0.08341507,0.00040901,0.02007384,0.01085685,-0.00624897,-0.0811171,-0.00239767,0.02036599,0.01862106,0.01421817,-0.0393339,-0.05382257,-0.04665208,-0.06694791,-0.06117899,-0.07032039,-0.06049241,-0.00067559,-0.00123199,0.02014647,0.03027877,-0.05326552,-0.05229671,-0.04825779,-0.08083933,0.01790298,0.06469325,0.00251431,-0.00937109,4.62e-06,0.00043571,0.0044539,-0.05765608,0.05630637,-0.03808755,0.00507626,0.0024398,0.0630673,-0.03350486,-0.06237769,0.03133817,0.02953604,0.02931996,0.0555163,-0.01954977,0.02462066,-0.04674416,-0.03554251,0.02457599,0.0090768,-0.03480305,0.03283396,0.02737442,-0.0667497,-0.06452957,0.04998524,-0.02599114,0.01654753,0.07542795,0.03738379,0.02766987,-0.03523752,-0.03392658,-0.0356685,0.04981261,0.09128091,0.01716657,0.02324847,0.01865979,0.06703848,0.06966471,0.0099609,-0.06913491,0.00203813,0.01861503,0.05401661,0.00993881,0.04493053,0.10688815,-0.09965774,-0.03905318,0.0333254,-0.1071829,-0.02486773,-0.00439451,-0.04580421,0.02055266,0.07210017,0.06717705,-0.04177319,-0.01079576,0.04929414,0.04976081,0.07566846,0.0440418,-0.07275257,-0.04569179,-0.05018853,0.15475522,-0.05852753,-0.02034512,-0.02341164,0.01498351,0.09105473,-0.03763611,0.01525999,-0.08130865,-0.02484817,-0.01378567,0.05689594,-0.05047542,0.00562926,-0.0150456,0.0089911,0.0901719,-0.01648982,-0.00811981,0.04132034,-0.1127393,0.03896141,-0.02756134,-0.12881674,0.09177485,0.00841433,0.0085643,1.409e-05,-0.04409434,6.715e-05,-0.0344637,0.01715265,-0.00421194,0.01851343,-0.05017617,-0.06400782,-0.0260811,0.01548192,0.06575451,-0.05209904,0.04899992,-0.02891517,0.06348136,0.0203562,0.05647751,-0.06712145,-0.01778444,-0.01871222,-0.00168901,-0.1098438,0.05167499,0.05092775,-0.0479,0.0066939,-0.00127241,-0.00137968,-0.06285006,0.074099,0.0798527,-0.00183937,-0.06289573,0.14433235,-0.03189024,-0.01780788,-0.10233796,0.02016091,0.04167868,0.04473984,0.02022047,-0.06467246,-0.0192108,-0.01846275,-0.01405093,-0.04286575,-0.05632451,-0.06063124,-0.05067729,-0.03126853,0.05702306,-0.00058726,-0.07967332,-0.14213546,0.0716278,0.1329031,-0.00435534,0.00964112,-0.06343959,-0.03077804,0.05378655,0.04181438,-0.04525526,0.01746109,-0.02597098,0.15522046,0.01172044,0.0688116,-0.03573536,-0.01484789,0.05925948,-0.00485196,-0.03716063,-0.05651669,-0.05550987,0.00622865,-0.02221347,0.01792098,-0.04650643,0.04938815,-0.03274429,-0.04267117,0.01381379,0.02088431,-0.07096986,0.02754281,-0.02304073,-0.00671618,0.03152474,0.02149754,0.03108594,-0.03094565,-0.06711411,-0.09433289,0.05595965,1.45e-06,-0.00417485,-0.05801838,0.02078312,-0.00955225,0.00431516,-0.0671179,-0.00503071,-0.00539272,0.01360429,0.06021507,-0.01387762,-0.10674187,-0.00986973,0.04358294,0.062141,0.0068512,-0.02260933,0.06104902,-0.06185856,-0.08982762,0.08704805,-0.07194207,0.0174327,-0.06123784,-0.05399646,0.0020026,-0.01274851,-0.05434861,-0.0163685,0.00365701,0.03372454,-0.03866974,-0.09695276,-0.00120654,0.07285379,-0.00144638,0.01646104,-0.03011583,0.01754421,0.04614457,0.05252156,-0.0087263,-0.03609742,0.01725893,0.05090455,-0.04381254,-0.01594426,0.05420165,0.05492438,0.05062452,-0.04297585,0.0211578,-0.02919022,0.01986195,-0.00203078,-0.01577579,-0.0374176,0.0361808,-0.06827574,-0.00955827,-0.07697725,0.06174915,0.0258108,-0.04730456]');
INSERT INTO public.detalles_proyectos VALUES (78, '2026-08-05', 'Pregrado', 'Este es un resumen de prueba automatizada para verificar la carga por lotes via AJAX.', 1, 'Comunidad de Pruebas', 'Prueba, AJAX, Lotes, PHP', '2026-08-05 09:43:26.945146', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.12106838,0.04973528,-0.09255663,-0.02869596,-0.0935646,0.08402925,-0.03020252,0.06660336,-0.02261252,-0.06187334,0.00367303,0.01660055,-0.0288878,-0.05499734,0.01876421,0.01836412,-0.01919362,-0.01485065,-0.0236071,-0.01290656,-0.02158088,-0.04767714,-0.02846051,-0.03174646,-0.01151084,0.03451944,0.01934438,0.04145245,0.01631719,-0.01560945,0.0358038,0.06753767,-0.04420602,-0.02929382,0.06835754,0.02040925,-0.12385551,-0.05182614,-0.03207639,-0.01982309,0.04933178,-0.01990569,-0.09412533,-0.00282419,0.01322694,-0.02103953,-0.0215564,0.07054141,-0.01164831,-0.10237681,-0.05052824,-0.04673455,0.0507022,-0.00587818,-0.12708554,-0.00198436,0.05118881,0.06578188,0.00630165,0.04464834,0.03119222,0.00205714,-0.04904225,-0.04133907,-0.06119314,-0.02336575,-0.05204766,-0.00480925,-0.02948958,0.0196756,-0.00334629,0.0432384,-0.02912064,0.05946294,0.00231897,-0.01767486,-0.06394331,-0.06879807,-0.04872164,-0.03282697,0.00646243,-0.12668693,-0.04181207,-0.0134694,-0.00237668,0.03286984,0.04725121,0.04293123,0.04969825,0.0147799,-0.02768866,-0.01574091,-0.01865972,0.1155379,-0.09075842,0.0217219,0.02497345,-0.01183375,0.04273093,-0.00332938,0.02549466,0.01189486,-0.01121997,-0.03266635,-0.09034306,0.04147514,-0.00926178,0.07624779,0.03373872,-0.02733791,-0.09841925,0.00497186,-0.01076069,-0.00079801,-0.05743779,-0.04165945,-0.01815891,-0.09475091,-0.03131135,-0.02771248,0.07891782,-0.02591765,-0.06893024,0.00935448,0.0402303,-0.07370853,-0.01954264,3.1e-06,0.02122247,-0.02311841,0.06739784,-0.01914227,-0.01397935,-0.02004547,-0.03656039,-0.03216825,0.0275799,0.05285741,-0.06795113,0.04100216,-0.07075732,0.0149708,-0.05106926,0.03196509,0.01857359,0.03778456,-0.00502232,-0.02995946,-0.00111731,0.08625203,0.02891893,0.0352513,0.01861954,0.13354856,0.02022381,-0.01090093,0.02194935,0.02139756,0.07639554,0.04194664,-0.09957053,0.05319482,-0.03300693,-0.01998618,-0.08687555,-0.04610635,-0.01663018,0.04574915,0.08499415,0.05692151,0.00191884,-0.016305,-0.00653292,-0.09354782,0.09160492,0.08930224,-0.02246419,0.05929135,-0.0724045,0.01370075,-0.03781015,0.06560704,-0.08583121,-0.01908077,-0.06906769,0.05850071,0.0036133,0.00074666,0.07860196,0.08742951,0.06372423,0.02059839,0.02523679,-0.02048756,-0.03660044,0.04770687,0.01939394,0.02922455,0.01136573,0.05770864,0.10400986,0.01855951,0.07902647,-0.04716525,0.04641842,0.07249694,-0.09456235,0.00702078,0.00882844,-0.0143781,-0.03542852,-0.0192296,0.10367403,0.069429,-0.06616658,0.00245679,-0.02977783,0.06723945,0.02599568,0.00181499,-0.02246655,0.06015673,-0.00237242,8.66e-06,0.01237197,-0.00161163,0.02379542,0.0623177,0.06302917,-0.02570774,-0.04766073,0.09089502,-0.03357351,0.0438128,-0.05247556,-0.00590211,0.09629831,-0.01518534,-0.01052057,-0.06610913,0.04017344,-0.01211906,-0.06806836,-0.05280514,-0.02763574,0.01817189,-0.03857343,0.05478868,-0.02021397,-0.05022834,-0.01876389,-0.03845818,-0.02226213,0.02734958,0.06168664,-0.0027804,0.01046481,0.03658353,-0.03717428,-0.10617768,0.08026054,0.03170785,0.08951136,0.05976803,-0.01144407,-0.0661207,-0.03869509,-0.00733168,-0.01151031,-0.02360636,-0.03778335,-0.08590557,0.09001156,-0.00275339,0.06118154,0.05715257,0.00814514,-0.05736603,0.09319757,0.00719396,-0.02425064,-0.02014919,-4.484e-05,0.0260263,0.02030959,0.02949478,0.09586579,-0.04678959,0.02461768,-0.0332618,-0.01565074,0.00286422,0.06584619,0.01977042,0.04428417,0.05875757,-0.06473502,0.10545476,-0.07580231,-0.01398501,0.00140021,0.05340149,0.11669966,0.00554071,-0.00243802,-0.08557216,0.00990312,-0.05503465,0.0256719,0.03769247,-0.03152404,-0.03161236,-0.01857489,-0.04453729,0.04462153,0.02544496,0.10989302,0.00494372,-0.01747861,8.2e-07,0.0839689,-0.07591312,-0.03666976,0.00686677,0.02512297,-0.06530204,-0.01191796,0.11700791,-0.06030656,-0.02915389,0.06011282,0.01930215,0.00509449,-0.00577208,-0.05788531,0.01025439,0.01782473,-0.02338221,-0.01057658,-0.01467136,-0.00440559,-0.04133443,-0.06115586,-0.00922338,0.11748897,-0.07545421,-0.00581255,0.05815939,-0.02521178,-0.08592969,-0.00137427,-0.027409,-0.02524302,-0.08081789,0.05801507,0.06228271,0.03338581,-0.01283188,-0.06169725,-0.13141817,0.15490706,-0.03701142,0.01105089,0.00050801,0.05682189,-0.03063474,-0.00118683,-0.08303028,0.00060762,-0.08837233,-0.06298618,0.02361758,-0.01588488,0.01579756,0.03153374,0.10458989,-0.02451551,0.00385743,0.05998378,0.0238349,0.05613488,0.08127091,-0.02751317,0.03761507]');
INSERT INTO public.detalles_proyectos VALUES (81, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:48:05.72936', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.05925068,-0.03747497,-0.06554396,-0.06347282,0.01856329,0.0415299,0.05377476,0.10685623,0.05460723,0.03196778,0.08279354,0.06442101,0.01426161,0.0007417,-0.13562989,-0.01493109,-0.05580729,0.00164277,-0.02545193,-0.01468712,0.0132404,0.01598016,0.07330317,-0.04391609,-0.01787274,0.02037033,0.03482323,-0.01704263,0.00341584,0.01304632,0.06558904,-0.06145763,0.01316492,0.08380699,-0.01494173,0.04393533,-0.08256437,-0.10029584,0.02182061,0.07503076,0.06523211,-0.00610733,0.03320326,0.08640354,0.08585673,0.06037054,-0.02271806,-0.01604923,-0.04371217,0.01633242,0.02850392,0.00413208,0.03778185,-0.01648953,-0.0161786,0.10706628,-0.07986466,-0.04913699,-0.07885514,0.02256624,0.03681554,0.04616365,-0.0425443,-0.05501776,0.01100808,0.11584439,-0.0081685,-0.05767765,-0.0091198,-0.05316713,0.02103463,0.05223362,-0.00291947,-0.12071764,-0.00130953,0.03160652,-0.02734314,0.04127144,0.01108991,-0.12584205,0.10424985,0.07652345,0.04712555,0.00176827,0.11890375,-0.02383992,-0.01895286,0.06245206,-0.00729361,0.00917773,0.04305568,-0.06614543,0.00908485,0.02611431,-0.04885596,-0.02181673,-0.00105996,-0.03111089,-0.057998,0.01686539,-0.00727856,-0.00139625,0.03950926,-0.05493696,-0.06238852,-0.05383612,0.00963787,0.0214844,0.03785872,0.00315767,-0.08622454,0.00416399,-0.03752857,-0.07686241,-0.00908461,0.0661759,-0.02461115,0.01082825,-0.02248694,-0.07010105,-0.05430985,-0.00608437,-0.13605554,0.0112975,-0.02149057,-0.08430942,-0.05646141,3.21e-06,-0.07987106,-0.01602008,-0.00933469,-0.00408661,-0.02661944,0.01917713,0.00905931,-0.12045219,0.05354382,-0.05263243,-0.04802468,0.08581738,-0.09023449,0.05450471,-0.06882542,-0.04295062,-0.01837071,-0.00357691,0.05667996,-0.01180245,0.04246951,-0.0507843,0.05409719,-0.00390888,0.07288364,0.0087514,-0.06223964,-0.00147278,-0.03561648,-0.00920601,-0.01965639,-0.01123199,-0.01697859,-0.08213739,-0.03854224,-0.04664787,-0.00275579,-0.00404482,-0.00727182,0.01521499,-0.01659469,0.04641366,0.02330178,-0.0487258,0.04125558,-0.00652024,-0.00590073,0.06888349,0.04427981,-0.02817638,0.00508188,0.01732542,-0.0125411,-0.00736206,0.07285189,-0.05632782,-0.06329135,0.07969406,-0.00122718,-0.08634257,0.10870047,0.01329681,-0.05255424,-0.00413914,-0.01895038,0.04435976,0.01566249,-0.01518438,0.09163385,-0.06740361,-0.00181399,0.00769066,-0.06145932,0.012579,-0.05515216,1.691e-05,-0.02480184,-0.00196143,-0.00609901,-0.034685,-0.08281297,-0.03834116,0.0072031,0.0100735,0.04163548,0.01352952,0.07415431,0.04811691,-0.04825307,-0.13594279,0.053152,0.02716416,0.02636822,-0.02861826,0.03203705,9.79e-06,-0.02460734,-0.06887246,-0.09604268,-0.02112676,-0.03868301,0.07451401,0.05378975,0.01698826,-0.01614856,0.01324593,-0.00913136,0.03950854,-0.00121857,-0.11889489,0.07318349,-0.02479184,-0.08955573,0.14931805,0.06334787,0.02727128,-0.02892712,0.06855976,0.052133,0.02930059,0.03624786,0.02327998,-0.0090085,0.0854945,-0.09212457,-0.00627728,-0.06393677,-0.02988164,-0.0665653,0.00189788,-0.06680981,-0.08173897,0.13036403,0.04231716,-0.09248656,-0.01629011,0.09620461,0.02152195,0.00771597,-0.023515,-0.02283161,0.00957707,-0.11102535,-0.0642013,-0.03442768,-0.01683184,0.00518137,0.0158,0.03648627,-0.06483453,-0.00697529,0.10704095,-0.01780985,-0.01667721,0.0011673,-0.02532093,-0.03949369,-0.01413426,-0.08802,0.04969863,0.0361064,-0.02721713,-0.01111619,0.08395679,0.04666321,-0.0330354,0.0629675,0.10614736,0.03325658,0.00139505,0.00617149,0.00879519,0.03306021,0.06298578,0.0023987,-0.00572087,-0.01463203,-0.00524449,-0.02678896,0.01367217,0.02317833,0.04104972,0.07347298,-0.05941938,-0.00329941,-0.08513018,0.01286764,0.04468953,0.0194444,0.00305922,-0.02291581,1.11e-06,-0.01570775,0.03718476,-0.05531328,-0.1150342,-0.00422939,-0.0473276,-0.02332504,0.00737749,-0.05463131,-0.01339876,0.01847537,-0.06700005,-0.01225674,-0.00934864,-0.03893824,0.00673474,0.01025172,0.08636674,-0.01835875,-0.07791511,0.04298008,0.01676851,-0.00817484,0.03374759,0.0118809,-0.00127945,-0.04114699,-0.04888707,-0.04104183,0.03008015,-0.00319994,-0.00567964,0.09543272,0.05643447,0.04699715,0.0655061,0.10699537,-0.02390887,-0.06485443,0.00253741,-0.05319513,-0.05711511,0.04206506,-0.003752,0.03024953,-0.06744282,0.08687716,-0.0408002,0.09775817,0.03818335,0.06504231,0.05108979,-0.04361028,-0.02236277,-0.0624283,-0.00970754,0.01476808,0.02779144,-0.06446841,-0.01884621,0.02751798,-0.04763774,-0.03617489,0.04219326]');
INSERT INTO public.detalles_proyectos VALUES (82, '2026-08-05', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo  ------------------------------------------------Naturaleza de la Comunidad: El CAIPA-Trujillo, Valera Estado Trujillo', '', '2026-08-05 09:48:05.817964', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.04716677,-0.06057356,-0.04440034,-0.00493835,-0.00489193,0.00485664,0.1029608,0.04147603,0.00886891,-0.02073908,0.07193383,-0.0344237,0.0137705,0.0082276,-0.10215218,-0.03451192,-0.02888579,0.05218385,0.03130703,0.0396698,0.04245239,-0.013605,0.04839506,0.01901894,-0.09533104,0.04103261,0.015713,-0.04546714,0.01439086,-0.0142618,0.00404924,-0.08165703,0.13193154,-0.00863611,-0.05205898,0.05631007,0.02590699,-0.02243045,-0.07538093,-0.04373183,0.0361135,-9.33e-05,0.05078609,-0.0563288,0.04642539,-0.02115643,0.05657109,-0.04939023,-0.04624572,0.04149926,-0.05625863,-0.05492771,0.09743567,0.00069605,-0.04100061,-0.06928335,-0.0161713,-0.01223203,0.04247358,0.01913369,0.05259098,0.00874278,0.04732342,0.00489786,0.10723429,0.06673481,-0.07134705,-0.0771285,0.00402038,0.02372522,0.00562487,0.05148009,0.03642773,0.02507052,-0.02581626,-0.02693431,-0.02273569,-0.01578488,0.03964786,-0.06460021,0.04873166,0.0403893,0.00970165,0.04317302,0.00024071,0.01959589,-0.04337939,0.05532605,0.07716616,0.0411885,0.09246427,0.08805824,0.00633322,-0.0654966,-0.04260259,-0.04465282,0.00683053,-0.04409181,-0.08142064,0.00385052,0.00397067,-0.02694161,0.03432046,0.07183266,0.02724951,-0.04999301,0.01880834,-0.05485206,-0.02267001,0.03032563,-0.03366657,0.01756761,-0.07523723,0.0024147,0.00263666,0.04968917,-0.02343718,-0.02340091,0.03337131,0.02546923,-0.03792505,-0.04346898,-0.05672238,-0.0099025,0.06227964,-0.0030468,-0.05209016,4.31e-06,-0.07982607,-0.03184207,-0.00592504,0.00187085,0.05747547,-0.01854185,-0.02685828,-0.07540421,0.06797367,-0.02642834,-0.01728932,0.01955598,-0.02942954,0.06438632,0.03203937,-0.07703609,-0.00448927,0.01809213,0.05538989,-0.05788671,0.02045272,-0.0097328,0.01160094,0.02540365,0.00372383,-0.00073987,-0.04556228,0.06866206,0.04520765,0.00157956,0.03930703,-0.02584676,-0.02926507,-0.10316547,0.04479457,0.01792279,0.11317866,0.04230945,0.04088533,0.05569305,0.01363829,-0.00984468,0.03977696,-0.06694614,-0.06582708,-0.01863349,0.04051781,0.0001979,0.03924062,-0.11294162,0.00925274,0.00275809,-0.1023283,-0.14650524,0.03482052,-0.11212661,-0.01356429,-0.05062974,-0.07561027,-0.01472356,0.09060003,-0.10231831,0.03332616,-0.06168695,-0.04379849,-0.03541259,0.08788563,-0.01110453,0.15770876,-0.07593841,-0.12197348,0.01294271,0.00962491,-0.00296436,-0.0115184,-0.01268402,-0.08257081,0.00643157,-0.01848004,-0.01337507,-0.03853697,0.03583707,-0.04472336,-0.0067058,0.03464803,0.01568487,-0.02084325,0.0763638,-0.05909136,-0.01409293,-0.00331973,0.00784961,-0.0393782,0.02263991,-0.05730282,1.426e-05,-0.07850326,-0.05598062,-0.03144148,-0.00635277,-0.07213985,0.01139056,0.0132486,-0.00073236,0.052322,-0.01158459,0.0656557,-0.05506611,0.00043717,-0.06397135,0.05997762,0.09496857,-0.08465706,0.0562475,-0.04397256,-0.12541553,-0.03186764,0.01967473,-0.01969299,0.0136506,-0.02573344,-0.00707219,-0.05131934,0.08763146,0.00957687,0.04775801,-0.12775186,-0.04605616,-0.03309966,0.09621622,-0.0158141,-0.05238146,-0.02641701,-0.031549,-0.07843854,0.0145844,0.08553347,0.00502444,-0.07637961,-0.00381942,0.00137783,0.02580267,0.01316108,-0.04475925,-0.09025946,0.04533254,0.11282286,-0.02702791,0.00883822,-0.01665422,-0.02667551,0.13375251,0.00359616,-0.03317598,-0.04025068,0.0098456,0.01431734,-0.0377044,-0.09812734,-0.03952191,0.03242353,0.05563275,-0.08436755,0.10799132,0.04386369,-0.04976459,0.05059256,-0.0426139,0.04670723,-0.05552945,-0.08887365,-0.00272668,-0.02695053,0.01721523,-0.03878082,-0.01087486,-0.03640137,0.05637805,0.05016947,0.03149363,0.00131779,0.06672274,0.01347384,-0.00038215,0.01189398,0.02307194,-0.05144924,0.00310751,-0.00451808,-0.02662981,-0.05533111,1.56e-06,-0.07970646,-0.03434329,-0.06705377,-0.04276682,0.01139101,0.06443339,-0.07281688,-0.00290105,-0.04038399,0.07328128,-0.01846353,-0.03094022,-0.0632544,-0.02255871,-0.03431236,0.03074864,0.05176483,0.05942897,0.00596043,-0.08411107,0.04237277,-0.03946909,-0.081602,0.0010489,-0.03495481,0.00778219,-0.13481148,0.01906901,-0.05844131,0.03620564,0.0065223,-0.06986051,0.00873249,0.01892298,0.07629934,0.01631677,-0.04151183,-0.0525841,0.00608643,0.08201166,0.11095172,-0.05080515,-0.0156104,0.01169391,-0.04247553,0.00939467,-0.01288174,-0.02087956,0.03368687,0.04524406,0.00977445,-0.01424635,-0.00235148,0.047424,0.00455135,-0.05666507,0.00532185,-0.01157953,-0.10816561,0.09900688,0.08044245,0.06602637,0.01085558,-0.03354631]');
INSERT INTO public.detalles_proyectos VALUES (83, '2026-08-05', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-05 09:48:05.91852', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.14262035,-0.04138631,0.00370947,-0.00561436,-0.02851785,0.06651385,0.00089725,0.06254293,-0.01737906,-0.04021238,0.08183172,-0.06141574,-0.03050535,0.00261522,-0.1582647,0.00436497,0.01398326,-0.00284185,-0.03734475,0.04313157,0.00818752,0.00258174,-0.05300255,0.05717705,-0.06107654,0.00398839,0.03956627,0.05659829,0.00959054,0.01730583,0.00510822,-0.02565085,0.09243306,0.03062636,0.05681187,0.00496713,-0.01758667,-0.05823644,0.08775705,0.01494081,0.04117054,-0.01151959,-0.02843318,0.00906447,0.0091825,-0.09397407,0.11119761,0.02713008,-0.05935883,-0.01888004,-0.06208323,-0.02891491,0.08075183,-0.07747262,-0.0375692,-0.0007294,0.00560188,-0.07043077,-0.04053161,-0.02658387,0.11733456,0.01573883,-0.05319407,-0.02281589,-0.01693424,0.03514697,0.0502642,0.00227657,0.02766347,0.00836341,0.03547676,0.03116904,0.05892881,0.00721512,-0.02799726,-0.01547093,0.02889463,-0.02459472,0.03354716,-0.12292739,0.10070048,0.01883764,0.00644567,-0.01518963,0.0024993,-0.11154404,-0.02155246,0.02933223,0.09641225,0.04422388,0.07507616,-0.04820938,0.06135874,-0.06291657,0.01767098,0.01455812,0.08179084,-0.07481995,-0.00210888,0.04169874,0.04107554,0.03084721,0.014028,0.03997235,-0.07960934,0.02972948,-0.02419631,0.00782063,0.0286128,0.0224161,-0.0550735,0.02195072,0.0010963,-0.07477008,-0.04631352,0.00699584,-0.03678406,0.01803029,0.0013145,-0.04025124,-0.06135763,-0.09204017,-0.06067452,0.01670593,0.07089831,-0.01553092,-0.07596397,4.95e-06,-0.0708984,-0.03440867,0.00015292,0.00151329,0.09629333,-0.03339804,-0.09062825,-0.05381652,0.00530432,-0.08348269,0.01399603,0.04929557,-0.06466352,-0.01470605,-0.03263694,0.06949649,-0.13414098,-0.02346022,0.01520415,-0.01325059,-0.00402645,-0.00108049,0.0191858,-0.01529325,0.02454874,0.01134474,0.01850094,0.00240911,-0.11634827,0.02297985,0.03968864,0.00099614,-0.0398379,-0.01083203,0.06012547,9.89e-06,0.00837147,-0.00923675,0.0271285,-0.01341141,0.07113278,0.05872927,0.02174099,-0.04623408,0.00917005,-0.05611143,-0.0277477,0.08502508,-0.01491911,0.02423714,0.07918105,-0.04225991,-0.03883448,0.01771338,0.04842227,-0.01599718,-0.07935447,0.03637282,0.02739484,-0.08212407,0.08600234,0.0275936,-0.0106347,0.06224919,-0.09370424,0.01240093,-0.08244038,0.05061654,0.14479887,0.02565301,-0.05874916,0.00913496,-0.02677805,0.10363659,-0.07400628,0.02794924,-0.05470984,-0.04982317,-0.03220139,0.04418559,-0.05549176,0.00610161,0.03063611,-0.04279974,-0.04846288,0.05873225,-0.01223216,0.08367771,0.00414071,-0.02809138,0.01882136,-0.01977319,0.00056196,0.02454361,0.00585311,1.585e-05,-0.01745377,-0.05301251,-0.02341244,-0.04106723,-0.05846588,0.01027124,-0.05270018,-0.04810286,-0.01625407,-0.07030061,0.00229863,0.03916752,0.01452991,-0.03488335,0.10405682,0.07375644,0.01136371,-0.09120964,-0.06509527,-0.09528051,-0.013108,-0.010817,-0.00758369,-0.01529048,-0.03290158,-0.00885676,-0.02598805,0.10577328,0.0482482,0.04952361,0.01375859,0.01937693,-0.04581746,0.01712822,-0.08575677,-0.04331399,0.07532756,-0.02121788,-0.05068172,0.07898097,0.09198089,0.01544739,-0.00094773,0.04197739,-0.0481865,0.07435264,-0.01937786,-0.17635016,0.03132395,-0.08557011,0.06362844,-0.04617631,0.02152551,-0.06240099,0.02024369,0.08891495,0.0847679,-0.02902935,-0.01703799,0.03185883,0.02547449,0.03532297,-0.06130538,-0.03097293,-0.02510285,0.02428338,-0.03795075,0.05820219,0.06338006,-0.09480544,0.02926515,0.02850721,-0.09300369,-0.00342623,-0.07012757,0.05833058,-0.06535749,0.00132352,-0.04986574,-0.01762528,-0.01343919,-0.09902743,0.02471149,-0.00978995,-0.02979932,-0.00995263,0.00260352,0.02129875,0.06666025,0.02383462,0.00293788,-0.05651473,-0.02635554,-0.08135119,-0.05496485,1.72e-06,-0.00094564,-0.06025674,0.03037308,-0.09536105,-0.02205638,0.00805455,-0.02121163,0.01507236,0.03720563,-0.02490383,0.04806051,-0.03404688,-0.03481889,0.06211267,-0.02360437,0.01099171,-0.0162388,0.03197199,-0.03635406,-0.11184483,-0.00253031,0.00552905,-0.10052729,-0.03036631,0.00406401,-0.01540986,-0.06201133,0.04353607,-0.07409975,0.01936941,-0.04824069,0.02741928,0.0089242,-0.0011138,0.05169126,0.06220078,0.08134569,-0.07164367,-0.05820338,-0.09192487,0.05945489,0.00778242,-0.10181576,0.02330524,0.02144457,0.00468096,0.05147969,-0.00755538,0.02991244,0.01774384,0.06770506,0.02186032,0.01155186,0.05731619,-0.07359243,-0.0370897,-0.02235213,-0.00011623,-0.07749762,-0.02832251,0.037197,0.03252252,0.06092875,-0.03253554]');
INSERT INTO public.detalles_proyectos VALUES (84, '2026-08-05', 'Pregrado', 'El propósito principal de este proyecto es realizar soporte técnico a los equipos de la institución (Escuela Técnica Comercial Madre Rafols)del Estado Trujillo municipio Valera. Y de igual forma dictar varias sesiones de capacitación formativas a los estudiantes de dicha institución cerca de software, hardware, partes, usos adecuados de un computador, donde podamos ofrecer nuevos conocimientos a los estudiantes. Todo esto aplicando nuevas tecnologías de aprendizaje que permitan el crecimiento y desarrollo del área de informática de la institución', 1, 'Escuela Técnica Comercial Madre Rafols', '', '2026-08-05 09:48:06.00888', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.11113653,-0.09847056,-0.06587983,-0.0278091,-0.02048023,0.0445723,0.03830233,0.05346307,0.0005648,-0.00905087,0.01738091,-0.04923003,0.03249343,0.02211552,-0.11263939,-0.07259414,-0.05508341,-0.08134898,0.07442327,0.04980288,0.01153283,-0.05004261,0.01934,0.168636,0.05075118,-0.01021985,-0.01205191,0.1265235,0.00617157,-0.10606607,0.03726709,0.03681074,0.03481001,-0.02615638,-0.03264555,-0.00969487,-0.05429325,0.04540054,0.00629432,0.0492539,-0.00973273,-0.01164474,-0.01807629,-0.00693643,0.00587292,-0.00580587,0.06115396,-0.04477966,-0.03977188,0.06139602,0.01791577,0.0262852,-0.03175168,0.00380026,0.02282866,-0.02497955,0.04450912,-0.07582345,0.0563843,0.04928672,0.01814527,0.06136974,0.04900569,-0.00372719,-0.03375904,0.01583295,-0.00295479,-0.01218774,0.03104972,-0.07395064,-0.02385116,0.04241795,0.03267425,0.08448248,-0.04064588,0.06824196,0.00509659,0.02343835,0.0885639,0.02524061,-0.08294172,-0.05119849,-0.05799668,-0.0292592,-0.0070741,-0.00291734,-0.01901958,-0.04119338,0.13653278,0.06228715,0.11735655,0.0335361,0.00537751,0.02071022,-0.04024693,-0.00383949,0.12199019,0.03942355,0.00231409,-0.03099705,0.01051708,-0.01522436,-0.01421112,-0.0270491,0.00874248,-0.02717713,0.04180658,-0.05151355,0.07916186,-0.07924361,-0.05661729,-0.02844751,-0.05436159,-0.03199817,-0.08017674,0.04949932,-0.05304914,-0.09340301,0.00155699,-0.0649601,-0.07456919,0.01176847,-0.08207798,0.01562511,0.05003335,-0.07416935,-0.0320702,4.3e-06,0.03520338,0.04339015,-0.03383348,0.11473578,-0.02545165,0.01261758,0.01038319,-0.03786846,0.06492896,-0.04573346,-0.03078079,-0.00064696,-0.00500671,0.08110048,-0.06801673,-0.01724071,-0.02643172,-0.07746772,-0.01049679,-0.00355136,-0.09680514,0.00070139,0.00149976,0.01196138,0.0764636,0.01507201,-0.11018049,0.06984492,-0.00399115,0.0395464,0.04621689,0.11283457,-0.04810449,0.01631783,0.06753859,-0.01671923,-0.02044163,-0.00603029,-0.04414023,0.06229289,0.06147795,0.00770515,-0.0237558,0.04985927,0.00413748,-0.06298528,0.0296215,0.04775569,0.0459636,-0.05207037,-0.04298144,0.01547753,0.03226361,-0.05903643,0.05743762,0.00585279,-0.0350038,0.0437946,0.0831613,-0.01140314,-0.00867591,0.03345886,-0.02676691,0.06377398,-0.01390238,-0.00570912,-0.07405101,-0.03357091,0.12204727,-0.0898266,0.05106224,-0.00098866,0.00041779,0.03588778,-0.05673053,0.0687296,-0.04947586,0.00521097,0.05318758,-0.04096175,-0.01156744,0.05140432,-0.05624387,0.01612877,0.07040964,-0.0194914,0.0723963,-0.02741967,0.07078337,0.09221125,-0.04665595,-0.08107654,0.01388013,-0.09901345,0.03148533,1.214e-05,0.03251788,0.01030094,-0.00998521,0.02141611,-0.05560171,0.02046262,0.05702163,0.00842993,0.02274116,0.03075579,0.07941167,-0.05915915,0.0271526,-0.07896963,0.00878512,-0.01413201,0.06757041,0.01046376,0.03124057,-0.02318121,0.01986677,-0.11265837,-0.03286071,-0.07503913,-0.06264001,0.03118114,-0.09976668,0.03922192,-0.03540744,0.02911802,-0.06185554,-0.05557773,-0.02698621,0.07883685,-0.02084738,-0.05940189,0.03193886,0.01351012,0.10109656,-0.07694982,0.01371313,-0.05043124,0.03749488,0.05905391,-0.04836949,-0.07873375,0.02949023,-0.13375136,0.08242142,0.02565107,0.0988922,-0.05494134,-0.04186655,-0.03598393,-0.03308153,-0.02561194,-0.04314169,-0.03815942,-0.04040295,-0.03200444,0.03215889,0.07119807,-0.03273045,-0.068999,0.00041422,-0.03251888,-0.03578958,0.03302523,0.06830345,-0.09312713,0.09391581,-0.08081446,0.05885247,-0.05342072,0.00586992,-0.08219173,-0.00639488,0.03654742,0.00945358,-0.04327352,-0.00768449,-0.01807202,0.00773943,-0.05536172,0.02460055,0.07184355,0.03545979,-0.03070943,0.03682725,-0.05403855,0.04031315,-0.00954288,0.03303467,0.02624504,0.09505627,1.27e-06,-0.03505869,-0.01825003,-0.03614083,-0.04494941,0.01329839,-0.00434969,0.01714791,-0.08874605,-0.04041197,-0.0140357,0.06575984,0.01152667,0.02301932,0.00873719,0.00439254,-0.03365988,-0.07559363,0.04456546,-0.04510416,-0.06052099,-0.01381653,-0.03286724,0.04575308,-0.04147094,-0.05032864,0.02292324,-0.07659118,-0.05051238,-0.01520852,0.0200728,0.02960247,-0.01792804,-0.00942359,-0.09131033,0.06943592,0.00712371,0.05606888,-0.01359969,-0.00071787,0.05752054,-0.00077751,-0.02957089,-0.0941701,-0.00976478,0.00727398,-0.02542516,-0.11313338,0.08079843,-0.04247125,0.02022168,0.04847553,-0.04875239,-0.01289296,-0.01914822,0.01517546,-0.05966451,0.01394697,0.00404783,0.01397366,0.01563791,-0.03596076,0.00460148,0.03453193,-0.07180388]');
INSERT INTO public.detalles_proyectos VALUES (85, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 09:56:42.188313', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.08940278,-0.00840193,-0.06608922,-0.04774869,-0.02501094,0.09822934,0.02283562,0.10030393,-0.07992499,0.03091388,0.06228523,0.00842423,-0.00239821,-0.04469942,-0.00529878,0.0427688,-0.03352859,0.03016328,0.04351359,-0.01822443,0.00166776,-0.01030428,-0.02743816,-0.02623061,0.02946352,0.08504768,0.03734616,0.00593619,-0.0066261,-0.0231558,0.02288006,-0.00747645,-0.01197646,-0.0153585,0.12356239,0.02112375,-0.09750238,0.01258773,-0.00103782,-0.00656064,0.08690717,-0.06455143,-0.05309234,-0.06406355,-0.00082641,-0.01629835,-0.07292479,0.04775501,0.02396012,-0.10280026,0.00196114,-0.0185955,0.02947068,0.03933132,-0.00951798,0.03264434,0.01382993,0.00232262,0.05360704,-0.04439954,-0.02892934,0.02355169,-0.09260991,-0.00585256,0.02607445,0.03713152,0.00712623,-0.01002389,0.01061847,0.01130492,-0.07412799,0.13202675,0.00638331,0.07441955,0.02368512,0.00813984,-0.03077107,-0.06286412,-0.05522584,-0.04751409,-0.06628801,-0.09917285,-0.06787408,0.05986637,-0.02503937,-0.00833961,0.01727296,0.02391015,0.0709724,0.00143045,-0.01460323,0.02772229,-0.00920574,0.03652807,-0.13251366,0.02927897,-0.09602436,0.04778211,0.04695092,-0.03907839,0.02345488,0.01251966,0.04802245,-0.01552821,-0.06997816,-0.01385405,-0.03236168,-0.02983054,0.07603255,0.00120785,-0.05372737,-0.04237847,-0.02152437,0.0189217,0.04452727,0.00615336,-0.05025163,-0.02119497,-0.04012537,-0.12948938,0.02814433,-0.01873718,-0.03447091,-0.04263244,-0.01871543,-0.05764512,-0.00714211,3.53e-06,-0.00080247,0.00237352,0.09795551,0.00928641,0.02910187,-0.02431433,-0.07058569,0.0047795,0.0080749,0.0140713,-0.07330288,0.01223563,-0.07637805,0.00647127,-0.13602106,-0.00623408,0.08124432,0.11737192,-0.0354985,0.02624597,0.03590301,0.11078853,0.02798058,0.03712999,0.00151538,0.07448704,0.03884044,-0.03767173,0.06487249,0.04365529,0.0253182,0.06466557,-0.0601541,0.06995139,-0.03270919,0.03458809,0.03204794,-0.02284724,-0.00782343,-0.00255397,0.0583651,-0.00414842,0.02640516,0.00580765,0.08934818,-0.13881172,0.12441597,0.08179789,0.0139279,0.13635848,-0.04820684,0.020359,-0.0450597,-0.01943921,-0.06924806,-0.00830961,-0.0362338,0.04841461,0.03935506,0.03390228,0.07802859,0.0994947,-0.00061473,-0.00121676,0.06323923,0.00417254,-0.00026657,0.02976748,0.07636592,0.02709392,-0.01925948,-0.00075105,-0.01060855,-0.00292453,0.05516993,-0.06814299,0.02915445,0.07343562,-0.04520527,0.00831083,-0.12055397,-0.03605034,-0.09888603,-0.11975274,0.04795865,0.01358745,-0.02501571,0.00437967,-0.05372047,-0.03002904,0.07909048,-0.03909078,-0.02587022,0.1146275,0.01572668,9.26e-06,0.04664551,-0.02926163,0.01051545,0.03000557,-0.0069742,-0.06154033,-0.05327206,0.07544846,-0.08628857,-0.05829267,-0.008961,-0.02343027,0.0548592,-0.04726449,-0.04007845,0.00922354,0.02791864,0.02982681,-0.06651135,-0.02380335,-0.03489562,0.0471696,0.0150169,0.06188341,-0.02022074,0.00306004,0.03441177,-0.0711131,0.03999222,0.03363262,0.01533441,0.05310743,-0.09367319,0.02362744,-0.06934205,-0.05270226,0.03172187,-0.02423251,0.09090599,0.02183581,0.00888221,-0.01715578,-0.07817761,0.00509058,-0.02854536,0.01480191,0.0282615,-0.02900454,0.07044615,-0.0028333,0.04088208,0.01100117,-0.00066765,-0.04805625,0.0181624,-0.00449053,-0.02642191,0.06963654,-0.05203676,-0.03043812,-0.00564836,0.01790301,0.05384084,0.01101114,0.01442906,-0.01884697,0.0706791,0.05110484,0.03748944,0.01520328,0.02942498,0.12095374,-0.06601066,-0.01773603,-0.06936085,-0.00793784,-0.05795814,0.02936166,0.0375902,0.08209027,-0.01638325,-0.03124624,-0.04798481,-0.03177862,-0.08074132,-0.01921569,-0.0065339,0.00471001,0.00835831,0.02341707,-0.04021752,0.08046094,0.09083622,0.09914346,-0.06406131,8.8e-07,0.03176064,-0.02688731,-0.05468844,0.06548384,0.06848714,-0.06140386,-0.11148441,0.05352994,-0.05301604,-0.06140598,-0.00994439,-0.02791313,-0.03041508,0.00378599,-0.0019027,-0.03695004,0.00591268,0.0154629,-0.02997631,-0.01551223,0.00623225,-0.04454055,0.01181364,0.05901126,0.03754523,-0.00871303,0.00670982,-0.05201633,-0.00925143,0.01099367,0.02116053,-0.03992961,-0.08198572,-0.09805371,0.09178945,0.07280454,0.1119836,-0.03894451,-0.01057173,-0.08930529,0.04684354,-0.02174859,-0.03888962,0.02721715,0.01178219,-0.04597665,-0.04491759,-0.04450397,-0.05935244,-0.06965621,-0.07097682,0.01788781,-0.03446112,-0.02205329,-0.0103318,0.12472873,0.01487836,-0.01556196,-0.00545718,0.07356964,0.02124233,0.02944239,0.0001281,0.07644293]');
INSERT INTO public.detalles_proyectos VALUES (87, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:34:46.219889', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.09522946,-0.01218499,-0.06354041,-0.04998595,-0.02646037,0.10264104,0.01858864,0.09583815,-0.08064845,0.03019358,0.05735914,0.01971635,0.00199288,-0.04986269,-0.00299239,0.04413859,-0.03721739,0.02449999,0.03232933,-0.0136856,-0.00413544,-0.00895107,-0.03403608,-0.02876193,0.02644825,0.08217514,0.03416761,0.00675859,-0.0097373,-0.02241976,0.02851517,0.00476514,-0.0068891,-0.01303921,0.12277363,0.02118583,-0.10097528,0.01338463,0.00273222,-0.00658715,0.08712343,-0.06507261,-0.04878686,-0.06109184,-0.00086006,-0.01716022,-0.07787242,0.0479449,0.02063448,-0.09624466,0.00048884,-0.01686482,0.03327458,0.0379346,-0.01161401,0.02553682,0.01619797,-0.0015374,0.04863051,-0.04626507,-0.02517908,0.02130516,-0.09164902,-0.00742518,0.02530996,0.03616664,0.00945629,-0.00966887,0.01609894,0.00764827,-0.06996663,0.1327212,0.01530495,0.07656542,0.02471692,0.0163034,-0.03364797,-0.06493268,-0.05240888,-0.04620674,-0.06774503,-0.09720062,-0.06998739,0.06165055,-0.02386387,-0.01694516,0.02181664,0.02327455,0.06956897,0.00365023,-0.0059497,0.02901823,-0.00510088,0.03017918,-0.14233115,0.02956919,-0.0962628,0.04315863,0.04472507,-0.03682586,0.02447289,0.01151645,0.04193619,-0.01547275,-0.06500877,-0.01238844,-0.02792192,-0.02191286,0.07414651,-0.0031224,-0.05569828,-0.04368522,-0.02325179,0.01906608,0.0432734,0.00372356,-0.04921999,-0.0220939,-0.03344784,-0.13147303,0.02734093,-0.0171872,-0.03129779,-0.0455339,-0.02399265,-0.05438186,-0.01045777,3.5e-06,-0.00342832,0.00256268,0.09879804,0.00380703,0.03415921,-0.02283655,-0.06813486,0.0076533,0.00888447,0.01099523,-0.07662683,0.01363384,-0.06701933,0.00235473,-0.14602774,-0.0095212,0.07980285,0.11518204,-0.04008819,0.03166357,0.04059373,0.10309687,0.02667272,0.03800849,-0.0008359,0.08061675,0.03844235,-0.03935038,0.06989045,0.04724249,0.02112989,0.06468148,-0.06220193,0.06798448,-0.03714319,0.04043512,0.02388857,-0.02954359,-0.01295739,-0.0029389,0.05850405,-0.0004264,0.01827842,0.00293734,0.08869639,-0.1317747,0.12297603,0.09219768,0.02030528,0.14005458,-0.05256366,0.02154126,-0.04563367,-0.02503333,-0.06871711,-0.01159892,-0.03808648,0.04775268,0.03683726,0.0324186,0.0819988,0.10918448,-0.00506375,-0.00307821,0.06295945,-0.00240499,0.00489668,0.03147507,0.06991705,0.02722574,-0.01387635,0.00377696,-0.00693996,-0.00469483,0.05561611,-0.06609176,0.03530002,0.07555271,-0.04085023,0.00876038,-0.11266829,-0.03810163,-0.09774304,-0.11541214,0.04351103,0.02236523,-0.02592649,0.00853315,-0.05331151,-0.02535433,0.08564847,-0.04335149,-0.02371844,0.11407472,0.01247516,9.12e-06,0.0456334,-0.02663635,0.01013565,0.02433377,-0.00225507,-0.06117082,-0.05422554,0.07931033,-0.08535934,-0.06037587,-0.0049163,-0.02127882,0.05522523,-0.05194478,-0.04280861,0.00081984,0.02929015,0.02728404,-0.07133288,-0.02202947,-0.03689993,0.05707789,0.00750101,0.06202989,-0.02070952,0.00684841,0.03898601,-0.0654554,0.04126046,0.03401068,0.01326545,0.05555152,-0.09201317,0.02016871,-0.06510918,-0.05759311,0.03633237,-0.02238108,0.08996298,0.02093853,0.00504541,-0.01400824,-0.07653887,0.00859345,-0.02668939,0.01469383,0.02503736,-0.03070003,0.07423854,0.00301412,0.04657584,0.01157771,0.00191468,-0.04471873,0.01827038,-0.00790664,-0.01885433,0.06732563,-0.05670817,-0.02890366,-0.01025845,0.02298872,0.05266868,0.01351474,0.02093114,-0.02112318,0.07121649,0.04650088,0.04447532,0.01103397,0.03302379,0.11785614,-0.06787654,-0.01239155,-0.06479494,-0.00252827,-0.05449242,0.02804199,0.03290748,0.08792318,-0.01329382,-0.02865877,-0.04622286,-0.0326112,-0.07615656,-0.02062242,-0.00860303,0.0017909,0.00361903,0.02216787,-0.04473171,0.07498255,0.08452121,0.09391512,-0.06190181,8.6e-07,0.04052483,-0.02178444,-0.05480688,0.06846622,0.06825901,-0.05569874,-0.1122819,0.05342656,-0.05320126,-0.05407345,-0.00813322,-0.03023112,-0.0342958,-0.00079165,-0.00623061,-0.04084971,0.00074261,0.00736569,-0.03317635,-0.01394082,0.00305397,-0.04456403,0.01334273,0.06120365,0.03369952,-0.00936003,0.01072912,-0.05062757,-0.00754213,0.00482164,0.01583258,-0.03842222,-0.09091545,-0.09318126,0.08991628,0.07299935,0.106286,-0.03682543,-0.01226549,-0.08693014,0.04497245,-0.02490521,-0.03915257,0.02827453,0.01006977,-0.05112216,-0.04993342,-0.04973693,-0.05935752,-0.0660233,-0.07310845,0.01475949,-0.03186356,-0.01711879,-0.01095311,0.13030563,0.01174206,-0.01501742,-0.00788042,0.07502581,0.02862195,0.0204488,0.00204164,0.07735766]');
INSERT INTO public.detalles_proyectos VALUES (90, '2026-08-10', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo', '', '2026-08-10 10:45:42.226083', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.03305351,-0.11323324,-0.06105924,-0.02606983,-0.03653689,0.02144249,0.04777593,0.0608454,-0.00173252,-0.00430647,0.06597803,-0.05146284,-0.00724518,-0.00833115,-0.07756007,0.00682706,-0.05067211,0.04388027,0.00915194,0.05258787,0.03865762,-0.02035476,0.00855783,-0.01163613,-0.07366289,0.03334415,0.01706276,-0.07940374,0.03167688,-0.02555976,0.01574131,-0.09711755,0.13225296,0.00515248,-0.00722583,0.02516411,0.03199875,-0.05940128,-0.07120025,-0.01996174,0.01806637,0.02683578,0.0346973,-0.08676005,-0.01143321,-0.05135757,0.02704953,-0.05768317,-0.02333433,0.06629197,-0.01055303,-0.03553982,0.08375561,0.01558143,-0.0075114,-0.05206963,-0.03071355,0.0017792,0.0723971,0.01938034,0.02962065,0.01515178,0.07698959,0.01615056,0.06772222,0.05099245,-0.05575525,-0.08046864,0.04529834,0.0414643,-0.00564158,0.04342987,0.05537717,0.05602505,0.00471035,-0.02715914,0.00576169,-0.00129998,0.07993635,-0.07302619,0.02543993,0.06504023,0.00243784,0.05639701,0.00041971,0.057983,-0.04922358,0.03538059,0.11997117,0.05151861,0.06414038,0.08174776,-0.04595784,-0.1035942,-0.0397305,-0.04508657,0.00692861,-0.05290565,-0.07306195,-0.01002674,-0.01135657,-0.03022405,0.01030779,0.04739854,0.01453774,-0.05130877,0.02674624,-0.09234715,-0.07011409,0.04272174,-0.00572976,0.02663359,-0.04027406,0.0374542,0.0144575,0.03041267,0.00883361,-0.04882062,0.03196214,0.00841571,-0.06513276,-0.03520607,-0.05055131,-0.01681521,0.04282405,0.00253795,-0.02419974,4.5e-06,-0.0737525,-0.07245298,-0.02536193,-0.01037369,0.04897667,-0.07177109,0.01545289,-0.07086435,0.06142642,-0.01264432,-0.04022772,-0.00724691,-0.02706991,0.07526832,0.01970923,-0.06852881,0.02244982,0.00508142,0.03785989,-0.02891698,0.00869766,0.00660283,0.01563371,0.01306989,-0.02719482,-0.00519138,0.01533227,0.06173147,0.10665595,0.01769535,-0.00866166,-0.0579337,-0.06820257,-0.08367885,0.03454734,0.03314856,0.13488075,0.05062444,0.03705226,0.04875117,0.02500001,0.007722,-0.00807577,-0.04927794,-0.07745558,0.00972566,0.04152262,-0.01826897,-0.00253589,-0.10977628,0.0030798,0.02201518,-0.05478828,-0.1571563,0.03731597,-0.10989224,-0.01989412,-0.06886904,-0.08529599,0.02258935,0.05327432,-0.12331919,0.02161065,-0.05746806,-0.02287639,-0.04852622,0.0463794,-0.05536512,0.09588874,-0.08904754,-0.10203745,-0.04347867,0.00410642,-0.02005184,-0.01872394,-0.0288282,-0.06956761,0.03086981,-0.04233157,-0.02332056,-0.06466534,0.07618743,-0.06600562,-0.00482142,0.04093166,-0.03376922,-0.03497325,0.01423509,-0.06899406,-0.02950535,-0.01722417,-0.03290867,0.00730641,0.02213852,-0.06660549,1.443e-05,-0.05497529,-0.01537672,-0.02317358,-0.01006752,-0.05076973,0.02132941,0.02144507,0.00866974,0.05730872,-0.02130779,0.06446298,-0.03237489,-0.03726149,-0.05411458,0.06672134,0.07290064,-0.05921357,0.08701429,-0.03890147,-0.11768872,-0.00473748,0.03866047,0.03287811,0.03089844,-0.01606071,-0.01958335,-0.06209501,0.11006989,-0.02907051,0.00972908,-0.08180028,-0.03619104,-0.0395042,0.09249874,-0.03661389,-0.03793888,-0.03466404,-0.02348384,-0.06540322,0.00345747,0.07557055,-0.01979868,-0.04314884,0.02378584,-0.00605231,0.04196478,0.00907729,-0.02456754,-0.06888031,0.02365608,0.05709503,-0.02575607,0.02422631,0.00884436,-0.0284562,0.1128043,0.00569194,-0.06435034,-0.04719368,0.01879973,0.00344235,-0.02975736,-0.072524,-0.0422696,0.04298639,0.07117298,-0.06214225,0.05467247,0.05578727,-0.05026942,0.07133019,-0.00870251,0.04673809,0.00776936,-0.08570024,-0.04852768,-0.00059397,-0.03716677,-0.00087847,-0.02809273,-0.06206973,0.05846832,0.03542915,0.03018554,-0.01473078,0.07859416,0.02019919,-0.01560721,0.00502795,0.05199782,-0.0618206,0.00913876,0.01135347,0.0179038,-0.02950731,1.52e-06,-0.08701174,0.00711651,-0.08021875,-0.04761611,-0.05166383,0.06890684,-0.09120409,-0.01370082,-0.02068682,0.04092431,-0.01618652,-0.00911157,-0.05455286,-0.0033306,-0.04624582,0.04081173,0.04344318,0.09748487,-0.01104137,-0.08982013,0.07124357,-0.03084935,-0.05868548,0.02114835,-0.02276238,0.04009142,-0.1252947,0.03933099,-0.04272705,0.07044431,0.01538575,-0.02677635,0.01531059,0.03283166,0.10176204,0.00775085,-0.03332735,-0.02249666,0.03135725,0.11230615,0.09634133,0.01342827,-0.03608722,-0.00494956,-0.06938868,-0.0002284,-0.02791326,0.05637725,0.05412735,0.08657642,0.00862352,-0.02078441,-0.03946733,0.04471957,-0.02214113,-0.06353418,-0.04049299,-0.02787479,-0.10739527,0.10783789,0.07507297,0.08693417,0.03081457,-0.01328349]');
INSERT INTO public.detalles_proyectos VALUES (92, '2026-08-10', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-10 11:34:04.575523', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.10910768,-0.05026503,-0.01290375,0.00492909,-0.04208741,0.0299179,0.00146372,0.0441023,-0.03590136,-0.04668182,0.08694613,-0.05881359,-0.00795472,0.00703488,-0.14402378,0.01172865,0.01244176,0.00661196,-0.03079522,0.01087731,0.02620462,-0.00615412,-0.02660012,0.03452633,-0.06818064,0.02634685,0.01793645,0.07404166,0.02037244,0.02075865,0.00897972,-0.02722954,0.07187055,0.02227487,0.04724746,0.01561467,0.01178186,-0.03211024,0.09018356,-0.00243919,0.04747884,0.00050767,-0.02942304,0.0327937,-0.00330735,-0.09131963,0.09329976,0.02334845,-0.0497194,-0.0199208,-0.04317822,-0.03077313,0.09081688,-0.06665977,-0.03886623,0.04354735,0.01606307,-0.02608282,-0.0284238,-0.02179781,0.10673768,0.02917976,-0.05657719,-0.0292909,-0.03810736,0.02745961,0.01858899,-0.01845112,0.02449843,-0.01564106,0.03743072,0.02471715,0.06407438,0.00884518,-0.00678145,-0.03314417,-0.00058169,-0.02517675,0.02820883,-0.12057162,0.10097614,-0.00549612,-0.00066794,-0.0109156,-0.00957898,-0.09966469,-0.01662921,-0.00680788,0.08877139,0.07094015,0.108256,-0.0441829,0.06555915,-0.08052119,0.00410319,-0.00763498,0.09168075,-0.05693808,-0.04148634,0.04825468,0.02512044,0.00587654,0.0007719,0.00031874,-0.07432764,0.0258329,-0.00763998,0.02331213,0.01917857,0.00888087,-0.03600912,0.03432156,-0.00884476,-0.0802607,-0.00637012,0.04211845,-0.0284761,0.04638923,-0.01684214,-0.02491623,-0.04543822,-0.07516917,-0.04938713,0.01204419,0.06125847,-0.02566356,-0.08067553,5.08e-06,-0.06411911,-0.00397581,0.01503984,0.01933882,0.11663006,-0.00928232,-0.10104557,-0.04132203,0.02120804,-0.0567048,0.01390001,0.07719666,-0.07585921,-0.00487965,0.00208771,0.05804159,-0.10004391,-0.03499358,0.0285542,-0.00957493,0.01082744,0.00148244,0.03137519,-0.00508986,0.0454943,0.02497765,0.02526563,0.00473018,-0.05653878,0.04151463,0.00375746,-0.01944912,-0.05713692,-0.02497871,0.05192079,0.00204929,0.02565591,-0.01209615,0.02187961,-0.05318834,0.04676282,0.06036251,0.04332969,-0.06125786,-0.01219122,-0.05893631,-0.0030602,0.09732258,0.01634102,0.04556091,0.06334902,-0.05179273,-0.00507883,0.03245003,0.04440526,-0.03926589,-0.05027608,0.06305668,0.05227611,-0.06526507,0.11104795,0.02704322,-0.02465251,0.07745327,-0.1102896,-0.00926338,-0.08866989,0.0382486,0.1338786,0.02281365,-0.07753251,0.01657012,-0.03287553,0.1216149,-0.09513863,0.011649,-0.05742713,-0.03618257,-0.024882,0.03143188,-0.06608524,0.00165842,0.02864853,-0.02189996,-0.02826748,0.04890936,-0.03187653,0.09278696,-0.00770992,-0.02618313,0.00106775,-0.01950686,0.02286153,0.03992436,-0.01049297,1.59e-05,-0.01996404,-0.05576301,-0.02832782,-0.07202158,-0.03876164,0.01667445,-0.06200842,-0.03492426,-0.00075083,-0.07910359,0.01799713,0.04823175,-0.00323227,-0.0324428,0.10519596,0.04472958,-0.00656112,-0.07342162,-0.06962522,-0.09707455,-0.00712344,0.01224393,0.0088123,-0.00347211,-0.03430768,-0.0086143,-0.0196646,0.12013757,0.0371828,0.06441711,0.01536239,0.00436547,-0.06208528,0.02073854,-0.11321324,-0.05391485,0.06263946,-0.01778396,-0.06569572,0.11123724,0.09443892,0.02098007,0.00259111,0.0380862,-0.05641527,0.06613233,-0.03661124,-0.20113692,-0.00730933,-0.09232414,0.03023009,-0.05241646,0.03208331,-0.04800796,0.02373264,0.08446162,0.05904319,-0.04810536,-0.00170772,0.01982091,0.03415118,0.02999258,-0.04883028,-0.00613425,-0.02626755,0.0152397,-0.02949605,0.08151478,0.03426843,-0.06390052,0.01675281,-0.00736703,-0.0996533,-0.00157201,-0.07238432,0.05429972,-0.08421368,0.0049625,-0.03770291,-0.04272399,-0.00684391,-0.12126402,0.02389331,-0.00891796,-0.02614813,-0.03704415,0.01973063,0.03199792,0.07856002,0.02215194,0.01462955,-0.05857153,-0.02046157,-0.06316867,-0.04345136,1.68e-06,-0.03430226,-0.0562026,0.0202102,-0.08973171,-0.0090582,-0.00047998,-0.05378311,0.03199547,0.01718152,-0.01468601,0.03593705,-0.05601804,-0.04077302,0.03424996,-0.02008269,0.02753371,-0.01483689,0.0513609,-0.02609702,-0.12338346,-0.00422989,-0.00988878,-0.10534694,-0.01804929,-0.00010164,-0.01662857,-0.07952689,0.03983358,-0.07689204,0.02899494,-0.05040234,0.0194493,0.01171296,-0.00445095,0.02184391,0.05698799,0.04938452,-0.08316385,-0.06610698,-0.09515369,0.04295248,-0.00097858,-0.06862307,0.03143513,0.04235182,0.01012348,0.07410415,0.00579689,0.06862945,-0.00230593,0.05165247,0.02967667,0.00241497,0.04245574,-0.08410944,-0.02944891,-0.0442226,0.02447304,-0.09010427,-0.05390882,0.04318625,0.0607739,0.05683825,-0.03810901]');
INSERT INTO public.detalles_proyectos VALUES (93, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 11:34:42.145006', NULL, 'Trayecto I', NULL, NULL, NULL, '[0.00784693,-0.06438086,-0.03354258,-0.04536917,-0.0473368,0.03173457,0.00540765,0.05259424,-0.01329749,-0.03780311,0.03892033,0.06671371,-0.0459436,-0.00881369,-0.0306086,0.00619595,-0.1145687,0.03027726,-0.04027452,-0.03098435,-0.02764751,-0.03946406,-0.0063206,0.03366447,-0.05373025,0.04785975,0.13886605,0.06623548,-0.03202648,-0.05326539,0.04656268,-0.02408441,-0.04832877,-0.01422211,-0.01537459,-0.03050936,-0.11472829,-0.17147243,0.058246,0.05738183,0.03113434,0.05006837,0.02844557,0.09796771,0.11167727,0.01796913,-0.04271128,-0.03254793,-0.07834104,-0.0625515,0.03955864,0.01234822,0.04980894,0.01590762,0.03004953,0.15147349,0.02163708,-0.00972727,0.03369753,0.02353443,0.04212686,-0.02485621,0.02995728,-0.07228991,-0.03906245,0.0639346,0.0271882,-0.05804775,-0.02266952,0.00739007,-0.00409307,0.04660206,-0.02111017,-0.10689029,-0.00415564,-0.03000146,-0.01765666,0.04538125,-0.03972175,-0.0489181,0.06641267,0.02036767,0.04032907,0.03059233,0.13128833,-0.05892391,-0.04101508,-0.01947091,-0.02031378,0.10340877,0.0082102,-0.02996184,-0.07817568,-0.00853757,-0.07047808,-0.01302701,0.07852986,-0.11231703,-0.02247457,0.02677672,0.01641955,-0.04792642,0.01565266,-0.00687959,-0.01980657,0.06819621,0.02475532,0.02993743,0.02704161,0.06297862,-0.04090437,-0.04588982,0.0048828,-0.04959775,0.004315,0.11842467,-0.00575046,0.06476488,-0.01416202,-0.07154529,0.00191749,0.00535908,-0.14334556,-0.01242224,-0.04987672,-0.03220422,-0.16458566,4.51e-06,0.06020422,-0.01964527,-0.0338558,-0.00104832,-0.0266371,-0.02255427,-0.03313574,-0.04009834,-0.00864468,-0.05778599,-0.04179421,-0.00536569,-0.08660215,0.01605517,-0.05248776,0.00269238,-0.01699356,-0.0087934,-0.03081226,-0.0013338,0.05282751,0.00560193,-0.01338022,-0.00882042,-0.02986479,-0.01048917,-0.04968698,0.00285752,-0.01878892,-0.00876385,0.0291307,-0.00206599,-0.03925258,0.02251633,0.04664804,-0.10675235,0.01197308,-0.01123117,-0.00338204,0.01815351,0.05219313,0.0755204,0.01476946,-0.06536119,0.06921491,-0.05491569,0.01325138,-0.0030949,0.0900062,0.05541957,-0.02806349,0.01526515,0.00667358,0.01463895,0.0347061,-0.0150809,-0.02246165,0.08999693,-0.00457691,-0.03048199,0.10931759,0.00325597,0.04949295,-0.04072923,0.0432286,0.10243125,0.00140164,0.00929555,0.05585994,-0.0858797,-0.03211197,0.04892377,0.06582875,-0.03320898,-0.04754046,-0.00614518,-0.06177629,0.06264189,0.00759403,-0.04432506,-0.02803095,-0.01114561,-0.00755575,-0.01405802,0.02860496,-0.0074868,0.02257593,0.01617533,0.00434475,-0.01443779,-0.01236991,-0.08152144,0.0723694,-0.05835169,-0.02491274,1.217e-05,-0.0148557,-0.02877802,0.01626702,0.00510935,-0.06461393,0.01588411,0.05465676,-0.06260488,-0.00059589,0.01270803,-0.02081392,0.1141408,-0.00940292,-0.11500679,0.15645666,-0.01563168,-0.02320731,0.13433123,0.02310101,-0.075525,-0.01855461,0.02704717,-0.00839072,-0.03524814,-0.036679,0.05401689,0.05499063,0.02895862,-0.02142493,0.01119403,-0.05990371,0.07232866,-0.027576,0.03290447,-0.07138864,-0.00022011,0.00151132,-0.02603568,-0.07227328,-0.00775191,0.00841654,-0.01068511,0.03942825,-0.00764499,-0.00354442,-0.00588034,-0.02113781,-0.00123892,-0.00350439,0.02043732,0.02464498,0.04224692,-0.07396794,-0.05986756,-0.0384144,0.01015938,-0.01523801,0.02742461,0.04122617,0.04087714,-0.01579503,-0.00188469,-0.01676534,0.0228266,-0.048782,0.0120745,-0.02179067,0.04922198,0.1321632,0.00766196,0.0312721,0.1594208,0.0952223,0.03088403,-0.00262526,-0.05400604,-0.01586121,0.08031141,-0.00561185,0.02631728,-0.01763714,-0.06705077,-0.03517452,0.03148721,0.04012979,-0.08585579,-0.00781366,-0.02375847,0.02989458,-0.14482574,0.0610728,0.08757837,0.0029155,-0.05336672,0.04365255,1.25e-06,0.08488793,-0.02530898,-0.10689903,0.02148925,0.03867638,-0.02482867,-0.01972606,-0.02706408,-0.08591836,0.02389377,0.01802883,-0.04405252,0.00549264,-0.09829397,-0.03602692,0.02206657,-0.007399,0.04687374,-0.03538453,0.0047436,0.036004,0.01066533,-0.06314635,0.02800596,0.01425664,-0.06159735,-0.07902827,0.01391746,-0.00801805,0.00264874,0.05830896,0.03284943,-0.0219665,0.03431466,0.09514823,0.01625007,0.06262564,0.01112576,-0.05032237,-0.00790869,-0.08820745,-0.12107347,-0.0125663,0.00843587,-0.03949013,0.01767996,0.03492416,-0.01563786,0.03035263,0.03201958,-0.01449254,0.0484296,-0.07258859,0.02181139,-0.06782913,0.09401197,0.0459713,0.01560422,-0.06940567,0.00915736,0.02181109,-0.0434097,0.01728052,-0.02181719]');
INSERT INTO public.detalles_proyectos VALUES (97, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 12:03:37.275866', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.00794993,-0.08306275,-0.02752284,-0.06164628,-0.03484474,0.02413307,0.00434308,0.06188212,-0.02219373,-0.03003917,0.04089635,0.05040484,-0.05625351,-0.006603,-0.05372341,0.00388794,-0.1236157,0.04979177,-0.0556273,-0.01866969,-0.03444043,-0.02885436,0.0035957,0.04886321,-0.05157054,0.01456256,0.1499516,0.06669877,-0.01811188,-0.06705997,0.05002361,-0.02486303,-0.02746667,0.00610164,0.01040617,-0.01504282,-0.07806749,-0.16196015,0.03007558,0.08077499,0.03362879,0.03190623,0.01231864,0.05464027,0.09331501,0.03065063,-0.01348106,-0.00815804,-0.03004344,-0.07406757,0.03756022,0.03071071,0.02462493,-0.00414967,0.03643936,0.14545521,-0.00432393,-0.07540534,0.01867295,0.03028593,0.03841537,0.01481763,0.03466707,-0.05669035,-0.03782888,0.07442913,0.00184401,-0.08258169,-0.04613638,-0.01068139,0.00013325,0.01975689,-0.03546494,-0.11933051,-0.0183354,0.0055004,-0.02481629,0.04012661,-0.07314705,-0.07044782,0.05121453,0.04172851,0.03918691,-0.0003019,0.08603729,-0.03223976,-0.05760597,-0.00096944,-0.01760159,0.11014133,0.04558242,-0.02088176,-0.0502733,-0.00696761,-0.04955598,-0.00678257,0.06274434,-0.09515564,-0.0228537,0.04856685,-0.03924926,-0.06399597,0.02220615,-0.01165497,0.01096739,0.07447266,0.03043283,0.0246795,0.08072447,0.04173326,-0.04236805,-0.03746908,0.00644394,-0.04107852,-0.0388682,0.07290635,0.02147607,0.0503131,-0.03886549,-0.07529408,0.03859582,-0.02008326,-0.11007212,0.00731864,-0.04633114,-0.04725783,-0.12420887,4.83e-06,0.03566021,-0.02688276,0.00100749,-0.02208909,-0.01863688,-0.05129252,-0.02041614,-0.06680223,0.00580505,-0.08447677,-0.03730344,0.03701067,-0.07419953,0.01568483,-0.06841562,-0.00417172,-0.02102927,-0.02289448,-0.0302005,0.03476032,0.04440356,-0.01779327,-0.00027465,0.00461583,-0.04159366,0.01242895,-0.04586135,0.00440296,-0.01392976,-0.00781662,0.00589488,-0.01867873,-0.02973001,0.01852783,0.04062269,-0.06644034,0.02745144,-0.01185248,-0.01102725,0.05358733,-0.01729014,0.03241374,-0.02354867,-0.03815866,0.04503449,-0.06253855,0.01437699,0.00881129,0.05609022,0.03713607,-0.05048521,-0.00139118,0.01691631,-0.01070329,0.05587424,-0.02784213,-0.01015244,0.11020803,0.00523138,-0.036959,0.12492142,0.00479615,0.02924458,-0.02461533,0.04076721,0.07538079,-0.02751799,-0.0147496,0.08290419,-0.11526335,-0.02340254,0.04079908,0.04882695,-0.00955999,-0.02219262,-0.01935788,-0.05388758,0.05477607,-0.00880967,-0.03038314,-0.04403373,0.00305988,-0.0196026,0.00217767,0.05903846,-0.01713832,0.02794415,0.0249933,-0.02726296,-0.07248292,-0.02807964,-0.05325917,0.08512385,-0.08992364,0.00769022,1.287e-05,-0.0338919,-0.03107449,-0.03549571,0.03889291,-0.08466221,0.04023463,0.03376353,-0.02143184,-0.02157262,0.00911801,-0.0413034,0.09863345,-0.01902803,-0.12311289,0.14358719,0.00634985,-0.03207871,0.1353307,-0.00225255,-0.05531255,-0.02516606,0.04253522,-0.02616727,-0.03569282,-0.03444326,0.05197339,0.0268419,0.04057819,-0.05257394,-0.01101739,-0.07114243,0.05000224,-0.05418084,0.01704587,-0.04998579,0.01624685,0.02521694,-0.00414798,-0.07694027,0.00646257,0.00834194,-0.01157233,0.03850629,-0.03823436,-0.01239105,0.00541535,-0.06679236,0.00046899,0.0036708,0.04912423,0.05000704,0.10568863,-0.05933666,-0.04053698,-0.05191345,0.04228996,0.05725043,0.05393965,0.0406988,0.04481165,-0.03780178,-0.01255626,-0.00579035,0.04304082,-0.0321437,0.0088687,-0.0290604,0.06727812,0.13052677,-0.0348165,0.05910087,0.16424917,0.11627839,0.04735834,0.0049008,-0.03823149,-0.03153174,0.05858128,-0.01082827,0.04655916,-0.00092846,-0.07325693,-0.03119371,0.02990286,0.02480649,-0.03084012,0.02677779,-0.04441437,0.00331739,-0.16002634,0.09162369,0.05585435,-0.01584154,-0.04936275,0.00311659,1.28e-06,0.0677489,-0.04706138,-0.08519365,-0.04735754,0.05550734,-0.01732404,-0.01672831,-0.02598499,-0.08329906,0.00019847,0.0362719,-0.01561272,0.00399737,-0.08172347,-0.02393967,0.05362414,0.01033639,0.04416827,-0.02733445,0.00523526,0.03886646,0.03797942,-0.08199919,0.00605398,0.01680142,-0.06604607,-0.07348673,0.03751822,-0.03198345,0.00470687,0.07890837,0.02437093,-0.0146441,0.04218186,0.08006628,0.01576739,0.04275744,0.01061181,-0.05775431,0.00472579,-0.10761088,-0.10529912,0.02953226,-0.01183605,-0.03550323,0.02258291,0.02252059,0.01119883,0.07535597,0.04554294,-0.01067451,0.0740333,-0.09729174,0.00800893,-0.06290174,0.0719014,0.06928905,-0.01997784,-0.08408821,0.01436197,0.02124585,0.01075381,0.0241722,-0.00419656]');
INSERT INTO public.detalles_proyectos VALUES (100, '2026-08-10', 'Pregrado', 'El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes', 1, 'Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry', 'App, Aplicación móvil, Coordinación, Ascensos', '2026-08-10 12:14:42.285533', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.16355579,0.002527,-0.09464699,-0.05355509,-0.0673431,0.04090834,-0.02878966,0.00246398,-0.03507158,0.02075229,0.08187348,0.04725265,-0.03191121,0.03477861,-0.06900981,-0.0614534,-0.05051573,-0.06034804,-0.00385434,0.05741652,0.03026107,0.03792606,0.02574715,0.06340853,-0.07204043,-0.03983652,-0.04593103,-0.01264307,0.08202502,-0.00321926,0.06528982,0.06372902,0.05587249,0.0107,0.05459473,0.00719343,-0.00166875,-0.04173785,0.08177865,0.04131487,0.08741273,-0.02848829,0.01120564,0.08329617,0.03707479,-0.10390184,0.01130223,0.00289202,-0.00540033,0.01250754,-0.02709614,-0.02265202,-0.00752965,0.05673415,-0.11473104,0.01173181,-0.06957965,-0.05856057,-0.05117027,-0.04627926,0.03898917,0.03215341,-0.00718139,0.03667661,-0.03585939,0.08051901,-0.06213507,-0.03021036,0.05154111,0.00049396,0.04427587,-0.04876702,0.01107249,0.06426055,0.04932133,0.02649767,0.01836587,-0.05116871,-0.00877688,-0.1366533,0.08165179,-0.01118056,0.03806786,0.10402817,-0.05545388,-0.02918643,-0.03419576,-0.02251555,0.03697366,0.00629094,0.02457744,0.01922326,0.06109344,-0.02316642,-0.04359489,0.00286086,0.106448,-0.01332156,-0.02065362,0.02509022,-0.06799945,-0.03204075,-0.05072069,-0.05342225,-0.03959477,-0.04249651,0.00345071,-0.06421184,0.11878851,-0.00550232,-0.04571945,-0.0723385,-0.09657281,-0.06681623,-0.01257316,0.07852172,-0.03655346,-0.00888261,0.09303333,-0.07837296,-0.03845398,-0.02267554,-0.10721326,-0.07911603,0.01977167,-0.04019222,0.015805,4.69e-06,-0.02630355,0.07424307,-0.01674724,0.01657882,0.07132863,0.02640844,0.07155188,-0.03128474,-0.02480739,-0.0762891,-0.03745839,0.01220293,-0.07989595,0.02507416,-0.105513,-0.07520771,-0.02547225,0.00748427,0.03259972,0.02198174,0.06046463,0.0584218,-0.02544832,0.01949694,-0.02184536,-0.02500973,-0.06535581,0.06636755,0.01676155,0.02149549,0.05675664,-0.01846186,-0.05469236,-0.01118589,0.03623693,0.03464155,-0.02395658,-0.02282144,0.04679783,-0.01412897,0.02759018,0.03521856,0.00669321,0.03429738,-0.02926029,-0.01387936,-0.13399246,-0.02467328,0.10387514,0.02012009,-0.02103906,0.06697957,-0.02065972,-0.0049204,0.0169298,-0.01820669,-0.07223756,0.06320417,0.0478884,-0.09391052,0.1234006,-0.04298614,0.00631067,0.06123054,-0.02785897,-0.04667427,0.02988069,-0.08588313,0.08289014,-0.05052226,-0.06208378,0.06663619,-0.02269959,-0.03895774,-0.03597911,0.06525715,-0.05567554,0.00043909,0.01259461,0.0562239,-0.07073919,0.02829207,-0.01493161,0.056654,0.07796455,0.04023327,0.04757918,0.06426642,0.03288726,0.0220993,0.10449915,-0.0809354,0.03224242,-0.06233685,0.02741106,1.3e-05,-0.05346201,-0.05691157,-0.01409401,-0.02290855,-0.04007033,-0.04785118,0.04173565,0.03412304,0.05641207,0.00738087,0.11107564,0.05565729,0.01793931,-0.07473312,-0.02599749,0.04598371,0.00854424,0.11832844,0.03005926,-0.01423946,0.01628535,0.04788688,-0.02614183,0.03090929,-0.03687025,-0.02307276,0.05631407,0.09253045,0.0460289,0.01558295,-0.00416791,0.05864373,-0.11239219,0.00686547,-0.08774767,-0.00361722,-0.02434379,0.01268341,-0.01503359,-0.08289893,0.07615606,-0.0462933,-0.02218537,0.07441495,-0.01332156,0.00143255,-0.06414402,-0.04288556,0.0508899,0.08116979,-0.02021183,-0.00508022,0.02711788,-0.09079132,0.00718965,0.0278869,0.01746546,0.02827411,-0.02066869,0.00779897,0.06107591,0.01283987,-0.0713619,0.04069342,-0.02682339,0.03826815,-0.07895295,-0.0420329,0.04411239,-0.01722241,-0.04113189,-0.03370406,-0.08442434,-0.054181,-0.03013928,-0.08473661,0.00296642,0.03561563,-0.01653813,0.00504088,-0.1441971,-0.03054049,-0.03961698,0.08505253,0.01982237,-0.02414676,0.00961148,-0.00066226,0.00208951,0.06556799,0.01865074,0.02229902,-0.05230609,-0.01100068,0.0784421,1.32e-06,-0.01465714,-0.03449061,0.02602584,-0.06221508,0.04943378,0.03962688,-0.05442194,-0.08411648,0.05604358,0.00206566,-0.01158138,-0.03786412,0.04705273,-0.04663446,-0.05474163,0.02377219,-0.00589636,0.06799734,-0.05874227,-0.0936587,-0.00805061,-0.01983545,0.03435492,-0.01008985,-0.01903431,0.06809449,-0.03655868,0.00763498,-0.06909083,-0.04043982,0.00741808,0.04474207,0.02038727,-0.02614289,0.00236072,-0.02042717,0.05129327,-0.03805049,0.00767006,-0.01264618,0.00914844,-0.02627734,-0.01861011,0.0498763,-0.02448723,-0.02510796,0.03598596,0.07841267,0.02257656,0.02204265,-0.03600293,-0.03542069,0.01194322,-0.06381142,-0.06608124,-0.00675856,0.01886026,0.06749093,-0.05565032,0.03979695,-0.00538486,-0.05551677,0.10334118,-0.06153547]');
INSERT INTO public.detalles_proyectos VALUES (109, '2026-08-11', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.', 1, 'Centro Clínico “María Edelmira Araujo”', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-11 10:10:03.006883', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.05519975,-0.07525839,-0.0299871,-0.0465447,-0.07432431,-0.02874134,-0.02618954,-0.01167847,0.02011059,0.02127491,-0.0029101,0.01858366,0.04887039,-0.06067661,-0.08191492,-0.06955663,-0.04934326,-0.06174338,0.02805221,0.02297879,0.05990111,-0.08252953,-0.03933649,0.06461508,-0.01840596,0.09100597,0.04726126,0.01326649,-0.07319526,0.00767138,-0.07782202,0.06060787,0.07055074,0.00475578,-0.01866865,0.04910358,0.03077495,-0.02209247,0.01979848,0.00951682,-0.008046,-0.05320539,0.0446172,-0.0427348,-0.08100767,-0.01434314,-0.02358449,-0.06903549,-0.06433004,-0.03380399,-0.08558385,0.0028346,-0.02614827,-0.00963713,-0.00578568,0.01861733,0.11437073,0.00305177,0.03519621,-0.03469974,0.03736759,-0.00560394,0.06474851,0.07693101,0.03555574,-0.02425057,0.01810429,-0.01458583,0.04244213,-0.06820851,-0.0374167,0.01547381,0.00705685,0.09832889,0.0056946,0.02222202,-0.05005459,-0.02735645,0.14910136,-0.08129441,0.02449219,0.03565166,-0.06326357,0.0570481,-0.02312577,0.05602697,-0.07817409,-0.01644013,0.17993712,-0.07236356,0.10774336,0.00785785,-0.01754964,-0.02518988,0.03646658,-0.06006233,0.08171848,-0.05571861,-0.11177376,-0.01121653,-0.00213827,0.03419836,0.03326906,-0.08511511,-0.02408702,0.05294534,0.00315721,-0.01724261,0.01258132,-0.05808726,-0.05727528,-0.07045208,-0.07266726,-0.08940374,-0.05835439,-0.03770502,-0.00604597,0.00361186,0.02953461,-0.06174498,-0.06343282,-0.09253781,-0.06414702,0.05606198,0.08847917,-0.00319218,-0.02131397,4.25e-06,0.02010658,0.01420836,-0.05155795,0.04853335,-0.04053984,0.0044844,0.03211059,0.07103963,-0.01052902,-0.08758302,0.00160384,0.04417264,0.03164426,0.04541372,0.00482177,0.01184126,0.00250803,-0.02067969,0.05824858,0.03929987,0.00792004,0.02833696,0.03835248,-0.07033023,-0.00463004,0.02835836,-0.05974865,0.03680754,0.04114887,0.04360763,0.0475443,-0.03488538,-0.07195452,-0.0432,0.04907924,0.01099442,0.03086266,0.01739222,0.05208906,0.05689496,0.06851876,-0.0085387,-0.03152023,0.02058941,0.04213918,-0.0126777,0.00108499,0.03324169,0.08256987,-0.09170192,-0.03724461,0.01232827,-0.07605733,-0.01505924,-0.00666311,-0.0577715,0.01720879,0.04408193,0.13045777,-0.00111889,-0.00110542,0.01980609,0.01861795,0.05249172,0.05437986,-0.05486407,-0.02620468,-0.01224439,0.12250535,-0.03457701,0.0117189,-0.04507818,4.11e-05,0.08519331,-0.06410034,0.03234362,-0.07940349,-0.01341984,-0.03093986,0.0377245,-0.03008149,0.02005929,0.00608479,0.00110794,0.10771217,0.01433173,-0.01463491,0.05617199,-0.08858758,-0.00807671,0.01516859,-0.09694493,0.04152214,0.03100636,0.03753661,1.327e-05,-0.04912993,0.0282549,-0.07195015,0.03541597,-0.0569155,-0.00849155,-0.02029443,-0.01942561,-0.04113637,0.01404833,0.05883788,-0.07432511,0.05185989,-0.05680582,0.08248446,0.01398656,0.05712416,-0.01794502,-0.01254732,-0.01451682,0.02107646,-0.13915256,-0.02154415,0.00406227,-0.03296834,0.02287657,-0.04503192,-0.0108384,-0.05347721,0.07349155,0.02808517,-0.02601417,-0.05729152,0.18003325,-0.02785917,-0.02972121,-0.0995334,0.00279253,0.00576151,0.04266036,0.07805514,-0.04633609,-0.00620267,0.02142765,-0.03185938,-0.04858927,-0.05536478,-0.05266359,-0.02458261,-0.01520989,0.0627989,-0.0151949,-0.04920484,-0.13708992,0.03904031,0.11783133,0.00063809,0.04906468,-0.08463066,-0.03565002,0.06925549,-0.00035504,-0.01825256,0.02787698,-0.02881139,0.12631527,0.02062386,0.09162695,-0.0289,-0.06763925,0.06244712,-0.02018555,-0.02945084,-0.0731467,-0.01197319,0.00205024,-0.01843691,0.0347952,-0.00311264,0.05207174,-0.02616866,-0.00750397,0.00178517,-0.01316958,-0.04738447,0.05552415,0.00369587,0.0025494,-0.01165972,-0.03723475,0.0178188,-0.02102945,-0.02689292,-0.05173438,0.05794207,1.37e-06,-0.00619953,-0.03308912,0.02483671,0.00996313,0.03651464,-0.10909667,-0.01487467,-0.00811173,-0.00190866,0.00099153,-0.00442964,-0.11632074,-0.0226909,0.05410329,0.08123258,0.03256137,-0.02600601,0.02098248,-0.04671311,-0.11570342,0.0838549,-0.09187675,0.00038391,-0.03541113,-0.06352964,0.00491092,-0.03344526,-0.02601099,-0.02752577,0.01422112,0.01145646,-0.09407015,-0.08242373,0.00650744,0.09690382,-0.00737696,0.01901103,-0.00948405,0.02793599,-0.00248621,0.01359916,-0.04216253,-0.02073437,0.03317745,0.05954042,-0.00169249,-0.06971618,0.03812006,0.0170896,0.04843053,-0.06183532,0.03987688,-0.02860132,0.00759037,-0.0007699,-0.0326672,-0.0201658,-0.00513494,-0.04380516,0.01460382,-0.0530331,0.07426394,-0.01503379,-0.05444185]');
INSERT INTO public.detalles_proyectos VALUES (110, '2026-08-25', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-25 19:01:06.312899', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.06889127,-0.02397583,-0.06647994,-0.06036997,-0.001629,0.04815263,0.04947122,0.10586292,0.05310008,0.03621769,0.07502788,0.06446808,0.03389049,-0.00213147,-0.15182403,-0.0061171,-0.06727865,-0.00053005,-0.00807697,-0.02638402,0.01192104,0.01404311,0.06792047,-0.04257207,-0.02259996,0.03671475,0.03798141,-0.01955811,0.02016705,0.02399854,0.06858621,-0.07867029,0.00861707,0.07727397,-0.02235433,0.05572181,-0.0747291,-0.09567361,0.02955302,0.06750221,0.0762616,-0.00355919,0.02667949,0.09158696,0.06855695,0.06434195,-0.02343081,-0.00217479,-0.05866648,-0.00045777,0.03779374,-0.00412515,0.04087628,-0.0195963,-0.02406547,0.10919519,-0.08600585,-0.03305805,-0.07548095,0.01860362,0.03206046,0.03707813,-0.03662219,-0.05149819,0.00294227,0.11157202,-0.00577254,-0.04477366,-0.01238678,-0.02976815,0.00930239,0.05602527,0.00548486,-0.12005038,0.00405121,0.03443163,-0.03405352,0.02965438,0.01375199,-0.14514793,0.10633689,0.06291211,0.04146007,0.01307787,0.1263289,-0.02753882,-0.02155769,0.04747704,7.265e-05,0.01096849,0.04253488,-0.07720586,0.0041865,0.01615993,-0.0391978,-0.0213812,-0.0044583,-0.02902564,-0.05862696,0.00524452,-0.00715198,0.00235569,0.0525283,-0.06092744,-0.05681555,-0.04783395,0.00393573,0.01826801,0.04166433,0.01342667,-0.095617,-0.00880963,-0.03185118,-0.08008897,-0.00188884,0.07082509,-0.01842617,0.0102963,-0.01845057,-0.0812131,-0.05171922,-0.00490524,-0.13714717,0.01274664,-0.01641429,-0.08957131,-0.04960005,3.31e-06,-0.08694728,-0.02172193,-0.02419292,-0.00276049,-0.03617553,0.01593342,0.00683005,-0.11200613,0.04745779,-0.03913846,-0.05220019,0.07983438,-0.08742987,0.06183439,-0.04996307,-0.02713631,-0.00543447,0.00378001,0.04877323,-0.01214701,0.03555222,-0.0610044,0.05383485,-0.0058337,0.08634783,-0.00607913,-0.04490816,0.00205283,-0.03013721,-0.01648709,-0.00431319,-0.00115776,-0.02817157,-0.08412745,-0.03057359,-0.03760534,0.00587826,0.00204436,-0.00637575,0.00751968,-0.00768627,0.0377,0.01699364,-0.03246731,0.05710336,-0.00691696,-0.01385827,0.05837769,0.05095339,-0.02525239,0.01217451,0.01790704,-0.0161163,-0.00188913,0.06207684,-0.06775449,-0.07510545,0.06972932,-0.01131789,-0.08845399,0.11154134,0.00194726,-0.05281515,-0.0240505,-0.00652546,0.03159349,0.01415232,-0.00484667,0.06610957,-0.06788444,-0.00399385,0.01096771,-0.06114968,0.01901557,-0.07218745,0.00247966,-0.02233973,0.00442433,-0.00565718,-0.03280772,-0.07446604,-0.03292426,0.00744819,0.0057599,0.04559274,0.01225248,0.07399348,0.04487796,-0.04927424,-0.11969572,0.0597855,0.02537406,0.01676929,-0.00851669,0.00877501,1.011e-05,-0.03750571,-0.06597184,-0.0943844,-0.01198352,-0.04464358,0.0746465,0.07109535,0.02007891,-0.00650419,0.00651028,-0.01065131,0.03877271,0.00067836,-0.10905483,0.08069343,-0.01898617,-0.08709325,0.1614662,0.06625973,0.03059634,-0.02213655,0.07695848,0.05236721,0.01788278,0.0296816,0.02419462,-0.00787015,0.07675166,-0.09860747,0.00708954,-0.05376709,-0.03359149,-0.07295972,0.00246937,-0.0720792,-0.08143602,0.12377599,0.02659896,-0.09529243,-0.01436292,0.10958351,0.02692136,0.00861519,-0.01368688,-0.01257369,0.00701085,-0.10790177,-0.05419072,-0.06250985,-0.01928764,0.00384272,0.01453276,0.04594441,-0.05337888,0.00516575,0.09814851,-0.03800559,-0.01733847,-0.01174801,-0.03880895,-0.03001104,-0.0176365,-0.10118027,0.05295483,0.03916651,-0.01932128,-0.00734994,0.08644807,0.05150847,-0.03301251,0.06100167,0.09421683,0.01108854,-0.01897267,0.01395801,0.01309886,0.03359654,0.05935564,-0.01472213,-0.01265362,-0.02381184,0.00253629,-0.01874511,0.01341388,0.03110161,0.04894892,0.07342253,-0.04160569,-0.00182188,-0.07731917,0.00368531,0.04403774,0.02342013,0.00560351,-0.01599625,1.15e-06,-0.02938548,0.04472966,-0.05883469,-0.1131611,-0.0047752,-0.04950599,-0.02837741,-0.00226255,-0.07477201,-0.00629928,0.00872542,-0.08002553,-0.00700319,-0.01514745,-0.0335842,-0.00234836,0.01609039,0.09518322,-0.02038779,-0.07827669,0.03553389,0.02088313,-0.01812967,0.04645036,0.01562208,0.00532308,-0.03809529,-0.06591186,-0.04558103,0.03392987,0.01043785,-0.00735704,0.08909864,0.0452533,0.05883238,0.07392304,0.09400199,-0.02106058,-0.04937332,-0.00403979,-0.05712857,-0.04681053,0.04885517,0.01087092,0.02816922,-0.06360955,0.08031069,-0.04332406,0.09543976,0.04064294,0.06591615,0.04701497,-0.04629232,-0.02016941,-0.05220267,-0.02113681,0.01625194,0.02411933,-0.06286017,-0.01809993,0.02858812,-0.03642895,-0.04466358,0.04209398]');
INSERT INTO public.detalles_proyectos VALUES (113, '2026-08-27', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-27 09:08:37.073105', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.08965559,-0.03699306,-0.0789203,-0.06459713,0.0144306,0.03893765,0.06719908,0.11220538,0.05119147,0.01737194,0.0831232,0.05768692,0.01260586,-0.01894344,-0.14810096,-0.00120706,-0.04277442,0.00071873,-0.03039893,-0.02352206,0.00842359,0.0296798,0.05645817,-0.03896243,-0.00614961,0.02214646,0.03633301,-0.00654234,0.01291489,-0.00084754,0.07955794,-0.04878486,0.00472519,0.06941441,-0.00395966,0.04403808,-0.09621088,-0.11391557,0.02864059,0.06544009,0.08799958,-0.01599372,0.02957686,0.08688277,0.08994068,0.06185264,-0.04303754,-0.0117238,-0.04379397,0.02405326,0.03059086,0.02330146,0.04605186,0.00642015,-0.02355016,0.07925457,-0.09974546,-0.04450735,-0.067507,0.03750848,0.04700788,0.03189038,-0.02781663,-0.06485672,-0.01253785,0.0997244,0.00369778,-0.08264546,-0.00637727,-0.04718065,0.00570909,0.06667963,0.01371734,-0.12410953,0.00215931,0.02166706,-0.00618489,0.00907822,7.314e-05,-0.13043162,0.09758508,0.07342151,0.0460949,0.00683145,0.11595377,-0.02335857,-0.0214969,0.06123859,-0.00629838,0.01501516,0.04415329,-0.07439117,-0.01407088,0.02651114,-0.04214068,-0.00646726,-0.00061757,-0.03064434,-0.07337792,0.01329729,0.00739607,0.00918254,0.0356823,-0.05046985,-0.07056437,-0.04418541,0.00428651,0.04216726,0.02251746,-0.00526732,-0.08671702,-0.00993218,-0.03410842,-0.0796079,0.00672098,0.08747085,-0.03708988,0.01934105,-0.01639799,-0.06589436,-0.04967707,0.02270506,-0.14613508,0.01381407,-0.03988078,-0.06445204,-0.05083992,3.72e-06,-0.087293,-0.02402615,-0.01269659,-0.01558287,-0.00911645,0.02302037,0.00596275,-0.10934651,0.06209255,-0.06154112,-0.05060145,0.05950769,-0.10223864,0.0431577,-0.08988638,-0.05805909,-0.00280295,-0.00552867,0.06228009,-0.0322644,0.0404999,-0.05142536,0.04072896,-0.00608029,0.07560357,0.03159368,-0.03655762,0.00313446,-0.02946532,0.00062206,-0.0170196,-0.00699104,-0.01292821,-0.08076871,-0.03777447,-0.06568354,-0.00965738,-0.00613467,-0.00927658,0.00902275,-0.01900305,0.06097918,0.01776757,-0.05828858,0.0414191,-0.01611506,-0.01386331,0.10105472,0.06378983,-0.02452964,-0.00053245,0.01763396,0.01406539,-0.01081139,0.06541112,-0.06026645,-0.06653606,0.08255017,-0.00354226,-0.08131662,0.09973258,0.01141361,-0.04748656,0.00072928,-0.0231418,0.05430954,0.02759899,-0.00315209,0.05427202,-0.05958929,-0.01605864,0.01467613,-0.05992744,-0.00139982,-0.03811278,-0.01055775,-0.00930578,0.00257318,-0.01541299,-0.03010192,-0.06591845,-0.01130083,0.02754206,0.00828943,0.01780877,0.00785537,0.04524654,0.05178014,-0.0335623,-0.12159862,0.05348375,0.03141894,0.03344489,-0.02305404,0.03176992,1.064e-05,-0.02674493,-0.05444628,-0.08943579,-0.02255188,-0.05130181,0.06762075,0.04444361,0.0275935,-0.00632564,0.01525723,0.0232028,0.06223703,-0.02435208,-0.13509005,0.06853439,-0.02673342,-0.09466717,0.14934982,0.0726047,0.0265547,-0.01855397,0.09256442,0.04897777,0.02416485,0.02433548,0.02274184,-0.00318884,0.10704042,-0.07522907,0.01186262,-0.0671403,-0.03367596,-0.06660095,0.00346936,-0.07204902,-0.08984584,0.13698928,0.03775571,-0.11003705,-0.01774988,0.09829703,0.02048369,0.00175264,-0.02922596,-0.02233027,0.00757317,-0.07377653,-0.08113484,-0.02590917,-0.03060633,-0.00593599,0.0033972,0.02478833,-0.04970925,-0.00471596,0.10952426,-0.01070998,-0.00875899,0.00791628,-0.04554799,-0.03080447,-0.01070404,-0.06145477,0.02854174,0.04694559,-0.01106603,-0.00946223,0.07769729,0.05992627,-0.03033411,0.0666598,0.09781855,0.02667117,-0.00287221,0.00558434,0.00367797,0.03066291,0.04456478,-0.01300213,-0.01910104,-0.01148374,-0.00870341,-0.02739495,0.01232548,0.01289551,0.03941395,0.07523596,-0.02580743,-0.01590109,-0.06766831,0.00016702,0.03997997,0.01374817,0.03602979,-0.02427035,1.15e-06,-0.02185002,0.04299352,-0.06335094,-0.11743642,0.01045505,-0.04343622,-0.03753272,0.01662342,-0.04433161,-0.01845286,0.011499,-0.05773415,0.00643239,0.00184217,-0.03737974,-0.00309186,-0.00464714,0.06990904,-0.0150113,-0.090795,0.04698393,0.0109829,0.02035398,0.01481046,0.01184786,-0.01207754,-0.05332387,-0.02379767,-0.05255908,0.02355578,-0.02000614,-0.00158198,0.09679378,0.03682762,0.04934012,0.08412806,0.08162182,-0.02737952,-0.06291452,-0.00109188,-0.05645057,-0.06021906,0.04034107,-0.00497286,0.02226767,-0.0759262,0.09517104,-0.0547621,0.08290925,0.04383795,0.05236708,0.0378703,-0.01816339,-0.0232609,-0.04817362,-0.01353745,-0.00367933,0.00648345,-0.05697908,-0.01668757,0.0338002,-0.05466771,-0.03887783,0.04439954]');
INSERT INTO public.detalles_proyectos VALUES (114, '2026-08-27', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-27 10:17:48.017574', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.08477283,-0.09446006,-0.07529146,-0.05138322,-0.07303345,0.027892,0.01567125,-0.0040503,0.01724909,0.02194093,-0.01743039,-0.01611238,0.09058332,-0.00019843,-0.08229455,-0.02100608,-0.04339586,-0.14246283,0.0811513,0.00949695,-0.00779425,-0.03721448,0.02949926,0.12834698,0.01289814,0.00812641,-0.00749551,0.00755871,-0.02309274,-0.05147042,0.00478268,0.04647189,-0.00601191,-0.00599138,-0.0550763,0.03483341,-0.0111235,0.04190723,0.00414975,-0.00129897,-0.04613688,-0.01627532,-0.00833355,0.00420082,0.00152183,-0.01582907,0.04040195,-0.05594858,-0.05804808,0.01473886,-0.05578396,-0.00443449,-0.0105381,-0.0180665,0.03906324,0.02557918,0.12130061,-0.04208475,0.07416127,0.035291,0.01643605,0.02560725,0.03630529,0.01959293,-0.02787246,0.02204426,0.00498593,-0.02761758,0.07546911,-0.05498451,-0.00842227,0.03628227,0.06679041,0.06017874,-0.02349109,0.02432378,-0.04848125,0.01884569,0.13937883,-0.05082421,-0.01430994,-0.00652703,-0.0947136,-0.01457716,0.0342657,0.02797788,-0.00875524,-0.03254069,0.13558663,0.03344351,0.10687198,-0.02653219,0.02635921,0.02311918,-0.06757576,-0.09776267,0.13413414,0.02364406,-0.01783907,-0.01410257,0.00030171,-0.02422371,0.07962305,-0.03515041,0.0061492,-0.03147714,0.05940725,-0.0304163,0.09188807,-0.12011132,-0.0766074,0.00407704,-0.03247795,-0.01760314,-0.04492001,0.02048883,-0.03364069,-0.06499644,-0.00109691,-0.06090652,0.00602714,-0.02185773,-0.04709969,0.0326073,0.09503832,-0.06966541,-0.05105739,4.05e-06,0.09616213,0.03603198,-0.03050236,0.10461377,-0.00588568,-0.0066837,0.03918072,-0.02069502,0.06061207,-0.04736307,0.00217242,0.00812677,0.00868845,0.1130674,-0.03998112,0.00538298,-0.03243158,-0.08458656,0.03152441,-0.0329801,-0.03405586,0.03760808,0.01170641,-0.00281798,0.05588453,-0.00161465,-0.11221063,0.05235212,-0.00610449,0.05213122,0.02123236,0.08786946,-0.0953734,0.01500961,0.09051141,0.00381519,0.01885262,-0.05394249,-0.01316896,0.0828617,0.0922328,0.00578441,-0.0389753,0.01388324,0.04379047,-0.07934416,0.00817192,-0.01855314,-0.01197825,-0.03334624,-0.09261749,0.01470402,0.05732266,-0.04780038,0.01746734,-0.0374312,-0.01040981,0.05484561,0.15458466,0.0040509,0.02488409,0.02154803,-0.00204499,0.06235825,-0.01501939,-0.04295898,-0.04383864,-0.04242154,0.08015048,-0.03129033,0.05939279,0.01560312,-0.01083869,0.05743831,-0.08053197,0.05526952,-0.05994257,-0.02582024,-0.0209923,-0.04857182,-0.00982617,0.0221269,-0.06281909,0.02932524,0.07407188,0.03612272,0.06877967,-0.04025233,-0.01228649,0.03960856,-0.09063198,-0.06781349,0.04399394,-0.06068221,-0.02292459,1.159e-05,-0.01201734,0.02787687,-0.03058812,0.04399707,-0.05829332,0.01024041,0.00585156,0.01624824,-0.00890808,0.01418702,0.05896837,-0.07149959,0.06123691,-0.06814406,0.07278253,-0.03976704,0.09691255,-0.00345541,0.00178522,-0.01451899,0.05971774,-0.11228657,-0.04729543,-0.06318789,-0.00931557,0.04764175,-0.03502217,0.02777806,-0.03939069,0.02920603,-0.04648935,-0.00579,-0.01447793,0.08059554,-0.01833262,-0.03744458,0.02838076,-0.00867894,0.06962642,-0.03028903,0.08902898,0.01662089,0.01865749,0.08404097,-0.06592503,-0.03322092,-0.02425493,-0.12599492,0.11440497,-0.03049943,0.08529971,-0.08544596,-0.02690981,-0.11000076,-0.04019284,-0.0397443,-0.04758915,-0.02129962,-0.02658031,-0.00468479,-0.03673994,0.03037623,-0.05307073,-0.04259374,0.00396602,0.00075542,-0.03960963,0.11932952,0.0207384,-0.07341052,0.11185643,0.00471393,0.02730354,-0.05725055,-0.01304205,-0.06072695,-0.06356601,0.08158625,0.02361638,-0.02151412,0.04502965,-0.02675627,-0.00214251,-0.09304862,-0.02359415,0.05751144,0.0059184,-0.02141587,0.00413486,-0.11805661,0.05113562,-0.03410647,0.03791706,-0.01664446,0.09334421,1.17e-06,0.00712019,0.01743587,0.00791186,0.00178325,0.04781268,-0.0193416,0.01212298,-0.03874399,-0.03952811,-0.01638722,0.03362477,-0.03704255,0.00772903,0.04432446,-0.00901401,0.02883567,-0.10702396,0.00577663,-0.03886198,-0.05641674,0.04761727,-0.04812703,0.02969556,-0.01848223,-0.05633084,0.01758221,-0.07350355,-0.08662341,-0.04824171,-0.07096977,-0.00664087,-0.04006043,-0.04717062,-0.04533828,0.06049073,-0.02457578,0.02382222,0.00816878,0.01873786,0.03252788,-0.0452305,-0.03014618,-0.08434286,0.0182428,-0.03766483,-0.03314935,-0.09971986,0.05037158,-0.07311748,0.00766182,0.03596208,-0.01961293,0.0296963,-0.00665538,0.00060175,-0.04707952,0.04653566,0.03537975,0.0035473,0.00744304,-0.01654549,0.01259787,-0.00344147,-0.05353544]');
INSERT INTO public.detalles_proyectos VALUES (117, '2026-08-31', 'Doctorado', 'El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” NUES Dr', 'Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa', '2026-08-31 10:08:06.559751', NULL, NULL, 'https://github.com/Zhailox/proyect_CIIDI', NULL, NULL, '[-0.08714943,0.06022811,-0.00524983,-0.07749216,-0.06449809,0.02341666,-0.00610037,0.05432112,-0.10613631,-0.01080035,0.05779677,0.04995233,0.07275464,-0.08870173,-0.04677243,-0.02665579,0.0357445,0.02542848,0.00856309,0.00575867,-0.05293153,-0.05318136,0.053314,-0.01948317,-0.03751812,-0.02676114,-0.01734255,0.09230942,0.02893392,-0.01422793,0.03802009,0.02370934,0.0308394,-0.01734706,-0.01987144,0.06490175,-0.01518844,0.02543229,0.0166164,0.03848204,0.06409044,0.00097451,0.00640134,-0.00282955,0.05866042,-0.00424059,-0.11896117,-0.04109224,-0.03967964,-0.04673261,-0.08100135,-0.02748098,0.12769334,-0.00952607,0.03260184,-0.04627605,-0.06412398,-0.04195642,0.05257919,-0.04603255,0.02241324,0.06620404,-0.01455529,-0.03172891,0.053572,0.01471369,0.07387759,-0.09256734,0.02122677,0.05468031,0.04621435,0.02190803,-0.04471178,0.02182263,-0.04297509,0.06103091,0.06053728,0.01289754,0.00992899,-0.06213893,0.05217754,0.00497983,-0.06309098,0.06308517,-0.01288385,-0.03219108,-0.07302063,0.03194448,0.02850443,0.00836995,0.01869231,-0.01879798,0.08030425,-0.0478559,0.07827125,0.09427178,-0.04734109,0.00468451,-0.00884946,0.02298668,0.0116863,-0.04279449,0.03339889,-0.00483533,-0.0803506,-0.03383011,0.03281476,0.04081495,0.07620537,-0.02526625,-0.04305373,-0.01765013,-0.01222228,-0.04527377,-0.0687159,0.07651092,-0.0244801,0.00725539,-0.00355605,-0.03260563,-0.07473148,-0.08313147,-0.0712143,-0.02622009,0.02373261,-0.01710658,-0.07185998,4.23e-06,-0.02530602,-0.095342,0.0046839,-0.0672618,-0.01133111,-0.07728494,0.02870952,-0.05257142,0.00025789,-0.05085509,-0.08239036,-0.04230105,-0.11251826,0.10993381,0.02174222,-0.12192362,-0.08194435,0.04121679,-0.02160116,0.05665602,-0.01457125,-0.13899797,-0.00921564,-0.03491396,0.03632855,0.04399875,0.03915512,0.01893121,-0.10780618,0.018187,0.04817787,-0.061957,-0.02140911,0.00221408,-0.08059879,-0.04397635,-0.03384541,0.0247995,0.02333313,-0.00175457,-0.00516333,0.0797721,-0.00664598,-0.00321676,-0.02520705,0.06082914,-0.10436313,0.03138857,-0.00512831,0.05646101,0.04937637,0.04888539,-0.03678387,-0.00091591,0.03965681,0.03101289,0.02235529,0.08304839,0.00652735,-0.03729419,0.01781689,-0.08412797,0.03769053,0.03620828,-0.00708334,0.07856412,0.00065155,-0.0599648,0.06630853,-0.00678721,-0.04226136,-0.12414207,-0.0326307,0.06278581,0.01656324,0.03528818,-0.02798069,0.04348414,-0.07099192,0.01153079,-0.04891725,0.10073898,-0.10792046,-0.0482847,0.10332463,-0.05550781,-0.00626984,-0.00857979,-0.03839957,-0.05553042,0.00505033,-0.01959501,0.00573185,0.04097881,-0.02863248,1.364e-05,-0.00054824,-0.03294815,0.05036512,-0.00987972,-0.05260971,0.03319122,0.00323158,-0.03392228,-0.02979279,0.07195253,0.04287921,0.02601735,0.01014148,-0.08969828,0.01052257,0.03970051,-0.02223876,0.07854536,0.12322513,0.00178814,0.00428869,0.08515614,0.03613623,-0.02337529,0.03293568,0.00767999,-0.00401943,0.1377668,-0.02969901,-0.00647796,-0.09090707,-0.00898833,-0.0812622,0.06025869,-0.01670299,-0.01438387,0.05345945,0.03472529,-0.00710736,-0.03966495,0.04957947,-0.00801658,0.00854126,0.06696494,-0.02640684,-0.00486956,-0.04010875,-0.11573743,-0.00702632,-0.0053876,0.04203734,0.03372391,0.12090332,0.02066174,0.00927783,0.0752178,0.01737165,0.03951132,0.01772338,-0.03945598,0.04724852,0.05571768,-0.04218955,0.04918725,-0.07267483,0.10991456,-0.02942932,-0.00460032,-0.06018283,-0.02220501,-0.09355451,0.00337392,0.05974789,0.06779116,-0.0585283,0.06367429,-0.0949397,0.03389112,-0.00231367,-0.00659474,-0.04999915,-0.02383059,-0.09809575,-0.0517115,-0.03767812,-0.03906949,0.02944878,0.0120528,-0.01615831,-0.02213475,0.04039799,0.00824263,-0.01564565,-0.03347775,-0.04243004,1.63e-06,0.01742248,-0.06290608,-0.04552176,-0.02451361,-0.01558125,-0.03464053,-0.05133542,-0.0214984,0.03295124,-0.02222017,0.05694641,-0.03184433,-0.02782567,0.0025462,-0.00234152,-0.12868693,-0.03761837,0.06784173,-0.10320005,-0.06543622,0.0638655,0.04182837,0.04517553,0.03190289,0.03798085,-0.01109707,0.03153975,-0.03216588,-0.03134896,-0.0019424,-0.03387276,0.04772922,-0.07928026,0.00149696,0.01811288,0.09482087,-0.04959241,-0.04550052,0.0038524,0.00909593,-0.00619701,-0.06656876,-0.06961989,-0.03069003,-0.01280583,-0.04551012,-0.03067713,0.01033998,-0.04539389,0.03343189,0.03182596,-0.01197549,0.00850599,-0.11292785,-0.06228383,0.03573506,0.00148429,0.02759857,-0.04842923,0.0984449,0.02496014,-0.01683026,0.11396875,0.0145593]');
INSERT INTO public.detalles_proyectos VALUES (127, '2026-09-02', 'Pregrado', 'Aunado a esto, el proyecto responde a una necesidad real: la baja fluidez en teclado y la falta de incentivos lúdicos para aprender mecanografía, problema especialmente urgente en entornos con brecha digital; por su diseño ligero y opciones offline, Tippen Tag es viable en mercados con acceso limitado a internet (Venezuela) y adaptable/competitivo en mercados con alta adopción tecnológica (Alemania).', 1, 'Comunidad / Organización No Específicamente Nombrada', '', '2026-09-02 15:10:22.258855', NULL, 'Trayecto I', 'https://github.com/Zhailox/proyect_CIIDI/tree/Zhailox', NULL, NULL, '[0.01369965,-0.06421646,-0.04691606,-0.02233892,0.13075797,-0.02029471,0.06274536,0.09932553,-0.0048536,0.01614749,0.10927355,-0.03056669,-0.02743479,-0.02291764,-0.02957462,-0.03687999,-0.03971248,-0.12963074,0.02053453,0.09108634,0.07298626,-0.01930663,8.438e-05,0.04993797,-0.07858004,0.01166147,-0.05277085,0.00307372,-0.04488514,-0.09554818,-0.0358108,0.05018686,-0.07185519,-0.07637646,0.07136241,0.00414915,-0.02341307,-0.03059119,-0.04376631,-0.04099699,0.03678115,-0.06920169,-0.02280769,0.03660156,0.03029255,0.00335078,0.03963389,0.10108931,-0.03686167,0.12492753,0.00729272,-0.06907616,0.04859531,0.00705862,-0.00649203,-0.06164273,-0.01001551,-0.08797807,0.08628364,0.01731437,0.05232154,0.04508938,0.07739495,0.00121694,0.01902865,-0.04675304,-0.01952271,0.03232265,0.02451661,0.00485246,0.03658659,-0.03637408,-0.01531847,-0.0272913,0.0511111,-0.06655951,-0.05356948,-0.00542749,-0.07538506,0.00819055,-0.06101766,-0.07967309,0.05615768,-0.00698961,-0.03950014,-0.0148703,-0.01138028,-0.02594315,0.07016913,0.03713119,-0.0392674,0.07532274,0.03275947,-0.05513039,-0.03909239,0.02272211,0.02051024,-0.05113169,-0.06995583,0.03014813,0.01922625,0.00984869,-0.05775071,-0.06470233,0.03876287,0.05643396,-0.02505301,0.04094824,0.02304093,-0.09552221,-0.02662059,-0.0342581,0.02218438,-0.08951836,-0.0115994,-0.0115338,-0.02335751,-0.0790709,0.04253988,-0.05387627,-0.01548586,-0.03350661,-0.03146793,-0.13483492,0.01884825,-0.0411043,0.04819913,3.38e-06,-0.02536255,0.02641966,-0.09379527,-0.00891554,0.00693624,-0.03987138,0.00025916,-0.01824662,-0.07418105,-0.06627239,0.05491128,0.01155732,-0.12456236,0.0976196,0.04303674,-0.06811903,0.0535776,0.03884805,0.02837377,-0.0746879,-0.01137527,-0.03198665,0.00519772,-0.11265118,-0.00138132,0.09510319,-0.05855752,0.03085557,0.1536118,0.04737674,0.01036693,0.03966423,-0.0035985,-0.0174656,-0.00914454,-0.00163969,-0.03755135,-0.10067164,-0.04501941,-0.03258559,-0.00627966,0.04373317,-0.05349857,-0.05412775,-0.06515765,0.06173476,0.0681329,-0.01526645,0.01852296,-0.0443671,-0.02352842,-0.00366354,0.0320161,-0.03597864,0.0154018,0.00049312,-0.1011331,0.03376755,-0.01401839,-0.01714904,0.0013013,0.07218627,-0.00662344,-0.0085108,-0.00637911,0.00133147,0.05755378,0.03139063,0.07168881,-0.09435755,-0.07811751,0.02586907,0.04678955,0.04555286,0.04797163,-0.02537099,-0.0675242,-0.05092705,0.05463981,0.06269355,-0.0469994,-0.03413706,0.04332703,0.00803426,0.0729749,-0.0408475,0.07572499,-0.02367625,-0.02330019,0.05887813,-0.04975155,0.04799436,-0.02854098,-0.03206699,0.02243951,1.238e-05,-0.07592567,-0.05755524,-0.04668745,0.07850861,0.02565323,0.02235968,-0.00889082,-0.02758395,0.00066377,-0.0930517,-0.08310101,0.05162895,-0.09080586,-0.03621764,0.04729702,-0.05735114,0.00487337,-0.01292177,-0.00559569,-0.0656685,-0.00392017,-0.09422674,0.02085471,0.07632967,-0.01330767,-0.03320407,-0.00403509,0.00537456,-0.03422957,0.05361624,0.01556496,0.06172832,-0.00884908,0.01602428,-0.03722506,-0.03222863,-0.02658758,0.08030991,0.05074914,0.07693304,0.04470937,-0.06978974,0.00857517,-0.06249812,-0.0703266,-0.00510223,-0.08866532,-0.07485515,0.04776721,0.04499126,0.09808027,-0.07440583,0.03598332,-0.08641451,-0.00035518,0.07532082,-0.00230887,-0.03749571,-0.05804398,-0.03195625,0.05421285,0.00358118,-0.04545543,0.00551268,0.0692394,-0.00786637,-0.00962828,-0.0608177,-0.03980234,0.03478017,0.10510678,0.0504913,0.00903475,-0.02457518,0.10370834,-0.02778574,0.03924769,0.05373134,-0.03889746,-0.06902988,-0.0397349,0.04979918,0.00912254,-0.03867549,0.08708666,-0.05813068,-0.03839211,-0.0531938,-0.05090919,0.00885577,0.02324937,0.01225461,-0.03885603,0.03950498,-0.02759232,1.4e-06,0.0168802,-0.00931254,0.05089233,-0.01263669,0.0033181,0.06525888,0.06397,-0.0327295,-0.01930131,0.0276446,0.03544012,0.0048889,0.03861584,0.00356216,-0.02314213,-0.04597077,0.00713418,-0.00264469,-0.08331676,-0.01520108,0.02822902,0.02019685,-0.02565825,-0.05344797,0.01013784,0.00035652,0.08608627,-0.02531918,-0.03211384,0.01315102,0.03634507,-0.02017773,-0.07857585,0.04750698,-0.00591418,0.03337308,-0.05904825,0.03041688,-0.06108733,0.06232583,0.03612927,0.02121082,-0.08142596,-0.03883463,0.05880475,-0.02284724,-0.10669122,0.0623053,-0.02916619,0.02201275,-0.05253046,-0.03413092,0.02025209,0.07800547,0.0454138,0.04287903,0.10345046,0.06832994,-0.05901178,-0.03992591,0.09154245,-0.00249399,0.06423064,0.02513114]');
INSERT INTO public.detalles_proyectos VALUES (128, '2026-09-02', 'Pregrado', 'En el ámbito de la seguridad informática, la autenticación basada en contraseñas sigue siendo uno de los eslabones más vulnerables en la protección de sistemas y datos, el presente informe documenta un ejercicio práctico de auditoría de credenciales, cuyo objetivo principal es demostrar la susceptibilidad de las contraseñas débiles ante técnicas de criptoanálisis, específicamente mediante ataques de diccionario.', 1, 'Comunidad / Organización No Específicamente Nombrada', 'Waos', '2026-09-02 17:22:38.760639', NULL, 'Trayecto I', NULL, NULL, true, '[-0.02092846,0.00199759,0.01941334,0.0287496,0.07073391,0.0211629,0.09712351,0.05492071,-0.01584252,0.03046933,0.05216457,-0.04945922,0.10187398,-0.00924246,-0.05099134,-0.04565827,0.03608356,0.00701263,0.04128077,0.14997417,0.01238937,-0.03783991,-0.00835885,0.01439618,-0.04992885,-0.00225322,-0.01689034,-1.57e-05,-0.02302464,-0.11109202,-0.06280352,0.01760128,0.01215849,0.00241183,0.0555301,-0.04023828,0.00915851,-0.06617932,-0.01521898,0.00452271,-0.00456999,-0.02419077,0.00288728,-0.05170656,-0.05065985,-0.0198107,0.07848379,-0.03007735,-0.08555877,0.03714725,-0.05054383,-0.09557328,0.01914552,0.05473841,-0.09236538,-0.07683107,0.05856599,-0.04165252,-0.04425503,0.0227163,0.03737047,0.08486284,0.03292484,0.03835143,0.02560361,0.10353778,-0.01703017,-0.0093232,0.02147848,0.03852749,0.06740481,-0.06482018,0.05880492,-0.00943005,0.04018734,0.03473451,0.02058445,-0.06070298,0.04970519,-0.08996548,-0.08308315,0.08334818,0.01991773,0.01144911,-0.00305306,0.04227132,0.0016655,-0.00967393,0.08168002,-0.00248036,0.08120111,-0.03338548,0.0608869,0.0301578,0.09638335,-0.04415155,-0.03389151,0.00074973,0.06638461,-0.01086141,-0.01643873,0.03119175,-0.00706111,0.07156546,-0.02057518,-0.07431723,-0.02141082,-0.12317341,0.0213647,-0.00698799,0.06539553,-0.00489664,-0.04137298,-0.09160277,-0.06837823,-0.03956245,0.03597927,-0.05774315,-0.05394775,-0.07270457,0.06460985,-0.05655962,-0.0356614,-0.1013121,0.04193818,-0.06790847,-0.02528635,3.93e-06,0.01521263,0.00859547,0.01129657,-0.02200565,0.00107485,0.05059254,-0.0217401,-0.00761709,0.02221976,0.03452634,-0.07050389,0.069246,-0.04416631,0.0300505,-0.00024352,0.03356188,0.0009731,0.03131212,0.01467776,0.01680205,-0.05282285,-0.04513165,-0.00887894,-0.03071014,-0.01809091,0.07716614,-0.04664591,0.04654784,-0.04332796,0.01261792,-0.04148962,-0.01530237,0.07230266,0.00227959,-0.00846397,-0.00144674,-0.10323859,-0.00656614,0.03675761,0.06052133,0.06525709,0.03036559,0.0154369,-0.05137616,0.00722499,-0.04266556,-0.06032802,-0.05249336,0.00286631,-0.1119315,-0.02987381,0.00248454,-0.03575768,-0.03133001,0.04868166,-0.03858045,-0.04232746,-0.03568509,-0.02058184,0.01122402,0.01152637,-0.04392744,0.08063323,-0.02525034,-0.05296013,0.00193652,0.02228267,-0.06661243,0.04081815,-0.04770789,-0.12217206,-0.0120031,-0.03599387,0.03621491,0.02884789,-0.03596635,-0.01212609,0.0194834,0.03193235,0.01645124,-0.04244819,-0.04202478,-0.01852874,-0.01196403,-0.0792824,0.03801483,0.01312974,0.06066996,0.05865912,0.0399024,0.00185778,-0.04681146,-0.07634214,-0.01469577,-0.0614781,1.302e-05,-0.09681594,-0.07151937,-0.05749291,0.00669125,-0.1100305,-0.10532867,0.00884824,0.07503782,-0.01706668,0.12258675,0.00664967,-0.01471742,-0.09665683,-0.08968028,0.03822572,0.06086347,0.01717305,0.03592905,0.00563178,-0.05707742,0.04049685,0.05428165,0.02034914,0.01443278,-0.02483168,0.04394355,-0.00711451,-0.01066874,-0.00033134,0.06197043,-0.05556934,-0.01547408,0.00598958,0.11210073,0.0332317,-0.10404505,0.050344,-0.00133786,-0.00080275,-0.08939632,0.05081009,0.06884163,0.00104113,0.04890997,-0.0016665,-0.07090618,-0.06547588,-0.02202426,0.07524787,-0.010409,0.04117895,-0.08293286,0.04908616,-0.10428891,-0.05125071,-0.00427704,-0.02366102,-0.05307209,-0.01312117,0.09197813,0.05324304,0.03748892,-0.03325884,-0.02790952,0.00306782,0.09051748,-0.01842784,0.03725976,0.04847265,-0.07366743,0.10239978,0.05238398,-0.01511157,-0.06200587,-0.04842527,-0.02627508,-0.03013031,0.01105492,-0.0006793,0.01702234,0.05945295,-0.02613048,0.02475589,0.04757874,0.03825953,0.03380954,-0.01711466,0.02431886,-0.02611407,0.00078047,-0.12372067,-0.01589376,-0.0310979,0.0186921,0.08251925,1.49e-06,-0.00799912,-0.04573747,0.00574297,-0.1440995,-0.08764805,-0.08332666,-0.06796168,-0.05619251,0.07831237,0.03228294,0.01822922,-0.03602102,-0.01075871,0.0005991,-0.00447049,0.01608113,-0.01081464,0.06046572,-0.09190532,0.00150126,0.07239787,-0.00452802,0.00392511,-0.04398331,0.03202107,0.02662801,0.02217172,0.01489785,-0.09966045,-0.00162305,-0.00650814,-0.04266978,0.01303898,0.09372362,-0.02245633,0.04959381,0.05090135,-0.02473673,0.03306272,0.03722294,-0.01385523,-0.12150728,-0.01204412,0.03322653,-0.00798485,-0.05170762,-0.02588115,0.01615452,-0.02901349,0.01306365,-0.04421311,-0.0425611,0.02047906,0.00721457,-0.05813501,-0.09441626,0.04250489,0.05394601,0.02367832,0.00851864,0.09255729,-0.05534711,0.15639643,-0.06546193]');
INSERT INTO public.detalles_proyectos VALUES (149, '2026-09-11', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones', 1, 'Centro Clínico “María Edelmira Araujo”, S', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-09-10 21:02:32.217243', NULL, 'Trayecto I', NULL, 'Proporcionar un Soporte Técnico a Usuarios y Equipos de Computación en el Centro Clínico “María Edelmira Araujo”, S.A.', true, '[-0.03093,-0.05152436,-0.03602974,-0.00934933,-0.03645983,-0.03117615,-0.04131429,-0.02863325,0.03718016,0.00865576,-0.00022026,0.02589695,0.04724601,-0.09295456,-0.06118183,-0.02893049,-0.02656861,-0.00940363,-0.01028627,0.08070317,0.04468548,-0.07777307,-0.04300198,0.06183533,-0.02351576,0.06747006,-0.01943628,0.03338747,-0.05731084,-0.00532187,-0.04741229,0.10560668,0.1087834,0.01625856,-0.01386548,0.0463669,0.04862722,-0.01944395,-0.02878464,0.03864898,-0.0608679,-0.04509625,0.05792637,-0.03042542,-0.10189202,-0.03261672,-0.02897985,-0.04789426,-0.04439707,-0.07297028,-0.09863961,-0.02739948,-0.0011888,-0.00574732,-0.03730556,0.02854448,0.07506801,-0.03125626,0.04344074,-0.0224066,0.05906542,0.01426815,0.0409379,0.05566977,0.02486034,0.00325067,0.03616891,0.01599474,0.05563171,-0.05082655,-0.01524559,0.00168426,0.00408551,0.10950095,0.04112043,-0.0084075,-0.06911227,-0.02894511,0.11364525,-0.09361508,0.03023283,0.02096058,-0.06030866,0.05962463,-0.03136415,0.07151573,-0.04505257,-0.0136987,0.17366153,-0.04027252,0.09157303,0.05575224,-0.03657173,-0.04593312,0.04299782,-0.01535276,0.09031248,-0.08414658,-0.08407058,0.00124701,0.01964965,0.01336796,-0.00042303,-0.08126616,-0.00060716,0.02240084,0.01848275,0.01507369,-0.03514029,-0.05703391,-0.0463405,-0.06927907,-0.06005195,-0.0693139,-0.06118455,-0.00034647,0.00405899,0.02042109,0.0256035,-0.05699044,-0.05653629,-0.05255754,-0.07916472,0.01584247,0.06053449,-0.00289824,-0.01144441,4.68e-06,-0.00065799,0.00306503,-0.05447041,0.0508127,-0.0446502,0.00511734,0.00253694,0.06410842,-0.0330313,-0.06552647,0.03084916,0.02791688,0.02779409,0.06009454,-0.01844216,0.02389616,-0.0435468,-0.0411522,0.02541951,0.00510993,-0.03242805,0.02949164,0.02627698,-0.06664841,-0.05942365,0.05136908,-0.0210288,0.01536121,0.07632068,0.03574103,0.02615635,-0.02794971,-0.03468029,-0.03662228,0.04837624,0.08704485,0.01299149,0.0229625,0.02167629,0.06569846,0.07073337,0.01135909,-0.07062456,0.00166518,0.01863871,0.05475277,0.00685701,0.0436238,0.10869234,-0.10165923,-0.0414193,0.03517482,-0.10461042,-0.02172617,-0.00272172,-0.04530953,0.02500838,0.07194995,0.06618826,-0.04100592,-0.01567639,0.04966947,0.05101077,0.07429027,0.04201304,-0.06724138,-0.05074924,-0.05248646,0.15030025,-0.05382059,-0.02347089,-0.03012819,0.00895435,0.08641909,-0.03905909,0.00977855,-0.08398522,-0.02790695,-0.01524512,0.05685312,-0.04588386,0.00133146,-0.01598133,0.01515551,0.09024916,-0.01299208,-0.00968571,0.0401912,-0.11586364,0.03620225,-0.0239347,-0.12902974,0.09568895,0.00421484,0.00979628,1.43e-05,-0.04472706,-4.194e-05,-0.03651689,0.02288642,-0.00477088,0.01471997,-0.04736462,-0.06634583,-0.02690242,0.01472721,0.06257871,-0.05099761,0.05033908,-0.02860767,0.06564702,0.02491422,0.05858891,-0.06350216,-0.01608285,-0.01657921,-0.00217128,-0.10999541,0.04664743,0.0463932,-0.04983279,0.00879107,-0.00095314,-0.00497557,-0.07011805,0.07284962,0.07494688,0.00094025,-0.06212752,0.1446789,-0.03370973,-0.02098065,-0.10420638,0.02073311,0.03997202,0.03976333,0.0202,-0.06719815,-0.01606115,-0.01649496,-0.00976216,-0.04252314,-0.05999836,-0.05900991,-0.05080511,-0.0313002,0.05593519,-0.00372829,-0.083018,-0.14037772,0.07135188,0.12987559,-0.00635482,0.00771831,-0.06247472,-0.03329173,0.05208353,0.03759872,-0.04432643,0.01770818,-0.03106434,0.15306513,0.01140704,0.07239107,-0.03124698,-0.01585686,0.06051237,-0.00482185,-0.03255451,-0.05385452,-0.05818462,0.01204588,-0.02009531,0.01272277,-0.0464467,0.04713175,-0.03475372,-0.04348804,0.01626098,0.02347507,-0.07277462,0.02450916,-0.02293644,-0.00983298,0.03166394,0.02201018,0.02984432,-0.02605345,-0.06557257,-0.09257025,0.05600509,1.46e-06,-0.00411669,-0.05866199,0.01969183,-0.01087027,0.00344215,-0.06698108,-9.699e-05,-0.00891201,0.01365386,0.05606133,-0.01325808,-0.11006113,-0.01055309,0.04422242,0.06302116,0.007841,-0.01789932,0.0674432,-0.06686938,-0.08246193,0.08987141,-0.07191928,0.02165279,-0.05896521,-0.05397767,-0.0001452,-0.01374111,-0.04986113,-0.01529476,0.00019486,0.03382116,-0.03631415,-0.09204084,0.00088367,0.07163097,-0.00167388,0.014614,-0.02736863,0.01913318,0.03995728,0.04885835,-0.01125259,-0.04149466,0.01813147,0.05388013,-0.04496522,-0.01416522,0.05030593,0.05403168,0.05277429,-0.04639147,0.02301766,-0.02706443,0.02005903,0.00222502,-0.0157454,-0.04153306,0.03636336,-0.06822208,-0.00829013,-0.07968662,0.05795667,0.02416749,-0.04643076]');
INSERT INTO public.detalles_proyectos VALUES (45, '2026-06-15', 'Pregrado', 'Dise¤o e implementaci¢n de un motor de renderizado ligero y de alto rendimiento. Se evit¢ el uso de frameworks pesados para garantizar una ejecuci¢n "metal pure", optimizando el consumo de RAM y CPU en equipos de bajos recursos.', 1, 'Comunidad de Desarrolladores Independientes', 'Rust, Tauri, Novela Visual, Nativo, Optimizaci¢n', '2026-07-05 17:21:44.350197', NULL, 'Trayecto II', NULL, NULL, NULL, '[-0.07049538,-0.03002026,0.04811038,0.0313763,0.04087676,-0.01243392,0.06132799,0.0248652,-0.08311176,-0.06451062,0.02075082,-0.00451979,0.00587875,-0.04218728,-0.04641454,0.02969693,0.04270992,0.05559118,0.0146771,-0.01166894,-0.02059293,-0.07732871,-0.01004001,0.01476931,0.03747248,0.10597146,0.02413527,0.04406648,0.00095856,-0.04599842,-0.00406918,0.00917696,-0.05255913,-0.07096737,0.00097008,-0.03294183,0.00249385,-0.01932257,-0.11589102,-0.08962954,-0.03910578,0.07713918,0.02880431,-0.02212228,0.10411997,0.03533076,0.04121813,-0.04247311,0.00926765,-0.03521927,-0.07396737,-0.06522158,0.02720078,-0.09836809,0.02741137,0.07024792,-0.03256152,-0.09991322,0.04073904,-0.00938369,0.03358568,-0.03123116,0.00060199,0.00517426,0.06381278,0.03983751,0.04336143,-0.03503846,0.00917992,-0.02572406,0.03275906,-0.01675679,0.00731938,-0.01516734,-0.09162785,0.05122703,-0.00164277,-0.07463756,-0.01003873,-0.10661062,-0.02741688,0.00792508,0.00012859,-0.05624517,0.07792776,0.10374968,-0.03681505,-0.06554999,0.04817179,0.04420384,0.01830845,0.05071941,-0.1079789,-0.03680498,-0.02238723,-0.01272942,0.01959054,-0.00369266,-0.06241179,0.04594416,-0.01349255,0.00329476,0.07426732,-0.06568355,-0.04132489,-0.00504058,0.01014842,0.03468835,-0.05471844,0.02222384,0.01965946,-0.00546697,-0.06130832,-0.01975861,0.05692186,-0.046173,-0.03038628,-0.07383704,0.04329091,0.07551834,0.00720053,-0.0234691,-0.04817738,-0.00513947,0.02220798,-0.05705466,0.03316462,4.94e-06,-0.03715221,0.03574128,0.02748159,0.00364098,0.07397814,-0.05748811,0.01314798,-0.08656369,-0.00017461,0.00476765,0.01723738,0.05100345,-0.04833464,0.06411224,0.05861719,-0.15469386,0.10897261,-0.03945858,0.04550036,-0.03502631,-0.01649958,0.09001555,-0.04646755,-0.01011933,-0.03532872,0.00644573,-0.0246,-0.02796373,-0.09863098,0.08434459,-0.02001988,0.0761319,-0.10118347,-0.01436442,0.02672794,-0.06256698,-0.05830627,-0.06324909,0.04304875,-0.03314017,-0.06823265,0.02100622,-0.08005693,0.05396246,-0.04969647,-0.00221849,0.0631543,0.08610075,-0.03855277,0.00407554,-0.07932166,0.06342649,0.01152127,-0.01055144,0.02810035,-0.01682738,0.02627975,-0.01515048,0.03818813,0.05770114,0.00153062,0.1163834,-0.05689292,-0.00802505,0.00820585,0.00825516,-4.484e-05,-0.0107103,-0.09022184,0.03981328,-0.01217589,-0.00340617,0.07195474,0.04560598,0.03292781,0.02364159,0.01007061,0.02641964,-0.01706869,-0.04777008,-0.1160182,0.05594694,-0.0419363,-0.04154711,-0.00624692,0.01575874,0.01731072,0.05768571,0.00279926,-0.09260316,0.08447313,-0.02413243,-0.01025283,-0.06436701,-0.02666664,1.363e-05,0.00616944,0.01514034,-0.02052374,0.0262298,-0.05001318,-0.0065443,-0.06518888,0.02555557,0.00928514,-0.05939987,0.01364241,0.0093665,0.01049787,0.02798946,0.04440301,0.00918386,0.03712616,0.0036694,0.03869116,-0.09143333,0.03322186,0.02203812,-0.01226234,-0.02199858,0.03073204,-0.02444417,-0.06158465,0.06929069,-0.00429011,0.07153983,0.01773784,0.02625233,0.06099427,-0.03271487,0.01849788,-0.06499183,0.11088574,-0.00310134,0.00960792,0.02759391,0.01084125,-0.06679262,0.01371307,0.04518193,-0.00032792,0.01807002,-0.04670389,-0.09923634,0.06779906,0.00971396,0.12532327,0.01006913,0.06105575,-0.07264607,-0.02531992,-0.11445733,0.03788631,-0.05561468,0.01437961,0.05871928,0.04004031,0.0050174,-0.06613975,-0.04583351,-0.03596022,-0.07224445,-0.01967684,-0.00981619,0.00357596,-0.05457428,-0.02191347,0.02666918,0.01768151,0.00370363,-0.05800467,-0.02956019,0.17041945,0.10822855,0.02650248,0.02001014,0.05839473,0.05121599,0.03167873,0.06101075,0.03826871,-0.00922329,-0.11044395,0.01133202,-0.01512353,-0.06786915,0.02854805,-0.06934258,0.0151212,0.0939047,-0.06533391,1.34e-06,-0.04629291,-0.03868307,0.03571165,-0.01729666,0.01344082,0.12258689,0.03303418,-0.10136827,-0.01134087,-0.04001961,0.0712028,-0.0102253,0.00579156,0.01416394,-0.02964687,0.0948672,-0.00834778,0.03211525,-0.04242009,-0.00421453,0.10545304,0.0032175,0.02456081,-0.0156904,-0.07339844,-0.00207527,0.00040121,-0.06077607,-0.03321789,-0.03392178,-0.05633802,0.01891815,0.03032154,0.00957777,0.00546806,-0.0214291,-0.06359118,0.08395945,-0.05590322,0.03647908,-0.13560273,0.04625178,-0.03540247,-0.01491735,0.04186348,-0.01280802,-0.03514794,-0.14517196,-0.06161282,-0.01599973,-0.01354443,-0.00704945,0.06599774,0.05900177,0.00293432,0.08595326,0.0194935,-0.04790274,-0.0877058,-0.02794918,0.05514227,-0.03359332,0.03036556,0.05929188]');
INSERT INTO public.detalles_proyectos VALUES (89, '2018-01-10', 'Pregrado', 'El presente proyecto sociotecnológico se centra en el desarrollo de un módulo avanzado para la administración y proyección de las líneas de investigación del PNFI, en el cual la innovación principal radica en la integración de modelos de Inteligencia Artificial (Machine Learning) orientados al análisis predictivo, esta herramienta procesa el volumen y la tipología de las investigaciones registradas para identificar tendencias emergentes, predecir el crecimiento de áreas temáticas y asistir al Comité Científico Investigador en la toma de decisiones estratégicas, todo ello operando sobre la arquitectura base del Sistema Integral de Gestión.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Líneas de investigación, PNFI, Machine Learning, Análisis predictivo, Toma de decisiones, Comité científico, Gestión del conocimiento, Sistema integral de gestión', '2026-08-10 10:35:39.007693', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.106911,-0.05934569,-0.01816612,-0.02918361,0.00401346,0.05353091,0.02148036,0.0102906,-0.1289228,0.03272149,0.0316654,0.03346945,0.06094049,-0.06925744,-0.05566427,-0.0023215,-0.07153075,-0.025082,0.02504236,-0.03182858,-0.03946562,-0.05423755,0.08427687,-0.01183774,-0.05611666,0.01536732,0.04577249,0.00195514,0.04121561,0.07840493,0.03586993,-0.01177277,0.06081786,-0.01930205,0.04278957,0.05194452,-0.02232108,0.06094465,0.01097988,0.02190476,0.04986301,-0.0505483,-0.01519133,-0.0682274,0.02495529,-0.04204305,-0.00232999,-0.08040031,-0.07977066,-0.05216601,-0.06355302,-0.05344252,0.04483969,-0.0007256,-0.05087959,-0.07703696,-0.01242287,-0.03305174,-0.01913351,0.01057376,-0.0130755,-0.01360271,-0.02468991,0.04009112,0.13005121,0.04943472,-0.04095194,0.05502406,0.03245868,-0.01379437,0.04941404,-0.01530652,-0.03980749,0.04487691,0.00533736,0.01563453,0.01635407,-0.0577233,0.06512187,-0.08000863,0.03667853,-0.02564412,-0.03716166,-0.00123415,-0.01388816,-0.04181803,-0.07104185,-0.01714344,0.06597688,0.00069676,0.05170984,0.013662,0.05322828,-0.05140436,0.03730162,0.04494605,-0.05750698,-0.0444558,-0.07607714,0.06347766,-0.08135516,0.0287415,0.0024647,-0.03218229,0.00702814,0.02077133,0.00913249,-0.02145898,0.09065047,0.03006192,-0.05869351,0.02009531,-0.01403955,-0.01849106,0.03702649,0.03239479,-0.07359422,0.01923627,0.00443354,0.01874239,-0.05635448,-0.07186643,-0.06988829,-0.03661427,-0.00668964,-0.01809649,-0.09021367,3.59e-06,-0.01313123,-0.0614147,-0.02681582,-0.0467359,0.04170694,-0.03604466,-0.01508356,-0.07313535,0.07586411,-0.05173498,-0.03770088,-0.03365997,-0.01097065,0.10532054,-0.03390847,-0.05815334,-0.07040366,9.141e-05,-0.01279961,0.01744484,0.03626888,-0.09081503,0.02921943,-0.00178115,-0.06649659,0.01646813,0.02073399,0.09629157,-0.04059605,0.03552114,0.02666092,-0.0430262,-0.0719429,-0.00482534,-0.0239263,0.00620765,0.01223924,0.02071466,0.02668753,0.02029373,-0.02154869,-0.02804829,0.01469246,-0.0274727,-0.03430583,0.01601073,-0.0386067,-0.07649373,-0.07970191,-0.03181546,0.00858856,-0.00663452,-0.03297402,-0.06514494,0.03211981,0.04111667,0.05416587,0.02474822,0.04923621,-0.06902316,0.12397436,-0.01378794,0.06248937,0.01542783,-0.02687831,0.08051727,0.00973591,0.00118396,0.06719952,0.01237225,-0.01811112,0.02031503,-0.01844129,0.00683675,0.08084321,5.358e-05,-0.04602978,0.05902713,-0.04218504,0.0160201,-0.02641081,0.03336349,-0.0620693,0.00358204,0.05539052,-0.02250395,-0.00832443,0.01778717,-0.0274511,-0.01243163,-0.00447257,-0.02093601,0.00747329,0.06738107,-0.06375264,1.284e-05,-0.1124162,-0.00040135,-0.01312495,-0.05968212,-0.0033776,-0.01553096,-0.0312307,0.00984876,0.01433467,0.0616998,-0.01992697,-0.02329315,0.05044076,-0.09595954,0.06662292,0.03175093,0.00187822,0.08696017,-0.00303111,-0.03799643,0.01408898,0.02711365,-0.01229537,0.02512774,-0.01086461,-0.01521507,-0.01010757,0.12844399,0.02838753,0.03167254,-0.05208988,-0.02864403,-0.09393317,0.026774,-0.04062099,0.01655871,0.06352015,-0.05289804,-0.01475038,-0.05894936,0.10994426,0.06449309,-0.12173069,0.02044098,0.00310683,-0.02202386,-0.10712524,-0.14490624,0.0345546,-0.02595413,0.08707276,0.04089913,0.08775641,-0.0901532,-0.029842,0.09268447,0.08240451,0.04499166,-0.02069507,0.04531411,-0.08604814,-0.02136983,-0.03740771,0.09909794,-0.03730319,0.09093468,-0.0289533,0.09724649,-0.03713871,-0.06439657,-0.00022026,0.07120757,-0.08611486,-0.02744275,-0.10471934,-0.00027148,-0.10180981,-0.00595746,-0.02483705,-0.01410811,-0.02950215,-0.10746603,-0.03414439,-0.00651262,-0.00850148,0.00834729,0.02379303,0.00401465,0.02983262,-0.03618319,0.05218412,-0.01660835,-0.09964746,-0.04460416,-0.04299597,1.5e-06,-0.00785889,-0.020555,0.09512509,-0.00440757,0.12766092,0.02176315,-0.09242073,-0.04411359,0.05726217,-0.02900738,0.06240606,-0.02377983,0.01921812,0.00775149,-0.04042818,-0.02372388,-0.05707312,0.08363521,-0.05964065,0.00499716,0.15984216,0.02120544,0.01815721,0.01574843,0.0125938,-0.04761835,-0.05362732,-0.03809541,-0.09393964,0.00095515,-0.07226649,0.02840764,-0.01813487,-0.03098102,0.00787142,0.08385685,0.11894243,-0.08773799,-0.06309997,0.07381707,-0.06070347,-0.07676331,0.00994568,0.01795866,0.00429539,-0.00283418,0.03168565,-0.03921852,0.00244571,0.02205604,0.05571313,0.03929995,0.0074625,-0.0525997,0.01321316,-0.04876331,-0.04487736,0.04228003,-0.06694911,0.05284137,0.06819383,-0.03558244,0.09597054,-0.02829745]');
INSERT INTO public.detalles_proyectos VALUES (147, '2026-09-11', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-09-10 21:02:31.80467', NULL, 'Trayecto I', NULL, 'Desarrollar un Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A., compuesto por un módulo de gestión local y una plataforma de comercio electrónico interconectados mediante una base de datos centralizada en la nube, con el fin de automatizar los procesos internos de inventario y ventas, y ampliar el alcance comercial de la empresa hacia el entorno digital.', true, '[-0.01040047,0.0464434,0.03348156,-0.11894738,-0.01793981,-0.05856845,0.11330336,0.10907865,-0.01254862,-0.04248562,0.15662852,-0.09181652,0.06552008,0.01098482,0.03337649,-0.01691731,0.02040718,0.05396639,-0.04463262,0.10874937,-0.01588511,0.02625558,0.05332847,0.03383944,0.0264749,-0.03813775,0.04914231,0.00451021,0.05204702,0.00687996,0.00745278,0.03552941,0.00125671,0.09105089,-0.00674154,-0.03063848,-0.01534404,-0.10316311,-0.105663,0.05416323,-0.01800117,-0.03060819,-0.00336944,0.0308325,0.03220688,0.01358314,-0.0281621,0.03710851,-0.04957044,0.05455722,-0.01590234,0.06494969,0.00200787,0.03120549,-0.10313541,0.06040471,-0.05276881,0.01024633,0.0705496,0.06350582,0.09609901,0.02722146,-0.02768123,0.00471257,0.03794761,0.01565789,-0.03215371,-0.01525977,0.02361198,0.00584303,-0.01992434,-0.01110517,-0.02699352,-0.07096514,-0.03711777,0.02529409,-0.01312008,0.00930163,-0.01506265,-0.00255479,0.0484894,0.08475302,-0.04053284,0.07608213,0.05766943,0.03416717,-0.03836672,0.10882003,-0.0134574,-0.04678311,0.04114941,-0.02198586,-0.05481464,-0.03945637,-0.03667312,0.00149686,-0.02483737,-0.11069395,-0.04603502,0.07007351,-0.04020545,-0.0308523,0.0527779,0.04017001,-0.02381982,0.01574275,-0.06627387,0.03606471,0.03382301,0.04812165,-0.03416642,-0.0663749,0.01917841,-0.09478911,-0.090187,0.10505749,0.04003357,0.03218996,0.05223513,-0.05855377,-0.00670282,0.00761512,-0.07604438,-0.05358105,-0.04002494,-0.02892877,0.03716614,5.1e-06,-0.07024329,-0.02862369,0.03121357,0.0043214,0.03987043,-0.01841711,0.03945943,-0.04352523,0.00903137,-0.07762426,-0.03715791,0.05744979,-0.09058061,0.07033008,0.00435079,-0.00164002,-0.00214428,-0.02881411,0.09978216,0.05624955,-0.01370731,-0.04616296,0.05684927,-0.00506012,0.0173657,0.06430796,-0.02636786,0.06488508,0.00284067,0.00939845,0.06552683,-0.10500689,0.02339842,-0.05663049,-0.03383655,-0.04392494,0.00338105,-0.04787108,0.0253908,0.00612738,-0.10486905,0.04326627,0.00465214,-0.0016461,-0.05420497,-0.01908938,0.04965512,0.06332432,0.02114307,0.02225233,-0.00945868,-0.05883026,-0.09323701,-0.09100119,-0.0348045,-0.05078661,-0.06233432,0.09861546,-0.05131531,-0.00902641,-0.02860181,-0.01366776,0.02665811,1.32e-06,-0.08431194,-0.0450223,0.00502841,-0.06527549,0.02507307,0.00803015,-0.07879061,-0.01413632,-0.03011133,0.0088382,0.01274528,0.01982877,-0.02895057,-0.04226136,0.01980845,-0.00558186,0.00499828,0.01625787,0.02780509,0.05376739,0.06347046,-0.03978171,-0.01820741,-0.09488136,-0.02728764,0.02335688,-0.07176212,0.02663879,0.01231917,0.03611001,-0.0254701,1.441e-05,-0.0924635,-0.05511795,-0.01451756,0.03104216,-0.00543124,-0.01600809,-0.01342086,-0.0566791,-0.05891199,0.0297511,-0.00766203,0.03832418,0.02379878,-0.01914068,0.02865716,0.11348703,0.00472526,0.02571365,-0.00993933,-0.03317169,-0.03032547,0.07879629,0.05885758,-0.1010504,0.01548373,-0.05711372,0.06275301,0.05339926,0.00707506,0.0124238,-0.05764286,-0.04476182,-0.11120623,0.00409814,0.03595676,-0.05061375,0.02308963,0.06858488,0.01420615,-0.06370145,0.02911062,-0.09502899,0.01502615,-0.02488442,-0.00869044,-0.00010733,-0.07380144,-0.09268676,0.02130304,0.03943036,0.05644345,0.01590851,0.02388438,0.00697025,-0.08545404,0.10087997,0.00739758,-0.08979,0.00146283,-0.10968602,0.0758145,-0.06591973,-0.032167,-0.03957432,-0.01907934,0.00310678,-0.04311114,0.02570873,-0.02857583,-0.06248833,0.04799447,0.00996836,-0.00308967,-0.02076292,-0.07832784,0.0158774,-0.01687707,-0.05360882,0.03872959,-0.01999792,-0.06993535,0.06375253,-0.00112688,-0.12321457,0.03619337,-0.01977949,0.01681063,0.07841474,-0.0617863,0.03753813,-0.03423446,0.09501455,-0.05535102,0.05195611,-0.05834462,1.49e-06,-0.0122672,-0.06292699,-0.05477363,-0.07763529,-0.05825159,-0.00096978,-0.02428437,-0.02402867,0.08476492,0.07928298,0.03971094,-0.03043884,-0.06911992,0.07534902,-0.02060409,0.02763708,0.0395462,0.06683957,-0.07123272,-0.04845083,0.08588934,0.05453232,0.04035179,-0.04055912,0.00676764,-0.03419672,-0.0086591,0.03340124,-0.02361795,-0.00545151,0.03178166,-0.00307533,-0.01279257,-0.03612835,-0.05287189,0.06183536,-0.0408516,-0.03897851,0.01220287,0.01223381,0.00615904,-0.0883071,0.04949279,0.03625499,-0.02246351,-0.09227288,0.10804568,-0.01209007,0.05076766,0.15482195,0.0382849,-0.07299488,-0.01733285,-0.10017683,0.00083657,-0.0029118,0.07861287,-0.00110613,-0.00294725,0.0156644,-0.04129981,0.02735534,0.03551947,-0.00685458]');
INSERT INTO public.detalles_proyectos VALUES (148, '2026-09-11', 'Pregrado', 'El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes', 1, 'Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry', 'App, Aplicación móvil, Coordinación, Ascensos', '2026-09-10 21:02:31.979685', NULL, 'Trayecto I', NULL, 'Crear y fortalecer las condiciones intelectuales y materiales para propiciar, generar, coordinar, diseminar y difundir conocimiento científico y cultural que responda al perfeccionamiento de las y los docentes en servicio, que contribuyan de manera sustancial al mejoramiento, desarrollo y crecimiento académico.', true, '[-0.17822734,-0.0365167,-0.06722667,-0.06064529,-0.04593806,0.01138579,-0.01135448,0.02392926,-0.0424472,0.02796123,0.09287687,0.04564626,0.00217062,-0.00713628,-0.0460693,0.03564411,-0.02812531,4.019e-05,0.05467257,0.08830688,0.05549312,0.01277636,0.03397625,0.06853834,-0.03558008,0.006877,-0.001102,0.00380855,0.08905209,-0.02305799,0.03167332,0.06220732,0.08857315,0.02001841,0.07029003,0.02692086,-0.02076885,-0.02694645,0.04112344,0.00790678,0.0390642,-0.04006191,-0.01461495,-0.00837345,0.00370087,-0.14135666,0.03981298,0.04876864,-0.00349145,-0.000745,-0.01533456,-0.04199177,-0.04271049,0.01816453,-0.0996703,-0.03383022,-0.06715698,-0.04917645,-0.0220366,-0.03104184,0.03282392,0.11304831,0.01827306,0.08180636,-0.00430447,0.08556091,-0.0340251,0.013227,0.00558684,-0.03384571,0.05525525,-0.0465048,0.02945342,0.02221719,-0.01273545,0.01110213,0.03915645,-0.01837701,-0.03659703,-0.11437294,0.03020482,0.00410479,0.03240805,0.07756071,-0.04863719,0.00019943,-0.04999947,-0.01950812,0.08532588,0.04983898,0.02752638,0.03577003,0.09547909,-0.03051222,-0.00660801,0.01316379,0.08564953,0.02597098,0.01250078,0.02490632,-0.06152079,-0.00364913,-0.03605393,-0.07243507,-0.04371484,-0.02600903,0.01562117,-0.03449516,0.12139845,-0.01128361,-0.04827188,-0.11188198,-0.08286378,-0.04588027,-0.06139615,-0.00342488,-0.02892363,-0.06567636,0.05853614,-0.04560713,-0.05293786,0.00379403,-0.08804424,-0.06264314,0.02630104,-0.08081114,0.04818275,5.16e-06,-0.01585819,0.06470108,-0.0473783,-0.00443326,0.06822528,0.00084486,0.03776972,-0.06060562,-0.03872009,-0.07470491,-0.05332183,0.0741408,-0.06217953,0.03781268,-0.08981155,-0.06585606,-0.02961165,0.03046838,0.0874078,0.01106843,-0.01447712,0.01477256,-0.01949551,0.00694576,-0.09923552,0.03929707,-0.04741555,0.08294271,-0.02154142,0.03096712,0.04436248,-0.07795294,-0.03593807,-0.01295563,0.04177765,0.02934747,0.01245998,-0.04320539,-0.00674718,0.00951734,-0.03050637,0.00285309,-0.01126119,0.07168066,-0.03985724,-0.01958615,-0.08272689,-0.00945026,0.09156456,-0.01015518,-0.0462249,0.04527879,-0.00994546,-0.00563881,-0.00518201,0.01229896,-0.09401679,0.0605811,0.06045387,-0.08808582,0.10792934,-0.03835569,-0.01457892,0.01331806,-0.06298293,-0.0371679,0.01289573,-0.08522411,0.1256064,-0.05904459,-0.04695425,0.05755813,-0.00291673,0.00064662,-0.02221543,0.07352229,-0.01399759,-0.03988772,-0.01900985,0.08573765,-0.06365863,-0.00497227,-0.03687792,0.02569256,0.11484404,0.03866836,0.0441748,0.04587343,-0.01463101,0.06270328,0.07867405,-0.09015091,-0.00026517,-0.07804714,0.04740085,1.418e-05,-0.10752222,-0.04228484,-0.03843361,-0.0225133,-0.06163303,-0.02689207,0.01655104,0.06123371,0.03432147,0.00700619,0.06358869,0.0172512,0.01273748,-0.11631875,-0.03386841,0.05758478,0.02435459,0.08613402,0.00997548,-0.03545084,0.01285882,0.02536793,-0.05605322,0.00525334,-0.02669302,0.02041186,0.03748906,0.05915493,0.03959266,0.05538933,0.05586122,0.01673828,-0.09873866,0.03139656,-0.05005893,0.01317821,-0.01186874,0.02433544,0.01628707,-0.04530469,0.07410558,-0.02109798,-0.01931497,0.00645278,-0.03207458,0.03593408,-0.09051579,-0.0507812,0.0587639,0.04788028,0.07057888,0.01910521,0.05056302,-0.1114988,0.02228044,0.01177297,-0.00116617,0.03010767,-0.06003943,-0.0141234,0.02495779,-0.00568298,-0.05990539,0.03778992,-0.02229006,0.05088274,-0.0703202,-0.0143321,-0.02747756,0.00276162,-0.01174992,-0.01552625,-0.08589276,-0.03904264,0.01390253,-0.07366445,0.00676065,0.01656013,0.01949463,-0.00140147,-0.09815087,0.02400611,-0.03687699,-0.02697981,0.04605176,-0.02152049,-0.05957429,0.02902593,0.01592019,0.04845484,0.04516609,0.03420914,-0.08693061,0.00342436,0.04888885,1.47e-06,-0.02337543,-0.0729318,0.03280379,-0.07607673,0.08383381,0.04572792,-0.02658568,-0.06062255,0.05953754,-0.03071801,0.02566581,-0.04704177,-0.02060662,-0.03102382,-0.02847879,0.02575463,0.03109864,0.02898423,-0.0508632,-0.13272,0.0416344,-0.01208287,-0.01069374,-0.04219565,0.01269765,0.0570787,-0.05998778,-0.02019564,-0.11606681,-0.00750523,0.02118115,0.06142074,-0.04607229,-0.08266655,0.02744886,-0.01459661,0.03900502,-0.03379067,-0.0332788,0.06585363,0.0118373,-0.01279738,-0.00254787,0.02075316,-0.01945906,0.02409355,0.02450765,0.10841131,0.00686923,-0.00276403,-0.00204672,-0.06540766,0.00357036,-0.06414894,-0.10082016,-0.07432798,0.06274821,0.049483,-0.04525548,0.00677816,0.00877813,0.01484078,0.1274566,-0.06065178]');
INSERT INTO public.detalles_proyectos VALUES (158, '2026-09-23', 'Pregrado', '', 1, '', '', '2026-09-23 12:29:57.034447', NULL, 'Trayecto I', NULL, NULL, true, '[-0.06546919,-0.02656363,-0.05188713,0.04130376,-0.10017707,-0.04136967,0.00796826,0.0269482,-0.04972479,0.04384127,0.08512991,0.00026631,-0.02077556,0.02176574,-0.09313739,-0.00256443,0.01454314,-0.06757871,0.07908095,0.03444056,0.07270218,0.0616442,0.1010707,-0.00825643,0.06532786,-0.01595017,-0.07909791,-0.01797796,0.0964056,0.03775056,-0.00024005,-0.02780936,0.02564977,-0.1035592,0.10436646,-0.01227351,-0.04733038,-0.06460035,0.06798819,0.03523769,-0.0262134,-0.04245557,-0.00698927,-0.01487183,-0.00661155,-0.0511032,-0.00822902,0.04854716,0.03597821,0.0103765,-0.06208717,-0.15434863,-0.04437498,-0.014846,-0.0140803,-0.0789069,0.00214517,-0.01495665,-0.03819915,0.01014395,-0.04021144,0.03940937,-0.10786666,0.03079252,0.01260628,0.09270417,0.03577865,-0.03470156,0.01395081,0.03615521,-0.02561149,-0.00418075,0.04723168,0.02936787,-0.04571266,-0.10071286,0.01426925,-0.04707607,0.00642303,-0.03332814,0.05464055,0.1181246,0.04667945,-0.02060053,0.02398644,-0.00581388,0.07539929,-0.0205965,-0.00194867,-0.00383843,0.03036004,0.00663477,-0.04820135,0.03680945,-0.07614797,-0.02877437,0.03641281,-0.00423867,-0.00021925,0.06326115,-0.05164902,-0.08337841,0.07010372,-0.02569533,-0.07168494,-0.0086187,0.00233199,-0.00863964,0.0297259,-0.00683051,-0.02040966,-0.02408791,-0.02978101,0.03613468,-0.01852534,-0.0914307,0.02969332,-0.02281286,0.05711222,0.05303375,-0.07568232,-0.01334795,0.0572593,-0.05454384,-0.04873689,-0.00542811,-0.02845465,1.63e-06,-0.0695612,-0.07086431,-0.00271417,0.00329785,0.00191158,0.06162151,-0.05067164,-0.08826516,-0.02770551,0.01166378,-0.04413186,-0.01550217,0.02386238,0.0084444,0.00556692,-0.03149269,0.05557166,0.07984547,-0.0020385,-0.05654087,-0.03161017,0.04544723,0.0821052,-0.07466402,-0.00023276,-0.01881488,0.01910875,0.00605826,0.01564072,0.09355401,0.04127159,0.02843008,-0.01279305,-0.10636922,-0.01532048,0.01695102,0.07123866,-0.10001382,-0.0016827,0.04875441,-0.08087933,-0.00112243,-0.00575048,0.01025627,-0.04388112,0.01831877,0.05612166,0.04386238,-0.05962393,-0.02488898,-0.00590188,-0.01067613,-0.04234432,0.01143489,-0.07232691,0.07720721,-0.02824807,0.02153155,0.01911221,0.02590481,0.05144863,0.0099972,0.05831958,-0.08556544,0.0249334,-0.1049927,0.01864091,0.04787928,0.02187502,-0.01654791,-0.09935913,-0.03260929,0.01586026,0.0930411,0.0399636,0.01138565,0.02866212,-0.01442024,-0.05086175,-0.01634527,-0.09772301,-0.00340062,0.07205308,-0.09321387,-0.05541653,0.08018984,0.00080383,-0.01205584,0.01026782,0.05751038,-0.06329026,0.01870993,0.00620267,0.02340348,-0.02273095,6.14e-06,-0.01806481,-0.01025998,-0.04462069,-0.09251063,-0.07327032,0.0152908,0.01766645,0.05019796,-0.03741479,0.02140856,0.05455172,0.01182497,0.01732602,-0.07465145,0.0231523,-0.0161229,-0.06953686,0.00428171,-0.01288512,-8.335e-05,0.01786749,-0.03935927,-0.06885455,-0.02438496,0.03914821,0.0280843,-0.0310354,0.00650299,-0.02056858,0.01636893,0.02257788,-0.02731561,-0.02996351,-0.04266148,-0.04571468,0.0127857,0.03195773,-0.07064464,-0.01016903,0.02709929,0.04020277,0.05559041,0.07507239,-0.02822963,0.01756943,0.01167756,-0.07841952,-0.05731033,0.00197716,-0.10728152,0.05993406,0.03248382,-0.03005723,0.06723376,-0.00982495,0.0076742,-0.07451339,-0.03878102,-0.08110831,0.04075116,0.00980576,0.00487397,-0.08711855,-0.00198631,0.15306725,0.02117255,-0.03171844,-0.11153542,0.10683286,-0.02083477,0.05874981,-0.04224295,-0.06364565,0.05686063,-0.10452636,0.03258305,-0.03755856,-0.09119617,-0.02958949,-0.02321583,0.07994756,-0.01658189,0.05898489,-0.0087574,-0.02895147,-0.06860486,0.0819768,0.0280113,-0.02039371,-0.00447255,0.01999915,0.023931,-0.01436053,0.02136148,0.11079026,6.7e-07,-0.02252764,0.05380688,0.01893668,0.02352071,0.03735474,-0.0425052,0.02437934,-0.00447638,-0.01730776,-0.04007874,-0.02391341,-0.01595311,0.00092446,0.0298633,-0.0304346,0.02320044,-0.02905262,0.09761641,-0.00192641,-0.03056837,0.04510892,-0.05085898,-0.00572001,0.01952423,-0.05581722,-0.03721338,0.05702107,0.13150696,0.00237775,0.0005501,-0.02799332,0.02658604,-0.01622607,-0.01374779,-0.05514724,0.10896157,-0.04150557,-0.01118003,0.01593866,0.19037811,0.08083579,-0.0999491,0.0583326,-0.01737292,-0.01102445,-0.00485526,-0.00698171,0.0225049,0.01735945,0.00840341,0.08860164,0.01913368,0.02941711,0.11233775,0.0003777,-0.01604134,0.02511578,0.09050696,-0.07504855,0.05608143,0.09264607,-0.10212241,0.08125226,-0.02011236]');
INSERT INTO public.detalles_proyectos VALUES (46, '2025-11-20', 'Pregrado', 'Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.', 1, 'Estudiantes de Computaci¢n Gr fica', 'Lua, TIC-80, Retro, GameDev, M quina de Estados', '2026-07-05 17:21:44.350197', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.05116083,0.02345255,-0.01021361,-0.11530817,-0.0253934,0.00857013,0.04648811,-0.00510607,-0.04147248,0.0365659,0.05142217,-0.02102412,-0.02468238,0.02043852,0.04671616,0.00910571,0.03380279,0.0371693,-0.02879999,0.07217761,0.00402356,-0.15434878,-0.0602577,-0.02157884,0.00368895,0.06034494,-0.04018424,0.08659101,-0.01706941,-0.05783396,-0.03637471,0.07621395,-0.00017805,0.00784321,0.04048069,-0.06603352,-0.05255684,-0.09808541,-0.07457419,0.01658598,-0.0922888,0.09128127,-0.04686225,-0.05326935,-0.03112979,0.09977382,0.05732583,-0.07220923,0.03852098,-0.06111816,0.00787052,-0.01531399,-0.01235696,-0.03128337,-0.04468357,-0.04450989,-0.01025925,-0.02794894,0.07214903,0.10054994,-0.03560216,0.02502398,0.02155219,0.00376669,-0.03452123,-0.08792791,0.06154833,-0.06072029,-0.04716655,0.0139419,-0.04587833,-0.00033362,-0.08742364,0.02160314,-0.06587009,-0.00178463,0.02269577,-0.02381545,-0.03872226,-0.06996543,0.08679737,-0.05147991,0.02172942,0.02962064,0.02791548,0.06868586,0.06045761,0.04121795,0.07252553,0.03800521,-0.05869023,0.07397178,0.01346575,-0.02699367,0.06029796,0.01622976,0.07358286,-0.00492607,-0.00995077,0.02896909,0.04263146,-0.07162801,0.05726494,-0.07455023,-0.01619183,0.04426049,0.03100861,0.07728099,-0.01396169,0.000289,-0.03133612,0.03811375,0.00631211,-0.05694642,-0.11331567,0.00079572,-0.07213601,0.00987426,-0.01701918,-0.08603612,0.05492401,-0.09123227,0.0511962,0.03209916,0.04105257,-0.15187658,-0.00819569,3.66e-06,-4.694e-05,-0.00193576,-0.04861372,0.03859435,-0.02124758,0.05519366,0.05735583,0.00119476,-0.01375154,-0.05249287,-0.05006602,-0.01681616,-0.0568711,0.06277306,0.00049501,-0.03022789,-0.07323017,-0.04663725,-0.03547358,-0.00620233,-0.0144655,0.00411904,0.05562538,-0.01371124,-0.12195272,0.04246984,-0.03798775,-0.04983195,0.07232922,0.03869901,0.04555241,0.00840495,0.05607232,0.05572548,0.00279953,0.08967805,-0.03191501,-0.0416612,-0.07759226,0.02884923,0.07212582,0.08015994,-0.07276344,0.02374221,-0.07159302,-0.03097092,0.04555218,-0.00901308,0.02754774,0.08741331,-0.03723655,-0.00788982,-0.07101209,-0.08699779,-0.07076756,0.01113885,-0.01134131,-0.00052876,-0.0714167,0.01219986,-0.00387629,0.04888942,0.01669946,0.01348413,-0.05517344,-0.03553465,-0.02400163,-0.08919946,-0.02971237,0.05077565,0.00521648,-0.07546918,0.07340885,-0.07409325,0.05864521,-0.01501811,-0.01226848,-0.02025425,-0.08122974,0.0081912,-0.10372741,-0.03332846,-0.01258645,0.01667118,0.06034409,0.00359894,0.06485134,-0.03231441,-0.04510748,0.01939559,-0.04431668,-0.05092534,-0.00100801,0.01468563,0.03462586,1.067e-05,-0.01247167,0.05185798,0.0294944,0.05168486,-0.00317327,-0.02390727,0.01978408,-0.02292343,-0.00627167,-0.00028408,-0.02143116,-0.01498983,-0.06838149,-0.01410335,-0.03177546,-0.04889337,0.01476684,-0.06871245,-0.05019248,-0.00608961,-0.03429922,0.00271472,0.1386584,-0.02020184,0.03329139,-0.03341036,0.04418941,0.02718448,0.05275406,-0.01735892,-0.00412623,-0.03680898,0.02155336,0.07937598,0.03170238,-0.03107334,0.15347853,-0.02066693,-0.02591518,-0.02276402,-0.05967228,0.01728229,-0.08482292,0.0033311,0.01866459,0.00989414,-0.0675265,0.02309883,0.02407309,0.01608183,0.10156604,-0.02195134,0.01861546,-0.0153461,0.00935705,-0.06791197,-0.0553382,0.07165621,-0.00740604,0.00018733,0.07941672,0.06999408,-0.05019666,-0.05558144,0.01399909,0.13818225,0.01885916,0.01039403,-0.04844451,-0.05250303,0.00134401,-0.05517745,-0.07783336,-0.00446152,-0.00144331,-0.13686132,-0.00858008,0.03945638,0.10114758,-0.01050291,-0.03190625,-0.01652935,-0.02289581,0.01624839,0.05909595,0.06630328,-0.1116059,0.05216481,0.05793716,0.07153862,0.05696832,0.05629997,0.07865267,0.08968947,0.02176374,1.18e-06,-0.03272719,0.05028126,-0.05890351,-0.02038395,-0.00975814,0.0402698,0.02980797,-0.008379,0.0273679,-0.0253781,0.08004745,-0.02715512,0.02446376,0.00948101,0.03603999,0.05704146,0.00979402,0.06317878,-0.01994558,0.08188601,0.01273465,-0.01168597,0.05294091,0.00548616,-0.17981821,-0.00761639,0.00043862,-0.04151226,-0.04109421,0.01534935,0.00828868,0.05146919,0.00822061,-0.0884528,-0.01562413,-0.04150344,-0.06141542,0.03435634,-0.01968746,0.07904405,-0.00185726,-0.03196232,0.00204404,-0.00769573,0.02874139,0.01901352,0.02072819,-0.07810939,-0.00061966,0.0576872,0.04562323,0.06390412,-0.08940869,0.03346969,-0.04356751,-0.02565434,0.04985908,0.0091911,-0.02566502,0.00046882,0.07282566,0.01103264,0.02158438,0.02314734]');
INSERT INTO public.detalles_proyectos VALUES (47, '2026-07-02', 'Pregrado', 'Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.', 1, 'Laboratorios de Arquitectura del Computador', 'Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy', '2026-07-05 17:21:44.350197', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.06448983,0.0465988,0.02280469,-0.05580477,-0.09324167,0.04576209,0.0174903,0.01999002,-0.0389492,-0.03723685,0.03830434,0.06036446,-0.01539931,-0.05904765,-0.08364032,-0.03556948,0.02459007,-0.01915107,0.08914446,-0.00454355,-0.04755467,-0.03509071,-0.03426526,0.04052486,-0.00272846,0.07767026,0.0571255,0.09068476,-0.01188905,-0.0117748,0.01940639,-0.04924344,0.0149418,-0.06325686,-0.02227806,-0.03443267,0.02089254,-0.06837152,-0.01273001,0.06760722,0.02126968,-0.01086468,-0.06685381,0.032875,0.10839072,-0.05661145,-0.04536075,-0.01065204,0.01020286,-0.03657684,0.09474011,0.01033114,-0.0140318,0.00888464,0.00916315,0.00410475,0.05178458,0.00172395,0.09877757,0.03644921,-0.01681703,0.03403699,0.04634671,-0.03288585,0.00887764,0.00237092,0.00745338,-0.02959731,0.06177482,-0.02573216,-0.05487533,-0.03184541,0.05014169,-0.04759231,-0.00219348,0.02969966,-0.06944813,0.00319626,-0.01169222,-0.01695162,-0.01990188,-0.09927738,-0.03948747,0.03745273,0.00699464,0.05616161,-0.02951664,-0.10370868,0.05432235,-0.03727969,-0.02064489,0.01527196,0.01874666,-0.05295685,0.04042334,0.02550602,0.03575947,0.07530215,-0.00051638,0.03283917,0.04742054,-0.07192974,-0.06991675,-0.04837787,0.04018791,0.00324893,-0.002482,-0.0547107,-0.06212146,-0.12562971,-0.0982222,0.00809022,-0.05854598,-0.07074829,-0.00999463,-0.03888914,-0.06513531,-0.05480907,0.01082561,-0.09769225,-0.03079318,-0.02121283,0.02808736,-0.05296375,-0.00111668,0.00387748,-0.05373805,4.66e-06,-0.0399932,0.05603467,-0.02592405,-0.05557694,-0.08227412,-0.02408071,-0.02361418,0.04922242,0.01566393,-0.00280949,0.02407273,-0.00310434,0.03473019,-0.06941394,-0.00638226,-0.02306072,0.00200887,0.03648394,0.05872592,-0.05435925,-0.00627297,-0.0337174,0.05914794,0.02403281,0.02184879,0.0960886,-0.08393518,-0.05484844,0.06074519,0.01245445,0.05249097,0.15187173,0.04114362,-0.07074384,0.01753349,0.00849938,-0.05958613,-0.01586658,-0.02957829,0.05335399,0.06143685,0.08869932,-0.04937519,-0.01363101,0.05031347,-0.04439474,-0.08143286,-0.0029576,0.13388969,-0.02469535,-0.07650213,0.02925586,-0.03336916,0.05673093,-0.0075493,-0.04132922,0.06621234,0.05073897,0.10762332,-0.00622063,0.06587288,0.09514405,-0.06013431,-0.11091626,0.0103572,0.05257558,0.01450447,-0.00588555,0.00880234,-0.07892425,0.08201216,-0.09706954,-0.03911303,0.06144134,-0.02857747,0.05818791,-0.05147565,0.04748667,0.04637634,-0.11737164,-0.01436379,0.01420828,-0.02072893,-0.0119866,0.1119782,-0.03866879,-0.00048418,-0.01313484,0.0373875,0.02560668,0.05968024,-0.03110668,0.05294556,-0.06680074,-0.02310379,1.298e-05,0.05274138,-0.02398259,-0.06591874,0.02561166,-0.01599483,0.01395769,-0.03089512,-0.00953927,0.03122618,0.04997877,0.10134134,-0.02861629,0.07990709,-0.0322732,0.05273529,0.0406678,0.00483051,-0.02276465,0.04043539,0.0331941,0.05635567,-0.004721,0.08231361,0.01541563,-0.02395761,-0.02107016,0.04782963,0.01747581,0.00026481,-0.00531913,-0.02312344,0.04082042,0.03001735,0.04274067,-0.07433388,-0.04588127,0.10580148,0.03744415,0.03885818,-0.08663519,0.04063747,0.01552472,0.08043502,0.07541297,-0.06954139,-0.05293809,-0.08567439,-0.03310029,0.04853573,0.01983537,0.07904085,-0.09920345,0.04097169,0.00962595,-0.06631479,-0.00810421,-0.03422549,0.00377883,-0.00067806,-0.07982314,0.08157405,-0.02657601,0.00606611,-0.05159419,0.05351188,-0.04771727,-0.05070164,0.02057046,-0.00016602,-0.12815174,0.03506975,-0.01190996,0.0574235,-0.01192607,-0.05562335,0.00740106,-0.08937769,0.01240173,0.07029103,-0.04095932,-0.11235258,-0.04032819,-0.03060673,-0.03562641,0.00443875,-0.12528253,0.01402496,-0.03256925,-0.04841188,-0.02320958,0.0111534,-0.06151342,0.01856492,0.03389163,0.01475933,1.38e-06,0.04469178,-0.00289004,-0.02438035,0.02016933,0.00674395,-0.04002806,-0.03541259,-0.04428581,-0.02802256,0.03105943,0.08094096,-0.007003,0.06412657,-0.03557343,-0.03672655,0.08364383,-0.04719085,0.07221584,-0.05155825,-0.02930643,0.05655834,0.05046036,0.06567506,-0.022585,0.02486093,-0.02325247,0.08103045,-0.02581856,-0.00210278,-0.09087072,-0.06081112,-0.01448554,-0.02389266,-0.07638979,0.05823614,0.0245858,0.00385246,0.08923402,0.01400163,0.0258576,0.01990479,-0.10276471,-0.14979811,-0.01904417,-0.02323032,-0.0428985,0.01567174,-0.00091954,-0.08419458,0.04121628,-0.00486338,-0.01265774,-0.00620186,-0.06090141,-0.01039885,0.01256314,0.0105411,0.11065954,-0.00861015,-0.00073073,0.01586173,-0.00151298,0.0868335,0.00541441]');
INSERT INTO public.detalles_proyectos VALUES (48, '2026-05-10', 'Pregrado', 'Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.', 1, 'Departamento de Sistemas de la Universidad', 'Microkernel, PHP, PostgreSQL, MVC, Arquitectura', '2026-07-05 17:21:44.350197', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.06482898,-0.01242675,-0.12893543,-0.01021572,-0.10795403,0.05504145,0.05208448,0.00938992,-0.08265727,0.05262602,0.04487297,-0.04937532,-0.01779244,-0.13757776,-0.07454298,0.0278431,-0.02031571,-0.04703383,0.08127686,0.00966111,-0.10716805,-0.05471998,-0.13896377,0.04248256,0.0022418,0.02996452,-0.0502742,0.07715416,0.03668294,-0.04254703,0.02155524,-0.02267726,0.0936585,0.06578569,0.05173556,0.05463691,-0.02688435,-0.08802663,-0.02892433,-0.0318543,-0.03943635,-0.08081779,-0.15205325,-0.01353524,-0.01337076,-0.01343168,0.03181561,0.01703717,-0.07010649,-0.07165053,-0.06116873,0.01203308,0.06952353,-0.04479445,-0.05179548,-0.03180889,-0.10481497,0.00784903,-0.04080785,0.00617304,-0.01090947,-0.04445794,0.01723066,0.00454781,0.00542681,0.00349348,0.02520948,-0.08738646,0.08778258,-0.03060913,-0.04677969,-0.03388377,-0.08845193,0.02112295,-0.04752939,0.06531939,-0.08701344,-0.05737883,-0.02024843,0.06649928,0.0405247,0.0464065,-0.05374947,0.02873947,0.03794344,0.00625697,0.02425816,0.00334764,0.04776946,-0.00592758,0.01320728,0.06789063,-0.01033012,-0.02311541,0.0032034,-0.0284455,0.03050771,-0.08007047,0.0750267,0.07943398,0.01878495,0.01378764,-0.07166777,-0.06194695,-0.04468468,-0.06821743,0.06679649,0.01310313,0.10434888,-0.03518927,-0.05655005,0.0295749,-0.10579084,-0.02224505,0.00555546,-0.00810545,0.11301942,-0.06289068,0.05398262,-0.05087015,-0.0183704,0.0227758,-0.14944535,-0.05833902,-0.0203992,-0.03310952,0.01323809,6.64e-06,-0.03029977,0.03747408,0.06249157,-0.09488545,-0.02881342,0.07361001,0.00469932,0.00402504,0.08195972,-0.01243929,0.00188713,0.02243231,-0.02996139,0.06531799,0.03989376,-0.05175435,-0.03695555,0.01461901,0.10724998,-0.05859931,-0.07971433,0.00321848,-0.04294744,0.03762684,0.00211454,0.07831292,-0.008396,-0.03392991,0.00147668,0.01032445,0.04442718,0.01773158,-0.06994119,-0.0239131,-0.03039632,-0.00483495,-0.03944434,0.01048888,-0.0523469,0.0180626,-0.02150914,0.07556024,-0.0078112,-0.02193688,-0.03254848,-0.0279931,-0.13585255,-0.01952973,0.01546402,-0.05241517,-0.05843135,0.02989334,0.02316277,0.04092037,0.092716,-0.02028159,-0.04391826,0.02112717,0.02576702,0.01316522,-0.02745187,-0.08201811,0.02590703,-0.05000825,0.00450052,-0.03742013,-0.06460936,0.06349769,0.08741399,0.03498794,0.03196387,0.08395029,0.02693837,0.00474849,0.0086993,0.00052363,-0.06704244,0.05589186,0.03517829,-0.01488201,0.00384566,0.00121321,0.10555012,-0.00628154,-0.00473272,0.04371855,-0.0087372,0.11202955,-0.03409002,0.02143031,0.02345582,0.04276076,0.1300515,-0.00403733,-0.00056606,1.751e-05,-0.00644765,-0.07040546,-0.04222085,0.03784067,0.00957091,0.07556244,-0.0261037,0.00075533,-0.01763814,0.017609,-0.06384834,0.05855374,0.05931597,-0.07057898,0.0401147,0.0458937,-0.05097588,-0.04293579,-0.0031718,0.02988673,-0.06765051,0.04760053,0.04715003,0.00657366,0.00598196,-0.055822,-0.02538235,0.02135349,-0.01130093,-0.0237872,0.01332973,-0.0435283,0.0291438,0.0879761,0.0005776,-0.05692314,0.00767436,0.06361745,-0.03519483,0.0485172,0.02378258,-0.04383275,0.00977787,-0.04507032,0.00018584,-0.03945004,-0.03655587,-0.07061758,0.00179538,0.03651725,-0.02481396,0.02596126,-0.03332224,-0.02563841,0.01715229,0.0073085,-0.02850801,-0.01283987,-0.09169065,-0.0121787,-0.03882038,0.0566286,0.02009393,-0.0128155,0.07318382,-0.03296738,0.01949249,0.04296525,-0.02400847,-0.07022057,0.02404976,-0.04696014,0.06966964,0.00673077,0.01281933,0.01057446,0.03677343,0.05776708,0.07905475,0.04360838,-0.03485261,0.02667631,-0.00799194,0.04694964,-0.01845475,-0.03657015,-0.01322338,-0.08827658,-0.04033744,0.03010583,-0.00395772,0.04188775,0.01072451,0.03308252,0.03358057,1.54e-06,0.08230179,-0.09209507,-0.09894143,0.04058701,0.07519052,0.03362224,-0.03289265,0.03427833,-0.06259124,-0.03711228,0.12175845,-0.09534863,0.10273858,0.0488175,-0.05194069,-0.04918623,0.04418678,0.0295129,-0.05703179,-0.07828272,-0.15601496,0.00883209,-0.03139623,-0.00329684,0.00669045,0.0374462,-0.03557166,0.05070236,-0.00635604,0.01458288,-0.05455059,0.05114434,0.0058493,0.06532695,0.022652,0.05370055,-0.00119997,0.00113539,-0.0838059,0.0138098,0.08069883,-0.02237434,0.01063381,-0.05318314,-0.00419569,0.01933747,-0.00645158,-0.01667746,0.00165048,0.09277122,-0.01493451,0.02897889,0.04560894,0.06932175,0.03646963,-0.00745971,0.02954598,0.00290501,-0.0109221,-0.05140699,0.01718177,0.0094672,0.00468071,0.05405777]');
INSERT INTO public.detalles_proyectos VALUES (50, '2026-04-22', 'Pregrado', 'Aplicaci¢n interactiva dise¤ada como medio did ctico para facilitar los procesos de ense¤anza. Combina fundamentos comunicacionales y l¢gicos mediante una interfaz interactiva de alto rendimiento.', 1, 'µrea de Ciencias B sicas de la Instituci¢n', 'Edum tica, Software Educativo, Multimedia, µlgebra', '2026-07-05 17:39:35.498485', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.0881173,-0.06049242,-0.0064408,-0.07760451,-0.10290827,0.02299566,0.05417821,0.08699108,0.0296529,0.0034709,-0.0026842,-0.0039444,-0.00510847,0.01467781,-0.02891866,0.03028914,-0.06918698,-0.00812323,-0.10545123,0.00653463,-0.01184006,-0.05816011,-0.03507325,0.02827756,0.07382715,0.00866473,0.02549097,-0.02148714,0.06546332,-0.05629879,0.04658933,0.07818762,0.21410649,-0.00371416,0.0524601,0.00694916,0.0826258,-0.03974468,-0.0860998,-0.02900335,-0.08837133,0.04465679,-0.0046907,-0.06383538,0.01865809,-0.13520078,-0.018919,0.00633916,-0.0113422,0.05373059,-0.0450619,-0.01220357,-0.05622,0.00966525,-0.07575958,-0.00787655,0.05487563,0.00845794,0.0082156,0.01884196,-0.06347123,-0.01536262,0.00698935,0.04761713,-0.08675839,0.03384752,-0.01831056,-0.00677039,0.05047592,-0.07580626,-0.06165692,-0.08022068,-0.00998752,0.02193167,0.05262696,-0.04878437,0.05631771,-0.00238436,0.03700093,-0.05220678,-0.00496959,0.026808,0.00445827,-0.05336708,0.03392053,-0.00746457,0.03443577,-0.02705578,0.0750502,-0.01916989,-0.0782062,0.05998136,0.00027674,0.01632153,0.03784293,-0.03857504,-0.02345247,-0.14514709,0.04912261,0.03261943,-0.02076927,-0.00602493,-0.0825342,-0.05309196,-0.07462132,-0.03503861,0.02771953,0.01156068,0.02037508,-0.00900057,-0.03694515,-0.05610572,-0.03679304,-0.0113229,-0.02765069,-0.00513393,-0.04225993,-0.10043641,0.09300306,-0.02506527,-0.02820061,-0.04674629,-0.10415181,0.00579404,0.00499586,-0.03618593,0.00482439,5.5e-06,-0.04325782,-0.07167581,0.00109899,0.00919791,0.00557461,0.02524677,-0.0471586,-0.055581,-0.03394094,-0.02712883,0.01114864,0.07489142,-0.0296231,0.09903698,-0.00210488,-0.05055111,-0.05282722,0.05588593,-0.00610346,0.06067447,-0.06407093,-0.02248276,0.03147624,-0.01092445,0.00104672,0.07995712,0.02550819,0.017683,0.2198954,0.01993295,-0.0062081,-0.12183768,-0.00062487,-0.07902705,-0.04382462,0.04268747,-0.00024947,0.02851717,0.02695294,0.06218903,0.04158349,0.05607078,-0.01516815,-0.06234383,-0.00873609,0.10082693,0.0240227,-0.00668969,-0.05378328,0.00652817,-0.02845858,0.05631311,0.00254602,-0.01704216,0.0068462,-0.04611718,-0.04825296,0.03730292,-0.0146393,-0.01224469,0.01123881,0.01766369,0.05010198,0.02914436,0.00663783,-0.00092595,0.05311561,0.00346288,0.04495638,0.03238748,-0.08167846,0.02112225,0.00303466,0.04032985,-0.02380589,-0.08614836,-0.01186734,0.00219228,0.06733347,0.04120394,-0.03730553,-0.0050524,0.05710316,-0.02875224,0.01039814,-0.01145621,-0.01264915,0.00157293,-0.05452437,0.02422524,0.00674155,0.04816088,-0.06859677,0.1167888,0.10430143,1.498e-05,0.02016421,0.06305895,-0.01984058,-0.06658615,-0.00672852,0.08752864,0.01555573,0.03505673,0.06512158,0.02644234,0.03830277,0.02041671,-0.08750529,-0.0204286,-0.03684153,-0.05483008,0.12749678,0.02619397,0.00226408,-0.00971129,0.03933006,-0.05660197,0.00027588,-0.02903735,-0.04296512,-0.02324673,0.00822269,0.03079611,-0.00408244,0.03525545,0.07782961,-0.02628715,-0.08100307,-0.06622537,0.01389609,-0.05521023,0.06886476,-0.01512953,-0.05149031,-0.01078044,0.08611067,0.00943605,-0.00984811,0.00572035,0.02410203,0.0288174,-0.13836971,-0.01206343,-0.01049476,0.03901083,-0.01643745,-0.00785341,0.10650544,-0.08262526,-0.02071962,-0.02102583,0.01643939,0.01866895,-0.03179518,-0.00024546,0.03296825,-0.04671981,-0.05993583,-0.11442524,-0.00733783,0.06982293,0.02052999,-0.08029651,-0.08309937,0.06259383,0.12420749,0.06002294,-0.0616226,-0.03923443,-0.04318045,0.09678904,-0.00116925,-0.02479051,-0.00780275,-0.02408278,-0.02327828,-0.0056237,0.05278199,0.03452919,-0.06150309,0.07997016,0.00319065,0.01203943,-0.02504897,0.00166555,-0.02525772,0.00927317,0.03967802,-0.02863904,-0.02861987,1.4e-06,-0.00310078,-0.08862547,-0.00497319,-0.08161668,-0.03170121,0.13298164,-0.04996199,0.07915188,0.02555834,-0.03116726,0.01096648,-0.02905624,-0.05244361,0.07243471,0.02818126,0.04928845,0.07327229,-0.02005003,-0.02049595,-0.0283929,0.00412368,0.01041295,-0.00393516,-0.02590711,-0.12885296,0.02791841,0.10423377,0.05591415,-0.05772032,-0.00360787,0.04098054,0.03995291,-0.00847847,-0.02664393,0.02075733,-0.05023335,0.05711339,-0.01130084,-0.04213123,0.04450078,0.0158259,-0.01490828,0.06368772,-0.06801029,0.0333611,0.05218417,0.01564038,-0.06754175,-0.01251688,0.01584644,-0.05381097,0.00894561,-0.07346859,-0.07106524,-0.03797047,-0.0119414,0.00883032,-0.03358841,-0.02069073,0.03444833,0.04482551,-0.03666399,-0.01863585,-0.00723958]');
INSERT INTO public.detalles_proyectos VALUES (51, '2025-07-10', 'Pregrado', 'Dise¤o de un sistema distribuido cooperativo entre clientes y un servidor centralizado. Permite la gesti¢n din mica de solicitudes concurrentes controlando de manera efectiva las peticiones HTTP contra la base de datos.', 1, 'Coordinaci¢n de Control de Estudios', 'Web, Cliente-Servidor, PHP, PostgreSQL', '2026-07-05 17:39:35.498485', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.09319069,0.00330168,-0.11382248,-0.08147517,-0.13300093,0.04374662,0.00583743,0.012194,0.01090111,0.06578995,0.03395673,-0.0166021,-0.01274808,-0.09442797,-0.00245573,-0.00447566,-0.00586829,0.00219299,0.02452259,-0.02021965,-0.11242688,-0.10440294,-0.07890226,0.01677518,-0.06811191,0.04478786,-0.04617771,0.00833043,0.0148738,-0.02909208,-0.00142961,-0.09728412,-0.00288766,0.04879219,-0.07067325,-0.04296197,0.0207878,-0.09371291,0.01210785,0.00219776,0.05416116,0.00747363,-0.06354482,-0.01852726,-0.06942371,-0.03460001,-0.07099093,0.08182341,-0.04033076,-0.0352226,-0.06760382,-0.02834967,-0.03198765,0.01659018,-0.02841686,-0.08905509,-0.06207192,0.03680708,0.01994311,0.02625384,-0.0573928,0.02063734,0.01774509,0.03702307,-0.0021919,-0.02037796,0.00348122,-0.00470905,0.01661746,-0.06294605,-0.01282602,-0.0379798,-0.09809872,0.0094198,-0.03986539,0.00232292,-0.03168159,0.02196665,0.05646159,-0.02647785,0.07404383,0.05548152,-0.00117925,0.05064129,-0.0117475,-0.06909005,-0.01678258,0.01914473,0.08347887,-0.0035687,-0.0055684,0.04721923,0.01089205,-0.041914,-0.10030724,0.03469316,0.04055228,-0.0142657,-0.04084077,0.06447319,0.05721816,-0.04236595,0.00486832,-0.0331581,0.01465587,-0.03441402,0.02572804,0.06251917,0.04130629,-0.00974483,-0.16633166,0.04536764,-0.12221479,-0.12426932,-0.03687902,0.03556165,-0.04492177,-0.03335054,0.04037789,-0.06627113,0.01525556,-0.03788666,0.00966523,-0.03453946,0.06211973,-0.05911991,0.07486712,4.27e-06,-0.04196075,-0.08758742,0.01858441,-0.054607,0.07446315,0.06391434,0.04466327,0.05335794,-0.10196412,-0.03931998,-0.00020706,-0.02240321,-0.02897515,-0.00118583,0.07909741,-0.01951807,0.00841536,-0.03579913,0.151089,-0.05624704,0.01709262,0.00501812,0.05215989,0.0963945,0.03593767,-0.03424639,-0.08896816,0.06449628,0.03188006,0.05628174,0.11039649,0.03524681,-0.04622522,0.01547426,-0.05953187,0.03265038,-0.04932178,-0.03620996,-0.03887617,0.06886161,0.00622468,-0.00588107,-0.04056746,0.02899591,-0.08476393,-0.02459246,-0.10440087,-0.05322257,-0.07969811,0.02344478,-0.06736568,0.05275278,-0.00037966,0.09667801,0.06245021,0.02408809,-0.04911022,0.00340742,-0.03427722,0.03885229,0.01478142,-0.07838003,-0.04792512,-0.03448529,-0.02794636,-0.06666886,-0.02086892,0.02451416,0.04520784,0.03352137,-0.01660136,0.03018395,0.02656664,0.00593063,-0.04634854,-0.00986137,-0.08505461,0.05571276,-0.00864976,0.02353033,-0.01015996,0.02101566,0.02863548,0.11509073,-0.00385665,-0.01103582,0.0243213,0.09359771,-0.06462986,0.01563665,0.01327547,0.07709138,0.07771394,0.01126682,0.06497073,1.228e-05,-0.01232378,-0.05002007,-0.0494481,0.0078704,-0.01120699,0.01831153,0.02817837,-0.06857943,-0.05512614,0.08048384,-0.00523545,0.01573347,0.02433404,-0.02394496,-0.02925892,0.00359427,0.0022659,-0.05287128,0.00472693,-0.05537172,-0.09053249,0.05127715,0.09630965,-0.02767371,0.02319618,-0.07114677,-0.04624583,0.04108937,0.0120432,-0.00051576,-0.02436055,0.00436988,0.03467908,0.02154506,-0.04522758,-0.03767786,0.06591067,0.06071373,-0.0347911,0.01397596,0.08618851,-0.01261032,-0.04287492,0.02681019,0.09741436,0.05260343,-0.11440442,-0.00700481,-0.03822052,0.07066502,-0.05165073,-0.00050085,0.00963031,-0.01875162,0.04108413,0.00276001,0.0103116,-0.00366842,-0.010927,-0.02728566,0.04013248,-0.02231318,-0.06369069,0.02008212,0.12322354,0.00919166,0.01975852,0.07785864,0.09901827,-0.0276965,0.03061932,0.00550301,0.01818035,-0.03432827,0.04417792,-0.04983063,0.01570502,0.0393012,0.04370616,0.00794314,-0.05551761,0.0081545,0.05570983,-0.01101191,-0.03562595,-0.03952703,0.01395176,-0.03030041,-0.0014956,0.03144067,-0.0670287,0.00597309,-0.00439051,0.02226956,0.00526738,1.17e-06,-0.00366487,-0.03561518,-0.10561677,0.07545474,0.03130032,0.09178992,-0.09023384,-0.06870222,0.02292922,0.09344059,0.07312846,-0.03110523,0.02626887,0.03145622,-0.08524079,-0.00055917,0.02412266,0.01288578,0.02341562,-0.05751693,-0.05739877,0.01827496,-0.00626977,-0.00457678,0.01390513,-0.01784689,-0.00581086,0.04631483,-0.0795884,-0.02013898,-0.10630256,0.00533542,-0.00407596,-0.00380607,0.07831436,0.00592899,-0.12286701,-0.00598776,0.01341463,0.00055281,0.11730584,0.04221803,-0.00732272,-0.0297857,0.12759686,0.05552807,0.04122608,0.00517019,0.07396473,0.0115684,-0.03524096,0.03207189,0.07336635,0.04479602,0.02449736,-0.00716868,0.04795732,-0.05110713,0.04488664,0.02374366,0.01348167,0.06097936,-0.00526319,0.00989919]');
INSERT INTO public.detalles_proyectos VALUES (52, '2026-06-18', 'Pregrado', 'Herramienta de simulaci¢n orientada al testeo preventivo de la transmisi¢n de datos. Permite modelar el comportamiento de las decisiones de routing antes de iniciar el despliegue f¡sico de una infraestructura de red.', 1, 'Laboratorio de Redes y Telecomunicaciones', 'Simulaci¢n, Routing, Algoritmos, Redes, Topolog¡a', '2026-07-05 17:39:35.498485', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.05878708,-0.00471802,-0.03188194,-0.07042395,-0.00321706,-0.010422,-0.04166001,0.02090903,-0.05147251,0.0245848,0.00037682,0.02806655,0.03132785,-0.02145663,-0.01869541,0.00812651,-0.041057,0.03890298,-0.03755574,-0.07735648,0.03454912,-0.03236581,-0.07872863,0.01977835,-0.06489126,-0.04751194,0.01173989,0.05574642,0.01042522,-0.06674985,0.05911957,0.04070331,-0.06409006,-0.02925953,0.0371731,-0.07817458,-0.01582887,-0.07901146,0.04267913,0.00042843,0.01451829,-0.02628787,-0.00216054,0.02588694,-0.03639306,0.01096603,0.11993766,0.10404498,-0.03465944,-0.01620071,-0.01175162,0.00917398,-0.03136853,0.00754416,0.08315817,0.02958166,-0.01796031,-0.07974353,-0.00396371,0.06349711,0.00924231,-0.02441544,0.02151835,-0.02024913,-0.00529121,-0.0292026,-0.04825097,-0.04432591,-0.05170253,0.06932924,0.02887641,-0.03794137,0.05849143,0.01776362,0.01531796,0.05632498,-0.01008277,-0.01674194,0.05218045,-0.06561716,-0.0187889,0.00464259,-0.03924121,-0.00386779,0.09519703,-0.03427752,-0.0432831,0.03613284,0.05790535,0.0437641,0.05580994,0.04472253,0.01468052,-0.02331287,-0.07421949,0.03087455,0.00686492,0.0202096,0.01467185,0.0437986,0.04939358,-0.05182282,-0.03098017,0.00591978,-0.06971198,0.03449922,0.07784429,-0.00601337,0.0138908,0.00139033,-0.00370365,-0.05757191,0.03449255,-0.03992068,-0.15690452,-0.01594506,-0.01719541,-0.01337886,-0.06414484,-0.1403069,-0.0273126,-0.07648935,0.08059002,-0.04705005,-0.01355527,-0.08317901,0.06817172,4.81e-06,-0.05184657,0.01795679,-0.05960825,0.01032731,0.05701061,0.03521838,-0.00171261,-0.03679317,-0.03126386,0.02573193,-0.07642081,0.07105376,-0.04535195,0.04160915,-0.01254179,0.0115684,0.00901069,0.01351968,-0.07411243,-0.00028029,0.04182667,-0.03444648,-0.0141253,-0.03366749,-0.08862094,0.04897575,-0.06186487,-0.07759128,0.00192416,0.03765794,9.582e-05,0.01918201,0.00952919,0.04008472,-0.03070605,0.08562559,0.04133391,-0.01165125,-0.01471097,0.04633693,-0.03291476,0.11210534,0.02971063,0.12575907,-0.04923389,0.03779894,0.01014653,-0.0299103,0.0728661,0.05201608,-0.05368162,-0.01541872,0.07940117,-0.11123253,0.03730527,0.02398878,-0.00058287,0.12073117,0.02112546,0.07945851,-0.05052487,-0.01657386,-0.03085921,-0.00910479,-0.00908561,0.02614562,-0.04763076,-0.14753254,0.081802,-0.05562534,0.00591452,0.02808618,0.02653518,0.06613655,0.00665451,-0.05534163,-0.03310077,0.08870084,0.02415564,-0.1348541,-0.01378622,0.05430378,-0.0782694,0.01351939,0.0492888,-0.04175822,-0.00037316,0.02246782,-0.03771408,-0.04026759,0.0499167,-0.00552027,0.05217111,-0.04329516,0.10526144,1.232e-05,-0.04861025,0.08139976,-0.00743276,0.02346043,-0.06134724,-0.07312952,0.11254187,0.06649574,-0.00076417,-0.02120845,0.03293424,-0.03443252,0.05236046,0.01292152,-0.00036101,-0.04991961,0.00039263,0.01181284,-0.05293961,-0.086996,-0.05846003,0.01744858,-0.07107183,-0.05610538,-0.04339214,0.03977942,-0.02123282,0.04114,-0.03467979,0.00618021,-0.03842813,0.04410974,-0.0614427,0.02375452,0.05633666,0.01318853,0.0347278,0.05109232,-0.03772492,0.04604917,0.01290849,0.03320547,0.06404681,0.10191719,-0.03407836,-0.01622615,-0.08678334,-0.10064494,0.01285057,-0.04048934,0.05337502,0.02975556,0.03336726,-0.03942382,0.09667042,0.01180586,-0.06713553,0.05580457,-0.07640246,0.04058767,0.02666402,0.05203974,-0.0055686,0.01290649,0.06227926,-0.03303309,-0.07493913,-0.07080494,0.03954953,-0.00534145,-0.00721016,-0.01922804,0.01213552,-0.05476977,0.07406503,-0.07653275,0.00574607,0.04818813,0.0480526,0.0425861,-0.10383584,-0.02910788,0.01114807,-0.00796852,0.05490131,-0.0187642,0.02734807,0.0061394,0.00635178,-0.01325357,0.02458531,-0.00593867,0.05732364,-0.03028941,-0.04813859,1.29e-06,-0.04751171,-0.10259103,-0.01955312,-0.07560692,0.01003998,0.12061454,-0.00963894,-0.02979237,0.00525863,-0.02016612,0.07005117,-0.0214925,-0.05788906,0.00531062,-0.0585505,0.06287674,0.046945,0.01243409,-0.08216107,0.02296441,-0.093565,0.10370007,-0.02633906,0.01959181,-0.03817795,0.0493158,0.00756248,-0.06723889,0.0456058,0.10141486,-0.03377961,-0.01434279,0.03604126,0.01103295,-0.01165324,0.0926057,-0.07825499,0.12659279,0.00254154,-0.01481555,-0.02284465,-0.03277497,-0.03723077,0.01033047,0.11573523,-0.01549794,-0.10098196,0.03809041,-0.06102554,-0.02904713,-0.0613728,-0.04045813,-0.05792139,-0.02705676,-0.05035205,-0.09828671,-0.02349352,0.06254437,-0.03873684,0.00693764,0.05167809,0.03617527,0.07807276,0.01613141]');
INSERT INTO public.detalles_proyectos VALUES (58, '2026-07-07', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio. Palabras clave: Gestión doc', 1, 'asdasdasdasd', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-07-07 00:01:54.74783', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.03523557,-0.02716035,-0.0524321,-0.08520749,0.03701183,0.04310807,0.05001116,0.13509654,0.05991996,0.05337836,0.04276185,0.07239116,0.0153185,-0.00280379,-0.09716005,0.00245414,-0.08224452,0.01926512,-0.03305278,-0.00380554,0.02032306,0.0469131,0.03145007,-0.02631412,-0.03441341,0.03683953,0.02930799,-0.02785694,0.01325181,0.02800664,0.07330319,-0.04792113,-0.0073862,0.05349017,-0.04993933,0.03971977,-0.05613498,-0.11747524,-0.01130874,0.06936854,0.06774344,-0.01281329,-0.0100392,0.03751245,0.07319797,0.01342625,-0.06050843,0.01121185,-0.03211298,0.07276388,0.00443273,-0.01519367,0.02723635,-0.00906714,-0.00335689,0.07686965,-0.10638967,-0.0390889,-0.06902968,0.00530575,0.04569143,0.05856752,-0.03675413,-0.02972077,-0.01380975,0.12017376,-0.01527893,-0.06624283,-0.00534961,-0.04023139,-0.02101982,0.03181446,0.00534452,-0.16270696,-0.02928575,0.05215675,0.01374484,0.00291022,0.0062429,-0.11411887,0.08280825,0.07784996,0.04645042,0.03606221,0.08812954,-0.00604017,-0.02642366,0.05685995,0.02007153,-0.03385709,0.03671316,-0.11378961,-0.02764726,0.00593681,-0.03467357,0.01141244,-0.03459016,-0.04521889,-0.05054521,0.0107385,-0.00970973,-0.02211229,0.04818938,-0.07793898,-0.06858337,-0.02809669,-0.03327916,0.00690793,0.02547166,0.01847538,-0.07828066,0.01162393,-0.03743097,-0.08194495,-0.03134601,0.10444866,-0.06957308,-0.01546457,-0.03521315,-0.07048064,-0.04611208,-0.00012783,-0.07760595,0.01078912,0.00175937,-0.06294357,-0.01794874,3.16e-06,-0.08031848,-0.05134587,0.03385962,-0.03840672,0.00124429,0.02667936,0.03105389,-0.10967053,0.05528963,-0.04818692,-0.08253172,0.10659791,-0.09605078,0.04538215,-0.07684478,-0.02707196,-0.02347572,-0.01458773,0.0423413,0.00651644,0.04607697,-0.0176395,0.03326718,0.00238135,0.04216224,0.02918583,-0.043724,0.02155475,-0.03025392,-0.00460159,-0.03458996,-0.01273407,-0.0159536,-0.06063037,-0.05429124,-0.04682454,-0.02743677,0.00633702,-0.00191225,0.0214913,-0.01565784,0.05194787,0.03643058,-0.05398991,0.03731592,-0.02890332,-0.04409799,0.03972606,0.02918981,-0.01556638,-0.00617085,0.03866196,0.00679888,0.03491864,0.08012312,-0.09196315,-0.06028422,0.09756042,0.02840454,-0.05731801,0.08816108,-0.03869787,-0.03019185,-0.0153711,-0.04947238,0.02824722,0.06456438,0.01307167,0.04409578,-0.02369988,0.00971008,0.01403009,-0.07374315,0.0338887,-0.00949995,-0.03697723,-0.00685793,0.00759467,-0.02340878,-0.02460068,-0.05106059,-0.02801161,0.00803106,0.06563188,0.01432368,0.00385949,0.02740646,0.06876476,-0.08545128,-0.1378281,0.07003004,0.04540317,0.0124778,-0.02917465,0.01058776,1.059e-05,-0.04484987,-0.05660884,-0.0888893,-0.00272649,-0.02545594,0.05972765,0.08223978,0.0554148,-0.02187314,0.00738769,0.01559326,0.06416959,-0.05000976,-0.10435006,0.03093498,0.00414394,-0.08172451,0.14708854,0.03757078,0.02186105,-0.00207458,0.09642231,0.05720297,0.02533559,0.02821797,0.00502379,-0.01922253,0.08224639,-0.12110133,0.00163114,-0.03654786,0.00564651,-0.08324595,-0.00783861,-0.03081059,-0.09175055,0.12569433,0.04343898,-0.09123324,-0.00953419,0.10997122,0.00777105,0.01469098,-0.02006248,-0.01386725,0.00727849,-0.15096743,-0.09204016,-0.02005604,0.00292809,-0.01559852,0.02088628,0.06605867,-0.05841217,-0.03997982,0.09488094,-0.00037193,-0.02389941,-0.00776049,0.03530047,-0.03759222,-0.01883751,-0.07941622,0.03262605,0.04333489,0.00939181,-0.01900309,0.08170305,0.03780415,-0.00287823,0.03650812,0.08444896,0.02406265,0.03225981,0.01101367,-0.00328891,-0.00016517,0.00021885,0.00583528,-0.01161346,-7.447e-05,-0.04711734,0.01425301,-0.00123587,0.01147438,0.03683831,0.05074221,-0.06521446,-0.03978531,-0.042089,-0.00729994,0.03218472,-0.00453154,0.02750947,-0.01118345,1.19e-06,-0.02139513,0.00055843,-0.04573605,-0.12669359,-0.02223052,-0.02559011,-0.06309723,0.03916512,-0.04344369,-0.0238971,0.04202694,-0.06033394,-0.02749308,0.02004047,-0.01536559,0.01982866,0.02425398,0.07629926,-0.03150508,-0.13042273,0.05424482,0.02525458,0.01534148,-0.00436791,0.04848748,-0.02045419,-0.02704306,-0.00300756,-0.01841648,0.02690487,-0.01042185,-0.00328725,0.04950624,0.07742686,0.03195438,0.07130092,0.06700291,-0.03233721,-0.08437381,-0.01858179,-0.08630048,-0.03699256,0.01716634,-0.01832347,0.04656533,-0.06004304,0.09715007,-0.03048383,0.09380993,0.03086828,0.04364429,0.05104549,-0.00138269,-0.0580938,-0.09442784,-0.02637425,-0.00231193,0.03037823,-0.05267247,-0.06708918,0.00556398,-0.04229501,0.00805,0.06332159]');
INSERT INTO public.detalles_proyectos VALUES (59, '2026-11-10', 'Pregrado', 'El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”', 'Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa', '2026-08-04 09:22:58.539505', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.08061925,0.02169837,-0.0253053,-0.06889659,-0.04207453,0.0218488,-0.00751984,0.05311544,-0.1160316,-0.00955705,0.04996216,0.06143745,0.06094419,-0.08960557,-0.03344204,-0.02806714,0.03495953,0.02117248,0.02122598,0.00434399,-0.06445193,-0.07627258,0.05826146,-0.0316784,-0.03796601,-0.02809276,-0.00588363,0.08304581,0.03329216,-0.02192486,0.03053608,0.02878033,0.0380925,-0.01542312,-0.01146378,0.06423875,-0.01799238,0.01760296,0.01326503,0.00569564,0.05681512,-0.01267994,0.00344657,-0.01237264,0.07414891,-0.02448379,-0.10053689,-0.03596463,-0.03700533,-0.0427619,-0.07733418,-0.02590814,0.13635209,0.01948516,0.01375574,-0.06612012,-0.04785447,-0.04205837,0.04115665,-0.06406959,0.00887267,0.06631275,-0.01768074,-0.01795657,0.06965032,0.02084623,0.06897928,-0.09328443,0.04011937,0.04535988,0.03387896,0.02200326,-0.05690849,0.05049281,-0.03323227,0.04979615,0.06944759,0.02307008,0.02983316,-0.04492558,0.05756624,-0.01062244,-0.05539789,0.07334554,-0.01694337,-0.01921596,-0.09342089,0.02918241,0.0142703,-0.00595245,0.00086047,0.00693335,0.102985,-0.03978071,0.08534438,0.06902554,-0.05422498,0.02233265,-0.02114917,0.00891216,0.00198982,-0.03068024,0.02195457,0.01093115,-0.07540668,-0.04646762,0.04881693,0.02983733,0.09135804,-0.01618152,-0.0549802,-0.03159841,0.000284,-0.03117632,-0.05720156,0.0697192,-0.00257085,0.02035611,0.01721217,-0.01702365,-0.07032182,-0.07383038,-0.06695912,-0.0359265,0.02878427,-0.0534852,-0.08271644,3.93e-06,-0.02533716,-0.08427909,0.01777133,-0.06775224,0.00369148,-0.077801,0.04081412,-0.07617587,0.00468337,-0.04926639,-0.08314979,-0.05276524,-0.09663278,0.12050484,0.02006159,-0.13408832,-0.09616267,0.04549307,-0.00276489,0.07285092,-0.00099286,-0.12616523,-0.01593284,-0.01044656,0.03967458,0.02973671,0.02467249,0.02024776,-0.08681322,0.02108511,0.02696962,-0.07200932,-0.03562161,-0.0048216,-0.10055562,-0.05064826,-0.02141499,0.01045413,0.01853319,0.02495847,0.00370714,0.0469331,-0.00435465,-0.02648233,-0.02083205,0.05148839,-0.10041354,0.01856858,-0.03079085,0.05370736,0.03238773,0.0298033,-0.01270612,-0.01891463,0.04471091,0.00865101,0.01152547,0.08294056,0.00530587,-0.02788366,0.01490634,-0.08304153,0.03156795,0.04498548,-0.02662712,0.06824725,0.00077315,-0.08233787,0.07030042,0.01833228,-0.05619781,-0.10023483,-0.04746797,0.04968431,0.02557358,0.0287751,-0.05001208,0.06169405,-0.06660734,0.00732529,-0.04807845,0.07364889,-0.10086263,-0.02701707,0.12412482,-0.05482635,-0.01194657,-0.01202501,-0.04555581,-0.07090429,0.01019203,-0.02648565,0.02041066,0.04571757,-0.02513621,1.279e-05,-0.02085972,-0.0191411,0.05176339,-0.00024511,-0.04397682,0.039294,0.02301449,-0.04516997,0.02480831,0.09043653,0.05561671,0.0060611,0.01160696,-0.09447072,0.02484757,0.04760745,-0.02300304,0.06242176,0.1329788,-0.00794451,-0.00546185,0.07244865,0.04296144,-0.02132728,0.03115276,0.00764921,-0.00827915,0.13147175,-0.02427398,0.00724057,-0.08049201,-0.00403945,-0.07493704,0.07177696,-0.00811672,-0.01030334,0.01230608,0.04806583,0.00436766,-0.02999131,0.04979717,-0.01873044,0.00150318,0.05021554,-0.04269127,-0.03138008,-0.03766567,-0.12187717,-0.00807792,-0.0068933,0.06167653,0.04827446,0.12373176,0.02256062,0.01061521,0.07598659,0.02325877,0.0461762,0.0312835,-0.03933388,0.03289419,0.05493275,-0.03741196,0.05893308,-0.0609238,0.09026635,-0.03150475,-0.00307125,-0.05259492,-0.01436505,-0.07957514,0.00528512,0.07525854,0.0918241,-0.04865336,0.0660063,-0.07420024,0.05426457,0.01446061,-0.00492772,-0.06331617,-0.02599656,-0.0946916,-0.05097332,-0.03753335,-0.03868943,0.01360926,0.02304836,0.00485365,-0.02313919,0.04570369,0.00697349,-0.00616213,-0.05436903,-0.05249691,1.51e-06,0.03944922,-0.08053721,-0.05456588,-0.02625677,-0.01122771,-0.01836898,-0.05974926,-0.03570833,0.02957241,-0.00859481,0.07980656,-0.02509955,0.00235599,-0.008476,0.01218894,-0.12523006,-0.02685068,0.07275893,-0.09372865,-0.05704241,0.06578909,0.02595644,0.03183015,0.04133411,0.04620039,-0.00329251,0.00727297,-0.04599279,-0.049871,0.01413679,-0.03911145,0.03647076,-0.08343665,-0.00410067,-0.00442295,0.08691013,-0.02808816,-0.05130821,-0.00447339,0.03345402,-0.00430036,-0.08642428,-0.04765672,-0.04324004,-0.0171941,-0.05528445,-0.03172568,0.00820539,-0.04904301,0.01059816,0.00893273,-0.0088634,0.00227919,-0.11173846,-0.03357305,0.02458788,-0.00956979,0.02922592,-0.03517228,0.09610644,0.01151698,0.02094733,0.11841239,0.01190974]');
INSERT INTO public.detalles_proyectos VALUES (72, '2026-08-04', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-04 10:11:11.510708', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.01579353,0.04465786,0.03352646,-0.11567121,-0.01309519,-0.06053163,0.11474832,0.10410908,-0.01374324,-0.042597,0.15491638,-0.08181805,0.06859624,0.00897367,0.02420859,-0.00805511,0.01233623,0.05973884,-0.04102081,0.11121397,-0.02387192,0.02530317,0.05362358,0.03158488,0.03457134,-0.03952306,0.05178183,0.00797021,0.05362493,0.00167494,0.01940691,0.03434418,-0.01420134,0.09488771,-0.00398344,-0.03215603,-0.01630002,-0.10308297,-0.1003088,0.06074962,-0.01334507,-0.02624178,0.00042721,0.02147986,0.03356338,0.01271048,-0.03349405,0.03703538,-0.05415158,0.05209226,-0.01673544,0.06198793,0.00512663,0.03209278,-0.09970828,0.05752684,-0.05353411,0.00578711,0.06498613,0.06058567,0.09830229,0.02953038,-0.02216912,0.00264298,0.02845984,0.01939936,-0.02771357,-0.01590537,0.02495666,0.00376801,-0.01952926,-0.01089966,-0.03529574,-0.07650173,-0.04147311,0.02874293,-0.01009259,0.01231727,-0.02570188,-0.00256114,0.05367643,0.08137493,-0.03936405,0.07046036,0.05748314,0.03052727,-0.04513276,0.10856255,-0.00979564,-0.04538367,0.03915317,-0.02190513,-0.05856508,-0.04418888,-0.03298618,0.00152085,-0.02351743,-0.1080439,-0.04640201,0.07510192,-0.04225071,-0.034009,0.04798003,0.03347718,-0.02030617,0.01759272,-0.05924217,0.0358914,0.03201571,0.04588409,-0.03599363,-0.06801273,0.02384354,-0.09283197,-0.08844601,0.10605736,0.0407959,0.03298717,0.04531042,-0.0612447,0.00512669,0.01585404,-0.079197,-0.04403399,-0.04123736,-0.02610734,0.03057488,5.18e-06,-0.06873699,-0.02465713,0.03167292,-0.0015378,0.04016578,-0.02305268,0.04156714,-0.04837308,0.00458712,-0.08045751,-0.04079918,0.06304343,-0.0925318,0.07245643,0.00326106,-0.00843916,-0.00211095,-0.02267149,0.10228925,0.04973644,-0.0183697,-0.04898851,0.05767512,-0.002131,0.02113789,0.06042271,-0.02423988,0.0624947,0.00306795,0.01088363,0.0701461,-0.10295045,0.0150278,-0.05361265,-0.0320155,-0.05133119,0.00136305,-0.04910491,0.02629806,0.00319142,-0.10034826,0.04103493,0.00104618,-0.0028708,-0.05549273,-0.02150804,0.04974902,0.06620735,0.02600616,0.01153369,-0.00988127,-0.05903807,-0.09666336,-0.08669298,-0.02484313,-0.05324197,-0.06334466,0.100867,-0.05352539,-0.00149814,-0.02984346,-0.0208895,0.02500167,-0.00207014,-0.07945187,-0.04300883,0.01312209,-0.06696887,0.0249714,0.00858665,-0.0763782,-0.00876414,-0.02959657,0.00859258,0.01943226,0.01685519,-0.03031013,-0.03559894,0.01927552,-0.00340627,-0.00672747,0.00655919,0.02432416,0.04917182,0.06077465,-0.03816528,-0.00915416,-0.09391207,-0.02183891,0.01311998,-0.07796886,0.02498276,0.01196874,0.03283993,-0.02855976,1.459e-05,-0.09691595,-0.05174874,-0.01169797,0.0275875,-0.01256137,-0.00884777,-0.01773788,-0.0473674,-0.06016439,0.03396235,-0.01379014,0.04146461,0.02344496,-0.02407653,0.03145727,0.10954855,0.00876184,0.02358248,-0.00764383,-0.03905954,-0.02698467,0.08096433,0.06437995,-0.1003353,0.01899804,-0.05676866,0.06232648,0.05909611,0.00760181,0.01433123,-0.05510497,-0.04681304,-0.10961125,0.00128856,0.03247208,-0.04844814,0.02769716,0.0681187,0.01575835,-0.06261618,0.02538359,-0.09540055,0.0192942,-0.03056885,-0.00937032,-0.00075176,-0.08208262,-0.08479399,0.02061775,0.03353512,0.0549126,0.02349605,0.03026982,0.00883646,-0.07927012,0.10416155,0.00284942,-0.09028733,0.0020197,-0.11322893,0.08295002,-0.0631256,-0.02675445,-0.04583072,-0.01609395,0.00456214,-0.04045644,0.02263068,-0.0310702,-0.05957875,0.05502615,0.01071525,0.00118936,-0.01484887,-0.0698391,0.0136396,-0.019318,-0.05020101,0.04368432,-0.0232505,-0.06454477,0.06347843,0.00341506,-0.12791315,0.03836337,-0.0247685,0.02418864,0.08418578,-0.05840331,0.03206473,-0.03463775,0.09778934,-0.05230251,0.05506653,-0.05568709,1.51e-06,-0.01148337,-0.06284371,-0.06227027,-0.08222542,-0.05050667,-0.00275693,-0.03297366,-0.01902849,0.08115814,0.08173367,0.03917403,-0.03966613,-0.06569435,0.07089018,-0.0255321,0.0245732,0.03741994,0.06523617,-0.06934977,-0.05285851,0.09027967,0.05999272,0.03621021,-0.05027738,0.00719511,-0.0330596,-0.01894011,0.02551924,-0.02458963,-0.00068138,0.02772622,-0.00345924,-0.01242037,-0.03471686,-0.05967454,0.06337352,-0.04372736,-0.04582989,0.01436367,0.0136732,0.01241584,-0.08411664,0.05601958,0.03551452,-0.02558826,-0.09211773,0.11081541,-0.01437329,0.055156,0.15522666,0.03721413,-0.06982727,-0.01847071,-0.09911853,-0.00181079,-0.00599966,0.07852701,-0.00586825,-0.00033496,0.00970981,-0.03780909,0.03002211,0.03718845,0.00397841]');
INSERT INTO public.detalles_proyectos VALUES (79, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:45:15.201621', NULL, 'Trayecto IV', NULL, NULL, NULL, '[-0.05925068,-0.03747497,-0.06554396,-0.06347282,0.01856329,0.0415299,0.05377476,0.10685623,0.05460723,0.03196778,0.08279354,0.06442101,0.01426161,0.0007417,-0.13562989,-0.01493109,-0.05580729,0.00164277,-0.02545193,-0.01468712,0.0132404,0.01598016,0.07330317,-0.04391609,-0.01787274,0.02037033,0.03482323,-0.01704263,0.00341584,0.01304632,0.06558904,-0.06145763,0.01316492,0.08380699,-0.01494173,0.04393533,-0.08256437,-0.10029584,0.02182061,0.07503076,0.06523211,-0.00610733,0.03320326,0.08640354,0.08585673,0.06037054,-0.02271806,-0.01604923,-0.04371217,0.01633242,0.02850392,0.00413208,0.03778185,-0.01648953,-0.0161786,0.10706628,-0.07986466,-0.04913699,-0.07885514,0.02256624,0.03681554,0.04616365,-0.0425443,-0.05501776,0.01100808,0.11584439,-0.0081685,-0.05767765,-0.0091198,-0.05316713,0.02103463,0.05223362,-0.00291947,-0.12071764,-0.00130953,0.03160652,-0.02734314,0.04127144,0.01108991,-0.12584205,0.10424985,0.07652345,0.04712555,0.00176827,0.11890375,-0.02383992,-0.01895286,0.06245206,-0.00729361,0.00917773,0.04305568,-0.06614543,0.00908485,0.02611431,-0.04885596,-0.02181673,-0.00105996,-0.03111089,-0.057998,0.01686539,-0.00727856,-0.00139625,0.03950926,-0.05493696,-0.06238852,-0.05383612,0.00963787,0.0214844,0.03785872,0.00315767,-0.08622454,0.00416399,-0.03752857,-0.07686241,-0.00908461,0.0661759,-0.02461115,0.01082825,-0.02248694,-0.07010105,-0.05430985,-0.00608437,-0.13605554,0.0112975,-0.02149057,-0.08430942,-0.05646141,3.21e-06,-0.07987106,-0.01602008,-0.00933469,-0.00408661,-0.02661944,0.01917713,0.00905931,-0.12045219,0.05354382,-0.05263243,-0.04802468,0.08581738,-0.09023449,0.05450471,-0.06882542,-0.04295062,-0.01837071,-0.00357691,0.05667996,-0.01180245,0.04246951,-0.0507843,0.05409719,-0.00390888,0.07288364,0.0087514,-0.06223964,-0.00147278,-0.03561648,-0.00920601,-0.01965639,-0.01123199,-0.01697859,-0.08213739,-0.03854224,-0.04664787,-0.00275579,-0.00404482,-0.00727182,0.01521499,-0.01659469,0.04641366,0.02330178,-0.0487258,0.04125558,-0.00652024,-0.00590073,0.06888349,0.04427981,-0.02817638,0.00508188,0.01732542,-0.0125411,-0.00736206,0.07285189,-0.05632782,-0.06329135,0.07969406,-0.00122718,-0.08634257,0.10870047,0.01329681,-0.05255424,-0.00413914,-0.01895038,0.04435976,0.01566249,-0.01518438,0.09163385,-0.06740361,-0.00181399,0.00769066,-0.06145932,0.012579,-0.05515216,1.691e-05,-0.02480184,-0.00196143,-0.00609901,-0.034685,-0.08281297,-0.03834116,0.0072031,0.0100735,0.04163548,0.01352952,0.07415431,0.04811691,-0.04825307,-0.13594279,0.053152,0.02716416,0.02636822,-0.02861826,0.03203705,9.79e-06,-0.02460734,-0.06887246,-0.09604268,-0.02112676,-0.03868301,0.07451401,0.05378975,0.01698826,-0.01614856,0.01324593,-0.00913136,0.03950854,-0.00121857,-0.11889489,0.07318349,-0.02479184,-0.08955573,0.14931805,0.06334787,0.02727128,-0.02892712,0.06855976,0.052133,0.02930059,0.03624786,0.02327998,-0.0090085,0.0854945,-0.09212457,-0.00627728,-0.06393677,-0.02988164,-0.0665653,0.00189788,-0.06680981,-0.08173897,0.13036403,0.04231716,-0.09248656,-0.01629011,0.09620461,0.02152195,0.00771597,-0.023515,-0.02283161,0.00957707,-0.11102535,-0.0642013,-0.03442768,-0.01683184,0.00518137,0.0158,0.03648627,-0.06483453,-0.00697529,0.10704095,-0.01780985,-0.01667721,0.0011673,-0.02532093,-0.03949369,-0.01413426,-0.08802,0.04969863,0.0361064,-0.02721713,-0.01111619,0.08395679,0.04666321,-0.0330354,0.0629675,0.10614736,0.03325658,0.00139505,0.00617149,0.00879519,0.03306021,0.06298578,0.0023987,-0.00572087,-0.01463203,-0.00524449,-0.02678896,0.01367217,0.02317833,0.04104972,0.07347298,-0.05941938,-0.00329941,-0.08513018,0.01286764,0.04468953,0.0194444,0.00305922,-0.02291581,1.11e-06,-0.01570775,0.03718476,-0.05531328,-0.1150342,-0.00422939,-0.0473276,-0.02332504,0.00737749,-0.05463131,-0.01339876,0.01847537,-0.06700005,-0.01225674,-0.00934864,-0.03893824,0.00673474,0.01025172,0.08636674,-0.01835875,-0.07791511,0.04298008,0.01676851,-0.00817484,0.03374759,0.0118809,-0.00127945,-0.04114699,-0.04888707,-0.04104183,0.03008015,-0.00319994,-0.00567964,0.09543272,0.05643447,0.04699715,0.0655061,0.10699537,-0.02390887,-0.06485443,0.00253741,-0.05319513,-0.05711511,0.04206506,-0.003752,0.03024953,-0.06744282,0.08687716,-0.0408002,0.09775817,0.03818335,0.06504231,0.05108979,-0.04361028,-0.02236277,-0.0624283,-0.00970754,0.01476808,0.02779144,-0.06446841,-0.01884621,0.02751798,-0.04763774,-0.03617489,0.04219326]');
INSERT INTO public.detalles_proyectos VALUES (80, '2026-08-05', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones', 1, 'Centro Clínico “María Edelmira Araujo”, S', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-05 09:48:05.633265', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.03482301,-0.0511314,-0.03498223,-0.00384492,-0.03424594,-0.03291514,-0.04187362,-0.03011711,0.03822904,0.01337112,-0.00225304,0.02821508,0.04532943,-0.09136165,-0.06034093,-0.02703596,-0.0311053,-0.00747885,-0.00853233,0.07994567,0.04153795,-0.0768302,-0.04291943,0.06224649,-0.02817489,0.06435907,-0.0200178,0.03542714,-0.05799962,-0.00165773,-0.04442082,0.10314108,0.10520855,0.01501195,-0.01498969,0.04526936,0.04178364,-0.01537622,-0.02921864,0.03974377,-0.06168643,-0.0415804,0.05087307,-0.0336436,-0.10590254,-0.03655488,-0.0322046,-0.04663027,-0.04522838,-0.0691078,-0.09668752,-0.02809252,-0.00742435,-0.00448068,-0.03755506,0.0253594,0.0739074,-0.03434919,0.04635023,-0.02079227,0.06102284,0.01243913,0.04486309,0.05319423,0.02461842,0.00294235,0.03718556,0.0177744,0.05747779,-0.04760209,-0.01358196,0.00358279,0.00589012,0.1094748,0.04272691,-0.01126134,-0.06831491,-0.03320648,0.11177598,-0.0906062,0.02772985,0.02279229,-0.0629608,0.05749147,-0.02897948,0.06742685,-0.04108953,-0.01776725,0.16840035,-0.03226426,0.09100827,0.06416997,-0.03585674,-0.0495359,0.04485235,-0.02211359,0.08720332,-0.08323255,-0.08341507,0.00040901,0.02007384,0.01085685,-0.00624897,-0.0811171,-0.00239767,0.02036599,0.01862106,0.01421817,-0.0393339,-0.05382257,-0.04665208,-0.06694791,-0.06117899,-0.07032039,-0.06049241,-0.00067559,-0.00123199,0.02014647,0.03027877,-0.05326552,-0.05229671,-0.04825779,-0.08083933,0.01790298,0.06469325,0.00251431,-0.00937109,4.62e-06,0.00043571,0.0044539,-0.05765608,0.05630637,-0.03808755,0.00507626,0.0024398,0.0630673,-0.03350486,-0.06237769,0.03133817,0.02953604,0.02931996,0.0555163,-0.01954977,0.02462066,-0.04674416,-0.03554251,0.02457599,0.0090768,-0.03480305,0.03283396,0.02737442,-0.0667497,-0.06452957,0.04998524,-0.02599114,0.01654753,0.07542795,0.03738379,0.02766987,-0.03523752,-0.03392658,-0.0356685,0.04981261,0.09128091,0.01716657,0.02324847,0.01865979,0.06703848,0.06966471,0.0099609,-0.06913491,0.00203813,0.01861503,0.05401661,0.00993881,0.04493053,0.10688815,-0.09965774,-0.03905318,0.0333254,-0.1071829,-0.02486773,-0.00439451,-0.04580421,0.02055266,0.07210017,0.06717705,-0.04177319,-0.01079576,0.04929414,0.04976081,0.07566846,0.0440418,-0.07275257,-0.04569179,-0.05018853,0.15475522,-0.05852753,-0.02034512,-0.02341164,0.01498351,0.09105473,-0.03763611,0.01525999,-0.08130865,-0.02484817,-0.01378567,0.05689594,-0.05047542,0.00562926,-0.0150456,0.0089911,0.0901719,-0.01648982,-0.00811981,0.04132034,-0.1127393,0.03896141,-0.02756134,-0.12881674,0.09177485,0.00841433,0.0085643,1.409e-05,-0.04409434,6.715e-05,-0.0344637,0.01715265,-0.00421194,0.01851343,-0.05017617,-0.06400782,-0.0260811,0.01548192,0.06575451,-0.05209904,0.04899992,-0.02891517,0.06348136,0.0203562,0.05647751,-0.06712145,-0.01778444,-0.01871222,-0.00168901,-0.1098438,0.05167499,0.05092775,-0.0479,0.0066939,-0.00127241,-0.00137968,-0.06285006,0.074099,0.0798527,-0.00183937,-0.06289573,0.14433235,-0.03189024,-0.01780788,-0.10233796,0.02016091,0.04167868,0.04473984,0.02022047,-0.06467246,-0.0192108,-0.01846275,-0.01405093,-0.04286575,-0.05632451,-0.06063124,-0.05067729,-0.03126853,0.05702306,-0.00058726,-0.07967332,-0.14213546,0.0716278,0.1329031,-0.00435534,0.00964112,-0.06343959,-0.03077804,0.05378655,0.04181438,-0.04525526,0.01746109,-0.02597098,0.15522046,0.01172044,0.0688116,-0.03573536,-0.01484789,0.05925948,-0.00485196,-0.03716063,-0.05651669,-0.05550987,0.00622865,-0.02221347,0.01792098,-0.04650643,0.04938815,-0.03274429,-0.04267117,0.01381379,0.02088431,-0.07096986,0.02754281,-0.02304073,-0.00671618,0.03152474,0.02149754,0.03108594,-0.03094565,-0.06711411,-0.09433289,0.05595965,1.45e-06,-0.00417485,-0.05801838,0.02078312,-0.00955225,0.00431516,-0.0671179,-0.00503071,-0.00539272,0.01360429,0.06021507,-0.01387762,-0.10674187,-0.00986973,0.04358294,0.062141,0.0068512,-0.02260933,0.06104902,-0.06185856,-0.08982762,0.08704805,-0.07194207,0.0174327,-0.06123784,-0.05399646,0.0020026,-0.01274851,-0.05434861,-0.0163685,0.00365701,0.03372454,-0.03866974,-0.09695276,-0.00120654,0.07285379,-0.00144638,0.01646104,-0.03011583,0.01754421,0.04614457,0.05252156,-0.0087263,-0.03609742,0.01725893,0.05090455,-0.04381254,-0.01594426,0.05420165,0.05492438,0.05062452,-0.04297585,0.0211578,-0.02919022,0.01986195,-0.00203078,-0.01577579,-0.0374176,0.0361808,-0.06827574,-0.00955827,-0.07697725,0.06174915,0.0258108,-0.04730456]');
INSERT INTO public.detalles_proyectos VALUES (86, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:02:04.774776', NULL, 'Trayecto III', NULL, NULL, NULL, '[-0.08972564,-0.00809067,-0.06632644,-0.05000142,-0.02766256,0.10520017,0.02159543,0.09694924,-0.0793738,0.03287699,0.05939716,0.01697177,0.00743525,-0.04405242,-0.00201843,0.04238997,-0.03657338,0.02471142,0.03530686,-0.01588659,0.00040909,-0.01332612,-0.03533704,-0.02308304,0.02459821,0.08562383,0.03765567,0.00731849,-0.00939131,-0.01664591,0.02545461,-0.00322811,-0.01110794,-0.01247817,0.12323844,0.01995215,-0.09751234,0.01876388,0.00044533,-0.00908944,0.08261738,-0.06644396,-0.04583753,-0.06351756,0.0003304,-0.01334921,-0.07486191,0.04692583,0.02234543,-0.10301937,0.00322512,-0.01944009,0.03440156,0.0378368,-0.01407335,0.02762847,0.0188922,-0.00196666,0.05342009,-0.0411009,-0.03299357,0.01671233,-0.08983276,-0.00452087,0.02567575,0.03815246,0.01273739,-0.00747426,0.01218397,0.0104159,-0.0724723,0.13344952,0.01363369,0.07551152,0.02411549,0.00942526,-0.03164697,-0.06408444,-0.05275206,-0.05161709,-0.06540484,-0.10100406,-0.06973668,0.05492359,-0.02386937,-0.00784847,0.02013564,0.02062658,0.07019989,-0.0004604,-0.01699537,0.02693192,-0.00844856,0.03219763,-0.13729014,0.0286705,-0.09800595,0.04371878,0.04615366,-0.03796824,0.02309038,0.00953416,0.04716384,-0.01642287,-0.0627366,-0.01487461,-0.02824385,-0.02732618,0.07292934,-0.00105834,-0.0542161,-0.0389377,-0.02438446,0.01935522,0.04500222,0.00233528,-0.05058748,-0.02209699,-0.03995481,-0.13250522,0.02475278,-0.02110498,-0.03359262,-0.04463717,-0.01964879,-0.05479037,-0.00560094,3.46e-06,-0.00066993,0.00175476,0.09848155,0.00419197,0.03090597,-0.02168842,-0.06376003,0.01036485,0.01127756,0.00735374,-0.06906377,0.01355061,-0.07329686,0.00672976,-0.13673905,-0.00746953,0.08506612,0.11740427,-0.03905132,0.03208461,0.03688488,0.10710097,0.02882028,0.04104675,0.00369171,0.07868965,0.03676082,-0.03924188,0.06480454,0.04667735,0.02588234,0.0665477,-0.06618704,0.06927636,-0.03093467,0.03664617,0.0287487,-0.02643706,-0.01126545,-0.00365565,0.05471635,-0.00297393,0.01985866,0.00234916,0.08689629,-0.13476245,0.12307581,0.08363388,0.0125122,0.13824826,-0.04640855,0.0209815,-0.04777532,-0.02392224,-0.07153916,-0.01425589,-0.03708354,0.04424677,0.03751319,0.03560282,0.07589635,0.10572758,-0.00298702,-0.00609497,0.06363705,0.00198013,0.00841818,0.0302669,0.07078325,0.0237099,-0.01305291,0.00198994,-0.00223978,-1.119e-05,0.05109998,-0.06337021,0.03018249,0.0725753,-0.04572841,0.01040798,-0.11996947,-0.03716417,-0.09615354,-0.11912765,0.05163074,0.01690974,-0.02083739,0.00548495,-0.05405675,-0.02810875,0.08001354,-0.03899746,-0.02343842,0.12078997,0.01047642,9.09e-06,0.04518791,-0.02864464,0.00948171,0.03092561,-0.00581331,-0.05978434,-0.05442546,0.07591895,-0.08736005,-0.05845995,-0.0061002,-0.02267347,0.05338964,-0.04824896,-0.04656299,0.00299622,0.02390773,0.02646127,-0.06368469,-0.02207201,-0.03628101,0.05840434,0.01225404,0.06492192,-0.02108083,0.00021635,0.03565078,-0.06435486,0.0408418,0.03249337,0.01511821,0.05306921,-0.08980318,0.02243824,-0.0697619,-0.05294245,0.03828083,-0.02764715,0.09392301,0.02200335,0.0016808,-0.0111168,-0.08087945,0.00244507,-0.03250311,0.01448973,0.02654977,-0.03405601,0.07223465,-0.00200889,0.04560343,0.01151323,-0.00258077,-0.04515783,0.01741342,-0.00976078,-0.02480557,0.06960864,-0.0559402,-0.03114572,-0.00801682,0.01800664,0.05417894,0.01527728,0.01939043,-0.01726912,0.07449394,0.04980648,0.03881238,0.01182048,0.02776708,0.11681973,-0.06692658,-0.0093073,-0.06829635,-0.00544516,-0.05530999,0.026911,0.03477559,0.08545672,-0.0211224,-0.0304725,-0.05104256,-0.03431508,-0.08160605,-0.01970751,-0.00734512,0.00437716,0.00652536,0.01999889,-0.04439149,0.07737308,0.08758708,0.09523097,-0.06202853,8.6e-07,0.03412768,-0.02076793,-0.05384737,0.06689433,0.06987296,-0.06145987,-0.11194401,0.05193745,-0.05219453,-0.0585833,-0.00514261,-0.03175913,-0.0327675,0.00360134,-0.00621913,-0.04372712,0.00154368,0.01316879,-0.03128285,-0.00980024,0.00643964,-0.04529918,0.0101438,0.06035896,0.03876419,-0.00742018,0.00878254,-0.05064031,-0.00853073,0.01147376,0.01510616,-0.04042443,-0.08816637,-0.09076849,0.09424254,0.07712404,0.1053292,-0.03351803,-0.01481227,-0.08158125,0.04802605,-0.02627744,-0.03892739,0.03219511,0.01280322,-0.04830748,-0.04686357,-0.05077801,-0.06362737,-0.06901114,-0.07195583,0.02160839,-0.03369071,-0.01871385,-0.01095385,0.12902369,0.01252943,-0.01620429,-0.00746995,0.07686839,0.02346782,0.02495568,-0.00184294,0.0763646]');
INSERT INTO public.detalles_proyectos VALUES (88, '2026-08-10', 'Pregrado', 'Según Arboleda (2014), un proyecto representa un esfuerzo temporal diseñado para producir un resultado o entregable único de forma gradual. Para enriquecer la fundamentación, Project Management Institute (2021), lo define como un esfuerzo temporal emprendido para crear un producto, servicio o resultado único.', 1, 'Corporación Eléctrica Nacional (CORPOELEC) de Venezuela', '', '2026-08-10 10:26:58.264555', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.080141,-0.0683165,-0.01854285,-0.05302329,-0.01786695,-0.01539911,-0.0462116,0.0194459,0.02637057,-0.01978112,0.02214071,-0.0417643,-0.02900771,0.03947124,-0.05585892,-0.06991609,-0.00581925,-0.06883421,0.07312763,-0.01379403,0.07921884,-0.01359965,0.0276064,0.06933734,0.00303761,-0.01970837,0.02658223,0.03919003,-0.02234482,-0.08965625,-0.02201887,0.05714051,0.00469598,-0.01765604,0.05167473,0.10757097,0.04110741,-0.01863434,0.05319605,-0.06196611,-0.03819756,-0.09283488,0.0415383,-0.00778748,-0.00875549,-0.00935239,0.07767519,-0.07857232,-0.04835861,0.04980595,-0.04286483,-0.02080213,0.01397029,-0.05023236,-0.02289819,-0.02386251,0.0470427,0.0546035,-0.02235527,-0.00937361,0.00941304,0.00260708,0.04858603,0.005255,0.01262571,0.02157998,-0.04775022,-0.06569685,0.00903299,0.00348025,0.00823548,-0.01422763,-0.00206176,-0.03001744,-0.0782671,0.07984209,0.02103861,-0.00083608,0.05411389,-0.06606598,0.10229242,-0.00775931,-0.03835021,-0.03082268,-0.03175199,0.0597262,-0.05256493,0.03454837,0.13431421,0.00034502,0.05126821,0.01881639,0.10280827,0.02065762,-0.01830672,-0.0630204,0.02837587,0.05200816,0.01371232,0.03460377,-0.07553075,-0.04352773,0.11031889,-0.00627494,-0.02502421,0.00890124,-0.00323202,-0.02191819,0.04679466,-0.03064141,-0.07701923,-0.05148061,-0.04056095,-0.04469239,-0.04403478,-0.01997515,-0.06348997,-0.02472166,0.0559059,-0.01987396,0.05048871,-0.03219143,-0.00561482,-0.04819881,-0.03072125,-0.0050963,0.09965917,5.05e-06,0.00831665,-0.01001239,-0.10195228,0.06258289,0.00613073,0.04940367,0.09760453,0.00836363,-0.03687171,-0.11483334,-0.08572338,0.09187834,0.01159475,0.06115351,-0.03997345,-0.06703018,0.05044014,0.00377996,0.09791971,-0.01640359,0.03676022,-0.03946635,9.792e-05,-0.09226835,0.00500103,-0.07609278,-0.0580967,0.07242132,-0.07224403,0.01432801,0.01457523,0.0762625,-0.07080159,-0.03538204,0.0732365,0.00659834,0.00413049,-0.00508828,0.0882469,0.01725101,0.00700727,0.00855431,-0.05523375,0.01844563,0.00583449,-0.07917693,-0.02875593,0.08160708,0.03403696,-0.05716455,0.01518742,0.0586384,-0.01324373,-0.07545541,0.08313053,-0.03391234,0.02600187,0.0196913,0.02272616,0.02459955,0.03091391,0.03981576,-0.04703042,0.04671266,-0.05028165,0.00471657,0.02064944,0.00363712,0.11605038,-0.02726134,0.03058576,-0.05436018,0.04799981,0.0129206,0.04025979,0.01079054,-0.04471095,-0.10434206,-0.00495342,0.03454556,-0.05122121,0.00448912,-0.08011718,-0.0005618,0.09316109,0.04656583,-0.01311033,0.09959503,-0.00031723,0.01410384,-0.01187969,-0.11193881,0.00865822,0.0329475,0.06309728,1.416e-05,0.03656195,0.03165714,-0.0232534,0.01635702,-0.00067884,-0.02741636,-0.00198364,-0.01147598,-0.03406052,0.10200512,0.06369836,-0.11805324,0.04754706,-0.05012312,-0.01197966,0.04154845,-0.00866006,-0.06277228,0.01122153,-0.01776155,0.02932082,-0.03353081,-0.08015334,-0.03635986,-0.06825562,0.01190407,-0.02294367,-0.00508466,-0.08157582,0.00575233,-0.1026207,-0.07935672,-0.08528993,0.02818979,-0.03585142,-0.01877682,-0.03851196,-0.07528747,0.06905087,-0.11258101,0.06775107,0.0216488,-0.01826386,-0.00890435,-0.03457304,-0.02056708,-0.09241038,-0.10973421,0.04477066,0.0227102,0.03279337,0.04571208,-0.03108869,-0.15029943,-0.04462439,0.05100433,0.0136797,-0.02120559,-0.11194543,0.07196873,0.05561045,-0.00932208,0.05640495,0.03609798,0.09955923,0.04541793,-0.00293961,0.07600198,0.05981797,-0.04010193,0.1008419,0.02929054,-0.05921258,0.0309699,-0.03602019,-0.02778313,-0.05978026,0.05530305,0.01950655,-0.02172552,0.02913293,-0.00190368,-0.00116808,-0.02244703,-0.04962871,0.01249756,0.02250288,-0.04933108,0.01865567,-0.03458812,0.05740495,-0.08603103,0.00971589,0.07934152,0.08171629,1.41e-06,-0.02794452,0.0256227,0.03868484,-0.06761434,0.03451005,-0.02363888,0.00300835,-0.02802155,0.12391521,-0.06755763,0.03540262,-0.05683282,0.05323571,0.08499092,0.01624339,0.02826363,0.01664756,0.05318937,-0.02260206,-0.07221713,0.08991009,0.00867682,-0.00213672,-0.06396139,-0.06222869,-0.00467297,-0.08629328,0.09523268,-0.03140368,0.01216841,-0.03078628,-0.04144057,-0.02545371,-0.0099692,0.00025169,-0.01972929,0.008905,-0.04128058,-0.0947243,0.06606836,-0.01197949,-0.0168827,-0.10326957,0.02865738,0.00710541,-0.04400584,-0.08704796,0.0144795,-0.08782343,0.03383146,0.00159973,0.01717267,-0.04654356,0.00652532,0.00890577,-0.01833072,-0.01297171,-0.00102755,-0.07352517,0.00629488,-0.00665146,9.889e-05,0.0459819,-0.06270299]');
INSERT INTO public.detalles_proyectos VALUES (91, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 10:52:47.26864', NULL, 'Trayecto I', NULL, NULL, NULL, '[0.00704848,-0.07388677,-0.03175919,-0.06412091,-0.00831828,0.03351708,0.00731937,0.06535555,-0.02680218,-0.02680881,0.02867808,0.06059333,-0.03607324,-0.00120224,-0.03154363,0.01106879,-0.10992668,0.02632163,-0.02430117,-0.01790228,-0.06044862,-0.02348564,0.00044374,0.03245523,-0.04227113,0.02026809,0.14549062,0.04131805,-0.01847738,-0.03978049,0.06425111,-0.04293453,-0.04306741,0.0002014,0.02723608,-0.01399413,-0.08092821,-0.15157273,0.01823536,0.09802646,0.03025628,0.03198235,0.01416462,0.03870103,0.04981496,0.01834067,-0.03573513,-0.00715878,-0.02580119,-0.05378596,0.05158487,0.01805158,0.01327832,0.0084325,0.06135143,0.12751319,-0.01648451,-0.05313154,-0.01154259,0.02434476,0.0334824,0.0381216,0.03473414,-0.05432536,-0.00865425,0.07534471,-0.00517379,-0.08642451,-0.037622,-0.00944025,-0.02912942,0.02869378,-0.04743028,-0.12283435,-0.03691744,0.0342366,-0.00123979,0.03199064,-0.06421524,-0.094056,0.03072274,0.04528043,0.03131379,-0.00738633,0.10143681,-0.0228208,-0.07143939,0.02585597,-0.02187808,0.12678058,0.05992252,-0.05501804,-0.03990382,-0.02282786,-0.04788456,-0.01310362,0.05287201,-0.09566217,-0.03666618,0.05432482,-0.03585912,-0.026209,0.02064293,-0.03040762,-0.01541023,0.05730164,0.01204079,0.01513927,0.08020733,0.04711971,-0.05849463,-0.03157061,0.0272002,-0.06999107,-0.08837182,0.07445132,-0.00918923,0.03976606,-0.02449744,-0.08563031,0.03890729,-0.00960648,-0.09762852,0.0196781,-0.05035389,-0.03000776,-0.09552202,4.5e-06,0.02631255,-0.02481081,0.01775428,-0.02328315,0.00658893,-0.04122069,0.00838591,-0.05637441,-0.00146167,-0.06163404,-0.02042669,0.05231095,-0.07143861,-0.00436202,-0.06518358,0.01224931,-0.02393935,-0.00556463,-0.03056295,0.07603483,0.02596941,-0.0082614,0.01656766,0.00103978,-0.05265544,0.0122692,-0.07310472,0.04774953,-0.01245089,3.768e-05,-0.02158115,-0.04399304,-0.04071842,0.00702933,0.02272826,-0.09312386,0.03796451,-0.0143549,-0.03125641,0.02095727,0.00764519,0.04317108,-0.03034829,-0.01461328,0.0248662,-0.05009526,0.01944115,-0.01933045,0.04486153,0.02430672,-0.04331471,-0.00049009,0.02161365,0.00245727,0.06356151,-0.0617564,-0.02891097,0.08133431,0.01488997,-0.05557424,0.13993558,0.01346334,0.04013306,-0.04476453,0.03556436,0.06389831,-0.0120226,-0.03072689,0.08505343,-0.11786284,0.00559675,0.0427543,0.05319476,0.01356716,-0.02918807,-0.02612287,-0.04852372,0.04083642,0.00976361,-0.04524306,-0.04173032,-0.01574707,-0.00591217,-0.00593529,0.05968973,-0.00039508,0.01681121,0.02259502,-0.0454892,-0.09658053,-0.05566569,-0.05098482,0.09094739,-0.05895771,0.01103408,1.204e-05,-0.05480294,-0.02808239,-0.0500424,-0.00199687,-0.08968169,0.04046118,0.05010008,-0.01911198,-0.01914655,0.00782113,-0.0404115,0.07381148,-0.02845948,-0.12181653,0.14611857,0.01203081,-0.02430128,0.1461244,-0.01318369,-0.0471292,0.00239302,0.05176789,-0.0326903,-0.01414177,-0.02678502,0.05585673,0.05050734,0.03920572,-0.07574567,-0.0024163,-0.06576176,0.05624892,-0.05296856,0.0256266,-0.06783999,-0.0090224,0.02691959,0.00790968,-0.09173106,-0.01126697,0.02090958,0.0039164,0.02803272,-0.01690959,-0.01048668,0.00885094,-0.10901464,-0.00840821,0.03471697,0.06723637,0.02521741,0.0868129,-0.03576585,-0.0288746,-0.03929444,0.0567926,0.02857541,0.05201043,0.04410289,0.03732521,-0.01658111,0.00495538,-0.04369975,0.04366153,-0.0098279,0.04467119,-0.04845071,0.06708519,0.11710629,-0.03047902,0.01744146,0.15563542,0.10952177,0.07089915,0.03339267,-0.03135529,-0.00300963,0.05684658,0.00414576,0.05693864,-0.03406142,-0.05750549,-0.03274183,0.03695733,0.03577591,-0.02491343,0.0327402,-0.05250808,-0.02886251,-0.15949129,0.0745935,0.08610367,-0.00293942,-0.03480925,0.01292085,1.2e-06,0.05060871,-0.08482583,-0.08792517,-0.05050593,0.0306351,-0.01680542,-0.03798988,-0.04409921,-0.08225097,0.00121433,0.02364616,-0.0124144,0.01791167,-0.0671896,-0.04238746,0.04181397,0.01166119,0.05532061,-0.04691458,-0.02601737,0.06038456,0.05855555,-0.05152419,-0.00655075,0.023568,-0.05480346,-0.05912595,0.0533194,-0.04032341,0.01546004,0.05241897,0.00665101,-0.02688333,0.0372066,0.07807657,0.0204538,0.05112781,0.01769724,-0.04983241,0.00895855,-0.11113938,-0.10726333,0.01712207,-0.01323042,-0.0273071,0.00737112,0.00110808,0.05517211,0.07094399,0.06484582,0.01094765,0.0820941,-0.08854955,-0.01157683,-0.04564738,0.03280759,0.05805844,0.00535684,-0.09600183,0.00437853,0.04892867,0.00868486,0.03604827,0.01429396]');
INSERT INTO public.detalles_proyectos VALUES (94, '2026-08-10', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-10 11:40:58.141559', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.02606142,0.04436913,0.03632917,-0.11434388,-0.01975273,-0.05970285,0.1015881,0.10691847,-0.0226114,-0.04457523,0.15445922,-0.08192832,0.07467997,0.00505414,0.01739782,-0.0111194,0.01158341,0.0599064,-0.02570802,0.10860453,-0.01459593,0.05326564,0.0588037,0.02889751,0.04188145,-0.02621913,0.05595219,0.01103711,0.05715278,0.01283715,0.02133916,0.03904049,-0.01635595,0.08673677,-0.00684565,-0.04766039,-0.01597855,-0.10871926,-0.09453567,0.05156602,-0.0048822,-0.01589813,0.0112111,0.02552107,0.04605501,0.01671171,-0.0330947,0.02973248,-0.06896771,0.04780696,-0.00382198,0.06402951,0.0144658,0.02954264,-0.10614342,0.05730709,-0.05350762,0.01639643,0.06925557,0.06638325,0.10930751,0.02250131,-0.02194205,-0.0003719,0.03810733,0.01052427,-0.01736396,-0.02214576,0.03399739,-0.00186254,-0.02408553,0.00204101,-0.01829026,-0.08321462,-0.0406422,0.03374569,-0.0011668,0.00755914,-0.0113845,-0.01170605,0.05636491,0.06696786,-0.02773167,0.07451005,0.06365984,0.03135471,-0.0421639,0.11755069,-0.00980587,-0.05190453,0.04057334,-0.03645647,-0.05906129,-0.03013867,-0.04990513,0.00597643,-0.01776543,-0.1025547,-0.04280839,0.07148691,-0.02916058,-0.02086962,0.04790564,0.03320464,-0.02701027,0.01565595,-0.06214728,0.04154985,0.02750275,0.04347837,-0.02468581,-0.06315991,0.01718756,-0.08211182,-0.08177458,0.10675746,0.03556893,0.03475533,0.04811962,-0.07321839,2.38e-05,0.02387371,-0.10570412,-0.05189178,-0.04758275,-0.01203972,0.02400566,5.04e-06,-0.06500243,-0.0206394,0.0378567,0.0048328,0.03405229,-0.01092861,0.02491597,-0.04515068,0.00645624,-0.06858093,-0.04693027,0.04956112,-0.09516573,0.07776309,-0.01627788,-0.01042818,0.0057077,-0.02334326,0.10263507,0.04387146,-0.01179657,-0.04679438,0.05828138,-0.00912962,0.03571707,0.07409323,-0.00543756,0.06029021,0.01449444,0.01267835,0.07883376,-0.1039912,0.01158014,-0.05443348,-0.02901855,-0.07060335,-0.00385693,-0.04709308,0.02621848,0.00786485,-0.08635145,0.05341606,0.00533451,-0.0043169,-0.04340508,-0.03501757,0.04767315,0.08201828,0.04420704,0.02665207,-0.0143979,-0.06278919,-0.11265328,-0.08337661,-0.03110124,-0.07324839,-0.06703967,0.09741116,-0.05441367,0.00473932,-0.01737061,-0.0074615,0.02567067,-0.00486057,-0.08306506,-0.03805184,0.0112794,-0.06550261,0.01310061,0.02034027,-0.07217681,-0.01590734,-0.02276662,-0.00048732,0.02231807,-0.00036493,-0.0150665,-0.02904143,0.02682326,-0.01162386,-0.01864406,0.00382381,0.02636137,0.0475121,0.04246703,-0.04629964,-0.02618227,-0.09163097,-0.0149878,0.02495244,-0.06528985,0.02614968,0.02819014,0.04206537,-0.04383223,1.413e-05,-0.08874632,-0.04352756,-0.01220682,0.00503355,-0.02195572,-0.02007131,-0.00749023,-0.03984262,-0.04877906,0.01354624,0.00885537,0.05234919,0.04122285,-0.02502278,0.04208967,0.09644272,0.01812603,0.03732991,-0.00578063,-0.04122841,-0.02633479,0.08747508,0.06850825,-0.09724434,0.01119016,-0.0557525,0.07692379,0.06216836,0.01765822,0.02195546,-0.06348158,-0.05081296,-0.11833972,0.0075491,0.02349598,-0.06243889,0.03474598,0.06124101,0.01932705,-0.06911165,0.03055917,-0.07446674,0.00775031,-0.01878482,-0.0084874,-0.00139426,-0.05384787,-0.08498585,0.0201855,0.02734875,0.04838144,0.00546818,0.03647197,0.02398966,-0.07291672,0.0953717,-0.00122539,-0.09518523,-0.00232083,-0.11322162,0.08522347,-0.05235622,-0.02956514,-0.04800157,-0.01283273,-0.00166526,-0.05033571,0.01761352,-0.02118809,-0.06071097,0.054547,0.01327285,-0.00853527,-0.01259131,-0.0730795,-0.00295171,-0.02542885,-0.05552161,0.04696596,-0.03400264,-0.06054677,0.06752031,0.00148841,-0.11751576,0.05108398,-0.02707667,0.01262326,0.0940926,-0.05026037,0.02420399,-0.04536247,0.10141546,-0.06032107,0.05501457,-0.04112169,1.48e-06,-0.0103123,-0.056671,-0.0681016,-0.07809074,-0.05008784,0.00020575,-0.03743884,-0.01671157,0.07219689,0.07118116,0.02817899,-0.03052307,-0.06007448,0.05605275,-0.03151449,0.01833515,0.02499207,0.06438342,-0.07650684,-0.05839177,0.08593252,0.04529271,0.04954768,-0.04953364,-0.00197327,-0.02861966,-0.02982322,0.019545,-0.01660394,-0.02042989,0.01864636,-0.01544883,-0.01514441,-0.03748081,-0.0524113,0.06104974,-0.0296836,-0.04067749,0.01759758,0.00465435,0.01083331,-0.09126225,0.05184699,0.04165542,-0.0174343,-0.10636727,0.11288565,-0.01948494,0.04573738,0.1549597,0.03377307,-0.07277429,-0.02636825,-0.09957907,-0.00781643,-0.01063471,0.05463529,-0.01155794,-0.00646938,0.02226298,-0.02245177,0.0221647,0.01725285,-0.00282685]');
INSERT INTO public.detalles_proyectos VALUES (99, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:10:58.390178', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.07901982,-0.04579591,-0.06399581,-0.05774772,0.0224085,0.04861985,0.06251025,0.09964506,0.05477792,0.02978246,0.07473495,0.06330905,0.01757372,-0.00720382,-0.14147106,-0.00308577,-0.05109709,0.00029882,-0.01715576,-0.02854049,0.00523249,0.02241797,0.06237772,-0.03177307,-0.02317453,0.01715774,0.01943105,-0.00697199,-0.00431711,0.0121039,0.07485683,-0.04442432,0.00032205,0.07416054,-0.02419275,0.04115069,-0.09144541,-0.11339279,0.02285596,0.06055755,0.07852939,-0.00841473,0.02230949,0.09327897,0.09707719,0.05913023,-0.02576855,-0.03690565,-0.0548556,0.01534155,0.0309227,0.01909945,0.04141925,-0.01695623,-0.01309033,0.10197164,-0.08156867,-0.03242518,-0.07387636,0.02814411,0.04503785,0.02445469,-0.04517934,-0.05390048,0.00680905,0.11386051,0.00024738,-0.06660341,0.00131167,-0.051495,-0.00157476,0.0692279,0.00481669,-0.11247997,0.02018938,0.03269322,-0.01618766,0.02984242,-0.00165906,-0.12866215,0.10517475,0.06073699,0.04404459,0.01173706,0.13524732,-0.02750749,-0.01259638,0.0591471,-0.01989947,0.01510853,0.03263251,-0.07064701,-0.00159653,0.03317318,-0.05331706,-0.02457827,0.00762446,-0.02627334,-0.06275462,0.0166719,-0.00844811,0.00384422,0.03387692,-0.05526694,-0.06185808,-0.05956736,0.01089076,0.03342117,0.03269158,0.01090201,-0.07924412,0.00676454,-0.02340987,-0.08048524,0.00926158,0.07377242,-0.042637,0.01060221,-0.00487976,-0.06276151,-0.065808,0.00417723,-0.14457221,0.02307644,-0.04314826,-0.085192,-0.05946225,3.29e-06,-0.08414075,-0.01126169,0.00576276,-0.01006619,-0.02002585,0.03222204,0.0030698,-0.12167758,0.06989248,-0.05854789,-0.04924576,0.06678208,-0.09528458,0.05323353,-0.08599229,-0.05688987,-0.01204253,-0.00270364,0.06011238,-0.02444952,0.04526666,-0.04773167,0.03148824,-0.00333784,0.07740294,0.00876846,-0.04871678,-0.00062894,-0.02669641,-0.00562755,-0.01569301,-0.01129945,-0.01878245,-0.08357259,-0.03691876,-0.06963301,0.0014787,0.00141514,-0.00569116,0.00590921,-0.00593455,0.06367468,0.02950548,-0.05430056,0.03596744,-0.01696684,-0.00693556,0.08240442,0.04631814,-0.0252602,0.00790498,0.01438357,0.00489633,-0.00288838,0.05963629,-0.0572878,-0.05364172,0.07822086,-0.0017633,-0.08162173,0.10852664,0.02810301,-0.04267985,-0.01660845,-0.02279622,0.05631938,0.01413427,-0.01867247,0.08268835,-0.05915805,-0.01138976,0.01583136,-0.06736136,-0.0100825,-0.04336391,-0.00413565,-0.01702111,0.00569688,-0.01981823,-0.03773049,-0.06471326,-0.03173589,0.0196765,0.00378094,0.02689061,0.0054456,0.06751623,0.04960961,-0.04055895,-0.12172075,0.06905396,0.01962237,0.04109683,-0.01573733,0.02758238,9.84e-06,-0.02220745,-0.06606548,-0.0830964,-0.02706342,-0.03410717,0.07015055,0.04043636,0.01417724,-0.00100051,0.00060468,0.02816941,0.03844694,-0.01695836,-0.1338035,0.07178744,-0.03469136,-0.09624624,0.14970079,0.06917409,0.02656811,-0.01603339,0.07798919,0.05335713,0.03160486,0.01861056,0.01586158,0.00955139,0.09717151,-0.07572885,-0.0027573,-0.06467502,-0.02236018,-0.08633829,0.00632914,-0.06081435,-0.08584573,0.12159909,0.03796601,-0.10540765,-0.00703974,0.10395006,0.02507906,-0.00063409,-0.02009147,-0.01978116,0.00088455,-0.0909335,-0.06927564,-0.02610815,-0.01695917,-0.00224834,0.00441648,0.02767904,-0.06420764,-0.00221844,0.10753718,-0.01533656,-0.01097437,0.00439244,-0.03240376,-0.03702915,-0.02510168,-0.0717014,0.02769655,0.03529622,-0.02156757,-0.0140087,0.08010856,0.06518592,-0.03668962,0.05236501,0.0975678,0.02771983,0.0018118,-0.00531801,-0.00733004,0.03280008,0.06438836,-4.364e-05,-0.01524685,-0.02244918,-0.00143007,-0.01731955,0.01680027,0.02568061,0.03590529,0.075507,-0.0480325,-0.00374405,-0.0847721,0.02196613,0.04628415,0.02859041,0.01500637,-0.01245001,1.1e-06,-0.01318111,0.03917511,-0.05954706,-0.10353019,0.00768529,-0.05103174,-0.02736702,0.0050585,-0.05700845,-0.01495968,0.01634828,-0.05980412,-0.00088109,-0.00214765,-0.02317298,0.01319426,0.00717996,0.08321799,-0.01237014,-0.07580704,0.0343448,0.0072042,-0.00107572,0.03768432,0.01839454,-0.00342738,-0.0458048,-0.04429244,-0.04248356,0.03208315,-0.01942314,-0.00895721,0.08801077,0.03852325,0.05399201,0.07971377,0.10610118,-0.02578465,-0.07314251,0.0008706,-0.06173204,-0.06844583,0.03418158,0.00234862,0.01868404,-0.06103093,0.10474085,-0.02533472,0.08249455,0.0429742,0.05406225,0.05529388,-0.04185039,-0.02726665,-0.0629438,-0.01218653,-0.00082378,0.02177983,-0.05211846,-0.02474916,0.03482662,-0.054645,-0.03258012,0.03935319]');
INSERT INTO public.detalles_proyectos VALUES (108, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:20:17.959675', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.07278105,-0.02905011,-0.05988716,-0.0558422,0.01666599,0.05455533,0.04801954,0.11677805,0.07314338,0.0219616,0.06915544,0.04909044,0.00697034,-0.02689877,-0.14633963,0.00475983,-0.05616508,-0.01190771,-0.01677815,-0.03192682,-0.00491202,0.0108241,0.04098212,-0.03919665,-0.01294926,0.03140573,0.01965909,-0.00457774,0.00170186,0.01586786,0.08716946,-0.05116948,-0.0240806,0.06824201,-0.03178869,0.02091682,-0.10308711,-0.13514376,0.02762551,0.05952084,0.06628436,0.00880278,0.01562387,0.11986904,0.11166573,0.05513349,-0.03494135,-0.0274535,-0.07733577,0.0113928,0.04422636,0.00138075,0.04994686,-0.01178155,-0.0082284,0.08329131,-0.09358871,-0.0063936,-0.05046013,0.03114025,0.05825542,0.00955683,-0.03493286,-0.07028555,0.01098015,0.11163272,0.01573268,-0.073937,0.00334975,-0.03331139,-0.02057207,0.07467103,0.02402196,-0.08880307,0.02904487,0.01972183,-0.02331793,0.01738924,0.02451678,-0.12948051,0.10228824,0.06310556,0.04061632,0.02116224,0.13401386,-0.03743423,0.01026478,0.04999369,-0.00183552,0.0170061,0.01255203,-0.07901795,-0.01612543,0.01412965,-0.08550475,-0.02155693,0.02662491,-0.02638364,-0.05616864,0.01450813,0.01380279,0.01639226,0.05288732,-0.07095922,-0.07058268,-0.04860203,0.02015943,0.04022857,0.00171382,0.00506912,-0.08708608,0.01228318,-0.04714315,-0.09100796,0.01116112,0.10420862,-0.04183108,0.00368158,0.0015938,-0.05718778,-0.07300358,0.00862496,-0.15851054,-0.00230327,-0.05516004,-0.07741442,-0.07378612,3.13e-06,-0.09028767,-0.02222156,-0.01690308,8.961e-05,-0.01419225,0.04860336,-0.00689646,-0.09382701,0.06515542,-0.04593077,-0.06146757,0.02542294,-0.08575773,0.05121452,-0.07621752,-0.05251557,-0.00870654,0.00151005,0.04574718,-0.04280348,0.05889707,-0.03345648,0.03682167,-0.01138818,0.08707694,0.0077016,-0.04032319,-0.00852116,-0.01586828,-0.00692387,-0.02169588,0.00333229,-0.02632615,-0.07627682,-0.03013308,-0.08962076,-0.00843388,-0.00670772,0.01505525,-0.00121542,0.03000229,0.08354641,0.05119595,-0.06784903,0.05456545,-0.01808761,-0.02090793,0.08715022,0.06507942,-0.02303625,0.01402692,0.01332744,0.00980903,0.00665936,0.06103076,-0.04048882,-0.06178769,0.06578608,-0.0217768,-0.0839594,0.10314421,0.02986434,-0.03929084,-0.02237812,-0.00955119,0.07053715,0.01817415,0.00304627,0.06162729,-0.06441686,-0.00665335,0.00939484,-0.03636538,-0.03629991,-0.04562659,-0.00089106,-0.0237565,0.03320473,0.01188371,-0.05295464,-0.08561826,-0.04101471,0.02693099,-0.01781801,0.01572922,0.00279384,0.04494922,0.05405578,-0.03267479,-0.08461669,0.06626225,0.00161241,0.02963112,-0.01083986,0.00679239,9.52e-06,-0.01477202,-0.05498642,-0.0578285,-0.05214109,-0.0198069,0.06933745,0.03347117,-0.01148888,-0.01434804,-0.00873823,0.04663087,0.05528739,-0.01569871,-0.10603056,0.07882702,-0.0396829,-0.10009722,0.14935505,0.07831891,0.01488518,-0.0277172,0.06740909,0.06242561,0.03734971,0.01786263,0.01272888,0.00747518,0.09822151,-0.04324032,0.02675182,-0.07969088,-0.01035535,-0.05611345,0.01609001,-0.04401237,-0.09168888,0.11889809,0.03139984,-0.09223211,-0.02696125,0.08273226,0.01109234,-0.00526911,-0.00764102,-0.00022195,0.01832323,-0.06096069,-0.08312404,-0.03135633,-0.03467488,-0.00570358,-0.0179436,0.01158423,-0.05235159,-0.00599946,0.11379442,-0.02809738,-0.03452262,0.01331064,-0.03399183,-0.0252131,-0.01662333,-0.06009627,0.02846417,0.03980817,-0.02361535,-0.02931118,0.08026701,0.08113988,-0.03522807,0.04437904,0.09859358,0.02113018,-0.00137135,-0.02094869,-0.01178786,0.05015312,0.0574945,-0.00611362,-0.03468411,-0.03111344,-0.00158295,-0.00123923,0.02454781,0.01566905,0.01733422,0.0637764,-0.05846415,-0.00872319,-0.07377796,0.00376174,0.06351805,0.02666568,0.03096522,0.01249662,1.09e-06,-0.01699362,0.06598247,-0.06606551,-0.08070169,-8.197e-05,-0.06090656,-0.05167547,0.01635036,-0.0618325,-0.00408515,-0.0078121,-0.05678844,0.00105287,-0.00854475,-0.01798652,-0.00778356,-0.0115207,0.07620408,-0.01908108,-0.06534122,0.03578014,-0.00301588,0.02460686,0.0381222,0.01860059,-0.00801346,-0.04272204,-0.02977413,-0.04045351,0.02160751,-0.01637845,0.00213733,0.07289573,0.04947521,0.06629061,0.07503279,0.09897973,-0.0319392,-0.05924059,0.00177431,-0.03785982,-0.06590271,0.00540012,0.01662264,0.03866511,-0.0650368,0.1027658,-0.0434083,0.07157693,0.03833731,0.05225999,0.04221721,-0.04079651,-0.01882342,-0.05383564,-0.01027218,-0.0279813,0.04325083,-0.03667133,-0.01570533,0.04152439,-0.06313253,-0.03366146,0.0368137]');
INSERT INTO public.detalles_proyectos VALUES (111, '2026-08-25', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-25 19:01:06.640902', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.07395713,-0.09568264,-0.07829733,-0.05112629,-0.0763161,0.03107943,0.01981424,-0.00730564,0.00135696,0.02807856,-0.02534383,-0.00917311,0.09600637,0.00108013,-0.09183897,-0.02606907,-0.04716039,-0.14013389,0.07579649,0.01251227,0.00031695,-0.0388491,0.0363821,0.12573889,0.0079852,0.0114569,-0.02618801,0.01012963,-0.01590045,-0.04787936,0.0124808,0.0391932,0.00063902,-0.0064532,-0.0511494,0.04672913,-0.00693492,0.04888154,0.00479184,0.00239567,-0.04618455,-0.03276881,-0.00882726,-0.0111681,0.00543222,-0.00392005,0.03035269,-0.05887629,-0.05590823,-0.00022076,-0.06747766,-0.00914971,-0.00084901,-0.014888,0.03968184,0.02098104,0.11982773,-0.04313602,0.07729404,0.02090051,0.00380989,0.02463392,0.02312893,0.02744717,-0.01179678,0.03071128,0.00417188,-0.02153704,0.0882094,-0.04921484,-0.00811779,0.03263321,0.06564264,0.05700208,-0.01659179,0.02462684,-0.04843574,0.00293867,0.14597397,-0.05572934,-0.00618903,-0.00915615,-0.09777529,-0.004182,0.0437451,0.02769816,-0.01019373,-0.04511747,0.12623261,0.03365613,0.09406718,-0.0302867,0.0266511,0.01895688,-0.0738267,-0.09973292,0.12971199,0.014604,-0.0261087,-0.01327222,-0.00566701,-0.0320108,0.07713302,-0.04085418,0.00044834,-0.04519383,0.0526648,-0.02942315,0.08786659,-0.11197471,-0.09049243,-0.00326484,-0.03701218,-0.00958105,-0.03223765,0.01399712,-0.02586877,-0.07685667,-0.00662613,-0.07686598,0.00457594,-0.0209893,-0.04259409,0.02418548,0.09172686,-0.07745043,-0.05013623,4.44e-06,0.08014305,0.03416596,-0.03023256,0.10270819,-0.00243234,-0.00903697,0.03109081,-0.02301147,0.07864058,-0.04370124,0.01557103,0.01546651,0.00870582,0.11697274,-0.04249551,0.01433972,-0.01686445,-0.07973693,0.0201668,-0.02427756,-0.04463377,0.03413855,0.00323606,-0.00123799,0.05194404,-0.02174884,-0.10154465,0.06346551,-0.00954516,0.04744229,0.03297861,0.09703403,-0.09927884,0.00283462,0.07705286,0.00466145,0.02861295,-0.05624212,-0.00823942,0.07875059,0.08648082,0.00291136,-0.02420195,0.02198201,0.06875845,-0.05281008,0.0094714,-0.01768376,-0.02554877,-0.01981197,-0.09055032,0.02316937,0.06000191,-0.05136368,0.01569454,-0.03671392,-0.00772745,0.0313613,0.15028928,0.00323592,0.02315178,0.01675471,-0.00067045,0.04599222,-0.01277837,-0.05233802,-0.04839084,-0.04332847,0.07992115,-0.02594631,0.05072976,0.02086298,-0.01074373,0.06978013,-0.07300613,0.05024919,-0.05113825,-0.01591669,-0.01137592,-0.04732529,0.00297791,0.0278224,-0.06717641,0.04216644,0.07406398,0.01884296,0.06528425,-0.05103272,-0.01673026,0.03953167,-0.0906372,-0.08256744,0.04257026,-0.0561333,-0.0335154,1.256e-05,-0.00497621,0.0311247,-0.04365255,0.04976479,-0.0497481,0.01507254,0.00545258,0.01997342,0.00344575,0.01681437,0.06779257,-0.06962522,0.06887584,-0.06556054,0.07050383,-0.04534123,0.09225853,0.0034055,0.00029875,-0.00669205,0.06822323,-0.09788948,-0.04799586,-0.06615074,-0.01236237,0.04753865,-0.03707729,0.03525711,-0.04351954,0.03081008,-0.03761696,-0.00512625,-0.03092602,0.07573209,-0.01837384,-0.0407224,0.03360505,-0.02269413,0.05175768,-0.03699451,0.09538561,0.0193904,0.01897252,0.08063964,-0.05902392,-0.04006551,-0.03101576,-0.13811062,0.11035307,-0.02393385,0.09061441,-0.07964696,-0.03070982,-0.09991624,-0.03625855,-0.03811077,-0.04763502,-0.03108265,-0.03323767,-0.00060583,-0.03230267,0.02973477,-0.06245107,-0.03480889,0.00198476,-0.00459565,-0.03572065,0.12183643,0.02412496,-0.06658413,0.11606648,0.00333818,0.03520502,-0.0714714,-0.00840705,-0.06299623,-0.07047996,0.08712604,0.02368847,-0.02247253,0.04946736,-0.02937719,0.00240272,-0.08924353,-0.02586705,0.05886838,0.00108643,-0.02100662,0.00055514,-0.1174353,0.0469523,-0.03954495,0.04554407,-0.02211802,0.09240629,1.25e-06,0.01052138,0.0241574,0.01613499,-0.00127161,0.0346568,-0.01860497,0.00799515,-0.05269771,-0.05162618,-0.00497997,0.02515128,-0.03262726,0.01669534,0.04055049,-0.01139669,0.0281909,-0.10597255,0.00710874,-0.05108227,-0.04850108,0.0468569,-0.04980679,0.01303628,-0.01105839,-0.06193123,0.01586363,-0.06516491,-0.09653204,-0.05301221,-0.05955818,-0.00274696,-0.03955549,-0.04132096,-0.04288912,0.04884429,-0.0331055,0.00917866,0.01098028,0.02078503,0.03156174,-0.04158391,-0.03796252,-0.07809529,0.02905962,-0.028147,-0.03593761,-0.09738,0.05095968,-0.07019163,0.0136518,0.04212343,-0.01788916,0.03346661,-0.01130503,0.00518829,-0.04974157,0.05202376,0.04524231,0.00201914,0.01220208,-0.00960146,0.0164054,-0.00821331,-0.05074277]');
INSERT INTO public.detalles_proyectos VALUES (112, '2026-08-25', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-25 19:01:06.749048', NULL, 'Trayecto I', NULL, NULL, NULL, '[-0.02610817,0.06999041,0.02958966,-0.11567587,-0.03200752,-0.06200793,0.11619614,0.11275585,-0.00710944,-0.04989769,0.15872276,-0.04905323,0.08363298,-0.00804548,0.01136989,-0.02562215,0.02840097,0.06571539,-0.02496208,0.11503602,-0.02365189,0.02947256,0.05283521,0.02733021,0.0427077,-0.03544493,0.05183344,0.0123763,0.05314914,0.01350018,0.02701591,0.03192398,-0.02352316,0.08542665,-0.02202436,-0.03611417,-0.0045524,-0.09111032,-0.08632544,0.05367772,-0.01817447,-0.03299841,-0.01471112,0.02597935,0.05186762,0.01100267,-0.02670493,0.02862161,-0.05223074,0.03610405,-0.02036806,0.08124141,0.01410844,0.03503387,-0.10525842,0.04719274,-0.04222343,0.0228726,0.05904025,0.07993947,0.09386143,0.03102855,-0.02861604,-0.00304825,0.00885977,0.04552137,-0.02602392,-0.04489711,0.0166656,0.0143658,-0.00825058,-0.02228493,-0.03513378,-0.06803963,-0.04789313,0.02589494,-0.00840566,0.02731645,-0.01549627,-0.0116355,0.04425651,0.06669561,-0.05914477,0.06989442,0.04686721,0.03478627,-0.04499924,0.10275331,-0.0108232,-0.04677443,0.04468258,0.00471595,-0.07502753,-0.05347374,-0.01796358,-0.00607811,-0.05028606,-0.09066139,-0.05279225,0.0556943,-0.02878661,-0.02134344,0.04129202,0.04845973,-0.04046334,0.00216004,-0.03492721,0.02474891,0.03464137,0.04810357,-0.02328636,-0.06187883,0.01798387,-0.07871581,-0.08611194,0.12828617,0.02475149,0.05292773,0.05661969,-0.07464563,0.00274962,0.02431294,-0.08679127,-0.06204727,-0.03258215,-0.0372092,0.03382579,5.75e-06,-0.07938354,-0.02375952,0.01637073,0.00370126,0.03357804,-0.02635113,0.05150778,-0.05375025,0.01032577,-0.06536026,-0.0263971,0.06223106,-0.1056245,0.05086365,-0.01096227,-0.00655912,0.02153059,-0.03314594,0.09468396,0.02989549,-0.02456585,-0.0586944,0.06351361,-0.00545308,0.0176041,0.06327925,-0.0213813,0.05275416,-0.01313598,0.00943865,0.06079363,-0.10262294,0.01808016,-0.04219846,-0.0382583,-0.05998429,0.00861608,-0.04235488,0.03424359,0.00310111,-0.10044007,0.04835551,0.0174436,-0.00356868,-0.04837784,-0.00246172,0.02956072,0.06227873,0.04225605,0.02291023,-0.0242658,-0.05620286,-0.08289583,-0.07575391,-0.03859547,-0.05590496,-0.05612954,0.09326383,-0.05368593,-0.00495857,-0.03588153,-0.01082772,0.01073061,-0.01742187,-0.08287506,-0.04138612,0.0104599,-0.07029737,0.00930132,0.00628513,-0.07430055,-0.00093769,-0.03727065,0.01225514,0.01362212,0.02060059,-0.0295869,-0.03703264,0.03386726,-0.0122894,0.00853798,-0.00353991,0.02095011,0.04817139,0.05134419,-0.02527694,-0.03074067,-0.09260939,-0.02141275,0.02238279,-0.07753046,0.02939879,-0.01003373,0.05981188,-0.03620421,1.558e-05,-0.09324646,-0.05462129,-0.01326377,0.0254891,-0.0083073,-0.00409739,-0.01495483,-0.04060502,-0.04108672,0.02105594,-0.00407369,0.03220022,0.00307167,-0.03573545,0.03405212,0.11047779,0.01601722,0.01367872,-0.00168073,-0.04026811,-0.03055897,0.10333244,0.08172007,-0.09977786,0.02608168,-0.0690025,0.06001074,0.07468504,0.00144069,0.00327391,-0.03962399,-0.06491377,-0.09780637,0.01132106,0.04900082,-0.04453948,0.05682653,0.05416007,0.02148124,-0.0686253,0.04739301,-0.08614358,0.02848522,-0.03222114,0.00660484,-0.01458802,-0.07103573,-0.09536584,-0.00069553,0.05204202,0.06414173,0.03022285,0.02787663,0.00975812,-0.07821102,0.10355547,0.00789651,-0.07819588,-0.02619576,-0.11889828,0.10186004,-0.04634555,-0.03600628,-0.05457118,-0.0056699,0.0295682,-0.0383678,0.02181155,-0.01194792,-0.04343253,0.04939993,0.00185795,0.0049728,-0.02154284,-0.06242142,0.01618704,-0.04116415,-0.04375296,0.04157515,-0.01633051,-0.05314173,0.05966556,-0.01059045,-0.12236278,0.0137342,-0.02490099,0.01055754,0.08295421,-0.04176218,0.03942721,-0.04077555,0.08358269,-0.06013852,0.07473514,-0.05155316,1.56e-06,-0.02596817,-0.06839419,-0.0555871,-0.07566544,-0.03604026,0.0095297,-0.0264789,-0.01691148,0.07979903,0.07388178,0.01889121,-0.06069396,-0.07921598,0.06277431,-0.03999668,0.01895985,0.04002556,0.06315764,-0.06817532,-0.07398683,0.08986176,0.04186255,0.03179293,-0.03003432,0.00838952,-0.03418266,-0.01914541,0.0190836,-0.01210612,0.00938458,0.01148612,-0.01583314,-0.00489925,-0.04110766,-0.05832208,0.05961003,-0.0441979,-0.03826571,0.02988105,0.02769111,-0.0080632,-0.0733584,0.06787274,0.05144626,-0.02624781,-0.08638985,0.09567133,-0.01710389,0.06411024,0.13059899,0.05136235,-0.08307211,-0.02641574,-0.08261953,-0.03089846,-0.022827,0.08646626,-0.0040778,-0.01662591,0.00533016,-0.02717496,0.04180031,0.04750095,0.01108117]');
INSERT INTO public.detalles_proyectos VALUES (116, '2026-08-31', 'Doctorado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-31 09:47:06.862144', NULL, NULL, NULL, NULL, NULL, '[-0.14218825,-0.0464609,-0.0058442,0.01766919,-0.03504182,0.06997651,0.01143639,0.05528012,-0.02315694,-0.0530111,0.09044541,-0.05151796,-0.02575316,0.00393019,-0.15284178,0.0076079,0.01382069,0.0036347,-0.03013565,0.02683899,0.01071377,0.00582926,-0.02885869,0.06165336,-0.06947874,0.00279988,0.03499892,0.04581739,0.01356885,0.01809845,-0.00211291,-0.02240636,0.09187295,0.03032626,0.05687546,0.0054567,-0.01910545,-0.04154085,0.08121699,0.01476963,0.03661354,-0.01264164,-0.03384501,0.01927333,0.00111764,-0.10464798,0.10542853,0.02340295,-0.03361852,-0.01541278,-0.05944463,-0.01719712,0.06954618,-0.09025986,-0.04015287,0.01466391,-0.01525235,-0.06746769,-0.039261,-0.02041669,0.10823036,0.02318669,-0.07368187,-0.02091956,-0.00854443,0.04860191,0.03516272,-0.00115645,0.03245482,0.01116891,0.03213634,0.03247528,0.05884172,0.01574121,-0.02671361,-0.00697877,0.03019961,-0.01723575,0.01600484,-0.12522864,0.09501011,0.00437966,0.00879451,-0.01505203,0.00054379,-0.11008683,-0.01072206,0.02866185,0.09125714,0.05439658,0.07281388,-0.04283041,0.05769307,-0.06726436,0.01940848,0.01256344,0.08824273,-0.0724159,-0.00338817,0.04646244,0.04950963,0.03348477,0.01134078,0.03730245,-0.07663831,0.04344368,-0.04265281,-0.00546474,0.03987293,0.02822864,-0.05709192,0.01557679,6.377e-05,-0.08414564,-0.04744411,0.00546808,-0.03779104,0.02044096,0.00517198,-0.04742575,-0.05739673,-0.08382071,-0.0543656,0.02869913,0.0635019,-0.00066659,-0.08137471,5.01e-06,-0.08166154,-0.02554379,0.02210158,-0.00252163,0.08028895,-0.03965393,-0.08804764,-0.04810141,-0.00198248,-0.07986071,0.02548698,0.05683572,-0.07465908,-0.01764096,-0.02329634,0.07214695,-0.12850362,-0.03024711,0.01725339,-0.00957087,-0.02011152,0.01089031,0.02002943,-0.00710697,0.02654934,0.00893691,0.02182489,0.001117,-0.12103108,0.02084213,0.03532903,0.00599929,-0.05603337,-0.02037412,0.05109791,0.00537681,0.02061425,-0.02201079,0.01274007,-0.02515606,0.06108769,0.05671675,0.02067049,-0.04185817,-0.00549996,-0.04319785,-0.03348905,0.07924528,-0.00880463,0.03556663,0.06930625,-0.035771,-0.03771721,0.02133628,0.04226677,-0.01682308,-0.08587434,0.04822016,0.04339884,-0.07145443,0.11272946,0.00995713,-0.01118917,0.06334731,-0.0947671,0.0038444,-0.07880241,0.06546454,0.13720784,0.01803073,-0.0671441,0.01478905,-0.04114034,0.10255605,-0.06865692,0.02682718,-0.05703759,-0.02940719,-0.02520568,0.03833817,-0.05257398,0.00952688,0.04070272,-0.03183632,-0.04994124,0.0593255,-0.0005392,0.07206017,0.00180179,-0.02232637,0.00825113,-0.02908778,0.00078589,0.03879732,0.00636705,1.592e-05,-0.0158282,-0.06409661,-0.01783091,-0.04838695,-0.05493145,-0.00608774,-0.03602684,-0.03086007,-0.02123819,-0.07010463,0.00405819,0.04987244,0.01333818,-0.04216712,0.10449176,0.06282145,0.01549234,-0.07360991,-0.06706809,-0.08193956,-0.00663851,0.00772976,-0.01282155,-0.01422943,-0.034881,-0.01695013,-0.02091229,0.11341758,0.03591052,0.05241356,0.03835426,0.0407225,-0.04273471,0.01601959,-0.09852996,-0.03988598,0.06784142,-0.02692737,-0.05105185,0.08392445,0.08523909,0.02169901,-0.00413896,0.02829147,-0.04806115,0.07096292,-0.0377576,-0.17976578,0.02630271,-0.09818944,0.04859818,-0.04565335,0.02509551,-0.06885011,0.02393627,0.09133752,0.08935043,-0.03766609,-0.01266489,0.03555164,0.02203172,0.03630482,-0.04702395,-0.04635226,-0.02400739,0.01880806,-0.04249086,0.05746656,0.05843969,-0.09019552,0.0176226,0.01687214,-0.10147422,0.01482391,-0.07257765,0.05609986,-0.05652126,0.00277283,-0.05189883,-0.02868266,-0.02500597,-0.10890797,0.01878011,0.01131579,-0.03860388,-0.01473789,0.01766045,0.02544132,0.06758771,0.03602352,0.01769278,-0.06120207,-0.02657877,-0.06574174,-0.05975461,1.68e-06,-0.01380488,-0.05871524,0.0398283,-0.10206898,-0.02399065,0.02350566,-0.02518991,0.01146287,0.02617604,-0.02577861,0.03508449,-0.0497123,-0.02681444,0.0617019,-0.03751513,0.00707481,-0.01980038,0.04187768,-0.03386907,-0.10448277,-0.01261546,0.01710961,-0.09741129,-0.02513918,0.00382446,-0.01914238,-0.06137499,0.04934807,-0.06527103,0.01508845,-0.05005082,0.02730553,0.01408317,-0.00479196,0.04690249,0.0543049,0.08933242,-0.08073906,-0.05596682,-0.10019255,0.0581966,-0.01115508,-0.08755639,0.01969188,0.03173088,0.01077443,0.05381008,-0.00198929,0.02832097,0.03366243,0.06687761,0.03262839,0.00053829,0.03427574,-0.07179493,-0.04184396,-0.03638888,0.0024934,-0.08050109,-0.04688241,0.04424338,0.03090825,0.06264501,-0.02251241]');
INSERT INTO public.detalles_proyectos VALUES (132, '2026-09-04', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-09-04 10:35:11.057661', NULL, 'Trayecto I', NULL, 'Desarrollar un Sistema Integral de Gestión Documentos Académicos, basado en una arquitectura modular, para la automatización de la búsqueda híbrida de información y la centralización de recursos académicos en beneficio de la comunidad del PNF en Informática.', true, '[-0.06820898,-0.03987347,-0.05641365,-0.06617668,0.02620296,0.04784802,0.05694392,0.11366388,0.05632957,0.0409088,0.09208983,0.06738056,0.01730321,0.00760483,-0.1279735,-0.00604577,-0.06844729,0.00328843,-0.02313659,-0.02292804,-0.00017123,0.01107944,0.06615355,-0.03704241,-0.02691198,0.02303154,0.04405978,-0.03068663,0.0021628,0.01317368,0.06947047,-0.06228169,0.00620717,0.06798749,-0.01920359,0.05659107,-0.08675502,-0.10218445,0.01617277,0.07068089,0.06637871,-0.01163842,0.02950291,0.08803916,0.08847883,0.05946008,-0.01817008,-0.01267121,-0.04943992,0.01622863,0.02365781,-0.00798639,0.03344893,-0.01867533,-0.01178053,0.10216358,-0.08185573,-0.04815998,-0.08075134,0.02732011,0.03696862,0.04235641,-0.04580691,-0.05155655,0.01306222,0.12255652,-0.00357025,-0.06675397,-0.00734873,-0.04813821,0.02352943,0.05566823,0.00679126,-0.1141557,-0.00026077,0.03547563,-0.03013932,0.03321303,0.0178875,-0.12376842,0.10134251,0.0670313,0.04049361,-0.00360638,0.10779116,-0.02353908,-0.02083683,0.05810762,-0.01112724,0.01529154,0.05065561,-0.08057691,0.01091236,0.02799546,-0.04623596,-0.0203243,0.00366549,-0.02285972,-0.05709289,0.01065899,-0.01086557,-0.00132487,0.04418176,-0.0508149,-0.05488605,-0.04489449,0.00595496,0.02168497,0.04197521,0.00627653,-0.09368443,0.00464832,-0.02954077,-0.07054432,-0.01375436,0.07155209,-0.00832472,0.00702256,-0.02659992,-0.07773466,-0.05507795,-0.00353629,-0.13326298,0.00986555,-0.02480868,-0.08745582,-0.05385987,3.3e-06,-0.07268374,-0.01881173,-0.01627901,-0.00586486,-0.01618173,0.01811218,0.01315754,-0.12203681,0.05169017,-0.05393324,-0.04471056,0.08664827,-0.09177421,0.05284444,-0.07885616,-0.03947573,-0.03124203,-0.00816492,0.05216927,-0.01357889,0.03837481,-0.04805475,0.04405122,0.00856095,0.06697539,0.01195428,-0.05105975,0.00490107,-0.02844697,-0.01122983,-0.02353063,-0.01706665,-0.02516057,-0.07324004,-0.03957017,-0.03431975,-0.00676728,-0.00644694,-0.00293006,0.01385529,-0.01582279,0.04249231,0.01719994,-0.04251236,0.03804928,-0.01577274,-0.02312556,0.06115401,0.02799556,-0.02959958,0.01252161,0.02022557,-0.01271457,-0.0041546,0.05753323,-0.05691839,-0.07849372,0.08358284,0.0030296,-0.07699181,0.12716435,0.02319953,-0.04479177,-0.00216504,-0.03254559,0.05004057,0.01628581,-0.02215454,0.08234476,-0.07137828,0.0120599,0.01555929,-0.07253808,0.01679297,-0.05580754,-0.01113903,-0.02239742,-0.00174652,-0.01834406,-0.04525564,-0.08138874,-0.03759696,0.01487581,0.0318085,0.03203848,0.02140403,0.07157589,0.04369114,-0.05848954,-0.1290364,0.0555725,0.02685127,0.024209,-0.02135495,0.03445813,1.006e-05,-0.02968518,-0.08067787,-0.09111592,-0.02616273,-0.03686867,0.07186868,0.06017597,0.02765903,-0.00595717,-0.00075103,-0.01570362,0.04914653,-0.00434894,-0.12088305,0.06473553,-0.02027254,-0.08672508,0.14924023,0.05214739,0.02539527,-0.01768177,0.07944129,0.04538019,0.03322879,0.02944026,0.01951703,-0.01079736,0.08321002,-0.08793538,-0.00437476,-0.05337738,-0.02316035,-0.06592859,-0.00353393,-0.07056839,-0.08043919,0.13304406,0.04002132,-0.09053408,-0.02090798,0.10211887,0.01948865,0.00856777,-0.01934948,-0.01657336,0.01703808,-0.1123951,-0.07404217,-0.02614354,-0.01420033,0.00181353,0.0203192,0.03973463,-0.06898596,-0.01114406,0.10536498,-0.00949463,-0.017576,-0.00431509,-0.03087117,-0.03793241,-0.00943272,-0.09102334,0.05383948,0.04280809,-0.02679346,-0.01003056,0.0796932,0.05589025,-0.03766666,0.06252651,0.10580791,0.0178718,0.00637451,0.01295498,0.01043378,0.02365583,0.06696698,-0.00611707,-0.00465298,-0.00769475,-0.0117754,-0.01682024,0.01387635,0.01985573,0.03790108,0.07757905,-0.05437773,-0.00943796,-0.07898449,0.00606944,0.03540976,0.00572374,0.0059049,-0.0237941,1.14e-06,-0.013077,0.03786563,-0.0485628,-0.11983989,-0.02182192,-0.03995225,-0.02206089,0.02244002,-0.05566131,-0.01815675,0.00712315,-0.04968374,-0.01770175,0.00374643,-0.04029593,0.00900355,0.00137879,0.09266131,-0.02098523,-0.07282108,0.0362597,0.01771125,-0.00942717,0.03839217,0.0181418,0.00276297,-0.02893091,-0.04906752,-0.04277303,0.02434207,-0.00531765,-0.00771834,0.08919472,0.04605677,0.05534439,0.07118269,0.10378014,-0.02663324,-0.06675357,0.01451678,-0.05846433,-0.05861553,0.04537754,0.00391008,0.03092657,-0.06573984,0.0769805,-0.03265995,0.10733229,0.0462714,0.06983847,0.05609597,-0.05088053,-0.03718802,-0.06783614,-0.01031774,0.01466645,0.02694671,-0.05681402,-0.03279735,0.03806685,-0.04148194,-0.0160284,0.03939087]');


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
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.proyecto_tutores VALUES (57, 7, 3);
INSERT INTO public.proyecto_tutores VALUES (57, 8, 2);
INSERT INTO public.proyecto_tutores VALUES (57, 9, 4);
INSERT INTO public.proyecto_tutores VALUES (58, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (58, 11, 2);
INSERT INTO public.proyecto_tutores VALUES (58, 12, 4);
INSERT INTO public.proyecto_tutores VALUES (78, 16, 3);
INSERT INTO public.proyecto_tutores VALUES (85, 16, 3);
INSERT INTO public.proyecto_tutores VALUES (86, 16, 3);
INSERT INTO public.proyecto_tutores VALUES (87, 16, 3);
INSERT INTO public.proyecto_tutores VALUES (90, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (90, 18, 2);
INSERT INTO public.proyecto_tutores VALUES (90, 19, 4);
INSERT INTO public.proyecto_tutores VALUES (91, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (92, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (92, 20, 2);
INSERT INTO public.proyecto_tutores VALUES (92, 21, 4);
INSERT INTO public.proyecto_tutores VALUES (93, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (94, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (94, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (97, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (97, 25, 2);
INSERT INTO public.proyecto_tutores VALUES (99, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (99, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (100, 29, 3);
INSERT INTO public.proyecto_tutores VALUES (100, 30, 2);
INSERT INTO public.proyecto_tutores VALUES (100, 31, 4);
INSERT INTO public.proyecto_tutores VALUES (108, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (108, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (109, 35, 3);
INSERT INTO public.proyecto_tutores VALUES (109, 36, 4);
INSERT INTO public.proyecto_tutores VALUES (110, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (110, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (111, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (111, 38, 4);
INSERT INTO public.proyecto_tutores VALUES (112, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (113, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (113, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (114, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (114, 38, 4);
INSERT INTO public.proyecto_tutores VALUES (116, 10, 3);
INSERT INTO public.proyecto_tutores VALUES (116, 20, 2);
INSERT INTO public.proyecto_tutores VALUES (116, 21, 4);
INSERT INTO public.proyecto_tutores VALUES (117, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (117, 39, 4);
INSERT INTO public.proyecto_tutores VALUES (127, 40, 3);
INSERT INTO public.proyecto_tutores VALUES (128, 40, 3);
INSERT INTO public.proyecto_tutores VALUES (132, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (132, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (148, 29, 3);
INSERT INTO public.proyecto_tutores VALUES (148, 30, 2);
INSERT INTO public.proyecto_tutores VALUES (148, 31, 4);
INSERT INTO public.proyecto_tutores VALUES (149, 35, 3);
INSERT INTO public.proyecto_tutores VALUES (149, 36, 4);
INSERT INTO public.proyecto_tutores VALUES (147, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (49, 40, 3);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (3, 1);
INSERT INTO public.recurso_autores VALUES (58, 42);
INSERT INTO public.recurso_autores VALUES (58, 43);
INSERT INTO public.recurso_autores VALUES (58, 44);
INSERT INTO public.recurso_autores VALUES (58, 45);
INSERT INTO public.recurso_autores VALUES (59, 46);
INSERT INTO public.recurso_autores VALUES (69, 50);
INSERT INTO public.recurso_autores VALUES (69, 51);
INSERT INTO public.recurso_autores VALUES (72, 52);
INSERT INTO public.recurso_autores VALUES (72, 53);
INSERT INTO public.recurso_autores VALUES (78, 55);
INSERT INTO public.recurso_autores VALUES (78, 56);
INSERT INTO public.recurso_autores VALUES (79, 42);
INSERT INTO public.recurso_autores VALUES (79, 43);
INSERT INTO public.recurso_autores VALUES (79, 44);
INSERT INTO public.recurso_autores VALUES (79, 45);
INSERT INTO public.recurso_autores VALUES (80, 50);
INSERT INTO public.recurso_autores VALUES (80, 51);
INSERT INTO public.recurso_autores VALUES (80, 57);
INSERT INTO public.recurso_autores VALUES (81, 42);
INSERT INTO public.recurso_autores VALUES (81, 43);
INSERT INTO public.recurso_autores VALUES (81, 44);
INSERT INTO public.recurso_autores VALUES (81, 45);
INSERT INTO public.recurso_autores VALUES (82, 58);
INSERT INTO public.recurso_autores VALUES (82, 59);
INSERT INTO public.recurso_autores VALUES (82, 60);
INSERT INTO public.recurso_autores VALUES (82, 61);
INSERT INTO public.recurso_autores VALUES (83, 62);
INSERT INTO public.recurso_autores VALUES (83, 63);
INSERT INTO public.recurso_autores VALUES (83, 64);
INSERT INTO public.recurso_autores VALUES (83, 65);
INSERT INTO public.recurso_autores VALUES (84, 66);
INSERT INTO public.recurso_autores VALUES (84, 67);
INSERT INTO public.recurso_autores VALUES (84, 68);
INSERT INTO public.recurso_autores VALUES (85, 55);
INSERT INTO public.recurso_autores VALUES (86, 55);
INSERT INTO public.recurso_autores VALUES (87, 55);
INSERT INTO public.recurso_autores VALUES (88, 69);
INSERT INTO public.recurso_autores VALUES (88, 70);
INSERT INTO public.recurso_autores VALUES (89, 34);
INSERT INTO public.recurso_autores VALUES (89, 71);
INSERT INTO public.recurso_autores VALUES (89, 72);
INSERT INTO public.recurso_autores VALUES (89, 73);
INSERT INTO public.recurso_autores VALUES (90, 58);
INSERT INTO public.recurso_autores VALUES (90, 59);
INSERT INTO public.recurso_autores VALUES (90, 60);
INSERT INTO public.recurso_autores VALUES (90, 61);
INSERT INTO public.recurso_autores VALUES (91, 74);
INSERT INTO public.recurso_autores VALUES (91, 75);
INSERT INTO public.recurso_autores VALUES (91, 76);
INSERT INTO public.recurso_autores VALUES (91, 77);
INSERT INTO public.recurso_autores VALUES (92, 62);
INSERT INTO public.recurso_autores VALUES (92, 63);
INSERT INTO public.recurso_autores VALUES (92, 64);
INSERT INTO public.recurso_autores VALUES (92, 65);
INSERT INTO public.recurso_autores VALUES (93, 74);
INSERT INTO public.recurso_autores VALUES (93, 75);
INSERT INTO public.recurso_autores VALUES (93, 76);
INSERT INTO public.recurso_autores VALUES (93, 77);
INSERT INTO public.recurso_autores VALUES (94, 52);
INSERT INTO public.recurso_autores VALUES (94, 53);
INSERT INTO public.recurso_autores VALUES (97, 74);
INSERT INTO public.recurso_autores VALUES (97, 75);
INSERT INTO public.recurso_autores VALUES (97, 76);
INSERT INTO public.recurso_autores VALUES (97, 77);
INSERT INTO public.recurso_autores VALUES (99, 42);
INSERT INTO public.recurso_autores VALUES (99, 43);
INSERT INTO public.recurso_autores VALUES (99, 44);
INSERT INTO public.recurso_autores VALUES (99, 45);
INSERT INTO public.recurso_autores VALUES (100, 78);
INSERT INTO public.recurso_autores VALUES (108, 42);
INSERT INTO public.recurso_autores VALUES (108, 43);
INSERT INTO public.recurso_autores VALUES (108, 44);
INSERT INTO public.recurso_autores VALUES (108, 45);
INSERT INTO public.recurso_autores VALUES (109, 50);
INSERT INTO public.recurso_autores VALUES (109, 51);
INSERT INTO public.recurso_autores VALUES (109, 57);
INSERT INTO public.recurso_autores VALUES (110, 42);
INSERT INTO public.recurso_autores VALUES (110, 43);
INSERT INTO public.recurso_autores VALUES (110, 44);
INSERT INTO public.recurso_autores VALUES (110, 45);
INSERT INTO public.recurso_autores VALUES (111, 82);
INSERT INTO public.recurso_autores VALUES (111, 83);
INSERT INTO public.recurso_autores VALUES (111, 84);
INSERT INTO public.recurso_autores VALUES (112, 52);
INSERT INTO public.recurso_autores VALUES (112, 53);
INSERT INTO public.recurso_autores VALUES (113, 42);
INSERT INTO public.recurso_autores VALUES (113, 43);
INSERT INTO public.recurso_autores VALUES (113, 44);
INSERT INTO public.recurso_autores VALUES (113, 45);
INSERT INTO public.recurso_autores VALUES (114, 82);
INSERT INTO public.recurso_autores VALUES (114, 83);
INSERT INTO public.recurso_autores VALUES (114, 84);
INSERT INTO public.recurso_autores VALUES (116, 62);
INSERT INTO public.recurso_autores VALUES (116, 63);
INSERT INTO public.recurso_autores VALUES (116, 64);
INSERT INTO public.recurso_autores VALUES (116, 65);
INSERT INTO public.recurso_autores VALUES (117, 46);
INSERT INTO public.recurso_autores VALUES (144, 92);
INSERT INTO public.recurso_autores VALUES (144, 96);
INSERT INTO public.recurso_autores VALUES (144, 97);
INSERT INTO public.recurso_autores VALUES (144, 102);
INSERT INTO public.recurso_autores VALUES (146, 103);
INSERT INTO public.recurso_autores VALUES (146, 104);
INSERT INTO public.recurso_autores VALUES (146, 105);
INSERT INTO public.recurso_autores VALUES (127, 42);
INSERT INTO public.recurso_autores VALUES (127, 88);
INSERT INTO public.recurso_autores VALUES (127, 73);
INSERT INTO public.recurso_autores VALUES (128, 34);
INSERT INTO public.recurso_autores VALUES (128, 42);
INSERT INTO public.recurso_autores VALUES (132, 42);
INSERT INTO public.recurso_autores VALUES (132, 43);
INSERT INTO public.recurso_autores VALUES (132, 44);
INSERT INTO public.recurso_autores VALUES (132, 45);
INSERT INTO public.recurso_autores VALUES (122, 57);
INSERT INTO public.recurso_autores VALUES (122, 58);
INSERT INTO public.recurso_autores VALUES (122, 59);
INSERT INTO public.recurso_autores VALUES (122, 60);
INSERT INTO public.recurso_autores VALUES (121, 53);
INSERT INTO public.recurso_autores VALUES (121, 54);
INSERT INTO public.recurso_autores VALUES (121, 55);
INSERT INTO public.recurso_autores VALUES (121, 56);
INSERT INTO public.recurso_autores VALUES (120, 49);
INSERT INTO public.recurso_autores VALUES (120, 50);
INSERT INTO public.recurso_autores VALUES (120, 51);
INSERT INTO public.recurso_autores VALUES (120, 52);
INSERT INTO public.recurso_autores VALUES (119, 47);
INSERT INTO public.recurso_autores VALUES (119, 48);
INSERT INTO public.recurso_autores VALUES (118, 44);
INSERT INTO public.recurso_autores VALUES (143, 13);
INSERT INTO public.recurso_autores VALUES (149, 50);
INSERT INTO public.recurso_autores VALUES (149, 51);
INSERT INTO public.recurso_autores VALUES (149, 57);
INSERT INTO public.recurso_autores VALUES (150, 13);
INSERT INTO public.recurso_autores VALUES (151, 31);
INSERT INTO public.recurso_autores VALUES (147, 52);
INSERT INTO public.recurso_autores VALUES (147, 53);
INSERT INTO public.recurso_autores VALUES (49, 45);
INSERT INTO public.recurso_autores VALUES (49, 34);
INSERT INTO public.recurso_autores VALUES (156, 82);


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

INSERT INTO public.recurso_clasificaciones VALUES (50, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (51, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (52, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (57, 9, 17);
INSERT INTO public.recurso_clasificaciones VALUES (58, 7, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (59, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (69, 8, 14);
INSERT INTO public.recurso_clasificaciones VALUES (72, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (78, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (79, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (80, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (81, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (82, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (83, 8, 12);
INSERT INTO public.recurso_clasificaciones VALUES (84, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (85, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (86, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (87, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (88, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (89, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (90, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (91, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (92, 8, 12);
INSERT INTO public.recurso_clasificaciones VALUES (93, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (94, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (97, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (99, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (100, 9, 17);
INSERT INTO public.recurso_clasificaciones VALUES (108, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (109, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (110, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (111, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (112, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (113, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (114, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (116, 8, 12);
INSERT INTO public.recurso_clasificaciones VALUES (117, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (127, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (128, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (132, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (148, 9, 17);
INSERT INTO public.recurso_clasificaciones VALUES (149, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (147, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (49, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (158, 7, NULL);


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
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recursos VALUES (1, 'Sistema de Reconocimiento Biométrico Facial para Comedor Universitario', 1, 2026, NULL);
INSERT INTO public.recursos VALUES (2, 'Prototipo de Cerradura Digital con Matriz de Teclado y Arduino', 1, 2025, NULL);
INSERT INTO public.recursos VALUES (3, 'Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos', 2, 2026, NULL);
INSERT INTO public.recursos VALUES (4, 'Impacto del Cambio Climático en Trujillo - Parte 8', 2, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (5, 'Simulación de Cargas Estáticas en Puentes - Parte 7', 2, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (6, 'Big Data en Finanzas Institucionales - Parte 9', 1, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (7, 'Optimización de CPU en Servidores Locales - Parte 7', 1, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (8, 'Sistemas de Riego Automatizado - Parte 5', 1, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (9, 'Bioinformática y Análisis de ADN - Parte 5', 3, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (10, 'Inteligencia Artificial en Diagnóstico Médico - Parte 6', 2, 2025, 'dummy.pdf');
INSERT INTO public.recursos VALUES (11, 'Robótica Educativa para Escuelas - Parte 5', 3, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (12, 'Software Libre para Bibliotecas - Parte 1', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (13, 'E-Learning para Zonas Desfavorecidas - Parte 1', 3, 2018, 'dummy.pdf');
INSERT INTO public.recursos VALUES (14, 'Telecomunicaciones de Fibra Óptica Rural - Parte 1', 2, 2022, 'dummy.pdf');
INSERT INTO public.recursos VALUES (15, 'Criptografía Cuántica Post-RSA - Parte 2', 1, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (16, 'Criptografía Cuántica Post-RSA - Parte 7', 3, 2026, 'dummy.pdf');
INSERT INTO public.recursos VALUES (17, 'Criptografía Cuántica Post-RSA - Parte 5', 1, 2024, 'dummy.pdf');
INSERT INTO public.recursos VALUES (18, 'Criptografía Cuántica Post-RSA - Parte 8', 1, 2021, 'dummy.pdf');
INSERT INTO public.recursos VALUES (19, 'Software Libre para Bibliotecas - Parte 6', 1, 2023, 'dummy.pdf');
INSERT INTO public.recursos VALUES (20, 'Inteligencia Artificial en Diagnóstico Médico - Parte 1', 3, 2020, 'dummy.pdf');
INSERT INTO public.recursos VALUES (45, 'Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri', 1, 2026, 'motor_rust_tauri_v1.pdf');
INSERT INTO public.recursos VALUES (46, 'Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80', 1, 2025, 'juego_aislamiento_tic80.pdf');
INSERT INTO public.recursos VALUES (47, 'Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478', 1, 2026, 'restauracion_pentium4.pdf');
INSERT INTO public.recursos VALUES (48, 'Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro', 1, 2026, 'microkernel_php_routing.pdf');
INSERT INTO public.recursos VALUES (50, 'Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal', 1, 2026, 'software_educativo_algebra.pdf');
INSERT INTO public.recursos VALUES (51, 'Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas', 1, 2025, 'plataforma_web_citas.pdf');
INSERT INTO public.recursos VALUES (52, 'Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas', 1, 2026, 'simulador_routing_topologias.pdf');
INSERT INTO public.recursos VALUES (57, 'hola adios', 1, 2026, 'documentos/pst/pst_hola_adios_1783290093.pdf');
INSERT INTO public.recursos VALUES (58, 'Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1783396914.pdf');
INSERT INTO public.recursos VALUES (59, 'SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ', 1, 2026, 'documentos/pst/pst_sistema_de_optimizaci__n_basad_1785849778.pdf');
INSERT INTO public.recursos VALUES (69, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .', 1, 2023, 'documentos/pst/pst_nues_dr__pablo_viloria_____la__1785851934.pdf');
INSERT INTO public.recursos VALUES (72, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.', 1, 2026, 'documentos/pst/pst_sistema_integral_de_gesti__n_c_1785852671.pdf');
INSERT INTO public.recursos VALUES (78, 'PST Prueba Carga por Lotes - 20260805134326', 1, 2026, 'documentos/pst/pst_pst_prueba_carga_por_lotes___2_1785937406.pdf');
INSERT INTO public.recursos VALUES (79, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937515.pdf');
INSERT INTO public.recursos VALUES (80, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .', 1, 2023, 'documentos/pst/pst_nues_dr__pablo_viloria_____la__1785937685.pdf');
INSERT INTO public.recursos VALUES (81, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937685.pdf');
INSERT INTO public.recursos VALUES (82, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 'documentos/pst/pst_optimizaci__n_del_sistema_de_i_1785937685.pdf');
INSERT INTO public.recursos VALUES (83, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 'documentos/pst/pst_sistema_inteligente_para_la_ge_1785937685.pdf');
INSERT INTO public.recursos VALUES (84, 'SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS', 1, 2023, 'documentos/pst/pst_soporte_tecnico_a_equipos_y_us_1785937686.pdf');
INSERT INTO public.recursos VALUES (85, 'PST Prueba Duplicados - 20260805135642', 1, 2026, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785938202.pdf');
INSERT INTO public.recursos VALUES (86, 'PST Prueba Duplicados - 20260805140204', 1, 2026, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785938524.pdf');
INSERT INTO public.recursos VALUES (87, 'PST Prueba Duplicados - 20260805143446', 1, 2026, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785940486.pdf');
INSERT INTO public.recursos VALUES (88, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC', 1, 2021, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1786372014_343.docx');
INSERT INTO public.recursos VALUES (89, 'MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ', 1, 2026, 'storage/documentos/pst/pst_m__dulo_inteligente_basado_en__1786372449_773.docx');
INSERT INTO public.recursos VALUES (90, 'OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, NULL);
INSERT INTO public.recursos VALUES (91, 'Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786373559_627.docx');
INSERT INTO public.recursos VALUES (94, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1786376454_286.docx');
INSERT INTO public.recursos VALUES (93, 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786376074_943.docx');
INSERT INTO public.recursos VALUES (92, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1786376037_906.docx');
INSERT INTO public.recursos VALUES (112, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1787698715_771.docx');
INSERT INTO public.recursos VALUES (97, 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786377809_260.docx');
INSERT INTO public.recursos VALUES (99, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378254_697.docx');
INSERT INTO public.recursos VALUES (100, 'il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas', 1, 2023, NULL);
INSERT INTO public.recursos VALUES (108, 'Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378813_891.docx');
INSERT INTO public.recursos VALUES (109, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”', 1, 2023, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1786457302_317.pdf');
INSERT INTO public.recursos VALUES (110, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787698529_393.docx');
INSERT INTO public.recursos VALUES (111, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787698700_582.pdf');
INSERT INTO public.recursos VALUES (113, 'Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787836105_952.docx');
INSERT INTO public.recursos VALUES (114, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787840266_406.pdf');
INSERT INTO public.recursos VALUES (116, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 'storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1788184014_102.docx');
INSERT INTO public.recursos VALUES (117, 'SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ', 1, 2026, 'PST 4 David LidmarFinal.docx');
INSERT INTO public.recursos VALUES (127, 'ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG', 1, 2026, 'storage/documentos/pst/pst_actividades_acreditables_iv_in_1788376198_636.docx');
INSERT INTO public.recursos VALUES (128, 'Materia: Seguridad Informática', 1, 2026, 'storage/documentos/pst/pst_materia__seguridad_inform__tic_1788384113_508.docx');
INSERT INTO public.recursos VALUES (132, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1788532202_175.docx');
INSERT INTO public.recursos VALUES (122, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');
INSERT INTO public.recursos VALUES (121, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (120, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (119, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (118, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');
INSERT INTO public.recursos VALUES (143, 'Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124890/98825');
INSERT INTO public.recursos VALUES (146, 'Modelamiento de confort adaptativo para un trapiche panelero', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/112625/91645');
INSERT INTO public.recursos VALUES (144, 'Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124428/98826');
INSERT INTO public.recursos VALUES (148, 'VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...', 1, 2023, 'storage/documentos/pst/pst_valera_edo_trujillo_aplicaci___1789088548_770.docx');
INSERT INTO public.recursos VALUES (149, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO', 1, 2023, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1789088547_631.pdf');
INSERT INTO public.recursos VALUES (150, 'e', 3, 2026, 'https://www.youtube.com/');
INSERT INTO public.recursos VALUES (151, 'e', 3, 2026, 'https://www.wikipedia.org/');
INSERT INTO public.recursos VALUES (147, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1789794852.pdf');
INSERT INTO public.recursos VALUES (49, 'Sistema de Información Automatizado para la Gestión de Inventario y Suministros Médicos', 1, 2026, 'proyecto_inventario_medico.pdf');
INSERT INTO public.recursos VALUES (156, 'e', 3, 2026, 'https://www.wikipedia.org/');
INSERT INTO public.recursos VALUES (158, 'PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO', 1, 2026, NULL);


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);
INSERT INTO public.registro_actividad VALUES (3, 12, NULL, '2026-09-19 14:19:44.068925', '2026-09-19 14:20:25.834661', 2);
INSERT INTO public.registro_actividad VALUES (4, 17, NULL, '2026-09-19 14:57:43.371674', '2026-09-19 14:58:00.847148', 2);
INSERT INTO public.registro_actividad VALUES (2, 7, NULL, '2026-09-19 13:23:18.589777', '2026-09-24 23:35:11.511771', 22);


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


--
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.telemetria_cache VALUES (1, '{"timestamp": 1790316712, "storage_mb": 14.54, "files_count": 62}');


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


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios VALUES (2, 'lando', 'lando@gmail.com', '22222222', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 2, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (3, 'miki', 'miki@gmail.com', '33333333', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (4, 'ale', 'ale@yaju.com', '44444444', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (5, 'bibi', 'bibi@gmail.com', '4444111', NULL, 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (8, 'Yisu Monte', 'yisu@gmail.com', '30866991', '$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (9, 'Pedro Perez', 'iaiaia@gmail.com', '4123123', '$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (11, 'Juan', '123@gmail.com', '1234', '$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (7, 'Miguel González', 'erwazaaaa@gmail.com', '32621284', '$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (6, 'Piñin Piña', 'pina@hotmail.com', '1', '$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a', 4, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (10, 'Wazaaaa', 'wazaaa@gmail.com', '123', '$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O', 4, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (1, 'Adrus', 'andru@gmail.com', '11111111', '$2y$10$1sBy413YpJ9MQGlRt/g6y.OGkfno7aRuKxShONKSeWrvhQNS53YDO', 1, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (14, 'Sixsevenaldo González', '676767@gmail.com', '67', '$2y$10$XIdtQdP6d.bZSGCINdEbIuTtuqee9E3EEKYp5Ogbm/I2JW5XQbP/O', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (13, 'Cepillíno', 'cepillin@gmail.com', '80', '$2y$10$ML6M4RYmR0f2yCoxOpRvaONIE/nUvwOmcsmGySeBOOBGjy9xUmbk6', 3, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (16, 'no soy miguel', 'orlando5711666@gmail.com', '1234567', '$2y$10$g1LIDvziBpq3KrEilAhp3.RC1ziliq9hOpkdhWFISEjtJ.Y00SUHS', 3, true, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (12, '[Archivado] ANDRUS', 'andrusramirez2020@gmail.com_deleted_1789844158', '30469331_x9844158', '$2y$10$Vii.OSXjYhxOk.Xq.dx2EODQb3U9cahqa401C48PNPR3mlXdi85Ii', 2, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (15, '[Archivado] DIOSs', 'DIOS@gmail.com_deleted_1789845222', '12000000_x9845222', '$2y$10$eaICZWMAWr11BS/RL0ju0O49pEw.3lQIpEGrLoe6FweXprGxkjttu', 1, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (17, 'adrusss', 'andrusramirez2020@gmail.com', '30469331', '$2y$10$SFowO4NOxSgKqx35qYr7iOiJU2PJ6hJ.uTO2zdxSSXMQjX64sRwiu', 2, true, NULL, NULL, NULL, true, NULL);


--
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: waf_rate_limiter; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, true);


--
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 321, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 105, true);


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

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 3, true);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 11, true);


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

SELECT pg_catalog.setval('public.recursos_id_seq', 158, true);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 4, true);


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
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 40, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 17, true);


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
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- Name: idx_detalles_vector_hnsw; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');


--
-- Name: idx_detalles_vector_null; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_vector_null ON public.detalles_proyectos USING btree (id_recurso) WHERE (vector_semantico IS NULL);


--
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


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
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


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
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict hqahC2YFJNzxtbzqQGlj8szXTc2zQyHRH4ydnvCrVZDpFriwNScgWFV7HZlhwrk

