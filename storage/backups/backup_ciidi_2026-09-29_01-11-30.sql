--
-- PostgreSQL database dump
--

\restrict wiVplGxYX8Hb8XPUxbZktrORQcXChw2izVAFlXxQU2hUnCeX4hDlkBT6lS3tU4E

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
DROP INDEX IF EXISTS public.idx_detalles_vector_null;
DROP INDEX IF EXISTS public.idx_detalles_vector_hnsw;
DROP INDEX IF EXISTS public.idx_detalles_proyectos_trayecto;
DROP INDEX IF EXISTS public.idx_detalles_inv_ofertada;
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
    url_repositorio text,
    obj_general text,
    activo boolean DEFAULT true,
    vector_semantico public.vector(384),
    id_trayecto integer
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
INSERT INTO public.autores VALUES (106, 'Analy De Los Angeles Hernández Cortéz', 'V-30601065');
INSERT INTO public.autores VALUES (119, 'JULIE ANDREA SARMIENTO FORERO', 'V-30877998');
INSERT INTO public.autores VALUES (120, 'MARIO ALEXANDER ROMERO RODRIGUEZ', 'V-30123654');
INSERT INTO public.autores VALUES (121, 'Fabián Eduardo Alcoser Cantuña', 'V-20545874');
INSERT INTO public.autores VALUES (122, 'Andrés Leal', 'V-12542222');


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

