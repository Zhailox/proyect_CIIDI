--
-- PostgreSQL database dump
--

\restrict EIIc95Nbnnmj8sYMQUh9CKtaZeJVO4fcqVszJfADNLpOb9UQO2Rt0CJADZQ1Bf7

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
    activo boolean DEFAULT true
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
INSERT INTO public.auditoria VALUES (322, 'recursos', 158, 'DELETE', NULL, NULL, '{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}', NULL, '2026-09-24 15:25:00.208343');
INSERT INTO public.auditoria VALUES (323, 'recursos', 156, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-24 15:32:37.139911');
INSERT INTO public.auditoria VALUES (324, 'recursos', 151, 'DELETE', NULL, NULL, '{"titulo": "ee", "id_tipo_recurso": 3}', NULL, '2026-09-24 15:32:45.571367');
INSERT INTO public.auditoria VALUES (325, 'recursos', 159, 'INSERT', NULL, NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', '2026-09-24 15:33:13.600732');
INSERT INTO public.auditoria VALUES (326, 'usuarios', 18, 'INSERT', NULL, NULL, NULL, '{"email": "Ereselmejorplaneta@gmail.com", "id_rol": 3, "nombre": "Vegeta"}', '2026-09-24 15:43:26.496769');
INSERT INTO public.auditoria VALUES (327, 'usuarios', 18, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegeta"}', '{"activo": false, "id_rol": 3, "nombre": "[Archivado] Vegeta"}', '2026-09-24 15:44:40.529754');
INSERT INTO public.auditoria VALUES (328, 'usuarios', 19, 'INSERT', NULL, NULL, NULL, '{"email": "7@gmail.com", "id_rol": 1, "nombre": "e"}', '2026-09-24 15:44:50.456306');
INSERT INTO public.auditoria VALUES (329, 'usuarios', 19, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 1, "nombre": "e"}', '{"activo": false, "id_rol": 1, "nombre": "[Archivado] e"}', '2026-09-24 15:47:30.219535');
INSERT INTO public.auditoria VALUES (330, 'usuarios', 20, 'INSERT', NULL, NULL, NULL, '{"email": "vegeta@gmail.com", "id_rol": 3, "nombre": "Vegeta"}', '2026-09-24 15:47:51.673558');
INSERT INTO public.auditoria VALUES (331, 'recursos', 160, 'INSERT', NULL, NULL, NULL, '{"titulo": "Edo. Trujillo Soporte Técnico A Equipos Y Usuarios De Computación Del Infocentro De Escuque", "id_tipo_recurso": 1}', '2026-09-24 15:55:40.312665');
INSERT INTO public.auditoria VALUES (332, 'recursos', 161, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', '2026-09-24 16:03:22.312137');
INSERT INTO public.auditoria VALUES (333, 'recursos', 161, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:04:25.131209');
INSERT INTO public.auditoria VALUES (334, 'recursos', 128, 'DELETE', NULL, NULL, '{"titulo": "Materia: Seguridad Informática", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:04:43.96802');
INSERT INTO public.auditoria VALUES (335, 'recursos', 127, 'DELETE', NULL, NULL, '{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:05:21.682338');
INSERT INTO public.auditoria VALUES (336, 'recursos', 117, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:05:34.305711');
INSERT INTO public.auditoria VALUES (337, 'recursos', 116, 'DELETE', NULL, NULL, '{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:05:37.392984');
INSERT INTO public.auditoria VALUES (338, 'recursos', 97, 'DELETE', NULL, NULL, '{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:05:40.819204');
INSERT INTO public.auditoria VALUES (339, 'recursos', 162, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', '2026-09-24 16:12:28.905906');
INSERT INTO public.auditoria VALUES (340, 'recursos', 162, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', NULL, '2026-09-24 16:18:17.526735');
INSERT INTO public.auditoria VALUES (341, 'recursos', 163, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', '2026-09-24 16:19:14.417988');
INSERT INTO public.auditoria VALUES (342, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegeta"}', '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '2026-09-24 16:20:49.190751');
INSERT INTO public.auditoria VALUES (343, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '{"activo": false, "id_rol": 3, "nombre": "Vegetas"}', '2026-09-24 16:22:33.528388');
INSERT INTO public.auditoria VALUES (344, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Vegetas"}', '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '2026-09-24 16:23:12.046624');
INSERT INTO public.auditoria VALUES (345, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '{"activo": false, "id_rol": 3, "nombre": "Vegetas"}', '2026-09-24 16:23:34.988345');
INSERT INTO public.auditoria VALUES (346, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": false, "id_rol": 3, "nombre": "Vegetas"}', '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '2026-09-24 16:24:13.496913');
INSERT INTO public.auditoria VALUES (347, 'usuarios', 20, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegetas"}', '{"activo": false, "id_rol": 3, "nombre": "[Archivado] Vegetas"}', '2026-09-24 16:24:42.444695');
INSERT INTO public.auditoria VALUES (348, 'recursos', 159, 'DELETE', NULL, NULL, '{"titulo": "e", "id_tipo_recurso": 3}', NULL, '2026-09-24 16:34:45.833601');
INSERT INTO public.auditoria VALUES (349, 'usuarios', 21, 'INSERT', NULL, NULL, NULL, '{"email": "vegeta@gmail.com", "id_rol": 3, "nombre": "Vegeta"}', '2026-09-24 21:43:57.20628');
INSERT INTO public.auditoria VALUES (350, 'usuarios', 21, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 3, "nombre": "Vegeta"}', '{"activo": false, "id_rol": 3, "nombre": "[Archivado] Vegeta"}', '2026-09-24 21:44:08.918173');
INSERT INTO public.auditoria VALUES (351, 'usuarios', 22, 'INSERT', NULL, NULL, NULL, '{"email": "vegeta@gmail.com", "id_rol": 4, "nombre": "Vegeta"}', '2026-09-24 21:44:22.083254');
INSERT INTO public.auditoria VALUES (352, 'recursos', 163, 'DELETE', NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', NULL, '2026-09-24 22:30:28.342749');
INSERT INTO public.auditoria VALUES (353, 'recursos', 164, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera", "id_tipo_recurso": 1}', '2026-09-24 22:32:16.286518');
INSERT INTO public.auditoria VALUES (354, 'recursos', 165, 'INSERT', NULL, NULL, NULL, '{"titulo": "ASASDA", "id_tipo_recurso": 3}', '2026-09-24 23:06:25.875346');
INSERT INTO public.auditoria VALUES (355, 'recursos', 165, 'DELETE', NULL, NULL, '{"titulo": "ASASDA", "id_tipo_recurso": 3}', NULL, '2026-09-24 23:06:56.275927');
INSERT INTO public.auditoria VALUES (356, 'recursos', 166, 'INSERT', NULL, NULL, NULL, '{"titulo": "NUES DR. PABLO VILORIA - LA BEATRIZ SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DEL LABORATORIO 1 - INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY (UPTTMBI)”", "id_tipo_recurso": 1}', '2026-09-24 23:18:34.716723');
INSERT INTO public.auditoria VALUES (357, 'usuarios', 22, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Vegeta"}', '{"activo": true, "id_rol": 4, "nombre": "Vegetas"}', '2026-09-24 23:56:40.702302');
INSERT INTO public.auditoria VALUES (358, 'usuarios', 22, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Vegetas"}', '{"activo": true, "id_rol": 4, "nombre": "Vegetasa"}', '2026-09-24 23:57:32.733102');
INSERT INTO public.auditoria VALUES (359, 'usuarios', 22, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Vegetasa"}', '{"activo": true, "id_rol": 4, "nombre": "Vegetasaa"}', '2026-09-24 23:57:46.157723');
INSERT INTO public.auditoria VALUES (360, 'usuarios', 22, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 4, "nombre": "Vegetasaa"}', '{"activo": true, "id_rol": 4, "nombre": "Vegeta"}', '2026-09-24 23:57:49.248695');
INSERT INTO public.auditoria VALUES (361, 'usuarios', 17, 'UPDATE', NULL, NULL, '{"activo": true, "id_rol": 2, "nombre": "adrusss"}', '{"activo": true, "id_rol": 2, "nombre": "Andrus"}', '2026-09-24 23:57:59.504575');


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
INSERT INTO public.autores VALUES (106, 'Perez Eulgimariano Rajoy', 'V-5555');
INSERT INTO public.autores VALUES (107, 'Lula Da Silva', NULL);
INSERT INTO public.autores VALUES (108, 'Kenedy Maldonado', 'V-30048268');
INSERT INTO public.autores VALUES (109, 'Adrismar Peña', 'V-29994037');
INSERT INTO public.autores VALUES (110, 'Carlos Suarez', 'V-29932647');
INSERT INTO public.autores VALUES (111, 'Alejandro Contreras', 'V-30302424');
INSERT INTO public.autores VALUES (112, 'Julio Granadino', 'V-30475679');
INSERT INTO public.autores VALUES (113, 'Luis Castellanos', 'V-30558543');
INSERT INTO public.autores VALUES (114, 'YEYEYE', 'IAIA');
INSERT INTO public.autores VALUES (115, 'Jorge Rodriguez', NULL);
INSERT INTO public.autores VALUES (116, 'López Peña Willker Gabrielci', 'V-27896359');
INSERT INTO public.autores VALUES (117, 'Salas Vásquez Andyjosé', 'V-27888136');
INSERT INTO public.autores VALUES (118, 'Judici Becerra Frank Starling', 'V-29739761');


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
INSERT INTO public.detalles_articulos VALUES (150, 7, 'e', 'e', 'e', '2026-09-11 11:59:24.004409', 'https://i.pinimg.com/736x/34/63/e7/3463e729b17ec40b1c60c25e1d86af52.jpg', 'e', false, NULL);


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_proyectos VALUES (46, '2025-11-20', 'Pregrado', 'Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.', 1, 'Estudiantes de Computaci¢n Gr fica', 'Lua, TIC-80, Retro, GameDev, M quina de Estados', '2026-07-05 17:21:44.350197', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (47, '2026-07-02', 'Pregrado', 'Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.', 1, 'Laboratorios de Arquitectura del Computador', 'Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy', '2026-07-05 17:21:44.350197', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (48, '2026-05-10', 'Pregrado', 'Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.', 1, 'Departamento de Sistemas de la Universidad', 'Microkernel, PHP, PostgreSQL, MVC, Arquitectura', '2026-07-05 17:21:44.350197', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (50, '2026-04-22', 'Pregrado', 'Aplicaci¢n interactiva dise¤ada como medio did ctico para facilitar los procesos de ense¤anza. Combina fundamentos comunicacionales y l¢gicos mediante una interfaz interactiva de alto rendimiento.', 1, 'µrea de Ciencias B sicas de la Instituci¢n', 'Edum tica, Software Educativo, Multimedia, µlgebra', '2026-07-05 17:39:35.498485', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (51, '2025-07-10', 'Pregrado', 'Dise¤o de un sistema distribuido cooperativo entre clientes y un servidor centralizado. Permite la gesti¢n din mica de solicitudes concurrentes controlando de manera efectiva las peticiones HTTP contra la base de datos.', 1, 'Coordinaci¢n de Control de Estudios', 'Web, Cliente-Servidor, PHP, PostgreSQL', '2026-07-05 17:39:35.498485', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (52, '2026-06-18', 'Pregrado', 'Herramienta de simulaci¢n orientada al testeo preventivo de la transmisi¢n de datos. Permite modelar el comportamiento de las decisiones de routing antes de iniciar el despliegue f¡sico de una infraestructura de red.', 1, 'Laboratorio de Redes y Telecomunicaciones', 'Simulaci¢n, Routing, Algoritmos, Redes, Topolog¡a', '2026-07-05 17:39:35.498485', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (57, '2026-07-05', 'Pregrado', 'ahsdhajsdhahakjfhafggfjhgfkjh', 1, 'asdasdasdasd', 'asdasdasdasdasd', '2026-07-05 18:21:33.639701', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (59, '2026-11-10', 'Pregrado', 'El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”', 'Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa', '2026-08-04 09:22:58.539505', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (69, '2026-08-04', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.', 1, 'Centro Clínico “María Edelmira Araujo”', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-04 09:58:54.904249', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (72, '2026-08-04', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-04 10:11:11.510708', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (78, '2026-08-05', 'Pregrado', 'Este es un resumen de prueba automatizada para verificar la carga por lotes via AJAX.', 1, 'Comunidad de Pruebas', 'Prueba, AJAX, Lotes, PHP', '2026-08-05 09:43:26.945146', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (45, '2026-06-15', 'Pregrado', 'Dise¤o e implementaci¢n de un motor de renderizado ligero y de alto rendimiento. Se evit¢ el uso de frameworks pesados para garantizar una ejecuci¢n "metal pure", optimizando el consumo de RAM y CPU en equipos de bajos recursos.', 1, 'Comunidad de Desarrolladores Independientes', 'Rust, Tauri, Novela Visual, Nativo, Optimizaci¢n', '2026-07-05 17:21:44.350197', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (80, '2026-08-05', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones', 1, 'Centro Clínico “María Edelmira Araujo”, S', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-05 09:48:05.633265', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (88, '2026-08-10', 'Pregrado', 'Según Arboleda (2014), un proyecto representa un esfuerzo temporal diseñado para producir un resultado o entregable único de forma gradual. Para enriquecer la fundamentación, Project Management Institute (2021), lo define como un esfuerzo temporal emprendido para crear un producto, servicio o resultado único.', 1, 'Corporación Eléctrica Nacional (CORPOELEC) de Venezuela', '', '2026-08-10 10:26:58.264555', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (49, '2026-03-15', 'Pregrado', 'Desarrollo de un sistema tradicional para optimizar los métodos y procedimientos del inventario médico. Sigue un patrón arquitectónico modular para agilizar los procesos organizacionales.', 1, 'Ambulatorio Urbano Tipo II', 'Sistemas de Información, PostgreSQL, Gestión, Inventario', '2026-07-05 17:39:35.498485', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (81, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:48:05.72936', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (82, '2026-08-05', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo  ------------------------------------------------Naturaleza de la Comunidad: El CAIPA-Trujillo, Valera Estado Trujillo', '', '2026-08-05 09:48:05.817964', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (83, '2026-08-05', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-05 09:48:05.91852', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (84, '2026-08-05', 'Pregrado', 'El propósito principal de este proyecto es realizar soporte técnico a los equipos de la institución (Escuela Técnica Comercial Madre Rafols)del Estado Trujillo municipio Valera. Y de igual forma dictar varias sesiones de capacitación formativas a los estudiantes de dicha institución cerca de software, hardware, partes, usos adecuados de un computador, donde podamos ofrecer nuevos conocimientos a los estudiantes. Todo esto aplicando nuevas tecnologías de aprendizaje que permitan el crecimiento y desarrollo del área de informática de la institución', 1, 'Escuela Técnica Comercial Madre Rafols', '', '2026-08-05 09:48:06.00888', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (85, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 09:56:42.188313', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (86, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:02:04.774776', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (87, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:34:46.219889', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (58, '2026-07-07', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio. Palabras clave: Gestión doc', 1, 'asdasdasdasd', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-07-07 00:01:54.74783', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (79, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:45:15.201621', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (89, '2018-01-10', 'Pregrado', 'El presente proyecto sociotecnológico se centra en el desarrollo de un módulo avanzado para la administración y proyección de las líneas de investigación del PNFI, en el cual la innovación principal radica en la integración de modelos de Inteligencia Artificial (Machine Learning) orientados al análisis predictivo, esta herramienta procesa el volumen y la tipología de las investigaciones registradas para identificar tendencias emergentes, predecir el crecimiento de áreas temáticas y asistir al Comité Científico Investigador en la toma de decisiones estratégicas, todo ello operando sobre la arquitectura base del Sistema Integral de Gestión.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Líneas de investigación, PNFI, Machine Learning, Análisis predictivo, Toma de decisiones, Comité científico, Gestión del conocimiento, Sistema integral de gestión', '2026-08-10 10:35:39.007693', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (90, '2026-08-10', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo', '', '2026-08-10 10:45:42.226083', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (91, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 10:52:47.26864', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (92, '2026-08-10', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-10 11:34:04.575523', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (93, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 11:34:42.145006', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (94, '2026-08-10', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-10 11:40:58.141559', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (99, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:10:58.390178', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (100, '2026-08-10', 'Pregrado', 'El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes', 1, 'Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry', 'App, Aplicación móvil, Coordinación, Ascensos', '2026-08-10 12:14:42.285533', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (108, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:20:17.959675', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (109, '2026-08-11', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.', 1, 'Centro Clínico “María Edelmira Araujo”', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-11 10:10:03.006883', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (110, '2026-08-25', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-25 19:01:06.312899', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (111, '2026-08-25', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-25 19:01:06.640902', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (147, '2026-09-11', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-09-10 21:02:31.80467', NULL, 'Trayecto I', NULL, 'Desarrollar un Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A., compuesto por un módulo de gestión local y una plataforma de comercio electrónico interconectados mediante una base de datos centralizada en la nube, con el fin de automatizar los procesos internos de inventario y ventas, y ampliar el alcance comercial de la empresa hacia el entorno digital.', true);
INSERT INTO public.detalles_proyectos VALUES (113, '2026-08-27', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-27 09:08:37.073105', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (114, '2026-08-27', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-27 10:17:48.017574', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (149, '2026-09-11', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones', 1, 'Centro Clínico “María Edelmira Araujo”, S', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-09-10 21:02:32.217243', NULL, 'Trayecto I', NULL, 'Proporcionar un Soporte Técnico a Usuarios y Equipos de Computación en el Centro Clínico “María Edelmira Araujo”, S.A.', true);
INSERT INTO public.detalles_proyectos VALUES (112, '2026-08-25', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-25 19:01:06.749048', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (132, '2026-09-04', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-09-04 10:35:11.057661', NULL, 'Trayecto I', NULL, 'Desarrollar un Sistema Integral de Gestión Documentos Académicos, basado en una arquitectura modular, para la automatización de la búsqueda híbrida de información y la centralización de recursos académicos en beneficio de la comunidad del PNF en Informática.', true);
INSERT INTO public.detalles_proyectos VALUES (160, '2026-09-18', 'Pregrado', 'El Infocentro de Escuque fue fundado en el 2009, aunque el proyecto de los infocentro ha estado en funcionamiento desde el año 2001 y ha sido una instalación donde las personas pueden ir a buscar información mediante la tecnología', 1, 'Infocentro de Escuque', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-09-24 15:55:40.312665', NULL, 'Trayecto I', NULL, 'Ofrecer Soporte Técnico A Usuario Y Mantenimiento De Equipos De Computación Del Infocentro De Escuque Propósitos Específicos: Ayudar a los usuarios de computadoras canaimas a realizar el mantenimiento preventivo a sus equipos.', true);
INSERT INTO public.detalles_proyectos VALUES (148, '2026-09-11', 'Pregrado', 'El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes', 1, 'Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry', 'App, Aplicación móvil, Coordinación, Ascensos', '2026-09-10 21:02:31.979685', NULL, 'Trayecto I', NULL, 'Crear y fortalecer las condiciones intelectuales y materiales para propiciar, generar, coordinar, diseminar y difundir conocimiento científico y cultural que responda al perfeccionamiento de las y los docentes en servicio, que contribuyan de manera sustancial al mejoramiento, desarrollo y crecimiento académico.', true);
INSERT INTO public.detalles_proyectos VALUES (164, '2026-09-25', 'Pregrado', 'El presente proyecto es realizado en el Liceo Bolivariano “Rafael Rangel” de la mano con la fundación CBIT “Rafael Rangel”, ubicado en el estado Trujillo, Valera, el Centro, Tiene como objetivo Soporte Técnico a Usuarios y Equipos de Computación, para mejorar el rendimiento educativo, ya que los equipos de computación necesitaban una mejora de su rendimiento por medio del soporte técnico preventivo y correctivo, igualmente se realizó un taller de capacitación hacia los estudiante, Mediante unas visitas constantes que se realizaron en el CBIT “Rafael Rangel”, donde proporcionamos distinta información sobre el software que nos permitió la institución. Cabe destacar que también abarcamos el tema sobre, el uso correcto del internet, para no caer o ser víctimas de ataques ciberriesgos, malware, programas espías, entre otros… que son algunos de los peligros a los que se enfrentan enfrenta el usuario, así como también, Cómo utilizar una computadora y cuáles son sus partes, y el uso de software educativo . En este proyecto se realizó una investigación sobre la intimación como de sus necesidades en el área informática. Donde se realizó un diagnóstico de los fallos de los equipos del CBIT Una de las Oficinas y en la coordinación de 4to año en donde en la mayoría se realizó un mantenimiento preventivo a los equipos ya que necesitaban una limpieza en el hardware del equipo también se realizó una capacitación a un grupo de estudiantes sobre LibreOffice writ en el que se les enseño', 1, 'La institución educativa, Liceo Bolivariano “Rafael Rangel” y Los Centros Bolivarianos de Informática y Telemática (CBIT), son espacios educativos dotados de re', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-09-24 22:32:16.286518', NULL, 'Trayecto I', 'https://github.com/Zhailox/proyect_CIIDI/tree/Zhailox', 'Brindar al Liceo Bolivariano "Rafael Rangel" un soporte técnico a las computadoras de las coordinaciones y del Cbit y un taller de capacitación a usuarios a los estudiantes del grupo estable de informática.', true);
INSERT INTO public.detalles_proyectos VALUES (166, '2026-09-24', 'Pregrado', 'Este proyecto es desarrollado en el laboratorio 1 de Informática de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” (UPTTMBI) ubicado en La Beatriz. Tiene como propósito brindar un eficiente soporte técnico a los equipos de laboratorio 1 de informática, mediante la revisión y el mantenimiento físico de estos; e instruir a los usuarios en el uso adecuado y correcto de los ordenadores, abordando como tema el reciclaje electrónico. Los que nos lleva a implementar una serie de entrevistas aplicadas al Ing. Ramón Santander y al Abg. Encargado del laboratorio Fabio Vera, así como también la observación directa empleada en el laboratorio 1 de Informática. La metodología aplicada en el proyecto socio-tecnológico es el marco lógico permitiendo así un eficaz diagnostico de la comunidad. A través de esta investigación fue posible hallar los problemas que presentan los equipos entre los cuales encontramos la falta de componentes, las bajas condiciones de operatividad, deficiente soporte técnico, entre otros. Se presentaron inconvenientes por lo que no fue posible ejecutar el soporte técnico a usuarios y equipos con totalidad, solo logrando ejecutar el mantenimiento preventivo en el hardware. INDICE GENERAL Pág. RESUMEN', 1, 'R: Laboratorio 1 de Informática.', '', '2026-09-24 23:18:34.716723', NULL, 'Trayecto I', NULL, 'Realizar un eficiente soporte técnico a usuarios y equipos en el Laboratorio 1 – Informática de la UPTTMBI.', true);


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
INSERT INTO public.dimensiones_operativas VALUES (13, 8, 'Juegos didácticos', 'El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploración y expresión, un instrumento para la organización y aplicación de habilidades y, un factor de socialización e integración.', true);


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
INSERT INTO public.matriz_rbac VALUES (1, 'Cursos', '{"crear": true, "editar": false, "auditar": false, "eliminar": true}');
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
INSERT INTO public.matriz_rbac VALUES (7, 'Articulos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (7, 'Cursos', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (7, 'Investigaciones', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (7, 'LineasInvestigacion', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (7, 'RepositorioPST', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (7, 'VinculacionEmpresarial', '{"crear": false, "editar": false, "auditar": false, "eliminar": false}');
INSERT INTO public.matriz_rbac VALUES (12, 'Sistema', '{"ver": true, "editar": false, "eliminar": false}');


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
INSERT INTO public.privilegios VALUES (11, 7);
INSERT INTO public.privilegios VALUES (12, 6);


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
INSERT INTO public.proyecto_tutores VALUES (113, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (113, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (114, 37, 3);
INSERT INTO public.proyecto_tutores VALUES (114, 38, 4);
INSERT INTO public.proyecto_tutores VALUES (132, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (132, 10, 4);
INSERT INTO public.proyecto_tutores VALUES (149, 35, 3);
INSERT INTO public.proyecto_tutores VALUES (149, 36, 4);
INSERT INTO public.proyecto_tutores VALUES (49, 40, 3);
INSERT INTO public.proyecto_tutores VALUES (160, 40, 3);
INSERT INTO public.proyecto_tutores VALUES (112, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (147, 22, 3);
INSERT INTO public.proyecto_tutores VALUES (148, 29, 3);
INSERT INTO public.proyecto_tutores VALUES (148, 30, 2);
INSERT INTO public.proyecto_tutores VALUES (148, 31, 4);
INSERT INTO public.proyecto_tutores VALUES (164, 41, 3);
INSERT INTO public.proyecto_tutores VALUES (164, 42, 2);
INSERT INTO public.proyecto_tutores VALUES (164, 43, 4);
INSERT INTO public.proyecto_tutores VALUES (166, 44, 3);
INSERT INTO public.proyecto_tutores VALUES (166, 42, 2);
INSERT INTO public.proyecto_tutores VALUES (166, 45, 4);


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
INSERT INTO public.recurso_autores VALUES (113, 42);
INSERT INTO public.recurso_autores VALUES (113, 43);
INSERT INTO public.recurso_autores VALUES (113, 44);
INSERT INTO public.recurso_autores VALUES (113, 45);
INSERT INTO public.recurso_autores VALUES (114, 82);
INSERT INTO public.recurso_autores VALUES (114, 83);
INSERT INTO public.recurso_autores VALUES (114, 84);
INSERT INTO public.recurso_autores VALUES (144, 92);
INSERT INTO public.recurso_autores VALUES (144, 96);
INSERT INTO public.recurso_autores VALUES (144, 97);
INSERT INTO public.recurso_autores VALUES (144, 102);
INSERT INTO public.recurso_autores VALUES (146, 103);
INSERT INTO public.recurso_autores VALUES (146, 104);
INSERT INTO public.recurso_autores VALUES (146, 105);
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
INSERT INTO public.recurso_autores VALUES (49, 45);
INSERT INTO public.recurso_autores VALUES (49, 34);
INSERT INTO public.recurso_autores VALUES (160, 108);
INSERT INTO public.recurso_autores VALUES (160, 109);
INSERT INTO public.recurso_autores VALUES (160, 110);
INSERT INTO public.recurso_autores VALUES (112, 52);
INSERT INTO public.recurso_autores VALUES (112, 53);
INSERT INTO public.recurso_autores VALUES (147, 52);
INSERT INTO public.recurso_autores VALUES (147, 53);
INSERT INTO public.recurso_autores VALUES (148, 42);
INSERT INTO public.recurso_autores VALUES (164, 111);
INSERT INTO public.recurso_autores VALUES (164, 112);
INSERT INTO public.recurso_autores VALUES (164, 113);
INSERT INTO public.recurso_autores VALUES (164, 114);
INSERT INTO public.recurso_autores VALUES (166, 116);
INSERT INTO public.recurso_autores VALUES (166, 117);
INSERT INTO public.recurso_autores VALUES (166, 118);


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
INSERT INTO public.recurso_clasificaciones VALUES (99, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (100, 9, 17);
INSERT INTO public.recurso_clasificaciones VALUES (108, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (109, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (110, 9, NULL);
INSERT INTO public.recurso_clasificaciones VALUES (111, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (113, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (114, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (132, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (149, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (49, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (160, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (112, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (147, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (148, 9, 17);
INSERT INTO public.recurso_clasificaciones VALUES (164, 8, 13);
INSERT INTO public.recurso_clasificaciones VALUES (166, 8, 11);


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
INSERT INTO public.recursos VALUES (99, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378254_697.docx');
INSERT INTO public.recursos VALUES (100, 'il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas', 1, 2023, NULL);
INSERT INTO public.recursos VALUES (108, 'Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378813_891.docx');
INSERT INTO public.recursos VALUES (109, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”', 1, 2023, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1786457302_317.pdf');
INSERT INTO public.recursos VALUES (110, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787698529_393.docx');
INSERT INTO public.recursos VALUES (111, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787698700_582.pdf');
INSERT INTO public.recursos VALUES (113, 'Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787836105_952.docx');
INSERT INTO public.recursos VALUES (114, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787840266_406.pdf');
INSERT INTO public.recursos VALUES (132, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1788532202_175.docx');
INSERT INTO public.recursos VALUES (122, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');
INSERT INTO public.recursos VALUES (121, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (120, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (119, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (118, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');
INSERT INTO public.recursos VALUES (143, 'Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124890/98825');
INSERT INTO public.recursos VALUES (146, 'Modelamiento de confort adaptativo para un trapiche panelero', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/112625/91645');
INSERT INTO public.recursos VALUES (144, 'Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública', 3, 2026, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124428/98826');
INSERT INTO public.recursos VALUES (149, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO', 1, 2023, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1789088547_631.pdf');
INSERT INTO public.recursos VALUES (150, 'e', 3, 2026, 'https://www.youtube.com/');
INSERT INTO public.recursos VALUES (49, 'Sistema de Información Automatizado para la Gestión de Inventario y Suministros Médicos', 1, 2026, 'proyecto_inventario_medico.pdf');
INSERT INTO public.recursos VALUES (160, 'Edo. Trujillo Soporte Técnico A Equipos Y Usuarios De Computación Del Infocentro De Escuque', 1, 2022, 'storage/documentos/pst/pst_edo__trujillo_soporte_t__cnico_1790279574_121.docx');
INSERT INTO public.recursos VALUES (112, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1787698715_771.docx');
INSERT INTO public.recursos VALUES (147, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A', 1, 2026, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1789794852.pdf');
INSERT INTO public.recursos VALUES (148, 'VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...', 1, 2023, 'storage/documentos/pst/pst_valera_edo_trujillo_aplicaci___1789088548_770.docx');
INSERT INTO public.recursos VALUES (164, 'NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera', 1, 2026, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1790303536_984.docx');
INSERT INTO public.recursos VALUES (166, 'NUES DR. PABLO VILORIA - LA BEATRIZ SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DEL LABORATORIO 1 - INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY (UPTTMBI)”', 1, 2019, 'storage/documentos/pst/pst_nues_dr__pablo_viloria___la_be_1790306314_103.docx');


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);
INSERT INTO public.registro_actividad VALUES (3, 12, NULL, '2026-09-19 14:19:44.068925', '2026-09-19 14:20:25.834661', 2);
INSERT INTO public.registro_actividad VALUES (4, 17, NULL, '2026-09-19 14:57:43.371674', '2026-09-19 14:58:00.847148', 2);
INSERT INTO public.registro_actividad VALUES (5, 20, NULL, '2026-09-24 15:47:51.683653', '2026-09-24 16:24:35.034052', 8);
INSERT INTO public.registro_actividad VALUES (2, 7, NULL, '2026-09-19 13:23:18.589777', '2026-09-24 23:09:06.604365', 20);
INSERT INTO public.registro_actividad VALUES (6, 14, NULL, '2026-09-24 23:22:14.086172', '2026-09-24 23:22:14.086172', 1);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (1, 'Super Administrador', 1);
INSERT INTO public.roles VALUES (3, 'Estudiantes', 6);
INSERT INTO public.roles VALUES (4, 'Docentes', 3);
INSERT INTO public.roles VALUES (2, 'Comité', 2);
INSERT INTO public.roles VALUES (5, 'Pepe', 11);


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
INSERT INTO public.system_audit_log VALUES ('log_6ab578fb8c93c5.64589351', '2026-09-24 15:24:43', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80', 'cf239c7774327d6031502a49679ff8a371fbb226c6f9d510884ee421adb419ed');
INSERT INTO public.system_audit_log VALUES ('log_6ab578fec20326.03335490', '2026-09-24 15:24:46', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-24_15-24-42.sql.gz', 'Miguel González (ID: 7)', '::1', 'cf239c7774327d6031502a49679ff8a371fbb226c6f9d510884ee421adb419ed', '36456b09d552a6f001e100f56ab8b60df8af103249d6ca387cf29d6537efcc34');
INSERT INTO public.system_audit_log VALUES ('log_6ab579008d4874.56702771', '2026-09-24 15:24:48', 'WARNING', 'SuperAdmin', 'Eliminar Backup', 'Respaldo eliminado: pre_restore_checkpoint_2026-09-23_18-15-15.sql.gz', 'Miguel González (ID: 7)', '::1', '36456b09d552a6f001e100f56ab8b60df8af103249d6ca387cf29d6537efcc34', '143681f09af781b84f43cdd0f23e6fde1bf28386c94644e91158468d14666aa7');
INSERT INTO public.system_audit_log VALUES ('log_6ab5790c35fb47.78937127', '2026-09-24 15:25:00', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #158 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '143681f09af781b84f43cdd0f23e6fde1bf28386c94644e91158468d14666aa7', '963eda5aa0e44755b2835db857582c2f2c31ff3d57c9e87e779e275a7cbbca04');
INSERT INTO public.system_audit_log VALUES ('log_6ab57a6acf4285.03804230', '2026-09-24 15:30:50', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '963eda5aa0e44755b2835db857582c2f2c31ff3d57c9e87e779e275a7cbbca04', '949f9dc67a09393871c8f5904067ecb8cd79dc31a20dcf4530a3cf0f6636ac45');
INSERT INTO public.system_audit_log VALUES ('log_6ab57a83717e27.29688164', '2026-09-24 15:31:15', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: V-32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '949f9dc67a09393871c8f5904067ecb8cd79dc31a20dcf4530a3cf0f6636ac45', '13bafabefce1f22fa8581d67acb5fab52a6593ee12e64055ccb66c78d90ce945');
INSERT INTO public.system_audit_log VALUES ('log_6ab57a930b0b35.73070913', '2026-09-24 15:31:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '13bafabefce1f22fa8581d67acb5fab52a6593ee12e64055ccb66c78d90ce945', 'ad49db75613defa90a84e920d1968da64c143fe231b84cede0452e273b829338');
INSERT INTO public.system_audit_log VALUES ('log_6ab57a98599b24.07882250', '2026-09-24 15:31:36', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''Miguel''.', 'Anónimo / Sistema', '::1', 'ad49db75613defa90a84e920d1968da64c143fe231b84cede0452e273b829338', 'f3c0fddf2866d249f34e064ddaa11a3dcbc29b4d9cd75e25d71b3ed5da77054a');
INSERT INTO public.system_audit_log VALUES ('log_6ab57a9f14b946.12392623', '2026-09-24 15:31:43', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''Mikey''.', 'Anónimo / Sistema', '::1', 'f3c0fddf2866d249f34e064ddaa11a3dcbc29b4d9cd75e25d71b3ed5da77054a', '0be32ae56496e1b23ece5cbd4c5fad7f010889c85ca66a7355ef79e20c2921f8');
INSERT INTO public.system_audit_log VALUES ('log_6ab57aafec5e73.49186567', '2026-09-24 15:31:59', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '0be32ae56496e1b23ece5cbd4c5fad7f010889c85ca66a7355ef79e20c2921f8', 'c9f0012a952d670c69a43510ec17938d8ce8660f0060fa2e89bc0a9d5f39547c');
INSERT INTO public.system_audit_log VALUES ('log_6ab57ad524ca77.86071456', '2026-09-24 15:32:37', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #156 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', 'c9f0012a952d670c69a43510ec17938d8ce8660f0060fa2e89bc0a9d5f39547c', '0325e645fdd2d2490c7e6a1bf014c0866b72bf4bc5d35c80b958af66082c2958');
INSERT INTO public.system_audit_log VALUES ('log_6ab57ad6a962c7.09273957', '2026-09-24 15:32:38', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #151.', 'Miguel González (ID: 7)', '::1', '0325e645fdd2d2490c7e6a1bf014c0866b72bf4bc5d35c80b958af66082c2958', 'f315d1eb34d4dc2ff5289c8e4969f8d6e2f1c46237c19cd643dd26bac4fd96d5');
INSERT INTO public.system_audit_log VALUES ('log_6ab57adb0642a6.21716737', '2026-09-24 15:32:43', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #151 actualizado: ''ee''.', 'Miguel González (ID: 7)', '::1', 'f315d1eb34d4dc2ff5289c8e4969f8d6e2f1c46237c19cd643dd26bac4fd96d5', '9b6b5a8e453a44ddf9ad158cca8e1777444f92b179c2fd661042f162b0983621');
INSERT INTO public.system_audit_log VALUES ('log_6ab57add8e2563.72041859', '2026-09-24 15:32:45', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #151 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', '9b6b5a8e453a44ddf9ad158cca8e1777444f92b179c2fd661042f162b0983621', '052fec013303487f3ad5cfd3f70f16f8e03257c44d6bb022f74b17b03d0e3505');
INSERT INTO public.system_audit_log VALUES ('log_6ab57af99622a1.48453356', '2026-09-24 15:33:13', 'INFO', 'Articulos', 'Publicar Artículo', 'Nuevo artículo publicado: ''e'' (2026).', 'Miguel González (ID: 7)', '::1', '052fec013303487f3ad5cfd3f70f16f8e03257c44d6bb022f74b17b03d0e3505', 'd319eae730ca7cf009630d81b34a0cb476b62c292b9201e2f7283917821dde0a');
INSERT INTO public.system_audit_log VALUES ('log_6ab57b16eb6103.32536734', '2026-09-24 15:33:42', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #159 actualizado: ''e''.', 'Miguel González (ID: 7)', '::1', 'd319eae730ca7cf009630d81b34a0cb476b62c292b9201e2f7283917821dde0a', '628b2efee850119ec7c69aaaf4d6078301974d24817ae67d5395a5c22d8155cd');
INSERT INTO public.system_audit_log VALUES ('log_6ab57b1f6b6602.31996772', '2026-09-24 15:33:51', 'INFO', 'Articulos', 'Actualizar Artículo', 'Artículo ID #159 actualizado: ''e''.', 'Miguel González (ID: 7)', '::1', '628b2efee850119ec7c69aaaf4d6078301974d24817ae67d5395a5c22d8155cd', 'f39d3240574da3ca7d835b952a8ced5662473a47d53d9607c426dea9c0bc86a1');
INSERT INTO public.system_audit_log VALUES ('log_6ab57b30441318.47874717', '2026-09-24 15:34:08', 'INFO', 'Articulos', 'Crear Categoría', 'Nueva categoría: ''Andrus''.', 'Miguel González (ID: 7)', '::1', 'f39d3240574da3ca7d835b952a8ced5662473a47d53d9607c426dea9c0bc86a1', 'e5075709a408b05f57683a41bc5a6e150c6be0249a5f0915dd34d8052294bee7');
INSERT INTO public.system_audit_log VALUES ('log_6ab57b33737a20.78244276', '2026-09-24 15:34:11', 'WARNING', 'Articulos', 'Eliminar Categoría', 'Categoría ID #19 eliminada.', 'Miguel González (ID: 7)', '::1', 'e5075709a408b05f57683a41bc5a6e150c6be0249a5f0915dd34d8052294bee7', 'ef4d017b1f02c3d352e1b9ebf7003559547a03368a356d4ea936f6f36d58dfd8');
INSERT INTO public.system_audit_log VALUES ('log_6ab57d5e7a7831.02368102', '2026-09-24 15:43:26', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Vegeta (C.I: 777)', 'Miguel González (ID: 7)', '::1', 'ef4d017b1f02c3d352e1b9ebf7003559547a03368a356d4ea936f6f36d58dfd8', '3c87a2dc42191380e56c1634d79af56ff19f9072cca3893bd08143fa2ec53f78');
INSERT INTO public.system_audit_log VALUES ('log_6ab57da8825b49.80124118', '2026-09-24 15:44:40', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #18 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '3c87a2dc42191380e56c1634d79af56ff19f9072cca3893bd08143fa2ec53f78', '03c8aaa5b8e33c03527d811a2b73ed48db0dc9624e44a011e7987b57bd23b06a');
INSERT INTO public.system_audit_log VALUES ('log_6ab57db2704411.69895388', '2026-09-24 15:44:50', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: e (C.I: 777)', 'Miguel González (ID: 7)', '::1', '03c8aaa5b8e33c03527d811a2b73ed48db0dc9624e44a011e7987b57bd23b06a', '71b8530315b0ddf49c9ad26763eb32ae985b6bacb457119e08568ea916facc03');
INSERT INTO public.system_audit_log VALUES ('log_6ab57e5236a301.01088245', '2026-09-24 15:47:30', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #19 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '71b8530315b0ddf49c9ad26763eb32ae985b6bacb457119e08568ea916facc03', '7c03dcbef468fa0c65380adb6234f86ea3c6186d90bcb7bbe38b3a073cbf95cf');
INSERT INTO public.system_audit_log VALUES ('log_6ab57e53592d27.79788124', '2026-09-24 15:47:31', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '7c03dcbef468fa0c65380adb6234f86ea3c6186d90bcb7bbe38b3a073cbf95cf', '2b7dbe004922a5955cb512c58d213c15fc2af4b4661b2268ebe9103e20f88604');
INSERT INTO public.system_audit_log VALUES ('log_6ab57e67a59b86.06297819', '2026-09-24 15:47:51', 'INFO', 'Autenticacion', 'Registro de Usuario', 'Nuevo usuario registrado exitosamente: ''Vegeta'' (C.I: 777, Email: vegeta@gmail.com).', 'Anónimo / Sistema', '::1', '2b7dbe004922a5955cb512c58d213c15fc2af4b4661b2268ebe9103e20f88604', '236a04210f5add7a17d1b5c569371e2c66bb1e962fcee6379658a14242b4d2f7');
INSERT INTO public.system_audit_log VALUES ('log_6ab57e80553354.19305388', '2026-09-24 15:48:16', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Vegeta'' (ID: 20) cerró su sesión voluntariamente.', 'Vegeta (ID: 20)', '::1', '236a04210f5add7a17d1b5c569371e2c66bb1e962fcee6379658a14242b4d2f7', '8c18fae3559be835f596f5a4ffc846d2a7c119ff09fb7d5f20f4a4f20b00970d');
INSERT INTO public.system_audit_log VALUES ('log_6ab57e84e74311.35585900', '2026-09-24 15:48:20', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '8c18fae3559be835f596f5a4ffc846d2a7c119ff09fb7d5f20f4a4f20b00970d', '48a5d93234f1f9c825845ab8fe51e1f75542cf633d32ec82a241037b2c27f630');
INSERT INTO public.system_audit_log VALUES ('log_6ab5803c517701.34807514', '2026-09-24 15:55:40', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''Edo. Trujillo Soporte Técnico A Equipos Y Usuarios De Computación Del Infocentro De Escuque'' (ID: #160).', 'Miguel González (ID: 7)', '::1', '48a5d93234f1f9c825845ab8fe51e1f75542cf633d32ec82a241037b2c27f630', 'fa3ff20c626155a6cce11d47cd054634d272f109d28117209b225be7356540ec');
INSERT INTO public.system_audit_log VALUES ('log_6ab5820a513928.81654483', '2026-09-24 16:03:22', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera'' (ID: #161).', 'Miguel González (ID: 7)', '::1', 'fa3ff20c626155a6cce11d47cd054634d272f109d28117209b225be7356540ec', 'cccdf54967d61966b1a57d6d2a98aa4af6d83f15570c8c29c3b426ca9103198a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5824f69bea2.65190419', '2026-09-24 16:04:31', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #161 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'cccdf54967d61966b1a57d6d2a98aa4af6d83f15570c8c29c3b426ca9103198a', '5e935cd408500779b60213d0da13ade2bda08ae2efa46ed8c9551d9bc553a984');
INSERT INTO public.system_audit_log VALUES ('log_6ab5826ea9a251.26204342', '2026-09-24 16:05:02', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #128 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '5e935cd408500779b60213d0da13ade2bda08ae2efa46ed8c9551d9bc553a984', 'd763e3342844ab48ea49c2c348c16536a432e25e9c4d0c7207871ada1d327a52');
INSERT INTO public.system_audit_log VALUES ('log_6ab5828a9fd668.55905191', '2026-09-24 16:05:30', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #127 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', 'd763e3342844ab48ea49c2c348c16536a432e25e9c4d0c7207871ada1d327a52', '380f6305a99a1cc056f8095a6e399390baf9323977c5855eb8b3ce90fec3b2ca');
INSERT INTO public.system_audit_log VALUES ('log_6ab5832cd82a06.97803084', '2026-09-24 16:08:12', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #147 cambiado a estado: Oculto.', 'Miguel González (ID: 7)', '::1', '380f6305a99a1cc056f8095a6e399390baf9323977c5855eb8b3ce90fec3b2ca', '9c0e55ee473f43a586a16b4b3c9391a5e7486a89182389e54ab95b4dbcd79afd');
INSERT INTO public.system_audit_log VALUES ('log_6ab5832f735fb5.27079013', '2026-09-24 16:08:15', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #147 cambiado a estado: Visible.', 'Miguel González (ID: 7)', '::1', '9c0e55ee473f43a586a16b4b3c9391a5e7486a89182389e54ab95b4dbcd79afd', '0fa3aa5e8da30d8d791145d233f8ab4fd58cbc25a4ce13253e7707370c743d7e');
INSERT INTO public.system_audit_log VALUES ('log_6ab583320226b8.12451685', '2026-09-24 16:08:18', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #147 cambiado a estado: Oculto.', 'Miguel González (ID: 7)', '::1', '0fa3aa5e8da30d8d791145d233f8ab4fd58cbc25a4ce13253e7707370c743d7e', 'e8d89b3e210cef58ff463656f099957b723db5291feaccc1afbcf266f7479a43');
INSERT INTO public.system_audit_log VALUES ('log_6ab5833e1aaaa5.67653642', '2026-09-24 16:08:30', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #147 cambiado a estado: Visible.', 'Miguel González (ID: 7)', '::1', 'e8d89b3e210cef58ff463656f099957b723db5291feaccc1afbcf266f7479a43', '22ab129659e6dca9f013270aae28d056494003d0fb8d8108127ad4ca318ecad6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5835fe15376.63308105', '2026-09-24 16:09:03', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '22ab129659e6dca9f013270aae28d056494003d0fb8d8108127ad4ca318ecad6', 'f9e411456e40b4f184b783eff5bcfd6ea54b0c014ff38f403dd25ef6a117b77f');
INSERT INTO public.system_audit_log VALUES ('log_6ab58368952b76.93847944', '2026-09-24 16:09:12', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'f9e411456e40b4f184b783eff5bcfd6ea54b0c014ff38f403dd25ef6a117b77f', '4c9e82c0f4e5c99f01e2db4dc08d7b615e2a46b44a12086e789d83145c0477a1');
INSERT INTO public.system_audit_log VALUES ('log_6ab58373c63fe5.40374571', '2026-09-24 16:09:23', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '4c9e82c0f4e5c99f01e2db4dc08d7b615e2a46b44a12086e789d83145c0477a1', '789fe628caccb90653fd75cb095faf9226a68db89bc1a2ad4f01d619b6ca4d3e');
INSERT INTO public.system_audit_log VALUES ('log_6ab58380e5fb51.04968685', '2026-09-24 16:09:36', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '789fe628caccb90653fd75cb095faf9226a68db89bc1a2ad4f01d619b6ca4d3e', 'dcf54ed0fc77fa8431ec6edc8c20414cbc51ba019083ebcf6ee3e34a18e48fb1');
INSERT INTO public.system_audit_log VALUES ('log_6ab583cddb2617.41599782', '2026-09-24 16:10:53', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'dcf54ed0fc77fa8431ec6edc8c20414cbc51ba019083ebcf6ee3e34a18e48fb1', '854a0efa147a4aed2da21afb9d3efcd3ad7362e012ab6d54e81190b1fc1b2905');
INSERT INTO public.system_audit_log VALUES ('log_6ab583e7958061.59468589', '2026-09-24 16:11:19', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '854a0efa147a4aed2da21afb9d3efcd3ad7362e012ab6d54e81190b1fc1b2905', 'eefeb0b677e360e7749610a2de20eb231d7868f7ffc918e84bd613bd4d676302');
INSERT INTO public.system_audit_log VALUES ('log_6ab5841192fdb7.35843207', '2026-09-24 16:12:01', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'eefeb0b677e360e7749610a2de20eb231d7868f7ffc918e84bd613bd4d676302', 'd4df26c4c39530be158dbf457140ee9d9a961e42c58192ce93640db81561200b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5842ce18c06.88342638', '2026-09-24 16:12:28', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera'' (ID: #162).', 'Miguel González (ID: 7)', '::1', 'd4df26c4c39530be158dbf457140ee9d9a961e42c58192ce93640db81561200b', '1a565c51f596dc652db018042d0639fb3d80521c2ab3367f36bf32116be25acd');
INSERT INTO public.system_audit_log VALUES ('log_6ab58458358f52.95247208', '2026-09-24 16:13:12', 'WARNING', 'RepositorioPST', 'Fallo en Configuración', 'Petición rechazada por seguridad: Token CSRF no válido o expirado.', 'Miguel González (ID: 7)', '::1', '1a565c51f596dc652db018042d0639fb3d80521c2ab3367f36bf32116be25acd', '452a29cf69ba60ac11c3218b14c148d93202368e45e6cf097d6c59adc346a04b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5845bcbc7f5.57390318', '2026-09-24 16:13:15', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '452a29cf69ba60ac11c3218b14c148d93202368e45e6cf097d6c59adc346a04b', '7148ccadceeb6c5a6e2aa13abf57f9cc4344b16c755d84c5b4bf6db89342fdfa');
INSERT INTO public.system_audit_log VALUES ('log_6ab584b88fe922.49351500', '2026-09-24 16:14:48', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '7148ccadceeb6c5a6e2aa13abf57f9cc4344b16c755d84c5b4bf6db89342fdfa', 'ec60f47c880aaf2b59dea0646c9d7091569772b3b6711a42d65e1c1611c2b66d');
INSERT INTO public.system_audit_log VALUES ('log_6ab584d7ed6289.21758768', '2026-09-24 16:15:19', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ec60f47c880aaf2b59dea0646c9d7091569772b3b6711a42d65e1c1611c2b66d', '494a30af92881b99ba4427cb565ecdd1b4fbde4c59d44d76c9ce21e90762ac4b');
INSERT INTO public.system_audit_log VALUES ('log_6ab584e390dd98.63911893', '2026-09-24 16:15:31', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '494a30af92881b99ba4427cb565ecdd1b4fbde4c59d44d76c9ce21e90762ac4b', '9cace7140bb73562823cf5d8f6099f2c721407a9a511fbf5383f9260b14ad96a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5851f2a6b03.10298013', '2026-09-24 16:16:31', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '9cace7140bb73562823cf5d8f6099f2c721407a9a511fbf5383f9260b14ad96a', '618b3acf2bcfddb9fe190913bfa866809f8738d357170d0c8cc0e4514561a2c2');
INSERT INTO public.system_audit_log VALUES ('log_6ab5852570ab95.85698009', '2026-09-24 16:16:37', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '618b3acf2bcfddb9fe190913bfa866809f8738d357170d0c8cc0e4514561a2c2', '91bd72377f09c8235cc3a860a0a21763ae2caedced0b4ba3858bb0455bd4d114');
INSERT INTO public.system_audit_log VALUES ('log_6ab585289db6c1.23009317', '2026-09-24 16:16:40', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '91bd72377f09c8235cc3a860a0a21763ae2caedced0b4ba3858bb0455bd4d114', '5e7f858ebd6b259420c01112660228c3a1711145c820bdbd939b5e2990e9afd9');
INSERT INTO public.system_audit_log VALUES ('log_6ab5852cb7e355.07272197', '2026-09-24 16:16:44', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '5e7f858ebd6b259420c01112660228c3a1711145c820bdbd939b5e2990e9afd9', '0bf3f2c8b65797a3b7d53a6b1225015fbdd7510ac930527f6ffa623cfe022906');
INSERT INTO public.system_audit_log VALUES ('log_6ab58533bf9d88.00377895', '2026-09-24 16:16:51', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '0bf3f2c8b65797a3b7d53a6b1225015fbdd7510ac930527f6ffa623cfe022906', '056cafc402756ec17bafcbc8b957e2cb2fc1b23cbdbf1d1b6dd678aefc01801c');
INSERT INTO public.system_audit_log VALUES ('log_6ab5853a030b81.71565253', '2026-09-24 16:16:58', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '056cafc402756ec17bafcbc8b957e2cb2fc1b23cbdbf1d1b6dd678aefc01801c', 'd89ab9b5fc6037c6e4a16df25a755acf60aec80d1bd661ac6661edf94e6e3bbe');
INSERT INTO public.system_audit_log VALUES ('log_6ab58553b74de2.92032150', '2026-09-24 16:17:23', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'd89ab9b5fc6037c6e4a16df25a755acf60aec80d1bd661ac6661edf94e6e3bbe', 'c284bd8084dee84bae14757c7dd070b925176e4bd82d6a8ebd3a4f14b7da9c1f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5856cbde985.61968868', '2026-09-24 16:17:48', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'c284bd8084dee84bae14757c7dd070b925176e4bd82d6a8ebd3a4f14b7da9c1f', '05632fac68a424872c29bda868f16d9669d7ee292666a26d63a403448a85423c');
INSERT INTO public.system_audit_log VALUES ('log_6ab5856df26740.28246197', '2026-09-24 16:17:49', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '05632fac68a424872c29bda868f16d9669d7ee292666a26d63a403448a85423c', 'a3ca2261a47af6685f6f3c27f85393fc2d170cbdda0bd9ab8c94ab170472c30e');
INSERT INTO public.system_audit_log VALUES ('log_6ab585778c5790.48596428', '2026-09-24 16:17:59', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'a3ca2261a47af6685f6f3c27f85393fc2d170cbdda0bd9ab8c94ab170472c30e', '84611893bbea8f6b6d8eff53ff2258d12baf0b766a0100a86ace0108941f3992');
INSERT INTO public.system_audit_log VALUES ('log_6ab5857d03f746.83279241', '2026-09-24 16:18:05', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '84611893bbea8f6b6d8eff53ff2258d12baf0b766a0100a86ace0108941f3992', '0eeda219be70ddd43c7d314391fcdcb87cdf0f06a1a67ae2a9a017dd287b581a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5858d605621.62591171', '2026-09-24 16:18:21', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #162 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '0eeda219be70ddd43c7d314391fcdcb87cdf0f06a1a67ae2a9a017dd287b581a', '32360ebbfcbfc436ad5290cd200bee3cde6fc15133b659771755c9c0345a6391');
INSERT INTO public.system_audit_log VALUES ('log_6ab585c26a6a07.22170215', '2026-09-24 16:19:14', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''NUES DR. PABLO VILORIA – LA BEATRIZ Soporte técnico a equipos de computación y capacitación a usuarios del CBIT “Rafael Rangel” del municipio Valera'' (ID: #163).', 'Miguel González (ID: 7)', '::1', '32360ebbfcbfc436ad5290cd200bee3cde6fc15133b659771755c9c0345a6391', 'fc543bd253b8449cf3b311336c6024d4aa20c34c88f61fad0eab399790184156');
INSERT INTO public.system_audit_log VALUES ('log_6ab585d3916dc8.07869938', '2026-09-24 16:19:31', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', 'fc543bd253b8449cf3b311336c6024d4aa20c34c88f61fad0eab399790184156', '3fede4138e9af89c995fbf88ab48f5b8bf4f166b670df3aafa8a7ff6c07b93f2');
INSERT INTO public.system_audit_log VALUES ('log_6ab586170f9912.39905272', '2026-09-24 16:20:39', 'CRITICAL', 'SuperAdmin', 'Revocar Sesión Remota', 'Sesión expulsada para el Usuario ID #20', 'Miguel González (ID: 7)', '::1', '3fede4138e9af89c995fbf88ab48f5b8bf4f166b670df3aafa8a7ff6c07b93f2', 'f7dbfb5b3447f2632b3a8891f3584808987272883797ae36abf53ecaf6e60571');
INSERT INTO public.system_audit_log VALUES ('log_6ab586212f5a91.30309101', '2026-09-24 16:20:49', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 777 (Vegetas). Se forzó cambio de contraseña. Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'f7dbfb5b3447f2632b3a8891f3584808987272883797ae36abf53ecaf6e60571', '919f94dd37acb98842b1f1360e23ba65df994c61c655bee69628145112f28250');
INSERT INTO public.system_audit_log VALUES ('log_6ab5862e1b1bf8.39862025', '2026-09-24 16:21:02', 'WARNING', 'Autenticacion', 'Login Fallido', 'Contraseña incorrecta para el usuario ''Vegetas'' (C.I: 777).', 'Anónimo / Sistema', '::1', '919f94dd37acb98842b1f1360e23ba65df994c61c655bee69628145112f28250', '075906b226384703dee6edc9cb252ae07ed9a6abb92f32cd15064870c371c72d');
INSERT INTO public.system_audit_log VALUES ('log_6ab586340d9008.21155891', '2026-09-24 16:21:08', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '075906b226384703dee6edc9cb252ae07ed9a6abb92f32cd15064870c371c72d', '962e14afa3ea57304b7fb182d29a10959eb2e764e3bb1d892a1f9fc1c5829922');
INSERT INTO public.system_audit_log VALUES ('log_6ab5863cecfd42.09662295', '2026-09-24 16:21:16', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '962e14afa3ea57304b7fb182d29a10959eb2e764e3bb1d892a1f9fc1c5829922', '94c815f07b7d4bc85ff3b14e30c10b19d7b0099a0014f080be1ac550264f81f3');
INSERT INTO public.system_audit_log VALUES ('log_6ab58642133426.30766366', '2026-09-24 16:21:22', 'CRITICAL', 'SuperAdmin', 'Revocar Sesión Remota', 'Sesión expulsada para el Usuario ID #20', 'Miguel González (ID: 7)', '::1', '94c815f07b7d4bc85ff3b14e30c10b19d7b0099a0014f080be1ac550264f81f3', 'd8d6bc45ac1f0654fe6633e8822291c828fce0bed43a209935522b93e8c18387');
INSERT INTO public.system_audit_log VALUES ('log_6ab5864a360708.55529252', '2026-09-24 16:21:30', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', 'd8d6bc45ac1f0654fe6633e8822291c828fce0bed43a209935522b93e8c18387', '205fafdb5c0c2f362ec2eab73ad3c0d02ee1eac115879f56d5e6e060f69cc59d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5864cc89616.03057644', '2026-09-24 16:21:32', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Vegetas'' (ID: 20) cerró su sesión voluntariamente.', 'Vegetas (ID: 20)', '::1', '205fafdb5c0c2f362ec2eab73ad3c0d02ee1eac115879f56d5e6e060f69cc59d', '97aacdbd94c7df16a0fc69d93ca46b60d336a42b00550a5a4ea1de0a1ba4a5f7');
INSERT INTO public.system_audit_log VALUES ('log_6ab5864f361ed9.06775417', '2026-09-24 16:21:35', 'CRITICAL', 'SuperAdmin', 'Revocar Sesión Remota', 'Sesión expulsada para el Usuario ID #20', 'Miguel González (ID: 7)', '::1', '97aacdbd94c7df16a0fc69d93ca46b60d336a42b00550a5a4ea1de0a1ba4a5f7', '3514a9bffe451aac652a4a47fee32b6bb3de3cbccb4ac827feaec37f0ad21957');
INSERT INTO public.system_audit_log VALUES ('log_6ab58654610969.60297632', '2026-09-24 16:21:40', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '3514a9bffe451aac652a4a47fee32b6bb3de3cbccb4ac827feaec37f0ad21957', '1e049e6e90ab54ed251d6f5b34fdaaa5ba951276593aa43160cfab361833c01d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5868982e943.61346793', '2026-09-24 16:22:33', 'WARNING', 'SuperAdmin', 'Suspender Cuenta Usuario', 'Estado de la cuenta del Usuario C.I. 777 cambiado a: Suspendido', 'Miguel González (ID: 7)', '::1', '1e049e6e90ab54ed251d6f5b34fdaaa5ba951276593aa43160cfab361833c01d', '12049f16967a1e68c11b71fc8d2007bcd5ec1174d58ff35d5b8fcee6ad15bfb3');
INSERT INTO public.system_audit_log VALUES ('log_6ab586aa758c20.87555981', '2026-09-24 16:23:06', 'WARNING', 'Autenticacion', 'Acceso Bloqueado', 'Intento de acceso a cuenta suspendida: ''Vegetas'' (C.I: 777).', 'Anónimo / Sistema', '::1', '12049f16967a1e68c11b71fc8d2007bcd5ec1174d58ff35d5b8fcee6ad15bfb3', '93aaf99374a6258d1f317998e24a20903d086217b83e57bce10e560fa6076c54');
INSERT INTO public.system_audit_log VALUES ('log_6ab586b00d8375.80133711', '2026-09-24 16:23:12', 'INFO', 'SuperAdmin', 'Restaurar Cuenta Usuario', 'Estado de la cuenta del Usuario C.I. 777 cambiado a: Activo', 'Miguel González (ID: 7)', '::1', '93aaf99374a6258d1f317998e24a20903d086217b83e57bce10e560fa6076c54', '9e88f1142bc4091198918c99ae669ca81ae2160599a4f93bb2030b5faf42008b');
INSERT INTO public.system_audit_log VALUES ('log_6ab586b9adf998.35089780', '2026-09-24 16:23:21', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '9e88f1142bc4091198918c99ae669ca81ae2160599a4f93bb2030b5faf42008b', '2fff97edac7450302cf2cdf857358e37fe923ca2dc54e9c80e85ee9946828797');
INSERT INTO public.system_audit_log VALUES ('log_6ab586bff31826.60365561', '2026-09-24 16:23:27', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '2fff97edac7450302cf2cdf857358e37fe923ca2dc54e9c80e85ee9946828797', '7f854175bcc98a5e18a1574353b6346b309e7a254ba8dc4a238b1793065eef0b');
INSERT INTO public.system_audit_log VALUES ('log_6ab586c6f37181.06679544', '2026-09-24 16:23:34', 'WARNING', 'SuperAdmin', 'Suspender Cuenta Usuario', 'Estado de la cuenta del Usuario C.I. 777 cambiado a: Suspendido', 'Miguel González (ID: 7)', '::1', '7f854175bcc98a5e18a1574353b6346b309e7a254ba8dc4a238b1793065eef0b', '949013a846fb8dcd13532a3809345fe53a5221a38da968b269d729896b87ec1a');
INSERT INTO public.system_audit_log VALUES ('log_6ab586ed7b6a91.24166099', '2026-09-24 16:24:13', 'INFO', 'SuperAdmin', 'Restaurar Cuenta Usuario', 'Estado de la cuenta del Usuario C.I. 777 cambiado a: Activo', 'Miguel González (ID: 7)', '::1', '949013a846fb8dcd13532a3809345fe53a5221a38da968b269d729896b87ec1a', '296e675cdb999d3f30036f7b0e4aa7b497870a9f412a9861ab646fb3803bbf06');
INSERT INTO public.system_audit_log VALUES ('log_6ab58703088e84.36937987', '2026-09-24 16:24:35', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Vegetas'' (C.I: 777, Rol: Estudiantes).', 'Vegetas (ID: 20)', '::1', '296e675cdb999d3f30036f7b0e4aa7b497870a9f412a9861ab646fb3803bbf06', 'a97a1cd16b4e222fe59d96a860c738a1a6c456c2c0a29b30d748a97fe2186ad0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5870a6d7d43.78104101', '2026-09-24 16:24:42', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #20 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', 'a97a1cd16b4e222fe59d96a860c738a1a6c456c2c0a29b30d748a97fe2186ad0', '64a25b3e5e210ac80676cace3925eb75e05e0f05936dd95226b513b5b7666313');
INSERT INTO public.system_audit_log VALUES ('log_6ab587121df152.36656438', '2026-09-24 16:24:50', 'WARNING', 'Autenticacion', 'Login Fallido', 'Intento de inicio de sesión con cédula no registrada: ''777''.', 'Anónimo / Sistema', '::1', '64a25b3e5e210ac80676cace3925eb75e05e0f05936dd95226b513b5b7666313', 'f68bd7f3e3f9c9797f2dbc55cf5e4e6057c765a2fe0dfb06d55e5d242f99461b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5871a079f95.98618045', '2026-09-24 16:24:58', 'WARNING', 'SuperAdmin', 'Extender Privilegios', 'Se ha creado un nuevo nivel jerárquico en el sistema.', 'Miguel González (ID: 7)', '::1', 'f68bd7f3e3f9c9797f2dbc55cf5e4e6057c765a2fe0dfb06d55e5d242f99461b', 'c4defcff7bcd93a2fb9bd0dbea005f9a914e05176018f7d67aa83a5dd0d3a174');
INSERT INTO public.system_audit_log VALUES ('log_6ab5871d5f2f47.31929296', '2026-09-24 16:25:01', 'WARNING', 'SuperAdmin', 'Eliminar Nivel', 'Nivel de privilegio 6 eliminado de la BD y purgado del RBAC.', 'Miguel González (ID: 7)', '::1', 'c4defcff7bcd93a2fb9bd0dbea005f9a914e05176018f7d67aa83a5dd0d3a174', '001d606d48b6c5b7cc31c412df8c946e64d41be1c437c84afff3e76b484f979c');
INSERT INTO public.system_audit_log VALUES ('log_6ab5871fccfdb6.04274399', '2026-09-24 16:25:03', 'WARNING', 'SuperAdmin', 'Extender Privilegios', 'Se ha creado un nuevo nivel jerárquico en el sistema.', 'Miguel González (ID: 7)', '::1', '001d606d48b6c5b7cc31c412df8c946e64d41be1c437c84afff3e76b484f979c', '6d73e93daa7691ed9cb6cb4bd560d93881ae1bf8a3b2dd796b639b2844e2b378');
INSERT INTO public.system_audit_log VALUES ('log_6ab5872640d201.63623698', '2026-09-24 16:25:10', 'WARNING', 'SuperAdmin', 'Modificar Matriz RBAC', 'Se actualizaron los permisos granulares por Módulo y Nivel. Sesiones sincronizadas.', 'Miguel González (ID: 7)', '::1', '6d73e93daa7691ed9cb6cb4bd560d93881ae1bf8a3b2dd796b639b2844e2b378', 'c46fa36d9004cd184da01f3a9c28e1877031bfc4dfc0d569cae6601dcd4d8d18');
INSERT INTO public.system_audit_log VALUES ('log_6ab5873b6d7907.12314484', '2026-09-24 16:25:31', 'INFO', 'SuperAdmin', 'Modificar Rol', 'Rol ID #2 actualizado a ''Comité'' (Nivel: 6). Sesiones remotas de usuarios revocadas.', 'Miguel González (ID: 7)', '::1', 'c46fa36d9004cd184da01f3a9c28e1877031bfc4dfc0d569cae6601dcd4d8d18', 'f28b188dd1830a9fa8ea0f24b3238efdb549a4ab47bac684e542a0b1c69ea76d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5873fcbebd5.02467756', '2026-09-24 16:25:35', 'INFO', 'SuperAdmin', 'Modificar Rol', 'Rol ID #2 actualizado a ''Comité'' (Nivel: 2). Sesiones remotas de usuarios revocadas.', 'Miguel González (ID: 7)', '::1', 'f28b188dd1830a9fa8ea0f24b3238efdb549a4ab47bac684e542a0b1c69ea76d', '8bfd4fd8a0786429e49eac04a46fc8fe6a8fe770561139b518e17577215221be');
INSERT INTO public.system_audit_log VALUES ('log_6ab58763472f07.92797183', '2026-09-24 16:26:11', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '8bfd4fd8a0786429e49eac04a46fc8fe6a8fe770561139b518e17577215221be', '2c314ca705ad2ab84b45c00333f199755e3f6360f9c68f4b369728887f370d5c');
INSERT INTO public.system_audit_log VALUES ('log_6ab587642ccac6.70540629', '2026-09-24 16:26:12', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '2c314ca705ad2ab84b45c00333f199755e3f6360f9c68f4b369728887f370d5c', '4ea971519e544d92f72b7ee0a9f9c8893df92fa1ecc9b1e846ea8f6288b20d1e');
INSERT INTO public.system_audit_log VALUES ('log_6ab587656aef43.74349460', '2026-09-24 16:26:13', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '4ea971519e544d92f72b7ee0a9f9c8893df92fa1ecc9b1e846ea8f6288b20d1e', '731d44ab1a298b75208969196235ca91f807c7d869238abc2a3db7353753c454');
INSERT INTO public.system_audit_log VALUES ('log_6ab58766329f42.21263929', '2026-09-24 16:26:14', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '731d44ab1a298b75208969196235ca91f807c7d869238abc2a3db7353753c454', 'f2a2ae265ff5820c582e9548d1db1842cd251f4cb7720c5a77cf281bbd62c7d5');
INSERT INTO public.system_audit_log VALUES ('log_6ab587671aba98.91320487', '2026-09-24 16:26:15', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo RepositorioPST cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'f2a2ae265ff5820c582e9548d1db1842cd251f4cb7720c5a77cf281bbd62c7d5', '92493189909758adfd1cda3496d00550b0cbaeb7b632de48159d4738c130ba15');
INSERT INTO public.system_audit_log VALUES ('log_6ab587682e7df3.13692140', '2026-09-24 16:26:16', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '92493189909758adfd1cda3496d00550b0cbaeb7b632de48159d4738c130ba15', '80b277a542d5eeef512b04b60575f4b4e9d3b1b77968a8feaab95229e5d56b03');
INSERT INTO public.system_audit_log VALUES ('log_6ab58778f3fe65.33640872', '2026-09-24 16:26:32', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '80b277a542d5eeef512b04b60575f4b4e9d3b1b77968a8feaab95229e5d56b03', '9d414503439e398ccdaf192c48fae4182dcee11c83cde0cd522a43132fc93e98');
INSERT INTO public.system_audit_log VALUES ('log_6ab587799bbbe2.26986222', '2026-09-24 16:26:33', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '9d414503439e398ccdaf192c48fae4182dcee11c83cde0cd522a43132fc93e98', 'aebed3d0e44cbb77fcac99c9c3c1e0c6ff67e08c008f8227e3cb4efec9149e12');
INSERT INTO public.system_audit_log VALUES ('log_6ab5877b876253.12629689', '2026-09-24 16:26:35', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'aebed3d0e44cbb77fcac99c9c3c1e0c6ff67e08c008f8227e3cb4efec9149e12', 'b9c2bb07858bd11b796e4626e227296d510fbc424e803683af631e56f52f734b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5877c4fb3f1.24227058', '2026-09-24 16:26:36', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'b9c2bb07858bd11b796e4626e227296d510fbc424e803683af631e56f52f734b', 'e85718d647929bb83e8ffd1b6e65c5bbcc8220cb8866e1fe36bbf9eb342dc74b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5877d4b47f6.59948112', '2026-09-24 16:26:37', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo RepositorioPST cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'e85718d647929bb83e8ffd1b6e65c5bbcc8220cb8866e1fe36bbf9eb342dc74b', '5da7b53da21b496ba979826f0df3f82c0e3d613d597643323292df903bd69613');
INSERT INTO public.system_audit_log VALUES ('log_6ab5877f2f2c15.78435095', '2026-09-24 16:26:39', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '5da7b53da21b496ba979826f0df3f82c0e3d613d597643323292df903bd69613', '4eb9421c8a795c1bdbfae558a5d0309057706ecc7d58c5302fce81b83efc5b5a');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a131c1d9.76666467', '2026-09-24 16:27:13', 'WARNING', 'SuperAdmin', 'Importar Configuración', 'Configuración global restaurada desde archivo importado.', 'Miguel González (ID: 7)', '::1', '4eb9421c8a795c1bdbfae558a5d0309057706ecc7d58c5302fce81b83efc5b5a', '3db4690bc3e902781bc258b8d06a802544b71e8ede832e256b368edce88ec009');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a6120a14.82547692', '2026-09-24 16:27:18', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '3db4690bc3e902781bc258b8d06a802544b71e8ede832e256b368edce88ec009', 'ba39f76a2c2eea0b939c585d7bc1bf7f24c5f78c7bc436be44764c4e450af1e0');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a7cf40d6.34919315', '2026-09-24 16:27:19', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'ba39f76a2c2eea0b939c585d7bc1bf7f24c5f78c7bc436be44764c4e450af1e0', 'c1f326577e3f1b57c61e3e9d8c39b000aced49c7bac3b9b539aab26affb7513a');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a862d327.38971757', '2026-09-24 16:27:20', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'c1f326577e3f1b57c61e3e9d8c39b000aced49c7bac3b9b539aab26affb7513a', '45fdf7d3ab7ee4ff5fde14702601eefd691811d43f8f2b1a4624ce0a01edec79');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a91d83b3.91651871', '2026-09-24 16:27:21', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '45fdf7d3ab7ee4ff5fde14702601eefd691811d43f8f2b1a4624ce0a01edec79', 'fe0c808a076b1e1daadaaf1ef2e5dcb46f2b7bac7e8f8ec1dbf31985ff3bae20');
INSERT INTO public.system_audit_log VALUES ('log_6ab587a9d4fd73.78342723', '2026-09-24 16:27:21', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo RepositorioPST cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'fe0c808a076b1e1daadaaf1ef2e5dcb46f2b7bac7e8f8ec1dbf31985ff3bae20', 'a3793af87650cc52a7c350ea2f81f426996885ebbb61488af69e7ae4dbd652af');
INSERT INTO public.system_audit_log VALUES ('log_6ab587aad0f040.72789589', '2026-09-24 16:27:22', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'a3793af87650cc52a7c350ea2f81f426996885ebbb61488af69e7ae4dbd652af', '29da806575b226bb31eb65f875b13b5ef6f8dfc935dacf51a4a33eec3271a069');
INSERT INTO public.system_audit_log VALUES ('log_6ab5880d9498d1.18995287', '2026-09-24 16:29:01', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', '29da806575b226bb31eb65f875b13b5ef6f8dfc935dacf51a4a33eec3271a069', 'c2d683ce5dbc36dd742298c5da92f78ed70da631f3a200e94764187fe70a0fd6');
INSERT INTO public.system_audit_log VALUES ('log_6ab58815bcb915.80775320', '2026-09-24 16:29:09', 'WARNING', 'SuperAdmin', 'Modificar Variables Globales', 'Se actualizaron las variables de entorno del sistema.', 'Miguel González (ID: 7)', '::1', 'c2d683ce5dbc36dd742298c5da92f78ed70da631f3a200e94764187fe70a0fd6', '90b5805f310674cba710dd838722f226e3369eaf4b684c2879ac88d765439aaa');
INSERT INTO public.system_audit_log VALUES ('log_6ab5895e41be82.03687394', '2026-09-24 16:34:38', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #159.', 'Miguel González (ID: 7)', '::1', '90b5805f310674cba710dd838722f226e3369eaf4b684c2879ac88d765439aaa', 'e8525e6dac62de1a17f9451bdad6f5ba67000f2acc2d2172f3ec8d874e9b8936');
INSERT INTO public.system_audit_log VALUES ('log_6ab58960661766.37906369', '2026-09-24 16:34:40', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #159.', 'Miguel González (ID: 7)', '::1', 'e8525e6dac62de1a17f9451bdad6f5ba67000f2acc2d2172f3ec8d874e9b8936', '68b2ab687fdffc924ed5babfe5ab20d584c1f1eacbbb2d6b8cd5e1b1c37f5d11');
INSERT INTO public.system_audit_log VALUES ('log_6ab58965ce22d4.16600942', '2026-09-24 16:34:45', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #159 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', '68b2ab687fdffc924ed5babfe5ab20d584c1f1eacbbb2d6b8cd5e1b1c37f5d11', 'c7ebeceb046a8f426866d0d62cadf6169db1c9b170428ff6a257dc81ea217956');
INSERT INTO public.system_audit_log VALUES ('log_6ab589a033ec83.14920806', '2026-09-24 16:35:44', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #163 cambiado a estado: Oculto.', 'Miguel González (ID: 7)', '::1', 'c7ebeceb046a8f426866d0d62cadf6169db1c9b170428ff6a257dc81ea217956', '9c294807b4cb60078773ccddeb9a1bececa7b66bf729bbdcaa19413001e37e33');
INSERT INTO public.system_audit_log VALUES ('log_6ab589a294ce36.82578306', '2026-09-24 16:35:46', 'INFO', 'RepositorioPST', 'Alternar Visibilidad Proyecto', 'Proyecto ID #163 cambiado a estado: Visible.', 'Miguel González (ID: 7)', '::1', '9c294807b4cb60078773ccddeb9a1bececa7b66bf729bbdcaa19413001e37e33', 'd12a70ab3fc622cf00b1baee7f9a4df965c53abf3356619e8c6f7d4a0aa957ea');
INSERT INTO public.system_audit_log VALUES ('log_6ab58afbe43e31.39999289', '2026-09-24 16:41:31', 'WARNING', 'SuperAdmin', 'Crear Rol', 'Nuevo rol creado: Pepe (Privilegio ID: 12)', 'Miguel González (ID: 7)', '::1', 'd12a70ab3fc622cf00b1baee7f9a4df965c53abf3356619e8c6f7d4a0aa957ea', 'fe3fd4f0e8c7845046eaf039e30c3d2fd4f1623a2c761526069ae19b4d7ff0f9');
INSERT INTO public.system_audit_log VALUES ('log_6ab58fc60da930.80698750', '2026-09-24 17:01:58', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', 'fe3fd4f0e8c7845046eaf039e30c3d2fd4f1623a2c761526069ae19b4d7ff0f9', '0e81cdc80e682d54f0d902b498eb5f31afa7368c516bce390c0cb58c6e94012e');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d05a5c53c1.58348704', '2026-09-24 21:37:30', 'WARNING', 'SuperAdmin', 'Restaurar BD', 'Base de datos restaurada exitosamente.', 'Miguel González (ID: 7)', '::1', '0e81cdc80e682d54f0d902b498eb5f31afa7368c516bce390c0cb58c6e94012e', '755120f147e45bd5ffe578dea9e843399ed0d2ca99eddcc4c35d50375cbb9eb8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0a72b44f0.05451105', '2026-09-24 21:38:47', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '755120f147e45bd5ffe578dea9e843399ed0d2ca99eddcc4c35d50375cbb9eb8', '66dbf48353a84664223a098c6140b92070cad90ca96148c5850e37c671ec29de');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0ae537748.52087794', '2026-09-24 21:38:54', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', '66dbf48353a84664223a098c6140b92070cad90ca96148c5850e37c671ec29de', 'cad59b98f0ea2156bd976bb50d600dc8a2cbb9da852e00006a63d999decc89e9');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0cc128f35.05049190', '2026-09-24 21:39:24', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'cad59b98f0ea2156bd976bb50d600dc8a2cbb9da852e00006a63d999decc89e9', 'f67e8a7e24fbddf825e15945929bb8c65dc301177fc5b72b1ce9eadec46be60c');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0df4da858.17398397', '2026-09-24 21:39:43', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'f67e8a7e24fbddf825e15945929bb8c65dc301177fc5b72b1ce9eadec46be60c', '8ef6b498bc4b35ec2bc0307c9438df8f68f10f57ce1277e0a7a93242e7968159');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0dfc9fca6.77527342', '2026-09-24 21:39:43', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '8ef6b498bc4b35ec2bc0307c9438df8f68f10f57ce1277e0a7a93242e7968159', '249366640215bac142dfd1f47663ac9d81bbdb2329104205c9d27fefe8cf66b9');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0e1875db6.39310598', '2026-09-24 21:39:45', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '249366640215bac142dfd1f47663ac9d81bbdb2329104205c9d27fefe8cf66b9', '1d4920e4f4af03662c0e386e8bcb58b9e4eb9e0cacda8fc6b4b05338f3dd9531');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0e23f50b0.23440512', '2026-09-24 21:39:46', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo RepositorioPST cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '1d4920e4f4af03662c0e386e8bcb58b9e4eb9e0cacda8fc6b4b05338f3dd9531', 'c3b51a0072406cce5a37cd86d45cbc2aa31d4af608e7c88c3d9f85d51fb321df');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0e373b258.17129447', '2026-09-24 21:39:47', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'c3b51a0072406cce5a37cd86d45cbc2aa31d4af608e7c88c3d9f85d51fb321df', '8384d44d5e657d12b09526fad03693b8548b2a1a1ad6656b09f82fdf41b21737');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0e47a2932.08419767', '2026-09-24 21:39:48', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '8384d44d5e657d12b09526fad03693b8548b2a1a1ad6656b09f82fdf41b21737', '696c97e41c0a12d1537b5043759d07a38d119230acbb623295385d9514249abd');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d0e80eb698.53635469', '2026-09-24 21:39:52', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '696c97e41c0a12d1537b5043759d07a38d119230acbb623295385d9514249abd', 'dc6272e4ec4689ee7193324857efda245e3f8b2a045b978c1428a7e51db3174c');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d1dd33ac30.57992399', '2026-09-24 21:43:57', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Vegeta (C.I: 777)', 'Miguel González (ID: 7)', '::1', 'dc6272e4ec4689ee7193324857efda245e3f8b2a045b978c1428a7e51db3174c', '64568f4b9d7ab7dcfa1cbd0e74987536ee265ad769e373c11a80115525d37bf9');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d1e8e11fe6.93197346', '2026-09-24 21:44:08', 'WARNING', 'SuperAdmin', 'Eliminar/Archivar Usuario', 'Usuario ID #21 archivado exitosamente. Credenciales liberadas.', 'Miguel González (ID: 7)', '::1', '64568f4b9d7ab7dcfa1cbd0e74987536ee265ad769e373c11a80115525d37bf9', '696ff861c0634069fa7fd4db1b6e0fe10ffbda395f9dcfb353019fc64bf3024b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d1f6152100.46590687', '2026-09-24 21:44:22', 'INFO', 'SuperAdmin', 'Crear Usuario', 'Nuevo usuario registrado: Vegeta (C.I: 777)', 'Miguel González (ID: 7)', '::1', '696ff861c0634069fa7fd4db1b6e0fe10ffbda395f9dcfb353019fc64bf3024b', '90cc5c7fd12d3a09f4a6c676589341aa3ab09cad2d97c54e6f9a845279df604e');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d2874a6e89.04442235', '2026-09-24 21:46:47', 'INFO', 'SuperAdmin', 'Modificar Rol', 'Rol ID #5 actualizado a ''Pepe'' (Nivel: 11). Sesiones remotas de usuarios revocadas.', 'Miguel González (ID: 7)', '::1', '90cc5c7fd12d3a09f4a6c676589341aa3ab09cad2d97c54e6f9a845279df604e', 'cb8c54334e03e1b70d15272145f3fa8d45989b25897c52c171b7e4322e547a51');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d46ee309c4.79815623', '2026-09-24 21:54:54', 'INFO', 'SuperAdmin', 'Programar Mantenimiento', 'Ventana agendada para: 2026-09-24 21:55:00', 'Miguel González (ID: 7)', '::1', 'cb8c54334e03e1b70d15272145f3fa8d45989b25897c52c171b7e4322e547a51', '8a835b4e6d91d6e75bbf65b9043ee4388fe99c1fbce30d0a176ac9c1bfa6b2d4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d529832452.76735404', '2026-09-24 21:58:01', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo auto_cron_backup_2026-09-24_21-58-01.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', '8a835b4e6d91d6e75bbf65b9043ee4388fe99c1fbce30d0a176ac9c1bfa6b2d4', '19d2cc98f96d0e8c2c93fb6daf0fa434a2f2861fef95ffb6ce2b3973e168d507');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d5298c8af6.36922680', '2026-09-24 21:58:01', 'INFO', 'SuperAdmin', 'Ejecución Tarea Programada', 'Tarea ''Respaldo Automático de Medianoche (PostgreSQL Dump)'': ÉXITO (340.58ms): Respaldo generado con éxito: auto_cron_backup_2026-09-24_21-58-01.sql.gz', 'Miguel González (ID: 7)', '::1', '19d2cc98f96d0e8c2c93fb6daf0fa434a2f2861fef95ffb6ce2b3973e168d507', '1b0714ffade071f10bdc133b820de0bd468518aeee919f08d09a1f85d79e8eed');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d627d066a5.34792135', '2026-09-24 22:02:15', 'INFO', 'SuperAdmin', 'Optimizar BD', 'VACUUM ANALYZE ejecutado exitosamente.', 'Miguel González (ID: 7)', '::1', '1b0714ffade071f10bdc133b820de0bd468518aeee919f08d09a1f85d79e8eed', '3c03c419ba79ed5f4d901d008e6f9a75be59cc2f5cae870dfda3c69985922d36');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d637ca96d9.35321573', '2026-09-24 22:02:31', 'INFO', 'SuperAdmin', 'Optimizar BD', 'VACUUM ANALYZE ejecutado exitosamente.', 'Miguel González (ID: 7)', '::1', '3c03c419ba79ed5f4d901d008e6f9a75be59cc2f5cae870dfda3c69985922d36', 'be66798f81782de2b48b0e4c739f87de9dec7330410d02ef21ffa5f80fbbb471');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d7d29a6f34.40449602', '2026-09-24 22:09:22', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', 'be66798f81782de2b48b0e4c739f87de9dec7330410d02ef21ffa5f80fbbb471', '193262bf07e24e2627d0b4ec6021a59801ada43c152dc6aff24232e138d04453');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d7e19c33a7.91509292', '2026-09-24 22:09:37', 'INFO', 'SuperAdmin', 'Programar Mantenimiento', 'Ventana agendada para: 2026-09-25 22:09:00', 'Miguel González (ID: 7)', '::1', '193262bf07e24e2627d0b4ec6021a59801ada43c152dc6aff24232e138d04453', 'd3e30884a4a3c1954b6273f4d9dd41f81119c1e7f8fa464b9a66a634fefd2db3');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d7f8454a35.95838194', '2026-09-24 22:10:00', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', 'd3e30884a4a3c1954b6273f4d9dd41f81119c1e7f8fa464b9a66a634fefd2db3', '23ce6052c71fb90422462f5989fc2fdee4d4470dee45446c1a0c62316d1144df');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d7fc941ba5.73425060', '2026-09-24 22:10:04', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', '23ce6052c71fb90422462f5989fc2fdee4d4470dee45446c1a0c62316d1144df', '9fdcbb0dbe1279f82f302650ad8aea7b079bf0f92a9945ef8e6ee0a7cf58cf93');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d80fae15e6.20042563', '2026-09-24 22:10:23', 'INFO', 'SuperAdmin', 'Programar Mantenimiento', 'Ventana agendada para: 2032-12-24 22:10:00', 'Miguel González (ID: 7)', '::1', '9fdcbb0dbe1279f82f302650ad8aea7b079bf0f92a9945ef8e6ee0a7cf58cf93', '2b674f35bd98b9ae9fd504aebbf11d52a89f447342e181634766a97663205e84');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d81597f678.28650139', '2026-09-24 22:10:29', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', '2b674f35bd98b9ae9fd504aebbf11d52a89f447342e181634766a97663205e84', 'e3748977f1299318899905584fd10188f5b24fff48da28d37c7cbb97b5ff2cc3');
INSERT INTO public.system_audit_log VALUES ('log_6ab5d81e61c115.44749247', '2026-09-24 22:10:38', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', 'e3748977f1299318899905584fd10188f5b24fff48da28d37c7cbb97b5ff2cc3', '6fecaf7ddafdeab619edfb685d59adcbbed1118ead9b411d64e4ca9e525604ea');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dac44c9bd4.54604907', '2026-09-24 22:21:56', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '6fecaf7ddafdeab619edfb685d59adcbbed1118ead9b411d64e4ca9e525604ea', '6cf033f00380d701c2ad29f957f275ec32bff66e889e31d588532d2ce0f942ca');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dac94db8f6.46069311', '2026-09-24 22:22:01', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '6cf033f00380d701c2ad29f957f275ec32bff66e889e31d588532d2ce0f942ca', '7c0ec23c3c87a98c1846c8b98caeb761ea7ab10f9dfa03750eea81f252cbb0c4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dae47e5806.36480363', '2026-09-24 22:22:28', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '7c0ec23c3c87a98c1846c8b98caeb761ea7ab10f9dfa03750eea81f252cbb0c4', 'c167e10a636a62815b6749159b91d9e796b611d3823c087fc8c73a11c0726c46');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dae54f3ee5.09982335', '2026-09-24 22:22:29', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Investigaciones cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'c167e10a636a62815b6749159b91d9e796b611d3823c087fc8c73a11c0726c46', 'b9fdbb422ff0a963b593689b5731efb218752f6b3332513bb0981f039bb45b92');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dae858f7c8.39338949', '2026-09-24 22:22:32', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo LineasInvestigacion cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'b9fdbb422ff0a963b593689b5731efb218752f6b3332513bb0981f039bb45b92', '787a1e5b729856c54e19cae99d0e40bf7ac00409fce7f79c4b0fb54593aa374f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dae945bc70.49013373', '2026-09-24 22:22:33', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo RepositorioPST cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '787a1e5b729856c54e19cae99d0e40bf7ac00409fce7f79c4b0fb54593aa374f', 'cefb1edfa74bd785da9ab94221e6e0bafdc26bc2d9042af6f659ad47fdeac519');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dae9d9b741.30107480', '2026-09-24 22:22:33', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Cursos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'cefb1edfa74bd785da9ab94221e6e0bafdc26bc2d9042af6f659ad47fdeac519', 'bd5deb4ff57c62e3b1d204fab533133f3379ed4593e0e50b3348b9793f927657');
INSERT INTO public.system_audit_log VALUES ('log_6ab5daeaea65d0.53039477', '2026-09-24 22:22:34', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo VinculacionEmpresarial cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'bd5deb4ff57c62e3b1d204fab533133f3379ed4593e0e50b3348b9793f927657', 'bc7b00cbf365f2bf4d85da699b945209ac3addd8bc9cae4fdbb1d7ca0be97e1f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dcbb0c6142.59738745', '2026-09-24 22:30:19', 'INFO', 'RepositorioPST', 'Modificar Proyecto', 'Proyecto PST ID #148 modificado exitosamente: ''VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...''.', 'Miguel González (ID: 7)', '::1', 'bc7b00cbf365f2bf4d85da699b945209ac3addd8bc9cae4fdbb1d7ca0be97e1f', '63915b06086d0c8ee0630ae8b13ad23713941d9d9d9899c0e1f21fb8083b2985');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dcc45798a6.71087123', '2026-09-24 22:30:28', 'WARNING', 'RepositorioPST', 'Eliminar Proyecto', 'Proyecto PST ID #163 eliminado del repositorio.', 'Miguel González (ID: 7)', '::1', '63915b06086d0c8ee0630ae8b13ad23713941d9d9d9899c0e1f21fb8083b2985', '937d7873eba0ff06a25481b44d2ec9f7c8f26b33c2359bfa92d50cb8b8f14ca4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dd30757525.51951294', '2026-09-24 22:32:16', 'WARNING', 'RepositorioPST', 'Fallo en Extracción Masiva', 'Error al intentar guardar el PST ''Edo. Trujillo Soporte Técnico A Equipos Y Usuarios De Computación Del Infocentro De Escuque'': Ya existe un proyecto registrado con el título: ''Edo. Trujillo Soporte Técnico A Equipos Y Usuarios De Computación Del Infocentro De Escuque''', 'Miguel González (ID: 7)', '::1', '937d7873eba0ff06a25481b44d2ec9f7c8f26b33c2359bfa92d50cb8b8f14ca4', '2133309fc419042b34a63903c8ed949ea283da0c6c58e9dca1b8ed6f5e174fc7');
INSERT INTO public.system_audit_log VALUES ('log_6ab5dd79a77794.38461702', '2026-09-24 22:33:29', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '2133309fc419042b34a63903c8ed949ea283da0c6c58e9dca1b8ed6f5e174fc7', '4393c46edf77c07285b72f53b6d30f1bf4fbe6d0431c13321e4f8d565a82ffa8');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ddd50edbc8.78173080', '2026-09-24 22:35:01', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '4393c46edf77c07285b72f53b6d30f1bf4fbe6d0431c13321e4f8d565a82ffa8', '341dcf40853fda5a1f198d13b58c0b19480181b2fb337cf8ea5f5a37ca6af0f5');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e50a24fcc6.31833835', '2026-09-24 23:05:46', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', '341dcf40853fda5a1f198d13b58c0b19480181b2fb337cf8ea5f5a37ca6af0f5', '79a1fa0840ff8bc1722e17b496c96f9df484def09d21cb865f87cf50aa6b5834');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e50bc8b0f3.82864168', '2026-09-24 23:05:47', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', '79a1fa0840ff8bc1722e17b496c96f9df484def09d21cb865f87cf50aa6b5834', '23c654e3f3f15a469d5d8344f09c96ca8c8932a0ff76d0345dc0d509e280a297');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e531d98100.53110919', '2026-09-24 23:06:25', 'INFO', 'Articulos', 'Publicar Artículo', 'Nuevo artículo publicado: ''ASASDA'' (2026).', 'Miguel González (ID: 7)', '::1', '23c654e3f3f15a469d5d8344f09c96ca8c8932a0ff76d0345dc0d509e280a297', '0b65e2c163646563fa66763963b1dcd627c7c8a4625ab9da1c02dd002f6a7a20');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e550461a11.35870591', '2026-09-24 23:06:56', 'WARNING', 'Articulos', 'Eliminar Artículo', 'Artículo ID #165 eliminado del catálogo.', 'Miguel González (ID: 7)', '::1', '0b65e2c163646563fa66763963b1dcd627c7c8a4625ab9da1c02dd002f6a7a20', '9de179ab77c4fefd57dfe76ffc47756f9fd97073483bb52d74e03c36f1cf3eaa');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e5cd9abd78.29175026', '2026-09-24 23:09:01', 'INFO', 'Autenticacion', 'Cierre de Sesión', 'El usuario ''Miguel González'' (ID: 7) cerró su sesión voluntariamente.', 'Miguel González (ID: 7)', '::1', '9de179ab77c4fefd57dfe76ffc47756f9fd97073483bb52d74e03c36f1cf3eaa', '32309844325132baa3224dd8a0798d7a10980765c5fb6dfdc075ec997597aa86');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e5d293cc41.00805450', '2026-09-24 23:09:06', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Miguel González'' (C.I: 32621284, Rol: Super Administrador).', 'Miguel González (ID: 7)', '::1', '32309844325132baa3224dd8a0798d7a10980765c5fb6dfdc075ec997597aa86', 'ac7d2040b6d15050f086df0d98b616a30c060d2ae769256ecbe16d491e0bfd59');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e627c84902.96818407', '2026-09-24 23:10:31', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ac7d2040b6d15050f086df0d98b616a30c060d2ae769256ecbe16d491e0bfd59', '7994e72a3fe27b84bdad5c33d47286dd91402269a332e025a108e850ab5a602a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e63714ee00.69277833', '2026-09-24 23:10:47', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '7994e72a3fe27b84bdad5c33d47286dd91402269a332e025a108e850ab5a602a', 'beb9d7ce441a12a42aa031980551cc19540095c20cb07680639ae6952066dbb0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e63c989876.02721140', '2026-09-24 23:10:52', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'beb9d7ce441a12a42aa031980551cc19540095c20cb07680639ae6952066dbb0', '6ab565e83112d652c4aea305d6b0e122446bc1b29d03af2b16b9b6e671847ef2');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e6402c9676.98719092', '2026-09-24 23:10:56', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '6ab565e83112d652c4aea305d6b0e122446bc1b29d03af2b16b9b6e671847ef2', '52e4cf124a6bf8f23416659b818359465bbd971b2f3783e4698f5469887a1e1d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e64813ce31.01384447', '2026-09-24 23:11:04', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '52e4cf124a6bf8f23416659b818359465bbd971b2f3783e4698f5469887a1e1d', '6680d2867c22efa40afab42c3fa97b416d9c625765a7e2ff4590bfe781391815');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e64f60e1c2.85142611', '2026-09-24 23:11:11', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '6680d2867c22efa40afab42c3fa97b416d9c625765a7e2ff4590bfe781391815', 'fe9aeb28d63f687ce465eb90d7f3b39cce2e8faedce91a4b029434e1c6821ad3');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e7d0cc9794.96979218', '2026-09-24 23:17:36', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'fe9aeb28d63f687ce465eb90d7f3b39cce2e8faedce91a4b029434e1c6821ad3', '210fc93f32675be18f5f037f5ad5f7b9485e49da943e817fdd39e9d44fd2dd89');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e7d7efb4b0.45001772', '2026-09-24 23:17:43', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '210fc93f32675be18f5f037f5ad5f7b9485e49da943e817fdd39e9d44fd2dd89', '4fcac95374b1ed812989adb7ec3215df786b9dd6aeaea0c1b31163f29718707d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e7dd1f2675.57956320', '2026-09-24 23:17:49', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '4fcac95374b1ed812989adb7ec3215df786b9dd6aeaea0c1b31163f29718707d', 'de02fb6e6de9b96f141afd6d6af8b678cdb0e265eb856928b8ba40fb8a72247d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e7e1561812.61088751', '2026-09-24 23:17:53', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'de02fb6e6de9b96f141afd6d6af8b678cdb0e265eb856928b8ba40fb8a72247d', 'ba45bfd421c474263d8b3b804baf02eba8195d3520debc192169ac78fb5b0de4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e7f452c4b1.72177316', '2026-09-24 23:18:12', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ba45bfd421c474263d8b3b804baf02eba8195d3520debc192169ac78fb5b0de4', '7600b2d65f00bdefcefb588004450d46e65937f2e3db910df553089d2e253c06');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e804e8f558.27423613', '2026-09-24 23:18:28', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '7600b2d65f00bdefcefb588004450d46e65937f2e3db910df553089d2e253c06', '43504c0439f1b7af5e2d1246ab49dd2eb9b156704082ec9767c323025089ed12');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e80ab4b796.21399445', '2026-09-24 23:18:34', 'INFO', 'RepositorioPST', 'Registrar Proyecto', 'Proyecto PST registrado exitosamente: ''NUES DR. PABLO VILORIA - LA BEATRIZ SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DEL LABORATORIO 1 - INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY (UPTTMBI)”'' (ID: #166).', 'Miguel González (ID: 7)', '::1', '43504c0439f1b7af5e2d1246ab49dd2eb9b156704082ec9767c323025089ed12', '888b28d678d0123549668849e619e8ddd889d74f314e7e0e1edbfe845776441b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e86787b2a2.67843725', '2026-09-24 23:20:07', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '888b28d678d0123549668849e619e8ddd889d74f314e7e0e1edbfe845776441b', 'cabeeac25e8ea6846294d118b5ea45a8f3f68e5f74b22a2227009c0844de01c4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e86d75fc89.25861673', '2026-09-24 23:20:13', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'cabeeac25e8ea6846294d118b5ea45a8f3f68e5f74b22a2227009c0844de01c4', 'ae1ba8b895fcebd72e51a63c65c3f05e9a93b31223a9b7495d73bcc164f028ef');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e89ce22e06.18992168', '2026-09-24 23:21:00', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'ae1ba8b895fcebd72e51a63c65c3f05e9a93b31223a9b7495d73bcc164f028ef', '4b85a481f739b205c26d54ce85bc21f5202cd0dc877e52255c35716410d7d98f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e8a9a29ba8.41599488', '2026-09-24 23:21:13', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '4b85a481f739b205c26d54ce85bc21f5202cd0dc877e52255c35716410d7d98f', '687cb1fea86809975c40d01016826ca1118383e6530ed50980939dbd61898a19');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e8e6155b56.96836929', '2026-09-24 23:22:14', 'INFO', 'Autenticacion', 'Inicio de Sesión', 'Acceso exitoso al sistema de ''Sixsevenaldo González'' (C.I: 67, Rol: Estudiantes).', 'Sixsevenaldo González (ID: 14)', '::1', '687cb1fea86809975c40d01016826ca1118383e6530ed50980939dbd61898a19', 'a5ed957560d86d35d8664bd2c091eeded2eb7806dcb6e7fb3df88b301936c366');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e8fd660be8.85159503', '2026-09-24 23:22:37', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', 'a5ed957560d86d35d8664bd2c091eeded2eb7806dcb6e7fb3df88b301936c366', '92bb1c72235c3753be40bac57a7b1f9b067f66df19bf41dcd1e630e9b20aba84');
INSERT INTO public.system_audit_log VALUES ('log_6ab5e90ad5e278.82826238', '2026-09-24 23:22:50', 'INFO', 'RepositorioPST', 'Actualizar Configuración', 'Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).', 'Miguel González (ID: 7)', '::1', '92bb1c72235c3753be40bac57a7b1f9b067f66df19bf41dcd1e630e9b20aba84', 'a3878daec72d3469f6361dac4604b0c152eeb6e5289bd78459f3303c9dbd4911');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eb9cd79707.06780389', '2026-09-24 23:33:48', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', 'a3878daec72d3469f6361dac4604b0c152eeb6e5289bd78459f3303c9dbd4911', '3c4bcce80aedc75eeba9490e97c47ab525f9774642804444b2f784096c252643');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eb9f358660.19287344', '2026-09-24 23:33:51', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', '3c4bcce80aedc75eeba9490e97c47ab525f9774642804444b2f784096c252643', '6c9a4057bbb164eb1bbfa43a0888d2127bb70336814b2677ae2b5083979203f6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ec0cbd9a49.96883488', '2026-09-24 23:35:40', 'INFO', 'Articulos', 'Cambio Visibilidad', 'Se actualizó la visibilidad del artículo ID #150.', 'Miguel González (ID: 7)', '::1', '6c9a4057bbb164eb1bbfa43a0888d2127bb70336814b2677ae2b5083979203f6', '21211ff62e2d741e73ebad3839d9e622c6f5396ef4d913da43d2376502f33b5f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ed09b3d135.90781521', '2026-09-24 23:39:53', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 139.51ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', '21211ff62e2d741e73ebad3839d9e622c6f5396ef4d913da43d2376502f33b5f', 'b015c61be03f812be28542b986ae75dc549926bf5d8ca5b2a9316443fd769f0a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ed12712905.78997386', '2026-09-24 23:40:02', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 31.8ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', 'b015c61be03f812be28542b986ae75dc549926bf5d8ca5b2a9316443fd769f0a', '168fe841cfbcd890bee638f1c3f7783dad6b29414fef9149d589b1d5c02d240d');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ed14e06df3.86202428', '2026-09-24 23:40:04', 'INFO', 'SuperAdmin', 'Test Respuesta Core', 'Diagnóstico del Core ejecutado en 34.55ms. Memoria: 2 MB. Estado: SALUDABLE', 'Miguel González (ID: 7)', '::1', '168fe841cfbcd890bee638f1c3f7783dad6b29414fef9149d589b1d5c02d240d', '7914ecd2519957073ad4373543e829e13803b86e491c22ce3c4121ebf5ea8f91');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eec2d58447.96739777', '2026-09-24 23:47:14', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '7914ecd2519957073ad4373543e829e13803b86e491c22ce3c4121ebf5ea8f91', '89912171087ba41a069e95a406ba6e7343d7fb34c5d51ac1bd10cefef2a9f193');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eec3da2d88.06722296', '2026-09-24 23:47:15', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '89912171087ba41a069e95a406ba6e7343d7fb34c5d51ac1bd10cefef2a9f193', '2ade12781be259793f9348a0b23182397ae242974de7bad640eff9fec4d8e2e6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eec87643d7.90590613', '2026-09-24 23:47:20', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '2ade12781be259793f9348a0b23182397ae242974de7bad640eff9fec4d8e2e6', 'c28772c054d9d4ca95caf8e7d51913b4de135ed51225c1a352969d3af29c0faf');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eec97d4423.27655746', '2026-09-24 23:47:21', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'c28772c054d9d4ca95caf8e7d51913b4de135ed51225c1a352969d3af29c0faf', '1449850f7ea8cd27ecb62263697896dab2189711e7a09879890855665efc9679');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eeca614fa0.00509948', '2026-09-24 23:47:22', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '1449850f7ea8cd27ecb62263697896dab2189711e7a09879890855665efc9679', 'ee30be1b16f333480ea4ef5698388919b3b36c8c0ba479837dd64bd0c9808290');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eecaea9af4.33804631', '2026-09-24 23:47:22', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'ee30be1b16f333480ea4ef5698388919b3b36c8c0ba479837dd64bd0c9808290', '237496b59046eaa9b9244dc30112340ebaa1a8b2db77c153f95102706c12b0cd');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eef9099918.04659157', '2026-09-24 23:48:09', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '237496b59046eaa9b9244dc30112340ebaa1a8b2db77c153f95102706c12b0cd', 'd0a0da13f5da8a216c304e267206e5cbe188cfc8aa042a79a7788f4ac06c9b04');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eef9ad3b42.50428567', '2026-09-24 23:48:09', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'd0a0da13f5da8a216c304e267206e5cbe188cfc8aa042a79a7788f4ac06c9b04', '5e9960f2441addb3d238f3b2b410edc3bbd9d46cf1dbf6a1aac31e42fe6ec5a6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eefaedf973.70082828', '2026-09-24 23:48:10', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '5e9960f2441addb3d238f3b2b410edc3bbd9d46cf1dbf6a1aac31e42fe6ec5a6', '98136099cd46a63c7a32578adf66f1683256bca461beff165f4277623be25b65');
INSERT INTO public.system_audit_log VALUES ('log_6ab5eefbd47d69.91672839', '2026-09-24 23:48:11', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '98136099cd46a63c7a32578adf66f1683256bca461beff165f4277623be25b65', '875df28d97b0f2217cb5395778fb1a0ef875d843133dff584b3c8de8e8cbf4c1');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef00b10bd3.77019009', '2026-09-24 23:48:16', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '875df28d97b0f2217cb5395778fb1a0ef875d843133dff584b3c8de8e8cbf4c1', 'e525e4bd02dcbaee760c0e2329a0cd9e92dd6c0209d107f9e1aa5029f19abb05');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef022fbc98.27733071', '2026-09-24 23:48:18', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'e525e4bd02dcbaee760c0e2329a0cd9e92dd6c0209d107f9e1aa5029f19abb05', 'a3e3a7cde35d6d1031d8385bce9bacb6c4d1984b570dfc01cb8e150afbaef55e');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef0a5512b8.95907211', '2026-09-24 23:48:26', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'a3e3a7cde35d6d1031d8385bce9bacb6c4d1984b570dfc01cb8e150afbaef55e', 'fc9cb9aeb8d6b1a643da44b95375e8674b2ba23211a9d58667d1e71d4465adbc');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef0bb37f26.58112693', '2026-09-24 23:48:27', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', 'fc9cb9aeb8d6b1a643da44b95375e8674b2ba23211a9d58667d1e71d4465adbc', 'cf7b9196e0397934d2da57e3baf71e73f6d35b885a621dc12b6824f5f21211d4');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef0d2e1174.25002617', '2026-09-24 23:48:29', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', 'cf7b9196e0397934d2da57e3baf71e73f6d35b885a621dc12b6824f5f21211d4', '5d9c1966d9fdee7622fd6d1c0c2944606ecd9d4a3bf095072825b638ec2a7bae');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef0e0b60a8.96104966', '2026-09-24 23:48:30', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '5d9c1966d9fdee7622fd6d1c0c2944606ecd9d4a3bf095072825b638ec2a7bae', '5f19525fbe1619767ab93d77195547f157fb0e7d52f00b7640c266be6b57c521');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef287b7933.85618703', '2026-09-24 23:48:56', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: offline', 'Miguel González (ID: 7)', '::1', '5f19525fbe1619767ab93d77195547f157fb0e7d52f00b7640c266be6b57c521', '7e82110112c209e3ca9522d1c9ca140199960ace706b544baba96a8e89ccf2f6');
INSERT INTO public.system_audit_log VALUES ('log_6ab5ef28f36e19.55656048', '2026-09-24 23:48:56', 'WARNING', 'SuperAdmin', 'Alternar Estado Módulo', 'Módulo Articulos cambiado a estado: online', 'Miguel González (ID: 7)', '::1', '7e82110112c209e3ca9522d1c9ca140199960ace706b544baba96a8e89ccf2f6', '04d8be074333e3150ba05c5fabd114613422f7d5ef1cb0e0435ddcc88abdb3cb');
INSERT INTO public.system_audit_log VALUES ('log_6ab5efd8229027.43383978', '2026-09-24 23:51:52', 'INFO', 'Articulos', 'Purgado de Caché', 'Se eliminaron 0 archivos de caché (0 KB liberados).', 'Miguel González (ID: 7)', '::1', '04d8be074333e3150ba05c5fabd114613422f7d5ef1cb0e0435ddcc88abdb3cb', '1cd4a83e7e89039fa9cfbe0d9d5f37e2ed24670ba7b726d0af2fadfacd29199b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f0f8ac5fc7.75014861', '2026-09-24 23:56:40', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 777 (Vegetas). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '1cd4a83e7e89039fa9cfbe0d9d5f37e2ed24670ba7b726d0af2fadfacd29199b', '9eca886a6c46c5dc7eb2cef78763518e0b6cd1f8a4137fb595e2efa680d53e2f');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f12cb3d2b1.90743394', '2026-09-24 23:57:32', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 777 (Vegetasa). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '9eca886a6c46c5dc7eb2cef78763518e0b6cd1f8a4137fb595e2efa680d53e2f', '3101916e39689972ed2ad0ae406f03c4923f0e2cfa774469a84179acd98e0017');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f13a274ba3.39885498', '2026-09-24 23:57:46', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 777 (Vegetasaa). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '3101916e39689972ed2ad0ae406f03c4923f0e2cfa774469a84179acd98e0017', '57bb08b4ca2c308191df4f2cfcd24ba1cb3fd3a5b283bd0b988a0caa147d80c0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f13d3dd524.73172819', '2026-09-24 23:57:49', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 777 (Vegeta). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', '57bb08b4ca2c308191df4f2cfcd24ba1cb3fd3a5b283bd0b988a0caa147d80c0', 'c40f8f5b9a192394bcfc2fbab0579ab832b9c462c92ed0d1b1606ffe021cd99a');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f1477bed57.51797512', '2026-09-24 23:57:59', 'INFO', 'SuperAdmin', 'Editar Usuario', 'Datos actualizados para el Usuario C.I. 30469331 (Andrus). Sesión remota revocada.', 'Miguel González (ID: 7)', '::1', 'c40f8f5b9a192394bcfc2fbab0579ab832b9c462c92ed0d1b1606ffe021cd99a', 'fe664b1e795a8743d66ff98931d23b3cf25e8229975093c9df08bac42e60c0ae');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f338034bf4.60257817', '2026-09-25 00:06:16', 'INFO', 'SuperAdmin', 'Crear Backup', 'Respaldo backup_ciidi_2026-09-25_00-06-15.sql.gz generado exitosamente. Respaldos antiguos purgados: 0', 'Miguel González (ID: 7)', '::1', 'fe664b1e795a8743d66ff98931d23b3cf25e8229975093c9df08bac42e60c0ae', 'bd7c95f4f22ef1353b73e5dfe76accf52ff0e6d4f324d2eb0c3fe9de9593793b');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f33b932666.81842417', '2026-09-25 00:06:19', 'INFO', 'SuperAdmin', 'Optimizar BD', 'VACUUM ANALYZE ejecutado exitosamente.', 'Miguel González (ID: 7)', '::1', 'bd7c95f4f22ef1353b73e5dfe76accf52ff0e6d4f324d2eb0c3fe9de9593793b', '14f247f0c12bdb32bdf08b85033bdcf85a81e11c1b84b9646a29614104dce8b0');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f343edaa77.34024336', '2026-09-25 00:06:27', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '14f247f0c12bdb32bdf08b85033bdcf85a81e11c1b84b9646a29614104dce8b0', '126e1a000306258ed7e2662d9d21eaeb5b86867f9f8ffacf82acd6833bfe6948');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f347bc8625.33923949', '2026-09-25 00:06:31', 'WARNING', 'SuperAdmin', 'Cancelar Mantenimiento', 'Se canceló la programación de mantenimiento.', 'Miguel González (ID: 7)', '::1', '126e1a000306258ed7e2662d9d21eaeb5b86867f9f8ffacf82acd6833bfe6948', '2ff1e3a4feff04f2a610c678ba79e38fe704bd2b32b3df05e5038d8fab961cff');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f3534831e5.29511767', '2026-09-25 00:06:43', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: ACTIVADO', 'Miguel González (ID: 7)', '::1', '2ff1e3a4feff04f2a610c678ba79e38fe704bd2b32b3df05e5038d8fab961cff', '11449901949cc583ad202c864084bc50b58ae286d680380611a2593f0e04a892');
INSERT INTO public.system_audit_log VALUES ('log_6ab5f3579cd023.19225768', '2026-09-25 00:06:47', 'WARNING', 'SuperAdmin', 'Alternar Mantenimiento', 'Modo Mantenimiento cambiado a: DESACTIVADO', 'Miguel González (ID: 7)', '::1', '11449901949cc583ad202c864084bc50b58ae286d680380611a2593f0e04a892', 'bb992b5e54eb785b84d40f71dfee1544201ba7abc83304f7922170c76cb66f8f');


--
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.telemetria_cache VALUES (1, '{"timestamp": 1790308725, "storage_mb": 32.91, "files_count": 64}');


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
INSERT INTO public.tutores VALUES (41, 'Marzzia Gil', 'e');
INSERT INTO public.tutores VALUES (42, 'Jhonder Duran', NULL);
INSERT INTO public.tutores VALUES (43, 'Axel Olivares', NULL);
INSERT INTO public.tutores VALUES (44, 'Marisela Olmos', NULL);
INSERT INTO public.tutores VALUES (45, 'Ramón Santander', NULL);


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
INSERT INTO public.usuarios VALUES (18, '[Archivado] Vegeta', 'Ereselmejorplaneta@gmail.com_deleted_1790279080', '777_x0279080', '$2y$10$6SYCpDWXnVeimvyQunnNLuXMhkL1/Iilre1p6kzFmT5StXHuNNsWa', 3, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (19, '[Archivado] e', '7@gmail.com_deleted_1790279250', '777_x0279250', '$2y$10$ixpnJitBmMwTzprrpjcqOOVO40BUdK96.ocdp.t7rUonUu.b.FKga', 1, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (20, '[Archivado] Vegetas', 'vegeta@gmail.com_deleted_1790281482', '777_x0281482', '$2y$10$w2zZeID5cQrR1IScn2UgK.tXFpoJcBEqKCDTKgjhoAyymy23QyI9m', 3, false, NULL, NULL, NULL, true, NULL);
INSERT INTO public.usuarios VALUES (21, '[Archivado] Vegeta', 'vegeta@gmail.com_deleted_1790300648', '777_x0300648', '$2y$12$mWi.6f8OA5ga6WIdadYWF.AlGpkU.KuIVeKfbBIYfNXulrYHDTOr.', 3, false, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (22, 'Vegeta', 'vegeta@gmail.com', '777', '$2y$12$/cm17kDcfIszqQl6g9JRT.O73Cr4kRTHLrgIlZsa9KK8HD.m0ftpi', 4, true, NULL, NULL, NULL, false, NULL);
INSERT INTO public.usuarios VALUES (17, 'Andrus', 'andrusramirez2020@gmail.com', '30469331', '$2y$10$SFowO4NOxSgKqx35qYr7iOiJU2PJ6hJ.uTO2zdxSSXMQjX64sRwiu', 2, true, NULL, NULL, NULL, true, NULL);


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

SELECT pg_catalog.setval('public.auditoria_id_seq', 361, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 118, true);


--
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 19, true);


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

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 12, true);


--
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 16, true);


--
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 166, true);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 6, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 5, true);


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

SELECT pg_catalog.setval('public.tutores_id_seq', 45, true);


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

\unrestrict EIIc95Nbnnmj8sYMQUh9CKtaZeJVO4fcqVszJfADNLpOb9UQO2Rt0CJADZQ1Bf7