INSERT INTO public.detalles_proyectos VALUES (173, '2026-09-29', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”. Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr. Pablo Viloria”, coordinación de investigación del programa nacional', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP.', '2026-09-28 21:42:57.178804', NULL, 'https://github.com/Zhailox/proyect_CIIDI', 'Desarrollar un Sistema Integral de Gestión Documentos Académicos, basado en una arquitectura modular, para la automatización de la búsqueda híbrida de información y la centralización de recursos académicos en beneficio de la comunidad del PNF en Informática.', true, '[-0.05925069,-0.03747498,-0.06554397,-0.06347278,0.01856328,0.04152989,0.0537748,0.10685626,0.05460724,0.03196778,0.08279351,0.06442103,0.0142616,0.00074173,-0.1356299,-0.01493108,-0.05580725,0.00164277,-0.02545193,-0.01468711,0.0132404,0.01598015,0.07330319,-0.04391611,-0.01787273,0.02037034,0.03482321,-0.01704266,0.00341587,0.0130463,0.06558905,-0.06145759,0.0131649,0.08380697,-0.01494171,0.04393532,-0.08256437,-0.10029587,0.02182062,0.07503075,0.06523212,-0.00610734,0.03320325,0.08640354,0.08585677,0.06037056,-0.0227181,-0.0160492,-0.04371213,0.01633237,0.02850386,0.00413208,0.03778186,-0.01648951,-0.01617862,0.10706628,-0.07986463,-0.04913698,-0.07885513,0.02256624,0.03681551,0.04616364,-0.04254428,-0.05501775,0.01100807,0.11584439,-0.00816853,-0.05767767,-0.0091198,-0.05316711,0.02103463,0.05223362,-0.00291944,-0.12071767,-0.00130954,0.03160649,-0.02734312,0.04127143,0.01108991,-0.12584203,0.10424983,0.07652346,0.04712555,0.00176828,0.11890375,-0.02383991,-0.01895283,0.06245203,-0.00729361,0.00917774,0.04305562,-0.06614544,0.00908483,0.02611432,-0.04885595,-0.02181679,-0.00105993,-0.03111089,-0.05799797,0.01686539,-0.00727856,-0.00139628,0.03950931,-0.05493698,-0.06238848,-0.05383612,0.00963783,0.02148438,0.03785872,0.00315765,-0.08622456,0.004164,-0.03752858,-0.0768624,-0.00908454,0.06617588,-0.02461114,0.01082828,-0.0224869,-0.07010108,-0.05430985,-0.00608438,-0.13605556,0.01129745,-0.02149056,-0.08430939,-0.05646142,3.21e-06,-0.07987109,-0.01602009,-0.00933469,-0.00408662,-0.02661944,0.01917714,0.00905929,-0.12045219,0.05354386,-0.05263241,-0.04802469,0.08581738,-0.09023448,0.05450477,-0.06882538,-0.04295063,-0.0183707,-0.00357692,0.05667996,-0.0118025,0.0424695,-0.05078433,0.0540972,-0.00390886,0.07288367,0.00875139,-0.06223964,-0.00147281,-0.03561651,-0.00920601,-0.01965643,-0.01123198,-0.01697858,-0.0821374,-0.03854227,-0.04664788,-0.0027558,-0.00404482,-0.00727181,0.01521501,-0.01659469,0.04641366,0.02330182,-0.04872586,0.04125556,-0.00652021,-0.00590071,0.0688835,0.04427982,-0.02817639,0.00508187,0.0173254,-0.0125411,-0.00736206,0.07285192,-0.05632783,-0.06329135,0.07969405,-0.0012272,-0.08634257,0.10870047,0.01329678,-0.05255423,-0.00413915,-0.01895038,0.04435974,0.01566249,-0.01518437,0.09163383,-0.06740365,-0.00181401,0.00769065,-0.06145931,0.01257897,-0.05515214,1.691e-05,-0.02480186,-0.00196144,-0.00609902,-0.03468498,-0.082813,-0.03834114,0.00720314,0.01007346,0.04163549,0.01352953,0.07415434,0.04811692,-0.04825305,-0.13594282,0.05315195,0.02716414,0.02636825,-0.02861826,0.03203708,9.79e-06,-0.02460735,-0.06887244,-0.09604268,-0.02112674,-0.03868303,0.074514,0.05378978,0.01698826,-0.01614857,0.01324594,-0.00913139,0.03950855,-0.00121858,-0.11889488,0.07318352,-0.02479185,-0.08955578,0.14931802,0.0633479,0.0272713,-0.02892708,0.06855977,0.05213298,0.02930061,0.0362479,0.02327998,-0.00900855,0.08549445,-0.09212456,-0.0062773,-0.06393681,-0.02988164,-0.06656531,0.00189788,-0.06680977,-0.08173899,0.13036403,0.04231716,-0.09248655,-0.01629009,0.0962046,0.02152197,0.00771599,-0.02351502,-0.02283159,0.00957705,-0.11102535,-0.06420129,-0.0344277,-0.01683184,0.00518135,0.01580001,0.03648629,-0.06483451,-0.00697533,0.10704096,-0.01780988,-0.01667721,0.00116734,-0.02532095,-0.03949364,-0.01413431,-0.08801998,0.04969864,0.03610639,-0.02721713,-0.01111617,0.08395676,0.04666319,-0.0330354,0.06296749,0.10614739,0.03325656,0.00139509,0.00617149,0.00879517,0.0330602,0.06298579,0.00239869,-0.00572088,-0.01463202,-0.00524447,-0.02678896,0.01367217,0.02317831,0.04104974,0.073473,-0.05941937,-0.00329942,-0.08513019,0.01286764,0.04468957,0.01944439,0.00305925,-0.02291579,1.11e-06,-0.01570778,0.03718475,-0.05531326,-0.11503417,-0.00422938,-0.04732766,-0.02332506,0.00737748,-0.05463131,-0.01339876,0.01847539,-0.06700006,-0.01225672,-0.00934864,-0.0389382,0.00673474,0.01025173,0.08636673,-0.01835872,-0.07791513,0.04298014,0.01676848,-0.00817483,0.03374759,0.01188091,-0.00127944,-0.04114695,-0.04888705,-0.04104182,0.03008013,-0.00319994,-0.00567959,0.09543272,0.05643448,0.04699716,0.06550608,0.10699536,-0.02390888,-0.06485444,0.0025374,-0.0531951,-0.05711509,0.04206511,-0.00375201,0.03024956,-0.06744281,0.08687718,-0.04080023,0.09775816,0.03818337,0.06504229,0.0510898,-0.04361027,-0.02236276,-0.0624283,-0.00970752,0.01476809,0.02779145,-0.06446843,-0.01884619,0.02751795,-0.04763771,-0.0361749,0.04219323]', 4);
INSERT INTO public.detalles_proyectos VALUES (176, '2026-09-29', 'Pregrado', 'Proyecto sociotecnológico orientado al diseño y desarrollo de un sistema integral para Smartphone World C.A., compuesto por un módulo de gestión local —inventario, catálogo y reportes— y una tienda virtual de comercio electrónico, interconectados mediante una base de datos centralizada en la nube. La solución busca automatizar los procesos internos de inventario y ventas, reducir errores manuales, ampliar el alcance comercial de la empresa hacia el entorno digital y mejorar la experiencia de compra. Metodológicamente se enmarca en la Investigación-Acción Participativa (IAP) y la metodología ágil Kanban.', 1, 'Smarthphone World C.A. Valera, Trujillo', 'Sistemas de información; sistemas de información web; comercio electrónico; tienda virtual; gestión de inventario; desarrollo de aplicaciones web; base de datos centralizada; Smartphone World C.A.; Investigación-Acción Participativa; Kanban.', '2026-09-28 21:42:57.36464', NULL, NULL, 'Desarrollar un Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A., compuesto por un módulo de gestión local y una plataforma de comercio electrónico interconectados mediante una base de datos centralizada en la nube, con el fin de automatizar los procesos internos de inventario y ventas, y ampliar el alcance comercial de la empresa hacia el entorno digital.', true, '[0.01985952,0.07094203,-0.00238552,-0.13057585,0.02186875,-0.07937945,-0.05770198,0.04006172,-0.03605566,-0.02028793,0.15849763,-0.06105105,0.09173891,0.00743215,-0.00948643,-0.04883767,-0.02581971,-0.00500094,-0.00586483,0.09139819,0.01439474,0.02723359,0.03407835,0.0063564,0.03630525,-0.03930284,0.03303406,0.04511886,0.05106106,0.03126247,-0.00940637,0.0710755,-0.04375213,0.13838525,-0.03411234,-0.04955023,0.01102093,-0.05177833,-0.05231319,0.07070531,0.02875373,-0.02593235,0.03329957,0.00311921,-0.00206398,0.05054624,-0.0159033,-0.04053365,-0.08961645,-0.03454463,0.01988423,0.09558524,-0.00844969,0.01800124,-0.08293319,0.06372949,0.00744224,0.02505033,0.06279967,0.06931238,0.0844133,0.03886079,-0.03570087,-0.01423552,-0.0118013,0.01845861,-0.02132646,-0.00731776,0.04842911,-0.11408522,-0.00398291,-0.013698,0.03006148,-0.10031274,-0.04548948,0.03851598,-0.02080034,0.03517241,-0.01313011,-0.03700897,0.08063376,0.09052906,-0.00987269,0.06242925,0.06857232,-0.02178504,-0.07475212,0.07336083,0.00262275,-0.0374136,0.04157386,0.00810852,0.00160433,-0.01242881,0.01502916,0.03926325,0.00154229,-0.09771783,-0.05022117,0.02456313,0.01584313,-0.04959845,0.02961146,0.07457279,-0.01134913,0.00985592,-0.02798175,0.03459016,-0.00194146,0.00820839,-0.05081394,-0.02388711,0.01724218,-0.1335504,-0.08270342,0.06566998,0.07068728,0.07892974,0.0079365,-0.10344265,0.04471106,-0.05894118,-0.08229674,-0.06825164,0.03360102,-0.04787346,0.04312672,3.97e-06,-0.02247333,-0.02813695,0.00864426,0.0068661,0.0189039,0.00885051,-0.00865803,-0.00553141,0.00480288,-0.0743081,-0.00622663,0.02511503,-0.09736291,0.08719354,-0.00875973,-0.01233883,0.01533401,-0.08088913,0.02999764,0.03920232,-0.02767492,-0.06196172,0.08064747,-0.05068445,0.01949975,0.03085676,-0.00303399,-0.02810719,-0.00860167,0.02150979,0.05659482,-0.08795424,0.03024996,-0.01512152,-0.02450174,0.01478627,0.04887046,-0.01280913,-0.00316276,-0.00824196,-0.0139415,0.05304047,-0.05247531,0.00742833,0.04098904,-0.01990521,0.04692157,0.02572218,0.04677166,0.05021885,-0.01691481,-0.04510014,-0.13433573,-0.05561732,0.03649594,-0.01896187,0.02287414,0.06841137,-0.01527845,-0.01686777,-0.01277392,-0.0015382,-0.01980315,0.02649338,-0.00614031,-0.04189514,0.01753094,-0.07592851,0.0423334,0.03151833,-0.02348261,0.00764136,-0.02848372,0.06288379,-0.02466555,0.07324744,-0.07302298,0.03771569,-0.03008668,-0.01467534,-0.01442482,0.00462096,0.00765321,0.02284584,0.11377436,-0.04800183,-0.01886943,-0.07619259,-0.03692247,0.01136318,-0.05339911,0.02218387,0.03576334,0.04313724,-0.07829408,1.169e-05,-0.07187838,-0.03072275,-0.02833736,-0.028606,-0.02962481,-0.04862327,-0.05558837,-0.05456586,-0.07976109,0.07180777,0.0088289,0.03060526,0.08717507,-0.01491491,0.09332639,0.06145817,0.07501326,-0.00078807,-0.01726626,0.00441943,-0.02264565,0.04007063,0.05864647,-0.05647147,-0.00496021,-0.03296679,0.03521948,0.07275796,-0.03507761,0.02486666,-0.087371,-0.08936271,-0.05218089,0.0212646,0.04328981,-0.01704784,0.01342276,0.02677582,-0.01727928,-0.10895798,-0.00846797,-0.04610382,0.01755029,-0.06055957,-0.03124395,-0.01639051,-0.11341113,-0.03139392,-0.00395783,0.0590184,0.10302309,0.01243642,0.04897141,-0.02837964,-0.01788973,0.07562264,-0.02838806,-0.01372353,-0.04848669,-0.114097,0.11702261,-0.0269299,-0.07550073,-0.05475292,-0.03305218,0.03315384,0.00062401,0.04243458,-0.01308942,-0.01951094,0.01483972,0.05313,-0.03064657,-0.07927723,-0.03110083,0.03714048,-0.02170588,-0.00608075,0.03776922,-0.00978928,-0.03781902,0.05881948,0.0070434,-0.17028491,0.03742952,-0.00935042,0.0175951,0.06594529,-0.03059327,-0.00594868,-0.04997423,0.06097094,-0.1000648,0.00117198,-0.05116299,1.26e-06,-0.02660885,0.01080954,-0.06723181,-0.06845967,-0.02371018,-0.05115265,0.02566839,-0.04204132,0.08563372,0.07003161,0.05805737,-0.0701346,-0.03398318,0.06604284,-0.01638473,-0.02422986,0.01930934,0.06067912,-0.04543848,-0.015459,0.11434397,0.02815625,0.02165024,-0.02013673,0.03292522,-0.03155369,-0.04574576,-0.04187682,0.0081836,-0.00910023,0.01497305,-0.0438952,-0.02107744,-0.01111458,-0.0910754,0.06658481,-0.03032783,-0.03978919,0.06370669,-0.0372717,0.06718545,-0.09390166,0.03755091,0.04505807,-0.06746311,-0.10340935,0.09142085,-0.017073,0.06813696,0.11867006,0.02558627,-0.0422597,-0.02415622,-0.06736809,0.02007192,-0.0524175,0.09062227,-0.02153895,0.0092981,0.0275318,-0.05039734,-0.00524687,-0.0038743,0.06574797]', 1);
INSERT INTO public.detalles_proyectos VALUES (178, '2026-09-29', 'Pregrado', 'Las necesidades de los usuarios de telefonía móvil demandan cada vez más servicios, razón por la cual surge J2ME como tecnología para el desarrollo de software en dispositivos móviles. El asentamiento de tecnologías como GPRS ha permitido que aumente la gama de aplicaciones capaces de comunicarse con máquinas o dispositivos remotos e incluso manipularlos. Gracias a J2ME, es posible ejecutar comandos en un servidor conectado a Internet desde un dispositivo móvil, aprovechando todas las ventajas que esta tecnología ofrece para cubrir tareas específicas.', 1, 'Comunidad / Organización No Específicamente Nombrada', 'J2ME, Aplicación cliente-servidor, Dispositivo móvil, GPRS, UML, TCP/IP', '2026-09-28 22:19:25.188154', NULL, NULL, 'Diseñar y crear un prototipo de una aplicación cliente-servidor que permita ejecutar comandos básicos en un servidor remoto desde un dispositivo móvil.', true, '[-0.14411172,-0.07656197,0.0316156,-0.10254632,-0.05970205,-0.05200336,0.03811654,0.12786329,-0.00308773,0.05547953,0.09975484,0.06069791,0.02623486,0.01552907,0.0309525,0.07768106,0.04164487,0.02194112,0.05717304,0.08585897,0.0713499,-0.04777392,0.00487409,0.02635883,-0.05258956,-0.00397196,0.04165561,0.09434447,0.02400699,-0.03235112,0.03715437,0.0440168,-0.05928416,0.00296134,0.00407411,0.02924866,0.02860614,-0.10172377,-0.08165596,-0.08630587,-0.03060255,0.00398586,0.01621167,-0.0217723,0.03465194,-0.03043022,0.0179234,0.06670744,-0.03473819,0.05267858,0.00809241,0.00739181,0.01209925,0.03400459,-0.03103615,-0.01524468,-0.11625828,0.08944007,0.09804538,-0.02498407,-0.02348454,0.10563625,-0.00560327,0.04512785,-0.03934575,0.04081038,-0.04794649,-0.09585241,0.0657681,0.06562302,0.00851641,-0.07247273,-0.0530991,-0.06281449,-0.08221548,0.0575718,0.06366432,0.00200407,-0.03479077,-0.00025029,0.06733267,-0.00537186,0.03608818,-0.00237356,-0.00077461,0.06362069,-0.06436147,0.01342253,0.1137919,0.02681873,0.04363522,-0.04008166,0.00229088,0.02810902,-0.05462565,-0.02071294,0.00770666,-0.01292661,-0.09611588,0.04693617,-0.04375708,-0.01768261,-0.01695613,0.07583249,-0.03162886,-0.01764802,0.00535505,-0.01529406,0.05021386,-0.01786907,0.02810487,-0.03789011,-0.00351556,-0.05262277,-0.02438016,-0.00313385,-0.09253575,-0.10154362,0.03882094,-0.02556379,-0.03873922,-0.04442443,-0.03191473,-0.04019117,0.05759445,0.00731019,0.05842003,5.16e-06,0.01296363,0.00230128,0.00929914,0.00931864,0.0376617,0.04097037,0.09807329,0.03195464,-0.01522632,-0.09319799,-0.10142142,0.03161025,-0.01151042,0.04505561,0.03673584,-0.16802712,0.07793753,-0.03633951,0.047608,0.03633842,-0.03429339,-0.00819379,0.030906,0.00224507,-0.02134527,0.07582116,-0.05830027,0.04705614,0.11082814,0.08034465,-0.01164591,-0.01380819,-0.03877738,-0.00527778,-0.0388572,0.02758804,-0.10626822,-0.07637806,-0.01559404,-0.03310996,-0.02243502,-0.03378304,-0.12943386,-0.00108664,-0.03784155,-0.1252548,-0.04867774,-0.02084466,-0.08805314,-0.08101647,-0.02672616,0.09823977,0.00442962,-0.06585339,-0.00428855,-0.01951663,-0.00083471,0.0840191,0.01373653,-0.01585525,-0.04077482,0.01712322,0.04780078,0.03675315,0.00433869,-0.0564935,0.04543832,0.00915781,-0.00210797,0.02670949,-0.04720426,0.02765473,0.00212877,-0.02701443,0.0692837,-3.666e-05,0.01902044,0.0340581,-0.00224985,-0.01078356,0.00053185,-0.03214681,-0.03258416,-0.02189952,0.01545293,-0.05513379,0.0289738,-0.02949107,-0.00874822,0.01079392,0.01491124,0.03749896,-0.09723324,0.03790506,0.03804268,1.482e-05,-0.1069427,0.03012667,0.02641542,0.03703307,-0.15527269,-0.02829145,0.00459867,0.01138862,-0.08904773,0.04851188,-0.0358733,-0.04487365,0.00787664,-0.05372848,-0.02109903,0.03590378,-0.00997019,0.00582477,0.00339367,-0.04966499,-0.00368024,0.03791912,0.03865308,-0.0355734,-0.02059108,-0.03859969,-0.00619378,0.05078894,0.01049858,-0.04376861,0.02895131,0.03396125,-0.00391386,0.04208191,-0.03545884,0.04584656,-0.00801378,0.08165034,0.04791304,-0.07586809,0.1210115,-0.02911595,0.043675,0.01100862,0.01902193,-0.01287237,-0.05104797,-0.02813277,0.06093609,0.00095991,-0.03058875,-0.00206699,0.07449313,0.00180794,0.01888278,0.00252675,0.04019767,-0.09870644,0.00367956,-0.02891515,0.09569001,-0.05077512,-0.03816073,0.01083905,0.03422169,0.03121095,0.00594292,0.04243821,0.04105137,-0.05885001,0.05457956,0.02226271,0.00570706,-0.04620186,0.03327371,-0.01199383,-0.04418682,-0.09565905,-0.00222605,-0.07800362,-0.00154287,-0.00427066,0.03633285,-0.03801296,-0.01796061,-0.05583903,0.01574171,0.05072046,-0.03014486,-0.00665587,0.00830986,-0.01957086,0.00373547,0.0365425,0.04489513,1.48e-06,-0.03983065,-0.07765532,-0.01619183,-0.07422596,-0.01131874,0.01175053,-0.04365688,-0.01985931,0.01234694,-0.01554488,0.01533581,-0.11963625,-0.11745199,0.10408915,8.488e-05,0.09815756,0.01300107,-0.01021176,-0.03987638,-0.00730808,0.01650366,-0.06857794,0.11804671,0.06077459,-0.03324298,0.02433682,-0.04137399,-0.01250232,-0.0435237,-0.01022594,-0.09550486,0.00759037,0.00073605,-0.02681029,-0.09694905,0.01702258,-0.07868572,-0.00852924,0.00399294,0.03354774,0.05404755,-0.06461538,-9.611e-05,0.03064282,-0.04677418,0.00592527,-0.01113991,-0.02446944,-0.06621522,-0.0162646,-0.03420937,-0.04123229,0.03227136,-0.09721491,-0.07962228,-0.0178196,0.04096628,-0.02680835,0.01580892,-0.08702017,0.04155762,0.05948621,0.08412029,-0.06256799]', 3);
INSERT INTO public.detalles_proyectos VALUES (179, '2026-09-29', 'Pregrado', 'Este proyecto se basa en la instalación y configuración de Servidores Internet (Web, DNS, Correo Electrónico y FTP Anónimo) para la Empresa de Telecomunicaciones de Nariño TELENARIÑO, utilizando los sistemas operativos Digital Unix y Linux. Incluye el diseño de una página web y el desarrollo de un Sistema de Administración de Usuarios (SAINTEL) con acceso dinámico a bases de datos empleando PostgreSQL y la interfaz PHP.', 1, 'EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO', 'Servidores Internet, Página Web, Base de Datos, Linux, Cliente-Servidor.', '2026-09-28 22:19:26.337196', NULL, NULL, 'Diseñar la Página Web para la empresa TELENARIÑO y configurar e implementar el servidor web HTTP, SERVIDOR DE NOMBRES DE DOMINIO DNS, CORREO ELECTRONICO, TRANSFERENCIA DE ARCHIVOS (FTP ANONIMO) y Listas de Correo Electrónico para el servicio de INTERNET TELENARIÑO utilizando Digital UNIX y Linux como sistemas operativos base.', true, '[-0.09203771,-0.04935906,0.0035797,-0.12417524,-0.07486805,-0.01758587,0.00169183,-0.01363514,-0.05722429,0.04323824,0.0298245,0.03856117,-0.00298095,-0.01558421,-0.04108659,-0.03699101,-0.06837983,-0.06748968,0.00713756,0.04903423,0.06647187,-0.03332555,-0.01965365,-0.04273701,-0.00253619,0.00233396,0.01184971,0.01851079,-0.02294335,0.01178862,0.04248783,0.02712162,0.02514481,0.02555915,-0.08987072,-0.04095366,0.01840709,-0.0260849,-0.06427911,-0.0026773,0.06279035,-0.05469756,-0.01192253,-0.01458376,0.05540394,-0.08015055,-0.0232726,0.05491513,-0.04552077,-0.07094121,-0.00777004,0.01990365,-0.00604409,0.00918066,-0.02373633,0.03019633,-0.11307805,-0.02907942,0.07577277,0.04303127,-0.01741545,0.05889915,0.08755114,-0.01493393,0.04731963,-0.00558064,0.06762194,0.01337729,0.02747086,0.00208819,-0.08359074,0.04756406,-0.00225292,0.01503452,-0.10100382,0.02405009,0.06555097,-0.01267061,-0.02292031,0.00945551,0.00471423,0.00397155,0.08168604,0.04352787,-0.00352504,-0.04905636,0.02173162,-0.04533914,-0.00442671,-0.00608516,-0.01382648,0.00717067,0.01327119,-0.03249365,-0.07635633,-0.0191486,-0.04995497,-0.05529353,-0.07805302,-0.0036287,-0.01071153,-0.03380282,0.07584166,-0.05175891,-0.1071903,-0.09781823,0.06448974,0.03166538,0.07441857,0.05121583,-0.06153802,-0.03462074,-0.05659573,-0.12717922,-0.13158536,0.031361,-0.08740171,-0.04696149,0.08058976,-0.03738944,-0.04946366,-0.04484335,-0.00999047,-0.00617394,0.06123118,0.02294724,0.10667582,3.62e-06,0.0189736,0.06619633,-0.00879442,-0.06162762,0.05770546,0.05262238,0.07318558,0.04833154,-0.07909339,-0.03267528,-0.10381703,0.05851141,-0.05948976,0.01497679,0.08481785,-0.03372995,-0.0398706,-0.03940414,0.06923363,-0.00979456,-0.00147816,0.07833464,0.07032387,0.04040794,0.07448897,0.0146892,-0.05914573,0.02911138,-0.01177742,0.01618825,0.10359948,-0.02608722,-0.00835189,0.05729843,0.09846074,-0.02013182,0.00309435,0.01228962,-0.00654781,0.00661002,-0.00426638,0.01773994,-0.03532397,0.03592682,-0.08542975,-0.04947244,-0.01907482,-0.10288486,0.01252378,0.01938246,-0.05996633,0.02548372,-0.0486367,0.05431288,0.1101666,-0.0264946,-0.05790978,0.01046391,0.0147935,-0.00491896,0.02031815,-0.11073762,0.010194,-0.04391983,0.01435984,-0.03912044,0.02702934,-0.00997966,0.05970621,-0.07261181,-0.04124497,0.04278942,0.09200536,0.08218414,-0.05261496,0.10441961,-0.09239176,0.00042233,-0.03961335,0.04832684,0.06032566,0.02604712,0.0371771,0.04083968,0.09652201,0.00606795,0.05592658,0.08154291,-0.01462527,0.00704173,-0.01411772,0.05487827,0.02655489,0.00735808,0.03063161,1.164e-05,-0.00653752,-0.01544763,-0.04216934,-0.02286453,-0.02060577,-0.01266372,0.05156666,-0.04358217,-0.01959907,-0.01077097,0.06693373,-0.0310205,0.07159777,-0.06824384,-0.05750894,0.02338098,0.04624728,-0.10535878,-0.04028122,-0.0960125,0.01367528,-0.01091251,-0.0041107,-0.03526305,0.04545685,-0.09682786,0.07183238,0.04834199,-0.03285601,-0.00396569,0.0013009,0.00332607,-0.03587178,0.07159564,-0.03195906,0.05464949,0.07337636,0.0784569,0.08203718,-0.00619486,0.05350644,-0.00220126,0.0198804,-0.03532276,-0.02249372,-0.01560993,-0.04192184,-0.0439078,-0.08858853,0.01022886,0.03586573,0.00462056,0.09121476,-0.03935163,0.03062108,0.05473379,0.01895092,-0.02656744,-0.05729284,0.04180244,0.04221907,-0.06470564,-0.02242581,0.02469386,0.00326803,0.05536197,-0.0578249,0.04303809,0.02735393,-0.04357682,-0.0707751,-0.05660586,-0.00327176,-0.01945781,-0.04346992,-0.04815305,-0.04445718,0.0957489,0.02832166,-0.01444057,0.01746923,0.01029396,0.01568412,-0.10659022,0.00212947,-0.01108814,0.11806897,-0.03047305,-0.00275268,-0.10651021,-0.0486698,-0.05781565,-0.08637701,0.01758054,-0.08930448,1.16e-06,-0.00340977,-0.0779095,-0.01811996,-0.02664065,0.0897786,0.0327118,0.02554721,-0.01344016,0.01491796,-0.03537947,0.1077441,0.03033541,-0.07310126,0.04561941,0.02435587,-0.04007779,0.03319964,0.07584578,0.00203603,-0.00134286,0.02563964,-0.03974592,-0.08580272,-0.00061307,0.04660436,0.02576626,-0.03275435,-0.03819849,-0.08054943,0.00381653,-0.02126698,-0.00526793,-0.0360248,-0.01427852,-0.07428661,0.00359677,-0.04087729,-0.06186523,-0.02875787,-0.09124503,0.10737597,0.02276169,-0.00915138,0.00325177,0.08029785,0.02734736,0.01638912,-0.02154568,0.03828502,-0.04431972,-0.00212045,0.05576089,0.07184378,-0.05759273,0.01510497,-0.06551862,0.11581776,0.00017091,-0.04675453,0.07210141,-0.05928099,0.00657723,0.02969677,0.01776673]', 2);
INSERT INTO public.detalles_proyectos VALUES (174, '2026-09-29', 'Pregrado', 'El proyecto tiene como propósito optimizar el Sistema de Información para el Control de Matrícula del Centro de Atención Integral para Personas con Autismo “CAIPA Trujillo”, versión 2.0, ubicado en Valera, estado Trujillo. La investigación surge de la necesidad de mejorar la organización, búsqueda y gestión de los datos de los estudiantes, así como de corregir y ampliar las funcionalidades del sistema anterior, versión 1.0, que presentaba limitaciones en registro, consulta, reportes y control administrativo. Metodológicamente se enmarca en la Investigación-Acción Participativa, con enfoque sociotecnológico, mixto y aplicado, apoyado en la metodología ágil Extreme Programming (XP). El desarrollo se realizó con PHP, MySQL, HTML5, CSS3 y JavaScript bajo arquitectura MVC, incorporando módulos para estudiantes, representantes, personal docente, administrativo y obrero, Programa de Alimentación (PMA), medicamentos, reportes PDF y configuraciones. Como resultado, se obtuvo una plataforma web más moderna, segura y eficiente, con mejoras en usabilidad, validación de datos, paginación, búsqueda y generación de fichas técnicas. Las pruebas de calidad evidenciaron avances significativos y algunas correcciones pendientes en seguridad y reportes. Se concluye que la versión 2.0 optimiza el control de matrícula, reduce tiempos de gestión y fortalece la atención educativa e inclusiva de la institución.', 1, 'CAIPA Trujillo  ------------------------------------------------Naturaleza de la Comunidad: El CAIPA-Trujillo, Valera Estado Trujillo.   ------Misión', 'Sistema de información; control de matrícula; CAIPA Trujillo; autismo; aplicación web; optimización; metodología XP; PHP; MySQL; gestión educativa; inclusión; versión 2.0.', '2026-09-28 21:42:57.240697', NULL, NULL, 'Optimizar el sistema de información para el control de matrícula en el Centro de Atención Integral para Personas con Autismo “CAIPA TRUJILLO”. Versión 2.0.', true, '[-0.00571938,-0.04953498,-0.08629598,-0.03289757,-0.00857773,0.01331615,0.0340633,0.0673716,0.00466962,0.00622307,0.07896866,0.00184984,0.02425908,-0.01172042,-0.10141608,-0.00115401,-0.0049325,0.04657307,0.04746393,0.02212997,0.00704064,-0.01345895,0.05930132,-0.00785187,-0.08411591,0.00377013,-0.02679438,-0.01209026,0.04223953,-0.01513314,0.02804249,-0.07099612,0.11996243,-0.00899651,-0.09397516,0.0221704,-0.00066489,0.00887241,-0.05378565,-0.02100728,0.01937815,0.00831379,0.05940856,-0.05772688,0.03339604,0.01176001,0.03781492,-0.064724,-0.01994626,0.03689111,-0.08532099,-0.0255634,0.09385125,-0.03324623,-0.0222529,-0.04444352,9.333e-05,0.01022027,0.05644946,0.0314625,7.468e-05,0.00106255,0.08859298,-0.03433161,0.09047997,0.05337419,-0.02796561,-0.05238823,0.0056684,0.0215728,-0.00044551,0.04670694,0.0107275,0.05599345,-0.02100387,-0.04463188,-0.03381386,0.00588751,0.06558095,-0.04650596,0.04220439,0.04966015,-0.02274641,0.04255185,-0.00435739,0.0137031,-0.06145321,0.04722067,0.08281094,0.0342236,0.11065346,0.0740088,-0.01341383,-0.06181883,-0.01560853,-0.0578822,0.00060124,-0.02579393,-0.07767881,-0.00855687,-0.00898326,-0.04535138,0.00735668,0.03222778,-0.03357212,-0.08637534,0.05348231,0.00121735,-0.00717231,0.0314735,-0.02399469,0.01363574,-0.04991063,-0.02764525,0.00573506,0.02424147,-0.01288731,-0.01161377,0.05498582,0.03681189,-0.0138873,-0.06476001,-0.02950376,0.01789088,0.09377522,-0.04927289,-0.09194385,3.99e-06,-0.11736023,-0.01136356,0.0152148,0.0229757,0.00231399,-0.0105601,0.00394581,-0.06307554,0.05064955,-0.03997666,-0.03488859,0.00361811,-0.01433616,0.06537167,0.06188987,-0.0933183,0.01794364,0.02010839,0.02356089,-0.06430411,0.04822711,-0.03493352,0.00670492,0.01445281,0.04588763,0.01537796,-0.00664511,0.0551279,0.03909714,-0.0173378,0.02433442,-0.02862783,-0.06950868,-0.07946335,0.05193304,0.01079338,0.08873138,0.04271125,0.0329218,0.0649589,0.03170525,0.02281773,0.06608517,-0.03628481,-0.06681011,0.00346268,0.03550848,0.01619648,0.04754113,-0.10937289,0.02599898,-0.00410057,-0.08695522,-0.10956401,0.03879749,-0.09613352,-0.03328491,-0.06731256,-0.0592998,0.02833684,0.09025098,-0.13088676,0.03010702,-0.05371609,-0.01889052,-0.05040227,0.0856666,-0.05573363,0.15620387,-0.06605231,-0.07093699,0.01698302,0.0538259,-0.01301183,-0.00977093,-0.01556077,-0.11831327,0.03835668,-0.04864451,-0.04475818,-0.01153108,0.04164984,-0.04021977,0.00396321,0.06925572,-0.01055529,-0.01178205,0.11468049,-0.09394042,-0.01615525,0.05020351,-0.02536015,-0.03927977,0.0731451,-0.06975819,1.215e-05,-0.05465505,-0.05967352,0.00096445,-0.03406359,-0.0670287,0.009873,0.00958149,-0.02543025,0.03380241,-0.02329899,0.10534568,-0.086515,0.01929958,-0.07141153,0.04782291,0.0964474,-0.06398734,0.03385748,-0.03585588,-0.10822495,-0.06142102,-0.01254814,-0.00266526,-0.00066308,-0.01702423,0.00354437,-0.02928314,0.10717884,0.01720584,0.06523662,-0.15759249,-0.06922216,-0.0521619,0.1015155,0.03038084,-0.04361576,-0.03497508,-0.0509458,-0.06351568,0.0142692,0.08771912,0.04565064,-0.0750096,0.0013928,0.00759145,0.00461482,0.01616449,-0.02505468,-0.06725287,0.03481713,0.08919677,-0.02461283,0.02086076,-0.02096503,-0.00145545,0.12178374,-0.01330136,-0.024907,-0.02048674,-0.00976774,0.01823783,-0.03794979,-0.09246417,-0.01792341,0.0158406,0.06565722,-0.04130036,0.08402846,0.04870977,-0.02249243,0.04108274,-0.03370638,0.02607358,-0.02474131,-0.07468238,-0.01013681,0.0132718,0.05557633,-0.02900584,-0.00750556,-0.04420931,0.03864752,0.04630006,-0.00655822,-0.02862646,0.08763933,0.02443904,0.01644355,0.03115325,-0.01450574,-0.04158004,-0.00256847,0.05959474,-0.02155176,-0.09413143,1.36e-06,-0.09395938,-0.08263872,-0.06830602,-0.02143807,0.03336084,0.07283034,-0.04946786,0.01201681,-0.06043468,0.07155154,0.00669965,-0.01420139,-0.03733902,-0.05242552,0.00811942,-0.02119204,0.03293835,0.07984334,0.02796077,-0.10446852,0.05037287,-0.01573247,-0.08762269,-0.02640065,-0.01020894,-0.00602242,-0.101962,0.01585417,-0.02058463,0.03558667,0.01698938,-0.08007022,-0.00957641,0.02414479,0.05338432,0.00871717,-0.05452191,-0.03793967,0.0168057,0.0743237,0.1182828,-0.02095809,-0.01523965,-0.00041343,-0.00747605,0.02743655,-0.03264136,-0.05622501,0.04877659,-0.01409431,0.04214726,0.00202711,4.695e-05,0.04953497,0.00554586,-0.07487353,-0.01011193,0.01072712,-0.09809485,0.10192835,0.03435662,0.06720309,-0.02903569,-0.02252585]', 1);
INSERT INTO public.detalles_proyectos VALUES (175, '2026-09-29', 'Pregrado', 'El presente proyecto sociotecnológico se centra en el desarrollo de un módulo avanzado para la administración y proyección de las líneas de investigación del PNFI, en el cual la innovación principal radica en la integración de modelos de Inteligencia Artificial (Machine Learning) orientados al análisis predictivo, esta herramienta procesa el volumen y la tipología de las investigaciones registradas para identificar tendencias emergentes, predecir el crecimiento de áreas temáticas y asistir al Comité Científico Investigador en la toma de decisiones estratégicas, todo ello operando sobre la arquitectura base del Sistema Integral de Gestión.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr. Pablo Viloria”, coordinación de investigación del programa nacional', 'Líneas de investigación, PNFI, Machine Learning, Análisis predictivo, Toma de decisiones, Comité científico, Gestión del conocimiento, Sistema integral de gestión.', '2026-09-28 21:42:57.295976', NULL, NULL, 'Desarrollar un Sistema de Gestión de Eventos integrado con un módulo de Inteligencia Artificial (Machine Learning) para la administración, clasificación automática y análisis predictivo de las líneas de investigación de los proyectos académicos del PNFI, adscrito al Comité Científico de la UPTTMBI - Núcleo La Beatriz.', true, '[-0.10691101,-0.05934565,-0.01816613,-0.02918359,0.00401342,0.05353092,0.02148034,0.01029053,-0.12892279,0.03272152,0.03166542,0.03346941,0.06094049,-0.06925746,-0.05566427,-0.00232151,-0.0715307,-0.02508197,0.02504233,-0.0318286,-0.03946566,-0.05423755,0.08427692,-0.01183777,-0.05611667,0.01536735,0.04577246,0.00195516,0.04121561,0.07840494,0.03586992,-0.01177278,0.0608179,-0.01930206,0.04278956,0.05194455,-0.02232104,0.06094469,0.01097988,0.02190481,0.04986299,-0.05054829,-0.01519127,-0.06822739,0.02495528,-0.0420431,-0.00232999,-0.08040033,-0.07977065,-0.05216599,-0.06355304,-0.05344254,0.04483972,-0.00072563,-0.0508796,-0.07703695,-0.01242287,-0.03305169,-0.0191335,0.01057378,-0.01307547,-0.01360271,-0.02468993,0.04009112,0.13005123,0.04943472,-0.0409519,0.05502411,0.03245871,-0.01379436,0.04941405,-0.01530662,-0.0398075,0.04487692,0.00533738,0.01563456,0.01635406,-0.05772328,0.06512188,-0.08000862,0.03667854,-0.02564413,-0.03716166,-0.00123416,-0.0138882,-0.04181798,-0.07104185,-0.01714339,0.06597691,0.00069679,0.05170986,0.013662,0.05322829,-0.05140438,0.03730163,0.04494601,-0.05750694,-0.04445576,-0.07607714,0.06347766,-0.08135514,0.02874146,0.00246473,-0.03218228,0.00702817,0.02077131,0.00913249,-0.02145902,0.09065046,0.03006193,-0.05869355,0.02009534,-0.0140396,-0.01849112,0.03702654,0.0323948,-0.07359426,0.01923622,0.00443355,0.01874241,-0.05635449,-0.07186643,-0.06988829,-0.03661431,-0.00668964,-0.01809656,-0.09021372,3.59e-06,-0.01313119,-0.06141468,-0.02681581,-0.04673586,0.04170697,-0.03604468,-0.01508357,-0.07313532,0.07586415,-0.05173496,-0.03770082,-0.03365997,-0.01097066,0.10532051,-0.03390849,-0.05815334,-0.07040371,9.134e-05,-0.01279964,0.01744483,0.03626884,-0.09081505,0.0292194,-0.00178117,-0.06649663,0.01646813,0.02073399,0.09629155,-0.04059599,0.03552114,0.02666093,-0.04302624,-0.07194288,-0.00482538,-0.02392633,0.00620767,0.01223924,0.02071471,0.02668756,0.02029373,-0.02154868,-0.0280483,0.01469246,-0.02747267,-0.03430581,0.01601074,-0.03860669,-0.07649376,-0.0797019,-0.03181546,0.00858857,-0.00663453,-0.03297399,-0.06514493,0.03211981,0.0411167,0.05416586,0.02474821,0.04923617,-0.06902317,0.12397432,-0.01378794,0.06248938,0.01542784,-0.02687833,0.08051728,0.00973586,0.00118394,0.06719956,0.01237228,-0.01811113,0.02031502,-0.0184413,0.00683675,0.08084314,5.36e-05,-0.04602977,0.05902716,-0.04218503,0.01602012,-0.02641084,0.0333635,-0.06206931,0.00358204,0.05539051,-0.02250392,-0.00832444,0.01778713,-0.02745109,-0.01243164,-0.00447257,-0.02093601,0.00747335,0.06738105,-0.0637526,1.284e-05,-0.11241612,-0.00040135,-0.01312501,-0.05968213,-0.00337755,-0.01553092,-0.03123072,0.0098488,0.01433466,0.06169982,-0.01992697,-0.02329313,0.0504408,-0.09595956,0.06662289,0.03175093,0.00187822,0.08696009,-0.00303108,-0.03799642,0.01408894,0.02711364,-0.01229533,0.02512772,-0.01086463,-0.01521509,-0.01010758,0.12844397,0.02838753,0.03167254,-0.05208984,-0.02864406,-0.09393318,0.02677399,-0.04062098,0.01655869,0.06352014,-0.05289799,-0.01475044,-0.05894936,0.10994428,0.06449308,-0.1217307,0.020441,0.00310683,-0.0220238,-0.10712521,-0.14490625,0.03455459,-0.02595417,0.08707278,0.04089912,0.08775638,-0.09015322,-0.02984198,0.09268448,0.08240453,0.04499166,-0.02069507,0.0453141,-0.08604815,-0.02136984,-0.03740772,0.09909792,-0.03730319,0.09093473,-0.02895332,0.09724648,-0.03713877,-0.06439656,-0.00022025,0.07120758,-0.08611487,-0.02744274,-0.10471936,-0.00027148,-0.10180981,-0.00595749,-0.02483705,-0.01410815,-0.02950214,-0.10746602,-0.03414441,-0.00651263,-0.00850149,0.00834732,0.02379302,0.00401463,0.0298326,-0.03618317,0.05218413,-0.01660838,-0.09964748,-0.04460416,-0.042996,1.5e-06,-0.00785888,-0.02055499,0.09512505,-0.00440761,0.12766086,0.02176315,-0.09242076,-0.0441136,0.0572622,-0.02900737,0.06240599,-0.02377982,0.01921812,0.00775153,-0.04042818,-0.02372392,-0.0570731,0.0836352,-0.05964066,0.00499712,0.15984212,0.02120546,0.01815722,0.01574842,0.01259383,-0.04761832,-0.05362733,-0.03809543,-0.09393963,0.00095515,-0.07226653,0.02840768,-0.01813486,-0.03098103,0.00787146,0.08385681,0.11894244,-0.08773802,-0.06310001,0.07381704,-0.06070347,-0.07676332,0.00994569,0.01795867,0.00429539,-0.00283422,0.03168571,-0.0392185,0.00244572,0.02205604,0.05571314,0.03929997,0.0074625,-0.05259972,0.01321316,-0.04876329,-0.04487733,0.04228006,-0.06694911,0.05284138,0.06819382,-0.03558247,0.09597055,-0.02829743]', 4);
INSERT INTO public.detalles_proyectos VALUES (177, '2026-09-29', 'Pregrado', 'El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” NUES Dr. Pablo Viloria – La Beatriz, Coordinación del PNF en Informática.', 'Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa.', '2026-09-28 21:42:57.445384', NULL, 'https://github.com/Zhailox/proyect_CIIDI', 'Desarrollar un sistema de optimización basado en algoritmos genéticos que mejore automáticamente la generación de horarios en la Coordinación del Programa Nacional de Formación en Informática, considerando múltiples restricciones y criterios de optimización para reducir significativamente el tiempo de planificación, minimizar los conflictos de horario y mejorar la satisfacción de la comunidad académica.', true, '[-0.08061925,0.02169833,-0.02530532,-0.06889659,-0.04207454,0.02184883,-0.00751991,0.05311541,-0.11603164,-0.00955703,0.04996218,0.06143741,0.06094424,-0.08960559,-0.03344202,-0.02806711,0.03495954,0.02117246,0.021226,0.00434396,-0.06445195,-0.07627258,0.05826151,-0.03167837,-0.03796604,-0.02809276,-0.00588366,0.08304577,0.03329217,-0.02192484,0.03053606,0.02878036,0.03809247,-0.01542312,-0.01146376,0.06423877,-0.01799236,0.01760301,0.01326499,0.00569564,0.05681516,-0.01267998,0.00344654,-0.0123726,0.07414893,-0.02448377,-0.10053693,-0.03596458,-0.03700538,-0.04276186,-0.07733416,-0.02590818,0.1363521,0.01948524,0.01375572,-0.0661201,-0.04785442,-0.04205839,0.04115662,-0.06406962,0.00887264,0.06631269,-0.01768079,-0.01795657,0.0696503,0.02084621,0.06897931,-0.09328443,0.04011939,0.04535985,0.03387897,0.02200332,-0.05690847,0.05049293,-0.03323226,0.0497962,0.06944754,0.02307007,0.02983317,-0.04492555,0.05756622,-0.01062246,-0.05539792,0.07334555,-0.01694335,-0.019216,-0.09342088,0.0291824,0.01427024,-0.00595245,0.0008604,0.00693333,0.10298504,-0.0397807,0.08534431,0.06902553,-0.05422496,0.02233263,-0.02114915,0.00891211,0.00198975,-0.03068023,0.02195456,0.01093123,-0.07540669,-0.04646764,0.04881689,0.02983729,0.09135802,-0.01618152,-0.05498018,-0.03159838,0.00028401,-0.03117629,-0.05720155,0.06971919,-0.00257084,0.02035614,0.01721217,-0.01702365,-0.07032179,-0.07383035,-0.06695915,-0.03592653,0.02878421,-0.05348524,-0.08271637,3.93e-06,-0.02533714,-0.08427905,0.01777137,-0.06775224,0.00369153,-0.07780096,0.04081408,-0.0761759,0.00468338,-0.0492664,-0.08314979,-0.05276526,-0.09663274,0.12050485,0.0200616,-0.1340883,-0.09616269,0.0454931,-0.00276488,0.0728509,-0.00099287,-0.12616514,-0.01593283,-0.01044658,0.03967457,0.02973675,0.02467248,0.02024777,-0.08681324,0.02108511,0.02696961,-0.07200929,-0.03562162,-0.00482157,-0.10055564,-0.05064826,-0.02141497,0.01045405,0.01853314,0.0249585,0.00370711,0.04693305,-0.00435469,-0.02648236,-0.02083208,0.05148834,-0.10041356,0.01856854,-0.03079083,0.05370743,0.03238774,0.02980333,-0.01270614,-0.01891467,0.04471086,0.00865099,0.01152548,0.08294054,0.00530583,-0.02788374,0.01490641,-0.08304154,0.03156805,0.04498549,-0.02662715,0.0682472,0.00077311,-0.08233788,0.07030042,0.01833233,-0.0561978,-0.10023482,-0.04746802,0.04968428,0.02557355,0.0287751,-0.05001203,0.06169407,-0.06660732,0.00732524,-0.04807844,0.07364885,-0.10086265,-0.02701705,0.12412482,-0.05482634,-0.01194657,-0.012025,-0.04555582,-0.07090425,0.01019201,-0.02648563,0.02041074,0.04571759,-0.02513622,1.279e-05,-0.02085972,-0.01914107,0.05176337,-0.0002451,-0.04397679,0.039294,0.02301453,-0.04516994,0.0248083,0.09043651,0.05561668,0.00606115,0.01160695,-0.09447076,0.02484759,0.04760743,-0.02300303,0.06242188,0.13297883,-0.00794453,-0.00546181,0.07244862,0.04296147,-0.02132732,0.03115277,0.0076492,-0.00827914,0.13147175,-0.02427397,0.00724055,-0.080492,-0.00403944,-0.07493703,0.07177699,-0.0081167,-0.01030331,0.01230606,0.04806585,0.00436773,-0.02999131,0.04979712,-0.01873043,0.00150313,0.05021555,-0.04269128,-0.03138009,-0.03766563,-0.12187715,-0.00807789,-0.00689329,0.06167652,0.0482745,0.12373179,0.0225606,0.01061518,0.07598653,0.02325876,0.04617615,0.03128352,-0.03933392,0.03289413,0.05493277,-0.03741197,0.05893309,-0.06092378,0.09026635,-0.03150479,-0.00307127,-0.05259495,-0.01436497,-0.07957516,0.00528513,0.07525855,0.0918241,-0.04865333,0.0660063,-0.07420029,0.05426456,0.01446061,-0.00492774,-0.06331617,-0.02599655,-0.0946916,-0.05097333,-0.03753334,-0.03868946,0.01360921,0.02304838,0.00485369,-0.02313916,0.0457037,0.00697355,-0.00616215,-0.05436906,-0.05249684,1.51e-06,0.03944925,-0.08053718,-0.05456593,-0.02625673,-0.01122764,-0.01836897,-0.05974932,-0.03570841,0.02957242,-0.00859484,0.07980656,-0.02509957,0.00235605,-0.00847601,0.01218896,-0.12523001,-0.0268507,0.07275889,-0.09372865,-0.05704234,0.06578907,0.02595644,0.03183014,0.0413341,0.04620037,-0.00329255,0.00727294,-0.0459928,-0.04987107,0.01413676,-0.03911143,0.03647073,-0.08343671,-0.00410058,-0.00442295,0.08691016,-0.02808818,-0.05130818,-0.00447341,0.03345399,-0.0043003,-0.08642427,-0.04765673,-0.04324001,-0.01719409,-0.05528444,-0.0317257,0.00820539,-0.04904306,0.01059823,0.00893269,-0.00886343,0.00227922,-0.11173847,-0.03357306,0.02458791,-0.0095698,0.02922594,-0.03517225,0.09610646,0.01151697,0.02094735,0.11841241,0.01190971]', 3);
INSERT INTO public.detalles_proyectos VALUES (180, '2026-09-29', 'Pregrado', 'El componente metodológico empleó la observación directa de los procesos internos de la tienda y la aplicación de entrevistas a los clientes, con un enfoque teórico-práctico para el diseño de la solución. Se aplicaron las etapas del desarrollo del software: especificación de requerimientos, diagramas UML, modelo entidad relación, diagrama relacional, pruebas de la caja negra. Los resultados mostraron que el sistema mejora significativamente el rendimiento de la tienda y aumenta su valor diferencial al proporcionar recomendaciones automáticas sobre el valor nutricional de los productos adquiridos.', 1, 'Tienda ''Tuti'' del Cantón Vinces', 'Aplicación Web, Consumo, Inventario, Semáforo, Cliente-Servidor', '2026-09-28 22:19:26.610302', NULL, NULL, 'Desarrollar una aplicación web cliente-servidor para el control de inventario, que muestre el porcentaje de consumo, acorde al semáforo nutricional en la tienda ''TUTI'' del cantón Vinces.', true, '[-0.09822307,-0.09192882,-0.07735155,-0.05146326,-0.03457792,0.033037,0.0669247,0.04759888,0.00750228,-0.01973644,0.15337119,0.02617699,0.02660482,-0.03904517,-0.00752803,0.05662277,0.06580162,0.00500952,-0.01674626,0.0192102,-0.04325675,-0.06976543,-0.01177764,0.02296027,-0.06926976,-0.05853769,0.00283562,0.03818609,0.00141473,-0.0361485,0.07968216,-0.02703603,0.01030735,0.01273934,0.00587365,0.0483531,-0.03312245,-0.10854405,-0.03714388,0.03980689,0.03305408,-0.0056358,-0.07490217,-0.04214194,-0.0036545,-0.13063377,0.00662792,0.00223572,-0.08566509,0.03993488,-0.06225728,0.00308673,-0.02878384,-0.09956642,0.01669587,-0.04653555,-0.08540189,-0.02395113,-0.01225972,0.0390549,0.05229504,0.04888159,-0.00906783,0.03312738,-0.01022156,0.03496342,0.0702491,0.00709155,-0.05759141,0.00511548,0.0300779,-0.10499816,-0.02130406,-0.04360644,-0.01125919,0.02202566,0.03780572,-0.03211833,-0.05509146,-0.0386366,0.02434851,0.02529636,-0.0373684,-0.01128719,-0.05748583,0.0158708,0.01647676,0.01238833,0.08035254,0.04206424,-0.03215814,-4.16e-06,0.07534224,-0.06513647,0.02799659,0.03431264,0.05784241,0.03382558,-0.04593113,0.02884573,-0.02132341,-0.03514081,0.02613174,-0.03498475,-0.03022427,-0.03613671,-0.05968354,0.07086342,0.10063778,0.04872123,-0.02417753,0.01691586,0.0404647,-0.11697696,-0.05742649,0.03764528,-0.03383224,-0.10420843,0.05418167,-0.01507362,-0.00175982,-0.0040843,-0.04577932,0.02149391,0.00033383,-0.07284531,0.05378699,5.12e-06,-0.00928967,-0.03477434,0.00644416,0.02272351,0.06940335,0.02022097,0.05843434,-0.03928555,-0.00368485,-0.0684357,0.00174911,0.05892399,-0.05771837,0.02118103,0.00319856,-0.11726794,-0.00188646,0.00383855,0.11591217,0.00241432,-0.11537328,-0.07427613,0.0058167,0.0654016,-0.04721004,0.01563894,0.01056125,0.09209604,-0.01399419,0.00856203,0.16008419,-0.00871262,-0.04006914,0.02173001,-0.04720356,-0.04562094,-0.03606809,-0.02193332,0.01142552,0.0217825,-0.02682671,0.02245488,-0.07884333,0.05601071,-0.11488404,-0.03761689,-0.06486976,0.03808604,-0.02748293,-0.03785056,-0.03141061,0.0748714,0.08586314,0.06097529,0.05018805,-0.01523165,-0.01873094,0.02673448,-0.05034008,0.00222569,0.01925827,-0.03522836,-0.00769648,-0.01064536,-0.05624041,0.04357175,0.00910134,-0.05582486,0.09422329,-0.04885064,-0.07712122,0.03685058,-0.04571743,0.02271814,0.05544749,-0.02406755,-0.05437331,0.07833015,-0.02002551,0.01768751,-0.0499464,0.03999531,0.06802518,0.09209368,-0.02399721,0.03387457,-0.03118184,0.11033714,-0.0071232,0.0247066,0.05579175,-0.01213913,0.01629858,0.02033769,0.07639816,1.479e-05,-0.08403679,-0.07708661,-0.02798273,0.0516161,0.00296907,-0.02270421,-0.07648506,-0.010586,-0.06143939,-0.0069288,0.10259248,0.00037936,-0.01369566,-0.070377,-0.01537497,0.06295109,-0.00112796,0.03707331,-0.00839932,-0.0967849,-0.01228304,-0.05843423,0.04094313,-0.04974301,-0.02925406,-8.767e-05,-0.01415337,-0.02599722,-0.01333235,-0.01082291,0.02682683,-0.00291212,-0.00260679,0.02790644,-0.08000444,0.0031539,0.02046096,0.01736283,-0.04863166,-0.01199514,0.03821531,-0.01524704,0.00218924,0.03591255,-0.07909229,0.02932314,-0.06235968,-0.09005536,0.0644564,0.0275412,0.0484769,0.02676601,0.05232109,-0.14266095,-0.03105432,0.14311355,0.11002378,-0.04076586,0.01779254,-0.05703148,0.04454628,-0.0030673,0.02718814,-0.01405081,0.04484745,0.10116617,-0.00402177,0.03391811,0.04160703,-0.10272555,0.03993793,0.06221976,-0.02384244,-0.02358864,0.05894148,-0.00474031,0.01236674,0.03361087,0.01579688,-0.00957997,-0.07008873,-0.01953126,0.01890335,-0.03348697,0.02258379,-0.03123484,0.0046795,-0.00213648,-0.01943675,0.01850492,-0.05775355,0.02749772,-0.02187607,0.04131094,0.06532372,1.62e-06,-0.04742031,-0.07931738,-0.11136408,-0.067009,0.00110748,0.09562043,-0.0101476,-0.02079741,0.06877154,0.02032099,0.03360707,0.01971118,-0.12696373,0.06973519,-0.05755852,-0.00674378,-0.0094072,0.08289793,-0.02488211,-0.06665211,0.01758113,0.03564626,0.0135718,0.01066943,0.02502933,-0.04173861,-0.0111534,0.07015381,-0.01028368,0.02115123,-0.05360326,-0.045118,-0.0728095,-0.03240774,-0.03041547,0.04407991,-0.08339348,-0.07245622,-0.06495788,0.08525187,0.00385077,-0.04612431,-0.03570939,0.04044387,0.0319451,0.0477932,-0.0487328,0.0465968,-0.01394424,0.03027847,-0.02624198,-0.02869969,0.02420132,-0.10126752,-0.05413417,-0.04858912,0.11383318,-0.02839237,0.04211164,0.01080393,-0.02155243,-0.00451452,0.09131661,-0.02188322]', 2);


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

INSERT INTO public.proyecto_tutores VALUES (173, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (173, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (174, 17, 3);
INSERT INTO public.proyecto_tutores VALUES (174, 18, 2);
INSERT INTO public.proyecto_tutores VALUES (174, 19, 4);
INSERT INTO public.proyecto_tutores VALUES (175, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (175, 22, 4);
INSERT INTO public.proyecto_tutores VALUES (176, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (177, 10, 2);
INSERT INTO public.proyecto_tutores VALUES (177, 39, 4);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (3, 1);
INSERT INTO public.recurso_autores VALUES (179, 120);
INSERT INTO public.recurso_autores VALUES (144, 92);
INSERT INTO public.recurso_autores VALUES (144, 96);
INSERT INTO public.recurso_autores VALUES (144, 97);
INSERT INTO public.recurso_autores VALUES (144, 102);
INSERT INTO public.recurso_autores VALUES (146, 103);
INSERT INTO public.recurso_autores VALUES (146, 104);
INSERT INTO public.recurso_autores VALUES (146, 105);
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
INSERT INTO public.recurso_autores VALUES (150, 13);
INSERT INTO public.recurso_autores VALUES (151, 31);
INSERT INTO public.recurso_autores VALUES (156, 82);
INSERT INTO public.recurso_autores VALUES (173, 42);
INSERT INTO public.recurso_autores VALUES (173, 43);
INSERT INTO public.recurso_autores VALUES (173, 44);
INSERT INTO public.recurso_autores VALUES (173, 45);
INSERT INTO public.recurso_autores VALUES (174, 58);
INSERT INTO public.recurso_autores VALUES (174, 59);
INSERT INTO public.recurso_autores VALUES (174, 60);
INSERT INTO public.recurso_autores VALUES (174, 61);
INSERT INTO public.recurso_autores VALUES (175, 34);
INSERT INTO public.recurso_autores VALUES (175, 71);
INSERT INTO public.recurso_autores VALUES (175, 72);
INSERT INTO public.recurso_autores VALUES (175, 73);
INSERT INTO public.recurso_autores VALUES (176, 52);
INSERT INTO public.recurso_autores VALUES (176, 53);
INSERT INTO public.recurso_autores VALUES (177, 46);
INSERT INTO public.recurso_autores VALUES (178, 119);
INSERT INTO public.recurso_autores VALUES (180, 121);


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

INSERT INTO public.recurso_clasificaciones VALUES (179, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (173, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (174, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (175, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (176, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (177, 7, 7);
INSERT INTO public.recurso_clasificaciones VALUES (178, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (180, 9, 16);


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
INSERT INTO public.recursos VALUES (179, 'CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO', 1, 2026, 'storage/documentos/pst/pst_configuracion_e_implementacion_1790648366_353.pdf');
INSERT INTO public.recursos VALUES (173, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1790646177_290.docx');
INSERT INTO public.recursos VALUES (174, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 'storage/documentos/pst/pst_optimizaci__n_del_sistema_de_i_1790646177_402.docx');
INSERT INTO public.recursos VALUES (175, 'MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ', 1, 2026, 'storage/documentos/pst/pst_m__dulo_inteligente_basado_en__1790646177_217.docx');
INSERT INTO public.recursos VALUES (176, 'Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1790646177_161.docx');
INSERT INTO public.recursos VALUES (177, 'Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz', 1, 2026, 'storage/documentos/pst/pst_sistema_de_optimizaci__n_basad_1790646177_802.docx');
INSERT INTO public.recursos VALUES (178, 'DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL', 1, 2026, 'storage/documentos/pst/pst_dise__o_y_prototipo_de_una_apl_1790648365_813.pdf');
INSERT INTO public.recursos VALUES (180, 'Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda ''Tuti'' del Cantón Vinces', 1, 2025, 'storage/documentos/pst/pst_aplicaci__n_web_cliente_servid_1790648366_463.pdf');


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);
INSERT INTO public.registro_actividad VALUES (3, 12, NULL, '2026-09-19 14:19:44.068925', '2026-09-19 14:20:25.834661', 2);
INSERT INTO public.registro_actividad VALUES (4, 17, NULL, '2026-09-19 14:57:43.371674', '2026-09-19 14:58:00.847148', 2);
INSERT INTO public.registro_actividad VALUES (2, 7, NULL, '2026-09-19 13:23:18.589777', '2026-09-28 19:20:57.954753', 31);


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


--
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.telemetria_cache VALUES (1, '{"timestamp": 1790658502, "storage_mb": 34.29, "files_count": 81}');


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
INSERT INTO public.usuarios VALUES (19, 'Juan Salcedo', 'juanxzall0701@gmail.com', 'V-31008131', '$2y$12$tjD8yhgVIWvwuJiEPZnYguCyS5EukGmORWC3srlfCHIAE0KQFr1ZG', 2, true, NULL, NULL, NULL, false, NULL);


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

SELECT pg_catalog.setval('public.auditoria_id_seq', 410, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 122, true);


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

SELECT pg_catalog.setval('public.recursos_id_seq', 185, true);


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
-- Name: trayectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.trayectos_id_seq', 20, true);


--
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 40, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 19, true);


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
-- PostgreSQL database dump complete
--

\unrestrict wiVplGxYX8Hb8XPUxbZktrORQcXChw2izVAFlXxQU2hUnCeX4hDlkBT6lS3tU4E

