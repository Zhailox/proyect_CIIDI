--
-- PostgreSQL database dump
--

\restrict ZQwHmG6IRv6QHD0aotmucgIPcJYkmFKQdgIPLaw6V3MgauinYY9uTPsRkapK8Bl

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-11-02 13:34:47

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

--
-- TOC entry 3 (class 3079 OID 68776)
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- TOC entry 5859 (class 0 OID 0)
-- Dependencies: 3
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- TOC entry 2 (class 3079 OID 68768)
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- TOC entry 5860 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


--
-- TOC entry 988 (class 1247 OID 51366)
-- Name: accion_acceso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_acceso_enum AS ENUM (
    'visualizacion',
    'descarga'
);


ALTER TYPE public.accion_acceso_enum OWNER TO postgres;

--
-- TOC entry 991 (class 1247 OID 51372)
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);


ALTER TYPE public.accion_auditoria_enum OWNER TO postgres;

--
-- TOC entry 994 (class 1247 OID 51380)
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);


ALTER TYPE public.estado_curso_enum OWNER TO postgres;

--
-- TOC entry 997 (class 1247 OID 51388)
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);


ALTER TYPE public.estado_propuesta_enum OWNER TO postgres;

--
-- TOC entry 1000 (class 1247 OID 51396)
-- Name: nivel_academico_enum; Type: TYPE; Schema: public; Owner: miki
--

CREATE TYPE public.nivel_academico_enum AS ENUM (
    'Pregrado',
    'Especializacion',
    'Maestria',
    'Doctorado'
);


ALTER TYPE public.nivel_academico_enum OWNER TO miki;

--
-- TOC entry 1003 (class 1247 OID 51406)
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);


ALTER TYPE public.tipo_interaccion_enum OWNER TO postgres;

--
-- TOC entry 1006 (class 1247 OID 51412)
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);


ALTER TYPE public.tipo_interaccion_usuario_enum OWNER TO postgres;

--
-- TOC entry 1009 (class 1247 OID 51418)
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);


ALTER TYPE public.tipo_pregunta_enum OWNER TO postgres;

--
-- TOC entry 364 (class 1255 OID 51425)
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
-- TOC entry 365 (class 1255 OID 51426)
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
-- TOC entry 366 (class 1255 OID 51427)
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

--
-- TOC entry 317 (class 1255 OID 68775)
-- Name: normalizar_busqueda_pst(text); Type: FUNCTION; Schema: public; Owner: miki
--

CREATE FUNCTION public.normalizar_busqueda_pst(input_text text) RETURNS text
    LANGUAGE sql IMMUTABLE
    AS $$
                    SELECT LOWER(public.unaccent(COALESCE(input_text, '')));
                $$;


ALTER FUNCTION public.normalizar_busqueda_pst(input_text text) OWNER TO miki;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 221 (class 1259 OID 51428)
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
-- TOC entry 222 (class 1259 OID 51436)
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
-- TOC entry 5861 (class 0 OID 0)
-- Dependencies: 222
-- Name: accesos_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.accesos_recursos_id_seq OWNED BY public.accesos_recursos.id;


--
-- TOC entry 315 (class 1259 OID 60644)
-- Name: analitica_snapshots; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.analitica_snapshots OWNER TO miki;

--
-- TOC entry 314 (class 1259 OID 60643)
-- Name: analitica_snapshots_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.analitica_snapshots_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.analitica_snapshots_id_seq OWNER TO miki;

--
-- TOC entry 5862 (class 0 OID 0)
-- Dependencies: 314
-- Name: analitica_snapshots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.analitica_snapshots_id_seq OWNED BY public.analitica_snapshots.id;


--
-- TOC entry 223 (class 1259 OID 51437)
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
-- TOC entry 224 (class 1259 OID 51447)
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
-- TOC entry 5863 (class 0 OID 0)
-- Dependencies: 224
-- Name: auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.auditoria_id_seq OWNED BY public.auditoria.id;


--
-- TOC entry 225 (class 1259 OID 51448)
-- Name: autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.autores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.autores OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 51454)
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
-- TOC entry 5864 (class 0 OID 0)
-- Dependencies: 226
-- Name: autores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.autores_id_seq OWNED BY public.autores.id;


--
-- TOC entry 227 (class 1259 OID 51455)
-- Name: carreras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carreras (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);


ALTER TABLE public.carreras OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 51462)
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
-- TOC entry 5865 (class 0 OID 0)
-- Dependencies: 228
-- Name: carreras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carreras_id_seq OWNED BY public.carreras.id;


--
-- TOC entry 229 (class 1259 OID 51463)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 51468)
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
-- TOC entry 5866 (class 0 OID 0)
-- Dependencies: 230
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- TOC entry 231 (class 1259 OID 51469)
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
    estado_inscripcion character varying(50) DEFAULT 'Abierta'::character varying,
    url_formulario_google text
);


ALTER TABLE public.cursos OWNER TO postgres;

--
-- TOC entry 310 (class 1259 OID 60541)
-- Name: cursos_dimensiones; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.cursos_dimensiones (
    id_curso integer NOT NULL,
    id_dimension integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cursos_dimensiones OWNER TO miki;

--
-- TOC entry 232 (class 1259 OID 51486)
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
-- TOC entry 5867 (class 0 OID 0)
-- Dependencies: 232
-- Name: cursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cursos_id_seq OWNED BY public.cursos.id;


--
-- TOC entry 309 (class 1259 OID 60521)
-- Name: cursos_lineas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.cursos_lineas (
    id_curso integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cursos_lineas OWNER TO miki;

--
-- TOC entry 306 (class 1259 OID 60469)
-- Name: cursos_preinscripciones; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.cursos_preinscripciones OWNER TO miki;

--
-- TOC entry 305 (class 1259 OID 60468)
-- Name: cursos_preinscripciones_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.cursos_preinscripciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cursos_preinscripciones_id_seq OWNER TO miki;

--
-- TOC entry 5868 (class 0 OID 0)
-- Dependencies: 305
-- Name: cursos_preinscripciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.cursos_preinscripciones_id_seq OWNED BY public.cursos_preinscripciones.id;


--
-- TOC entry 233 (class 1259 OID 51487)
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
-- TOC entry 234 (class 1259 OID 51496)
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
-- TOC entry 288 (class 1259 OID 52116)
-- Name: detalles_proyectos; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.detalles_proyectos OWNER TO miki;

--
-- TOC entry 235 (class 1259 OID 51505)
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
-- TOC entry 236 (class 1259 OID 51514)
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
-- TOC entry 5869 (class 0 OID 0)
-- Dependencies: 236
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNED BY public.dimensiones_operativas.id;


--
-- TOC entry 237 (class 1259 OID 51515)
-- Name: editoriales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.editoriales (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.editoriales OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 51520)
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
-- TOC entry 5870 (class 0 OID 0)
-- Dependencies: 238
-- Name: editoriales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.editoriales_id_seq OWNED BY public.editoriales.id;


--
-- TOC entry 239 (class 1259 OID 51521)
-- Name: etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etiquetas (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    color_hex character varying(7) DEFAULT '#0ea5e9'::character varying
);


ALTER TABLE public.etiquetas OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 51527)
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
-- TOC entry 5871 (class 0 OID 0)
-- Dependencies: 240
-- Name: etiquetas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.etiquetas_id_seq OWNED BY public.etiquetas.id;


--
-- TOC entry 241 (class 1259 OID 51528)
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
-- TOC entry 242 (class 1259 OID 51538)
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
-- TOC entry 5872 (class 0 OID 0)
-- Dependencies: 242
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.historico_versiones_pst_id_seq OWNED BY public.historico_versiones_pst.id;


--
-- TOC entry 293 (class 1259 OID 52219)
-- Name: investigaciones_dimensiones; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.investigaciones_dimensiones (
    id_investigacion integer NOT NULL,
    id_dimension integer NOT NULL
);


ALTER TABLE public.investigaciones_dimensiones OWNER TO miki;

--
-- TOC entry 292 (class 1259 OID 52202)
-- Name: investigaciones_lineas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.investigaciones_lineas (
    id_investigacion integer NOT NULL,
    id_linea integer NOT NULL
);


ALTER TABLE public.investigaciones_lineas OWNER TO miki;

--
-- TOC entry 243 (class 1259 OID 51539)
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
-- TOC entry 244 (class 1259 OID 51554)
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
-- TOC entry 5873 (class 0 OID 0)
-- Dependencies: 244
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


--
-- TOC entry 312 (class 1259 OID 60577)
-- Name: investigadores; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.investigadores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    orcid character varying(50) NOT NULL,
    biografia text,
    pagina_web character varying(255),
    foto_perfil text,
    email character varying(150),
    grado_academico character varying(255)
);


ALTER TABLE public.investigadores OWNER TO miki;

--
-- TOC entry 311 (class 1259 OID 60576)
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
-- TOC entry 5874 (class 0 OID 0)
-- Dependencies: 311
-- Name: investigadores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.investigadores_id_seq OWNED BY public.investigadores.id;


--
-- TOC entry 245 (class 1259 OID 51555)
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
-- TOC entry 246 (class 1259 OID 51564)
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
-- TOC entry 5875 (class 0 OID 0)
-- Dependencies: 246
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lineas_investigacion_id_seq OWNED BY public.lineas_investigacion.id;


--
-- TOC entry 247 (class 1259 OID 51565)
-- Name: matriz_rbac; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matriz_rbac (
    nivel_privilegio integer NOT NULL,
    modulo character varying(100) NOT NULL,
    permisos jsonb
);


ALTER TABLE public.matriz_rbac OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 51572)
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
-- TOC entry 249 (class 1259 OID 51585)
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
-- TOC entry 5876 (class 0 OID 0)
-- Dependencies: 249
-- Name: niveles_academicos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.niveles_academicos_id_seq OWNED BY public.niveles_academicos.id;


--
-- TOC entry 250 (class 1259 OID 51586)
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
-- TOC entry 251 (class 1259 OID 51595)
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
-- TOC entry 5877 (class 0 OID 0)
-- Dependencies: 251
-- Name: notificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_id_seq OWNED BY public.notificaciones.id;


--
-- TOC entry 252 (class 1259 OID 51596)
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
-- TOC entry 253 (class 1259 OID 51605)
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
-- TOC entry 5878 (class 0 OID 0)
-- Dependencies: 253
-- Name: password_resets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.password_resets_id_seq OWNED BY public.password_resets.id;


--
-- TOC entry 254 (class 1259 OID 51606)
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


ALTER TABLE public.postulaciones_estudiantes OWNER TO postgres;

--
-- TOC entry 5879 (class 0 OID 0)
-- Dependencies: 254
-- Name: COLUMN postulaciones_estudiantes.aprobacion_profesor; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.postulaciones_estudiantes.aprobacion_profesor IS 'Validación del profesor PST elegido: Pendiente, Aprobado o Devuelto (con motivo). La asignación final la realiza el Comité (columna estado).';


--
-- TOC entry 5880 (class 0 OID 0)
-- Dependencies: 254
-- Name: COLUMN postulaciones_estudiantes.motivo_rechazo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.postulaciones_estudiantes.motivo_rechazo IS 'Motivo del rechazo o cancelación de la postulación (p. ej. propuesta empresarial revertida o archivada).';


--
-- TOC entry 255 (class 1259 OID 51617)
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
-- TOC entry 5881 (class 0 OID 0)
-- Dependencies: 255
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNED BY public.postulaciones_estudiantes.id;


--
-- TOC entry 256 (class 1259 OID 51618)
-- Name: preferencias_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.preferencias_usuario (
    id_usuario integer NOT NULL,
    tema character varying(50) DEFAULT 'light'::character varying,
    notificaciones_sistema boolean DEFAULT true
);


ALTER TABLE public.preferencias_usuario OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 51624)
-- Name: privilegios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.privilegios OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 51630)
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
-- TOC entry 5882 (class 0 OID 0)
-- Dependencies: 258
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNED BY public.privilegios.privilegio_id;


--
-- TOC entry 316 (class 1259 OID 60677)
-- Name: profesor_dimension; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.profesor_dimension (
    id_usuario integer NOT NULL,
    id_dimension integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.profesor_dimension OWNER TO miki;

--
-- TOC entry 291 (class 1259 OID 52183)
-- Name: profesor_linea; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.profesor_linea (
    id_usuario integer NOT NULL,
    id_linea integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.profesor_linea OWNER TO miki;

--
-- TOC entry 5883 (class 0 OID 0)
-- Dependencies: 291
-- Name: TABLE profesor_linea; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.profesor_linea IS 'Relación N:M de afiliación explícita de profesores (usuarios) a líneas de investigación.';


--
-- TOC entry 290 (class 1259 OID 52143)
-- Name: profesores_pst; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.profesores_pst OWNER TO miki;

--
-- TOC entry 5884 (class 0 OID 0)
-- Dependencies: 290
-- Name: TABLE profesores_pst; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.profesores_pst IS 'Trayecto(s) y trimestre(s) en los que cada profesor PST dicta/tutoriza el Proyecto Sociotecnológico.';


--
-- TOC entry 289 (class 1259 OID 52142)
-- Name: profesores_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.profesores_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.profesores_pst_id_seq OWNER TO miki;

--
-- TOC entry 5885 (class 0 OID 0)
-- Dependencies: 289
-- Name: profesores_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.profesores_pst_id_seq OWNED BY public.profesores_pst.id;


--
-- TOC entry 259 (class 1259 OID 51631)
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
    motivo_rechazo text,
    campos_extra jsonb DEFAULT '[]'::jsonb NOT NULL,
    eliminado_en timestamp without time zone,
    motivo_archivo text,
    archivado_por integer,
    revertido_en timestamp without time zone,
    motivo_reversion text
);


ALTER TABLE public.propuestas_empresa OWNER TO postgres;

--
-- TOC entry 5886 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.campos_extra; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.campos_extra IS 'Respuestas de campos personalizados del formulario público: [{"clave","etiqueta","tipo","valor"}].';


--
-- TOC entry 5887 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.eliminado_en; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.eliminado_en IS 'NULL = activa. Fecha de archivo lógico (soft-delete) de la propuesta.';


--
-- TOC entry 5888 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.motivo_archivo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.motivo_archivo IS 'Motivo indicado por el Comité al archivar la propuesta.';


--
-- TOC entry 5889 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.archivado_por; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.archivado_por IS 'Usuario que archivó la propuesta.';


--
-- TOC entry 5890 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.revertido_en; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.revertido_en IS 'Última fecha en que una propuesta aceptada fue revertida a pendiente.';


--
-- TOC entry 5891 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN propuestas_empresa.motivo_reversion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.propuestas_empresa.motivo_reversion IS 'Motivo de la última reversión a pendiente.';


--
-- TOC entry 260 (class 1259 OID 51645)
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
-- TOC entry 5892 (class 0 OID 0)
-- Dependencies: 260
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- TOC entry 261 (class 1259 OID 51646)
-- Name: proyecto_carreras_vinculadas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.proyecto_carreras_vinculadas (
    id_recurso integer NOT NULL,
    id_carrera integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.proyecto_carreras_vinculadas OWNER TO miki;

--
-- TOC entry 5893 (class 0 OID 0)
-- Dependencies: 261
-- Name: TABLE proyecto_carreras_vinculadas; Type: COMMENT; Schema: public; Owner: miki
--

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'Relación N:M para vincular proyectos PST con una o varias carreras secundarias adicionales a su carrera base.';


--
-- TOC entry 262 (class 1259 OID 51652)
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);


ALTER TABLE public.proyecto_tutores OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 51657)
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);


ALTER TABLE public.recurso_autores OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 51662)
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);


ALTER TABLE public.recurso_categorias OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 51667)
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);


ALTER TABLE public.recurso_clasificaciones OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 51672)
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);


ALTER TABLE public.recurso_etiquetas OWNER TO postgres;

--
-- TOC entry 313 (class 1259 OID 60590)
-- Name: recurso_investigadores; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.recurso_investigadores (
    id_recurso integer NOT NULL,
    id_investigador integer NOT NULL
);


ALTER TABLE public.recurso_investigadores OWNER TO miki;

--
-- TOC entry 267 (class 1259 OID 51677)
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
-- TOC entry 268 (class 1259 OID 51685)
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
-- TOC entry 5894 (class 0 OID 0)
-- Dependencies: 268
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.recursos_id_seq OWNED BY public.recursos.id;


--
-- TOC entry 269 (class 1259 OID 51686)
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
-- TOC entry 270 (class 1259 OID 51693)
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
-- TOC entry 5895 (class 0 OID 0)
-- Dependencies: 270
-- Name: registro_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registro_actividad_id_seq OWNED BY public.registro_actividad.id;


--
-- TOC entry 271 (class 1259 OID 51694)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    privilegio_id integer DEFAULT 1 CONSTRAINT roles_privilegios_id_not_null NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 51701)
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
-- TOC entry 5896 (class 0 OID 0)
-- Dependencies: 272
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 302 (class 1259 OID 60424)
-- Name: seg_lineas_alertas; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.seg_lineas_alertas OWNER TO miki;

--
-- TOC entry 301 (class 1259 OID 60423)
-- Name: seg_lineas_alertas_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.seg_lineas_alertas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seg_lineas_alertas_id_seq OWNER TO miki;

--
-- TOC entry 5897 (class 0 OID 0)
-- Dependencies: 301
-- Name: seg_lineas_alertas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.seg_lineas_alertas_id_seq OWNED BY public.seg_lineas_alertas.id;


--
-- TOC entry 300 (class 1259 OID 60403)
-- Name: seg_lineas_bloqueos; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.seg_lineas_bloqueos OWNER TO miki;

--
-- TOC entry 299 (class 1259 OID 60402)
-- Name: seg_lineas_bloqueos_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.seg_lineas_bloqueos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seg_lineas_bloqueos_id_seq OWNER TO miki;

--
-- TOC entry 5898 (class 0 OID 0)
-- Dependencies: 299
-- Name: seg_lineas_bloqueos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.seg_lineas_bloqueos_id_seq OWNED BY public.seg_lineas_bloqueos.id;


--
-- TOC entry 298 (class 1259 OID 60382)
-- Name: seg_lineas_excepciones; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.seg_lineas_excepciones OWNER TO miki;

--
-- TOC entry 297 (class 1259 OID 60381)
-- Name: seg_lineas_excepciones_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.seg_lineas_excepciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seg_lineas_excepciones_id_seq OWNER TO miki;

--
-- TOC entry 5899 (class 0 OID 0)
-- Dependencies: 297
-- Name: seg_lineas_excepciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.seg_lineas_excepciones_id_seq OWNED BY public.seg_lineas_excepciones.id;


--
-- TOC entry 295 (class 1259 OID 60331)
-- Name: seg_lineas_logs; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.seg_lineas_logs OWNER TO miki;

--
-- TOC entry 294 (class 1259 OID 60330)
-- Name: seg_lineas_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.seg_lineas_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seg_lineas_logs_id_seq OWNER TO miki;

--
-- TOC entry 5900 (class 0 OID 0)
-- Dependencies: 294
-- Name: seg_lineas_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.seg_lineas_logs_id_seq OWNED BY public.seg_lineas_logs.id;


--
-- TOC entry 296 (class 1259 OID 60368)
-- Name: seg_lineas_permisos; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.seg_lineas_permisos (
    nivel integer NOT NULL,
    submodulo character varying(80) NOT NULL,
    accion character varying(30) NOT NULL,
    permitido boolean NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.seg_lineas_permisos OWNER TO miki;

--
-- TOC entry 303 (class 1259 OID 60444)
-- Name: seg_lineas_politicas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.seg_lineas_politicas (
    clave character varying(60) NOT NULL,
    valor jsonb NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.seg_lineas_politicas OWNER TO miki;

--
-- TOC entry 304 (class 1259 OID 60457)
-- Name: seg_lineas_rutas; Type: TABLE; Schema: public; Owner: miki
--

CREATE TABLE public.seg_lineas_rutas (
    ruta character varying(160) NOT NULL,
    accion character varying(30) NOT NULL,
    actualizado_por character varying(160) DEFAULT ''::character varying NOT NULL,
    actualizado_en timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.seg_lineas_rutas OWNER TO miki;

--
-- TOC entry 308 (class 1259 OID 60491)
-- Name: solicitudes_docentes_pst; Type: TABLE; Schema: public; Owner: miki
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


ALTER TABLE public.solicitudes_docentes_pst OWNER TO miki;

--
-- TOC entry 307 (class 1259 OID 60490)
-- Name: solicitudes_docentes_pst_id_seq; Type: SEQUENCE; Schema: public; Owner: miki
--

CREATE SEQUENCE public.solicitudes_docentes_pst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.solicitudes_docentes_pst_id_seq OWNER TO miki;

--
-- TOC entry 5901 (class 0 OID 0)
-- Dependencies: 307
-- Name: solicitudes_docentes_pst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.solicitudes_docentes_pst_id_seq OWNED BY public.solicitudes_docentes_pst.id;


--
-- TOC entry 273 (class 1259 OID 51702)
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
-- TOC entry 274 (class 1259 OID 51709)
-- Name: telemetria_cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.telemetria_cache (
    id integer DEFAULT 1 NOT NULL,
    datos jsonb
);


ALTER TABLE public.telemetria_cache OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 51716)
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_recurso OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 51723)
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
-- TOC entry 5902 (class 0 OID 0)
-- Dependencies: 276
-- Name: tipo_recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_recurso_id_seq OWNED BY public.tipo_recurso.id;


--
-- TOC entry 277 (class 1259 OID 51724)
-- Name: tipo_tutor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_tutor (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_tutor OWNER TO postgres;

--
-- TOC entry 278 (class 1259 OID 51731)
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
-- TOC entry 5903 (class 0 OID 0)
-- Dependencies: 278
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- TOC entry 279 (class 1259 OID 51732)
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
-- TOC entry 280 (class 1259 OID 51743)
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
-- TOC entry 5904 (class 0 OID 0)
-- Dependencies: 280
-- Name: trayectos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: miki
--

ALTER SEQUENCE public.trayectos_id_seq OWNED BY public.trayectos.id;


--
-- TOC entry 281 (class 1259 OID 51744)
-- Name: tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.tutores OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 51749)
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
-- TOC entry 5905 (class 0 OID 0)
-- Dependencies: 282
-- Name: tutores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tutores_id_seq OWNED BY public.tutores.id;


--
-- TOC entry 283 (class 1259 OID 51750)
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
    activation_token character varying(255) DEFAULT NULL::character varying,
    estado_registro character varying(20) DEFAULT NULL::character varying,
    es_profesor_pst boolean DEFAULT false NOT NULL,
    es_investigador boolean DEFAULT false NOT NULL,
    foto_perfil text,
    orcid character varying(100),
    sitio_web character varying(255),
    grado_academico character varying(255),
    CONSTRAINT chk_usuarios_estado_registro CHECK (((estado_registro IS NULL) OR ((estado_registro)::text = ANY ((ARRAY['pendiente'::character varying, 'aprobado'::character varying, 'rechazado'::character varying])::text[]))))
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 5906 (class 0 OID 0)
-- Dependencies: 283
-- Name: COLUMN usuarios.estado_registro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.usuarios.estado_registro IS 'NULL = flujo normal. pendiente/aprobado/rechazado = auto-registro docente en línea sujeto a aprobación del Comité/Super Administrador.';


--
-- TOC entry 5907 (class 0 OID 0)
-- Dependencies: 283
-- Name: COLUMN usuarios.es_profesor_pst; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.usuarios.es_profesor_pst IS 'Indica si el docente imparte/tutoriza Proyecto Sociotecnológico (PST).';


--
-- TOC entry 284 (class 1259 OID 51762)
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
-- TOC entry 5908 (class 0 OID 0)
-- Dependencies: 284
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- TOC entry 285 (class 1259 OID 51763)
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
-- TOC entry 286 (class 1259 OID 51770)
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
-- TOC entry 5909 (class 0 OID 0)
-- Dependencies: 286
-- Name: visitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.visitantes_id_seq OWNED BY public.visitantes.id;


--
-- TOC entry 287 (class 1259 OID 51771)
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
-- TOC entry 5210 (class 2604 OID 51781)
-- Name: accesos_recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos ALTER COLUMN id SET DEFAULT nextval('public.accesos_recursos_id_seq'::regclass);


--
-- TOC entry 5346 (class 2604 OID 60647)
-- Name: analitica_snapshots id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.analitica_snapshots ALTER COLUMN id SET DEFAULT nextval('public.analitica_snapshots_id_seq'::regclass);


--
-- TOC entry 5213 (class 2604 OID 51782)
-- Name: auditoria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria ALTER COLUMN id SET DEFAULT nextval('public.auditoria_id_seq'::regclass);


--
-- TOC entry 5215 (class 2604 OID 51783)
-- Name: autores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores ALTER COLUMN id SET DEFAULT nextval('public.autores_id_seq'::regclass);


--
-- TOC entry 5216 (class 2604 OID 51784)
-- Name: carreras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras ALTER COLUMN id SET DEFAULT nextval('public.carreras_id_seq'::regclass);


--
-- TOC entry 5217 (class 2604 OID 51785)
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- TOC entry 5218 (class 2604 OID 51786)
-- Name: cursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos ALTER COLUMN id SET DEFAULT nextval('public.cursos_id_seq'::regclass);


--
-- TOC entry 5336 (class 2604 OID 60472)
-- Name: cursos_preinscripciones id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_preinscripciones ALTER COLUMN id SET DEFAULT nextval('public.cursos_preinscripciones_id_seq'::regclass);


--
-- TOC entry 5230 (class 2604 OID 51787)
-- Name: dimensiones_operativas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas ALTER COLUMN id SET DEFAULT nextval('public.dimensiones_operativas_id_seq'::regclass);


--
-- TOC entry 5232 (class 2604 OID 51788)
-- Name: editoriales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales ALTER COLUMN id SET DEFAULT nextval('public.editoriales_id_seq'::regclass);


--
-- TOC entry 5233 (class 2604 OID 51789)
-- Name: etiquetas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas ALTER COLUMN id SET DEFAULT nextval('public.etiquetas_id_seq'::regclass);


--
-- TOC entry 5235 (class 2604 OID 51790)
-- Name: historico_versiones_pst id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst ALTER COLUMN id SET DEFAULT nextval('public.historico_versiones_pst_id_seq'::regclass);


--
-- TOC entry 5238 (class 2604 OID 51791)
-- Name: investigaciones_ofertadas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas ALTER COLUMN id SET DEFAULT nextval('public.investigaciones_ofertadas_id_seq'::regclass);


--
-- TOC entry 5345 (class 2604 OID 60580)
-- Name: investigadores id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores ALTER COLUMN id SET DEFAULT nextval('public.investigadores_id_seq'::regclass);


--
-- TOC entry 5242 (class 2604 OID 51792)
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


--
-- TOC entry 5244 (class 2604 OID 51793)
-- Name: niveles_academicos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos ALTER COLUMN id SET DEFAULT nextval('public.niveles_academicos_id_seq'::regclass);


--
-- TOC entry 5250 (class 2604 OID 51794)
-- Name: notificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_id_seq'::regclass);


--
-- TOC entry 5254 (class 2604 OID 51795)
-- Name: password_resets id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets ALTER COLUMN id SET DEFAULT nextval('public.password_resets_id_seq'::regclass);


--
-- TOC entry 5257 (class 2604 OID 51796)
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- TOC entry 5263 (class 2604 OID 51797)
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


--
-- TOC entry 5302 (class 2604 OID 52146)
-- Name: profesores_pst id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesores_pst ALTER COLUMN id SET DEFAULT nextval('public.profesores_pst_id_seq'::regclass);


--
-- TOC entry 5265 (class 2604 OID 51798)
-- Name: propuestas_empresa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa ALTER COLUMN id SET DEFAULT nextval('public.propuestas_empresa_id_seq'::regclass);


--
-- TOC entry 5270 (class 2604 OID 51799)
-- Name: recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos ALTER COLUMN id SET DEFAULT nextval('public.recursos_id_seq'::regclass);


--
-- TOC entry 5271 (class 2604 OID 51800)
-- Name: registro_actividad id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad ALTER COLUMN id SET DEFAULT nextval('public.registro_actividad_id_seq'::regclass);


--
-- TOC entry 5275 (class 2604 OID 51801)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 5328 (class 2604 OID 60427)
-- Name: seg_lineas_alertas id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_alertas ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_alertas_id_seq'::regclass);


--
-- TOC entry 5323 (class 2604 OID 60406)
-- Name: seg_lineas_bloqueos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_bloqueos ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_bloqueos_id_seq'::regclass);


--
-- TOC entry 5320 (class 2604 OID 60385)
-- Name: seg_lineas_excepciones id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_excepciones ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_excepciones_id_seq'::regclass);


--
-- TOC entry 5306 (class 2604 OID 60334)
-- Name: seg_lineas_logs id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_logs ALTER COLUMN id SET DEFAULT nextval('public.seg_lineas_logs_id_seq'::regclass);


--
-- TOC entry 5338 (class 2604 OID 60494)
-- Name: solicitudes_docentes_pst id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.solicitudes_docentes_pst ALTER COLUMN id SET DEFAULT nextval('public.solicitudes_docentes_pst_id_seq'::regclass);


--
-- TOC entry 5279 (class 2604 OID 51802)
-- Name: tipo_recurso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso ALTER COLUMN id SET DEFAULT nextval('public.tipo_recurso_id_seq'::regclass);


--
-- TOC entry 5280 (class 2604 OID 51803)
-- Name: tipo_tutor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor ALTER COLUMN id SET DEFAULT nextval('public.tipo_tutor_id_seq'::regclass);


--
-- TOC entry 5281 (class 2604 OID 51804)
-- Name: trayectos id; Type: DEFAULT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos ALTER COLUMN id SET DEFAULT nextval('public.trayectos_id_seq'::regclass);


--
-- TOC entry 5284 (class 2604 OID 51805)
-- Name: tutores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores ALTER COLUMN id SET DEFAULT nextval('public.tutores_id_seq'::regclass);


--
-- TOC entry 5285 (class 2604 OID 51806)
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- TOC entry 5294 (class 2604 OID 51807)
-- Name: visitantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes ALTER COLUMN id SET DEFAULT nextval('public.visitantes_id_seq'::regclass);


--
-- TOC entry 5758 (class 0 OID 51428)
-- Dependencies: 221
-- Data for Name: accesos_recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.accesos_recursos (id, id_registro_actividad, id_recurso, accion, fecha_acceso) FROM stdin;
\.


--
-- TOC entry 5852 (class 0 OID 60644)
-- Dependencies: 315
-- Data for Name: analitica_snapshots; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.analitica_snapshots (id, fecha_snapshot, usuario_nombre, id_carrera, carrera_nombre, modelo_usado, modelo_label, fuente_datos, ventana_historial, horizonte_trimestres, trimestre_base, trimestre_meta, rmse_global, mae_global, confianza_pct, total_historico, total_proyectado, estacionalidad_activa, resumen_json) FROM stdin;
3	2026-10-22 16:37:21.504575	Coordinador CIIDI	\N	Todas las Carreras / PNF	linear	Regresión Lineal Múltiple	pst	2y	4	T3 2026	T1 2028	2.8535	2.2556	74.50	81	67	f	[{"r2": 1, "mae": 0, "name": "DESARROLLO DE APLICACIONES", "rmse": 0, "carrera": "PNF en Informática", "history": [0, 0, 0, 0, 0, 0], "capacity": 25, "ci_lower": [0, 0, 0, 0], "ci_upper": [0, 0, 0, 0], "ultimo_real": 0, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [0, 0, 0, 0], "history_labels": ["T1 2025", "T2 2025", "T3 2025", "T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 0, "total_historico": 0, "saturation_index": 0, "promedio_historico": 0, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 0, "primer_trimestre_label": "T1 2027"}, {"r2": 0.358, "mae": 0.4444, "name": "EDUMATICA", "rmse": 0.5631, "carrera": "PNF en Informática", "history": [0, 0, 1, 0, 2, 1], "capacity": 25, "ci_lower": [1.11, 1.39, 1.68, 1.96], "ci_upper": [2.23, 2.51, 2.8, 3.08], "ultimo_real": 1, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [1.67, 1.95, 2.24, 2.52], "history_labels": ["T1 2025", "T2 2025", "T3 2025", "T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 3, "total_historico": 4, "saturation_index": 10.08, "promedio_historico": 0.67, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 2, "primer_trimestre_label": "T1 2027"}, {"r2": 0.0171, "mae": 1.4444, "name": "REDES Y TELECOMUNICACIONES", "rmse": 1.8473, "carrera": "PNF en Informática", "history": [0, 0, 5, 0, 0, 0], "capacity": 25, "ci_lower": [0, 0, 0, 0], "ci_upper": [2.18, 2.04, 1.9, 1.85], "ultimo_real": 0, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [0.33, 0.19, 0.05, 0], "history_labels": ["T1 2025", "T2 2025", "T3 2025", "T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 0, "total_historico": 5, "saturation_index": 1.32, "promedio_historico": 0.83, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 0, "primer_trimestre_label": "T1 2027"}, {"r2": 0.0081, "mae": 6.9524, "name": "SISTEMAS DE INFORMACION Y MODELADO DE DATOS", "rmse": 9.0032, "carrera": "PNF en Informática", "history": [4, 6, 30, 11, 16, 5], "capacity": 38, "ci_lower": [4.6, 5.06, 5.51, 5.97], "ci_upper": [22.6, 23.06, 23.51, 23.97], "ultimo_real": 5, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [13.6, 14.06, 14.51, 14.97], "history_labels": ["T1 2025", "T2 2025", "T3 2025", "T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 15, "total_historico": 72, "saturation_index": 39.39, "promedio_historico": 12, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 14, "primer_trimestre_label": "T1 2027"}]
4	2026-10-22 17:02:44.988762	Coordinador CIIDI	\N	Todas las Carreras / PNF	linear	Regresión Lineal Múltiple	pst	1y	4	T3 2026	T1 2028	1.1196	1.0556	68.00	35	19	f	[{"r2": 1, "mae": 0, "name": "DESARROLLO DE APLICACIONES", "rmse": 0, "carrera": "PNF en Informática", "history": [0, 0, 0], "capacity": 25, "ci_lower": [0, 0, 0, 0], "ci_upper": [0, 0, 0, 0], "id_linea": 9, "ultimo_real": 0, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [0, 0, 0, 0], "history_labels": ["T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 0, "total_historico": 0, "saturation_index": 0, "promedio_historico": 0, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 0, "primer_trimestre_label": "T1 2027"}, {"r2": 0.25, "mae": 0.6667, "name": "EDUMATICA", "rmse": 0.7071, "carrera": "PNF en Informática", "history": [0, 2, 1], "capacity": 25, "ci_lower": [1.29, 1.79, 2.29, 2.79], "ci_upper": [2.71, 3.21, 3.71, 4.21], "id_linea": 8, "ultimo_real": 1, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [2, 2.5, 3, 3.5], "history_labels": ["T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 4, "total_historico": 3, "saturation_index": 14, "promedio_historico": 1, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 2, "primer_trimestre_label": "T1 2027"}, {"r2": 1, "mae": 0, "name": "REDES Y TELECOMUNICACIONES", "rmse": 0, "carrera": "PNF en Informática", "history": [0, 0, 0], "capacity": 25, "ci_lower": [0, 0, 0, 0], "ci_upper": [0, 0, 0, 0], "id_linea": 10, "ultimo_real": 0, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [0, 0, 0, 0], "history_labels": ["T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 0, "total_historico": 0, "saturation_index": 0, "promedio_historico": 0, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 0, "primer_trimestre_label": "T1 2027"}, {"r2": 0.2967, "mae": 3.5556, "name": "SISTEMAS DE INFORMACION Y MODELADO DE DATOS", "rmse": 3.7712, "carrera": "PNF en Informática", "history": [11, 16, 5], "capacity": 38, "ci_lower": [0.9, 0, 0, 0], "ci_upper": [8.44, 5.44, 3.77, 3.77], "id_linea": 7, "ultimo_real": 5, "future_labels": ["T1 2027", "T2 2027", "T3 2027", "T1 2028"], "model_applied": "linear", "adoption_curve": [4.67, 1.67, 0, 0], "history_labels": ["T1 2026", "T2 2026", "T3 2026"], "meta_final_pred": 0, "total_historico": 32, "saturation_index": 12.28, "promedio_historico": 10.67, "model_applied_label": "Regresión Lineal Múltiple", "primer_trimestre_pred": 5, "primer_trimestre_label": "T1 2027"}]
\.


--
-- TOC entry 5760 (class 0 OID 51437)
-- Dependencies: 223
-- Data for Name: auditoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auditoria (id, tabla_afectada, id_registro, accion, usuario_responsable, ip_origen, datos_anteriores, datos_nuevos, fecha_hora) FROM stdin;
1	usuarios	1	INSERT	\N	\N	\N	{"email": "andru@gmail.com", "id_rol": 1, "nombre": "Adrus"}	2026-03-23 14:09:42
2	usuarios	2	INSERT	\N	\N	\N	{"email": "lando@gmail.com", "id_rol": 2, "nombre": "lando"}	2026-03-23 14:09:42
3	usuarios	3	INSERT	\N	\N	\N	{"email": "miki@gmail.com", "id_rol": 3, "nombre": "miki"}	2026-03-23 14:09:42
4	usuarios	4	INSERT	\N	\N	\N	{"email": "ale@yaju.com", "id_rol": 3, "nombre": "ale"}	2026-03-23 14:09:42
5	recursos	1	INSERT	\N	\N	\N	{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-03-23 14:09:42
6	recursos	2	INSERT	\N	\N	\N	{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-03-23 14:09:42
7	recursos	3	INSERT	\N	\N	\N	{"titulo": "Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos", "id_tipo_recurso": 2, "ejemplares_totales": 1}	2026-03-23 14:09:42
8	usuarios	4	UPDATE	1	\N	{"activo": 1, "id_rol": 3, "nombre": "ale"}	{"activo": 1, "id_rol": 1, "nombre": "ale"}	2026-03-23 16:14:24
9	recursos	4	INSERT	\N	\N	\N	{"titulo": "Impacto del Cambio Climático en Trujillo - Parte 8", "id_tipo_recurso": 2, "ejemplares_totales": 1}	2026-03-23 16:56:13
10	recursos	5	INSERT	\N	\N	\N	{"titulo": "Simulación de Cargas Estáticas en Puentes - Parte 7", "id_tipo_recurso": 2, "ejemplares_totales": 1}	2026-03-23 16:57:08
11	recursos	6	INSERT	\N	\N	\N	{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-03-23 16:58:11
12	recursos	7	INSERT	\N	\N	\N	{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-03-23 16:58:11
13	recursos	8	INSERT	\N	\N	\N	{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-03-23 16:58:11
14	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "piña"}	{"activo": true, "id_rol": 3, "nombre": "piña"}	2026-06-18 18:46:17.662484
15	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "piña"}	{"activo": true, "id_rol": 3, "nombre": "piña"}	2026-06-18 19:05:42.587427
16	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "piña"}	{"activo": true, "id_rol": 3, "nombre": "piña"}	2026-06-18 19:54:46.993547
17	usuarios	7	INSERT	\N	\N	\N	{"email": "erwazaaaa@gmail.com", "id_rol": 3, "nombre": "Migel González"}	2026-06-18 20:58:57.768568
18	usuarios	8	INSERT	\N	\N	\N	{"email": "yisu@gmail.com", "id_rol": 3, "nombre": "Yisu Monte"}	2026-06-18 20:59:49.682537
19	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Migel González"}	{"activo": true, "id_rol": 1, "nombre": "Migel González"}	2026-06-18 21:38:28.663879
20	usuarios	9	INSERT	\N	\N	\N	{"email": "iaiaia@gmail.com", "id_rol": 3, "nombre": "Pedro Perez"}	2026-06-24 23:07:53.05286
21	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "piña"}	{"activo": true, "id_rol": 3, "nombre": "Piñin"}	2026-06-24 23:57:15.201582
22	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Piñin"}	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	2026-06-24 23:57:20.104368
23	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	{"activo": true, "id_rol": 2, "nombre": "Piñin"}	2026-06-24 23:57:31.726744
24	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Piñin"}	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	2026-06-24 23:57:37.330082
25	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	2026-06-25 00:01:40.353031
26	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	2026-06-25 00:01:46.873155
27	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin"}	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:01:55.730238
28	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:23:15.783421
29	usuarios	10	INSERT	\N	\N	\N	{"email": "wazaaa@gmail.com", "id_rol": 3, "nombre": "Wazaaaa"}	2026-06-25 00:33:07.592137
30	usuarios	11	INSERT	\N	\N	\N	{"email": "123@gmail.com", "id_rol": 3, "nombre": "Juan"}	2026-06-25 00:33:28.49575
31	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:34:43.439159
32	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:34:45.543113
33	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}	{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}	2026-06-25 00:34:51.608311
34	usuarios	10	UPDATE	\N	\N	{"activo": false, "id_rol": 3, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}	2026-06-25 00:35:18.586051
35	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:57:51.974985
36	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:57:57.479012
37	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:58:00.692516
38	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-25 00:58:05.014824
39	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}	2026-06-25 00:58:32.420893
40	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Migel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-06-25 01:22:04.451131
41	usuarios	6	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-06-29 11:45:47.198869
238	recursos	131	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-02 19:58:49.193407
42	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}	2026-06-29 12:10:37.626132
43	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}	2026-06-29 14:18:17.58523
44	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	2026-06-29 14:18:34.834966
45	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	2026-06-29 14:19:05.388306
46	recursos	21	INSERT	\N	\N	\N	{"titulo": "Betty yo a usted la amo", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-04 23:19:52.515406
47	recursos	22	INSERT	\N	\N	\N	{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 11:43:06.035947
48	recursos	23	INSERT	\N	\N	\N	{"titulo": "La Gran Verge", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 11:47:05.718779
49	recursos	24	INSERT	\N	\N	\N	{"titulo": "Pepe", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:02:34.181399
50	recursos	25	INSERT	\N	\N	\N	{"titulo": "Luisito comunicando", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:18:57.684684
51	recursos	26	INSERT	\N	\N	\N	{"titulo": "Manguagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:24:53.394994
52	recursos	27	INSERT	\N	\N	\N	{"titulo": "Que la guagua", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:29:10.583616
53	recursos	28	INSERT	\N	\N	\N	{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:38:15.349753
54	recursos	22	DELETE	\N	\N	{"titulo": "Don Pepe el de los Globos", "id_tipo_recurso": 3}	\N	2026-07-05 12:43:01.095251
55	recursos	26	DELETE	\N	\N	{"titulo": "Manguagua", "id_tipo_recurso": 3}	\N	2026-07-05 12:43:29.378629
58	recursos	31	INSERT	\N	\N	\N	{"titulo": "Imitadora", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 12:57:22.541344
59	recursos	23	DELETE	\N	\N	{"titulo": "La Gran Verge", "id_tipo_recurso": 3}	\N	2026-07-05 13:31:21.954982
60	recursos	32	INSERT	\N	\N	\N	{"titulo": "Manguagua 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 14:44:32.452702
61	recursos	33	INSERT	\N	\N	\N	{"titulo": "Waos", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:04:26.857648
62	recursos	33	DELETE	\N	\N	{"titulo": "Waos", "id_tipo_recurso": 3}	\N	2026-07-05 15:05:03.276405
63	recursos	34	INSERT	\N	\N	\N	{"titulo": "Waos 1", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:24:54.658482
64	recursos	35	INSERT	\N	\N	\N	{"titulo": "Waos 2", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:25:19.934157
65	recursos	36	INSERT	\N	\N	\N	{"titulo": "Waos 3", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:25:52.428559
66	recursos	37	INSERT	\N	\N	\N	{"titulo": "23123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:26:06.835471
67	recursos	38	INSERT	\N	\N	\N	{"titulo": "23", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:26:22.136069
68	recursos	39	INSERT	\N	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:26:32.188843
69	recursos	40	INSERT	\N	\N	\N	{"titulo": "123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:26:43.872553
70	recursos	41	INSERT	\N	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:28:49.036024
71	recursos	42	INSERT	\N	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:28:59.682383
72	recursos	43	INSERT	\N	\N	\N	{"titulo": "123123123123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 15:29:13.986916
73	recursos	44	INSERT	\N	\N	\N	{"titulo": "auuuu", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-05 16:15:08.182516
74	recursos	45	INSERT	\N	\N	\N	{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1, "ejemplares_totales": 2}	2026-07-05 17:21:44.350197
75	recursos	46	INSERT	\N	\N	\N	{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:21:44.350197
76	recursos	47	INSERT	\N	\N	\N	{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1, "ejemplares_totales": 3}	2026-07-05 17:21:44.350197
77	recursos	48	INSERT	\N	\N	\N	{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:21:44.350197
78	recursos	49	INSERT	\N	\N	\N	{"titulo": "Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:39:35.498485
79	recursos	50	INSERT	\N	\N	\N	{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:39:35.498485
80	recursos	51	INSERT	\N	\N	\N	{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:39:35.498485
81	recursos	52	INSERT	\N	\N	\N	{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 17:39:35.498485
85	recursos	56	INSERT	\N	\N	\N	{"titulo": "hola", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 18:14:56.263164
86	recursos	57	INSERT	\N	\N	\N	{"titulo": "hola adios", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-05 18:21:33.639701
87	recursos	56	DELETE	\N	\N	{"titulo": "hola", "id_tipo_recurso": 1}	\N	2026-07-05 18:29:50.693972
185	recursos	105	INSERT	\N	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:17:37.294398
120	recursos	58	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-07-07 00:01:54.74783
234	recursos	128	INSERT	\N	\N	\N	{"titulo": "Materia: Seguridad Informática", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-02 17:22:38.760639
121	recursos	59	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:22:58.539505
122	recursos	60	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:12.838831
123	recursos	61	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:12.876413
124	recursos	62	INSERT	\N	\N	\N	{"titulo": "", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:12.883112
125	recursos	60	DELETE	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:12.899565
126	recursos	61	DELETE	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:12.919034
127	recursos	63	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:34.622457
128	recursos	64	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:34.655711
129	recursos	63	DELETE	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:34.679754
130	recursos	64	DELETE	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:34.704588
131	recursos	65	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:59.650776
132	recursos	66	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:40:59.678997
133	recursos	65	DELETE	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:59.693745
134	recursos	66	DELETE	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}	\N	2026-08-04 09:40:59.706894
135	recursos	67	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:49:20.583041
136	recursos	68	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:49:20.633257
137	recursos	67	DELETE	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}	\N	2026-08-04 09:49:20.648244
138	recursos	68	DELETE	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}	\N	2026-08-04 09:49:20.66095
139	recursos	69	INSERT	\N	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 09:58:54.904249
140	recursos	62	DELETE	\N	\N	{"titulo": "", "id_tipo_recurso": 1}	\N	2026-08-04 09:59:05.308634
141	recursos	70	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:04:38.338495
142	recursos	71	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:04:38.361973
143	recursos	70	DELETE	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DOCUMENTAL MASIVA PARA EL PNF EN INFORMÁTICA - PRUEBA FIFO 1", "id_tipo_recurso": 1}	\N	2026-08-04 10:04:38.374265
144	recursos	71	DELETE	\N	\N	{"titulo": "DESARROLLO DE PLATAFORMA EDUCATIVA EDUMÁTICA INTELIGENTE - PRUEBA FIFO 2", "id_tipo_recurso": 1}	\N	2026-08-04 10:04:38.384732
145	recursos	72	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:11:11.510708
146	recursos	73	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:18:06.569838
147	recursos	73	DELETE	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}	\N	2026-08-04 10:18:06.593982
148	recursos	74	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:21:29.064851
149	recursos	74	DELETE	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}	\N	2026-08-04 10:21:29.089783
150	recursos	75	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:29:50.185276
151	recursos	75	DELETE	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}	\N	2026-08-04 10:29:50.218869
152	recursos	76	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:45:40.223902
153	recursos	76	DELETE	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}	\N	2026-08-04 10:45:40.245315
154	recursos	77	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-04 10:57:10.465552
155	recursos	77	DELETE	\N	\N	{"titulo": "Aplicación Web Móvil para el proceso de Ascensos e Incentivos del Personal Técnico del Cuerpo de Bomberos", "id_tipo_recurso": 1}	\N	2026-08-04 10:57:10.485941
156	recursos	78	INSERT	\N	\N	\N	{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:43:26.945146
157	recursos	79	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:45:15.201621
158	recursos	80	INSERT	\N	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:48:05.633265
159	recursos	81	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:48:05.72936
160	recursos	82	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:48:05.817964
161	recursos	83	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:48:05.91852
162	recursos	84	INSERT	\N	\N	\N	{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:48:06.00888
163	recursos	85	INSERT	\N	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 09:56:42.188313
164	recursos	86	INSERT	\N	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 10:02:04.774776
165	recursos	87	INSERT	\N	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-05 10:34:46.219889
166	recursos	88	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 10:26:58.264555
167	recursos	89	INSERT	\N	\N	\N	{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 10:35:39.007693
168	recursos	90	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 10:45:42.226083
169	recursos	91	INSERT	\N	\N	\N	{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 10:52:47.26864
170	recursos	92	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 11:34:04.575523
171	recursos	93	INSERT	\N	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 11:34:42.145006
172	recursos	94	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 11:40:58.141559
175	recursos	97	INSERT	\N	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:03:37.275866
176	recursos	98	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:04:15.392022
177	recursos	98	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comitésadsds Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-08-10 12:10:31.909346
178	recursos	99	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:10:58.390178
179	recursos	100	INSERT	\N	\N	\N	{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:14:42.285533
180	recursos	101	INSERT	\N	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:16:11.271762
181	recursos	102	INSERT	\N	\N	\N	{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:16:36.642725
182	recursos	103	INSERT	\N	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:17:01.653978
183	recursos	104	INSERT	\N	\N	\N	{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:17:32.045482
184	recursos	104	DELETE	\N	\N	{"titulo": "DEBUG TITLE 1786378652", "id_tipo_recurso": 1}	\N	2026-08-10 12:17:32.05166
186	recursos	106	INSERT	\N	\N	\N	{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:17:54.876228
187	recursos	107	INSERT	\N	\N	\N	{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:18:15.151824
188	recursos	102	DELETE	\N	\N	{"titulo": "TEST PDO RETURNING TITLE 1786378596", "id_tipo_recurso": 1}	\N	2026-08-10 12:18:40.558792
189	recursos	106	DELETE	\N	\N	{"titulo": "DEBUG PST RETURN ID 1786378674", "id_tipo_recurso": 1}	\N	2026-08-10 12:18:40.558792
190	recursos	107	DELETE	\N	\N	{"titulo": "DEBUG PST RETURN ID 1786378695", "id_tipo_recurso": 1}	\N	2026-08-10 12:18:40.558792
239	recursos	130	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-02 19:58:51.059906
191	recursos	108	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-10 12:20:17.959675
192	recursos	101	DELETE	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378571", "id_tipo_recurso": 1}	\N	2026-08-10 12:32:05.654115
193	recursos	103	DELETE	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378621", "id_tipo_recurso": 1}	\N	2026-08-10 12:32:05.654115
194	recursos	105	DELETE	\N	\N	{"titulo": "PST TEST CREAR AUTO 1786378657", "id_tipo_recurso": 1}	\N	2026-08-10 12:32:05.654115
195	recursos	109	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-11 10:10:03.006883
196	recursos	110	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-25 19:01:06.312899
197	recursos	111	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-25 19:01:06.640902
198	recursos	112	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-25 19:01:06.749048
199	recursos	113	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-27 09:08:37.073105
200	recursos	114	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-27 10:17:48.017574
201	recursos	115	INSERT	\N	\N	\N	{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-27 10:46:00.573241
202	recursos	115	DELETE	\N	\N	{"titulo": "INFORME PST IV (1) (1)", "id_tipo_recurso": 1}	\N	2026-08-29 18:33:19.816106
203	recursos	116	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-31 09:47:06.862144
204	recursos	117	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-08-31 10:08:06.559751
205	recursos	44	DELETE	\N	\N	{"titulo": "auuuu", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:33.675276
206	recursos	43	DELETE	\N	\N	{"titulo": "123123123123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:35.310918
207	recursos	42	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:36.983224
208	recursos	41	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:38.628174
209	recursos	40	DELETE	\N	\N	{"titulo": "123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:40.044516
210	recursos	39	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:41.532657
211	recursos	21	DELETE	\N	\N	{"titulo": "La Bebecita Bebelin", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:43.99534
212	recursos	38	DELETE	\N	\N	{"titulo": "23", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:45.967422
213	recursos	37	DELETE	\N	\N	{"titulo": "23123", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:47.549688
214	recursos	36	DELETE	\N	\N	{"titulo": "Waos 3", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:49.179948
215	recursos	35	DELETE	\N	\N	{"titulo": "Waos 2", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:50.605605
216	recursos	34	DELETE	\N	\N	{"titulo": "Waos 1", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:52.414388
217	recursos	32	DELETE	\N	\N	{"titulo": "Manguagua", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:53.763311
218	recursos	31	DELETE	\N	\N	{"titulo": "Imitadora", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:55.392376
219	recursos	28	DELETE	\N	\N	{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3}	\N	2026-09-02 00:21:57.099607
220	recursos	27	DELETE	\N	\N	{"titulo": "Que la guagua", "id_tipo_recurso": 3}	\N	2026-09-02 00:22:29.21399
221	recursos	25	DELETE	\N	\N	{"titulo": "Luisito comunicando", "id_tipo_recurso": 3}	\N	2026-09-02 00:22:30.866944
222	recursos	24	DELETE	\N	\N	{"titulo": "Pepe", "id_tipo_recurso": 3}	\N	2026-09-02 00:22:32.369068
223	recursos	118	INSERT	\N	\N	\N	{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:23:21.823509
224	recursos	119	INSERT	\N	\N	\N	{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:23:21.823509
225	recursos	120	INSERT	\N	\N	\N	{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:23:21.823509
226	recursos	121	INSERT	\N	\N	\N	{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:23:21.823509
227	recursos	122	INSERT	\N	\N	\N	{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:23:21.823509
228	recursos	123	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 00:30:00.179124
229	recursos	123	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-02 00:38:17.131525
233	recursos	127	INSERT	\N	\N	\N	{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-02 15:10:22.258855
235	recursos	129	INSERT	\N	\N	\N	{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-02 17:24:52.328736
236	recursos	130	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 18:00:56.314983
237	recursos	131	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-02 18:02:41.164769
240	recursos	132	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-04 10:35:11.057661
241	recursos	133	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 13:50:26.015403
242	recursos	134	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 13:52:40.315328
243	recursos	134	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-04 13:53:04.485629
244	recursos	133	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-04 13:53:06.306206
245	recursos	135	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 13:54:05.876851
246	recursos	136	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 14:19:43.111334
247	recursos	137	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 14:22:18.231644
248	recursos	138	INSERT	\N	\N	\N	{"titulo": "w", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 14:23:15.04343
249	recursos	139	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 22:47:39.66015
250	recursos	140	INSERT	\N	\N	\N	{"titulo": "E", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-04 23:26:52.51645
251	recursos	136	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-04 23:56:47.967712
252	recursos	135	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-04 23:57:41.917589
253	recursos	141	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-05 00:06:53.601656
254	recursos	141	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-05 00:23:32.107364
255	recursos	140	DELETE	\N	\N	{"titulo": "E", "id_tipo_recurso": 3}	\N	2026-09-05 00:23:38.352733
256	recursos	139	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-05 00:23:40.723753
257	recursos	138	DELETE	\N	\N	{"titulo": "w", "id_tipo_recurso": 3}	\N	2026-09-05 00:23:43.029266
258	recursos	137	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-05 00:23:45.640379
259	recursos	142	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-07 15:37:00.278657
260	recursos	142	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-07 15:37:48.807771
261	recursos	143	INSERT	\N	\N	\N	{"titulo": "Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-07 15:40:32.098085
262	recursos	144	INSERT	\N	\N	\N	{"titulo": "Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-09 00:24:54.886595
263	recursos	145	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-09 00:36:32.789579
264	recursos	145	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-09-09 00:49:28.135237
265	recursos	146	INSERT	\N	\N	\N	{"titulo": "Modelamiento de confort adaptativo para un trapiche panelero", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-09 00:51:39.29162
266	usuarios	12	INSERT	\N	\N	\N	{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "ANDRUS"}	2026-09-09 19:26:21.824338
267	recursos	129	DELETE	\N	\N	{"titulo": "Verde   Gestion de BD", "id_tipo_recurso": 1}	\N	2026-09-09 23:34:35.112448
268	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-09-10 11:09:57.334
269	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}	2026-09-10 11:33:11.894408
270	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	2026-09-10 11:39:27.139879
271	recursos	147	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-10 21:02:31.80467
272	recursos	148	INSERT	\N	\N	\N	{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-10 21:02:31.979685
273	recursos	149	INSERT	\N	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-10 21:02:32.217243
274	recursos	150	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-11 11:59:24.004409
275	recursos	151	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-09-11 15:27:57.58139
276	usuarios	13	INSERT	\N	\N	\N	{"email": "cepillin@gmail.com", "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 15:37:03.687571
277	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 16:33:03.211027
278	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:33:08.737789
279	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:33:13.015607
280	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:33:17.017251
281	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:33:25.348502
282	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:33:30.152351
283	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 16:36:55.505126
302	usuarios	14	INSERT	\N	\N	\N	{"email": "676767@gmail.com", "id_rol": 3, "nombre": "Sixsevenaldo González"}	2026-09-12 18:33:01.584383
284	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 16:36:59.169874
285	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	2026-09-11 16:37:03.711934
286	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	2026-09-11 16:37:10.689659
287	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	2026-09-11 16:37:17.568688
288	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	2026-09-11 17:40:06.161413
289	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	2026-09-11 17:41:47.64796
290	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 17:41:50.689906
291	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}	2026-09-11 22:36:49.699268
292	usuarios	12	UPDATE	\N	\N	{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	2026-09-11 22:36:53.227644
293	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}	2026-09-11 22:36:55.198334
294	usuarios	12	UPDATE	\N	\N	{"activo": false, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	2026-09-11 22:36:57.977504
295	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": false, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 22:40:14.652503
296	usuarios	13	UPDATE	\N	\N	{"activo": false, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	2026-09-11 22:40:16.809997
297	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillín"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 22:49:57.897673
298	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 22:50:01.579119
299	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 22:50:11.080621
300	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 22:50:16.912961
301	usuarios	13	UPDATE	\N	\N	{"activo": false, "id_rol": 3, "nombre": "Cepillíno"}	{"activo": true, "id_rol": 3, "nombre": "Cepillíno"}	2026-09-11 22:50:18.90332
303	usuarios	15	INSERT	\N	\N	\N	{"email": "DIOS@gmail.com", "id_rol": 1, "nombre": "DIOS"}	2026-09-13 23:57:20.408394
304	usuarios	16	INSERT	\N	\N	\N	{"email": "orlando5711666@gmail.com", "id_rol": 3, "nombre": "no soy miguel"}	2026-09-16 22:56:54.613604
305	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "ANDRUS"}	{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}	2026-09-19 14:19:20.874131
306	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "ANDRUS"}	{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}	2026-09-19 14:55:59.004653
307	usuarios	17	INSERT	\N	\N	\N	{"email": "andrusramirez2020@gmail.com", "id_rol": 3, "nombre": "30469331"}	2026-09-19 14:57:10.314214
308	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "30469331"}	{"activo": true, "id_rol": 3, "nombre": "adru"}	2026-09-19 14:59:29.871771
309	usuarios	15	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "DIOS"}	{"activo": true, "id_rol": 1, "nombre": "DIOSs"}	2026-09-19 15:13:32.480165
310	usuarios	15	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "DIOSs"}	{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}	2026-09-19 15:13:42.378327
311	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "adru"}	{"activo": true, "id_rol": 3, "nombre": "adruss"}	2026-09-19 15:19:07.37854
312	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "adruss"}	{"activo": true, "id_rol": 2, "nombre": "adruss"}	2026-09-19 15:19:14.229116
313	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adruss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-09-20 16:36:49.932377
314	recursos	152	INSERT	\N	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-23 11:21:36.922266
315	recursos	152	DELETE	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	\N	2026-09-23 11:22:57.677896
316	recursos	153	INSERT	\N	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1, "ejemplares_totales": 1}	2026-09-23 11:39:58.402099
317	recursos	153	DELETE	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	\N	2026-09-23 11:45:02.317989
318	recursos	156	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	2026-09-23 12:09:57.549672
319	recursos	157	INSERT	\N	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	2026-09-23 12:10:19.262043
320	recursos	157	DELETE	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	\N	2026-09-23 12:29:33.377305
321	recursos	158	INSERT	\N	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	2026-09-23 12:29:57.034447
322	recursos	159	INSERT	\N	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}	2026-09-25 23:45:29.603658
323	recursos	160	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}	2026-09-25 23:47:32.841904
324	recursos	160	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales (TEST_SIMULADO_1790394452)", "id_tipo_recurso": 1}	\N	2026-09-25 23:47:32.870735
325	recursos	161	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-26 00:01:30.60975
326	recursos	162	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}	2026-09-26 00:02:21.96766
327	recursos	163	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-26 00:03:12.854033
328	usuarios	19	INSERT	\N	\N	\N	{"email": "juanxzall0701@gmail.com", "id_rol": 2, "nombre": "Juan Salcedo"}	2026-09-28 20:05:54.593867
329	recursos	162	DELETE	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}	\N	2026-09-28 20:20:08.135505
330	recursos	163	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 20:20:14.51703
331	recursos	147	DELETE	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A", "id_tipo_recurso": 1}	\N	2026-09-28 20:22:34.941428
332	recursos	117	DELETE	\N	\N	{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}	\N	2026-09-28 20:23:10.410984
333	recursos	159	DELETE	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}	\N	2026-09-28 20:27:16.267346
334	recursos	158	DELETE	\N	\N	{"titulo": "PROYEC_YOHAN_-_BETSABÉ_-_MIGUEL_ TERMINADO_REVISADO", "id_tipo_recurso": 1}	\N	2026-09-28 20:27:18.567257
335	recursos	164	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	2026-09-28 23:19:51.125311
336	recursos	1	DELETE	\N	\N	{"titulo": "Sistema de Reconocimiento Biométrico Facial para Comedor Universitario", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
337	recursos	2	DELETE	\N	\N	{"titulo": "Prototipo de Cerradura Digital con Matriz de Teclado y Arduino", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
338	recursos	6	DELETE	\N	\N	{"titulo": "Big Data en Finanzas Institucionales - Parte 9", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
339	recursos	7	DELETE	\N	\N	{"titulo": "Optimización de CPU en Servidores Locales - Parte 7", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
340	recursos	8	DELETE	\N	\N	{"titulo": "Sistemas de Riego Automatizado - Parte 5", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
341	recursos	15	DELETE	\N	\N	{"titulo": "Criptografía Cuántica Post-RSA - Parte 2", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
342	recursos	17	DELETE	\N	\N	{"titulo": "Criptografía Cuántica Post-RSA - Parte 5", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
343	recursos	18	DELETE	\N	\N	{"titulo": "Criptografía Cuántica Post-RSA - Parte 8", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
344	recursos	19	DELETE	\N	\N	{"titulo": "Software Libre para Bibliotecas - Parte 6", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
345	recursos	45	DELETE	\N	\N	{"titulo": "Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
346	recursos	46	DELETE	\N	\N	{"titulo": "Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
347	recursos	47	DELETE	\N	\N	{"titulo": "Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
348	recursos	48	DELETE	\N	\N	{"titulo": "Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
349	recursos	50	DELETE	\N	\N	{"titulo": "Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
350	recursos	51	DELETE	\N	\N	{"titulo": "Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
351	recursos	52	DELETE	\N	\N	{"titulo": "Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
352	recursos	57	DELETE	\N	\N	{"titulo": "hola adios", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
353	recursos	58	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
354	recursos	59	DELETE	\N	\N	{"titulo": "SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
355	recursos	69	DELETE	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
356	recursos	72	DELETE	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
357	recursos	78	DELETE	\N	\N	{"titulo": "PST Prueba Carga por Lotes - 20260805134326", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
358	recursos	79	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
359	recursos	80	DELETE	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
360	recursos	81	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
426	usuarios	21	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:41.676708
361	recursos	82	DELETE	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
362	recursos	83	DELETE	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
363	recursos	84	DELETE	\N	\N	{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
364	recursos	85	DELETE	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805135642", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
365	recursos	86	DELETE	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805140204", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
366	recursos	87	DELETE	\N	\N	{"titulo": "PST Prueba Duplicados - 20260805143446", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
367	recursos	88	DELETE	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
368	recursos	89	DELETE	\N	\N	{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
369	recursos	90	DELETE	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
370	recursos	91	DELETE	\N	\N	{"titulo": "Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
371	recursos	94	DELETE	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
372	recursos	93	DELETE	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
373	recursos	92	DELETE	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
374	recursos	112	DELETE	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
375	recursos	97	DELETE	\N	\N	{"titulo": "Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
376	recursos	99	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
377	recursos	100	DELETE	\N	\N	{"titulo": "il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
378	recursos	108	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
379	recursos	109	DELETE	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
380	recursos	110	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
381	recursos	111	DELETE	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
382	recursos	113	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
383	recursos	114	DELETE	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
384	recursos	116	DELETE	\N	\N	{"titulo": "SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
385	recursos	127	DELETE	\N	\N	{"titulo": "ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
386	recursos	132	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
387	recursos	148	DELETE	\N	\N	{"titulo": "VALERA EDO TRUJILLO Aplicación Web Móvil para el proceso de Ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra. María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante...", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
388	recursos	149	DELETE	\N	\N	{"titulo": "NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
389	recursos	49	DELETE	\N	\N	{"titulo": "Sistema de Información Automatizado para la Gestión de Inventario y Suministros Médicos", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
390	recursos	161	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para elsdasdasdasdsa Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
391	recursos	128	DELETE	\N	\N	{"titulo": "Materia: Seguridad Informáticasssssss", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
392	recursos	164	DELETE	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	\N	2026-09-28 23:32:21.596096
393	recursos	173	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
394	recursos	174	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
395	recursos	175	INSERT	\N	\N	\N	{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
396	recursos	176	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
397	recursos	177	INSERT	\N	\N	\N	{"titulo": "Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
398	recursos	178	INSERT	\N	\N	\N	{"titulo": "DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
399	recursos	179	INSERT	\N	\N	\N	{"titulo": "CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
400	recursos	180	INSERT	\N	\N	\N	{"titulo": "Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda 'Tuti' del Cantón Vinces", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
401	recursos	181	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}	2026-09-28 23:32:21.596096
402	recursos	181	DELETE	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN Y GESTIÓN DE PROYECTOS DE GRADO", "id_tipo_recurso": 1}	\N	2026-09-28 23:36:50.181089
403	recursos	182	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-28 23:55:27.29017
404	recursos	183	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-28 23:55:52.326738
405	recursos	183	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-29 00:18:32.939693
406	recursos	182	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-29 00:18:42.813407
407	recursos	184	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-29 00:48:27.834532
408	recursos	184	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-29 00:48:57.405027
409	recursos	185	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-09-29 00:55:08.390241
410	recursos	185	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-09-29 00:55:19.687702
411	recursos	186	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	2026-09-30 19:49:51.452795
412	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}	2026-10-03 12:40:18.137748
413	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-10-03 12:44:23.513489
414	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-10-03 12:44:23.520783
415	usuarios	20	INSERT	\N	\N	\N	{"email": "qa_test_ciidi_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}	2026-10-03 12:44:23.792227
416	usuarios	20	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	2026-10-03 12:44:23.796502
417	usuarios	20	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:23.798405
418	usuarios	20	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:23.800171
419	usuarios	20	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:24.555246
420	usuarios	20	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Admin'; DROP TABLE dummy_test; --"}	2026-10-03 12:44:24.804573
421	usuarios	20	DELETE	\N	\N	{"email": "qa_nuevo_1791045863@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin'; DROP TABLE dummy_test; --"}	\N	2026-10-03 12:44:24.813668
422	usuarios	3	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "miki"}	{"activo": true, "id_rol": 3, "nombre": "miki"}	2026-10-03 12:44:41.401758
423	usuarios	3	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "miki"}	{"activo": true, "id_rol": 3, "nombre": "miki"}	2026-10-03 12:44:41.408021
424	usuarios	21	INSERT	\N	\N	\N	{"email": "qa_test_ciidi_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Usuario Regular QA"}	2026-10-03 12:44:41.673185
425	usuarios	21	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	{"activo": true, "id_rol": 3, "nombre": "Usuario Regular QA"}	2026-10-03 12:44:41.674697
427	usuarios	21	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:41.679952
428	usuarios	21	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	2026-10-03 12:44:42.447266
429	usuarios	21	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Super Administrador Modificado"}	{"activo": true, "id_rol": 3, "nombre": "Admin'; DROP TABLE dummy_test; --"}	2026-10-03 12:44:42.693185
430	usuarios	21	DELETE	\N	\N	{"email": "qa_nuevo_1791045881@upttmbi.edu.ve", "id_rol": 3, "nombre": "Admin'; DROP TABLE dummy_test; --"}	\N	2026-10-03 12:44:42.704726
431	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzálezzzzz"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:27:37.178154
432	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:28:47.02622
433	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:28:47.031874
434	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:29:04.750162
435	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:29:04.755525
436	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-03 13:29:10.8743
438	recursos	188	INSERT	\N	\N	\N	{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}	2026-10-04 14:20:31.26127
439	recursos	188	DELETE	\N	\N	{"titulo": "Proyecto Interdisciplinario Test 1791138031", "id_tipo_recurso": 1}	\N	2026-10-04 14:20:31.322724
440	recursos	189	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-10-04 14:23:02.803018
441	recursos	190	INSERT	\N	\N	\N	{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}	2026-10-04 14:31:09.763754
442	recursos	190	DELETE	\N	\N	{"titulo": "Proyecto Interdisciplinario Test 1791138669", "id_tipo_recurso": 1}	\N	2026-10-04 14:31:09.821791
443	recursos	191	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-10-04 16:43:25.694943
444	recursos	192	INSERT	\N	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	2026-10-04 17:14:02.756345
445	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-10-12 19:14:13.860953
446	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	2026-10-12 19:28:22.970838
447	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-10-12 19:28:22.970838
448	usuarios	12	UPDATE	\N	\N	{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}	{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}	2026-10-12 19:28:22.970838
449	usuarios	15	UPDATE	\N	\N	{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}	{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}	2026-10-12 19:28:22.970838
450	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-10-12 19:28:22.970838
451	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "Juan Salcedo"}	{"activo": true, "id_rol": 2, "nombre": "Juan Salcedo"}	2026-10-12 19:28:22.970838
452	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-10-12 19:28:22.970838
453	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-12 19:28:22.970838
454	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-10-12 19:28:22.970838
455	recursos	193	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
456	recursos	194	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
457	recursos	195	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
458	recursos	196	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
459	recursos	197	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
460	recursos	198	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
461	recursos	199	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
462	recursos	200	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
463	recursos	201	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
464	recursos	202	INSERT	\N	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
465	recursos	203	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
466	recursos	204	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
467	recursos	205	INSERT	\N	\N	\N	{"titulo": "Arquitectura de Datos y Sistema Transaccional para la Distribución de Insumos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
468	recursos	206	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
469	recursos	207	INSERT	\N	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
470	recursos	208	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
471	recursos	209	INSERT	\N	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
472	recursos	210	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
473	recursos	211	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
474	recursos	212	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
475	recursos	213	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
476	recursos	214	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
477	recursos	215	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
478	recursos	216	INSERT	\N	\N	\N	{"titulo": "Arquitectura de Datos y Sistema Transaccional para la Distribución de Insumos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
479	recursos	217	INSERT	\N	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
480	recursos	218	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
481	recursos	219	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
482	recursos	220	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
483	recursos	221	INSERT	\N	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
484	recursos	222	INSERT	\N	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
485	recursos	223	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
486	recursos	224	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
487	recursos	225	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
488	recursos	226	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
489	recursos	227	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
490	recursos	228	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
491	recursos	229	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
492	recursos	230	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
493	recursos	231	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
494	recursos	232	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
495	recursos	233	INSERT	\N	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
496	recursos	234	INSERT	\N	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
497	recursos	235	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
498	recursos	236	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
499	recursos	237	INSERT	\N	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
500	recursos	238	INSERT	\N	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
501	recursos	239	INSERT	\N	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
502	recursos	240	INSERT	\N	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
503	recursos	241	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
504	recursos	242	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
505	recursos	243	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
506	recursos	244	INSERT	\N	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
507	recursos	245	INSERT	\N	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
508	recursos	246	INSERT	\N	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
509	recursos	247	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
510	recursos	248	INSERT	\N	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
511	recursos	249	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
512	recursos	250	INSERT	\N	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
513	recursos	251	INSERT	\N	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
514	recursos	252	INSERT	\N	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
515	recursos	253	INSERT	\N	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
516	recursos	254	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
517	recursos	255	INSERT	\N	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
518	recursos	256	INSERT	\N	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
519	recursos	257	INSERT	\N	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
520	recursos	258	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
521	recursos	259	INSERT	\N	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
522	recursos	260	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
523	recursos	261	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
524	recursos	262	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
525	recursos	263	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
526	recursos	264	INSERT	\N	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
527	recursos	265	INSERT	\N	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
528	recursos	266	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
529	recursos	267	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
530	recursos	268	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
531	recursos	269	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
532	recursos	270	INSERT	\N	\N	\N	{"titulo": "Plataforma E-learning Adaptativa para la Capacitación Comunitaria en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
533	recursos	271	INSERT	\N	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
534	recursos	272	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
535	recursos	273	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
536	recursos	274	INSERT	\N	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
537	recursos	275	INSERT	\N	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
538	recursos	276	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
539	recursos	277	INSERT	\N	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
540	recursos	278	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
541	recursos	279	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
542	recursos	280	INSERT	\N	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
543	recursos	281	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
544	recursos	282	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
545	recursos	283	INSERT	\N	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
546	recursos	284	INSERT	\N	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
547	recursos	285	INSERT	\N	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
548	recursos	286	INSERT	\N	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
549	recursos	287	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
550	recursos	288	INSERT	\N	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
551	recursos	289	INSERT	\N	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
552	recursos	290	INSERT	\N	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
553	recursos	291	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
554	recursos	292	INSERT	\N	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
555	recursos	293	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
556	recursos	294	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
557	recursos	295	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
558	recursos	296	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
559	recursos	297	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
560	recursos	298	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
561	recursos	299	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
562	recursos	300	INSERT	\N	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
563	recursos	301	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
564	recursos	302	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
565	recursos	303	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
566	recursos	304	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
567	recursos	305	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
568	recursos	306	INSERT	\N	\N	\N	{"titulo": "Plataforma Web de Comercio Justo y Enlace Comercial para Productores de la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
569	recursos	307	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
570	recursos	308	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
571	recursos	309	INSERT	\N	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
572	recursos	310	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
573	recursos	311	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
574	recursos	312	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
575	recursos	313	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
576	recursos	314	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
577	recursos	315	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
578	recursos	316	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
579	recursos	317	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
580	recursos	318	INSERT	\N	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
581	recursos	319	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
582	recursos	320	INSERT	\N	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
583	recursos	321	INSERT	\N	\N	\N	{"titulo": "Plataforma Web de Comercio Justo y Enlace Comercial para Productores de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
584	recursos	322	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
585	recursos	323	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
586	recursos	324	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
587	recursos	325	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
588	recursos	326	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
589	recursos	327	INSERT	\N	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
590	recursos	328	INSERT	\N	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
591	recursos	329	INSERT	\N	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
592	recursos	330	INSERT	\N	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
593	recursos	331	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
594	recursos	332	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
595	recursos	333	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
596	recursos	334	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
597	recursos	335	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
598	recursos	336	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
599	recursos	337	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
600	recursos	338	INSERT	\N	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
601	recursos	339	INSERT	\N	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
602	recursos	340	INSERT	\N	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
603	recursos	341	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
604	recursos	342	INSERT	\N	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
605	recursos	343	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
606	recursos	344	INSERT	\N	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
607	recursos	345	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
608	recursos	346	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
609	recursos	347	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
610	recursos	348	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
611	recursos	349	INSERT	\N	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
612	recursos	350	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
613	recursos	351	INSERT	\N	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
614	recursos	352	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
615	recursos	353	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
616	recursos	354	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
617	recursos	355	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
618	recursos	356	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
619	recursos	357	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
620	recursos	358	INSERT	\N	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
621	recursos	359	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
622	recursos	360	INSERT	\N	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
623	recursos	361	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
624	recursos	362	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
625	recursos	363	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
626	recursos	364	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
627	recursos	365	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
628	recursos	366	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
629	recursos	367	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
630	recursos	368	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
631	recursos	369	INSERT	\N	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
632	recursos	370	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
633	recursos	371	INSERT	\N	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
634	recursos	372	INSERT	\N	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
635	recursos	373	INSERT	\N	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
636	recursos	374	INSERT	\N	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
637	recursos	375	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
638	recursos	376	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
639	recursos	377	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
640	recursos	378	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
641	recursos	379	INSERT	\N	\N	\N	{"titulo": "Sistema de Monitoreo de Tráfico y Seguridad de Red Basado en Software Libre en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
642	recursos	380	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
643	recursos	381	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
644	recursos	382	INSERT	\N	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
645	recursos	383	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
646	recursos	384	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
647	recursos	385	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
648	recursos	386	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
649	recursos	387	INSERT	\N	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
650	recursos	388	INSERT	\N	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
651	recursos	389	INSERT	\N	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
652	recursos	390	INSERT	\N	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
653	recursos	391	INSERT	\N	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
654	recursos	392	INSERT	\N	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	2026-10-12 21:40:55.004565
655	usuarios	22	INSERT	\N	\N	\N	{"email": "yisus@gmail.com", "id_rol": 4, "nombre": "MAMA DE LANDO"}	2026-10-14 12:10:53.300541
656	usuarios	23	INSERT	\N	\N	\N	{"email": "orlando57116666@gmail.com", "id_rol": 4, "nombre": "LANDO2"}	2026-10-14 12:13:09.901766
657	usuarios	23	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "LANDO2"}	{"activo": false, "id_rol": 4, "nombre": "[Archivado] LANDO2"}	2026-10-14 12:18:00.547422
658	usuarios	22	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "MAMA DE LANDO"}	{"activo": false, "id_rol": 4, "nombre": "[Archivado] MAMA DE LANDO"}	2026-10-14 12:18:08.814456
659	usuarios	24	INSERT	\N	\N	\N	{"email": "lando1609721@gmail.com", "id_rol": 4, "nombre": "landorus"}	2026-10-14 12:19:00.607571
660	usuarios	19	UPDATE	7	\N	{"activo": true, "id_rol": 2, "nombre": "Juan Salcedo"}	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	2026-10-20 13:53:34.243244
661	usuarios	19	UPDATE	7	\N	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	2026-10-20 13:56:21.740967
1000	recursos	3	DELETE	\N	\N	{"titulo": "Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos", "id_tipo_recurso": 2}	\N	2026-10-20 23:06:03.658644
1001	recursos	4	DELETE	\N	\N	{"titulo": "Impacto del Cambio Climático en Trujillo - Parte 8", "id_tipo_recurso": 2}	\N	2026-10-20 23:06:03.658644
1002	recursos	5	DELETE	\N	\N	{"titulo": "Simulación de Cargas Estáticas en Puentes - Parte 7", "id_tipo_recurso": 2}	\N	2026-10-20 23:06:03.658644
1003	recursos	186	DELETE	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1004	recursos	9	DELETE	\N	\N	{"titulo": "Bioinformática y Análisis de ADN - Parte 5", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1005	recursos	10	DELETE	\N	\N	{"titulo": "Inteligencia Artificial en Diagnóstico Médico - Parte 6", "id_tipo_recurso": 2}	\N	2026-10-20 23:06:03.658644
1006	recursos	11	DELETE	\N	\N	{"titulo": "Robótica Educativa para Escuelas - Parte 5", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1007	recursos	12	DELETE	\N	\N	{"titulo": "Software Libre para Bibliotecas - Parte 1", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1008	recursos	13	DELETE	\N	\N	{"titulo": "E-Learning para Zonas Desfavorecidas - Parte 1", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1009	recursos	14	DELETE	\N	\N	{"titulo": "Telecomunicaciones de Fibra Óptica Rural - Parte 1", "id_tipo_recurso": 2}	\N	2026-10-20 23:06:03.658644
1010	recursos	16	DELETE	\N	\N	{"titulo": "Criptografía Cuántica Post-RSA - Parte 7", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1011	recursos	20	DELETE	\N	\N	{"titulo": "Inteligencia Artificial en Diagnóstico Médico - Parte 1", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1012	recursos	191	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1013	recursos	122	DELETE	\N	\N	{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1014	recursos	121	DELETE	\N	\N	{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1015	recursos	120	DELETE	\N	\N	{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1016	recursos	119	DELETE	\N	\N	{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1017	recursos	118	DELETE	\N	\N	{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1018	recursos	143	DELETE	\N	\N	{"titulo": "Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1019	recursos	146	DELETE	\N	\N	{"titulo": "Modelamiento de confort adaptativo para un trapiche panelero", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1020	recursos	144	DELETE	\N	\N	{"titulo": "Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1021	recursos	150	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1022	recursos	151	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1023	recursos	156	DELETE	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	\N	2026-10-20 23:06:03.658644
1024	recursos	179	DELETE	\N	\N	{"titulo": "CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1025	recursos	173	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1026	recursos	174	DELETE	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1027	recursos	175	DELETE	\N	\N	{"titulo": "MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1028	recursos	176	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión Comercial y Tienda Virtual para Smartphone World C.A.", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1029	recursos	177	DELETE	\N	\N	{"titulo": "Sistema de Optimización basado en Algoritmos Genéticos para la Gestión de Horarios del PNFI de la UPTTMBI, Núcleo La Beatriz", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1030	recursos	178	DELETE	\N	\N	{"titulo": "DISEÑO Y PROTOTIPO DE UNA APLICACIÓN CLIENTE-SERVIDOR QUE PERMITA EJECUTAR COMANDOS BÁSICOS EN UN SERVIDOR REMOTO DESDE UN DISPOSITIVO MÓVIL", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1031	recursos	180	DELETE	\N	\N	{"titulo": "Aplicación web cliente-servidor para el control de inventario que indique el porcentaje de consumo de acuerdo al semáforo nutricional en la tienda 'Tuti' del Cantón Vinces", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1032	recursos	189	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1033	recursos	192	DELETE	\N	\N	{"titulo": "Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1034	recursos	193	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1035	recursos	194	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1036	recursos	195	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1037	recursos	196	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1038	recursos	197	DELETE	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1039	recursos	198	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1040	recursos	199	DELETE	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1041	recursos	200	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1042	recursos	201	DELETE	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1043	recursos	202	DELETE	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1044	recursos	203	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1045	recursos	204	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1046	recursos	205	DELETE	\N	\N	{"titulo": "Arquitectura de Datos y Sistema Transaccional para la Distribución de Insumos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1047	recursos	206	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1048	recursos	207	DELETE	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1049	recursos	208	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1050	recursos	209	DELETE	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1051	recursos	210	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1052	recursos	211	DELETE	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1053	recursos	212	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1054	recursos	213	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1055	recursos	214	DELETE	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1056	recursos	215	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1057	recursos	216	DELETE	\N	\N	{"titulo": "Arquitectura de Datos y Sistema Transaccional para la Distribución de Insumos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1058	recursos	217	DELETE	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1059	recursos	218	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1325	recursos	293	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1060	recursos	219	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1061	recursos	220	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1062	recursos	221	DELETE	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1063	recursos	222	DELETE	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1064	recursos	223	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1065	recursos	224	DELETE	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1066	recursos	225	DELETE	\N	\N	{"titulo": "Sistema de Información Gerencial para la Toma de Decisiones y Monitoreo en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1067	recursos	226	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1068	recursos	227	DELETE	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1069	recursos	228	DELETE	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1070	recursos	229	DELETE	\N	\N	{"titulo": "Sistema de Información para la Gestión de Bienes Nacionales y Activos Fijos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1071	recursos	230	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1072	recursos	231	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1073	recursos	232	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1074	recursos	233	DELETE	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1075	recursos	234	DELETE	\N	\N	{"titulo": "Plataforma de Información para el Registro y Control de Productores Agrícolas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1076	recursos	235	DELETE	\N	\N	{"titulo": "Sistema Automatizado de Expedientes Académicos y Registro Estudiantil para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1077	recursos	236	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1078	recursos	237	DELETE	\N	\N	{"titulo": "Gestor de Información Catastral y Recaudación Tributaria Local en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1079	recursos	238	DELETE	\N	\N	{"titulo": "Sistema Automatizado para el Control de Asistencia y Nómina del Personal en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1080	recursos	239	DELETE	\N	\N	{"titulo": "Modelo Relacional y Sistema de Gestión Documental para el Archivo Central de la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1081	recursos	240	DELETE	\N	\N	{"titulo": "Modelo de Base de Datos y Sistema Automatizado para la Gestión de Historias Clínicas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1082	recursos	241	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1083	recursos	242	DELETE	\N	\N	{"titulo": "Sistema de Información Web para el Control y Gestión de Inventarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1084	recursos	243	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1085	recursos	244	DELETE	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1086	recursos	245	DELETE	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1087	recursos	246	DELETE	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1088	recursos	247	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1089	recursos	248	DELETE	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1090	recursos	249	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1091	recursos	250	DELETE	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1092	recursos	251	DELETE	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1093	recursos	252	DELETE	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1094	recursos	253	DELETE	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1095	recursos	254	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1096	recursos	255	DELETE	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1097	recursos	256	DELETE	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1098	recursos	257	DELETE	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1099	recursos	258	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1100	recursos	259	DELETE	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1101	recursos	260	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1102	recursos	261	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1103	recursos	262	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1104	recursos	263	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1105	recursos	264	DELETE	\N	\N	{"titulo": "Herramienta Multimedia Educativa sobre Educación Ambiental y Reciclaje en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1106	recursos	265	DELETE	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1107	recursos	266	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1108	recursos	267	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1109	recursos	268	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1110	recursos	269	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1111	recursos	270	DELETE	\N	\N	{"titulo": "Plataforma E-learning Adaptativa para la Capacitación Comunitaria en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1112	recursos	271	DELETE	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1113	recursos	272	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1114	recursos	273	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1115	recursos	274	DELETE	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1116	recursos	275	DELETE	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1117	recursos	276	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1118	recursos	277	DELETE	\N	\N	{"titulo": "Software Didáctico para el Desarrollo del Pensamiento Computacional en Educación Primaria en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1119	recursos	278	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1120	recursos	279	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1121	recursos	280	DELETE	\N	\N	{"titulo": "Laboratorio Virtual de Ciencias Naturales como Apoyo Pedagógico para Estudiantes de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1122	recursos	281	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1326	recursos	294	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1123	recursos	282	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1124	recursos	283	DELETE	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1125	recursos	284	DELETE	\N	\N	{"titulo": "Sistema Tutor Inteligente para el Refuerzo de Razonamiento Lógico en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1126	recursos	285	DELETE	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1127	recursos	286	DELETE	\N	\N	{"titulo": "Objeto Virtual de Aprendizaje (OVA) Interactivo para la Formación Tecnológica en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1128	recursos	287	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1129	recursos	288	DELETE	\N	\N	{"titulo": "Plataforma Web Gamificada para la Enseñanza de Lectoescritura en Niños de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1130	recursos	289	DELETE	\N	\N	{"titulo": "Entorno Virtual de Aprendizaje para la Inclusión Educativa de Estudiantes con Discapacidad en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1131	recursos	290	DELETE	\N	\N	{"titulo": "Software Educativo Interactivo para el Fortalecimiento del Aprendizaje de Matemáticas en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1132	recursos	291	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1133	recursos	292	DELETE	\N	\N	{"titulo": "Aplicación Didáctica Multimedia para la Estimulación Cognitiva en el Aula de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1134	recursos	293	DELETE	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1135	recursos	294	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1136	recursos	295	DELETE	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1137	recursos	296	DELETE	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1138	recursos	297	DELETE	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1139	recursos	298	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1140	recursos	299	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1141	recursos	300	DELETE	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1142	recursos	301	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1143	recursos	302	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1144	recursos	303	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1145	recursos	304	DELETE	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1146	recursos	305	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1147	recursos	306	DELETE	\N	\N	{"titulo": "Plataforma Web de Comercio Justo y Enlace Comercial para Productores de la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1148	recursos	307	DELETE	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1149	recursos	308	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1150	recursos	309	DELETE	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1151	recursos	310	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1152	recursos	311	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1153	recursos	312	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1154	recursos	313	DELETE	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1155	recursos	314	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1156	recursos	315	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1157	recursos	316	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1158	recursos	317	DELETE	\N	\N	{"titulo": "Aplicación Móvil Híbrida para la Gestión y Reservación de Espacios Comunitarios en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1159	recursos	318	DELETE	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1160	recursos	319	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1161	recursos	320	DELETE	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1162	recursos	321	DELETE	\N	\N	{"titulo": "Plataforma Web de Comercio Justo y Enlace Comercial para Productores de el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1163	recursos	322	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1164	recursos	323	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1165	recursos	324	DELETE	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1166	recursos	325	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1167	recursos	326	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1168	recursos	327	DELETE	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1169	recursos	328	DELETE	\N	\N	{"titulo": "Aplicación de Gestión de Rutas y Logística de Distribución con Notificaciones Push para la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1170	recursos	329	DELETE	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1171	recursos	330	DELETE	\N	\N	{"titulo": "Portal Web Interactivo con Microservicios para la Atención al Ciudadano en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1172	recursos	331	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1173	recursos	332	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1174	recursos	333	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1175	recursos	334	DELETE	\N	\N	{"titulo": "Aplicación Móvil para el Control y Seguimiento de Pacientes Crónicos en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1176	recursos	335	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1177	recursos	336	DELETE	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1178	recursos	337	DELETE	\N	\N	{"titulo": "Aplicación Móvil para la Articulación de Compras Comunitarias y Emprendimientos en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1179	recursos	338	DELETE	\N	\N	{"titulo": "Plataforma Colaborativa Multiplataforma para la Gestión de Proyectos Comunitarios en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1180	recursos	339	DELETE	\N	\N	{"titulo": "Sistema Web con API RESTful para la Interoperabilidad de Procesos Operativos en la Dirección de Desarrollo Social de la Alcaldía", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1181	recursos	340	DELETE	\N	\N	{"titulo": "Aplicación Web Progresiva (PWA) para el Monitoreo y Solicitud de Servicios Públicos en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1182	recursos	341	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1183	recursos	342	DELETE	\N	\N	{"titulo": "Aplicación Móvil con Geolocalización para el Registro de Incidencias Urbanas en la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1184	recursos	343	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1327	recursos	295	INSERT	\N	\N	\N	{"titulo": "Sitio Web en la Comercializadora Francoplast C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1185	recursos	344	DELETE	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1186	recursos	345	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1187	recursos	346	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1188	recursos	347	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1189	recursos	348	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1190	recursos	349	DELETE	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para la Comuna Socialista 'El Renacer del Pueblo', Sector Centro", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1191	recursos	350	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1192	recursos	351	DELETE	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1193	recursos	352	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1194	recursos	353	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1195	recursos	354	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1196	recursos	355	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1197	recursos	356	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1198	recursos	357	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1199	recursos	358	DELETE	\N	\N	{"titulo": "Despliegue de Red Inalámbrica Comunitaria de Banda Ancha para el Acceso a Internet en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1200	recursos	359	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1201	recursos	360	DELETE	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1202	recursos	361	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1203	recursos	362	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro Comunitario de Protección Integral 'La Pastora'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1204	recursos	363	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Ambulatorio Urbano Tipo II 'La Trinidad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1205	recursos	364	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en la Empresa de Propiedad Social Directa 'Textiles del Alba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1206	recursos	365	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Comité de Salud del Sector 'El Tamarindo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1207	recursos	366	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Instituto de Educación Especial 'Luz del Futuro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1208	recursos	367	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1209	recursos	368	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Cooperativa Agropecuaria 'Tierra y Fruto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1210	recursos	369	DELETE	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1211	recursos	370	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1212	recursos	371	DELETE	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en el Hospital Materno Infantil 'Esperanza de Vida'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1213	recursos	372	DELETE	\N	\N	{"titulo": "Diseño e Implementación de Infraestructura de Red LAN Segura para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1214	recursos	373	DELETE	\N	\N	{"titulo": "Implementación de Telefonía IP (VoIP) Corporativa con Asterisk para Optimizar la Comunicación en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1215	recursos	374	DELETE	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1216	recursos	375	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1217	recursos	376	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1218	recursos	377	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1219	recursos	378	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en la Unidad de Producción Social 'Semillas de Libertad'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1220	recursos	379	DELETE	\N	\N	{"titulo": "Sistema de Monitoreo de Tráfico y Seguridad de Red Basado en Software Libre en el Liceo Nacional 'Alberto Arvelo Torrealba'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1221	recursos	380	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1222	recursos	381	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1223	recursos	382	DELETE	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en la Unidad Educativa 'Monseñor Castro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1224	recursos	383	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Escuela Básica Bolivariana 'Andrés Bello'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1225	recursos	384	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Núcleo Universitario UPT 'Barquisimeto'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1226	recursos	385	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1227	recursos	386	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1228	recursos	387	DELETE	\N	\N	{"titulo": "Plan de Contingencia y Seguridad de la Información en Infraestructuras de Redes de el Centro de Acopio y Distribución 'Pueblo Soberano'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1229	recursos	388	DELETE	\N	\N	{"titulo": "Servidor de Seguridad Perimetral con Firewall y Detección de Intrusos (IDS) en el Centro de Diagnóstico Integral (CDI) 'Simón Bolívar'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1230	recursos	389	DELETE	\N	\N	{"titulo": "Configuración de Servidores de Red DNS, DHCP y Active Directory para la Centralización de la Casa de Alimentación Comunal 'Mamá Rosa'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1231	recursos	390	DELETE	\N	\N	{"titulo": "Estudio de Cobertura y Optimización de Radioenlace Inalámbrico Punto a Punto para el Consejo Comunal 'Las Lomas de San Pedro'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1232	recursos	391	DELETE	\N	\N	{"titulo": "Segmentación de Red Mediante VLANs y Enrutamiento Estático para el Aislamiento de Tráfico en el Consejo Comunal 'Vencedores de Carabobo'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1233	recursos	392	DELETE	\N	\N	{"titulo": "Plataforma de Gestión y Diagnóstico Remoto de Dispositivos de Red en la Estación Experimental Agroalimentaria 'Valle Verde'", "id_tipo_recurso": 1}	\N	2026-10-20 23:06:03.658644
1234	recursos	3	INSERT	\N	\N	\N	{"titulo": "Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos", "id_tipo_recurso": 2}	2026-10-20 23:06:03.658644
1235	recursos	4	INSERT	\N	\N	\N	{"titulo": "Impacto del Cambio Climático en Trujillo - Parte 8", "id_tipo_recurso": 2}	2026-10-20 23:06:03.658644
1236	recursos	5	INSERT	\N	\N	\N	{"titulo": "Simulación de Cargas Estáticas en Puentes - Parte 7", "id_tipo_recurso": 2}	2026-10-20 23:06:03.658644
1237	recursos	9	INSERT	\N	\N	\N	{"titulo": "Bioinformática y Análisis de ADN - Parte 5", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1238	recursos	10	INSERT	\N	\N	\N	{"titulo": "Inteligencia Artificial en Diagnóstico Médico - Parte 6", "id_tipo_recurso": 2}	2026-10-20 23:06:03.658644
1239	recursos	11	INSERT	\N	\N	\N	{"titulo": "Robótica Educativa para Escuelas - Parte 5", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1240	recursos	12	INSERT	\N	\N	\N	{"titulo": "Software Libre para Bibliotecas - Parte 1", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1241	recursos	13	INSERT	\N	\N	\N	{"titulo": "E-Learning para Zonas Desfavorecidas - Parte 1", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1242	recursos	14	INSERT	\N	\N	\N	{"titulo": "Telecomunicaciones de Fibra Óptica Rural - Parte 1", "id_tipo_recurso": 2}	2026-10-20 23:06:03.658644
1243	recursos	16	INSERT	\N	\N	\N	{"titulo": "Criptografía Cuántica Post-RSA - Parte 7", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1244	recursos	20	INSERT	\N	\N	\N	{"titulo": "Inteligencia Artificial en Diagnóstico Médico - Parte 1", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1245	recursos	122	INSERT	\N	\N	\N	{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1246	recursos	121	INSERT	\N	\N	\N	{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1247	recursos	120	INSERT	\N	\N	\N	{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1248	recursos	119	INSERT	\N	\N	\N	{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1249	recursos	118	INSERT	\N	\N	\N	{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1250	recursos	143	INSERT	\N	\N	\N	{"titulo": "Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1251	recursos	146	INSERT	\N	\N	\N	{"titulo": "Modelamiento de confort adaptativo para un trapiche panelero", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1252	recursos	144	INSERT	\N	\N	\N	{"titulo": "Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1253	recursos	150	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1254	recursos	151	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1255	recursos	156	INSERT	\N	\N	\N	{"titulo": "e", "id_tipo_recurso": 3}	2026-10-20 23:06:03.658644
1256	recursos	224	INSERT	\N	\N	\N	{"titulo": "FORTALECIMIENTO DEL APRENDIZAJE DE CASTELLANO Y LITERATURA MEDIANTE UNA PLATAFORMA EDUCATIVA PARA LOS ESTUDIANTES DE PRIMER AÑO DEL LICEO \\"DR. REGULO BURELLI RIVAS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1257	recursos	225	INSERT	\N	\N	\N	{"titulo": "Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1258	recursos	226	INSERT	\N	\N	\N	{"titulo": "GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1259	recursos	227	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1260	recursos	228	INSERT	\N	\N	\N	{"titulo": "PROYECTO T4 Sistema Consejo Municipal", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1261	recursos	229	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1262	recursos	230	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1263	recursos	231	INSERT	\N	\N	\N	{"titulo": "IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1264	recursos	232	INSERT	\N	\N	\N	{"titulo": "OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1265	recursos	233	INSERT	\N	\N	\N	{"titulo": "Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1266	recursos	234	INSERT	\N	\N	\N	{"titulo": "Implementación de una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1267	recursos	235	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1268	recursos	236	INSERT	\N	\N	\N	{"titulo": "Sistema de Información Automatizado para la Gestión Académica del Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1269	recursos	237	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1270	recursos	238	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA OPTIMIZAR LA GESTIÓN DE PRODUCTOS Y SERVICIOS EN SUPER KAUCHOS GILSA, C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1271	recursos	239	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE ADMINISTRACION DE CASOS (SIAC) EN LA PREFECTURA DEL MUNICIPIO ESCUQUE", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1272	recursos	240	INSERT	\N	\N	\N	{"titulo": "SISTEMA INTEGRAL DE CONTROL DE PAGO DE ARANCELES DEL IUNE - VALERA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1273	recursos	241	INSERT	\N	\N	\N	{"titulo": "SISTEMA PARA LA DIGITALIZACION Y GESTION DE SERVICIOS DE EQUIPOS EN TECNOSERVICE DE MIGUEL MORALES F.P", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1274	recursos	242	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1275	recursos	243	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS)", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1276	recursos	244	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA LA COOPERATIVA “LA ONCE CON LA ONCE ”, MUNICIPIO VALERA, ESTADO TRUJILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1277	recursos	245	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE GESTIÓN Y CONTROL DE DATOS EDUCATIVOS DE LA UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1278	recursos	246	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE GESTIÓN ESCOLAR PARA LA ESCUELA “VICENTE DE LA TORRE", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1279	recursos	247	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1280	recursos	248	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN PARA EL REGISTRO Y GESTIÓN LABORAL DEL INDET(SIRGL-INDET)", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1281	recursos	249	INSERT	\N	\N	\N	{"titulo": "Sistema de gestión de inventario Web para INME C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1282	recursos	250	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1283	recursos	251	INSERT	\N	\N	\N	{"titulo": "IMPLEMENTACIÓN DE PORTAL WEB PARA LA EMPRESA PUBLIART", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1284	recursos	252	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE GESTIÓN DIGITAL PARA EL INVENTARIO, TRAZABILIDAD Y MOVIMIENTO DE BIENES EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1285	recursos	253	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1286	recursos	254	INSERT	\N	\N	\N	{"titulo": "Sistema Web de gestión integral del Talento humano para el SAPNNAET", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1287	recursos	255	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMNISTRATIVA DEL PERSONAL ADSCRITO A LA VALFOR S.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1288	recursos	256	INSERT	\N	\N	\N	{"titulo": "Implementación de una aplicación web para la Unidad Educativa Hna. María Sorrosal", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1289	recursos	257	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB PARA LA GESTIÓN DE PROYECTOS DE LOS CONSEJOS COMUNALES EN EL CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1290	recursos	258	INSERT	\N	\N	\N	{"titulo": "Sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1291	recursos	259	INSERT	\N	\N	\N	{"titulo": "Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1292	recursos	260	INSERT	\N	\N	\N	{"titulo": "Sistema web para la farmacia INSUMEDIC C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1293	recursos	261	INSERT	\N	\N	\N	{"titulo": "DESARROLLO DE APLICACIÓN WEB PARA EL LABORATORIO CLÍNICO GENÉSIS C.A., VALERA, ESTADO TRUJILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1294	recursos	262	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA \\"DR. HUMBERTO FERNÁNDEZ MORÁN\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1295	recursos	263	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB PARA LA GESTION ADMINISTRATIVA DE LA PANTERA PC STORE", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1296	recursos	264	INSERT	\N	\N	\N	{"titulo": "MANTENIMIENTO PREVENTIVO Y CORRECTIVO A LAS COMPUTADORAS DEL CBIT EN EL COMPLEJO EDUCATIVO ‘HILARIO PIZANI ANSELMI’", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1297	recursos	265	INSERT	\N	\N	\N	{"titulo": "SOPORTE TECNICO A EQUIPOS Y USUARIOS EN LA ALCALDIA MUNICIPAL RAFAEL RANGEL", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1298	recursos	266	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS Y USUARIOS PARA EL INFOCENTRO GRAN BASE DE MISIONES POR LA PAZ HUGO CHAVEZ FRIAS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1299	recursos	267	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y A USUARIOS EN DUILIO DE JESUS VALERO ROMERO PANADERIA, PASTELERIA Y LUNCHERIA “LA ESTRELLA DE VALERA”. F. P", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1300	recursos	268	INSERT	\N	\N	\N	{"titulo": "PABLO VILORIA\\" - LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A LOS USUARIOS DE EL CIBER CAFÉ PONCHO S.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1301	recursos	269	INSERT	\N	\N	\N	{"titulo": "NUES “DR. PABLO VILORIA” – LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A USUARIOS DE LA UPTTMBI NUES LA BEATRIZ EN EL LABORATORIO 2 DE INFORMÁTICA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1302	recursos	270	INSERT	\N	\N	\N	{"titulo": "SOPORTE TECNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CORPOELEC", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1303	recursos	271	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA NUEVA ELOISA FONSECA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1304	recursos	272	INSERT	\N	\N	\N	{"titulo": "Soporte Técnico a Equipos de Computación y usuarios en la Empresa “IP SISTEMAS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1305	recursos	273	INSERT	\N	\N	\N	{"titulo": "SOPORTE TECNICO A EQUIPOS TECNOLOGICOS Y USUARIOS DEL DESPACHO DE CARGA SUROCCIDENTE", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1306	recursos	274	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A EQUIPOS Y USUARIOS EN LA CLINICA UGA C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1307	recursos	275	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DE LA “ESCUELA TÉCNICA COMERCIAL PEDRO GARCÍA LEAL” DEL MUNICIPIO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1308	recursos	276	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A USUARIOS Y EQUIPOS EN EL MINISTERIO DEL PODER POPULAR PARA EL TRANSPORTE, SEDE VALERA - TRUJILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1309	recursos	278	INSERT	\N	\N	\N	{"titulo": "SOPORTE TÉCNICO A LAS COMPUTADORAS Y CAPACITACIÓN A LOS USUARIOS DEL CBIT DE LA ESCUELA DE EMPRENDIMIENTO VALERA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1310	recursos	279	INSERT	\N	\N	\N	{"titulo": "SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1311	recursos	280	INSERT	\N	\N	\N	{"titulo": "SOFTWARE EDUCATIVO PARA LA ENSEÑANZA DE MATEMATICAS DE PRIMER AÑO EN EL LICEO “HILARIO PIZANI ANSELMI", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1312	recursos	281	INSERT	\N	\N	\N	{"titulo": "SOFTWARE EDUCATIVO PARA MEJORAR LA ENSEÑANZA DE PROGRAMACIÓN Y DISEÑO DE SOFTWARE Y REDES EN LA E.T. \\"PEDRO GARCÍA LEAL\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1313	recursos	277	INSERT	\N	\N	\N	{"titulo": "Soporte Técnico de Equipos y Capacitación a Usuarios del Laboratorio 1 de PNFI de la Universidad Politécnica Territorial ¨Mario Briceño Iragorry¨", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1314	recursos	282	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1315	recursos	283	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB INFORMATIVA PARA EL FORTALECIMIENTO TECNOLÓGICO DEL PARQUE DE RECREACIÓN BOTAICO “LA BEATRIZ", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1316	recursos	284	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA EL COMPLEJO “EDUCATIVO PEDRO PABLO PAREDES", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1317	recursos	285	INSERT	\N	\N	\N	{"titulo": "APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1318	recursos	286	INSERT	\N	\N	\N	{"titulo": "APLICACION WEB PARA LA UNIDAD EDUCATIVA “EDUARDO BLANCO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1319	recursos	287	INSERT	\N	\N	\N	{"titulo": "Página Web en el Grupo Educativo “Eloísa Fonseca", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1320	recursos	288	INSERT	\N	\N	\N	{"titulo": "Página Web Institucional para la Unidad Educativa Fé y Alegría “Gonzalo Senior", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1321	recursos	289	INSERT	\N	\N	\N	{"titulo": "PÁGINA WEB INSTITUCIONAL PARA EL COLEGIO LOS CEDROS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1322	recursos	290	INSERT	\N	\N	\N	{"titulo": "página web municipal con sección al infocentro", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1323	recursos	291	INSERT	\N	\N	\N	{"titulo": "PÁGINA WEB PARA EL CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1324	recursos	292	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1328	recursos	296	INSERT	\N	\N	\N	{"titulo": "MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS\\"", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1329	recursos	297	INSERT	\N	\N	\N	{"titulo": "Optimización y modernización de la red de comunicación de CORPOELEC Sucursal: Despacho de Carga Suroccidente", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1330	recursos	298	INSERT	\N	\N	\N	{"titulo": "Sistema de control de acceso al laboratorio informático de la E.T \\"Pedro García Leal", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1331	recursos	299	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1332	recursos	300	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE INFORMACION PARA LA OPTIMIZACION DEL ACCESO Y CONTROL DE HISTORIAS MÉDICAS EN EL C.C.C. DR. JOSE GREGORIO HERNANDEZ C.A", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1333	recursos	301	INSERT	\N	\N	\N	{"titulo": "SISTEMA PARA CONTROL Y SEGUIMIENTO DE ASISTENCIAS DEL PROFESORADO EN LA ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1334	recursos	302	INSERT	\N	\N	\N	{"titulo": "SISTEMA WEB DE GESTIÓN DE VENTAS PARA INVERSIONES M.Q. DE MIRLA QUINTERO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1335	recursos	303	INSERT	\N	\N	\N	{"titulo": "MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1336	recursos	304	INSERT	\N	\N	\N	{"titulo": "REESTRUCTURACIÓN Y MANTENIMIENTO DE LA RED LAN EN LA DIRECCIÓN GENERAL DE PLANIFICACIÓN Y PRESUPUESTO DE LA GOBERNACIÓN DEL ESTADO TRUJILLO", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1337	recursos	305	INSERT	\N	\N	\N	{"titulo": "SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA", "id_tipo_recurso": 1}	2026-10-20 23:06:03.658644
1338	usuarios	7	UPDATE	7	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-21 16:28:12.083524
1339	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	2026-10-28 12:23:35.176232
1340	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-10-28 12:23:35.176232
1341	usuarios	12	UPDATE	\N	\N	{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}	{"activo": false, "id_rol": 2, "nombre": "[Archivado] ANDRUS"}	2026-10-28 12:23:35.176232
1342	usuarios	15	UPDATE	\N	\N	{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}	{"activo": false, "id_rol": 1, "nombre": "[Archivado] DIOSs"}	2026-10-28 12:23:35.176232
1343	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-10-28 12:23:35.176232
1344	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-10-28 12:23:35.176232
1345	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	2026-10-28 12:23:35.176232
1346	usuarios	23	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "[Archivado] LANDO2"}	{"activo": false, "id_rol": 4, "nombre": "[Archivado] LANDO2"}	2026-10-28 12:23:35.176232
1347	usuarios	22	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "[Archivado] MAMA DE LANDO"}	{"activo": false, "id_rol": 4, "nombre": "[Archivado] MAMA DE LANDO"}	2026-10-28 12:23:35.176232
1348	usuarios	24	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "landorus"}	{"activo": true, "id_rol": 4, "nombre": "landorus"}	2026-10-28 12:23:35.176232
1349	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	2026-10-28 12:23:35.176232
1350	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-10-28 12:23:35.176232
1351	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Wazaaaa"}	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	2026-11-01 22:03:46.862211
1352	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-01 22:18:27.43038
1353	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-01 22:18:27.43038
1354	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-01 22:18:27.43038
1355	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-01 22:18:49.43584
1356	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-01 22:18:49.43584
1357	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-01 22:18:49.43584
1358	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-01 22:19:20.319991
1359	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-01 22:19:20.319991
1360	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-01 22:19:20.319991
1361	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	2026-11-01 22:19:20.325125
1362	usuarios	6	UPDATE	\N	\N	{"activo": false, "id_rol": 4, "nombre": "Piñin Piña"}	{"activo": false, "id_rol": 4, "nombre": "Ing. José Rivero"}	2026-11-01 22:19:20.325633
1363	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	2026-11-01 22:19:20.326014
1364	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	2026-11-01 22:19:20.326378
1365	usuarios	24	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "landorus"}	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	2026-11-01 22:19:20.326701
1366	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	2026-11-02 12:09:05.267906
1367	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	2026-11-02 12:09:05.288513
1368	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	2026-11-02 12:09:05.289311
1369	usuarios	24	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	2026-11-02 12:09:05.28989
1370	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-02 12:09:05.290507
1371	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-02 12:09:05.290507
1372	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-02 12:09:05.290507
1373	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	2026-11-02 12:09:19.533171
1374	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	2026-11-02 12:09:19.536938
1375	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	2026-11-02 12:09:19.537298
1376	usuarios	24	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	2026-11-02 12:09:19.537629
1377	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-02 12:09:19.537944
1378	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-02 12:09:19.537944
1379	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-02 12:09:19.537944
1380	usuarios	7	UPDATE	7	\N	{"activo": true, "id_rol": 1, "nombre": "MSc. Carlos Mendoza"}	{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzalez"}	2026-11-02 12:44:29.244873
1381	usuarios	1	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	{"activo": true, "id_rol": 1, "nombre": "Adrus"}	2026-11-02 12:50:58.307898
1382	usuarios	2	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "lando"}	{"activo": true, "id_rol": 2, "nombre": "lando"}	2026-11-02 12:50:58.313984
1383	usuarios	7	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel Gonzalez"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-11-02 12:50:58.314775
1384	usuarios	10	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	{"activo": true, "id_rol": 4, "nombre": "Dr. Ricardo Dos Santos"}	2026-11-02 12:50:58.315787
1385	usuarios	17	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	{"activo": true, "id_rol": 2, "nombre": "adrusss"}	2026-11-02 12:50:58.316532
1386	usuarios	19	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "Dra. María Gómez"}	{"activo": true, "id_rol": 1, "nombre": "Juan Salcedo"}	2026-11-02 12:50:58.316871
1387	usuarios	24	UPDATE	\N	\N	{"activo": true, "id_rol": 4, "nombre": "MSc. Luis Hernández"}	{"activo": true, "id_rol": 4, "nombre": "landorus"}	2026-11-02 12:50:58.317181
1388	usuarios	7	UPDATE	7	\N	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	{"activo": true, "id_rol": 1, "nombre": "Miguel González"}	2026-11-02 13:01:12.758836
\.


--
-- TOC entry 5762 (class 0 OID 51448)
-- Dependencies: 225
-- Data for Name: autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.autores (id, nombre_completo, cedula) FROM stdin;
1	Alexandro Rosales	V-30737939
2	Kleyderman Caceres	V-30671984
3	Albert Hernández	V-30302607
4	Angel Sandrea	V-30600555
5	Edixon Angel	V-26235671
6	Franyer Bencomo	V-31521223
7	Raybel Segovia	V-29585227
8	Drover Briceño	V-30536269
9	Delgado Jesús	V-30600922
10	González Eudis	V-30600439
11	Aldana Guillermo	V-30379719
12	Morillo Alonso	V-29874501
13	Zambrano Michel	V-31029728
14	Paredes Viloria Leopoldo José	V-26962142
15	Ramírez Estrada Yorleidi Carmen	V-17604975
16	Barrios Luisanny	V-30600603
17	Bastidas Harold	V-28096941
18	Machado Norman	V-30302938
19	Suárez Luis	V-30895831
20	Angerly A Gudiño W	V-30190127
21	Andrea A Graterol A	V-29652280
22	Abrahan A Viloria D	V-30600727
23	Eyber E Chacon G	V-29932639
24	Darwin J Viera M	V-28485043
25	Higuera Jennifer	V-21364744
26	Moreno Carlos	V-28079273
27	Perdomo Jeslibeth	V-30048325
28	Ramírez Andrés	V-27070760
29	Favian Herrera	V-30600230
30	Yohelis Colmenares	V-29539073
31	Luciana Graterol	V-30438421
32	Zoraly Perdomo	V-30475316
33	Araujo Rivas Isamar Andreina	V-31029609
34	Collantes Peña José Manuel	V-31602776
35	León Custode María Fernanda	V-31094982
36	Ocanto Morales ángel David	V-31239885
37	Sáez Albornoz Eduard Alexander	V-30256767
38	Santiago Celis	V-30600647
39	José Quintero	V-31095296
40	Aarón Delgado	V-31520627
41	Hebert Morillo	V-16634538
42	Wilfredo González	V-30475446
43	Enmanuel Rodríguez	V-19898759
44	Leandro Abreu	V-30867672
45	Quevedo María	V-30602507
46	Ruiz Abraham	V-30259034
47	Torres Diego	V-30671160
48	Torres Edwar	V-30333971
49	Urbina Omar	V-30190456
50	Cristhian Hernández	V-31167852
51	Daniel Paredes	V-31239028
52	Harvely Valecillos	V-31239111
53	José Santiago	V-31029806
54	Aldana Yaneth	V-11614297
55	Karla Villegas	V-30673506
56	Yennifer Paredes	V-31979510
57	Eulises Briceño	V-29933293
58	Samuel Hernández	V-31168152
59	Andy González	V-31320644
60	Christian Marquez	V-31029322
61	Franyer Montilla	V-31259634
62	Ismaireth Rivera	V-30600496
63	José Borrero	V-30214170
64	Carlos Vallejo	V-30975678
65	José Rojo	V-30536364
66	Andrus Ramírez	V-30469331
67	Miguel González	V-32621284
68	José Pérez	V-31177398
69	Yohan Estrada	V-31320604
70	Fabian Matheus	V-31979751
71	Jerferson Mendoza	V-31814137
72	Jhoandry Hernández	V-31795273
73	Jeremy Aldana	V-31814474
74	Diego Salcedo	V-30976478
75	Contreras Jaiver	V-30880014
76	Hernández Rogers	V-31320587
77	Mejia Dayalex	V-31320075
78	Socarras Kevin	V-31095383
79	Tovar Silvana	V-29874386
80	Briceño Brandon	V-29814531
81	Nava Frank	V-30600820
82	Oviedo Yean Carlos	V-30737692
83	Sue Maykel Leal	V-29933387
84	Julio César García Alcalá	V-31898495
85	Andrés Efraín Matheus Rivas	V-30867328
86	álvaro Josué Leal Mejía	V-31239667
87	Johana Karina Valera Briceño	V-30189791
88	César Delgado	V-31426071
89	Brandon Aguilar	V-30976197
90	Amílcar Torres	V-31168311
91	González Victoria	V-30931145
92	Valero Alejandro	V-29814164
93	Linares Yerfenson	V-14800106
94	Rangel Jully	V-23252159
95	Torres V Alfredo J	V-27152700
96	Torres M Gabriel A	V-30047646
97	Arandia S Samuel L	V-29739914
98	Linares T Leonardo D	V-30931231
99	Rojo P Derek D	V-26558826
100	Chávez Rivero José Gabriel	V-30047608
101	Cruz Cruz Anthony Manojlovich	V-30235965
102	Ramírez Maria	V-31168449
103	Molina Luis	V-32886322
104	Delgado Juan	V-31897897
105	José Cano	V-28323346
106	Montilla Olmos	V-30866991
107	Piña Materán	V-31413623
108	Salcedo Angel	V-31008131
109	Orlando Ortega	V-27889926
110	Carrillo Alex	V-29932622
111	Delgado Faniuska	V-29994227
112	García Estefani	V-30302701
113	Alburguez Shirly	V-30600111
114	Conteras Jhoan	V-30380014
115	Valera Aarón	V-30867453
116	Pineda Andrés	V-30976384
117	Gil Salomón	V-29539917
118	Jesús Gutiérrez	V-28206934
119	Angel Mascagnini	V-31368896
120	Cárdenas Valera Alí Manuel	V-3073775
121	Ruiz Pulido Jhon Maickol	V-3073817
122	Araujo Maria	V-31979412
123	Briceño Jean	V-30302949
124	Carrasquero Hender	V-30738064
125	Montilla Angeli	V-31383853
126	Rivero Luis	V-30236073
127	José Vazquez	V-31732185
128	Douglas González	V-12796229
129	Jorge Carrillo	V-26123371
130	Luis Castellanos	V-30558543
131	Alejandro Gil	V-30302249
132	Andrés González	V-30880936
133	Julio Granadino	V-30475679
134	Daniel Villarreal	V-30600936
135	María Pacheco	V-15752623
136	Roberto Saavedra	V-30671594
137	Jeremías Chiquito	V-31732427
138	Adrián Briceño	V-32282913
139	Esteban Duarte	V-32374189
140	Alan Peña	V-32000179
141	José álzate	V-33382712
142	Gregory Rivero	V-33598166
143	Gabriel Morillo	V-31095500
144	Moreno Leiximar	V-32159124
145	Rivas Freyber	V-32461998
146	Torres Eimy	V-29585859
147	Hernández Junaiver	V-30302583
148	Matos Wilian	V-31980148
149	Rincón Junior	V-26962838
150	Vielma Fabiola	V-30437771
151	Cabrita Joel	V-27896010
152	Mejía José	V-32374692
153	Viloria Santiago	V-32234255
154	Araujo Andrés	V-32360642
155	Guzmán Derick	V-31028932
156	Rivero Oriana	V-32373897
157	Terán Orlienderson	V-32007187
158	Escobar Morales Gelany Paola	V-33573889
159	Ruza Ferrebus Jhon David	V-32282366
160	Moisés Carrillo	V-32282637
161	Alexander Villegas	V-32620849
162	Jared Albornoz	V-31732176
163	Ezequiel Suárez	V-32084207
164	Andrés Riveros	V-30475965
165	Sebastián Saporito	V-33311397
166	Derrick Rojo	V-28445791
167	Edgardo Molina	V-29994895
168	María Olivar	V-28542385
169	Adriana Sánchez	V-31320176
170	Mariana Pérez	V-32550856
171	Adrian Jose Diaz Sifuentes	V-32461812
172	Páez Jamko	V-13523529
173	Rangel Eduardo	V-32159229
174	Silva Miguel	V-26155069
175	Venegas Francisco	V-11322039
176	Andrade R Jorge A	V-31515045
177	Cadenas V Diego A	V-33639626
178	Quiroga B Sarai	V-32283208
179	Terán S Pedro A	V-32116439
180	Jorge Alarcón	V-30867685
181	Miguel Barrios	V-30866953
182	Ricardo Fontana	V-31666059
183	Sebastián Uribe	V-33174441
184	Rebeca Olivar	V-27778997
185	Karen Albarrán	V-32159241
186	Ariana Ruza	V-33290662
187	Balza Roger	V-31728855
188	Rodríguez Andreina	V-32000225
189	Sulbaran Olay Torres Marco	V-31457137
190	Sulbaran Olay	V-31732771
191	Carliany Baptista	V-32971126
192	Eilim Monsalve	V-32907046
193	Marianly Villegas	V-31898056
194	Baptista Luis	V-21364647
195	Guerrero Andry	V-28096379
196	Vásquez Ronald	V-28190249
197	Tribiño Mendéz Celena Lisseth	V-31188138
198	Barrios Samuel	V-29814838
199	Garzo Yexon	V-28542191
200	Ontiveros Emmanuel	V-30495057
201	Rendon Jonathan	V-29814614
202	Vale Jesús	V-30670829
203	Amadeo Dacunto	V-30717343
204	Junior Dávila	V-31168523
205	Jesus Salcedo	V-31319862
206	Martínez Edduin	V-31825153
207	Molina Adrián	V-31167961
208	Pérez Juan	V-31814987
209	Pulido Gabriela	V-31665705
210	Marian Andreina Hernández Terán	V-31168192
211	Valentina Amelie Gutiérrez Terán	V-31732186
212	Juan Pablo Barroeta González	V-31319720
213	Bastidas Parra José Manuel	V-31413711
214	Delgado Infante Mario José	V-32461801
215	Mejia Ruiz Javier Jesus	V-31095153
216	Ramos Valera Christian Andrés	V-28096179
217	Viloria Rojas Fabian Andrés	V-31732673
218	Valentina Sarais Castellanos Leal	V-31732652
219	Luis Alejandro Fernández Briceño	V-32188577
220	Sara Zoar Peña Itriago	V-31665730
221	Yorlendis Annyely Peña Rodríguez	V-31464347
222	Mariangy Alexandra Durán Jerez	V-31413049
223	Franscheska Fabiana Pabón Contreras	V-32550534
224	Camila De Los Angeles Sulbaran Blanco	V-31814330
225	Ramón Enrique Peña Pérez	V-29874509
226	Noreymig Rubio	V-32083701
227	Mariangelis Maldonado	V-31980173
228	Mariam Carreño	V-32084200
229	Kenmary Blanco	V-31732001
230	Yeisonk Pimentel	V-26967781
231	Mariangel Andreina Sánchez Trejo	V-31524534
232	Yacnely Carolina Rondón Moreno	V-32462345
233	Evangelys Valentina Rivas Valecillos	V-31602760
234	Diego Andres Villegas Rivero	V-30976505
235	Albornoz Olivares Abdon Leonardo	V-31732548
236	Atencio Marín Shergio Gabriel	V-31728829
237	Cadenas Briceño Salvador José	V-32297314
238	Moreno Araujo Juan Pablo Sebastian	V-31521358
239	Camacho Gilneiber	V-28445972
240	Nava Yeider	V-30536270
241	Reyes Anderlyn	V-29814904
242	Rondón Yosimar	V-25832509
243	Blanco Jeriko	V-31731931
244	Morales Reinaldo	V-31958901
245	Romero Valeria	V-31666110
246	Albany Layret Camargo Guillen	V-31814437
247	Stefany Jhoulet Castrillón Gutierrez	V-31666050
248	Yilfreddy Gabriel Duarte Valecillos	V-31735021
249	Oscar Manuel Villareal Valera	V-30600212
250	Analy De Los Angeles Hernández Cortéz	V-30601065
251	Anyela Alejandra Briceño Guerra	V-31413272
252	Valeria Alejandra Sulbarán Delgado	V-30976285
253	Diosmary Nazareth Duran Rivas	V-31854894
254	Diego Arellano	V-31029125
255	Maria Linares	V-31898407
256	Silvano Cadenas	V-32297313
257	Adrián Méndez	V-28190041
258	Abraham Graterol	V-31167863
259	Isaac Figuera	V-31239364
260	Wrayan Linares	V-31413308
261	Alvarado Miranda	V-31732595
262	Briceño Rafael	V-31732166
263	Cañizalez Yonathan	V-31520663
264	La Cruz Ismael	V-31234242
265	Araujo Arianny	V-30116864
266	Cardozo Eduard	V-26488002
267	Guillén Jesús	V-31095010
268	Valero Estefania	V-30116979
269	César Herrera	V-27896653
270	Pedro Salvucci	V-31958842
271	Jesús Becerra	V-30600950
272	José Giro	V-31665947
273	Jorge Moreno	V-30808439
274	Gonzalez Esnaider	V-31520502
275	Montilla Pedro	V-30738272
276	Moreno Israel	V-32159296
277	Barrios Abreu Enio Enrique	V-16846566
278	Dávila Araujo José Gonzalo	V-12041146
279	Blanco Victor	V-31898245
280	Ruiz David	V-31898257
\.


--
-- TOC entry 5764 (class 0 OID 51455)
-- Dependencies: 227
-- Data for Name: carreras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carreras (id, nombre, descripcion) FROM stdin;
1	PNF en Informática	Ingeniería y TSU en Informática
2	PNF en Electricidad	Ingeniería y TSU en Electricidad
3	PNF en Administración	Licenciatura y TSU en Administración
4	PNF en Agroalimentación	Ingeniería y TSU Agroalimentario
5	PNF en Construcción Civil	Ingeniería y TSU en Construcción Civil
\.


--
-- TOC entry 5766 (class 0 OID 51463)
-- Dependencies: 229
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nombre) FROM stdin;
1	Tecnología
3	Ingeniería
4	Sociales
5	Innovación
6	Ciencias Sociales
7	Salud y Biociencias
11	Salud
13	Matemáticas
14	Literatura
15	Psicología
16	Economía
17	Contaduría
18	Ingeniería Civil
10	Admin
\.


--
-- TOC entry 5768 (class 0 OID 51469)
-- Dependencies: 231
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cursos (id, id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion, fecha_creacion, fecha_actualizacion, slug, url_moodle, modalidad, nivel, duracion, cupo_maximo, fecha_inicio, fecha_fin, url_video_preview, estado_inscripcion, url_formulario_google) FROM stdin;
1	10	Introducción a la Metodología de la Investigación Científica	Curso teórico-práctico orientado a la formulación de proyectos socio-tecnológicos e investigaciones aplicadas, planteamiento del problema, objetivos e instrumentos metodológicos.	public/uploads/cursos/curso_1_1774383287.webp	publicado	70.00	2026-11-02 12:09:05.301457	2026-11-02 12:09:05.301457	introduccion-metodologia-investigacion	https://iutetvirtual.org.ve/course/view.php?id=101	Virtual	Básico	40 horas	35	2026-11-10	2026-12-15		Abierta	https://docs.google.com/forms/d/e/1FAIpQLSean-NUF1PEU6ci8MiNIQp13OewWCofp7O7V3MV8k_CA1CHNQ/viewform?usp=pp_url&curso_id=1&entry.2098787602=Virtual&entry.69221431=Inscripci%C3%B3n%20al%20Curso%3A%20Introducci%C3%B3n%20a%20la%20Metodolog%C3%ADa%20de%20la%20Investigaci%C3%B3n%20Cient%C3%ADfica
2	10	Fundamentos de Inteligencia Artificial y Ciencia de Datos	Formación intensiva en modelos de aprendizaje automático, procesamiento de datos científicos con Python y aplicaciones de IA en líneas de investigación universitarias.	public/uploads/cursos/curso_2_1774383364.webp	publicado	75.00	2026-11-02 12:09:05.303106	2026-11-02 12:09:05.303106	fundamentos-inteligencia-artificial	https://iutetvirtual.org.ve/course/view.php?id=102	Híbrido	Intermedio	48 horas	30	2026-11-15	2026-12-20		Abierta	https://docs.google.com/forms/d/e/1FAIpQLSean-NUF1PEU6ci8MiNIQp13OewWCofp7O7V3MV8k_CA1CHNQ/viewform?usp=pp_url&curso_id=2&entry.2098787602=Mixto&entry.69221431=Inscripci%C3%B3n%20al%20Curso%3A%20Fundamentos%20de%20Inteligencia%20Artificial%20y%20Ciencia%20de%20Datos
3	10	Introducción a la Metodología de la Investigación Científica — CIIDI UPTTMBI	Taller especializado en estructuración de artículos científicos arbitrados, citación académica normalizada, uso de gestores bibliográficos e identidad ORCID.	public/uploads/cursos/curso_3_1774383539.webp	publicado	70.00	2026-11-02 12:09:05.303352	2026-11-02 13:28:07.395169	introducci-n-a-la-metodolog-a-de-la-investigaci-n-cient-fica-ciidi-upttmbi	https://iutetvirtual.org.ve/course/view.php?id=103	Virtual	Básico	32 horas	40	2026-11-18	2026-12-18		Abierta	https://docs.google.com/forms/d/e/1FAIpQLSfeqIlmQW8RXkeq0S4IHGAYD5KRGG367gQdVnvwPEMOvByeZA/viewform?usp=publish-editor
\.


--
-- TOC entry 5847 (class 0 OID 60541)
-- Dependencies: 310
-- Data for Name: cursos_dimensiones; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.cursos_dimensiones (id_curso, id_dimension, created_at) FROM stdin;
\.


--
-- TOC entry 5846 (class 0 OID 60521)
-- Dependencies: 309
-- Data for Name: cursos_lineas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.cursos_lineas (id_curso, id_linea, created_at) FROM stdin;
1	7	2026-11-02 12:09:19.549203
2	7	2026-11-02 12:09:19.550231
2	8	2026-11-02 12:09:19.550536
3	9	2026-11-02 13:28:07.396039
\.


--
-- TOC entry 5843 (class 0 OID 60469)
-- Dependencies: 306
-- Data for Name: cursos_preinscripciones; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.cursos_preinscripciones (id, id_curso, nombre_completo, correo_electronico, telefono, horario_deseado, mensaje, fecha_preinscripcion) FROM stdin;
\.


--
-- TOC entry 5770 (class 0 OID 51487)
-- Dependencies: 233
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_articulos (id_recurso, id_editorial, volumen, numero, issn, created_at, imagen_portada, resumen, activo, id_categoria) FROM stdin;
120	\N	93	241	0012-7353	2026-07-09 20:16:51.643727	art_1783642611_6a5039f396bec.png	La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada	t	\N
118	5	87	213	0012-7353	2026-07-09 15:19:57.897052	https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png	Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato	t	\N
121	8	93	241	0012-7353	2026-07-09 20:19:28.855723	art_1783642768_6a503a90ca313.png	La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.	t	\N
122	8	93	241	0012-7353	2026-07-09 20:08:48.117741	art_1783642127_6a50380feed3b.png	La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.	t	\N
119	8	93	241	0012-7353	2026-07-09 20:13:49.50881	art_1783642429_6a50393d74626.png	Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos	t	\N
143	7	93	242	0012-7353	2026-09-07 15:40:32.098085	https://revistas.unal.edu.co/public/journals/21/submission_124890_112809_coverImage_es_ES.png	En una línea de transmisión de alta tensión de circuito único de 400 kV, la proximidad de los conductores de fase induce una corriente circulante en el conductor de tierra. Esta corriente forma un circuito cerrado a través del sistema de puesta a tierra de la base de la torre, que proporciona su ruta de retorno [1]. En este estudio, se modela y analiza la corriente circulante inducida. El modelado se realizó en el entorno MATLAB.Los resultados indican que la corriente circulante y la tensión inducida en el conductor de tierra presentan una relación aproximadamente lineal con las corrientes de los conductores de fase, y sus magnitudes se ven influenciadas por la resistencia de puesta a tierra de la torre R_g y la resistividad del suelo ρ. Además, las pérdidas de potencia en el conductor de tierra alcanzan niveles significativos en condiciones de alta corriente. Estos hallazgos resaltan la importancia de un diseño óptimo del sistema de puesta a tierra, una selección adecuada de las características del conductor de tierra y la implementación de métodos para reducir las corrientes circulantes con el fin de mejorar el rendimiento y reducir las pérdidas en las líneas de transmisión de alta tensión.	t	\N
146	\N	91	232	0012-735	2026-09-09 00:51:39.29162	https://revistas.unal.edu.co/public/journals/21/submission_112625_95079_coverImage_es_ES.png	La producción de azúcar de caña no centrifugada, en Colombia se realiza en instalaciones de poscosecha que generan alta cantidad de calor y vapor, producto de la evaporación de los jugos de caña del proceso. Este estudio tuvo como objetivo mejorar las condiciones de confort de una instalación de este tipo en el municipio de Pacho, Cundinamarca, Colombia, a través de simulación bioclimática, donde se modificó el cerramiento en las paredes y en la ventana cenital. Se evalúo el confort térmico adaptativo, donde el mejor comportamiento bioclimático se  presentó  en  las  configuraciones  con  perímetro  abierto  y  ventana  cenital,  esto  debido  a  que  una  mayor  área  de  ventilación  y  efecto chimenea optimizan  la  transferencia  de  calor  y  masa;  así  mismo,  se  observó  que  hay  un  comportamiento  generalizado  de  incomodidad  térmica para los trabajadores en la zona térmica hornilla, debido a las altas emisiones de calor y vapor en esta zona	t	\N
144	8	93	242	0012-7353	2026-09-09 00:24:54.886595	https://revistas.unal.edu.co/public/journals/21/submission_124428_112347_coverImage_es_ES.png	Esta investigación desarrolló y evaluó un modelo de aprendizaje automático para optimizar la contratación de profesionales de ingeniería en  una universidad pública, reduciendo el tiempo de evaluación, los errores y la subjetividad en el análisis de currículums. Se empleó un enfoque cuantitativo, aplicado y cuasiexperimental, utilizando procesamiento de lenguaje natural (TF-IDF), clasificación KNN bajo el esquema One vs-Rest y tres conjuntos de datos de 10, 20 y 30 CV. La información fue procesada en Google Colab mediante etapas de limpieza, vectorización, entrenamiento y evaluación. El modelo alcanzó una precisión del 82 % en la clasificación de candidatos, priorizando de manera consistente a los postulantes según su grado académico y experiencia profesional. Además, redujo el tiempo promedio de evaluación de 15 a 2,5 minutos por CV y disminuyó la tasa de error a menos del 2 %, demostrando ser una herramienta eficiente, objetiva y escalable.	t	\N
150	7	e	e	e	2026-09-11 11:59:24.004409	https://i.pinimg.com/736x/34/63/e7/3463e729b17ec40b1c60c25e1d86af52.jpg	e	t	\N
151	8	e	e	e	2026-09-11 15:27:57.58139	default_article.jpg	e	t	\N
156	8	e	e	e	2026-09-23 12:09:57.549672	default_article.jpg	e	t	\N
\.


--
-- TOC entry 5771 (class 0 OID 51496)
-- Dependencies: 234
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_investigaciones (id_recurso, planteamiento_problema, objetivo_general, id_investigacion_ofertada, created_at) FROM stdin;
\.


--
-- TOC entry 5825 (class 0 OID 52116)
-- Dependencies: 288
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, resumen, id_carrera, comunidad_beneficiada, palabras_clave, created_at, id_investigacion_padre, url_repositorio, obj_general, activo, vector_semantico, id_trayecto, vistas) FROM stdin;
226	2025-11-01	Pregrado	El desarrollo de este Proyecto abarcó un esfuerzo colaborativo por parte del equipo de desarrollo y el equipo administrativo de la universidad. Mediante la investigación y la lógica de programación se obtuvieron las respuestas a estas incógnitas, basado en esto, se generó la idea de dos sistemas que pudieran comunicarse entre sí mediante formatos originales para el manejo de datos. Primeramente, para manejo bruto de datos y procesos de depuración robusto se usó Python, mediante funciones y algoritmos inteligentes se pudo generar un listado de personas aprobadas y aptas para los certificados, purgando listas de más de 20 mil estudiantes en menos de 1 minuto. Así mismo, se diseñaron estrategias de software para generar un sistema capaz de manejar diferentes accesos, y con esto, diferentes funciones. A partir de esto, se desarrolló el sistema de gestión que va dirigido al servidor de la universidad, dicho sistema carga los datos y mediante funciones JavaScript robustas es capaz de generar certificados en formato .pptx masivamente, de 50 en 50, o de 100 en 100 perfectamente. Una vez culminados los sistemas, se han solucionado las problemáticas correspondientes al área de gestión y entrega de certificados que impulsaron la investigación, desarrollo y creación de dicho proyecto	1	PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ	Sistema, Gestor, Certificados, Pensum, Validación, Datos, Calificaciones, Administrativo	2026-10-19 22:36:58.090301	\N	\N	Desarrollar y proporcionar un Sistema de Gestión Administrativa para las Certificaciones del PNFI.	t	\N	4	0
227	2025-11-01	Pregrado	El presente proyecto tiene como finalidad el diseño e implementación de un Sistema de Información para la Gestión Administrativa de Gominova, una organización dedicada a la repostería, comercialización de detalles, venta de implementos especializados y servicios de papelería. Debido a la diversidad de sus actividades y productos, la organización enfrenta la necesidad de mejorar la gestión de procesos relacionados con el inventario, las ventas y el manejo de la información administrativa. Ante esta situación, se propone el desarrollo de una solución tecnológica que permita optimizar y organizar los procesos internos, reducir la dependencia de procedimientos manuales y facilitar el acceso a información precisa y oportuna. El sistema busca contribuir a una gestión más eficiente, proporcionando herramientas que apoyen el control de las operaciones y la toma de decisiones. Con la implementación de esta propuesta se pretende fortalecer la gestión administrativa de Gominova, mejorar la eficiencia de sus procesos y facilitar su adaptación a las actuales exigencias tecnológicas y comerciales, manteniendo la calidad del servicio y la atención cercana a sus clientes	1	Gominova	Sistema de Información, gestión administrativa, inventario, ventas, TIC	2026-10-19 22:37:05.825892	\N	\N	Implementar un sistema de información para la gestión administrativa de la empresa Gominova. Para alcanzar este objetivo que está estructurado en diversas fases lógicas, los objetivos intermedios son el siguiente paso para alcanzar nuestro propósito.	t	\N	4	0
228	2025-06-01	Pregrado	El proyecto consiste en el diseño, desarrollo e implementación de un Sistema de Gestión Informativa y Administrativa para el Concejo Municipal de Valera, estado Trujillo, con el objetivo primordial de modernizar la estructura comunicacional y administrativa de la institución mediante la sustitución de procesos analógicos por un ecosistema digital robusto basado en software libre y en estricto cumplimiento con la Ley de Infogobierno, centralizando la gestión de documentos oficiales, gacetas, noticias y atención al ciudadano para permitir una interacción fluida entre el ente legislativo municipal y la comunidad a través de metodologías de Investigación-Acción Participativa y tecnologías como PHP, MySQL, HTML y JavaScript que garantizan la soberanía tecnológica, la transparencia administrativa y la preservación digital de la memoria institucional del municipio, integrando de manera cohesiva sus componentes operativos que incluyen el Módulo de Inicio, Módulo de Noticias, Módulo de Gacetas Municipales, Módulo de Participación Ciudadana, Módulo de Gestión de Usuarios, Módulo Administrativo (Dashboard), Módulo de Seguridad y Módulo de Reportes para optimizar los tiempos de respuesta y la eficiencia en el manejo de la información pública	1	Concejo Municipal de Valera	Concejo Municipal de Valera, Participación Ciudadana	2026-10-19 22:37:13.034497	\N	\N	Desarrollar un Sistema de Gestión Informativa y Administrativa bajo estándares de Software Libre para el Concejo Municipal de Valera, estado Trujillo, con el fin de optimizar el resguardo de la memoria legislativa y fortalecer los mecanismos de participación ciudadana a través de un entorno web seguro y eficiente.	t	\N	4	0
230	2026-10-19	Pregrado	El Programa Nacional de Formación (PNF) en Turísmo, en su búsqueda por optimizar la planificación y gestión de las actividades turísticas, ha implementado el uso de un Sistema de Información Geográfica (SIG) enfocado en las potencialidades del sector. Este SIG se presenta como una herramienta fundamental para la identificación, análisis y visualización espacial de los recursos y atractivos turísticos existentes en el territorio. Mediante este sistema, se logra una recopilación y organización eficiente de datos georreferenciados, que incluyen información sobre sitios de interés natural y cultural, infraestructura turística, servicios disponibles, vías de acceso y patrones de flujo de visitantes, entre otros. La integración de esta información en un entorno geográfico que permite a los estudiantes y profesionales del Programa Nacional de Formación en Turismo (PNF Turismo), una comprensión más profunda de la distribución espacial de los recursos y las interrelaciones entre ellos. En el ámbito de la gestión de proyectos, el SIG juega un papel crucial al facilitar la toma de decisiones informadas. Permite la evaluación de la viabilidad de nuevos proyectos turísticos al considerar factores geográficos como la accesibilidad, la proximidad a otros atractivos y la disponibilidad de servicios. Asimismo, ayuda en la planificación de rutas turísticas, la identificación de áreas con alto potencial de desarrollo y la minimización de impactos ambientales negativos. La capacidad de sobreponer diferentes capas de información geográfica brinda una perspectiva integral para la formulación, ejecución y seguimiento de proyectos turísticos de manera más eficiente y sostenible, contribuyendo así al desarrollo estratégico del sector.	1	Programa Nacional de Formación (PNF Turísmo) UPTTMBI "La Beatríz" G-20005902-8	SIG, PNF Turísmo, gestión de proyectos, potencialidades turísticas, Sistema de información, Modelado de Datos, planificación sostenible, georreferenciación	2026-10-19 22:53:13.201708	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO.	t	\N	4	0
232	2026-10-19	Pregrado	La presente investigación se enfoca en la Optimización del Sistema de Información para el Control de Matrícula en el CAIPA Trujillo Versión 2.0, un proyecto sociotecnológico desarrollado bajo el paradigma cualitativo, la Investigación-Acción Participativa (IAP) y la metodología ágil de Programación Extrema (XP). Con el fin de superar las limitaciones de gestión de datos detectadas en la institución, el equipo de investigadores de la UPTT "Mario Briceño Iragorry" reestructuró el sistema utilizando una arquitectura Modelo-Vista-Controlador (MVC) nativa en PHP, con persistencia en una base de datos relacional MySQL normalizada en Tercera Forma Normal (3FN). A lo largo de cuatro iteraciones basadas en 11 Historias de Usuario, se rediseñó por completo la interfaz gráfica bajo un estilo Glassmorphism con hojas de estilo CSS puras, se optimizaron las consultas SQL mediante PDO incorporando búsquedas inteligentes con paginación, y se desarrolló el nuevo módulo de Gestión Integral del Personal a través de un asistente visual escalonado. El aseguramiento del sistema fue verificado mediante matrices de prueba de caja negra y caja blanca, consolidando una solución tecnológica local, rápida y segura de alta pertinencia social que optimiza integralmente el control de estudio y personal en el centro educativo.	1	CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0		2026-10-19 22:53:24.908561	\N	\N	Optimizar el sISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0.	t	\N	4	0
233	2025-11-01	Pregrado	Este proyecto consiste en el diseño, desarrollo e implementación de una aplicación web administrativa robusta y centralizada, optimizada para gestionar los procesos operativos, el control de registros y la comunicación interna dentro de la comunidad o establecimiento. La página web funcionará como un ecosistema digital integral que digitalizará tareas tradicionalmente manuales, tales como la gestión de usuarios, el inventario de recursos, el procesamiento de solicitudes y la generación de reportes detallados en tiempo real. Mediante una interfaz intuitiva, segura y de fácil acceso, el sistema optimizará la toma de decisiones basada en datos, reducirá los tiempos de respuesta administrativa y garantizará la transparencia en el manejo de la información, adaptándose a las necesidades estructurales del entorno y dejando una infraestructura tecnológica sostenible y escalable para el beneficio a largo plazo de la organización.	1	Consejo Comunal de la Comunidad “la Ciénega"	Ciénaga, Autogobierno, Desarrollo Web, Capacitación, Tecnología.	2026-10-19 22:53:26.807178	\N	\N	Desarrollar e implementar el Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega.	t	\N	4	0
234	2026-07-01	Pregrado	En el mundo digital actual, una aplicación web es una herramienta esencial para cualquier empresa, organización o individuo que desee tener una presencia en línea sólida y efectiva. Una aplicación web bien diseñada y funcional puede brindar una serie de beneficios, como:Aumento de la visibilidad y el alcance.\n\nMejora de la credibilidad y la confianza.\n\nCanal de comunicación directo.\n\nPromoción de productos y servicios.\n\nEn este contexto, en la comunidad seleccionada, Instituto Nacional de Capacitación y Educación Socialista (INCES) nos embarcaremos en la creación de una aplicación web que cumpla con sus objetivos específicos y satisfaga las necesidades de sus usuarios. Trabajaremos juntos para desarrollar un sitio web funcional, informativo y efectivo que ayude a dar el siguiente paso en la escala innovadora.	1	Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo		2026-10-19 22:53:29.323203	\N	\N	Implementar una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo.	t	\N	4	0
235	2025-11-01	Pregrado	El proyecto tuvo como propósito principal la implementación de un Sistema de Información para la Gestión Académico-Administrativa en la Escuela Técnica Comercial "Pedro García Leal". La necesidad del sistema se identificó mediante un análisis FODA y CAME lo que permitió jerarquizar las prioridades y determinar el problema central. La ejecución se gestionó bajo la metodología SCRUM, donde se realizó la delegación de roles y una definición exhaustiva de los requerimientos, funcionalidades y reglas del nuevo sistema. Posteriormente, esta etapa de planificación se materializó en el Diseño de la Base de Datos y el Diseño de la Interfaz Gráfica de Usuario (GUI), cuyo prototipo visual fue presentado a la comunidad para validación. En consecuencia, el desarrollo se articuló mediante una secuencia de objetivos específicos que abarcaron desde el análisis de procesos de asistencia y registro hasta el desarrollo, la realización de pruebas de integración y aceptación. El desarrollo incluyó la construcción del sistema, pruebas rigurosas de integración y aceptación, y su despliegue en producción. El proyecto culminó con la entrega de un Manual de Usuario y la realización de sesiones de capacitación presenciales para asegurar la correcta adopción del software, logrando así una solución funcional que optimiza integralmente la gestión de la institución.	1	ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL"		2026-10-19 22:55:36.412384	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL.	t	\N	4	0
224	2026-07-01	Pregrado	El presente proyecto sociotecnológico surge como una continuación y evolución de la plataforma educativa previamente desarrollada e implementada en el Liceo “Dr. Régulo Burelli Rivas”. Esta herramienta fue diseñada originalmente con la finalidad de fortalecer las competencias básicas en comprensión lectora y redacción de los estudiantes de primer año.Durante el proceso de aplicación de la plataforma educativa, se evidenciaron resultados positivos en el rendimiento académico de los estudiantes. Sin embargo, también se identificaron limitaciones técnicas que dificultaron su óptimo aprovechamiento, especialmente en lo referente al proceso de despliegue y configuración en los equipos, así como a la ausencia de funciones complementarias planificadas en su fase inicial. Con base en estas observaciones, se plantea la necesidad de desarrollar una nueva versión de la plataforma, optimizada en cuanto a su accesibilidad web, estabilidad y enriquecida con herramientas interactivas que amplíen su potencial educativo dentro del Liceo.	1	LICEO "DR. REGULO BURELLI RIVAS"		2026-10-19 22:30:39.179226	\N	\N	Actualizar la plataforma educativa existente mediante la simplificación de su instalación, la incorporación de nuevas funcionalidades interactivas y la capacitación docente, con el fin de fortalecer las competencias de lectura, escritura y razonamiento lógico-matemático de los estudiantes del Liceo “Dr. Régulo Burelli Rivas”.	t	\N	3	0
229	2025-11-01	Pregrado	Implementación de un Sistema Integral de Gestión Administrativa para el taller Automotriz Inversiones “Dios es Amor 31” C.A, Municipio Valera, Estado Trujillo. El presente proyecto tiene como finalidad la implementación de un Sistema Integral de Gestión Administrativa para el Taller Automotriz “Dios es Amor 31” C.A, Municipio Valera, Estado Trujillo. Como tal, el objetivo de dicho proyecto es buscar solución a la problemática que presenta el taller automotriz inversiones “Dios es Amor 31” C.A, con respecto a la falta de un sistema administrativo, con la experiencia y conocimiento que poseen los autores que realizan este proyecto, y poniendo en práctica todo lo aprendido hasta ahora de lo que han visto en la carrera de informática, siempre cumpliendo los lineamientos establecidos por la ley y por el PNFI adjunto a la U.P.T.T “Mario Briceño Iragorry”-La Beatriz (de la cual los integrantes de este proyecto pertenecen)	1	TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A	Sistema – internet - información -metodologías	2026-10-19 22:37:13.125266	\N	\N	Desarrollar e implementar el SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A.	t	\N	4	0
231	2025-07-01	Pregrado	Este informe detalla la importancia de las aplicaciones web en el entorno digital actual, destacando cómo potencian la visibilidad, credibilidad, comunicación y promoción de empresas.Se propone el desarrollo de una aplicación web para la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. El objetivo es crear un sitio web atractivo, funcional, informativo y efectivo que satisfaga las necesidades de su público y objetivos específicos.Para lograrlo, se emplearán tecnologías como PHP, CSS, HTML, MySQL y JavaScript. El proyecto se gestionará bajo la metodología XP (Programación Extrema), utilizando iteraciones o ciclos de desarrollo cortos. Esto permitirá priorizar funcionalidades clave valoradas por la empresa, asegurando entregas incrementales y un producto de calidad. Se mantendrá una comunicación constante con la stakeholder de la empresa para alinear el desarrollo con sus expectativas.El proceso se enfocará en las mejores prácticas de diseño, programación y seguridad web, priorizando la experiencia del usuario (UX), la accesibilidad y la funcionalidad para ofrecer un sitio web profesional y fácil de usar	1	EMPRESA TRUJILLANA DE PROYECTOS	Sistema de Información Web, Metodología XP, Programación, Base de Datos, PHPMyAdmin	2026-10-19 22:53:22.821568	\N	\N	Implementar uNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO.	t	\N	4	0
225	2025-10-01	Pregrado	El presente proyecto socio-tecnológico tiene como propósito actualizar y ampliar la página web existente desarrollada en el Proyecto Socio-tecnológico II, incorporando un sistema administrativo y de facturación para el Centro Inmobiliario C.A., dedicado a la compra, venta, alquiler y administración de inmuebles. Durante su ejecución se realizaron visitas a la empresa para identificar las necesidades del personal administrativo, se recolectó información técnica y para organizar las fases del desarrollo. El proyecto se sustenta en el diagnóstico previo realizado en el Proyecto II, donde se identificaron limitaciones tecnológicas como la falta de herramientas automatizadas para la gestión de facturación y alquileres. En esta nueva fase, se propone implementar un sistema que permita al propietario gestionar transacciones, pagos y reportes, ofreciendo una administración más transparente, eficiente y moderna, además de mejorar la experiencia de los usuarios mediante una plataforma más accesible y funcional.	1	Centro Inmobiliario C.A	Actualización web, ampliación de página web, sistema administrativo, sistema de facturación, gestión de inmuebles, compra, venta y alquiler, automatización de procesos, gestión de	2026-10-19 22:32:08.310709	\N	\N	Diseñar un sistema integrado para la gestión de los procesos administrativos en el Centro Inmobiliario C.A.	t	\N	3	0
237	2025-11-01	Pregrado	El presente trabajo de investigación abordó la problemática de la gestión administrativa ineficiente del Servicio Nacional de Medicina y Ciencias Forenses SENAMECF caracterizada por el uso predominante de procesos manuales y la consecuente dispersión de información vital para la operación forense. Es por ello que implantar un Sistema de Información para Automatizar la Gestión de los procesos administrativos y lógicos del SENAMECF garantizando la trazabilidad y eficiencia del servicio como logros. El proyecto se centró en analizar los procesos críticos, diseñar una arquitectura robusta, desarrollar los módulos funcionales y realizar pruebas exhaustivas del sistema para su validación operativa. La necesidad se detectó en los errores de registro, la lentitud en la búsqueda de expedientes y la gestión manual del inventario de insumos forenses lo cual impacta la calidad de los dictámenes periciales. La pertinencia de la solución radica en su capacidad de estandarizar los flujos de trabajo, automatizar tareas repetitivas convirtiendo la gestión administrativa en un soporte ágil y fiable para las operaciones periciales. El sistema fue desarrollado utilizando la metodología Programación Extrema XP, este enfoque ágil priorizó la entrega continua de valor, la retroalimentación constante con los usuarios finales del SENAMECF y la alta calidad del código a través de técnicas como el desarrollo guiado por pruebas unitarias. La flexibilidad de XP fue crucial para adaptarse a los requerimientos institucionales. El sistema de información implantado generó un impacto significativo al mejorar la eficiencia operacional, los beneficios obtenidos incluyen una alta reducción en el tiempo de procesamiento de documentos y la eliminación de duplicidad de datos, las mejoras implantadas se centran en la automatización del control, generación de reportes gerenciales en tiempo real lo que permitió a la dirección del SENAMECF una toma de decisiones proactivas en esencia. El sistema transformó la gestión administrativa fortaleciendo la capacidad operativa del servicio forense a nivel nacional.	1	SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF	Sistema de Información, Gestión Administrativa, SENAMECF, Programación	2026-10-19 22:57:32.71912	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF.	t	\N	4	0
238	2025-11-01	Pregrado	Este proyecto se centra en la evolución y mejora de la aplicación web para optimizar la gestión de productos y servicios previamente desarrollada en Super Kauchos Gilsa, C.A., empresa ubicada en Valera, Estado Trujillo. Partiendo del sistema ya implementado, el objetivo principal de esta es potenciar la eficiencia operativa, calidad del servicio al cliente y la comercialización de su catálogo de productos y servicios, mediante la incorporación de nuevas funcionalidades avanzadas junto con mejoras en la arquitectura del sistema, que permitan ampliar aún más su alcance y usabilidad. A partir de un diagnóstico técnico de la plataforma existente y la recolección de nuevos requerimientos con el personal clave, se determinó la continuidad de la metodología ágil Extreme Programming (XP). Esta elección se fundamenta en su enfoque iterativo, ideal para la integración continua de módulos y el refactorizado de código. El proceso de mejora del software siguió las etapas de planificación, diseño, codificación, pruebas y lanzamiento, garantizando una transición fluida hacia una versión del sistema más robusta, segura y adaptada a las demandas actuales de la empresa.	1	SUPER KAUCHOS GILSA, C.A	Aplicación web, optimizar, gestión, catálogo, metodología, Extreme Programming (XP), codificación, mejora, evolución, potenciar	2026-10-19 22:58:21.059322	\N	\N	Optimizar la gestión comercial de la empresa.	t	\N	3	0
239	2025-11-01	Pregrado	El presente proyecto, titulado “Sistema Integral de Administración de Casos (SIAC) para la Prefectura del Municipio Escuque”, se desarrolló como parte del Proyecto Socio-Tecnológico IV del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo (UPTT) Núcleo La Beatriz, durante el año 2025. Fue elaborado por los estudiantes Christian Márquez, Franyer Montilla, Ismaireth Rivera, José Borrero y Carlos Vallejo. El propósito principal del proyecto fue implementar un sistema integral que optimizara la gestión de los casos procesados en la Prefectura del Municipio Escuque, sustituyendo los métodos manuales y físicos utilizados anteriormente. El problema identificado radicaba en la falta de una herramienta tecnológica que permitiera registrar, consultar y administrar los casos de manera eficiente, lo que generaba retrasos, duplicidad de información y dificultades en el resguardo de los expedientes. El sistema fue desarrollado utilizando HTML, CSS, PHP, JavaScript y MySQL como tecnologías base, garantizando una plataforma funcional, segura y adaptable a las necesidades de la institución. El SIAC está compuesto por diversos módulos, entre los que destacan: Gestión de Casos, Denuncias, Cauciones, Contactos, Planillas, Registro Fotográfico, Estadísticas, Búsqueda Avanzada y Administración de Usuarios. Cada uno de estos módulos cumple una función específica orientada a mejorar la eficiencia administrativa y el control de información. Entre los beneficios obtenidos se destacan la agilización en el manejo de casos, la digitalización de los registros, y una mejor protección y respaldo de los datos institucionales. Además, el sistema promueve la modernización tecnológica de la Prefectura y contribuye al fortalecimiento del vínculo comunidad–universidad, al ofrecer una solución informática útil y sostenible que responde a una necesidad real de la comunidad.	1	PREFECTURA DEL MUNICIPIO ESCUQUE		2026-10-19 22:59:48.848881	\N	\N	Implementar un sistema integral que optimizara la gestión de los casos procesados en la Prefectura del Municipio Escuque, sustituyendo los métodos manuales y físicos utilizados anteriormente.	t	\N	4	0
236	2026-10-19	Pregrado	El presente proyecto tuvo como finalidad optimizar el Sistema de Información Automatizado para la Gestión Académica utilizado por el Centro Bolivariano de Informática y Telemática “Ing. Eli Rojas”, mediante la incorporación de nuevas funcionalidades y mejoras orientadas a fortalecer su funcionamiento. A partir del análisis realizado en la institución, se identificaron oportunidades de mejora relacionadas con la gestión de usuarios, el control y organización de la información. Como respuesta a estas necesidades, se desarrollaron mejoras visuales y funcionales, incorporando un sistema multiusuario, control de permisos según el tipo de usuario, nuevos módulos para la gestión de recursos del área, certificaciones, evaluaciones y estadísticas de asistencia. Para el desarrollo de la propuesta se empleó la metodología Investigación Acción Participativa (IAP) junto con la metodología ágil Extreme Programming (XP), permitiendo la participación de la comunidad durante las diferentes fases del proyecto. Los resultados obtenidos contribuyen a optimizar la gestión de la información y apoyar los procesos desarrollados dentro de la institución. De esta manera, se logró cumplir con los objetivos planteados, obteniendo una solución tecnológica más organizada, funcional y adaptada a las necesidades actuales.	1	Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera	Sistema de información, gestión académica, optimización, sistema multiusuario, automatización, XP	2026-10-19 22:56:48.560733	\N	\N	Optimizar sistema de información automatizado para la gestión académica del Centro Bolivariano de Informática y Telemática Ing. Eli Rojas de la Escuela de Emprendimiento Valera.	t	\N	4	1
240	2025-11-29	Pregrado	El presente proyecto busca resolver el problema detectado en la comunidad del Instituto Universitario de Educación Especializada (IUNE) que consistió en la ausencia de un sistema integral para el control de pago de aranceles en el Instituto Universitario de Educación Especializada (IUNE) en Valera, estado Trujillo, cuya carencia genera demoras en los procesos de las oficinas centrales. La solución propuesta por el equipo investigador fue la implantación de un Sistema Integral de Control de Pagos de Aranceles, empleando la metodología de desarrollo XP (Programación Extrema) para asegurar la planificación, codificación, validación y mantenimiento del software. Este trabajo se enmarca en la línea de desarrollo de sistemas del PNF en Informática, específicamente en las líneas de investigación de Sistema de Información y Modelado de Datos. Con la implementación de este sistema, se busca optimizar el control de pago de aranceles y generar un impacto positivo y sostenible en la eficiencia operativa de la institución.	1	IUNE Valera	Sistema Integral, Control de Pagos, Aranceles, Metodología de Desarrollo XP, IUNE, Sistema de Información, Modelado de Datos, Eficiencia Operativa	2026-10-19 23:01:57.118571	\N	\N	Implementar un Sistema Integral de Control de Pago de Aranceles del Instituto Universitario de Educación Especializada (Valera).	t	\N	3	0
241	2026-06-01	Pregrado	El presente Proyecto Socio-Tecnológico tiene como propósito diseñar e implementar un sistema web para digitalizar los servicios técnicos y registros de equipos en la microempresa TecnoService de Miguel Morales F.P. ubicada en Valera, Estado Trujillo la problemática central diagnosticada fue la falta de digitalización en los flujos de entrada y salida, lo cual generaba desorden en los equipos que ingresaban, el estudio se fundamenta en la Investigación-Acción Participativa (IAP) Esta metodología promueve la colaboración activa entre los estudiantes y la comunidad abordada, facilitando un proceso en el que todos los involucrados pueden contribuir a la identificación de problemas y a la búsqueda de soluciones, para el desarrollo tecnológico, se empleó la metodología ágil Scrum, permitiendo el desarrollo progresivo y estructurado, asegurando que el resultado final sea una herramienta útil para la comunidad	1	TECNOSERVICE DE MIGUEL MORALES F.P	Sistema Web, Gestión Digital, SCRUM, Base de Datos	2026-10-19 23:01:57.229604	\N	\N	Diseñar e implementar un sistema web para digitalizar los servicios técnicos y registros de equipos en la microempresa TecnoService de Miguel Morales F.	t	\N	1	0
242	2026-07-23	Pregrado	El presente informe aborda el análisis, diseño y desarrollo de un sistema de información Web administrativo–académico para la Escuela Técnica “Pedro García Leal”. El proyecto surge ante la necesidad institucional de modernizar y centralizar los procesos relacionados con el registro de estudiantes, gestión de notas, elaboración de listados y control interno de datos académicos. A través del desarrollo de una aplicación web administrativo personalizado, se busca optimizar las actividades administrativas, reducir errores manuales y garantizar un acceso rápido, organizado y seguro a la información. La propuesta tecnológica se fundamenta en principios de ingeniería de software y en metodologías de desarrollo orientadas a la mejora continua. Este sistema permitirá a la institución fortalecer su capacidad operativa y avanzar hacia una gestión más eficiente, digital y alineada con las exigencias actuales del entorno educativo. El proyecto contribuye directamente al fortalecimiento tecnológico de la escuela, promoviendo una cultura institucional basada en la innovación, la digitalización y el uso responsable de herramientas informáticas como MySQL en phpMyAdmin, usando lenguajes tales como PHP, HTML y CSS mediante la metodología agil: XP	1	ESCUELA TÉCNICA “PEDRO GARCÍA LEAL		2026-10-19 23:01:57.289094	\N	\N	Desarrollar un sistema de información administrativo-académico para la Escuela Técnica “Pedro García Leal”, que permita digitalizar, organizar y agilizar los procesos de gestión de estudiantes y notas, a partir del análisis de la información existente y los requerimientos institucionales, optimizando el trabajo administrativo mediante una solución tecnológica eficiente y adaptada a las necesidades reales de la institución.	t	\N	3	0
243	2025-11-01	Pregrado	El proyecto abordó la falta de un sistema de inventario para el control de Bienes Nacionales en la Oficina Administrativa Valera del IVSS, un problema generado por la dependencia del proceso manual, la limitación de personal y la escasez de recursos tecnológicos. Esta situación causaba dificultad en el seguimiento de los bienes, frustración en el personal y retraso en el envío de información a la sede principal. Para resolver esto, se implementó el Sistema de Gestión de Bienes Nacionales (SIGEBI-OAV), utilizando la metodología Proceso Unificado de Rational (RUP). La ejecución fue rigurosa, iniciando con la determinación precisa de requerimientos mediante Casos de Uso, y diseñando una arquitectura robusta centrada en la integridad de los datos y la aplicación estricta de la lógica de negocio. Seguidamente, la fase de construcción del software incluyó funcionalidades críticas como la reasignación automática de bienes y un estricto Control de Acceso por Roles para garantizar la seguridad. La calidad del producto fue validada con pruebas exhaustivas de Caja Negra y Caja Blanca. Finalmente, el proyecto culminó con la entrega exitosa del SIGEBI-OAV, lo que permitió automatizar y optimizar el registro y consulta de activos. Las conclusiones señalan que la nueva herramienta no solo moderniza la gestión, sino que también fortalece la transparencia y la rendición de cuentas en el manejo de los recursos públicos, tras haber asegurado su correcta adopción mediante la capacitación del personal directivo y administrativo	1	OFICINA ADMINISTRATIVA VALERA (IVSS)	sistema, inventario, IVSS	2026-10-19 23:01:57.359099	\N	\N	Desarrollar e implementar el SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS).	t	\N	3	0
244	2024-11-01	Pregrado	Implementación de una Aplicación Web para la Cooperativa “La Once con La Once” Parroquia Juan Ignacio Montilla, Municipio Valera, Estado Trujillo. El presente proyecto tiene como finalidad la implementación de una aplicación web para la Cooperativa “La Once con La Once”, ubicados en la parroquia Juan Ignacio Montilla, municipio Valera, estado Trujillo. Como tal, el objetivo de dicho proyecto es buscar solución a la problemática que presenta la Cooperativa “La Once con La Once” con respecto a la falta de una plataforma o medio digital para darse a conocer. Se realizara en base a la experiencia y conocimiento que poseen los autores que realizan este proyecto, y poniendo en práctica todo lo aprendido hasta ahora de lo que han visto en la carrera de informática, siempre cumpliendo los lineamientos establecidos por la ley y por el PNFI adjunto a la U.P.T.T “Mario Briceño Iragorry”-La Beatriz (de la cual los integrantes de este proyecto pertenecen)	1	COOPERATIVA “LA ONCE CON LA ONCE ”	Aplicación web – internet - información -metodologías	2026-10-19 23:01:57.42327	\N	\N	Desarrollar una aplicación web para la gestión de la	t	\N	3	0
245	2026-07-01	Pregrado	El objetivo de este proyecto, es llevar a cabo, un sistema de gestión y control de datos educativos mediante una aplicación web en la institución U.E Hugo Rafael Chávez Frías Municipio Valera Estado Trujillo. Parte del diagnóstico situacional, es proporcionar una carta a través de solicitar un permiso para llevar a cabo un proyecto socio tecnológico. A través de esto, se quiere saber, resolver los problemas en relación al área de informática A partir de este punto de vista, el propósito general de este proyecto es la actualización de esta página informativa de la institución Para que el servicio sea más eficiente, dado que los usuarios necesitan la intercomunicación, ya que no tenía cómo actualizar la información de la institución. El estudio se basa en la modalidad socio tecnológica cuantitativa del proyecto, los métodos que se utilizan para desarrollarlos son la observación, la entrevista, anotaciones al preparar un diagnóstico previo de investigación. Para realizar la observación, fue necesario utilizar como instrumento una libreta de campo la cual permitió recolectar datos resultantes del estudio como también, el uso de la cámara fotográfica para dar sustentación a la investigación. Para el desarrollo del proyecto, se utilizan diferentes programas para efectuar la debida actualización técnica. A nivel teórico se fundamenta en diferentes percepciones de autores de gran relevancia. El desarrollo técnico del proyecto se abordará bajo los principios de la Ingeniería de Software, utilizando la metodología ágil Extreme Programming (XP). Esta elección metodológica garantiza la entrega temprana de valor y la alta calidad del software, al basarse en la retroalimentación constante del usuario	1	UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS	página web, intercomunicación, información, usuarios	2026-10-19 23:01:57.494415	\N	\N	Actualizar a un sistema para la gestión y control de datos educativos en la Unidad Educativa “Hugo Rafael Chávez Frías” “Antonio Nicolás Briceño” Sector Santa Cruz, parroquia San Luis, Municipio Valera del Estado Trujillo., que incorpore múltiples módulos funcionales, tales como registro y control académico, gestión de asistencia, administración de usuarios, con el propósito de optimizar los procesos administrativos, fortalecer la organización de la institución.	t	\N	3	0
246	2026-07-01	Pregrado	Este proyecto socio-tecnológico, titulado "Sistema Local de Gestión\nEscolar para la Escuela 'Vicente de la Torre'" y desarrollado en el marco del\nPNF en Informática, consiste en el diseño e implementación de un software\nlocal basado en PHP y MySQL para automatizar y centralizar los registros\nadministrativos y estudiantiles de dicha institución. Su objetivo principal es\noptimizar el control de inscripciones, asistencias y calificaciones, sustituyendo\nlos procesos manuales en físico para mitigar la pérdida de documentos, agilizar\nlas consultas del personal directivo y facilitar la toma de decisiones mediante\nreportes estadísticos automatizados.	1	ESCUELA “VICENTE DE LA TORRE		2026-10-19 23:01:57.547376	\N	\N	Implementar un sistema de gestión local escolar para la Escuela “Vicente de la Torre” como como respaldo de información y automatización de esta, lo que facilitara el trabajo del secretario(a) al momento de hacer estadísticas e inscripción de los estudiantes.	t	\N	3	0
247	2025-11-01	Pregrado	El presente proyecto, desarrollado para Musart Academia C.A., tuvo como propósito principal la implementación del Sistema de Información Académico/Administrativo (SIACAD); dicho sistema fue diseñado específicamente para modernizar los procesos educativos junto con las operaciones de esta institución especializada en formación artística además de refuerzo académico. Cabe destacar que, dado que la academia atiende a estudiantes de diversas edades mediante metodologías innovadoras en instalaciones de primer nivel, el sistema fue concebido para responder a sus necesidades particulares. En cuanto a su desarrollo, se adoptó la metodología ágil Kanban, la cual permitió organizar el trabajo en etapas claramente definidas durante el período comprendido entre enero y noviembre de 2025. Por otra parte, el marco teórico que sustenta esta iniciativa integra conceptos clave de sistemas de información, gestión académica, gestión administrativa, así como optimización de procesos. Respecto a sus beneficios, la implementación del SIACAD generará importantes ventajas operacionales: automatización de tareas repetitivas, producción sistemática de informes estratégicos, agilización de los flujos comunicacionales junto con acceso inmediato a datos actualizados. En consecuencia, estas mejoras fortalecerán tanto la operatividad diaria como la capacidad de crecimiento institucional de Musart Academia C.A., posicionándola como referente en innovación educativa.	1	"Musart Academia, C.A	Sistema, Información, Gestión Académica, Gestión Administrativa, Metodología Kanban, Musart Academia	2026-10-19 23:01:57.607227	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A.	t	\N	3	0
248	2025-11-01	Pregrado	El presente trabajo de investigación tuvo como objetivo general implementar un sistema de información para el registro y gestión laboral del Instituto del Deporte del Estado Trujillo (INDET), utilizando la modalidad de investigación-acción participativa (IAP) y la metodología SCRUM para el desarrollo tecnológico. Entre los objetivos específicos se destacan: determinar los requerimientos funcionales y técnicos para la gestión de datos de los empleados del INDET, diseñar y desarrollar el Sistema de Información para el Registro y Gestión Laboral del INDET (SIRGL-INDET), realizar pruebas de funcionamiento para garantizar la estabilidad y confiabilidad del sistema, instaurar el sistema en el entorno operativo y capacitar al personal del departamento de recursos humanos en su uso eficiente. En la etapa de diagnóstico, se identificaron las necesidades y desafíos institucionales, promoviendo la participación activa de los miembros y fortaleciendo su sentido de pertenencia y compromiso con los procesos organizacionales. Durante la etapa de análisis, se fundamentaron las decisiones estratégicas orientadas a la solución de la problemática detectada. En la fase de diseño y desarrollo, se elaboró el SIRGL-INDET, automatizando los procesos manuales que generaban retrasos en la atención de solicitudes, lo que optimizó la gestión de información y mejoró los tiempos de respuesta y la calidad del servicio ofrecido por la institución. Finalmente, se ejecutó un proceso de capacitación dirigido al personal de recursos humanos, facilitando la apropiación y uso eficiente del sistema. El proyecto logró transformar la gestión laboral del INDET mediante la incorporación de tecnologías de información, promoviendo la participación activa de los miembros y mejorando la eficiencia y transparencia de los procesos institucionales, lo que representa un avance significativo en la modernización y profesionalización de la organización	1	INDET(SIRGL-INDET)	sistema, información, registro, gestión laboral, INDET	2026-10-19 23:01:57.767809	\N	\N	Implementar un sistema de información para el registro y gestión laboral del INDET(SIRGL-INDET)	t	\N	3	0
249	2025-10-10	Pregrado	El presente proyecto socio-tecnológico tiene como finalidad desarrollar un sistema de gestión de inventario web para la empresa INME C.A., dedicada a la fabricación de tableros eléctricos, cajas de distribución y centros de medición. Surge como respuesta a la necesidad de optimizar los procesos administrativos y operativos que actualmente se ejecutan de forma manual, generando retrasos, errores en los registros y pérdida de información.\n\nDicho estudio se fundamenta en la metodología de Investigación-Acción Participativa (IAP), permitiendo una interacción directa entre los investigadores y la organización, con el fin de identificar problemas reales, diseñar una propuesta tecnológica adecuada y aplicar soluciones efectivas orientadas al mejoramiento continuo.\n\nLa investigación aborda un diagnóstico situacional, la identificación de necesidades, el análisis de alternativas de solución y el desarrollo de un sistema automatizado para la gestión integral de inventarios, facturación, clientes y órdenes de producción. La implementación del sistema permitirá reducir errores, centralizar la información, optimizar tiempos de respuesta y aumentar la productividad empresarial.\n\nFinalmente, se espera que el proyecto contribuya al fortalecimiento tecnológico de INME C.A., mejore su capacidad competitiva en el mercado nacional y sirva como modelo de referencia para otras organizaciones con necesidades similares de transformación digital	1	INME C.A		2026-10-19 23:04:04.194917	\N	\N	Desarrollar un sistema de gestión de inventario web para la empresa INME C.	t	\N	4	0
250	2025-11-01	Pregrado	Este proyecto de investigación desarrolla e implementa un Sistema de Información Zoocéntrico para el Centro Veterinario Integral "Dr. Diego Hidalgo". Bajo el paradigma de la Investigación Acción Participativa (IAP), se identificó como problemática central la ausencia de un sistema unificado para gestionar historias médicas veterinarias y procesos de exportación, generando redundancia de datos, retrasos operativos y riesgos de incumplimiento normativo. Mediante un diagnóstico participativo y herramientas de análisis estratégico (matriz FODA, matriz de priorización), se determinó que la necesidad más crítica era una solución tecnológica integrada. La evaluación de alternativas mediante una matriz de decisión seleccionó el desarrollo del Sistema de Información Zoocéntrico como la opción óptima, por su equilibrio superior en costo, facilidad de uso, seguridad, eficiencia y cumplimiento legal. El objetivo general es implementar dicho sistema en el centro veterinario. Para ello, se estructuraron objetivos específicos que abarcan el análisis de requerimientos, el diseño de una arquitectura modular, el desarrollo basado en la metodología de Programación Extrema (XP), la implementación en la infraestructura existente y la capacitación del personal. El sistema integrará módulos para la gestión digitalizada de historias médicas y la automatización de los procesos de exportación, centralizando la información, eliminando redundancias y garantizando la trazabilidad integral del paciente, introduciendo mejoras sustanciales en seguridad informática, eficiencia operativa y cumplimiento normativo	1	CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO"	Sistema de Información Zoocéntrico, Centro Veterinario Integral, Programación Extrema (XP), historias médicas veterinarias, exportación de mascotas, Node	2026-10-19 23:04:44.164147	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO.	t	\N	4	0
267	2025-05-01	Pregrado	La dependencia tecnológica en empresas exige personal cualificado en soporte y mantenimiento informático para evitar ineficiencias y fallas. En este contexto, el Programa Nacional de Formación en Informática (PNFI) de la UPTTMBI capacita a futuros profesionales en dicha área. Como parte de la formación, el Proyecto Socio Tecnológico Integrador sirve como herramienta para lograr adquirir y aplicar el conocimiento en un entorno real y monitoreado. Siendo la empresa privada Duilio De Jesús Valero Romero Panadería, Pastelería Y Lunchería “La Estrella De Valera” en la localidad de Valera, Estado Trujillo. Un sitio pertinente para proporcionar un servicio técnico de mantenimiento y correctivo en el hardware y software en los equipos activos dentro de la empresa, buscando satisfacer las demandas reales en los equipos con el mantenimiento, y en los usuarios con la alfabetización tecnológica, con el fin de brindar conocimientos tangibles al equipo de estudiantes que llevara a cabo dicha práctica	1	Duilio De Jesús Valero Romero Panadería	Soporte técnico a usuarios y a equipos, Hardware, Software	2026-10-19 23:24:58.763374	\N	\N	Proporcionar soporte técnico a usuarios y equipos informáticos de la Panadería "La Estrella" para optimizar su rendimiento.	t	\N	1	0
251	2026-06-01	Pregrado	El presente Proyecto Socio-Tecnológico consistió en el diseño e implantación de un portal web integral para la automatización operativa de la agencia publicitaria PubliArt. El estudio se fundamentó en la Metodología de Investigación-Acción Participativa, diagnosticando debilidades críticas en el manejo manual de registros de datos, el control de la agenda interna y la proyección de la identidad corporativa. Como solución de ingeniería de software, los investigadores desarrollaron una plataforma dinámica estructurada en diez módulos independientes empleando el lenguaje de programación PHP y un sistema de gestión de bases de datos relacionales en MySQL. El sistema incorpora principios de diseño web responsivo, garantizando una óptima usabilidad (UI/UX) tanto en computadores como en dispositivos móviles. La implementación técnica sustituyó los flujos informales por procesos digitalizados y centralizados, optimizando la administración del portafolio digital y agilizando el agendamiento de actividades de la organización. Las jornadas de capacitación dictadas al personal aseguraron la correcta adopción tecnológica y el mantenimiento de la herramienta. Los resultados demostraron que la articulación entre la teoría académica y el desarrollo de software libre consolida soluciones eficientes que elevan la productividad, seguridad y el crecimiento institucional en el entorno digital.	1	EMPRESA PUBLIART	Proyecto Socio-Tecnológico, Portal web, Automatización, Implementación, Scrum	2026-10-19 23:05:11.101813	\N	\N	Implementar un Portal web que permita mostrar los servicios de la empresa junto a una base de datos que permita registrar posibles clientes en los diferentes servicios para la empresa Publiart.	t	\N	4	0
252	2026-07-01	Pregrado	El presente proyecto sociotecnológico tuvo como objetivo implantar un Sistema de Gestión Digital para el Inventario, Trazabilidad y Movimiento de Bienes en la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. (ETPROINVI S.A.), ubicada en Valera, estado Trujillo. El diagnóstico participativo identificó la dispersión de registros físicos y hojas de cálculo, así como dificultades para consultar el estado y los movimientos de los bienes institucionales. Se empleó la investigación acción participativa para recopilar y validar las necesidades de la Unidad de Bienes, y Scrum para organizar el levantamiento de requerimientos, el diseño, la construcción y la revisión progresiva del producto. La solución web se desarrolló con C#, ASP.NET MVC, MySQL, HTML, CSS y JavaScript. Comprende acceso por roles, registro y consulta de bienes, solicitudes de traspaso y desincorporación, historial de movimientos, etiquetas con códigos QR, notificaciones y reportes. El sistema se presentó a los usuarios, se sometió a verificaciones funcionales y de uso, y se documentaron actividades de instalación, entrega del manual digital y capacitación. El producto centraliza la información y permite consultar la trazabilidad de los bienes, con una base para fortalecer el control institucional. La evidencia del informe describe resultados cualitativos y no establece una medición comparativa de tiempos, errores o costos.	1	EMPRESA TRUJILLANA DE PROYECTOS	gestión de bienes, inventario digital, trazabilidad, sistema web, Scrum	2026-10-19 23:06:50.622395	\N	\N	Implantar un sistema de gestión digital para el inventario, trazabilidad y movimiento de bienes en la Empresa Trujillana de Proyectos, Infraestructura y Vivienda S.A. (ETPROINVI S.A.).	t	\N	4	0
253	2026-07-23	Pregrado	El presente informe aborda el análisis, diseño y desarrollo de un sistema de información Web administrativo–académico para la Escuela Técnica “Pedro García Leal”. El proyecto surge ante la necesidad institucional de modernizar y centralizar los procesos relacionados con el registro de estudiantes, gestión de notas, elaboración de listados y control interno de datos académicos. A través del desarrollo de una aplicación web administrativo personalizado, se busca optimizar las actividades administrativas, reducir errores manuales y garantizar un acceso rápido, organizado y seguro a la información. La propuesta tecnológica se fundamenta en principios de ingeniería de software y en metodologías de desarrollo orientadas a la mejora continua. Este sistema permitirá a la institución fortalecer su capacidad operativa y avanzar hacia una gestión más eficiente, digital y alineada con las exigencias actuales del entorno educativo. El proyecto contribuye directamente al fortalecimiento tecnológico de la escuela, promoviendo una cultura institucional basada en la innovación, la digitalización y el uso responsable de herramientas informáticas como MySQL en phpMyAdmin, usando lenguajes tales como PHP, HTML y CSS mediante la metodología agil: XP	1	ESCUELA TÉCNICA “PEDRO GARCÍA LEAL		2026-10-19 23:06:57.092771	\N	\N	Desarrollar un sistema de información administrativo-académico para la Escuela Técnica “Pedro García Leal”, que permita digitalizar, organizar y agilizar los procesos de gestión de estudiantes y notas, a partir del análisis de la información existente y los requerimientos institucionales, optimizando el trabajo administrativo mediante una solución tecnológica eficiente y adaptada a las necesidades reales de la institución.	t	\N	3	0
254	2025-11-26	Pregrado	Este proyecto propone el desarrollo e implementación de un sistema web para la gestión del talento humano del SAPNNAET, con el fin de modernizar y optimizar los procesos administrativos de la institución. A partir de un diagnóstico situacional, se identificaron problemáticas como el uso de sistemas obsoletos, la falta de centralización de la información y la limitada capacitación del personal. La solución planteada consiste en una herramienta web que mejora la eficiencia, organización y seguridad en la gestión de datos del personal. Este proyecto se enmarca en la línea de desarrollo de sistemas del PNF en Informática con las líneas de investigación: Sistema de Información y Modelado de Datos. Con esto busca generar un impacto positivo en la operatividad institucional y en la atención a la comunidad	1	SAPNNAET	Sistema Web, Gestión Integral, Talento humano, SAPNNAET	2026-10-19 23:06:57.170925	\N	\N	Desarrollar e implementar el Sistema Web de gestión integral del Talento humano para el SAPNNAET.	t	\N	3	0
255	2025-12-02	Pregrado	El proyecto titulado Sistema de Información para la Gestión Administrativa del Personal Adscrito a la VALFOR S.A. se desarrolló en la empresa VALFOR S.A., concesionario autorizado de la marca Ford ubicado en Valera, Estado Trujillo, Venezuela, con el objetivo general de implementar un sistema de información que optimice los procesos administrativos relacionados con la gestión del personal, mejorando la eficiencia, precisión y control en el cálculo de nómina y manejo de datos; entre sus objetivos específicos se incluyó el diseño, desarrollo e implementación del sistema, así como la capacitación del personal administrativo para su uso adecuado; la necesidad detectada se centró en la ausencia de un software propio para la gestión de nómina, lo que obligaba a la empresa a realizar procesos manuales en hojas de cálculo, generando errores y reduciendo la productividad, por lo que la solución tecnológica propuesta resultó pertinente al automatizar procesos clave y centralizar la información administrativa; la metodología aplicada fue de tipo Investigación Acción Participativa (IAP) con enfoque en la metodología ágil Scrum, favoreciendo la colaboración continua entre los desarrolladores y la comunidad empresarial; los beneficios obtenidos incluyen la reducción de errores, el ahorro de tiempo operativo y la mejora en la toma de decisiones mediante información precisa y actualizada; en conclusión, el proyecto fortaleció la gestión administrativa de VALFOR S.A., promoviendo la modernización tecnológica de sus procesos y reafirmando la importancia de las herramientas informáticas para la eficiencia organizacional.	1	VALFOR S.A		2026-10-19 23:08:32.454978	\N	\N	Implementar un sistema de información que optimice los procesos administrativos relacionados con la gestión del personal, mejorando la eficiencia, precisión y control en el cálculo de nómina y manejo de datos.	t	\N	4	0
270	2026-04-01	Pregrado	En el presente proyecto Socio tecnológico se tiene como propósito la ejecución de un soporte técnico abarcando un mantenimiento preventivo y correctivo, así como la capacitación a los usuarios dentro del departamento de Automatización de las Tecnologías de Información y Telecomunicaciones (ATIT) en CORPOELEC, este se aplicó a cinco equipos de computación los cuales presentaban fallas. Todo esto se llevó una metodología la cual fue Investigación-Acción Participativa (IAP) y apoyada en un enfoque mixto, con componentes de Investigación Tecnológica-Aplicada y Descriptiva, utilizando herramientas que nos permitirían llevar a cabo todo el trabajo, como la matriz ROLA y el Árbol de problemas, con los cuales pudimos hacer identificación de problemas como acumulación de polvo y falta de optimación de los sistemas. En este trabajo observaremos como llevamos a cabo todo el soporte tanto a equipos como a usuarios y que obtuvimos como resultado y como llevamos una charla/participativa sobre cómo tratar los equipos para tener una vida útil más prolongada al hardware. Esto permitiéndonos fortalecer todos los procesos informáticos y administrativos de la empresa	1	Corporación Eléctrica Nacional (CORPOELEC) de Venezuela		2026-10-19 23:24:59.123033	\N	\N	\N	t	\N	1	0
256	2025-07-01	Pregrado	El presente proyecto socio-tecnológico expone la implementación de una propuesta estratégica para la creación de una aplicación web con el fin de mejorar tanto la experiencia de los usuarios como la gestión de la institución, el cual tendrá como ambiente la Unidad Educativa Hermana. María Sorrosal, ubicada en la comunidad José Humberto Contreras (Morón) Municipio Valera, Estado Trujillo. Por otro lado, el mismo se va a constituir de un marco metodológico el cual nos proporcionó un estudio y recopilación de los puntos más importantes para el desarrollo de la presente investigación, ya que de esta manera se pudo orientar toda la información que será relevante, para construir criterios y fundamentar las razones que llevaron a la realización de dicho proyecto socio-tecnológico, de este modo se utilizó la herramienta de la entrevista para el diagnóstico situacional, en donde la misma nos permitió conocer los problemas y las variables en estudio a analizar, para que de esta forma se logré determinar el punto de inflexión en el cual se ejecutaron los objetivos de una forma eficiente y a su vez tener un punto en específico de inicio, en donde el mismo sería clave para la distribución y aprovechamiento de los recursos propios de la institución, por consiguiente se emplearon tres elementos, dentro de los cuales se destacan, el diagrama de causa-efecto y el árbol de problemas, de tal manera dichos elementos nos ayudaron a visualizar aquellas causas y consecuencias del problema principal, que era la falta de una aplicación web en el laboratorio de computación , aunado a esto se haría uso del árbol de objetivos, que expondría las alternativas de solución a aquellas problemáticas, generando de este modo soluciones concretas para garantizar una ejecución exitosa, todo ello se logró gracias a la participación del personal administrativo del colegio, generando así beneficios en los involucrados tanto de forma directa como indirecta. MEMORIA DESCRIPTIVA Identificación y objetivo del proyecto: El presente proyecto se redacta de acuerdo a la finalidad de la asignatura “Proyecto Socio Tecnológico (PST)” del PNFI en informática impartida en la Universidad Politécnica Territorial Mario Briceño Iragorry (UPTT). La guía y supervisión de mismo le corresponden a la docente asesor del proyecto Marzzia Gil, y dicho proyecto tiene como objetivo el realizar la implementación de una aplicación web, en la Unidad Educativa Hermana María Sorrosal. Antecedentes del proyecto: El presente proyecto ha de servir como un documento por medio del cual se han buscado soluciones prácticas y adecuadas reflejando de este modo su objetivo.	1	Unidad Educativa Hna. María Sorrosal		2026-10-19 23:10:14.893313	\N	\N	Implementar una aplicación web para la Unidad Educativa Hna. María Sorrosal.	t	\N	3	0
257	2025-04-01	Pregrado	El siguiente proyecto presenta una propuesta para implantar un Sistema Web para la gestión de proyectos de los Consejos Comunales en el Consejo Local de Planificación Pública (CLPP), Del Municipio Valera, parroquia Mercedes Díaz, Estado Trujillo. Para el logro de las metas se desarrollaron los siguientes objetivos específicos: Determinar los requerimientos del sistema web, Diseñar la arquitectura del sistema, incluyendo base de datos y módulos, Establecer un plan de mantenimiento y actualización del sistema, Implementar procedimientos de respaldo y seguridad de datos, Capacitar al personal técnico para el soporte continuo del sistema, Elaborar el manual de usuario del sistema web, Capacitar al personal administrativo del CLPP en el uso del sistema. Así mismo, para el diseño y modelado de la página se utilizó los lenguajes de programación CSS PHP, MYSQL, y HTML, en la estructura de las bases de datos se usó PhpMyAdmin. Elaboración del plan de acción para la puesta en marcha del sistema web, se procedió a subirlo en internet. Desde el punto de vista metodológico este estudio es de tipo positivista cualitativo y se corresponde con diseño de trabajo factible, con características documental y de campo. La importancia de este proyecto está representada en los beneficios otorgados a los ciudadanos y ciudadanas del municipio Valera, así como también a los consejos comunales, que amerite información sobre los proyectos, planes o acontecimientos del (CLPP), para vincularse al gobierno electrónico que sirve de medio para la toma de decisiones en la planificación municipal.	1	CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA	Sistema web, gestión, consejos comunales	2026-10-19 23:12:05.072795	\N	\N	Implementar un sistema web para la gestión de proyectos de los consejos comunales en el Consejo Local Planificación Pública.	t	\N	4	0
258	2025-11-19	Pregrado	El proyecto socio tecnológico que se desarrolló, junto con los autores mencionados, consiste en la creación de un Sistema de información para el registro académico y administrativo. La recolección de información incluyó tanto datos provenientes de la coordinación del colegio y de los responsables de la institución, como también investigaciones propias de los autores. Además, se obtuvieron antecedentes relevantes (Cabrera J, Rojo D (2019), Briceño, L (2022), Torres, D (2022)), mediante la consulta de la biblioteca de la universidad y gracias a la orientación proporcionada por el guía del proyecto. Este sistema permite agilizar y organizar de manera eficiente los procesos de registro y matriculación de personal y estudiantes, para la realización del proyecto, hemos utilizado la metodología XP (Programación Extrema), la cual se caracteriza por su enfoque ágil y flexible. La implementación de esta metodología nos ha permitido adaptarnos rápidamente a los cambios y requerimientos del cliente, garantizando así la satisfacción de sus necesidades. En cuanto a la tecnología utilizada, hemos creado una base de datos empleando MySQL, una herramienta de gestión de bases de datos relacionales muy robusta y popular. Además, hemos desarrollado una interfaz de usuario a través de PHP, HTML y CSS, permitiendo una conexión fluida y eficaz con la base de datos. El resultado ha sido un sistema efectivo y adaptable, que cumple con las necesidades de la institución y proporciona beneficios tanto para los desarrolladores como para sus usuarios.	1	C.E.I Enriqueta Sánchez Vivas		2026-10-19 23:14:05.279033	\N	\N	Implementar un sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas.	t	\N	4	0
259	2026-10-19	Pregrado	En resumen, este PSTI tiene como objetivo de implementar un Sistema de Información de Gestión Administrativo para la “Asociación de Taekwondo del Estado Trujillo” (ASOTKDET), y dicho Sistema de Información, que lleva por nombre de “ASOTKNET”, se trata de un Sistema administrativo que permite facilitar los procesos e información necesaria dentro de la asociación, facilitando así el trabajo de los miembros de la comunidad, especialmente al Presidente y Vicepresidente de la Asociación, también podemos contar aquellos miembros especialistas, es decir, con conocimientos previos en Informática, y estos miembros serían los Árbitros principales de la Asociación. Dicho Sistema, está únicamente creado para la Asociación de Taekwondo del Estado Trujillo” (ASOTKDET), comunidad ubicada en la Avenida las Ferias de la Parroquia San Luis, Municipio Valera del Estado Trujillo, y se trata de una Asociación Civil sin Fines de Lucro organizada para otorgar las herramientas holísticas sobre el Taekwondo a través de sus distinguidos modos actuales de enseñanza, práctica y entrenamiento de tan aclamado Arte Marcial desde el año 1987. Volviendo con el Sistema de Información, este sistema fue realizado por medio de lenguajes de programación, los cuales sería el lenguaje PHP (Hypertext Preprocessor) en el cual fue utilizado para programar el lado del servidor y de todas las funciones necesarias del sistema, también tenemos el lenguaje de Javascript utilizado para diseñar y para escribir las partes Front End (de la parte del cliente) y Back End (de la parte del servidor) del Sistema por medio de consultas AJAX, también no olvidemos de la estructura y el estilo visual que llevara el sistema, siendo los lenguajes HTML5 (Estructura) y CSS3 (Estilo visual). La metodología utilizada para el desarrollo e implementación del sistema, se trata de la metodología XP o Extreme Programming, y se trata de un método que permite la gestión como tal del equipo del PSTI, pautando fechas de entregas o visitas constantes a la comunidad, pero evitando descontentos de parte de esta, además de cambios repentinos en las funciones o requisitos del sistema. Como tal el desarrollo del sistema fue largo y tortuoso, pero al fin y al cabo se logró realizar un sistema exitoso que se adaptara a las peticiones de la comunidad.	1	Asociación de Taekwondo del Estado Trujillo (ASOTKDET)	Sistema de Información, Lenguajes de Programación y Gestión Administrativa	2026-10-19 23:20:21.153746	\N	\N	Desarrollar e implementar el Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo.	t	\N	4	0
299	2026-07-01	Pregrado	Desarrollar e implementar el SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO.	1	ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO	Sistema de Gestión Web, Investigación-Acción	2026-10-20 00:09:16.495903	\N	\N	Desarrollar e implementar el SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO.	t	\N	2	0
260	2026-10-19	Pregrado	En primer lugar, se presenta el resumen para el proyecto socio- tecnológico titulado “Sistema Web para la Farmacia INSUMEDIC”, realizado por los autores Araujo María, Carrasquero Hender, Briceño Jean Luis, Montilla Angeli y Rivero Luis. Específicamente, este proyecto socio-tecnológico se enfoca en la modernización digital de la farmacia “INSUMEDIC C.A”, una empresa con múltiples sedes operativas en el estado Trujillo. Sin embargo, en el contexto actual, la farmacia carecía de una presencia en línea y un catálogo digital, lo cual limitaba su visibilidad, su capacidad para interactuar eficientemente con los clientes y dificultaba el acceso oportuno a información crucial sobre la disponibilidad de medicamentos y los servicios ofrecidos. De este modo, el problema central identificado fue la ausencia de un sistema web y un catálogo en línea para la promoción de productos y servicios. Por consiguiente, la solución seleccionada y objetivo principal del proyecto fue el desarrollo de un Sistema Web para la farmacia “INSUMEDIC C.A”. En cuanto a la metodología, el sistema fue desarrollado empleando la Metodología de Programación Extrema (Extreme Programming - XP). Esta metodología ágil priorizó la adaptabilidad al cambio y se basó en ciclos de desarrollo cortos (iteraciones) definidos por la priorización de historias de usuario, con el fin de entregar el máximo valor al cliente en el menor tiempo posible. Finalmente, el producto terminado es una aplicación web responsive, diseñada para ser compatible con diversos dispositivos móviles, ofreciendo las siguientes funcionalidades clave: Catálogo Completo y Funcionalidad de Búsqueda: Permite la búsqueda avanzada de medicamentos y productos por nombre, proveedores y categorías, facilitando a los clientes encontrar rápidamente lo que necesitan. En conclusión, el proyecto dotó a INSUMEDIC de una plataforma tecnológica robusta y escalable que le permite optimizar la promoción de sus productos, mejorar la experiencia del cliente y posicionarse de manera competitiva en el sector farmacéutico.	1	farmacia INSUMEDIC C.A		2026-10-19 23:21:49.859608	\N	\N	Diseño y desarrollo de un sistema web para la farmacia “INSUMEDIC C.A”.	t	\N	3	0
261	2026-07-01	Pregrado	El proyecto Socio Integrador tiene como eje central la creación de una aplicación web que funcione no solo como un medio de comunicación, sino también como una herramienta estratégica que permita gestionar servicios como la consulta y descarga en línea de resultados, el acceso a información actualizada sobre pruebas disponibles, horarios e indicaciones, y la consolidación de una presencia digital sólida. la entrega de información relevante, el contacto directo con pacientes y la promoción de los servicios del laboratorio. Para el desarrollo de la solución informática, se utilizó una metodología que incluyó técnicas de recolección de información como la observación, la encuesta, la entrevista y la revisión documental, lo que permitió identificar las necesidades específicas del laboratorio y sus pacientes. En cuanto a las herramientas de desarrollo, se emplearon lenguajes de programación como HTML para la estructura del sitio, CSS para el diseño y estilización, y PHP para la lógica del servidor y la gestión de bases de datos. Estas herramientas, combinadas con un enfoque metodológico basado en la recopilación de datos precisa y el análisis de requerimientos, permitieron crear una aplicación web robusta y funcional que garantiza el liderazgo del laboratorio en el sector de salud local, alineándose con las tendencias tecnológicas actuales y mejorando significativamente la calidad del servicio ofrecido a la comunidad. La implementación de la aplicación web generará múltiples beneficios: fortalece la imagen corporativa, facilita el acceso a la información oportuna y a los resultados, mejorando así la satisfacción y confianza del cliente, lo que reforzará el compromiso del laboratorio con la excelencia, la innovación y el bienestar de sus pacientes.	1	LABORATORIO CLÍNICO GENÉSIS C.A		2026-10-19 23:22:40.994981	\N	\N	Desarrollar una aplicación web integral para el Laboratorio Clínico Génesis C.A. en Valera, Estado Trujillo, que optimice su presencia digital, facilite la gestión de servicios clínicos y mejore la interacción con los pacientes.	t	\N	4	0
262	2025-11-01	Pregrado	Este proyecto de investigación, inscrito en el Programa Nacional de Formación en Informática, aborda la crítica problemática de gestión administrativa identificada en el Centro de Alta Tecnología "Dr. Humberto Fernández Morán", donde la dependencia de procesos manuales para el registro y control de pacientes generaba ineficiencias operativas, riesgo de pérdida de información y errores en la transcripción de datos. Mediante un riguroso diagnóstico basado en la Investigación Acción Participativa (IAP), que incluyó múltiples visitas comunitarias y el uso de matrices de análisis FODA y priorización, se diseñó y desarrolló como solución tecnológica el Sistema de Información Integral para la Gestión de Pacientes (SIGP), una aplicación web robusta que, aplicando la metodología DevOps, centraliza y automatiza los procesos de citas, historiales médicos y generación de estadísticas. La implementación exitosa del SIGP no solo optimizó sustancialmente la eficiencia administrativa y la seguridad de los datos, sino que también se posiciona como un aporte significativo al fortalecimiento del sistema de salud pública, reafirmando el compromiso de la universidad con la transferencia tecnológica y la innovación al servicio de la comunidad.	1	CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN"		2026-10-19 23:23:20.301903	\N	\N	Desarrollar e implementar el SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN.	t	\N	4	0
263	2026-07-01	Pregrado	El presente proyecto socio-tecnológico abordó el diseño e implementación de PANTERA-GEST, un sistema web desarrollado para optimizar la gestión administrativa de la empresa "La Pantera PC Store", ubicada en Valera, Estado Trujillo. El diagnóstico situacional inicial evidenció que el control de inventarios, la facturación y el registro de servicios técnicos se ejecutaban de manera empírica y manual, lo que generaba pérdida de información, errores operativos y demoras en la atención al cliente. Para solucionar estas fallas, se aplicó una metodología cualitativa bajo el enfoque de Investigación-Acción Participativa (IAP) combinada con el marco de trabajo ágil Scrum, organizando el desarrollo en ciclos iterativos o sprints. El software se estructuró bajo el patrón arquitectónico Modelo-Vista-Controlador (MVC), garantizando un código modular, seguro y escalable. Las tecnologías empleadas para su construcción fueron de código abierto, destacando HTML5, CSS3 y JavaScript para la interfaz responsiva del frontend, PHP nativo para la lógica del backend y MySQL para la persistencia en el sistema de gestión de bases de datos. Como producto final, se consolidaron módulos operativos para el inventario bimonetario, facturación, atención al cliente (CRM), servicio técnico con trazabilidad de tickets mediante fases, y un módulo analítico de reportes gerenciales en tiempo real con capacidades predictivas de stock. Las pruebas unitarias y de usabilidad de caja negra validaron la estabilidad del sistema con los usuarios reales. Finalmente, el despliegue local y los talleres de transferencia tecnológica aseguraron la adopción exitosa de la plataforma por parte del personal, logrando la modernización, eficiencia operativa y automatización de los procesos comerciales del establecimiento.	1	La Pantera PC STORE	Sistema web, gestión administrativa, control de inventario, servicio técnico, Scrum	2026-10-19 23:24:24.616744	\N	\N	Implementar un sistema web para la gestión administrativa de la Pantera PC Store.	t	\N	4	0
264	2026-03-01	Pregrado	El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en CBIT del Complejo Educativo “Hilario Pizani Anselmi”. Se utilizó la metodología del Investigación-Acción Participativa (IAP) con un enfoque cualitativo y descriptivo, permitiendo la interacción directa con el personal y los estudiantes de la institución. Como técnica de recolección de datos se utilizó la entrevista a la tutora encargada, la Ing. Marlene Sosa. La fase de la elaboración de la propuesta, consistió en un plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: De un total de 20 computadoras, solo 8 se encontraban operativos, mientras que los 12 restantes presentaban fallas críticas. Entre los problemas detectados están disco duros y fuentes de poder dañados, memoria RAM insuficiente y software desactualizado. A pesar de las restricciones al acceso al hardware del CBIT de la institución este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador, realizando mantenimiento preventivo y correctivo a equipos propios del grupo de PST, además de la elaboración de un taller de capacitación técnica dirigida a los estudiantes de la institución para fomentar el cuidado y buen uso de los recursos	1	El Complejo Educativo “Hilario Pizani Anselmi”	Soporte Técnico, Mantenimiento, hardware, software	2026-10-19 23:24:58.424411	\N	\N	Actividades Fecha Duración Recursos/Herramientas Diagnosticar los problemas que presenta el CBIT de la institución Elaboración y Presentación de la propuesta de proyecto 13/06/25 2 horas Hojas, Lápiz Realización del inventario de los equipos del CBIT y toma de nota de los problemas que presentaban	t	\N	1	0
265	2026-04-01	Pregrado	Este proyecto socio-tecnológico surge como una solución integral ante la necesidad de optimizar la infraestructura tecnológica de la Alcaldía Municipal Rafael Rangel. El objetivo general es realizar el servicio de soporte técnico, mantenimiento preventivo y correctivo a los equipos de computación, además de capacitar a los usuarios en el manejo eficiente de los recursos informáticos. Se enmarca en la Investigación-Acción Participativa (IAP), permitiendo una interacción directa entre los investigadores y el personal administrativo. Se utilizaron técnicas de observación directa, entrevistas y diagnósticos técnicos para identificar las fallas recurrentes en los sistemas.. A través de la ejecución del plan de acción, se logró mejorar la operatividad de los equipos, garantizando un entorno digital estable para las funciones administrativas de la institución. Esta intervención no solo resuelve deficiencias técnicas, sino que fortalece la soberanía tecnológica y el compromiso social de los autores, dejando capacidades instaladas en el personal de la alcaldía	1	Alcaldía del Municipio Rafael Rangel	Socio-tecnológico, soporte técnico, mantenimiento preventivo, mantenimiento correctivo, capacitación, infraestructura	2026-10-19 23:24:58.575086	\N	\N	Desarrollar un soporte técnico a equipos de computación y usuarios en la institución señalada.	t	\N	1	0
266	2025-06-01	Pregrado	Este proyecto sociotecnológico se ejecutó en el Infocentro de la Gran Base de Misiones "Hugo Chávez Frías" se encuentra ubicado en La Floresta estado Trujillo, nuestro objetivo central fue optimizar la operatividad de 25 equipos informáticos para garantizar un servicio de calidad mediante un diagnóstico participativo, se identificaron fallas de rendimiento y equipos inoperativos causados por la falta de mantenimiento preventivo. Para solventar esta problemática se implementó un plan de acción que incluyó limpieza física de componentes, reinstalación de sistemas operativos y la migración estratégica a software libre en equipos de bajos recursos Complementariamente, se dictaron talleres de formación técnica para facilitadores y usuarios. Como resultado, se logró recuperar el 96% de la infraestructura tecnológica, beneficiando directamente a más de 500 usuarios mensuales. Para asegurar la sostenibilidad del servicio, se entregó un manual de procedimientos y cronogramas de revisiones periódicas además la activa participación comunitaria fortaleció la apropiación tecnológica y la corresponsabilidad en el cuidado del hardware. En conclusión, esté proyecto no solo restauró la funcionalidad técnica, sino que sentó las bases para una gestión autónoma y duradera, promoviendo la inclusión social mediante el acceso efectivo a las tecnologías de información. DEDICATORIA Este logro va dedicado con todo nuestro corazón a nuestras familias que con su amor y sacrificio nos han sostenido en cada paso de este camino, a la comunidad de La Floresta que nos abrió sus puertas y nos permitió aprender de su realidad, al personal del Infocentro por su confianza y disposición para trabajar juntos, y a todos aquellos que creen en el poder de la tecnología para transformar vidas. También dedicamos este esfuerzo a nuestros compañeros de estudio y a quienes día a día luchan por una educación inclusiva y de calidad, porque este proyecto es fruto del trabajo en equipo y de la convicción de que otro mundo es posible cuando unimos conocimientos y voluntades	1	Fundación Infocentro Gran Base de Misiones por la Paz Hugo Chávez Frías La Floresta		2026-10-19 23:24:58.673105	\N	\N	Realizar soporte técnico a los equipos y brindar capacitación a los usuarios de la comunidad de La Floresta.	t	\N	1	0
296	2025-11-01	Pregrado	El objetivo general del proyecto Socio Tecnológico fue realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”, y proponer mejoras mediante el diseño de una solución técnica. Se aplicó una investigación de campo, de carácter aplicativo y descriptivo, utilizando el método de análisis para caracterizar el entorno y ordenar los elementos involucrados. Se emplearon técnicas como la entrevista, el cuestionario y la observación directa. El proyecto se desarrolló con la metodología Cisco, la cual está constituida por seis fases de realización: Preparación, Planeación, Diseño, Implementación, Operación y Optimización. Los resultados evidenciaron la necesidad de mantenimiento en el laboratorio II y permitieron aplicar conocimientos adquiridos en redes.	1	ESCUELA TÉCNICA “MADRE RAFOLS	ethernet, conexión, red, cables, laboratorio, mantenimiento	2026-10-19 23:48:55.213995	\N	\N	Realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”.	t	\N	2	0
268	2026-04-01	Pregrado	El presente proyecto socio-tecnológico se centró en recuperar y optimizar la infraestructura tecnológica del Ciber Café Poncho S.A., la cual se encontraba en un estado crítico que afectaba su funcionamiento. Al mismo tiempo, organizamos una jornada de capacitación técnica para los compañeros de nuestra universidad, buscando fomentar la transferencia de conocimiento sobre cómo cuidar y mantener sus propios equipos. Durante el diagnóstico en el local, notamos que el 80% de las computadoras no funcionaban, por lo que aplicamos un plan de mantenimiento preventivo profundo de hardware y limpieza de sistemas operativos, logrando una optimización que dejó todas las unidades operativas antes del cierre del establecimiento. Todo este trabajo se realizó bajo una metodología socio- tecnológica con un paradigma cualitativo, utilizando un diseño de campo no experimental y el enfoque de investigación-acción participativa (IAP), lo que nos permitió aplicar la teoría en un entorno real y brindar un soporte técnico efectivo ante imprevistos. En conclusión, demostramos que combinar el mantenimiento profesional con la educación de los usuarios es la mejor forma de garantizar la sostenibilidad técnica tanto en los negocios como en la universidad	1	Ciber Café Poncho S.A	Mantenimiento preventivo, infraestructura	2026-10-19 23:24:58.860703	\N	\N	Implementar un plan de soporte técnico integral en el Ciber Café Poncho S.A., enfocado en el mantenimiento, diagnóstico y optimización de los equipos de computación, para subsanar las fallas técnicas y desactualizaciones, garantizando así un servicio digital estable, eficiente y de alto rendimiento que satisfaga las necesidades informáticas de la comunidad de Plata 3.	t	\N	1	0
269	2026-03-01	Pregrado	El Proyecto Socio-Tecnológico (PST) se fundamenta como el núcleo de integración de saberes teóricos y prácticos orientados a resolver problemas reales. Este proyecto tiene como objetivo general desarrollar el soporte técnico, alargar la vida útil de los equipos computacionales del Laboratorio 2 de Informática (UPTTMBI, Núcleo La Beatriz) y capacitar a sus usuarios. La metodología se basó en la Investigación-Acción Participativa (IAP) con un diseño de campo no experimental. Destaca el uso de la investigación aplicada, cuyo propósito fue utilizar los conocimientos técnicos del PNFI para abordar un problema práctico, ejecutando protocolos de mantenimiento preventivo y correctivo junto con capacitaciones. El diagnóstico evidenció que los 14 equipos de muestra presentaban deficiencias (86% lógicas y 14% físicas) por polvo, sistemas obsoletos y desconocimiento operativo. Con la aplicación directa de estas soluciones teóricas a la realidad del laboratorio, se logró restaurar la operatividad total de las computadoras. Esto garantizó la optimización de los recursos informáticos, prolongó su vida útil y mejoró significativamente el desarrollo de los procesos académicos	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo Dr.  Pablo Viloria	Soporte técnico, Mantenimiento preventivo, Mantenimiento correctivo, Capacitación, Hardware, Software	2026-10-19 23:24:59.01139	\N	\N	Dotar a los usuarios de las habilidades y los conocimientos necesarios para navegar por el sistema, aprovechar al máximo sus funciones y resolver problemas comunes que puedan surgir.	t	\N	1	0
271	2026-03-01	Pregrado	El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en la Escuela Nueva Generación Eloisa Fonseca. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó el árbol de problemas el cual explica el problema central junto con sus causas y consecuencias .Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: Se identificó solo 6 equipos operativos de un total de 12, se ejecutó un plan de acción enfocado en las 6 unidades restantes que se encontraban fuera de servicio. Gracias a estas labores, se logró una recuperación operativa 4 de 6 equipos críticos, reintegrando 4 de ellos al sistema en óptimo estado funcional .Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	ESCUELA NUEVA ELOISA FONSECA		2026-10-19 23:24:59.21358	\N	\N	Desarrollar Mantenimiento Preventivo y Correctivo a Equipos de Computación Dictar taller teórico-práctico sobre Hardware y Software.	t	\N	1	0
272	2025-07-01	Pregrado	Las tecnologías de información están presentes en todos los ámbitos de la sociedad, son herramientas que apoyan las actividades de un analista de sistemas quien es un profesional capacitado para planificar, desarrollar sistemas de información automatizados, diseñar aplicaciones Web, móviles, según las necesidades y requerimientos de las instituciones del gobierno, instituciones financieras, empresas u organizaciones. El analista en una empresa puede asumir diferentes roles, por ejemplo, puede trabajar como programador, con el tiempo cuando haya adquirido mucha experiencia puede ser un líder de proyecto, jefe del área de sistemas o computación, utiliza la tecnología, es decir lenguajes de programación, diferentes entornos operativos, por consiguiente, debe estar actualizado con los nuevos avances. Las nuevas tecnologías de información son herramientas o recursos que permiten hacer las actividades de manera diferente, más rápida y eficiente, facilitan cualquier trabajo, actividad o tarea, disminuyen esfuerzo, costos, tiempo. Entre las nuevas tecnologías se encuentra la inteligencia artificial, metaverso, blockchain, cloud, big data, entre los lenguajes de programación se encuentra Python, Java Script, etc. Hoy día el analista de sistemas debe actualizar sus conocimientos para adquirir competencias necesarias que le permitan ingresar y mantenerse en el mercado laboral bien sea presencial u online, el analista debe investigar hoy más que nunca para implementar los cambios necesarios ya que se observa cómo cambia y evoluciona la tecnología tan rápido, y así no quedar obsoletos en conocimiento, y ser un profesional competitivo. En el caso específico del analista de sistemas, las tecnologías de información apoyan o ayudan al analista en sus funciones o en las actividades, porque permiten mejorar o innovar los sistemas de información existentes, también permiten mejorar los procesos administrativos, por ejemplo, incorporando nuevo hardware y software. El analista puede trabajar desde su casa utilizando la tecnología de manera online, se puede comunicar con su equipo de trabajo, en cualquier parte el mundo, a través de videos llamadas, a través de las aplicaciones que permiten hacer conferencias. Almacenar gran cantidad de información en la nube y compartirla. Los analistas pueden desarrollar aplicaciones Web, móviles, utilizando diferentes lenguajes de programación, diseñar aplicaciones basadas en la nube, diseñar sistemas de aprendizaje utilizando la inteligencia artificial, muy importante la robótica que permite crear robots y sistemas automatizados para facilitar y hacer más eficientes las tareas, actividades, la tecnología ayuda automatizar los procesos de las empresas o instituciones por ejemplo en Venezuela, la banca móvil, Seniat, SAIME,CNE, INTT, etc, que permiten hacer las transacciones y tramites vía celular, facilitando la comunicación, rompiendo barreras de espacio y tiempo, ofrece información en tiempo real, ayuda al analista hacer más productivo ,eficiente en el trabajo. Muy importante o a manera de cierre, en mi opinión, la tecnología es una herramienta muy importante, valiosa en la actualidad, el analista de sistemas tiene que adaptarse a los cambios tecnológicos, especializarse, innovar, investigar y crear soluciones informáticas, innovadoras, utilizando las nuevas tecnologías de acuerdo a los requerimientos y exigencias actuales, para que la empresa y uno como analista ser competitivos y mantenerse en el mercado laboral presencial u online	1	Empresa “IP SISTEMAS		2026-10-19 23:24:59.308045	\N	\N	Analizar y mejorar el área de soporte técnico de la empresa IP Sistemas, mediante la identificación de fallas en los equipos de computación y en la gestión tecnológica.	t	\N	1	0
273	2026-04-01	Pregrado	El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en el Despacho de Carga Suroccidente. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, la cual se complementó con la metodología cualitativa bajo el enfoque de investigación acción participativa. Proyecto factible, de carácter descriptivo con una acción transformadora, se realizó en tres fases. Como técnica de recolección de datos se utilizó la entrevista no estructurada y como instrumento un cuestionario de preguntas cerradas. La fase de la elaboración de la propuesta consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: los equipos presentaban saturación por acumulación de polvo y degradación de la pasta térmica, lo que genera un aumento en la temperatura interna del hardware; asimismo, se detectó lentitud por la saturación de archivos temporales y falta de hábitos preventivos en los usuarios para el cuidado de los componentes. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	Despacho de Carga Sur Occidente	Mantenimiento, hardware, software	2026-10-19 23:24:59.418966	\N	\N	\N	t	\N	1	0
274	2026-04-01	Pregrado	El objetivo general del proyecto socio tecnológico fue aplicar Soporte técnico a equipos y usuarios en la Clinica Uga , Valera Estado Trujillo. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cualitativa. Proyecto de investigación acción participativa, de carácter descriptiva, se realizó en cuatro fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: No existe un sistema de seguimiento y control de las computadoras, ocasionando el deterioro de algunos equipos por falta de mantenimiento preventivo y dotación de equipos actualizados. También se observó el desconocimiento de los usuarios en el manejo de los equipos. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	Clinica Uga	Computadoras, mantenimiento, usuarios, hardware, software	2026-10-19 23:24:59.537726	\N	\N	Aplicar el Soporte técnico a equipo y usuarios de la Clínica UGA C.A.,	t	\N	1	0
275	2026-04-01	Pregrado	El objetivo general del proyecto socio tecnológico fue la realización del Soporte técnico a usuarios y equipos de computación en la Escuela Técnica Comercial “Pedro García Leal”. Se empleó la metodología del marco lógico para establecer los problemas, causas y consecuencias, se utilizó la metodología cuantitativa, proyecto factible, de carácter descriptivo, se realizó en tres fases. Como técnica de recolección de datos se utilizaron la entrevista y la encuesta, como instrumento el cuestionario. La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, se dictó el taller de capacitación a los usuarios. Los resultados obtenidos evidencian falta de mantenimiento en los equipos de computación, pasta térmica deteriorada y batería del CMOS/BIOS vencido. Se realizó la instalación de antivirus, instalación de software de propietario (Windows y Microsoft Office), del mismo modo se hizo necesario reemplazar componentes a los equipos de computación. Este proyecto permitió a los estudiantes aplicar los conocimientos adquiridos en el área de arquitectura del computador, Algoritmo y Programación y Matematicas I	1	Institución Educativa Técnica Comercial	hardware, mantenimiento a equipos, software, soporte a usuarios	2026-10-19 23:24:59.65562	\N	\N	Brindar soporte técnico a usuarios y equipos de computación de la Escuela Técnica Comercial “Pedro García Leal” del municipio Valera del Estado Trujillo.	t	\N	1	0
276	2026-04-01	Pregrado	El objetivo general del proyecto socio tecnológico es brindar un plan de soporte técnico a equipos de computación y a usuarios del Ministerio del Poder Popular para el Transporte, Sede Trujillo, con el fin de mejorar la eficiencia operativa y la calidad de los servicios prestados por la institución. Se utilizó la metodología del marco lógico para determinar los problemas, causas, consecuencias, presentando las propuestas en un plan de acción como herramienta guía para la ejecución de actividades de soporte técnico a usuarios y equipos que permitieran el cumplimiento de objetivos en el tiempo estimado. Se tomó la Investigación Acción Participativa (IAP) enfocada a la acción transformadora, apoyada en la Investigación Tecnológica Aplicada, optando por un diseño de Investigación No experimental descriptivo con un enfoque cualitativo y dentro de la IAP. Como técnica de recolección de datos se utilizó la entrevista, además de la observación directa en las oficinas. Los resultados obtenidos evidencian las necesidades de atención en equipos técnicos y desempeño humano, además de la oportunidad de contribuir con la solución a las fallas detectadas y la capacitación personalizada al personal de la institución, evidenciando que la gestión tecnológica requiere un ciclo constante de revisión y actualización para no recaer en la inoperatividad. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	Comunidad / Organización No Específicamente Nombrada	Soporte técnico, soporte a usuarios	2026-10-19 23:24:59.755223	\N	\N	Brindar un plan de soporte técnico a equipos de computación y a usuarios del Ministerio del Poder Popular para el Transporte, sede Trujillo, con el fin de mejorar la eficiencia operativa y la calidad de los servicios prestados por la institución.	t	\N	1	0
278	2025-04-20	Pregrado	El objetivo general del proyecto socio tecnológico fue realizar Soporte técnico a equipos de computación y usuarios en la Escuela de Emprendimiento Valera del Estado Trujillo. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cualitativa. El proyecto, que tiene una naturaleza descriptiva y es factible, se llevó a cabo en tres fases. Como técnica de recolección de datos se utilizó la entrevista y como instrumento la toma de notas, y de manera adicional la observación de participantes, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian que: las computadoras del lugar son modelos antiguos frecuentando su mantenimiento, falta de actualizaciones de los componentes de Windows/Linux ya que por la poca capacidad de los equipos no es recomendable las actualizaciones continuas, monitores con poca visualización, computadoras inactivas y que los equipos tienen poca capacidad. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	ESCUELA DE EMPRENDIMIENTO VALERA	Mantenimiento, hardware, software	2026-10-19 23:24:59.986036	\N	\N	Desarrollar soporte técnico a las computadoras y taller de capacitación a los usuarios de la Escuela de Emprendimiento Valera.	t	\N	1	0
279	2026-07-01	Pregrado	El presente proyecto se inserta en esta dinámica de transformación, orientando sus esfuerzos hacia el diseño, desarrollo e implantación de un Sistema Didáctico e Interactivo para la Enseñanza de Matemática Básica en la Unidad Educativa “José Antonio Velasco”, ubicada en la Calle Principal de San Genaro, Parroquia Carvajal del Municipio San Rafael de Carvajal, en el Estado Trujillo. Esta institución posee una trayectoria histórica que se remonta formalmente a la década de 1940 y que ha evolucionado significativamente hasta convertirse en una organización educativa eficiente, orientada a la formación integral de niños desde la educación inicial hasta el sexto grado de educación primaria; a pesar de contar con una estructura organizativa sólida y un Centro de Informática y Telemática (CBIT).	1	ESCUELA “JOSE ANTONIO VELASCO"	Matemática básica, Metodología Scrum, Sistema interactivo, Software educativo	2026-10-19 23:26:33.99831	\N	\N	Desarrollar e implementar el SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO.	t	\N	2	0
280	2026-10-19	Pregrado	El objetivo principal de este proyecto es fortalecer las enseñanzas de matemáticas en estudiantes de primer año del Liceo “Hilario Pizani Anselmi”, mediante el desarrollo de un Software Educativo offline que será implementado en el Centro Bolivariano de Informática y Telemática (CBIT). Esta herramienta busca mejorar el proceso de enseñanza-aprendizaje en el área de matemática, ofreciendo contenidos estructurados, interactivos y accesibles sin necesidad de conexión a internet, especialmente en contextos donde la red wifi presenta limitaciones. El software estará compuesto por módulos interactivos alineados al currículo nacional, con énfasis en operaciones básicas, lógica matemática y resolución de problemas. Incluirá ejercicios autocorregibles, material complementario extraído de los libros de la Colección Bicentenaria —en especial el texto “Matemática para la Vida” de primer año—, así como recursos multimedia y guías pedagógicas para docentes que faciliten su integración en el aula. El proyecto contempla un diagnóstico situacional, justificación, impacto social, fundamentos teóricos y un plan de acción detallado. Se espera que esta iniciativa incremente la disponibilidad de herramientas digitales adaptadas al nivel académico, promueva el aprendizaje autónomo y guiado, y fomente una cultura de cuidado y aprovechamiento de los recursos tecnológicos disponibles.	1	LICEO “HILARIO PIZANI ANSELMI		2026-10-19 23:33:45.603171	\N	\N	Implementar un Software Educativo Offline para la enseñanza de Matemáticas de Primer año en el Liceo “Hilario Pizani Anselmi”	t	\N	2	0
281	2025-11-01	Pregrado	El presente proyecto socio-tecnológico consistió en la implementación de un software educativo para optimizar la enseñanza de Programación y Diseño de Software y Redes en los estudiantes de 4to año de Telemática de la E.T. "Pedro García Leal". La investigación, bajo la metodología de Investigación Acción Participativa, empleó entrevistas, observación y encuestas que identificaron necesidades como escasez de recursos didácticos, equipos obsoletos y falta de materiales interactivos. Como solución, se desarrolló una herramienta digital que integra contenidos teóricos, evaluaciones, retos de conocimiento y herramientas de autoaprendizaje. El desarrollo técnico utilizó Python como lenguaje de programación, Tkinter para la interfaz gráfica, SQLite3 como gestor de base de datos y Visual Studio Code como entorno de desarrollo, siguiendo las fases de la metodología de Álvaro Galvis (diseño, implementación, prueba y evaluación participativa). Las pruebas funcionales en el laboratorio de informática contaron con la participación de docentes y estudiantes, quienes manifestaron su satisfacción con la utilidad y facilidad de uso del software. Paralelamente, se realizó un taller de capacitación y se entregó el manual de usuario en formato digital. Los logros obtenidos incluyeron el fortalecimiento del aprendizaje autónomo, el aumento de la motivación estudiantil mediante recursos interactivos y la mejora de las estrategias pedagógicas docentes. Como recomendaciones finales, se sugiere mantener y actualizar periódicamente el software, incorporar una red local con servidor central para permitir su uso simultáneo, y extender su aplicación a otras menciones y años académicos dentro de la institución educativa.	1	E.T. “Pedro García Leal”		2026-10-19 23:36:15.396849	\N	\N	Implementar un software educativo para la enseñanza de los RTP de Programación y Diseño de Software y Redes dirigido a los estudiantes de 4to año Telemática A de la E.T. “Pedro García Leal”.	t	\N	3	0
277	2026-04-01	Pregrado	Este proyecto socio tecnológico es la búsqueda de una solución integrada comunidad – universidad que tiene como objetivo general realizar el servicio del soporte técnico en el Laboratorio 1 del Programa Nacional en Informática de la Universidad Politécnica Territorial Mario Briceño Iragorry, mediante la introducción de estrategias efectivas que garanticen el correcto funcionamiento de los equipos computacionales y la capacitación efectiva de los usuarios. Además, se llevó a cabo bajo el enfoque metodológico de investigación de acción participativa con un diseño mixto que integra técnicas tecnológicas y descriptivas para el abordaje sistemático de las fallas de hardware y software detectadas en el contexto universitario. Asimismo, en función de esto y bajo los requerimientos de la oportunidad el alcance del proyecto cumplió sus objetivos mediante la ejecución de un plan de mantenimiento preventivo y correctivo que optimizó la infraestructura tecnológica, asegurando la estabilidad de los sistemas y fortaleciendo el proceso de enseñanza para la comunidad del PNF en Informática, en las capacitaciones se brindó la información necesaria para los usuarios en el mantenimiento preventivo y correctivo. En este sentido, este proyecto permite consolidar un entorno digital seguro, eficiente y actualizado según los estándares institucionales	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” (UPTTMBI)	Socio tecnológico, soporte técnico, mantenimiento preventivo y correctivo, capacitación usuarios	2026-10-19 23:24:59.88587	\N	\N	Realizar el servicio de soporte técnico integral en el Laboratorio 1, garantizando el correcto funcionamiento de los equipos.	t	\N	1	1
282	2025-11-01	Pregrado	La investigación, alineada con el PNF en Informática (UPT-Trujillo), tuvo como objetivo la implementación de una aplicación web modular para optimizar la gestión académica de calificaciones en el Liceo Andrés Bello. Mediante un diagnóstico situacional in situ (triangulación de datos, observación y análisis documental), se identificó la problemática central: latencia operativa e inconsistencia en la data debido a procesos manuales y obsolescencia tecnológica.La metodología IAP (Investigación-Acción Participativa) guio la co-construcción iterativa del sistema, involucrando al stakeholder clave. El desarrollo técnico incluyó la conceptualización de la arquitectura Cliente-Servidor, la programación orientada a objetos de módulos transaccionales (registro/consulta), la integración de algoritmos de cálculo automatizado, y la implementación de protocolos de seguridad (autenticación basada en roles y cifrado SSL/TLS). Los resultados demuestran empíricamente que el sistema reduce los tiempos de procesamiento (hasta en un $X\\%$), minimiza el margen de error en el data entry y fortalece la trazabilidad y transparencia de la información institucional. Se concluye que esta solución tecnológica constituye un modelo de referencia (benchmark) para la modernización de la infraestructura digital educativa en entornos rurales, impulsando la eficiencia y calidad de la gestión escolar	1	LICEO ANDRÉS BELLO	Aplicación Web, Gestión Académica, Optimizacion de Procesos, IAP, Trazabilidad de Datos	2026-10-19 23:44:28.850666	\N	\N	Desarrollar e implementar el APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO.	t	\N	2	0
283	2025-11-01	Pregrado	El presente informe demuestra todo el desarrollo llevado a cabo para la elaboración de una Aplicación Web Informativa a base del uso de distintas herramientas y métodos para su logro, tomando como base de dicha Aplicación, el contenido del Parque Botánico, Flora, Fauna y sus Áreas, para la creación de un sitio web que esté disponible para el público general y pueda informarse desde la comodidad de sus casas o escritorios, todo lo que la comunidad de INPARQUES en el municipio Valera tiene para ofrecer, sumando también un importante acceso de Administración para el personal de la comunidad, con el objetivo de permitir que la página sea actualizable cada vez que sea necesario, aplicando incluso herramientas fuera de lo común con el desarrollo web como Canva para lograr la meta de ofrecer un producto de calidad	1	Parque de Recreación Botánico La Beatriz		2026-10-19 23:44:28.921378	\N	\N	Implementar en el Parque de Recreación Botánico La Beatriz una Aplicación Web que conserve el contenido a mostrar de la comunidad.	t	\N	2	0
284	2025-11-01	Pregrado	El proyecto socio tecnológico “Aplicación Web para el Complejo Educativo Pedro Pablo Paredes” tuvo como objetivo desarrollar un portal institucional que facilitara la comunicación entre la institución y la comunidad educativa. La ausencia de un sitio web oficial dificulta la difusión de información y la visibilidad de las actividades. Se aplicó la metodología del marco lógico, complementada con un enfoque cuantitativo, y se ejecutaron tres fases: diagnóstico de necesidades, diseño y desarrollo de la aplicación, además de capacitar a los usuarios. La propuesta incluyó la creación de una aplicación web para publicar noticias, actividades e imágenes, junto con un taller formativo. Los resultados demostraron que la implementación fortaleció la comunicación y contribuyó a la modernización tecnológica del Complejo Educativo	1	Institución educativa de nivel medio general y técnica “Complejo Educativo Pedro Pablo Paredes”	Aplicación web, Comunicación, Educación, Información, Tecnología	2026-10-19 23:44:29.004372	\N	\N	Implementar una aplicación web para el Complejo Educativo Pedro Pablo Paredes.	t	\N	2	0
285	2026-07-01	Pregrado	El presente Proyecto Socio-Tecnológico tuvo como objetivo desarrollar una aplicación web para la gestión de ventas del Auto Servicio El Triángulo C.A., ubicado en Valera, Estado Trujillo, con el fin de automatizar los procesos de inventario, ventas y atención al cliente, los cuales se realizaban de manera manual y generaban inconsistencias en los registros y baja eficiencia operativa. La investigación se enmarcó en un paradigma mixto, utilizando la Investigación-Acción Participativa (IAP) como enfoque metodológico, complementada con la metodología ágil Extreme Programming (XP) para el desarrollo del software, lo que permitió una adaptación continua a los requerimientos de la comunidad. El sistema fue construido con tecnologías de código abierto (PHP, MySQL, JavaScript, HTML5 y CSS3) e integra módulos de catálogo digital, gestión de inventario, proceso de compras, historial de clientes, panel administrativo y reportes estadísticos. Los resultados obtenidos, validados mediante pruebas de usabilidad con el personal y clientes frecuentes, evidenciaron una percepción altamente favorable, confirmando que la aplicación cumple con los principios de facilidad de aprendizaje, eficiencia y satisfacción del usuario. Se concluye que el proyecto cumplió con todos los objetivos planteados, generando un impacto positivo en la comunidad al proporcionar una herramienta tecnológica que optimiza los procesos comerciales del autoservicio	1	Auto Servicio El Triángulo C.A	Aplicación web, comercio electrónico, gestión de ventas, inventario	2026-10-19 23:44:29.078593	\N	\N	Desarrollar e implementar el APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A.	t	\N	3	0
286	2025-11-01	Pregrado	El presente proyecto tiene como finalidad desarrollar e implementar una aplicación web para la U.E. "Eduardo Blanco ubicada en la parroquia Escuque, municipio Escuque, en respuesta a la carencia de un sitio web en la institución. Por lo tanto, la solución pertinente fue la implementación de una aplicación web institucional diseñada para centralizar la información, mejorar la visibilidad y facilitar el acceso a la comunidad educativa. Se utilizó el Diseño Centrado en el Usuario (UCD). Esta metodología fue crucial para asegurar que la aplicación respondiera directamente a las necesidades y expectativas de los usuarios finales (estudiantes, padres y personal), priorizando la usabilidad y una experiencia de usuario positiva. La implementación generó beneficios tangibles, como una notable mejora en la imagen institucional y la optimización de los canales de comunicación. El proyecto confirma la pertinencia de las soluciones web para instituciones educativas. El principal aprendizaje fue la validación de la metodología UCD como garantía de relevancia y adopción de la herramienta. La aplicación web no solo solventa la necesidad inicial, sino que aporta un canal de comunicación moderno y escalable, fortaleciendo la presencia digital de la U.E. “Eduardo Blanco” y mejorando la interacción con su comunidad. Asimismo, facilita el acceso a la comunidad a información clave y, su desarrollo responde a la necesidad de consolidar una identidad digital institucional clara, segura y alineada con los valores de la U.E. "Eduardo Blanco"	1	UNIDAD EDUCATIVA “EDUARDO BLANCO	Aplicación web, Gestión, Comunidad, UCD, identidad digital	2026-10-19 23:44:29.1629	\N	\N	Desarrollar e implementar una aplicación web para la U.E. "Eduardo Blanco ubicada en la parroquia Escuque, municipio Escuque, en respuesta a la carencia de un sitio web en la institución.	t	\N	2	0
287	2025-11-01	Pregrado	El proyecto titulado “Pagina Web en el Grupo Educativo Eloísa Fonseca” tiene como misión principal el desarrollo de un portal web institucional para el Grupo Educativo “Eloísa Fonseca”, con el objetivo de facilitar la gestión de la información oficial entre la institución y la comunidad que forma parte de la misma. Este, siguiendo los parámetros de recolección de información y de acción de una metodología cuantitativa. En la actualidad la escuela sufre de diferentes problemáticas, como el deterioro de la infraestructura, equipos desactualizados y la inexistencia de un sitio web oficial que sea de fácil acceso al cuerpo directivo, limitando la capacidad de distribución de información. Esta propuesta tiene como fin implementar un portal web que permita la transmisión de información relevante, como lo son las actividades semanales, asambleas y simulacros. La realización de este proyecto no solo mejorará la comunicación institución-comunidad sino que hará posible una optimización de la organización interna y aumentará la participación comunitaria. La creación del sitio web será considerado como el primer paso hacia la resolución de las deficiencias tecnológicas a las que se enfrenta la escuela; garantizando el cumplimiento de la visión de la Eloisa Fonseca de impartir una educación de calidad	1	Grupo Educativo “Eloisa Fonseca”	portal web, información, comunicación, educación, tecnología	2026-10-19 23:44:29.235168	\N	\N	Implementar una página web para el Grupo educativo “Eloísa Fonseca”	t	\N	2	0
288	2025-11-01	Pregrado	El proyecto se centra en el desarrollo de una página web institucional para la Unidad Educativa Fe y Alegría “Gonzalo Senior”, con el objetivo de mejorar la comunicación y la gestión de información entre la institución y su comunidad educativa. Actualmente, la institución enfrenta diversas problemáticas, entre las que destacan el deterioro de su infraestructura, los mínimos conocimientos tecnológicos que tiene el personal de la institución y la ausencia de una página web informativa, lo que limita su capacidad para informar a estudiantes y representantes sobre actividades académicas y administrativas. La propuesta busca implementar un sitio web que permite publicar información relevante como la misión, visión, ubicación e historia, procesos de preinscripción y eventos, facilitando así el acceso a la información sin necesidad de depender de sitios externos. Este desarrollo no solo optimizará la comunicación, sino que también contribuirá a una mejor organización interna y a un incremento en la participación comunitaria. La implementación de la página web se considera una solución clave para abordar las deficiencias en el uso de herramientas tecnológicas en la institución, garantizando un espacio digital seguro y personalizado que refleje la identidad de la Unidad Educativa Fe y Alegría “Gonzalo Senior”	1	Unidad Educativa Fé y Alegría “Gonzalo Senior	página web, educativa, comunicación, tecnología, gestión	2026-10-19 23:44:29.31214	\N	\N	Implementar una Página Web Institucional para la U.E Fe y Alegría “Gonzalo Senior”	t	\N	2	0
303	2025-11-01	Pregrado	El objetivo general del proyecto Socio Tecnológico fue realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”, y proponer mejoras mediante el diseño de una solución técnica. Se aplicó una investigación de campo, de carácter aplicativo y descriptivo, utilizando el método de análisis para caracterizar el entorno y ordenar los elementos involucrados. Se emplearon técnicas como la entrevista, el cuestionario y la observación directa. El proyecto se desarrolló con la metodología Cisco, la cual está constituida por seis fases de realización: Preparación, Planeación, Diseño, Implementación, Operación y Optimización. Los resultados evidenciaron la necesidad de mantenimiento en el laboratorio II y permitieron aplicar conocimientos adquiridos en redes	1	ESCUELA TÉCNICA “MADRE RAFOLS	ethernet, conexión, red, cables, laboratorio, mantenimiento	2026-10-20 00:09:56.340865	\N	\N	Realizar mantenimiento de la red local del laboratorio II en la Escuela Técnica “Madre Rafols”.	t	\N	2	0
289	2025-11-01	Pregrado	Este proyecto consistió en el diseño e implementación de una página web institucional para el Colegio Los Cedros, con el objetivo de fortalecer su presencia digital y mejorar la comunicación con la comunidad educativa. El diagnóstico situacional identificó que la página web existente presentaba deficiencias de actualización y diseño, limitando el acceso a información importante. Como solución, se desarrolló un sitio web moderno que incluye información institucional, eventos y una galería de imágenes. Para lograrlo, se establecieron objetivos específicos como diagnosticar las problemáticas, determinar requerimientos, diseñar módulos, codificar, implantar y capacitar al personal. La metodología Extreme Programming (XP) fue adoptada por su flexibilidad y enfoque colaborativo, facilitando una respuesta rápida a cambios mediante prácticas como desarrollo iterativo, integración continua y retroalimentación constante. Esto permitió a los estudiantes aplicar sus conocimientos en informática, creando una solución tecnológica útil que fortalece el vínculo entre la universidad y la institución educativa, garantizando un producto final alineado con las expectativas de los usuarios	1	COLEGIO LOS CEDROS	Página Web, programación, software	2026-10-19 23:44:29.407981	\N	\N	Implementar una Página Web para el Colegio Los Cedros.	t	\N	2	0
305	2025-11-01	Pregrado	El desarrollo del proyecto tiene como principal objetivo diseñar e implementar una infraestructura de red inalámbrica (WLAN), que permita una conectividad eficiente, segura y escalable para estudiantes, docentes y personal administrativo. Por otra parte, permite ampliar la cobertura de internet a toda la institución además, ayudar a los profesores a reducir el tiempo de transmisión de información. En este sentido y, tomando la topología hibrida WLAN como mejor alternativa para la solución de los problemas de conexión en la institución, se determinaron un punto estratégico del router para su máximo aprovechamiento en cuanto al alcance y rendimiento de la conexión. De igual manera, con la ayuda de simuladores (cisco packet tracer), se obtiene resultados más específicos en cuanto a la conexión de cada uno de los equipos que se encuentren conectados en la red, realizando pruebas de cobertura y velocidad para garantizar una conexión estable y trayendo grandes beneficios para el personal y estudiantes de la institución. Además, asegurando un acceso equitativo para toda la comunidad, mejoras en la calidad pedagógica gracias a la utilización de herramientas educativas y plataformas digitales.	1	ESCUELA “TOSTA GARCIA"	Red, WLAN, Inalámbrica, Conexión	2026-10-20 00:16:09.46605	\N	\N	Desarrollar e implementar el SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA".	t	\N	2	0
290	2025-11-01	Pregrado	El presente proyecto tuvo como objetivo general fortalecer la conectividad digital del municipio Escuque, facilitar el acceso a información sobre su dinámica social y cultural, y fomentar la participación ciudadana mediante la creación de una página web municipal con sección dedicada al Infocentro. Los objetivos específicos incluyeron: determinar los requerimientos técnicos, diseñar y desarrollar la plataforma, realizar pruebas de funcionamiento, elaborar el manual de usuario y capacitar al personal del Infocentro para su uso eficiente. La comunidad atendida fue el Infocentro de Escuque, institución que carecía de un canal digital oficial para centralizar y difundir contenidos relevantes. La necesidad detectada se centró en la ausencia de una herramienta tecnológica que permitiera visibilizar la gestión municipal, el patrimonio cultural y las actividades comunitarias, lo cual limitaba la comunicación institucional y ampliaba la brecha digital. La solución tecnológica consistió en implementar una página web como plataforma estratégica de gestión informativa, turística y comunitaria. Se utilizó la metodología ágil Scrum, permitiendo una planificación iterativa, colaborativa y orientada a resultados. Entre los beneficios obtenidos destacan la modernización digital del municipio, el fortalecimiento del Infocentro como ente transparente y activo, y el posicionamiento de Escuque como destino conectado. La experiencia evidenció que el desarrollo de plataformas digitales inclusivas promueve la interacción social, mejora la calidad de vida y contribuye al desarrollo local sostenible, consolidando el vínculo entre tecnología, comunidad e innovación	1	Nombre de la organización		2026-10-19 23:44:29.503237	\N	\N	Fortalecer la conectividad digital del municipio, simplificar la obtención de información sobre su dinámica social y cultural, y promover una mayor participación ciudadana.	t	\N	2	0
291	2025-04-03	Pregrado	El objetivo general de este proyecto socio-tecnológico se centra en la implementación de una página web para el Centro de Educación Inicial “Consuelo Navas Tovar”, con el propósito de mejorar la comunicación y la gestión de información entre la institución y su comunidad educativa. Actualmente, el centro enfrenta diversas problemáticas, entre las que destacan el deterioro de su infraestructura, especialmente en el salón de usos múltiples, la falta de un laboratorio de informática y la ausencia de una página web, lo que limita su capacidad para informar a los representantes sobre actividades académicas y administrativas del preescolar. La propuesta busca desarrollar una plataforma digital que permite publicar información relevante, como datos generales de la institución, eventos y actividades académicas, facilitando así el acceso sin necesidad de visitas presenciales. Este desarrollo no sólo optimizará la comunicación, sino que también contribuirá a una mejor organización interna y a un incremento en la participación comunitaria. La implementación de la página web se considera una solución clave para abordar las deficiencias en el uso de herramientas tecnológicas en la institución, garantizando un espacio digital seguro y personalizado que refleje la identidad del Centro de Educación Inicial” Consuelo Navas Tovar”	1	CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR	página web, tecnología, gestión, institucional, comunicación	2026-10-19 23:44:29.576492	\N	\N	Implementar una página web para el Centro de Educación Inicial “Consuelo Navas Tovar” del Municipio Valera, Estado Trujillo.	t	\N	2	0
292	2026-07-01	Pregrado	En virtud de lo expuesto, el presente proyecto tiene como objetivo general el desarrollo de un sistema web que integre la gestión de compra de usuarios para la empresa Publisys, con la finalidad de optimizar tanto la experiencia del cliente como los procesos internos relacionados con ventas, presupuestos y servicios. Para ello, se emplearon tecnologías como HTML, JavaScript y CSS3 en la construcción de la interfaz de usuario (frontend), junto con el desarrollo de la arquitectura del servidor (backend, utilizando MySQL para la base de datos y Apache como servidor web. Esta infraestructura permite la creación de un catálogo digital de productos, así como la habilitación de opciones de permitiendo la creación de un catálogo digital de productos y servicios, opciones de customización para el administrador del sistema, entre otras funcionalidades. Cabe destacar que este proyecto se desarrolló bajo la modalidad de aplicación tecnológica, apoyado en un diseño de campo con nivel descriptivo cuyo propósito es implementar soluciones a través de herramientas informáticas. Para el diagnóstico situacional y el levantamiento de los requerimientos funcionales y no funcionales, el trabajo se ejecutó de manera directa en el entorno operativo. En esta fase, se emplearon técnicas de observación y entrevistas no estructuradas dirigidas al personal de la organización. A nivel metodológico, la investigación se enmarcó bajo la modalidad de proyecto sociotecnológico con enfoque mixto, sustentada en la Investigación-Acción Participativa (IAP). Por su parte, la construcción del software se llevó a cabo bajo los principios de las metodologías ágiles, específicamente SCRUM. Este enfoque dual garantizó la entrega de un producto digital capaz de satisfacer plenamente las necesidades de la empresa. A continuación, se detalla la arquitectura de la investigación, la cual está estructurada en cinco bloques que sustentan el desarrollo del proyecto. El presente documento se estructura en cinco partes. La Parte I: Descripción del Proyecto expone el diagnóstico situacional, la priorización de necesidades, los objetivos y la justificación de la propuesta. Seguidamente, la Parte II: Marco Referencial reúne los antecedentes y las bases teóricas. La Parte III: Marco Metodológico detalla la modalidad de investigación, el diseño y las técnicas de recolección de datos. A continuación, la Parte IV: Producto Final describe el desarrollo tecnológico del sistema web, su validación y los recursos empleados. Finalmente, la Parte V: Conclusiones y Recomendaciones presenta las reflexiones de la implementación, sugerencias, bibliografía y anexos correspondientes	1	EMPRESA PUBLISYS	Automatización de Procesos, Catálogo Digital, Gestión de Compras, Metodología SCRUM, Sistema Web	2026-10-19 23:44:29.667762	\N	\N	Desarrollar e implementar el SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS.	t	\N	2	0
293	2026-07-01	Pregrado	El presente proyecto socio-tecnológico se basa en la creación de un sistema de gestión web para los recursos pedagógicos en el liceo “Libertador” ubicado en el municipio Valera del estado Trujillo. Con la finalidad de eliminar la brecha existente en cuanto a la relación entre tecnología y educación, además de derribar la barrera comunicacional entre los estudiantes y profesores que se encontraba limitada solo al horario presencial, también brindar un portal informativo para que la institución pueda mostrar noticias y actividades de interés. Este proyecto se enmarco bajo la metodología de investigación acción participativa, enmarcado en una forma de trabajo ágil específicamente SCRUM, ya que el mismo fue realizado en constante participación de la población institucional y comunidad vinculada a la misma para generar una solución de manera agil, practica y eficiente. El desarrollo se sustentó en tecnologías de código abierto como Node.js, Express.js, MySQL y Bootstrap 5, garantizando seguridad, escalabilidad y viabilidad económica. Mostrando en pruebas de usabilidad en muestras aplicadas una aceptación del 100%, por lo que se puede concluir que la solución implementada mejora y optimiza la gestión pedagógica y académica de la institución y brinda una oportunidad de avance, modernización y mejoras tecnológicas para el liceo “Libertador”	1	LICEO “LIBERTADOR	gestión pedagógica, sistema web, tecnologías educativas	2026-10-19 23:44:29.760518	\N	\N	Desarrollar e implementar el SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR.	t	\N	2	0
294	2026-07-01	Pregrado	El presente proyecto socio-tecnológico tiene por nombre sistema web para la gestión de ventas de Natural Nei F.P, que tuvo como objetivo general desarrollar un sistema web para la gestión de ventas de la firma personal Natural Nei F.P., con el fin de optimizar el procesamiento transaccional, mitigar la pérdida de registros comerciales y centralizar los flujos de información operativa. La problemática detectada se centró en la existencia de procesos manuales que generaban lentitud en la atención al cliente y cuellos de botella en el control del inventario de productos. Para el levantamiento de información, se aplicaron técnicas de observación directa y entrevistas semiestructuradas dirigidas al personal administrativo de la organización. El desarrollo del software se guio por la metodología XP ágil de ingeniería de software, articulando una arquitectura de base de datos relacional para el Backend y una interfaz de usuario fluida y responsiva para el Frontend. Como resultado principal, se logró el diseño e implantación de una plataforma informática robusta capaz de automatizar las órdenes de compra, procesar pagos digitales de forma segura y generar reportes financieros detallados en tiempo real. Se concluye que la integración de esta infraestructura tecnológica reduce significativamente los tiempos de respuesta comercial, eleva la confiabilidad en el manejo de datos transaccionales y dota a la empresa de una herramienta escalable que se alinea con las demandas socio-productivas y de conectividad del entorno actual	1	Natural Nei Firma Personal	Automatización de Procesos, Base de Datos, Gestión de	2026-10-19 23:44:29.853154	\N	\N	Desarrollar e implementar el SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P.	t	\N	2	0
295	2025-03-01	Pregrado	El presente proyecto se desarrollará en las instalaciones de la Comercializadora FrancoPlast C.A. con el objetivo de implementar un sitio web de esta para alcanzar una mayor visibilidad a nivel comercial-informativo. Será ejecutado bajo la metodología Scrum, siendo esta una metodología agil y flexible al momento del desarrollo del sitio web y la coordinación de los estudiantes; será llevada a cabo por varias etapas las cuales son: 1- La planificación Al comienzo del trabajo, se empezó la creación del Product Backlog, donde se dio prioridad a todas las funcionalidades, características y los requerimientos del sitio web. En nuestro caso, el Product Backlog inicial incluyó: Sección Funcionalidades, características y requerimientos Página principal o Inicio Información esencial de la comercializadora, como ubicación y número de contacto Catálogo	1	Comercializadora Francoplast  C.A		2026-10-19 23:44:29.93803	\N	\N	Implementar una página web en la Comercializadora FrancoPlast C.A.	t	\N	2	0
297	2025-10-01	Pregrado	El objetivo principal de este proyecto es mejorar y modernizar la infraestructura de la red de comunicación de CORPOELEC. Se utiliza la metodología PPDIOO de Cisco, que incluye seis fases del ciclo de vida de la red. La iniciativa comenzó con las fases de Preparación y Planificación, donde se elaboró un caso de negocio tras un diagnóstico que reveló problemas críticos, como falta de sistemas de refrigeración, obsolescencia de la red y necesidad de energía de respaldo por cortes frecuentes. Se utilizó un análisis (FODA, CAME) para definir requerimientos y el plan del proyecto. La fase de Diseño creó una nueva arquitectura de red que prioriza la resiliencia y la seguridad, integrando tecnologías avanzadas como protocolos eficientes y switches y routers de alta capacidad. En la fase de Implementación, se realizó la instalación física y configuración lógica de la solución, integrando nuevos dispositivos y sistemas de apoyo. También se llevó a cabo una capacitación integral al personal para asegurar el uso autónomo y mantenimiento. Finalmente, las fases de Operación y Optimización son continuas. La Operación maneja el mantenimiento diario, mientras que la Optimización aborda problemas y mejora el rendimiento a largo plazo, fomentando el desarrollo social y sostenible.	1	Corporación Eléctrica Nacional C.A.(CORPOELEC). Sucursal: despacho	Optimización, Modernización, Red, Comunicación, CORPOELEC	2026-10-19 23:50:36.672787	\N	\N	Optimización y\nModernización\nde la red de\ncomunicación\nde la empresa\nnacional\nCORPOELEC\nsucursal:\ndespacho\nsuroccidente.	t	\N	2	0
298	2025-11-01	Pregrado	Se llevó a cabo un proyecto titulado "Sistema de Control de Acceso al Laboratorio Informático de la E.T. Pedro García Leal", en el cual se analizaron y priorizaron las necesidades tecnológicas de la institución mediante un diagnóstico situacional, donde se procuró resolver el problema principal de acceso no autorizado al laboratorio informático, creando una solución viable con los recursos disponibles que se tenían. Se implementó el proyecto haciendo uso de la metodología RUP (Rational Unified Process), mediante el diagnóstico situacional y la entrevista realizada a la directora de la institución, se llegó a la conclusión de desarrollar un programa en Python con CustomTkinter como interfaz gráfica y el uso de SQLite3 como gestor de base de datos, permitiendo registros y la generación de reportes automatizados	1	Institución: Escuela Técnica “Pedro García Leal”	Sistema, Laboratorio, Metodología, Python, Base de Datos	2026-10-20 00:09:16.424521	\N	\N	Implementar un sistema de información para controlar el acceso al laboratorio informático de la E.T 'Pedro García Leal'.	t	\N	2	0
300	2025-11-01	Pregrado	El presente proyecto que tiene por nombre, fue realizado en la C.C.C DR.JOSE GREGORIO HERNADEZ C.A. ubicada en el municipio Valera del Estado Trujillo. En primera instancia, se dio inicio con un diagnostico situacional para detectar los problemas actuales que presenta la comunidad en cuanto a la deficiencia en el área de la informática. Por otra parte, abordando la realización de un sistema de información el cual se encargaría en convertir los registros médicos de papel en archivos electrónicos. Además, el objetivo principal fue desarrollar una herramienta que facilite la documentación de los pacientes atendidos por la comunidad agilizando el proceso, así tanto el personal de historias médicas como la comunidad en general y pacientes se beneficien de tener una historia resguardada tanto en físico como en digital. De igual manera, guiados por las fases de la metodología ágil DSDM basada en el estudio, la interacción, diseño, el desarrollo y la implementación, como también una capacitación al personal que allí labora para dar aplicar los conocimientos del sistema para su debido uso. En consecuencia y, gracias a la realización de este proyecto, se ha logrado avances positivos el área y especialmente en el departamento de registro de historias médicas de la institución, ya que mejoro la rapidez del proceso y el resguardo de información importante con la automatización del registro	1	El Centro Clínico de Cirugía Doctor José Gregorio Hernández C.A consisten principalmente en la atención de pacientes internos y se realizan bajo la su	Diagnostico, elaboración, información, registro, sistema, historias médicas, metodología, planteamiento, desarrollo, pruebas, implementación, proceso, metas alcanzadas	2026-10-20 00:09:16.610421	\N	\N	Implementar un sistema de información seguro para la gestión de historias médicas, que permita mejorar la eficiencia en la atención al paciente, facilitar el acceso a la información clínica y garantizar la confidencialidad de los datos.	t	\N	2	0
301	2025-11-01	Pregrado	El Proyecto Socio Tecnológico desarrollado en la Escuela Técnica “Pedro García Leal” tiene el propósito de implantar un Sistema de Información destinado a solucionar las diversas problemáticas encontradas en el área de informática, comenzando con el diagnóstico situacional, donde a través de una entrevista estructurada se nos puso al tanto de los diversos desafíos que atraviesa la institución. Mediante el desarrollo de una jerarquización e identificación de necesidades se seleccionó la alternativa más factible, con la ayuda de los diferentes proyectos en el marco referencial se tuvo la idea de usar la metodología ágil SCRUM, en el desarrollo de la propuesta, tomando en cuenta lo antes expresado, se hizo una lista de requisitos funcionales la cual se utilizó durante los diversos sprints realizados en el transcurso del proyecto, finalmente se ejecutó exitosamente la implantación del sistema	1	ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL	programación, base de datos, sistema de información, SCRUM, informática, entrevista, sprint, implantación	2026-10-20 00:09:16.704217	\N	\N	Brindar una educación técnica de la calidad de 1ero a 6to año.	t	\N	3	0
302	2026-07-01	Pregrado	Se detalla el diseño de un Sistema Informático para el minimarket Inversiones M.Q., ubicado en el sector Buenos Aires de Valera, estado Trujillo. El proyecto fue desarrollado por los estudiantes Esnaider González, Pedro Montilla e Israel Moreno para el Programa Nacional de Formación en Informática de la UPTT "Mario Briceño Iragorry". El comercio operaba originalmente bajo un esquema manual donde el registro de ventas y el control de mercancía se realizaban en cuadernos, lo que generaba lentitud en caja, pérdida de datos financieros y desorganización general. Mediante la adopción de la Metodología Scrum, se planificó y construyó una aplicación web robusta utilizando PHP, JavaScript, HTML, CSS y MySQL. El software implementa procesos de Automatización comercial e incluye módulos especializados para la Facturación ágil con conversión dinámica de divisas y una eficiente Gestión de Inventario con alertas de stock mínimo para evitar el desabastecimiento. La validación técnica del producto final arrojó un 100% de cumplimiento en la matriz de requerimientos del sistema, logrando reducir los tiempos de atención en caja a menos de 30 segundos, eliminando los errores humanos en los cálculos monetarios y optimizando con éxito la operatividad del negocio	1	Inversiones M.Q	Automatización, Facturación, Gestión de Inventario, Metodología Scrum, Sistema Informático	2026-10-20 00:09:16.782033	\N	\N	Desarrollar un sistema de gestión web de ventas hecho para la mejor facilidad y atención al cliente como también para el control de ventas e inventario, con el fin de digitalizar las ventas y productos, y alcanzar un mejor rendimiento de la empresa	t	\N	3	0
304	2025-11-01	Pregrado	El presente trabajo especial de grado tiene como objetivo principal la Reestructuración y Mantenimiento de la Red LAN en la Dirección General de Planificación y Presupuesto de la Gobernación del Estado Trujillo. El siguiente estudio se desarrolla en respuesta a la necesidad de mejorar el rendimiento, la seguridad y la estabilidad de la red local (LAN) de la mencionada comunidad, institución clave en la gestión organizacional y administrativa. A través de un diagnóstico técnico y la aplicación de estrategias de mantenimiento correctivo y preventivo, esta propuesta busca optimizar los recursos existentes y garantizar un entorno tecnológico confiable y eficiente para lograr este objetivo, este trabajo se realizó bajo el tipo de investigación práctica o aplicada, ya que dicha investigación busca aplicar los conocimientos adquiridos en la metodología para la reestructuración de redes de Cisco , un análisis exhaustivo de la red actual a nivel lógico y físico, identificando posibles problemas y deficiencias. Se utilizo la observación y la entrevista para la recolección de datos. Esto implica considerar aspectos como la aceptación por parte de los usuarios, la viabilidad técnica de implementar los cambios propuestos y el cumplimiento de las regulaciones legales pertinentes	1	La   Dirección General de Planificación y Presupuesto adscrita a la Gobernación del Estado Trujillo	Red LAN, Cisco, Cable UTP, Topología	2026-10-20 00:14:39.526868	\N	\N	Reestructurar y hacer Mantenimiento de la Red LAN en la Dirección General de Planificación y Presupuesto adscrita a la Gobernación del Estado Trujillo.	t	\N	2	0
\.


--
-- TOC entry 5772 (class 0 OID 51505)
-- Dependencies: 235
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dimensiones_operativas (id, id_linea, nombre, descripcion, activo) FROM stdin;
7	7	Sistemas de información web	Son primeramente sistemas de información que para su desarrollo se debe considerar la misma disciplina de construcción de sistemas de información no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van m s all  de ser un conjunto de p ginas Web.	t
8	7	Sistemas de información colaborativos	Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribución de la información en una organización o entre organizaciones.	t
9	7	Gestión tecnológica	Procesos relacionados con la implantación de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos módulos, adem s de comprobar el correcto funcionamiento de los componentes de un sistema de información que puede abarcar auditorías, t‚cnicas de control, evaluación de la calidad.	t
12	8	Tutoriales	Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realización de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.	t
16	9	Aplicaciones cliente - servidor	Sistema distribuido entre múltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma m s adecuada.	t
18	10	Simulación y herramientas de simulación	Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulación o test donde sea posible modelizar y probar el sistema que est  desarrollando. Reduce tiempo y chequea decisiones a priori.	t
20	9	Aplicaciones multiplataforma	diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.	t
21	9	Aplicaciones web interactivas	diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.	t
22	9	Aplicaciones móviles y ubicuas	desarrollo de soluciones adaptadas a dispositivos móviles y contextos de movilidad, integrando sensores, geolocalización, notificaciones y conectividad. Se promueve la experiencia de usuario y el acceso remoto a servicios en tiempo real.	t
23	9	Modelado y gestión de datos	diseño conceptual, lógico y físico de estructuras de datos que sustentan el funcionamiento de las aplicaciones, Incluye el uso de modelos entidad-relación, normalización, diseño de esquemas relacionales y no relacionales, así como la implementación en sistemas gestores de bases de datos. Esta dimensión garantiza la integridad, consistencia y eficiencia en el almacenamiento, recuperación y procesamiento de la información.	t
24	9	Seguridad y auditoría de aplicaciones	incorporación de prácticas de desarrollo seguro, autenticación, autorización, cifrado y trazabilidad. Se abordan normativas como ISO/IEC 27001 y principios de privacidad por diseño, garantizando la integridad y confidencialidad de los sistemas.	t
10	8	Software educativo	Programas para el computador creados con la finalidad específica de ser utilizados como medio did ctico, es decir, para facilitar los procesos de ense¤anza y de aprendizaje. Combina conocimiento educacional, comunicacional e inform tico.	t
17	9	Servicios de integración para aplicaciones web	Medio para exponer y hacer disponible la funcionalidad de los sistemas de información mediante las tecnologías est ndar Web, permitiendo reducción de la heterogeneidad por uso de tecnologías est ndar.	t
11	8	Guías de estudio web	Representan un material instruccional utilizados para cursos de educación a distancia y como complemento a la educación presencial, lo cual provee una estructura para un curso.	t
15	8	Sistemas e-learning	Programas que faciliten la creación, adopción y distribución de contenidos, así como la adaptación del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de límites horarios o geogr ficos.	t
6	7	Sistemas de información con propiedades geográficas	Son sistemas que permiten evaluar propiedades geogr ficas de un entorno, generando información referente a una entidad geogr fica desplegando im genes e información en un hipermapa.	t
13	8	Juegos didácticos	El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploración y expresión, un instrumento para la organización y aplicación de habilidades y, un factor de socialización e integración.	t
14	8	Entornos interactivos de enseñanza	Proyectos donde el profesor y los alumnos se encuentran en lugares físicamente distintos. El proceso de ense¤anza-aprendizaje se lleva a cabo a trav‚s de Internet, en cualquier momento y en cualquier lugar.	t
19	10	Modelos de transmisión de datos	Se discute la conceptualización integral de un sistema de transmisión desde un marco común a diferentes tecnologías, tales como: sistemas de comunicación por cable, radio enlaces fijos, móviles y satelitales.	t
5	7	Sistemas de información tradicionales	Est  constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e información y programas y aplicaciones; que interactúan entre sí dentro de una organización con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.	t
\.


--
-- TOC entry 5774 (class 0 OID 51515)
-- Dependencies: 237
-- Data for Name: editoriales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.editoriales (id, nombre) FROM stdin;
1	IEEE
3	Springer
4	Elsevier
5	UPTTMBI Ediciones
6	UNESCO
7	SciELO Venezuela
8	DYNA
\.


--
-- TOC entry 5776 (class 0 OID 51521)
-- Dependencies: 239
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.etiquetas (id, nombre, color_hex) FROM stdin;
1	Inteligencia Artificial	#0ea5e9
3	Educación	#0ea5e9
4	Redes Neuronales	#0ea5e9
5	Desarrollo Web	#0ea5e9
8	Teoría Matemática	#0ea5e9
9	Modelo Económico	#0ea5e9
10	Construcción	#0ea5e9
17	Machine Learning	#0ea5e9
\.


--
-- TOC entry 5778 (class 0 OID 51528)
-- Dependencies: 241
-- Data for Name: historico_versiones_pst; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.historico_versiones_pst (id, id_recurso, archivo_pdf, usuario_id, motivo, created_at) FROM stdin;
\.


--
-- TOC entry 5830 (class 0 OID 52219)
-- Dependencies: 293
-- Data for Name: investigaciones_dimensiones; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.investigaciones_dimensiones (id_investigacion, id_dimension) FROM stdin;
2	9
\.


--
-- TOC entry 5829 (class 0 OID 52202)
-- Dependencies: 292
-- Data for Name: investigaciones_lineas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.investigaciones_lineas (id_investigacion, id_linea) FROM stdin;
1	9
2	7
4	10
\.


--
-- TOC entry 5780 (class 0 OID 51539)
-- Dependencies: 243
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.investigaciones_ofertadas (id, id_profesor, titulo, planteamiento_problema, objetivo_general, id_linea, id_dimension, cupos_disponibles, estado, fecha_creacion, id_propuesta_empresa) FROM stdin;
1	7	asdasdasdasd	asdasdasdasdasdasd	asdasdasdasdasdasd	9	\N	3	Abierta	2026-09-09 23:15:22.674054	\N
2	7	Requerimiento: Sistema de Inventario	isuuuuuuu ordeña a carmencita\n\n(Nivel Requerido: Trayecto I)	Dar respuesta y solución tecnológica a los requerimientos de Megacell	7	9	3	Abierta	2026-09-17 00:01:42.595736	16
4	7	asdasdasdasdsadadasdsa	asdasdasdasd	asdasdadasdas	10	\N	3	En Desarrollo	2026-09-29 00:05:26.495721	\N
\.


--
-- TOC entry 5849 (class 0 OID 60577)
-- Dependencies: 312
-- Data for Name: investigadores; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.investigadores (id, nombre_completo, orcid, biografia, pagina_web, foto_perfil, email, grado_academico) FROM stdin;
1	Alejandro	0000-0000-0000-0013	\N	\N	\N	\N	\N
2	César Gerardo León-Velarde	0000-0000-0000-0097	\N	\N	\N	\N	\N
3	Guillermo Pastor Morales-Romero	0000-0000-0000-0096	\N	\N	\N	\N	\N
4	Daniel ángel	0000-0000-0000-0057	\N	\N	\N	\N	\N
5	Jairo Alexander Osorio-Saráz	0000-0000-0000-0105	\N	\N	\N	\N	\N
6	Giovanni Andrés Cortés-Tovar	0000-0000-0000-0103	\N	\N	\N	\N	\N
7	Jorge Lira-Camargo	0000-0000-0000-0102	\N	\N	\N	\N	\N
8	Estudiante Prueba Uno	0000-0000-0000-0047	\N	\N	\N	\N	\N
9	José Antonio Ogosi-Auqui	0000-0000-0000-0092	\N	\N	\N	\N	\N
10	David Lidmar	0000-0000-0000-0054	\N	\N	\N	\N	\N
11	Araujo Oliver	0000-0000-0000-0052	\N	\N	\N	\N	\N
12	María Autor Prueba	0000-0000-0000-0049	\N	\N	\N	\N	\N
13	Robinson Osorio-Hernández	0000-0000-0000-0104	\N	\N	\N	\N	\N
14	Collantes Peña José Manuel	0000-0000-0000-0059	\N	\N	\N	\N	\N
15	León Custode María Fernanda	0000-0000-0000-0060	\N	\N	\N	\N	\N
16	Alejandro Alicante	0000-0000-0000-0031	\N	\N	\N	\N	\N
17	Araujo Rivas Isamar Andreina	0000-0000-0000-0058	\N	\N	\N	\N	\N
18	Anyela Alejandra Briceño Guerra	0000-0000-0000-0082	\N	\N	\N	\N	\N
19	Estudiante Pruebas Dos	0000-0000-0000-0056	\N	\N	\N	\N	\N
20	Ramírez Duarte Andrus Ruben	0000-0000-0000-0044	\N	\N	\N	\N	\N
21	Estudiante Pruebas Uno	0000-0000-0000-0055	\N	\N	\N	\N	\N
22	Jesús Linares	0000-0000-0000-0051	\N	\N	\N	\N	\N
23	Nava Ailberth	0000-0000-0000-0053	\N	\N	\N	\N	\N
24	Estudiante Prueba Dos	0000-0000-0000-0048	\N	\N	\N	\N	\N
25	Favian Herrera	0000-0000-0000-0050	\N	\N	\N	\N	\N
26	Dr. Ricardo Dos Santos	0000-0001-8752-8550	Ingeniero de Sistemas (ULA, 2006), Magister Scientiae en Computación (ULA, 2016) y Doctor en Ciencias Aplicadas (ULA, 2026). Profesor Titular en la UPTTMBI e Investigador en Inteligencia Artificial, Datos Enlazados, Big Data, Machine Learning y Minería Semántica.	https://ricardojdsg.github.io/	public/uploads/perfiles/ricardo_dos_santos.jpg	ricardojdsg@upttmbi.edu.ve	Ingeniero de Sistemas · Magister Scientiae en Computación · Doctor en Ciencias Aplicadas
\.


--
-- TOC entry 5782 (class 0 OID 51555)
-- Dependencies: 245
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lineas_investigacion (id, nombre, id_carrera, descripcion, activo) FROM stdin;
10	REDES Y TELECOMUNICACIONES	1	Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisión de datos, como tambi‚n la detección de fallas dentro de una red.	t
9	DESARROLLO DE APLICACIONES	1	Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.	t
7	SISTEMAS DE INFORMACIÓN Y MODELADO DE DATOS	1	Desarrollar y gestionar sistemas de información dentro del  ámbito social. Aplicando soluciones efectivas para el uso adecuado y óptimo de los sistemas de información.	t
8	EDUMÁTICA	1	Aplicar las Tecnologías de la Información y Comunicación (TIC) para apoyar el proceso de aprendizaje, y así contribuir al mejoramiento de la educación en todos sus niveles.	t
\.


--
-- TOC entry 5784 (class 0 OID 51565)
-- Dependencies: 247
-- Data for Name: matriz_rbac; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.matriz_rbac (nivel_privilegio, modulo, permisos) FROM stdin;
1	Articulos	{"crear": true, "editar": true, "auditar": true, "eliminar": true}
1	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
1	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
1	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
1	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
2	Articulos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
2	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
2	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
2	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
2	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	Articulos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	Cursos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
3	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	Articulos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	Cursos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
4	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	Articulos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	Cursos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
5	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	Articulos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	Cursos	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	Investigaciones	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	LineasInvestigacion	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	RepositorioPST	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
6	VinculacionEmpresarial	{"crear": false, "editar": false, "auditar": false, "eliminar": false}
1	Cursos	{"crear": true, "editar": true, "auditar": true, "eliminar": true}
2	Cursos	{"crear": true, "editar": true, "auditar": true, "eliminar": true}
\.


--
-- TOC entry 5785 (class 0 OID 51572)
-- Dependencies: 248
-- Data for Name: niveles_academicos; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.niveles_academicos (id, codigo, nombre, descripcion, requiere_trayecto, orden, activo, created_at, updated_at) FROM stdin;
1	Pregrado	Pregrado	Estudios conducentes a título universitario de pregrado o ingeniería	t	1	t	2026-10-04 16:19:32.998269	2026-10-04 16:19:32.998269
3	Maestria	Maestría	Estudios de postgrado de investigación científica y magíster	f	3	t	2026-10-04 16:19:32.998269	2026-10-04 16:19:32.998269
4	Doctorado	Doctorado	Máximo grado académico de investigación epistémica	f	4	t	2026-10-04 16:19:32.998269	2026-10-04 16:19:32.998269
2	Especializacion	Especialización	Estudios de postgrado para especialización técnica	f	2	t	2026-10-04 16:19:32.998269	2026-10-04 16:53:32.699637
\.


--
-- TOC entry 5787 (class 0 OID 51586)
-- Dependencies: 250
-- Data for Name: notificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notificaciones (id, id_usuario, titulo, mensaje, leido, fecha_hora, fecha) FROM stdin;
1	4	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.	t	2026-03-23 16:14:24	2026-03-23 20:40:28
2	4	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 4. El estado de la cuenta es: completamente Activa.	t	2026-03-23 20:10:36	2026-03-23 20:40:28
3	5	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 1. El estado de la cuenta es: completamente Activa.	t	2026-03-23 21:42:42	2026-03-23 21:42:42
4	4	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: Suspendida por completo.	t	2026-04-02 02:13:17	2026-04-02 02:13:17
5	4	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.	t	2026-04-02 02:13:22	2026-04-02 02:13:22
6	5	Actualización Moderada de Cuenta	Su perfil fue ajustado por un administrador. Nuevo rol ID: 3. El estado de la cuenta es: completamente Activa.	t	2026-04-04 16:13:51	2026-04-04 16:13:51
\.


--
-- TOC entry 5789 (class 0 OID 51596)
-- Dependencies: 252
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (id, email, token_hash, expiracion, utilizado, created_at) FROM stdin;
1	orlando5711666@gmail.com	98ae6a718e630e4dffb2c144bbb36b095f55358c7299904798709c244db29659	2026-09-17 00:03:43.94943	f	2026-09-16 23:48:43.94943
\.


--
-- TOC entry 5791 (class 0 OID 51606)
-- Dependencies: 254
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.postulaciones_estudiantes (id, id_investigacion, id_estudiante, mensaje_motivacion, estado, fecha_postulacion, fecha_respuesta, equipo_extra, trayecto, trimestre, id_profesor_pst, aprobacion_profesor, motivo_profesor, fecha_revision_profesor, motivo_rechazo) FROM stdin;
1	1	7	asdasdasd	Rechazado	2026-09-09 23:16:44.679115	2026-09-19 02:07:44.686527	\N	\N	\N	\N	Pendiente	\N	\N	\N
2	2	7		Rechazado	2026-09-18 23:33:33.698004	2026-09-19 02:08:00.249562	[]	\N	\N	\N	Pendiente	\N	\N	\N
\.


--
-- TOC entry 5793 (class 0 OID 51618)
-- Dependencies: 256
-- Data for Name: preferencias_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.preferencias_usuario (id_usuario, tema, notificaciones_sistema) FROM stdin;
1	ocean	t
3	sunset	t
4	ocean	t
6	sunset	t
\.


--
-- TOC entry 5794 (class 0 OID 51624)
-- Dependencies: 257
-- Data for Name: privilegios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.privilegios (privilegio_id, nivel_privilegio) FROM stdin;
1	0
2	1
3	2
4	3
5	4
6	5
10	6
\.


--
-- TOC entry 5853 (class 0 OID 60677)
-- Dependencies: 316
-- Data for Name: profesor_dimension; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.profesor_dimension (id_usuario, id_dimension, id_linea, created_at) FROM stdin;
7	16	9	2026-10-21 16:28:12.083524
7	20	9	2026-10-21 16:28:12.083524
7	21	9	2026-10-21 16:28:12.083524
10	7	7	2026-11-01 22:03:46.862211
10	8	7	2026-11-01 22:03:46.862211
10	9	7	2026-11-01 22:03:46.862211
10	21	9	2026-11-01 22:03:46.862211
10	23	9	2026-11-01 22:03:46.862211
10	17	9	2026-11-01 22:03:46.862211
10	15	8	2026-11-01 22:03:46.862211
\.


--
-- TOC entry 5828 (class 0 OID 52183)
-- Dependencies: 291
-- Data for Name: profesor_linea; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.profesor_linea (id_usuario, id_linea, created_at) FROM stdin;
7	9	2026-10-21 16:28:12.083524
7	7	2026-10-21 16:43:56.76043
7	10	2026-10-21 16:43:56.76043
10	9	2026-11-01 22:03:46.862211
10	7	2026-11-01 22:03:46.862211
10	8	2026-11-01 22:03:46.862211
\.


--
-- TOC entry 5827 (class 0 OID 52143)
-- Dependencies: 290
-- Data for Name: profesores_pst; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.profesores_pst (id, id_profesor, trayecto, trimestre, created_at) FROM stdin;
1	6	2	1	2026-10-12 19:14:13.860953
2	6	3	1	2026-10-12 19:14:13.860953
\.


--
-- TOC entry 5796 (class 0 OID 51631)
-- Dependencies: 259
-- Data for Name: propuestas_empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.propuestas_empresa (id, nombre_empresa, rif_empresa, persona_contacto, telefono_contacto, correo_contacto, area_afectada, descripcion_problema, estado, fecha_creacion, nivel_trayecto, codigo_seguimiento, motivo_rechazo, campos_extra, eliminado_en, motivo_archivo, archivado_por, revertido_en, motivo_reversion) FROM stdin;
14	Punto Yali	27889926	Miki boss	04121609721	lando1609721@gmail.com	Sistema de Inventario	vbnchngfhgjfhgj	aceptada	2026-09-13 22:30:07.723734	Trayecto I	CIIDI-2026-29E32	\N	[]	\N	\N	\N	\N	\N
3	Punto Yali	123	Yohan Estrada	0416-6777467	yohan@gmail.com	facturacion	IAIAIAIA	rechazada	2026-09-11 22:13:08.195366	\N		CIIDI-2026-F421F	[]	\N	\N	\N	\N	\N
5	Megacell	J-12045552-	Miki waza	04121609721	lando1609721@gmail.com	inventario	Necesito un sistema que haga inventario	rechazada	2026-08-25 17:16:56.513754	Trayecto II	\N	\N	[]	\N	\N	\N	\N	\N
6	Megacell	J20789378	Miki waza	0414-7573234	lando1609721@gmail.com	inventario	necesito un sistema para mi alacen	aceptada	2026-09-09 21:47:51.4141	Trayecto I	CIIDI-2026-AF997	\N	[]	\N	\N	\N	\N	\N
7	Megacellll	J20789378	Miki waza	04121609721	lando16097211@gmail.com	redes	11111111111111	aceptada	2026-09-10 23:12:34.730038	Trayecto I	CIIDI-2026-4050F	\N	[]	\N	\N	\N	\N	\N
8	Punto Yali	27889926	Miki waza	04121609721	orlando5711666@gmail.com	inventario	necesito ayuda con respecto a un sistema que me controle el inventario de los productos que vendo aca en el local	aceptada	2026-09-13 18:02:49.521927	Trayecto I	CIIDI-2026-84BAE	\N	[]	\N	\N	\N	\N	\N
9	gregoria	123456789	Miki waza	04121609721	orlando5711666@gmail.com	facturacion	hola	aceptada	2026-09-13 18:42:50.07038	Trayecto II	CIIDI-2026-4DDC5	\N	[]	\N	\N	\N	\N	\N
11	zambrano cell	27889926	chailon	04121609721	lando1609721@gmail.com	inventario	ailberth deje de robarse las pantallas pa su primo chamo	aceptada	2026-09-13 18:55:13.333522	Trayecto III	CIIDI-2026-E5EAE	\N	[]	\N	\N	\N	\N	\N
10	carmencita coito	12345567889	Miki boss	04121609721	lando1609721@gmail.com	datos	yisus porfa ordeña a carmencita sisisisi	aceptada	2026-09-13 18:53:14.455601	Trayecto IV	CIIDI-2026-D55D0	\N	[]	\N	\N	\N	\N	\N
4	Punto G De Yali	G-30676767-0	Iojan	4147755888	Puntogdeyali@gmail.com	redes	Quiero un sistema de clasificacion de los pelos de mi anito riko mmm sisisii	aceptada	2026-07-10 14:02:25.411413	Trayecto I	\N	\N	[]	\N	\N	\N	\N	\N
1	Megacell	J-12045552-	ELLL PRIMOOOO	04121609721	lando1609721@gmail.com	facturacion	NECESITAMOS UN SISTEMA PARA CLASIFICAR FEMBOY, FURROS Y KPOPERAS 	aceptada	2026-07-10 00:05:36.297999	Trayecto I	\N	\N	[]	\N	\N	\N	\N	\N
12	Megacell	J20789378	chailon	04121609721	lando1609721@gmail.com	Sistema de Inventario	necesito un sistema para poder registrar los telefonos que estamo haciendo	aceptada	2026-09-13 19:46:05.970023	Trayecto I	CIIDI-2026-F5A16	\N	[]	\N	\N	\N	\N	\N
13	Punto Yali	20789378	Miki boss	04121609721	lando1609721@gmail.com	Sistema de Inventario	caafagsdfddsfdsfdsf	aceptada	2026-09-13 22:28:56.260924	Trayecto I	CIIDI-2026-EC9AC	\N	[]	\N	\N	\N	\N	\N
16	Megacell	27889926	Miki waza	04121609721	lando1609721@gmail.com	Sistema de Inventario	isuuuuuuu ordeña a carmencita	aceptada	2026-09-16 23:57:18.499486	Trayecto I	CIIDI-2026-CAA12	\N	[]	\N	\N	\N	\N	\N
15	zambrano cell	20789378	chailon	04121609721	lando1609721@gmail.com	Mejora de sistema	ghfjhmfhjfgdjhfd	aceptada	2026-09-13 22:30:21.539904	Trayecto I	CIIDI-2026-C8F0D	\N	[]	\N	\N	\N	\N	\N
2	123	123	123	123	123@gmail.com	inventario	123	aceptada	2026-09-02 21:42:33.312089	Trayecto IV	\N	\N	[]	\N	\N	\N	\N	\N
18	Megacell	J20789378	Miki waza	04121609721	lando1609721@gmail.com	Por evaluar (Se definirá al aprobar)	html <script>alert('Vulnerabilidad XSS Detectada')</script>	pendiente	2026-10-14 11:55:13.173423	\N	CIIDI-2026-886D4	\N	[]	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5798 (class 0 OID 51646)
-- Dependencies: 261
-- Data for Name: proyecto_carreras_vinculadas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.proyecto_carreras_vinculadas (id_recurso, id_carrera, created_at) FROM stdin;
\.


--
-- TOC entry 5799 (class 0 OID 51652)
-- Dependencies: 262
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.proyecto_tutores (id_recurso, id_tutor, tipo_tutor_id) FROM stdin;
224	17	3
224	41	2
224	47	4
225	17	3
225	41	2
225	42	4
226	28	3
226	48	2
226	39	4
227	45	3
228	43	3
228	17	2
228	44	4
229	45	3
229	10	2
229	46	4
230	49	3
230	50	2
230	51	4
231	52	3
231	53	2
231	54	4
232	17	3
232	18	2
232	19	4
233	17	3
233	55	4
234	17	3
234	41	2
234	56	4
235	45	2
236	17	3
236	57	2
236	58	4
237	59	3
237	17	2
237	60	4
238	17	3
238	57	2
238	61	4
239	45	2
239	62	4
240	45	3
240	22	2
240	63	4
241	10	3
241	37	2
241	64	4
242	65	3
242	66	2
242	67	4
243	49	3
243	68	2
243	69	4
244	52	3
245	17	3
245	41	2
245	70	4
246	17	3
246	71	4
247	45	3
247	35	2
247	72	4
248	45	2
248	73	4
249	45	3
249	74	2
249	75	4
250	45	3
250	28	2
250	76	4
251	10	3
251	28	2
251	77	4
252	45	3
252	78	2
252	54	4
253	65	3
253	66	2
253	67	4
254	45	2
254	79	4
255	18	3
255	45	2
255	80	4
256	52	3
256	81	4
257	45	3
257	28	2
257	82	4
258	45	3
258	39	2
258	83	4
259	84	3
259	85	2
259	86	4
260	45	3
260	22	2
260	87	4
261	17	3
261	41	2
261	88	4
262	45	3
262	39	2
262	89	4
263	45	3
264	37	3
264	90	2
264	91	4
265	10	3
265	92	2
265	93	4
266	22	3
266	94	2
266	95	4
267	18	3
267	90	2
267	96	4
268	10	3
268	90	2
268	97	4
269	10	3
269	92	2
269	98	4
270	22	3
270	99	2
270	100	4
271	101	3
271	90	2
271	102	4
272	37	3
272	10	2
272	103	4
273	37	3
273	10	2
273	104	4
274	37	3
274	105	2
274	106	4
275	18	3
275	90	2
275	107	4
276	18	3
276	10	2
276	108	4
278	37	3
278	106	2
278	58	4
279	110	3
279	39	2
279	111	4
280	45	3
280	29	2
280	91	4
281	112	3
281	39	2
281	107	4
277	10	3
277	109	4
282	113	3
282	114	2
282	115	4
283	112	3
283	116	2
283	117	4
284	52	3
284	39	2
284	118	4
285	10	3
285	28	2
285	119	4
286	45	3
286	10	2
286	120	4
287	52	3
287	37	2
287	121	4
288	122	3
288	28	2
288	123	4
289	52	3
289	37	2
289	124	4
290	45	3
290	39	2
290	125	4
291	126	3
291	106	2
291	127	4
292	22	3
292	37	2
292	128	4
293	129	3
293	130	2
294	22	3
294	28	2
294	131	4
295	52	3
295	28	2
295	132	4
296	52	3
296	92	2
296	38	4
297	45	3
297	92	2
297	133	4
298	52	3
298	39	2
298	134	4
299	10	3
299	28	2
299	135	4
300	45	3
300	10	2
300	136	4
301	52	3
301	39	2
301	134	4
302	10	3
302	66	2
302	137	4
303	52	3
303	92	2
303	38	4
304	45	3
304	39	2
304	138	4
305	45	3
305	22	2
305	139	4
\.


--
-- TOC entry 5800 (class 0 OID 51657)
-- Dependencies: 263
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_autores (id_recurso, id_autor) FROM stdin;
224	20
224	21
224	22
224	23
224	24
225	1
225	2
225	3
225	4
225	5
226	16
226	17
226	18
226	19
227	9
227	10
227	11
227	12
227	13
228	6
228	7
228	8
229	14
229	15
230	25
230	26
230	27
230	28
231	29
231	30
231	31
231	32
232	33
232	34
232	35
232	36
232	37
233	38
233	39
233	40
233	41
234	42
234	43
234	44
235	45
235	46
235	47
235	48
235	49
236	50
236	51
236	52
236	53
237	54
238	55
238	56
238	57
238	58
238	59
239	60
239	61
239	62
239	63
239	64
240	65
240	66
240	67
240	68
240	69
241	70
241	71
241	72
241	73
241	74
242	75
242	76
242	77
242	78
242	79
243	80
244	81
244	82
244	14
244	83
245	84
245	85
245	86
245	87
246	88
246	89
246	90
247	91
247	92
248	93
248	94
249	95
249	96
249	97
249	98
249	99
250	100
250	101
251	102
251	103
251	104
252	29
252	105
252	31
252	30
252	32
253	75
253	76
253	77
253	78
253	79
254	106
254	107
254	108
254	109
255	110
255	111
255	112
256	11
256	9
256	10
257	113
257	114
257	115
257	116
257	117
258	118
258	119
259	120
259	121
260	122
260	123
260	124
260	125
260	126
261	127
261	128
261	129
262	130
262	131
262	132
262	133
262	134
263	81
263	135
263	82
263	136
264	137
264	138
264	139
265	140
265	141
265	142
265	143
266	144
266	145
266	146
267	147
267	148
267	149
267	150
268	151
268	152
268	153
269	154
269	155
269	156
269	157
270	158
270	159
271	160
271	161
271	162
271	163
272	164
272	165
272	166
272	167
273	168
273	169
273	170
274	171
275	172
275	173
275	174
275	175
276	176
276	177
276	178
276	179
278	184
278	185
278	186
279	187
279	188
279	189
279	190
280	191
280	192
280	193
281	194
281	195
281	196
277	180
277	181
277	182
277	183
282	197
283	198
283	199
283	200
283	201
283	202
284	203
284	204
284	205
285	206
285	207
285	208
285	209
286	210
286	211
286	212
287	213
287	214
287	215
287	216
287	217
288	218
288	219
288	220
288	221
289	222
289	223
289	224
289	225
290	226
290	227
290	228
290	229
290	230
291	231
291	232
291	233
291	234
292	235
292	236
292	237
292	238
293	239
293	240
293	241
293	242
294	243
294	244
294	245
295	246
295	247
295	248
295	249
296	250
296	251
296	252
296	253
297	254
297	255
297	256
298	257
298	258
298	259
298	260
299	261
299	262
299	263
299	264
300	265
300	266
300	267
300	268
301	269
301	270
301	271
301	272
301	273
302	274
302	275
302	276
303	250
303	251
303	252
303	253
304	277
304	278
305	279
305	280
\.


--
-- TOC entry 5801 (class 0 OID 51662)
-- Dependencies: 264
-- Data for Name: recurso_categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_categorias (id_recurso, id_categoria) FROM stdin;
122	6
122	5
122	7
122	1
121	18
121	5
121	7
120	3
120	13
120	7
119	7
119	1
118	7
118	4
143	3
143	13
144	3
144	13
144	1
146	3
150	14
151	3
156	3
\.


--
-- TOC entry 5802 (class 0 OID 51667)
-- Dependencies: 265
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_clasificaciones (id_recurso, id_linea_investigacion, id_dimension_operativa) FROM stdin;
224	8	14
225	7	5
226	7	5
227	7	5
228	7	5
229	7	5
230	7	7
231	7	7
232	7	7
233	7	7
234	7	7
235	7	7
236	7	7
237	7	7
238	7	7
239	7	7
240	7	7
241	7	7
242	7	7
243	7	7
244	7	7
245	7	7
246	7	7
247	7	7
248	7	7
249	7	7
250	7	7
251	7	7
252	7	7
253	7	7
254	7	7
255	7	7
256	7	7
257	7	7
258	7	7
259	7	7
260	7	7
261	7	7
262	7	7
263	7	7
264	7	9
265	7	9
266	7	7
267	7	7
268	7	9
269	7	9
270	7	9
271	7	9
272	7	9
273	7	9
274	7	9
275	7	9
276	7	9
278	7	9
279	8	10
280	8	10
281	8	10
277	7	9
282	7	7
283	7	7
284	7	7
285	7	7
286	7	7
287	7	7
288	7	7
289	7	7
290	7	7
291	7	7
292	7	7
293	7	7
294	7	7
295	7	7
296	10	19
297	10	19
298	7	5
299	7	7
300	7	5
301	7	5
302	7	7
303	10	19
304	10	18
305	10	19
\.


--
-- TOC entry 5803 (class 0 OID 51672)
-- Dependencies: 266
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_etiquetas (id_recurso, id_etiqueta) FROM stdin;
143	10
143	1
122	1
122	4
121	10
120	8
119	3
119	1
118	1
144	3
144	1
146	10
150	5
151	3
156	1
\.


--
-- TOC entry 5850 (class 0 OID 60590)
-- Dependencies: 313
-- Data for Name: recurso_investigadores; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.recurso_investigadores (id_recurso, id_investigador) FROM stdin;
143	1
150	1
144	2
144	3
122	4
146	5
146	6
144	7
119	8
144	9
121	10
120	11
120	12
146	13
122	14
122	15
151	16
122	17
156	18
121	19
118	20
121	21
120	22
121	23
119	24
120	25
\.


--
-- TOC entry 5804 (class 0 OID 51677)
-- Dependencies: 267
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recursos (id, titulo, id_tipo_recurso, anio_publicacion, archivo_pdf) FROM stdin;
3	Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos	2	2026	\N
4	Impacto del Cambio Climático en Trujillo - Parte 8	2	2023	dummy.pdf
5	Simulación de Cargas Estáticas en Puentes - Parte 7	2	2024	dummy.pdf
9	Bioinformática y Análisis de ADN - Parte 5	3	2025	dummy.pdf
10	Inteligencia Artificial en Diagnóstico Médico - Parte 6	2	2025	dummy.pdf
11	Robótica Educativa para Escuelas - Parte 5	3	2023	dummy.pdf
12	Software Libre para Bibliotecas - Parte 1	3	2026	dummy.pdf
13	E-Learning para Zonas Desfavorecidas - Parte 1	3	2018	dummy.pdf
14	Telecomunicaciones de Fibra Óptica Rural - Parte 1	2	2022	dummy.pdf
16	Criptografía Cuántica Post-RSA - Parte 7	3	2026	dummy.pdf
20	Inteligencia Artificial en Diagnóstico Médico - Parte 1	3	2020	dummy.pdf
122	Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474
121	Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473
120	Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457
119	Entorno virtual de capacitación con EOG para manipular robots asistenciales	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135
118	Middleware MiSCi para ciudades inteligentes extendido con datos enlazados	3	2020	https://revistas.unal.edu.co/index.php/dyna/article/view/83226
143	Investigación y modelado de pérdidas por corriente circulante en sistemas de puesta a tierra de torres de alta tensión	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/124890/98825
146	Modelamiento de confort adaptativo para un trapiche panelero	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/112625/91645
144	Propuesta de un modelo de implementación basado en aprendizaje automático para el reclutamiento de profesionales de ingeniería en una universidad pública	3	2026	https://revistas.unal.edu.co/index.php/dyna/article/view/124428/98826
150	e	3	2026	https://www.youtube.com/
151	e	3	2026	https://www.wikipedia.org/
156	e	3	2026	https://www.wikipedia.org/
224	FORTALECIMIENTO DEL APRENDIZAJE DE CASTELLANO Y LITERATURA MEDIANTE UNA PLATAFORMA EDUCATIVA PARA LOS ESTUDIANTES DE PRIMER AÑO DEL LICEO "DR. REGULO BURELLI RIVAS	1	2026	storage/documentos/pst/pst_fortalecimiento_del_aprendizaj_1792463439_408.docx
225	Sistema integrado para la gestión de los procesos administrativos para el Centro Inmobiliario C.A	1	2025	storage/documentos/pst/pst_sistema_integrado_para_la_gest_1792463528_989.docx
226	GESTOR ADMINISTRATIVO DE CERTIFICACIÓN PARA EL PROGRAMA NACIONAL DE FORMACIÓN EN INFORMÁTICA DE LA UNIVERSIDAD POLITÉCNICA TERRITORIAL DEL ESTADO TRUJILLO “MARIO BRICEÑO IRAGORRY” NÚCLEO LA BEATRIZ (GACPNFI)	1	2025	storage/documentos/pst/pst_gestor_administrativo_de_certi_1792463818_742.docx
227	SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMINISTRATIVA DE GOMINOVA	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792463825_895.docx
228	PROYECTO T4 Sistema Consejo Municipal	1	2025	storage/documentos/pst/pst_proyecto_t4_sistema_consejo_mu_1792463833_258.pdf
229	SISTEMA INTEGRAL DE GESTIÓN ADMINISTRATIVO PARA EL TALLER AUTOMOTRIZ INVERSIONES “DIOS ES AMOR 31” C.A	1	2025	storage/documentos/pst/pst_sistema_integral_de_gesti__n_a_1792463833_282.docx
230	SISTEMA DE INFORMACIÓN GEOGRÁFICA DE LAS POTENCIALIDADES TURÍSTICAS Y GESTIÓN DE PROYECTOS DEL PNF TURÍSMO	1	2026	storage/documentos/pst/pst_sistema_de_informaci__n_geogr__1792464793_692.docx
231	IMPLEMENTACIÓN DE UNA APLICACIÓN WEB EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A. VALERA ESTADO TRUJILLO	1	2025	storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792464802_764.docx
232	OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0	1	2026	storage/documentos/pst/pst_optimizaci__n_del_sistema_de_i_1792464804_268.docx
233	Aplicación Web para la Gestión de los procesos Administrativos del Consejo Comunal de la Comunidad “la Ciénega	1	2025	storage/documentos/pst/pst_aplicaci__n_web_para_la_gesti__1792464806_425.docx
234	Implementación de una Aplicación Web para la gestión y control de inventario de los equipos de computación del Instituto Nacional de Capacitación y Educación Socialista INCES región Trujillo	1	2026	storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792464809_346.docx
235	SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ACADÉMICO ADMINISTRATIVA EN LA ESCUELA TÉCNICA COMERCIAL “PEDRO GARCÍA LEAL	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792464936_959.docx
236	Sistema de Información Automatizado para la Gestión Académica del Centro Bolivariano de Informática y Telemática. Ing. Eli Rojas de la Escuela de Emprendimiento Valera	1	2026	storage/documentos/pst/pst_sistema_de_informaci__n_automa_1792465008_899.docx
237	SISTEMA DE INFORMACIÓN PARA LA GESTION ADMINISTRATIVA DEL SERVICIO NACIONAL DE MEDICINA Y CIENCIAS FORENSES SENAMECF	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792465052_313.pdf
238	APLICACIÓN WEB PARA OPTIMIZAR LA GESTIÓN DE PRODUCTOS Y SERVICIOS EN SUPER KAUCHOS GILSA, C.A	1	2025	storage/documentos/pst/pst_aplicaci__n_web_para_optimizar_1792465101_719.docx
239	SISTEMA INTEGRAL DE ADMINISTRACION DE CASOS (SIAC) EN LA PREFECTURA DEL MUNICIPIO ESCUQUE	1	2025	storage/documentos/pst/pst_sistema_integral_de_administra_1792465188_372.docx
240	SISTEMA INTEGRAL DE CONTROL DE PAGO DE ARANCELES DEL IUNE - VALERA	1	2026	storage/documentos/pst/pst_sistema_integral_de_control_de_1792465317_949.docx
241	SISTEMA PARA LA DIGITALIZACION Y GESTION DE SERVICIOS DE EQUIPOS EN TECNOSERVICE DE MIGUEL MORALES F.P	1	2026	storage/documentos/pst/pst_sistema_para_la_digitalizacion_1792465317_297.docx
242	APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL"	1	2026	storage/documentos/pst/pst_aplicaci__n_web_para_el_proces_1792465317_468.docx
243	SISTEMA DE INVENTARIO PARA EL CONTROL DE BIENES NACIONALES EN LA OFICINA ADMINISTRATIVA VALERA (IVSS)	1	2025	storage/documentos/pst/pst_sistema_de_inventario_para_el__1792465317_371.docx
244	APLICACIÓN WEB PARA LA COOPERATIVA “LA ONCE CON LA ONCE ”, MUNICIPIO VALERA, ESTADO TRUJILLO	1	2024	storage/documentos/pst/pst_aplicaci__n_web_para_la_cooper_1792465317_831.pdf
245	SISTEMA DE GESTIÓN Y CONTROL DE DATOS EDUCATIVOS DE LA UNIDAD EDUCATIVA “HUGO RAFAEL CHÁVEZ FRÍAS	1	2026	storage/documentos/pst/pst_sistema_de_gesti__n_y_control__1792465317_952.docx
246	SISTEMA DE GESTIÓN ESCOLAR PARA LA ESCUELA “VICENTE DE LA TORRE	1	2026	storage/documentos/pst/pst_sistema_de_gesti__n_escolar_pa_1792465317_592.pdf
247	SISTEMA DE INFORMACIÓN ACADÉMICO/ADMINISTRATIVO(SIACAD) DE MUSART ACADEMIA C.A	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_acad___1792465317_908.docx
248	SISTEMA DE INFORMACIÓN PARA EL REGISTRO Y GESTIÓN LABORAL DEL INDET(SIRGL-INDET)	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_e_1792465317_937.doc
249	Sistema de gestión de inventario Web para INME C.A	1	2025	storage/documentos/pst/pst_sistema_de_gesti__n_de_inventa_1792465444_199.docx
250	SISTEMA DE INFORMACIÓN ZOOCÉNTRICO PARA EL CENTRO VETERINARIO INTEGRAL “DR. DIEGO HIDALGO	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_zooc___1792465484_982.docx
251	IMPLEMENTACIÓN DE PORTAL WEB PARA LA EMPRESA PUBLIART	1	2026	storage/documentos/pst/pst_implementaci__n_de_portal_web__1792465511_534.docx
252	SISTEMA DE GESTIÓN DIGITAL PARA EL INVENTARIO, TRAZABILIDAD Y MOVIMIENTO DE BIENES EN EMPRESA TRUJILLANA DE PROYECTOS, INFRAESTRUCTURA Y VIVIENDA S.A	1	2026	storage/documentos/pst/pst_sistema_de_gesti__n_digital_pa_1792465610_953.docx
253	APLICACIÓN WEB PARA EL PROCESO ADMINISTRATIVO DEL DEPARTAMENTO DE EVALUACIÓN DE LA ESCUELA TÉCNICA “PEDRO GARCÍA LEAL	1	2026	storage/documentos/pst/pst_aplicaci__n_web_para_el_proces_1792465617_587.docx
254	Sistema Web de gestión integral del Talento humano para el SAPNNAET	1	2026	storage/documentos/pst/pst_sistema_web_de_gesti__n_integr_1792465617_932.pdf
255	SISTEMA DE INFORMACIÓN PARA LA GESTIÓN ADMNISTRATIVA DEL PERSONAL ADSCRITO A LA VALFOR S.A	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792465712_837.doc
256	Implementación de una aplicación web para la Unidad Educativa Hna. María Sorrosal	1	2025	storage/documentos/pst/pst_implementaci__n_de_una_aplicac_1792465814_784.docx
257	SISTEMA WEB PARA LA GESTIÓN DE PROYECTOS DE LOS CONSEJOS COMUNALES EN EL CONSEJO LOCAL DE PLANIFICACIÓN PÚBLICA (CLPP) DEL MUNICIPIO VALERA	1	2025	storage/documentos/pst/pst_sistema_web_para_la_gesti__n_d_1792465925_304.docx
258	Sistema de información para el registro académico y administrativo del C.E.I Enriqueta Sánchez Vivas	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_para_e_1792466045_640.docx
259	Sistema de Información para la Gestión Administrativa en la “Asociación de Taekwondo del Estado Trujillo"	1	2026	storage/documentos/pst/pst_sistema_de_informaci__n_para_l_1792466421_468.docx
260	Sistema web para la farmacia INSUMEDIC C.A	1	2026	storage/documentos/pst/pst_sistema_web_para_la_farmacia_i_1792466509_133.docx
261	DESARROLLO DE APLICACIÓN WEB PARA EL LABORATORIO CLÍNICO GENÉSIS C.A., VALERA, ESTADO TRUJILLO	1	2026	storage/documentos/pst/pst_desarrollo_de_aplicaci__n_web__1792466560_824.doc
262	SISTEMA DE INFORMACIÓN INTEGRAL PARA LA GESTIÓN DE PACIENTES (SIGP) EN EL CENTRO DE ALTA TECNOLOGÍA "DR. HUMBERTO FERNÁNDEZ MORÁN"	1	2025	storage/documentos/pst/pst_sistema_de_informaci__n_integr_1792466600_140.docx
263	SISTEMA WEB PARA LA GESTION ADMINISTRATIVA DE LA PANTERA PC STORE	1	2026	storage/documentos/pst/pst_sistema_web_para_la_gestion_ad_1792466664_177.docx
264	MANTENIMIENTO PREVENTIVO Y CORRECTIVO A LAS COMPUTADORAS DEL CBIT EN EL COMPLEJO EDUCATIVO ‘HILARIO PIZANI ANSELMI’	1	2026	storage/documentos/pst/pst_mantenimiento_preventivo_y_cor_1792466698_878.docx
265	SOPORTE TECNICO A EQUIPOS Y USUARIOS EN LA ALCALDIA MUNICIPAL RAFAEL RANGEL	1	2026	storage/documentos/pst/pst_soporte_tecnico_a_equipos_y_us_1792466698_240.docx
266	SOPORTE TÉCNICO A EQUIPOS Y USUARIOS PARA EL INFOCENTRO GRAN BASE DE MISIONES POR LA PAZ HUGO CHAVEZ FRIAS	1	2025	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_y_u_1792466698_292.pdf
267	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y A USUARIOS EN DUILIO DE JESUS VALERO ROMERO PANADERIA, PASTELERIA Y LUNCHERIA “LA ESTRELLA DE VALERA”. F. P	1	2025	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466698_639.pdf
268	PABLO VILORIA" - LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A LOS USUARIOS DE EL CIBER CAFÉ PONCHO S.A	1	2026	storage/documentos/pst/pst_pablo_viloria____la_beatriz__s_1792466698_931.pdf
269	NUES “DR. PABLO VILORIA” – LA BEATRIZ. SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y CAPACITACIÓN A USUARIOS DE LA UPTTMBI NUES LA BEATRIZ EN EL LABORATORIO 2 DE INFORMÁTICA	1	2026	storage/documentos/pst/pst_nues____dr__pablo_viloria______1792466699_462.docx
270	SOPORTE TECNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CORPOELEC	1	2026	storage/documentos/pst/pst_soporte_tecnico_a_equipos_de_c_1792466699_830.docx
271	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA NUEVA ELOISA FONSECA	1	2026	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466699_969.docx
272	Soporte Técnico a Equipos de Computación y usuarios en la Empresa “IP SISTEMAS	1	2025	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1792466699_714.docx
273	SOPORTE TECNICO A EQUIPOS TECNOLOGICOS Y USUARIOS DEL DESPACHO DE CARGA SUROCCIDENTE	1	2026	storage/documentos/pst/pst_soporte_tecnico_a_equipos_tecn_1792466699_954.docx
274	SOPORTE TÉCNICO A EQUIPOS Y USUARIOS EN LA CLINICA UGA C.A	1	2026	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_y_u_1792466699_621.docx
275	SOPORTE TÉCNICO A USUARIOS Y EQUIPOS DE LA “ESCUELA TÉCNICA COMERCIAL PEDRO GARCÍA LEAL” DEL MUNICIPIO	1	2026	storage/documentos/pst/pst_soporte_t__cnico_a_usuarios_y__1792466699_227.pdf
276	SOPORTE TÉCNICO A USUARIOS Y EQUIPOS EN EL MINISTERIO DEL PODER POPULAR PARA EL TRANSPORTE, SEDE VALERA - TRUJILLO	1	2026	storage/documentos/pst/pst_soporte_t__cnico_a_usuarios_y__1792466699_832.pdf
278	SOPORTE TÉCNICO A LAS COMPUTADORAS Y CAPACITACIÓN A LOS USUARIOS DEL CBIT DE LA ESCUELA DE EMPRENDIMIENTO VALERA	1	2025	storage/documentos/pst/pst_soporte_t__cnico_a_las_computa_1792466699_339.pdf
279	SISTEMA DIDÁCTICO E INTERACTIVO PARA LA ENSEÑANZA DE MATEMÁTICA BÁSICA EN LA ESCUELA “JOSE ANTONIO VELASCO"	1	2026	storage/documentos/pst/pst_sistema_did__ctico_e_interacti_1792466793_970.docx
280	SOFTWARE EDUCATIVO PARA LA ENSEÑANZA DE MATEMATICAS DE PRIMER AÑO EN EL LICEO “HILARIO PIZANI ANSELMI	1	2026	storage/documentos/pst/pst_software_educativo_para_la_ens_1792467225_223.docx
281	SOFTWARE EDUCATIVO PARA MEJORAR LA ENSEÑANZA DE PROGRAMACIÓN Y DISEÑO DE SOFTWARE Y REDES EN LA E.T. "PEDRO GARCÍA LEAL"	1	2025	storage/documentos/pst/pst_software_educativo_para_mejora_1792467375_320.docx
277	Soporte Técnico de Equipos y Capacitación a Usuarios del Laboratorio 1 de PNFI de la Universidad Politécnica Territorial ¨Mario Briceño Iragorry¨	1	2026	storage/documentos/pst/pst_soporte_t__cnico_de_equipos_y__1792466699_390.docx
282	APLICACIÓN WEB ACADÉMICA PARA EL LICEO ANDRÉS BELLO	1	2025	storage/documentos/pst/pst_aplicaci__n_web_acad__mica_par_1792467868_602.docx
283	APLICACIÓN WEB INFORMATIVA PARA EL FORTALECIMIENTO TECNOLÓGICO DEL PARQUE DE RECREACIÓN BOTAICO “LA BEATRIZ	1	2025	storage/documentos/pst/pst_aplicaci__n_web_informativa_pa_1792467868_987.pdf
284	APLICACIÓN WEB PARA EL COMPLEJO “EDUCATIVO PEDRO PABLO PAREDES	1	2025	storage/documentos/pst/pst_aplicaci__n_web_para_el_comple_1792467869_791.docx
285	APLICACIÓN WEB PARA LA GESTIÓ N DE VENTAS DEL AUTO SERVICIO EL TRIÁNGULO C.A	1	2026	storage/documentos/pst/pst_aplicaci__n_web_para_la_gesti__1792467869_493.pdf
286	APLICACION WEB PARA LA UNIDAD EDUCATIVA “EDUARDO BLANCO	1	2025	storage/documentos/pst/pst_aplicacion_web_para_la_unidad__1792467869_393.docx
287	Página Web en el Grupo Educativo “Eloísa Fonseca	1	2025	storage/documentos/pst/pst_p__gina_web_en_el_grupo_educat_1792467869_197.docx
288	Página Web Institucional para la Unidad Educativa Fé y Alegría “Gonzalo Senior	1	2025	storage/documentos/pst/pst_p__gina_web_institucional_para_1792467869_682.docx
289	PÁGINA WEB INSTITUCIONAL PARA EL COLEGIO LOS CEDROS	1	2025	storage/documentos/pst/pst_p__gina_web_institucional_para_1792467869_791.docx
290	página web municipal con sección al infocentro	1	2025	storage/documentos/pst/pst_p__gina_web_municipal_con_secc_1792467869_869.docx
291	PÁGINA WEB PARA EL CENTRO DE EDUCACIÓN INICIAL “CONSUELO NAVAS TOVAR	1	2025	storage/documentos/pst/pst_p__gina_web_para_el_centro_de__1792467869_467.docx
292	SISTEMA WEB INTEGRAL PARA LA EMPRESA PUBLISYS	1	2026	storage/documentos/pst/pst_sistema_web_integral_para_la_e_1792467869_561.docx
293	SISTEMA WEB PARA LA GESTION DE RECURSOS PEDAGOGICOS DEL LICEO “LIBERTADOR	1	2026	storage/documentos/pst/pst_sistema_web_para_la_gestion_de_1792467869_432.docx
294	SISTEMA WEB PARA LA GETION DE VENTAS DE NATURAL NEI F.P	1	2026	storage/documentos/pst/pst_sistema_web_para_la_getion_de__1792467869_148.pdf
295	Sitio Web en la Comercializadora Francoplast C.A	1	2025	storage/documentos/pst/pst_sitio_web_en_la_comercializado_1792467869_802.docx
296	MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS"	1	2025	storage/documentos/pst/pst_mantenimiento_de_la_red_local__1792468135_366.docx
297	Optimización y modernización de la red de comunicación de CORPOELEC Sucursal: Despacho de Carga Suroccidente	1	2025	storage/documentos/pst/pst_optimizaci__n_y_modernizaci__n_1792468236_689.pdf
298	Sistema de control de acceso al laboratorio informático de la E.T "Pedro García Leal	1	2025	storage/documentos/pst/pst_sistema_de_control_de_acceso_a_1792469356_655.docx
299	SISTEMA DE GESTION WEB PARA LA ESCUELA TECNICA “MONSEÑOR ESTANISLAO CARRILLO	1	2026	storage/documentos/pst/pst_sistema_de_gestion_web_para_la_1792469356_888.pdf
300	SISTEMA DE INFORMACION PARA LA OPTIMIZACION DEL ACCESO Y CONTROL DE HISTORIAS MÉDICAS EN EL C.C.C. DR. JOSE GREGORIO HERNANDEZ C.A	1	2025	storage/documentos/pst/pst_sistema_de_informacion_para_la_1792469356_848.docx
301	SISTEMA PARA CONTROL Y SEGUIMIENTO DE ASISTENCIAS DEL PROFESORADO EN LA ESCUELA TÉCNICA: “PEDRO GARCÍA LEAL	1	2025	storage/documentos/pst/pst_sistema_para_control_y_seguimi_1792469356_585.docx
302	SISTEMA WEB DE GESTIÓN DE VENTAS PARA INVERSIONES M.Q. DE MIRLA QUINTERO	1	2026	storage/documentos/pst/pst_sistema_web_de_gesti__n_de_ven_1792469356_550.docx
303	MANTENIMIENTO DE LA RED LOCAL DEL LABORATORIO II EN LA ESCUELA TÉCNICA “MADRE RAFOLS	1	2025	storage/documentos/pst/pst_mantenimiento_de_la_red_local__1792469396_397.docx
304	REESTRUCTURACIÓN Y MANTENIMIENTO DE LA RED LAN EN LA DIRECCIÓN GENERAL DE PLANIFICACIÓN Y PRESUPUESTO DE LA GOBERNACIÓN DEL ESTADO TRUJILLO	1	2025	storage/documentos/pst/pst_reestructuraci__n_y_mantenimie_1792469679_613.docx
305	SISTEMA DE RED WLAN PARA LA CONEXION DEL CBIT DE LA ESCUELA “TOSTA GARCIA	1	2025	storage/documentos/pst/pst_sistema_de_red_wlan_para_la_co_1792469769_917.docx
\.


--
-- TOC entry 5806 (class 0 OID 51686)
-- Dependencies: 269
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registro_actividad (id, id_usuario, id_visitante, fecha_inicial, ultima_actividad, conteo_accesos) FROM stdin;
1	1	\N	2026-03-23 14:49:58	2026-03-23 14:49:58	1
3	12	\N	2026-09-19 14:19:44.068925	2026-09-19 14:20:25.834661	2
4	17	\N	2026-09-19 14:57:43.371674	2026-09-19 14:58:00.847148	2
5	24	\N	2026-10-14 12:21:02.057086	2026-10-14 12:21:02.057086	1
2	7	\N	2026-09-19 13:23:18.589777	2026-11-02 12:43:49.411214	82
\.


--
-- TOC entry 5808 (class 0 OID 51694)
-- Dependencies: 271
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, nombre, privilegio_id) FROM stdin;
1	Super Administrador	1
3	Estudiantes	6
2	Comité	2
4	Docentes	3
\.


--
-- TOC entry 5839 (class 0 OID 60424)
-- Dependencies: 302
-- Data for Name: seg_lineas_alertas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_alertas (id, fecha, tipo, severidad, titulo, detalle, ip, usuario_id, estado, atendida_por, atendida_en) FROM stdin;
2	2026-10-11 13:25:51	CADENA_ALTERADA	CRITICO	Cadena de custodia alterada	1 anomalía(s) detectada(s) en la cadena de custodia. Primer registro afectado: #2		\N	atendida	Miguel González (ID 7)	2026-10-11 13:26:29
1	2026-10-11 13:18:51	CADENA_ALTERADA	CRITICO	Cadena de custodia alterada	1 anomalía(s) detectada(s) en la cadena de custodia. Primer registro afectado: #2		\N	atendida	Miguel González (ID 7)	2026-10-11 13:26:32
3	2026-10-11 13:26:40	CADENA_ALTERADA	CRITICO	Cadena de custodia alterada	1 anomalía(s) detectada(s) en la cadena de custodia. Primer registro afectado: #2		\N	atendida	Miguel González (ID 7)	2026-10-11 13:27:00
\.


--
-- TOC entry 5837 (class 0 OID 60403)
-- Dependencies: 300
-- Data for Name: seg_lineas_bloqueos; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_bloqueos (id, ip, motivo, origen, creado_en, expira_en, creado_por, activo, levantado_por, levantado_en) FROM stdin;
\.


--
-- TOC entry 5835 (class 0 OID 60382)
-- Dependencies: 298
-- Data for Name: seg_lineas_excepciones; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_excepciones (id, usuario_id, submodulo, accion, efecto, motivo, expira_en, creado_por, creado_en) FROM stdin;
\.


--
-- TOC entry 5832 (class 0 OID 60331)
-- Dependencies: 295
-- Data for Name: seg_lineas_logs; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_logs (id, fecha, severidad, submodulo, accion, ruta, resultado, usuario_id, usuario_nombre, nivel, ip, metodo, user_agent, detalles, hash_anterior, hash) FROM stdin;
1	2026-10-10 12:09:41	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36	Cadena íntegra: 0 eslabones verificados con HMAC-SHA256.	GENESIS_SEGURIDAD_LINEAS_CIIDI_V1	056b60352052b21cf6286edcf7f6fa75754ece55470654d9a975459b19ae2436
3	2026-10-10 12:10:18	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3bbb0e0d7951d3d5966ac5225b86c125daf814c34396394cbee2f364babbc65b	a70c3cea55255f29230551c6b9dd49318df6ee8aaeaf2d352155d6180c5d59c5
4	2026-10-10 12:12:59	AVISO	SeguridadLineas	ver	seguridad-lineas-permisos	DENEGADO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36	[MATRIZ] Matriz de permisos: Visitante (sin sesión) no tiene «Ver» en «Seguridad».	a70c3cea55255f29230551c6b9dd49318df6ee8aaeaf2d352155d6180c5d59c5	f922d8c3d9f74df9f1721660130a6204b2564c13cb0dc328cfce8a94f8167b91
5	2026-10-11 11:22:55	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	f922d8c3d9f74df9f1721660130a6204b2564c13cb0dc328cfce8a94f8167b91	743f5741f9cf8c67c279a09014b9250636ba64eb2d303903ad9f0097ed489bcd
6	2026-10-11 11:23:47	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 5 eslabones verificados con HMAC-SHA256.	743f5741f9cf8c67c279a09014b9250636ba64eb2d303903ad9f0097ed489bcd	02444df640c2e1bee48482765ea6ac94afa4db9b3dc672e7c7fb94f67beefa1a
7	2026-10-11 12:01:24	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 6 eslabones verificados con HMAC-SHA256.	02444df640c2e1bee48482765ea6ac94afa4db9b3dc672e7c7fb94f67beefa1a	33a452ebb047e19907f94eec218a8beea49df1e232d0b1513e1a13c8a9b29130
8	2026-10-11 13:10:54	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 7 eslabones verificados con HMAC-SHA256.	33a452ebb047e19907f94eec218a8beea49df1e232d0b1513e1a13c8a9b29130	4b1dbaccc6fca6d62425b2bf1c9498a3dc57f562522b1071a7b2347630343ddb
9	2026-10-11 13:11:11	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 8 eslabones verificados con HMAC-SHA256.	4b1dbaccc6fca6d62425b2bf1c9498a3dc57f562522b1071a7b2347630343ddb	6f84eddb9e6b38fc90565e2b2aee4a38c91cadd3bdcd7c3919225d48352735f4
10	2026-10-11 13:15:38	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 9 eslabones verificados con HMAC-SHA256.	6f84eddb9e6b38fc90565e2b2aee4a38c91cadd3bdcd7c3919225d48352735f4	cc25fe9ecd9464fcee2058ca834b62ac1c14cd1079feef059d3b94e9beb30275
11	2026-10-11 13:15:44	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 10 eslabones verificados con HMAC-SHA256.	cc25fe9ecd9464fcee2058ca834b62ac1c14cd1079feef059d3b94e9beb30275	feb227209638a204e8c964161e0aae4490ec12dd79271d8bbeab793cacfb4586
12	2026-10-11 13:15:45	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 11 eslabones verificados con HMAC-SHA256.	feb227209638a204e8c964161e0aae4490ec12dd79271d8bbeab793cacfb4586	667469eeb129f405307bb201cbca8faab1b81696c26411a90910ae02317d5f3b
18	2026-10-11 13:27:00	INFO	SeguridadLineas	atender-alerta	seguridad-lineas-alerta-atender	ADMIN	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Alerta #3 marcada como atendida.	d9cec887ff7ffe5e872200c38e834987299a86979b4f4ff45ed9025a26047549	3fd8849600ab196866b758f4dde74e1fc5ca8f414b0a3dd143553bdeb88d19b7
13	2026-10-11 13:18:51	CRITICO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	1 anomalía(s) detectada(s) en la cadena de custodia.	667469eeb129f405307bb201cbca8faab1b81696c26411a90910ae02317d5f3b	e1ac2c4be98ff374352fd136926a99391d9b421d276b40f37455f09333692c7d
14	2026-10-11 13:25:51	CRITICO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	1 anomalía(s) detectada(s) en la cadena de custodia.	e1ac2c4be98ff374352fd136926a99391d9b421d276b40f37455f09333692c7d	5667ebd0bf644bf1322bdb20d878b46936a4ab92ccdfffcf02cf3a0945184cf0
15	2026-10-11 13:26:29	INFO	SeguridadLineas	atender-alerta	seguridad-lineas-alerta-atender	ADMIN	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Alerta #2 marcada como atendida.	5667ebd0bf644bf1322bdb20d878b46936a4ab92ccdfffcf02cf3a0945184cf0	bd43bfbf3f691e9c4bf076ca12a6d7f75a1036863e839644065456b717e93a18
16	2026-10-11 13:26:32	INFO	SeguridadLineas	atender-alerta	seguridad-lineas-alerta-atender	ADMIN	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Alerta #1 marcada como atendida.	bd43bfbf3f691e9c4bf076ca12a6d7f75a1036863e839644065456b717e93a18	2acd379b9c0ce6434ca4ed2e95d72a415f1fc8d3937028eeda3f1f77aa75b5c9
17	2026-10-11 13:26:40	CRITICO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	1 anomalía(s) detectada(s) en la cadena de custodia.	2acd379b9c0ce6434ca4ed2e95d72a415f1fc8d3937028eeda3f1f77aa75b5c9	d9cec887ff7ffe5e872200c38e834987299a86979b4f4ff45ed9025a26047549
2	2026-10-10 12:10:15	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	056b60352052b21cf6286edcf7f6fa75754ece55470654d9a975459b19ae2436	3bbb0e0d7951d3d5966ac5225b86c125daf814c34396394cbee2f364babbc65b
19	2026-10-11 13:27:03	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 18 eslabones verificados con HMAC-SHA256.	3fd8849600ab196866b758f4dde74e1fc5ca8f414b0a3dd143553bdeb88d19b7	949960d2d0acdb0df3d67c0fdb2734ee37ccc91bd15f71efac5d484f3b1e8cea
20	2026-10-11 13:47:50	INFO	SeguridadLineas	verificar-cadena	seguridad-lineas-verificar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Cadena íntegra: 19 eslabones verificados con HMAC-SHA256.	949960d2d0acdb0df3d67c0fdb2734ee37ccc91bd15f71efac5d484f3b1e8cea	95f0e2335bcca11b5992a22b08a18000f6b9582aacaef44ac3d55ee94a9b1511
21	2026-10-11 14:06:58	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	95f0e2335bcca11b5992a22b08a18000f6b9582aacaef44ac3d55ee94a9b1511	5254056c42506ccb0ac99f0d99af0e0310c4f10f6fd5e6ab3e0b1011076008a6
22	2026-10-11 14:07:01	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5254056c42506ccb0ac99f0d99af0e0310c4f10f6fd5e6ab3e0b1011076008a6	c77236bce978ae5376be4d8476c3f75a594fb2310908bdaedde6624780905266
23	2026-10-12 12:09:03	INFO	SeguridadLineas	exportar-bitacora	seguridad-lineas-exportar	ADMIN	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Exportación CSV de la bitácora. Filtros: []	c77236bce978ae5376be4d8476c3f75a594fb2310908bdaedde6624780905266	2c802d83d744657ffb1ac76173f304d7119e318bb563440d11a1950a1ef5bc73
24	2026-10-12 12:32:50	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2c802d83d744657ffb1ac76173f304d7119e318bb563440d11a1950a1ef5bc73	89f67e7a728c745f8cd5be259e059e7d2bcce3ac4a3ce62371d3eb0eace1c6b8
25	2026-10-12 12:40:12	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	89f67e7a728c745f8cd5be259e059e7d2bcce3ac4a3ce62371d3eb0eace1c6b8	cefe478a184c5de694e949b682875f520a44fbc0d12b4cce3e7c1c020314fe1e
26	2026-10-12 17:49:52	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	cefe478a184c5de694e949b682875f520a44fbc0d12b4cce3e7c1c020314fe1e	1433ec2c0843b19433f8d628bc31bd061c06f6ef668e7cb7c1280ce6a0093dfc
27	2026-10-12 17:54:45	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1433ec2c0843b19433f8d628bc31bd061c06f6ef668e7cb7c1280ce6a0093dfc	c665d0515c273df9a9ebd0c90d1d271e87a79033f57c35f78a1dc7a51fee0572
28	2026-10-12 17:54:49	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c665d0515c273df9a9ebd0c90d1d271e87a79033f57c35f78a1dc7a51fee0572	69395d536fd272eaca22481fe8c7654f1c4835f67a0053939b7b16bff6dc6ca9
29	2026-10-12 17:54:52	INFO	VinculacionEmpresarial	ver	profesores-pst	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	69395d536fd272eaca22481fe8c7654f1c4835f67a0053939b7b16bff6dc6ca9	def419dcec33392502b328c3827da8dd597db5af38d6383694b2aee0c499212f
30	2026-10-12 19:41:16	INFO	VinculacionEmpresarial	ver	profesores-pst	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	def419dcec33392502b328c3827da8dd597db5af38d6383694b2aee0c499212f	7c5fdf859c07e45b2a1fcf2f292d4dc61cbefd3ddda578fbbce4ef1ff06b3888
31	2026-10-12 19:41:34	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7c5fdf859c07e45b2a1fcf2f292d4dc61cbefd3ddda578fbbce4ef1ff06b3888	a6e1447eed0baf7a92c3d7ae7320f78867c92d1a18077e01850a7290b5a992bd
32	2026-10-12 19:41:45	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a6e1447eed0baf7a92c3d7ae7320f78867c92d1a18077e01850a7290b5a992bd	df11951f83ca0e457097d85f241f20e37d2e0268fb788b35cc0c6a7c226a1014
33	2026-10-12 19:41:50	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	df11951f83ca0e457097d85f241f20e37d2e0268fb788b35cc0c6a7c226a1014	0f7bdf195d0ea619c9d665e89f60ebce65eda471410d68657514b2d0a59ac108
34	2026-10-12 19:42:03	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0f7bdf195d0ea619c9d665e89f60ebce65eda471410d68657514b2d0a59ac108	37509d509583eb9261464f394969e487b22c345f2503cb40cd4704d7d8e88f13
35	2026-10-12 19:42:06	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	37509d509583eb9261464f394969e487b22c345f2503cb40cd4704d7d8e88f13	6e9b0594654f08c32b22b5918be69521111e19f62217f31392ced9f970cdde84
36	2026-10-12 19:42:11	INFO	Cursos	ver	cursos-preinscripcion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6e9b0594654f08c32b22b5918be69521111e19f62217f31392ced9f970cdde84	61af12880f80973ea576b91987b99e0069f851ee1f9c4db453e0daaa493612aa
37	2026-10-12 19:42:13	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	61af12880f80973ea576b91987b99e0069f851ee1f9c4db453e0daaa493612aa	0277bd26c879000d56e4bcf5624d7602fd088f9d74f8533de9a6159c7eff9e44
38	2026-10-12 19:42:18	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0277bd26c879000d56e4bcf5624d7602fd088f9d74f8533de9a6159c7eff9e44	cded9f84937ec400f1eba96234c079d86cff787a850c6238ecdeb7daf2ef7039
39	2026-10-12 19:42:29	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cded9f84937ec400f1eba96234c079d86cff787a850c6238ecdeb7daf2ef7039	4d391ed0184047ea60455b184c90e916f2bb5916621f8bb8d7846529fda918d3
40	2026-10-12 19:42:42	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4d391ed0184047ea60455b184c90e916f2bb5916621f8bb8d7846529fda918d3	be207e9b33b6f57c1518b9c286ed62bbc1c99ac2474e5667634ace955c2cf330
41	2026-10-12 19:42:46	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	be207e9b33b6f57c1518b9c286ed62bbc1c99ac2474e5667634ace955c2cf330	718b7af8e6b0ae6f38b0afa5eafb34c4b865e61a9953a6bdf2da9970e0c60a42
42	2026-10-12 19:42:50	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	718b7af8e6b0ae6f38b0afa5eafb34c4b865e61a9953a6bdf2da9970e0c60a42	40b5333032876cd622deb290b515efd64f7586d66f29bcaeda9824be58664677
43	2026-10-12 19:42:52	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	40b5333032876cd622deb290b515efd64f7586d66f29bcaeda9824be58664677	8888cb9737668ccb439d25389e6f2498eb111238a14398ce5af535fdcaac717a
44	2026-10-12 19:55:50	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8888cb9737668ccb439d25389e6f2498eb111238a14398ce5af535fdcaac717a	932ab8978d47fc22f44025e6a9dce91f9d163be0079983e3b983fc502a91a53f
45	2026-10-12 20:17:08	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	932ab8978d47fc22f44025e6a9dce91f9d163be0079983e3b983fc502a91a53f	32f5435ff3ef6a8d29eaf459debb42068ab601210b7d66656a90cd59b66d7952
46	2026-10-12 20:17:11	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	32f5435ff3ef6a8d29eaf459debb42068ab601210b7d66656a90cd59b66d7952	b17589b8840d31c092b72d6733671df52151772c710ba441cb0d6f05c83861bd
47	2026-10-12 20:17:21	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b17589b8840d31c092b72d6733671df52151772c710ba441cb0d6f05c83861bd	0f44a5649903b384525061c1e822610b6ab235c58b6dcb100f070cac75d42f7b
48	2026-10-12 20:18:06	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0f44a5649903b384525061c1e822610b6ab235c58b6dcb100f070cac75d42f7b	c34bb9ac1dc46aebb16c380f0507a96fc0a69917581238ffa55f843668388b91
49	2026-10-12 20:18:27	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c34bb9ac1dc46aebb16c380f0507a96fc0a69917581238ffa55f843668388b91	3aba6f99521cbbb9a6e2a31dab16c0723461e31e16cea62e5efc6d1888454495
50	2026-10-12 20:28:42	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3aba6f99521cbbb9a6e2a31dab16c0723461e31e16cea62e5efc6d1888454495	e622b3bdb25e6e296a43fc81f51c569d1c0643e3ad1cc8490494bed05c85d045
51	2026-10-12 20:28:44	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e622b3bdb25e6e296a43fc81f51c569d1c0643e3ad1cc8490494bed05c85d045	8e5023c4a28c29cb42e91799f20efbc283b1632986ba684888f6ea42e3b29298
52	2026-10-12 20:28:47	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8e5023c4a28c29cb42e91799f20efbc283b1632986ba684888f6ea42e3b29298	c5d1ab4284262e9a62571cfd0d916304a3ebf97ece0acedebf31318666b87fe9
53	2026-10-12 20:52:07	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c5d1ab4284262e9a62571cfd0d916304a3ebf97ece0acedebf31318666b87fe9	c15f0ea7849e22d3a5870e61c2cd9ad0ad4a9e83b00101b736d97ba22ac877a1
54	2026-10-12 20:52:11	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c15f0ea7849e22d3a5870e61c2cd9ad0ad4a9e83b00101b736d97ba22ac877a1	55adc4972e7a1cd92ab8068712cc22ddb78a9905c732b481196db0dacd19bd1c
55	2026-10-12 20:52:19	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	55adc4972e7a1cd92ab8068712cc22ddb78a9905c732b481196db0dacd19bd1c	aac3ebb6d8966933f851c2db2735a225ad53a3ba2d3825f0e1e810803018a028
56	2026-10-12 20:52:30	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	aac3ebb6d8966933f851c2db2735a225ad53a3ba2d3825f0e1e810803018a028	1b45478de676a9af2b055a1478f5a1a95157ea3fb4c38d0fa93752b36e9ba9b1
57	2026-10-12 20:55:23	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1b45478de676a9af2b055a1478f5a1a95157ea3fb4c38d0fa93752b36e9ba9b1	dc7517805f606502da299c71fc465ebc01cca3f649e11157651139eef429aedd
58	2026-10-12 20:55:31	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	dc7517805f606502da299c71fc465ebc01cca3f649e11157651139eef429aedd	1fb5ec831cc4b14dc37165f77f326d25a18ff0ae1e18d8e6317dfcba45b21c0f
59	2026-10-12 21:21:15	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1fb5ec831cc4b14dc37165f77f326d25a18ff0ae1e18d8e6317dfcba45b21c0f	cc560c7e7cd58a2cf9150cb537d76de06c4999955f6616a508b4e7be32213fe0
60	2026-10-12 21:25:51	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cc560c7e7cd58a2cf9150cb537d76de06c4999955f6616a508b4e7be32213fe0	3fef7056b2d0108f2822d717e334187cd62aa70cd25ded628d4a6934e8884aa3
61	2026-10-12 21:33:27	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3fef7056b2d0108f2822d717e334187cd62aa70cd25ded628d4a6934e8884aa3	e711e71f58471a048e902335f8a67fc2c343ec6b2de6f678fb305816afc96599
62	2026-10-12 21:33:29	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e711e71f58471a048e902335f8a67fc2c343ec6b2de6f678fb305816afc96599	b89fdbd639bd16a21e6c6bea2b10d45a0352d788f4624f382c980d91aef90887
63	2026-10-12 21:33:31	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b89fdbd639bd16a21e6c6bea2b10d45a0352d788f4624f382c980d91aef90887	fca511ba6ba48bda7bab3091d95e692c6d7cc286c0b48dc2f387c4408f9e5d48
64	2026-10-12 21:33:34	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fca511ba6ba48bda7bab3091d95e692c6d7cc286c0b48dc2f387c4408f9e5d48	ae19cd56019d303343fde1d0a624813de8fdc60993cb289ba04c96c402bbafc9
65	2026-10-12 21:33:38	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ae19cd56019d303343fde1d0a624813de8fdc60993cb289ba04c96c402bbafc9	e354dfd20269f16a86111e596cc1c03e2e43ef32181c02eacd7e4be634edc03c
66	2026-10-12 21:33:39	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e354dfd20269f16a86111e596cc1c03e2e43ef32181c02eacd7e4be634edc03c	adcad457c3b401c1d3387bb548fbe5f27cf71b8da796ba31c9ccd98297bf174a
67	2026-10-12 21:37:03	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	adcad457c3b401c1d3387bb548fbe5f27cf71b8da796ba31c9ccd98297bf174a	e7dafd799d293843daafe2eced4edf1197322792d4bc584895392afc5247aba7
68	2026-10-12 21:37:12	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e7dafd799d293843daafe2eced4edf1197322792d4bc584895392afc5247aba7	2cd59886ce025be0f805734eb07b40761f97b66f60a30a23411e65f222c63232
69	2026-10-12 21:37:15	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2cd59886ce025be0f805734eb07b40761f97b66f60a30a23411e65f222c63232	25414df40339245ed7bff8afc18d4076575159bd830e6df784b5cbf33b81d4a9
70	2026-10-12 21:37:19	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	25414df40339245ed7bff8afc18d4076575159bd830e6df784b5cbf33b81d4a9	f11f0cc310ac1b5decdc856f60df5a7be13e14b2b4adf8b0a323f61e791e8b87
71	2026-10-12 21:37:22	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f11f0cc310ac1b5decdc856f60df5a7be13e14b2b4adf8b0a323f61e791e8b87	7ecebee428f8f2bf181e833957aea6d0ac3ba62343d10f3abe76863d6eba8abc
72	2026-10-12 21:37:24	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7ecebee428f8f2bf181e833957aea6d0ac3ba62343d10f3abe76863d6eba8abc	44bb864cdd9edd171a296d8c6913183760ac4ec5dd97a5f0d1072bf5fecca348
73	2026-10-12 21:37:26	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	44bb864cdd9edd171a296d8c6913183760ac4ec5dd97a5f0d1072bf5fecca348	cdb8fede3d83d392f87ef04249d828f58df6984e2c960a7ade95b62960087598
74	2026-10-12 21:37:31	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cdb8fede3d83d392f87ef04249d828f58df6984e2c960a7ade95b62960087598	acf0fa5289468071763be6379979b1eaca366537a056cf8b0154b1cf50b333c6
75	2026-10-12 21:37:35	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	acf0fa5289468071763be6379979b1eaca366537a056cf8b0154b1cf50b333c6	ba6673c460617b9ed9763765d0d9fc7114ad89a396cd511e865efc86ca909036
76	2026-10-12 21:37:47	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ba6673c460617b9ed9763765d0d9fc7114ad89a396cd511e865efc86ca909036	33fefbb8334267e368344f5fc12418fb44ce61375fc966d87ded403f83626a37
77	2026-10-12 21:37:50	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	33fefbb8334267e368344f5fc12418fb44ce61375fc966d87ded403f83626a37	c41be9c5f7d26626a2e21eb84a6a7b133be9aa2702a447b06a7d0934ab3bfc2e
78	2026-10-12 21:37:55	INFO	Cursos	ver	cursos-preinscripcion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c41be9c5f7d26626a2e21eb84a6a7b133be9aa2702a447b06a7d0934ab3bfc2e	003a9d8aef0dfce5497f40ecb9549e03a3df72eacb9750c36fbd4d0fd5f0b669
79	2026-10-12 21:38:08	INFO	Cursos	editar	cursos-procesar-preinscripcion	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	003a9d8aef0dfce5497f40ecb9549e03a3df72eacb9750c36fbd4d0fd5f0b669	7de6fcf19f79dde5cb1dde07a9b845129a07db42dc78c3a9f588c4cfa828178a
80	2026-10-12 21:38:08	INFO	Cursos	ver	cursos-preinscripcion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7de6fcf19f79dde5cb1dde07a9b845129a07db42dc78c3a9f588c4cfa828178a	804effd758f1b61198cf84e953a59bb8cbb97a9d1522f9643bcbcbd48f648391
81	2026-10-12 21:38:16	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	804effd758f1b61198cf84e953a59bb8cbb97a9d1522f9643bcbcbd48f648391	869b05b28dab886a71531e150c26ee982bbc1bb98958a29b95b090335ff0b0ca
82	2026-10-12 21:38:19	INFO	Cursos	editar	cursos-editar	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	869b05b28dab886a71531e150c26ee982bbc1bb98958a29b95b090335ff0b0ca	92d955a1debbc3e6c07efb4efa95a8f6d5aeca4beac6b468e6fecdc1ee0e4148
83	2026-10-12 21:38:25	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	92d955a1debbc3e6c07efb4efa95a8f6d5aeca4beac6b468e6fecdc1ee0e4148	5878ab9cf479bf26d5ba8f4ee3c18ec2725f863da34e5b36ca7783b2cca95f30
84	2026-10-12 21:38:31	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5878ab9cf479bf26d5ba8f4ee3c18ec2725f863da34e5b36ca7783b2cca95f30	60272502ca2bd45e4059f469d9be9491933a8f9cdffaa13f279ba2a91e6907b4
85	2026-10-12 21:38:34	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	60272502ca2bd45e4059f469d9be9491933a8f9cdffaa13f279ba2a91e6907b4	1474476b3dc6f94f7773c842915620a7c93bed0cf5a2163ec3b0cbd3120e5a2d
86	2026-10-12 21:38:45	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1474476b3dc6f94f7773c842915620a7c93bed0cf5a2163ec3b0cbd3120e5a2d	151c5d2920a282cb5911327fc5a0592705fccfdf1a38a6f8266173d6b8ea4541
87	2026-10-12 21:38:58	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	151c5d2920a282cb5911327fc5a0592705fccfdf1a38a6f8266173d6b8ea4541	d35b7fc2b6defbd19591cc35f13fc47e2849ba9138cb3119669a14c26b64e8c1
88	2026-10-12 21:40:38	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d35b7fc2b6defbd19591cc35f13fc47e2849ba9138cb3119669a14c26b64e8c1	892ef12604b4b50a08799c4c34150c20a6a8e0b284a5a59f6678b151642ea74a
89	2026-10-12 21:40:40	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	892ef12604b4b50a08799c4c34150c20a6a8e0b284a5a59f6678b151642ea74a	a73fc4fda67648eccec646154cdebf7e734a4a8f95c4be0633347486d2d05de3
90	2026-10-12 21:40:42	INFO	Cursos	ver	cursos-preinscripcion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a73fc4fda67648eccec646154cdebf7e734a4a8f95c4be0633347486d2d05de3	a5165430f97ea6e15d229e935d01766a5cb4da5e04c972533e73859fe0a0da4f
164	2026-10-14 11:53:03	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1aaf1bc8f39da9c9f2ad77bce5a0a6eac400181ecd6c4577224f57c78ee90237	7b30db5147347c2ea23107d12134aefedc735b9efd67407da3e983eb998bb50b
91	2026-10-12 21:57:46	AVISO	SeguridadLineas	guardar-politicas	seguridad-lineas-politicas-guardar	ADMIN	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Políticas actualizadas: refresco_feed_seg=100	a5165430f97ea6e15d229e935d01766a5cb4da5e04c972533e73859fe0a0da4f	951bc1a478328710b383532ce189ac1601efe14d3454bac9584c1cd70a548e0b
92	2026-10-12 21:58:14	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	951bc1a478328710b383532ce189ac1601efe14d3454bac9584c1cd70a548e0b	79cc4fb1311eb231588f8b9eb10f6a72c971f06ce477bf8dacb439f2fe37e525
93	2026-10-12 22:00:28	AVISO	VinculacionEmpresarial	configurar	vinculacion-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	79cc4fb1311eb231588f8b9eb10f6a72c971f06ce477bf8dacb439f2fe37e525	ab803d2e13cc340162b4f450e4bc021a5a3565f095bfbcc2167430a26b4db6a1
94	2026-10-12 22:00:52	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ab803d2e13cc340162b4f450e4bc021a5a3565f095bfbcc2167430a26b4db6a1	7283c1aa7a6cc81a9f27a7663f9a2fd28393858aa9d3852483e9c57adac6962b
95	2026-10-12 22:01:02	INFO	VinculacionEmpresarial	ver	profesores-pst	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7283c1aa7a6cc81a9f27a7663f9a2fd28393858aa9d3852483e9c57adac6962b	60e405ba7f7da2e54e5487f5c59c0851e6eae00806708fb88ab70f952ce64cd0
96	2026-10-12 22:01:38	INFO	VinculacionEmpresarial	ver	seguimiento-empresa	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	60e405ba7f7da2e54e5487f5c59c0851e6eae00806708fb88ab70f952ce64cd0	7ab2368b22a8259f7b8b64a0e7c5e8458e66a9bd3bd7025219f0df633007f43a
97	2026-10-12 22:01:41	INFO	VinculacionEmpresarial	ver	cartelera-oportunidades	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7ab2368b22a8259f7b8b64a0e7c5e8458e66a9bd3bd7025219f0df633007f43a	a7f63bba1fa5dee6d0854be6fc9bc863fc32a91f0fe9e6378f5b327b816f964a
98	2026-10-12 22:02:10	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a7f63bba1fa5dee6d0854be6fc9bc863fc32a91f0fe9e6378f5b327b816f964a	440a555b4755e34f2e53717efc2caaac8e5703bb8d7dc82de491b7a69f376572
99	2026-10-12 22:02:21	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	440a555b4755e34f2e53717efc2caaac8e5703bb8d7dc82de491b7a69f376572	cdb9c1a8769068dcafc900f764c78317e43cd5edb5cc4e817a10314f484e8286
100	2026-10-12 22:02:50	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cdb9c1a8769068dcafc900f764c78317e43cd5edb5cc4e817a10314f484e8286	01b5d7497e1b90df73d8aaf52875440a469dcfef5f517ccb6821073ec325ccc0
101	2026-10-12 22:02:57	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	01b5d7497e1b90df73d8aaf52875440a469dcfef5f517ccb6821073ec325ccc0	78e3ccf262d0520fa6f92b3b8a380ba1dc7ee84570928e542c69f074d2406eed
102	2026-10-12 22:03:40	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	78e3ccf262d0520fa6f92b3b8a380ba1dc7ee84570928e542c69f074d2406eed	5f2e72ebcd5e15049c0cb3f69e7c1ee19bd417679607eb59218c4e3f015334c6
103	2026-10-12 22:03:45	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5f2e72ebcd5e15049c0cb3f69e7c1ee19bd417679607eb59218c4e3f015334c6	58433f4130cfdc45d87998d31b63713a2e73be089300a98340136a0f5bb68193
104	2026-10-12 22:04:09	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	58433f4130cfdc45d87998d31b63713a2e73be089300a98340136a0f5bb68193	08a41fdd2812aebae53a5ae57c16045d945eb243940bb509e90d372d357a5dea
105	2026-10-12 22:04:31	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	08a41fdd2812aebae53a5ae57c16045d945eb243940bb509e90d372d357a5dea	f6589a91f737f3daa719e92d36d60c7d53d156f977f5fd9a88d9ced4e980b787
106	2026-10-12 22:06:02	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f6589a91f737f3daa719e92d36d60c7d53d156f977f5fd9a88d9ced4e980b787	ef968e8b3564c6008d3c28ae6d595a2d2669317295d27f36ddcc5b3e17a41427
107	2026-10-12 22:06:38	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ef968e8b3564c6008d3c28ae6d595a2d2669317295d27f36ddcc5b3e17a41427	8a46a57ff265f4da1ef99963462181fe1ea25705cfb003d092b4f43e8b3bed21
108	2026-10-12 22:06:41	INFO	Cursos	crear	cursos-crear	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8a46a57ff265f4da1ef99963462181fe1ea25705cfb003d092b4f43e8b3bed21	81fd39e7dacfe0cb085156d0c6ef58fc0403e289d8aecaa30c5a788a014580ca
109	2026-10-12 22:08:04	INFO	Cursos	crear	cursos-procesar-crear	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	81fd39e7dacfe0cb085156d0c6ef58fc0403e289d8aecaa30c5a788a014580ca	623d2ef86c36e520c0e1881fd97f164ae5c72a52fe8ab2d8284a514d45533bab
110	2026-10-12 22:08:04	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	623d2ef86c36e520c0e1881fd97f164ae5c72a52fe8ab2d8284a514d45533bab	5613a44751c2b81c22d89c2b97b04222c066c8021c5c51582350c702b891b60c
111	2026-10-12 22:08:12	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5613a44751c2b81c22d89c2b97b04222c066c8021c5c51582350c702b891b60c	8346022788c4c5b82be0680ccb607b877bb10d5d4eb35a0d7b93f37ad546403d
112	2026-10-12 22:08:15	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8346022788c4c5b82be0680ccb607b877bb10d5d4eb35a0d7b93f37ad546403d	30446b1652f0e99c208c104dec39241490c35f2bb72a971ba62491b50dcf618f
113	2026-10-12 22:08:21	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	30446b1652f0e99c208c104dec39241490c35f2bb72a971ba62491b50dcf618f	13bbda1de4b840080ee15cdbb4a1eee5e1904b3419937686c2d052504551579d
114	2026-10-12 22:08:30	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	13bbda1de4b840080ee15cdbb4a1eee5e1904b3419937686c2d052504551579d	72e57146af88030ef7a6a6fe71c7881a50c118db70019e08a186b547609abcad
115	2026-10-12 22:09:03	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	72e57146af88030ef7a6a6fe71c7881a50c118db70019e08a186b547609abcad	07c1bce0f18949c08a338f4a16ebf52f8bfbdda7706cfcc1bfb13309f6f54f39
116	2026-10-12 22:09:08	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	07c1bce0f18949c08a338f4a16ebf52f8bfbdda7706cfcc1bfb13309f6f54f39	a19a34d981f9825de74b9b362c2616a996ff7f5911fe5baffeb4aae2e62809fc
117	2026-10-12 22:10:02	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a19a34d981f9825de74b9b362c2616a996ff7f5911fe5baffeb4aae2e62809fc	cfa49ec310fa958413a1654953a15f31cd3188dc6a4c6445b45ea89f9039a650
118	2026-10-12 22:10:10	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cfa49ec310fa958413a1654953a15f31cd3188dc6a4c6445b45ea89f9039a650	8ea1945d1c934be5861e0a80c9ead68b7d7bf2fc4c7cc4b2d166812d40d667a9
119	2026-10-12 22:10:15	INFO	Cursos	editar	cursos-editar	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8ea1945d1c934be5861e0a80c9ead68b7d7bf2fc4c7cc4b2d166812d40d667a9	66f2b6737e1c6897a0e316f218b3c82faf9b7498ed9ac35bf875a1698b2c277a
120	2026-10-12 22:10:21	INFO	Cursos	editar	cursos-procesar-editar	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	66f2b6737e1c6897a0e316f218b3c82faf9b7498ed9ac35bf875a1698b2c277a	04ff7423b939b403d3a1986b63981333d72b6e51a502fba4f73bbc78a45f4160
121	2026-10-12 22:10:22	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	04ff7423b939b403d3a1986b63981333d72b6e51a502fba4f73bbc78a45f4160	cabfead764d8a60c24576b11591f02558bbb6add240b57d87f0d768f906c697c
122	2026-10-12 22:10:26	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cabfead764d8a60c24576b11591f02558bbb6add240b57d87f0d768f906c697c	90daf9029d3fd03287fc25fead51433596d24f3146d66ee3edf15da6ee6b9111
123	2026-10-12 22:12:05	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	90daf9029d3fd03287fc25fead51433596d24f3146d66ee3edf15da6ee6b9111	b8c0863379ff27c88a7d53c0a7e03c2685f018140567a6f1b48fd0400e7debb0
124	2026-10-12 22:12:10	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b8c0863379ff27c88a7d53c0a7e03c2685f018140567a6f1b48fd0400e7debb0	de86d7760fb58359a8bb12e9dd7dfc90d42285eb57b99c1dd9bc4d9060c06e9c
125	2026-10-12 22:12:42	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	de86d7760fb58359a8bb12e9dd7dfc90d42285eb57b99c1dd9bc4d9060c06e9c	b2adef387c4c8aad7cbfc02588869f8d4b53f16e36bf8007a715aae8b48db687
126	2026-10-12 22:13:28	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b2adef387c4c8aad7cbfc02588869f8d4b53f16e36bf8007a715aae8b48db687	39e2827863adc3627030c5468b2de63fdad59e5e4734419de3d688633d9497bc
127	2026-10-12 22:17:37	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	39e2827863adc3627030c5468b2de63fdad59e5e4734419de3d688633d9497bc	df3979b1c90b772f1cf7eeb73a4eac5075d626c0f4428dbb2933232682185ecc
128	2026-10-12 22:17:53	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	df3979b1c90b772f1cf7eeb73a4eac5075d626c0f4428dbb2933232682185ecc	215a73c1291714b4687cd2958e1374e03be7d1a13e6684110422d702b460d148
129	2026-10-12 22:18:37	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	215a73c1291714b4687cd2958e1374e03be7d1a13e6684110422d702b460d148	4d8dee940fcc377f36e488526ce3d04b782a58099b54d4170276ee43377e5b60
130	2026-10-12 22:34:09	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4d8dee940fcc377f36e488526ce3d04b782a58099b54d4170276ee43377e5b60	c42376ac639e075bec3dd1cfb3e67c6bb45491f66ad3e27321b515420d56c20d
131	2026-10-12 22:34:37	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c42376ac639e075bec3dd1cfb3e67c6bb45491f66ad3e27321b515420d56c20d	d5d961db306ac5c4939ba83f49f04c2bcf91401a02ef11e373db6b3a2e4e0d8b
132	2026-10-12 22:35:41	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d5d961db306ac5c4939ba83f49f04c2bcf91401a02ef11e373db6b3a2e4e0d8b	4eb227dd8918dce4a58200734b18920d89c9920da47dfbdf01444d885ab9d167
133	2026-10-12 22:35:45	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4eb227dd8918dce4a58200734b18920d89c9920da47dfbdf01444d885ab9d167	6598e3bf2382690b9703e740ae4ad3f1e8d57d6e994147cba3c6cf232e3376d3
134	2026-10-12 22:35:46	INFO	Cursos	editar	cursos-editar	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6598e3bf2382690b9703e740ae4ad3f1e8d57d6e994147cba3c6cf232e3376d3	9abd682d5a786ec0d1565092e77856ee79088da17a556bd7d2179b426c705498
135	2026-10-12 22:36:14	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	9abd682d5a786ec0d1565092e77856ee79088da17a556bd7d2179b426c705498	6e62cefb7b4ebc201087146c2cc4a69ab0ea9228c73787ae802f3661398f6b5d
136	2026-10-12 22:41:29	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6e62cefb7b4ebc201087146c2cc4a69ab0ea9228c73787ae802f3661398f6b5d	4f950bdecb64a5f2d517042d3f03975ba85fea613e791c100868bcbc88e7d3e8
137	2026-10-12 22:41:32	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4f950bdecb64a5f2d517042d3f03975ba85fea613e791c100868bcbc88e7d3e8	04b3f03b8ae8ce16becd94d96563a3bc6d02f80bb020aa5dabc367a2aed402f8
138	2026-10-12 22:46:46	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	04b3f03b8ae8ce16becd94d96563a3bc6d02f80bb020aa5dabc367a2aed402f8	2cb9384098a34890c36867818d30c2c4027121b1344647a19765dec5f409f599
139	2026-10-12 22:49:14	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2cb9384098a34890c36867818d30c2c4027121b1344647a19765dec5f409f599	109b746b2d8b7ff47b4a6615dafe3867d09ae9db77e1c0f20a36394802797de2
140	2026-10-12 22:49:25	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	109b746b2d8b7ff47b4a6615dafe3867d09ae9db77e1c0f20a36394802797de2	42c412760e61d44cd4398f5137abf7ded72b725303a5093af5882e4f40a12fca
141	2026-10-12 22:54:24	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	42c412760e61d44cd4398f5137abf7ded72b725303a5093af5882e4f40a12fca	350c7727eb64be30217782ef5eb52e632f0380fd0a185920e737895c7c6f1771
142	2026-10-12 22:54:31	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	350c7727eb64be30217782ef5eb52e632f0380fd0a185920e737895c7c6f1771	0bd9c21af01f5eada2db83c803f9ba35e940bcc0436c898a1394cbe0e95a6c1a
143	2026-10-12 22:54:36	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0bd9c21af01f5eada2db83c803f9ba35e940bcc0436c898a1394cbe0e95a6c1a	4bd7ef6d8ddbd24e35811143ea3f975b1afe15f48516bcb782e794e5ea040333
144	2026-10-12 22:54:45	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4bd7ef6d8ddbd24e35811143ea3f975b1afe15f48516bcb782e794e5ea040333	a226d1f8f1e62c637232a83ee8c99787fb4d10fb5ccd766e536808ab7837593f
145	2026-10-12 22:55:14	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a226d1f8f1e62c637232a83ee8c99787fb4d10fb5ccd766e536808ab7837593f	f4207ba754212931fc44ca15eeeb60db6aceb5a47fa2eed9104406ade3dfebf9
146	2026-10-12 22:55:18	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f4207ba754212931fc44ca15eeeb60db6aceb5a47fa2eed9104406ade3dfebf9	edb0a9314ad521b893ed96278bf045b0792144b320716bdaaae0143d7b285e5e
147	2026-10-12 22:55:22	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	edb0a9314ad521b893ed96278bf045b0792144b320716bdaaae0143d7b285e5e	b76a2c2e19763ca1e4bbf084377df99d031e282c5935af486a678f705d3cb2f6
148	2026-10-12 22:55:53	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b76a2c2e19763ca1e4bbf084377df99d031e282c5935af486a678f705d3cb2f6	08e9db70d066f3ef5c9b6b138f11ba02dbb1462c45e9d5e126db3708e0cb52a3
149	2026-10-12 22:56:02	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	08e9db70d066f3ef5c9b6b138f11ba02dbb1462c45e9d5e126db3708e0cb52a3	e41f89f6a9b2a7f4ead3bbe8d39ed992b06029b96a88e09a0fd4acc8a3a89121
150	2026-10-12 22:56:14	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e41f89f6a9b2a7f4ead3bbe8d39ed992b06029b96a88e09a0fd4acc8a3a89121	8d685a727792457dd965333a9c6dfade66bf2e9540fed4f44fd873dd2f5d2092
151	2026-10-12 22:56:31	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8d685a727792457dd965333a9c6dfade66bf2e9540fed4f44fd873dd2f5d2092	0dd9a0b635e6bf8b406d4a84333cb7f48d9d0f9e5361e99347ab3b8ab8a46e9d
152	2026-10-12 22:59:12	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0dd9a0b635e6bf8b406d4a84333cb7f48d9d0f9e5361e99347ab3b8ab8a46e9d	714201c6d294c44074ae028ac5733450b1d9dd8d0f9db38da18edf0a912afd5a
153	2026-10-12 22:59:33	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	714201c6d294c44074ae028ac5733450b1d9dd8d0f9db38da18edf0a912afd5a	79dc4c41ed84d3518877b626064e59fa3ef9e9241362719de92488fc23436f4c
154	2026-10-12 22:59:35	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	79dc4c41ed84d3518877b626064e59fa3ef9e9241362719de92488fc23436f4c	35dcddf56d779dc8301a27682485a1c0613873224f609521213939d02446e157
155	2026-10-12 22:59:37	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	35dcddf56d779dc8301a27682485a1c0613873224f609521213939d02446e157	239fa88bc9a74cd0f7b3fc4c880754ee779fdde495899f29f5ddc24513346828
156	2026-10-12 22:59:43	INFO	Investigaciones	gestionar	mis-postulantes	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	239fa88bc9a74cd0f7b3fc4c880754ee779fdde495899f29f5ddc24513346828	676ca9813f89e2d44fc4a35d901f01568448c19c92c51b0ceb3b9b5fee70ac14
157	2026-10-12 22:59:49	INFO	Investigaciones	gestionar	panel-investigaciones-admin	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	676ca9813f89e2d44fc4a35d901f01568448c19c92c51b0ceb3b9b5fee70ac14	d349b84bfa371ae869ca203768517d7d6c70e5b88121a40f0b17224af2481801
158	2026-10-12 23:00:03	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d349b84bfa371ae869ca203768517d7d6c70e5b88121a40f0b17224af2481801	526ebbceeed43fbfc3d2ce113c43a22f37d2207b10eb499966177975126e2552
159	2026-10-12 23:00:05	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	526ebbceeed43fbfc3d2ce113c43a22f37d2207b10eb499966177975126e2552	35448b69b8a2f373e35c7ba9abd18c4b5735720ab987a5dade93bb8bdffa837f
160	2026-10-12 23:00:26	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	35448b69b8a2f373e35c7ba9abd18c4b5735720ab987a5dade93bb8bdffa837f	df1d284905d9c8ed0aa23d1c305d99d22b15fc629ff4cd6b5cfdb6709ed8e791
161	2026-10-12 23:00:36	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	df1d284905d9c8ed0aa23d1c305d99d22b15fc629ff4cd6b5cfdb6709ed8e791	379b908d5e275ee1aab0385a0d9d5b8aea7aa9e0a9bc3da7cf8c0c8a111a33e1
162	2026-10-14 11:52:30	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	379b908d5e275ee1aab0385a0d9d5b8aea7aa9e0a9bc3da7cf8c0c8a111a33e1	6adf3cb22f14b40906c7b17e377895a702085cbbfdd4f5bc11952c126a6579d8
163	2026-10-14 11:53:01	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6adf3cb22f14b40906c7b17e377895a702085cbbfdd4f5bc11952c126a6579d8	1aaf1bc8f39da9c9f2ad77bce5a0a6eac400181ecd6c4577224f57c78ee90237
165	2026-10-14 11:53:03	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7b30db5147347c2ea23107d12134aefedc735b9efd67407da3e983eb998bb50b	ae91cb8d9052f42de7d4a20be6c8c6ff9646632fb29140f5ca56a91a4208f8fb
166	2026-10-14 11:53:49	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ae91cb8d9052f42de7d4a20be6c8c6ff9646632fb29140f5ca56a91a4208f8fb	bde6540b57d7d40aa223418f37e82deb6ae2cb40d02f303710e0201973f3bef9
167	2026-10-14 11:55:13	INFO	VinculacionEmpresarial	editar	guardar-propuesta	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	bde6540b57d7d40aa223418f37e82deb6ae2cb40d02f303710e0201973f3bef9	a5f47e4985fd563665cf3d568cbd4b4735db9625c1f91ecd452af6d6e92edca1
168	2026-10-14 11:55:13	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a5f47e4985fd563665cf3d568cbd4b4735db9625c1f91ecd452af6d6e92edca1	c623fa90d1938bcab9d81f9f453511dae4cc85a5c023fe3062f6f25f729f37cf
169	2026-10-14 11:57:31	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c623fa90d1938bcab9d81f9f453511dae4cc85a5c023fe3062f6f25f729f37cf	ac82fbee7f36d70d455cf37bd4fd4019a701e35a78d5f81465693a732f4cc425
170	2026-10-14 11:57:54	INFO	Investigaciones	gestionar	mis-postulantes	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ac82fbee7f36d70d455cf37bd4fd4019a701e35a78d5f81465693a732f4cc425	da18767273ba5944a3e9a8ada3861167e3adc5fa11d57a1af213d4cb14bd3520
171	2026-10-14 11:58:41	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	da18767273ba5944a3e9a8ada3861167e3adc5fa11d57a1af213d4cb14bd3520	dd5796b0cbd383e398511a2886de6afe810dd7c4f020dfd4d8a19ea0c1772c5b
172	2026-10-14 11:58:44	INFO	Investigaciones	crear	crear-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	dd5796b0cbd383e398511a2886de6afe810dd7c4f020dfd4d8a19ea0c1772c5b	3ca6f9d9975b706728f84fd1d278bec817bde63a937330c8d92c5a9931d93af4
173	2026-10-14 12:02:28	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3ca6f9d9975b706728f84fd1d278bec817bde63a937330c8d92c5a9931d93af4	8bff459d7404c1a0ddb2ebb25e846b80edc82baf911daef8a2cbd3b9d6266ea3
174	2026-10-14 12:02:36	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8bff459d7404c1a0ddb2ebb25e846b80edc82baf911daef8a2cbd3b9d6266ea3	60c9d8fa0a963e38bac0e9d9a6792656f2a5ba07bc39ebfe15088dde561f937e
175	2026-10-14 12:02:52	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	60c9d8fa0a963e38bac0e9d9a6792656f2a5ba07bc39ebfe15088dde561f937e	53e8fa254f756849785661dfbc78757479c0a7301dfa11187237b3d6a7180ebe
176	2026-10-14 12:03:02	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	53e8fa254f756849785661dfbc78757479c0a7301dfa11187237b3d6a7180ebe	b286f3baac9aab41319c9d4c69def61d32a9b1fcbe5bf8d2c4600a9d4b154b58
177	2026-10-14 12:03:24	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b286f3baac9aab41319c9d4c69def61d32a9b1fcbe5bf8d2c4600a9d4b154b58	3f1c0380090bffc4f9bc13d6040e9af89e18789a63577e0488d034405afc63b8
178	2026-10-14 12:03:30	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3f1c0380090bffc4f9bc13d6040e9af89e18789a63577e0488d034405afc63b8	d27dff6d26baa6302eb8451e4ad1d725641a0700b6b1a326061915f920978196
179	2026-10-14 12:03:31	INFO	Cursos	crear	cursos-crear	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d27dff6d26baa6302eb8451e4ad1d725641a0700b6b1a326061915f920978196	d41757d4bb607fa35da8adab4dcd3b390d786d3715183672873f13382570bd54
180	2026-10-14 12:03:50	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d41757d4bb607fa35da8adab4dcd3b390d786d3715183672873f13382570bd54	e8e15b5b66b28d000d1d112582bc5513d2d6aa52fb84662b52c36c9025eadf1a
181	2026-10-14 12:03:52	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e8e15b5b66b28d000d1d112582bc5513d2d6aa52fb84662b52c36c9025eadf1a	ca6139a656f25e15d29804300febccff0e691e14ccb494f198d65be61448d837
182	2026-10-14 12:03:55	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ca6139a656f25e15d29804300febccff0e691e14ccb494f198d65be61448d837	9de613e645a1d53f954a95ef5cd15aa7a99a81d25ee87372fb4cf46464a68203
183	2026-10-14 12:06:19	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	9de613e645a1d53f954a95ef5cd15aa7a99a81d25ee87372fb4cf46464a68203	cdddaf208081ddfb0e51f0b88bf9379b76bfbcc1e95b469de080f529fd8dc13d
184	2026-10-14 12:08:12	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cdddaf208081ddfb0e51f0b88bf9379b76bfbcc1e95b469de080f529fd8dc13d	094e3afd6e12d2f1527bdc262629d2ed9adb0f6ea9095b19462d101d7ac37ccf
185	2026-10-14 12:08:32	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	094e3afd6e12d2f1527bdc262629d2ed9adb0f6ea9095b19462d101d7ac37ccf	dac5824d78a50206bb748c025886d475994acbe7b2d7c611189275e1ae871f6c
186	2026-10-14 12:21:43	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	24	landorus	2	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Nivel 2 tiene «Ver» en «Líneas».	dac5824d78a50206bb748c025886d475994acbe7b2d7c611189275e1ae871f6c	518b877f56846bd6b44c2ca33564def217a77c259fd1ca904fa4a46779fde9a8
187	2026-10-14 12:22:06	INFO	Cursos	ver	cursos	PERMITIDO	24	landorus	2	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Nivel 2 tiene «Ver» en «Formación».	518b877f56846bd6b44c2ca33564def217a77c259fd1ca904fa4a46779fde9a8	dc713f016c7ebc6b3436c8c6b983eef71f6997ed2eeb85345396e41a4676699a
188	2026-10-14 12:22:14	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	24	landorus	2	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Nivel 2 tiene «Ver» en «Formación».	dc713f016c7ebc6b3436c8c6b983eef71f6997ed2eeb85345396e41a4676699a	eb005b6a192d077532d0aceac3364d2e472b5b582cc4b6abfdbd39fc356a97d1
189	2026-10-14 12:23:13	INFO	Cursos	ver	cursos	PERMITIDO	24	landorus	2	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Nivel 2 tiene «Ver» en «Formación».	eb005b6a192d077532d0aceac3364d2e472b5b582cc4b6abfdbd39fc356a97d1	64e81dfba10e3f8da3c40b469412580772068dc44390e5f9202b6f911bff2834
190	2026-10-14 12:24:31	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	64e81dfba10e3f8da3c40b469412580772068dc44390e5f9202b6f911bff2834	7b8cca0103cb46bc9fadf01eb0d4299706519f227ef09cd8bbb731bb460af006
191	2026-10-14 12:24:51	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7b8cca0103cb46bc9fadf01eb0d4299706519f227ef09cd8bbb731bb460af006	80edb53abac712e4c98850defb15cec45ce74d59f7a0618a9caa6d618d851e2c
192	2026-10-14 12:25:19	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	80edb53abac712e4c98850defb15cec45ce74d59f7a0618a9caa6d618d851e2c	b427604748cd306934a75a322780c7f1ff76a161ac1c3c6f5244427c4bd8933f
193	2026-10-14 12:25:53	INFO	Cursos	crear	cursos-crear	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b427604748cd306934a75a322780c7f1ff76a161ac1c3c6f5244427c4bd8933f	f648c374c99ec13412c8c59efa0e45046841e9e609d6f79575700dfe9df748af
194	2026-10-14 12:26:58	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f648c374c99ec13412c8c59efa0e45046841e9e609d6f79575700dfe9df748af	7bab3ab7456e4c04c34c0cb5102b085b855870384ab38ce03c73585f2f4672e6
195	2026-10-14 12:27:00	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7bab3ab7456e4c04c34c0cb5102b085b855870384ab38ce03c73585f2f4672e6	b68dedc44204251e286a73266f9dc23602223cb959c2cc97804be233fb26260a
196	2026-10-14 12:27:16	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b68dedc44204251e286a73266f9dc23602223cb959c2cc97804be233fb26260a	d3380e60a17918f4e39c325064fdb579493fe777c2c3c309049b9a20c9ef26a8
197	2026-10-14 12:28:44	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d3380e60a17918f4e39c325064fdb579493fe777c2c3c309049b9a20c9ef26a8	9295bce311980e1f1b500e08563691795d319080d6221eda7098d1ad7b6a1b64
198	2026-10-14 12:28:48	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	9295bce311980e1f1b500e08563691795d319080d6221eda7098d1ad7b6a1b64	ce66f816ac23dae9399ad11a57e52bd58966fef019176719c19acddee4e0a48c
199	2026-10-17 13:27:38	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ce66f816ac23dae9399ad11a57e52bd58966fef019176719c19acddee4e0a48c	0d7f1617443feedcc75f6b8f283ca1e8f05d7384d692e40f628c34a6ee7745c6
200	2026-10-17 13:28:03	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0d7f1617443feedcc75f6b8f283ca1e8f05d7384d692e40f628c34a6ee7745c6	04973f10b4995d5449553cedb3eb25d9c806b94a6127ce9020d4747f7eb31abe
201	2026-10-17 13:28:04	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	04973f10b4995d5449553cedb3eb25d9c806b94a6127ce9020d4747f7eb31abe	89c9665be7c1608ff47cfd2bc703576e634651b7ebaf49df089cd80445ba76d5
202	2026-10-17 13:28:05	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	89c9665be7c1608ff47cfd2bc703576e634651b7ebaf49df089cd80445ba76d5	e2ad8e1488f60b155aed88ac866eff52d7236b9497b5c08278b6d828b3699e03
203	2026-10-17 13:28:06	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e2ad8e1488f60b155aed88ac866eff52d7236b9497b5c08278b6d828b3699e03	7f7fca797c6ea75df5e197dc686e42240e74e6adb2d934048ed624356929c882
204	2026-10-17 13:28:06	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7f7fca797c6ea75df5e197dc686e42240e74e6adb2d934048ed624356929c882	ec977eb2aa468b999565ab15c84c38c9ec5f1c014c8a5750ed62c3bc7acefbd2
205	2026-10-17 13:28:07	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ec977eb2aa468b999565ab15c84c38c9ec5f1c014c8a5750ed62c3bc7acefbd2	cebaf9f33b432d077d6aa41a20e7ee165b7ac28cc03b121cebdcc8341746d03b
206	2026-10-17 13:29:07	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cebaf9f33b432d077d6aa41a20e7ee165b7ac28cc03b121cebdcc8341746d03b	6ee66b2a971aa4f5b4e4dae304d99e66ad45e00c909bfcfc6a67867947974f53
207	2026-10-17 13:29:11	INFO	VinculacionEmpresarial	gestionar	gestion-proyectos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6ee66b2a971aa4f5b4e4dae304d99e66ad45e00c909bfcfc6a67867947974f53	a9f66b1a062d2b57c244bfaddd477c924ca523cb3c7ac35f0855f93908ec160a
208	2026-10-17 13:37:15	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a9f66b1a062d2b57c244bfaddd477c924ca523cb3c7ac35f0855f93908ec160a	cf6dedc724f53cf5e0b070a410bbf3b6ecb0934957efa3ee19cac58f44684697
209	2026-10-17 13:44:27	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Python-urllib/3.13	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	cf6dedc724f53cf5e0b070a410bbf3b6ecb0934957efa3ee19cac58f44684697	9be442b8c1e25d13376655df8a4bce8c5a45461129fa00c2f6accc9f39e0408d
210	2026-10-17 13:44:43	AVISO	SeguridadLineas	ver	seguridad-lineas	DENEGADO	\N	Visitante anónimo	999	::1	GET	Python-urllib/3.13	[MATRIZ] Matriz de permisos: Visitante (sin sesión) no tiene «Ver» en «Seguridad».	9be442b8c1e25d13376655df8a4bce8c5a45461129fa00c2f6accc9f39e0408d	21957847f4d1bb04669637668044094b2add5b0a2c8c11dd8b31e417382ab1b8
211	2026-10-17 13:46:23	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	21957847f4d1bb04669637668044094b2add5b0a2c8c11dd8b31e417382ab1b8	1044e0026b7d9fb29709e1539001f11bd7ff35d09247a31eb1a41333267929cb
212	2026-10-17 13:49:12	INFO	VinculacionEmpresarial	editar	guardar-propuesta	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1044e0026b7d9fb29709e1539001f11bd7ff35d09247a31eb1a41333267929cb	d2b05ad95cdf67c5eb839a48f053b87ed52de4cad696b4f8809d75e660b8de39
213	2026-10-17 13:49:12	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d2b05ad95cdf67c5eb839a48f053b87ed52de4cad696b4f8809d75e660b8de39	acb84f3d0d39ce70116612e4b9066ffeaf5b2ecea89b2c62fd1cff8f98111384
214	2026-10-17 13:50:02	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	acb84f3d0d39ce70116612e4b9066ffeaf5b2ecea89b2c62fd1cff8f98111384	cfa595f8ab000b624cec5bfd364f88ba3c27fc43aed27a64669da71ab0ff59d8
215	2026-10-17 13:51:39	AVISO	SeguridadLineas	ver	seguridad-lineas	DENEGADO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	[MATRIZ] Matriz de permisos: Visitante (sin sesión) no tiene «Ver» en «Seguridad».	cfa595f8ab000b624cec5bfd364f88ba3c27fc43aed27a64669da71ab0ff59d8	0d181a34090ed5973deb9a8199e1435ad42d2bc83679556396db10f90c25e21f
216	2026-10-17 13:57:32	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	0d181a34090ed5973deb9a8199e1435ad42d2bc83679556396db10f90c25e21f	09dff159d591c636a9ed5286ee864138ebdb5a106fa5a8fa55e0d68c19f83b16
217	2026-10-17 13:57:36	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	09dff159d591c636a9ed5286ee864138ebdb5a106fa5a8fa55e0d68c19f83b16	62c3db5893f26ce8fd4c10aef5e2ca81db151a4917138f57ed2ba096ae96cfa7
218	2026-10-17 13:57:39	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	62c3db5893f26ce8fd4c10aef5e2ca81db151a4917138f57ed2ba096ae96cfa7	22dcf84110c9fe286b3131aa3886575d39bfb84d8cb87bc439db8a47aa0e98c6
219	2026-10-17 14:09:52	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	22dcf84110c9fe286b3131aa3886575d39bfb84d8cb87bc439db8a47aa0e98c6	6f65193281a445d72f72d5962670b71c80e8d99fab9e1f28a643f2ac1d07cc92
220	2026-10-17 14:11:35	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6f65193281a445d72f72d5962670b71c80e8d99fab9e1f28a643f2ac1d07cc92	5dbbb116e58212aba39d425fe3dba267c94839e16c0841137bc1d720e0433724
221	2026-10-17 14:14:57	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5dbbb116e58212aba39d425fe3dba267c94839e16c0841137bc1d720e0433724	d9a17b825385f9f636308ed08c3e037babb50fdfc0864a131bd4ad06e38f9809
222	2026-10-17 14:15:07	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d9a17b825385f9f636308ed08c3e037babb50fdfc0864a131bd4ad06e38f9809	928d4247487bdecd1e6e9a587e04a39e0129636619c29306db0bdbdc9cb92920
223	2026-10-17 14:15:11	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	928d4247487bdecd1e6e9a587e04a39e0129636619c29306db0bdbdc9cb92920	5563e5efb66adb095340e171b2581ba8e1b6b19423d69de6c376e9cacb0cbba5
224	2026-10-17 14:16:56	INFO	LineasInvestigacion	gestionar	gestionar-lineas	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5563e5efb66adb095340e171b2581ba8e1b6b19423d69de6c376e9cacb0cbba5	d31055abab3d3e3b7722c8b0237f9f8f77f332f7312e041c25acaa123bbb5c25
225	2026-10-17 14:30:05	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d31055abab3d3e3b7722c8b0237f9f8f77f332f7312e041c25acaa123bbb5c25	e19d717e0023dbdf258167ae1a08847064aed90f95fddce965c508bfd4dfec68
226	2026-10-17 14:32:12	INFO	Cursos	ver	cursos-detalle	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e19d717e0023dbdf258167ae1a08847064aed90f95fddce965c508bfd4dfec68	73449e1ea59f34f48b57688fea33124b2335c4d90c91002a3c2f4ee362c5947b
227	2026-10-17 14:36:02	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	73449e1ea59f34f48b57688fea33124b2335c4d90c91002a3c2f4ee362c5947b	791d3ada670c7abbca291eac6310f14aa9f71df3fa2147498338efc90f91a532
228	2026-10-17 14:36:09	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	791d3ada670c7abbca291eac6310f14aa9f71df3fa2147498338efc90f91a532	bc9f6cf5b94b7efeb57318d788f76de136ecbbd2e3003877534afef777e94611
229	2026-10-17 14:36:19	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	bc9f6cf5b94b7efeb57318d788f76de136ecbbd2e3003877534afef777e94611	18d111b71245efc18bb5d4eb4c851242a4dd22cd5d960f861c3ef2318fb1180a
230	2026-10-17 14:36:22	INFO	Cursos	crear	cursos-crear	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	18d111b71245efc18bb5d4eb4c851242a4dd22cd5d960f861c3ef2318fb1180a	688c9566d185d43b0870a1b46c213f502329f17267b01f64f1874a6ca18f02ed
231	2026-10-17 14:38:29	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	688c9566d185d43b0870a1b46c213f502329f17267b01f64f1874a6ca18f02ed	879e1c27a5f88926a67cd3091df11582debb93b20a9871646ceb0e695957e421
232	2026-10-17 14:38:32	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	879e1c27a5f88926a67cd3091df11582debb93b20a9871646ceb0e695957e421	fcb7f06af8867620c3ca3cf589962ce196b019ff3362d287ab8e5934bc27583c
233	2026-10-17 14:38:35	AVISO	Cursos	configurar	cursos-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fcb7f06af8867620c3ca3cf589962ce196b019ff3362d287ab8e5934bc27583c	bff35804a81324311f58bd8f851f18d2528f957b48e54def3b279e89e793fe2e
234	2026-10-20 05:00:48	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	127.0.0.1	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	bff35804a81324311f58bd8f851f18d2528f957b48e54def3b279e89e793fe2e	50639d2df9304a09061d224fff6b9aac6f98fd7f45e9cb86dd811b3fa11578e4
235	2026-10-20 05:00:48	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	127.0.0.1	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	50639d2df9304a09061d224fff6b9aac6f98fd7f45e9cb86dd811b3fa11578e4	c0b232ccd2da930603b868b709c211a1ea3599e9adc89c7c7df6c7f6f7e4db83
236	2026-10-20 05:00:49	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	\N	Visitante anónimo	999	127.0.0.1	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	c0b232ccd2da930603b868b709c211a1ea3599e9adc89c7c7df6c7f6f7e4db83	54ff65811ca0f4bdae36c4d9577a2ba16dd7170e0497a3c60c442dfb16cfbc95
237	2026-10-20 05:10:10	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	54ff65811ca0f4bdae36c4d9577a2ba16dd7170e0497a3c60c442dfb16cfbc95	2334415d9a1162ee12414838196d7a314167498670005d2c4274b74f6751d603
238	2026-10-20 05:10:18	INFO	VinculacionEmpresarial	ver	cartelera-oportunidades	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2334415d9a1162ee12414838196d7a314167498670005d2c4274b74f6751d603	2e0497b350145926f1423a3cb7c85b2453d5f230ba631a994989cc03d9ad7012
239	2026-10-20 05:10:22	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2e0497b350145926f1423a3cb7c85b2453d5f230ba631a994989cc03d9ad7012	102493a2bd6518cc956c4fea72374a0780d81e36b704ac0b5e228f05abf5ba9f
240	2026-10-20 05:10:24	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	102493a2bd6518cc956c4fea72374a0780d81e36b704ac0b5e228f05abf5ba9f	1dd64c9bc26ffadef9002a0cea29da997223481c9c5c5991d625463772c49d01
241	2026-10-20 05:10:33	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1dd64c9bc26ffadef9002a0cea29da997223481c9c5c5991d625463772c49d01	3720019dac5f2c11b882eb62ddbc718ef05a902c0e53bbb9ac6c016b63bf61ce
242	2026-10-20 05:10:41	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	3720019dac5f2c11b882eb62ddbc718ef05a902c0e53bbb9ac6c016b63bf61ce	e89d4494141de296e839f18fece232bc1bfd95c984b5709255ef124e82bce1b2
243	2026-10-20 05:10:47	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e89d4494141de296e839f18fece232bc1bfd95c984b5709255ef124e82bce1b2	e9eccd8504f1bffd50f416b20080d7a36faa939e41f65d3aef1e5af7678d0427
244	2026-10-20 05:10:48	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e9eccd8504f1bffd50f416b20080d7a36faa939e41f65d3aef1e5af7678d0427	e4384b6e0b2bfb51c0a415c354e45fc9c4c2bbfa298e6f83232fa8a34cec43a0
245	2026-10-20 14:49:33	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	e4384b6e0b2bfb51c0a415c354e45fc9c4c2bbfa298e6f83232fa8a34cec43a0	73ed2ada633a9a509d2bcef3ffcbcdf5346024ca521cd6aa4cf734da06ff6998
246	2026-10-20 14:49:33	INFO	Cursos	ver	cursos-detalle	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	73ed2ada633a9a509d2bcef3ffcbcdf5346024ca521cd6aa4cf734da06ff6998	5149b938a5800f0d5f4fd1c3caad60adda27039e35dfb2d842fa2bfc5be7b0b1
247	2026-10-20 14:49:33	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	5149b938a5800f0d5f4fd1c3caad60adda27039e35dfb2d842fa2bfc5be7b0b1	21fd494aaa4ae9199218d3fb0eaab88815cb588d164b1321842f150620e8abe9
248	2026-10-20 14:51:02	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	21fd494aaa4ae9199218d3fb0eaab88815cb588d164b1321842f150620e8abe9	216d6b60438fe6df07e4ffcbf9c469df65d31cdabb8721a61dfe1da7fdf9ec72
249	2026-10-20 14:51:02	INFO	Cursos	ver	cursos-detalle	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	216d6b60438fe6df07e4ffcbf9c469df65d31cdabb8721a61dfe1da7fdf9ec72	7a8a7750c6cbad4a1e76d0a5aaa2f724d94362742342fe8301595a2d105f8c17
250	2026-10-20 14:51:02	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	7a8a7750c6cbad4a1e76d0a5aaa2f724d94362742342fe8301595a2d105f8c17	ab6748855ac0a1071366a72839f4b50bce2ecbda07026dbf5b08be166be97ece
251	2026-10-20 14:56:01	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	ab6748855ac0a1071366a72839f4b50bce2ecbda07026dbf5b08be166be97ece	ae461d284ea035214dc3752405fa658527824c8cd07415245c2880daac4e9689
252	2026-10-20 14:56:01	INFO	Cursos	ver	cursos-detalle	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	ae461d284ea035214dc3752405fa658527824c8cd07415245c2880daac4e9689	56f606c528abc31d8fdfdf7721a67124c7265bf465c7b43fe7790176a653b0d2
253	2026-10-20 14:56:01	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	CLI		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	56f606c528abc31d8fdfdf7721a67124c7265bf465c7b43fe7790176a653b0d2	4fa4f5273f7ba3dbd98a18159ae6016d5bce5b79ad02d59ad3ed50878d219c3c
254	2026-10-20 15:02:00	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	4fa4f5273f7ba3dbd98a18159ae6016d5bce5b79ad02d59ad3ed50878d219c3c	53ac89bad07b12f93473dcefde69806c5501de882fb3ed55e4bd63eb7122e503
255	2026-10-20 15:02:45	INFO	Cursos	ver	cursos-detalle	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	53ac89bad07b12f93473dcefde69806c5501de882fb3ed55e4bd63eb7122e503	6c6823bc04b00ff74c5028c67debc3c524fee799b21baafd3d0a10dbb1ee4c23
256	2026-10-20 15:04:23	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	0.0.0.0	GET		Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	6c6823bc04b00ff74c5028c67debc3c524fee799b21baafd3d0a10dbb1ee4c23	13a6ffb286ed2c63ae08a2539a150f3db2e628081532817fafafdd8a717497de
257	2026-10-20 15:32:04	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	13a6ffb286ed2c63ae08a2539a150f3db2e628081532817fafafdd8a717497de	a0076255e13c586019538601046ac9f9dc7295e9facc299820510c0daac5df15
258	2026-10-20 15:46:36	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	a0076255e13c586019538601046ac9f9dc7295e9facc299820510c0daac5df15	18654a25547fcda34e11fac05748208665a7c377ee681f2edecccd7f645bb2a3
259	2026-10-20 15:46:43	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	18654a25547fcda34e11fac05748208665a7c377ee681f2edecccd7f645bb2a3	e1d57c420b2e745d75f945e99ee4c536135ad71d793f9f334fa15c72d3942b86
260	2026-10-20 15:46:49	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	e1d57c420b2e745d75f945e99ee4c536135ad71d793f9f334fa15c72d3942b86	59df2ec2e6f47f215035255dcbb16b418831b1dd4f925bb026f4c4179f059ed7
261	2026-10-20 16:02:24	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	59df2ec2e6f47f215035255dcbb16b418831b1dd4f925bb026f4c4179f059ed7	97dcaf590d52a0a8ba0f0471069bb160a223b88d4bfe1dd69c9cd7c1e1d32e19
262	2026-10-20 16:02:38	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	97dcaf590d52a0a8ba0f0471069bb160a223b88d4bfe1dd69c9cd7c1e1d32e19	ad937558a63938f097a7944549693954f62b38132cff316b4a9aa2e134f16652
263	2026-10-20 16:02:50	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	ad937558a63938f097a7944549693954f62b38132cff316b4a9aa2e134f16652	a4047e6adcbb0b5aae9d35d05236faeaebc4f8bef16f6cc1020aea5b04c26841
264	2026-10-20 16:04:57	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a4047e6adcbb0b5aae9d35d05236faeaebc4f8bef16f6cc1020aea5b04c26841	fe53753fa97a46beed21832aab71443326d4f1143c597454e0d912dab1d47491
265	2026-10-20 16:05:22	AVISO	VinculacionEmpresarial	configurar	vinculacion-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fe53753fa97a46beed21832aab71443326d4f1143c597454e0d912dab1d47491	d7d715eda9c698e0c8584721a8afd8a3b40ea579ba61b1571798ba25404009b2
266	2026-10-20 16:05:52	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d7d715eda9c698e0c8584721a8afd8a3b40ea579ba61b1571798ba25404009b2	214cbe4a3e6168bf56d4d5074215c6c19b96eb85892e214c656205ac56f7f111
267	2026-10-20 16:07:39	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	214cbe4a3e6168bf56d4d5074215c6c19b96eb85892e214c656205ac56f7f111	11c1a6c869bd25854e42e09de4b3d525aff810af42a24a15655fff53e831e2ea
268	2026-10-20 16:09:15	AVISO	VinculacionEmpresarial	configurar	vinculacion-config	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	11c1a6c869bd25854e42e09de4b3d525aff810af42a24a15655fff53e831e2ea	1dc4547db4b1a152b4cd1970da0c9f373131d448203312e8e1e1d65ac51069eb
269	2026-10-20 16:12:54	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	1dc4547db4b1a152b4cd1970da0c9f373131d448203312e8e1e1d65ac51069eb	034aa45486beb0a6853a5b7f9bb5de20e83da60ed87ee1eebbe1735904c49190
270	2026-10-20 16:13:04	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	034aa45486beb0a6853a5b7f9bb5de20e83da60ed87ee1eebbe1735904c49190	55e9e6021432b35d358da03fe94ca72d9f4d9d7365143a353d186a796efcb7f7
271	2026-10-20 16:13:25	INFO	Investigaciones	ver	investigadores	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	55e9e6021432b35d358da03fe94ca72d9f4d9d7365143a353d186a796efcb7f7	70815c8268e33c0206c97a1e4dac31498e11b753cdc919a32ce1262faacae551
272	2026-10-20 16:14:05	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	70815c8268e33c0206c97a1e4dac31498e11b753cdc919a32ce1262faacae551	d90135eaf143de3aecf59fabfaec8f4ac9a6b0948cfb007bbbe015591b0ca3a4
273	2026-10-20 16:14:15	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	d90135eaf143de3aecf59fabfaec8f4ac9a6b0948cfb007bbbe015591b0ca3a4	9db8a6d660780f775879b9f7993e4315b8ac0bdfc3ca3ab8889b809bac7a22a7
274	2026-10-21 15:00:41	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	9db8a6d660780f775879b9f7993e4315b8ac0bdfc3ca3ab8889b809bac7a22a7	fc4a114937982bfae106bcebdff59aedde283ed4cf5ffebd3f61628ff1c6b3b1
275	2026-10-21 15:00:44	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fc4a114937982bfae106bcebdff59aedde283ed4cf5ffebd3f61628ff1c6b3b1	0f7d347bd0c6ad66bf23dc840d6f3d906c35fde34fca4dfd05707f293a1662c9
276	2026-10-21 15:00:48	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0f7d347bd0c6ad66bf23dc840d6f3d906c35fde34fca4dfd05707f293a1662c9	0ed0d7f3654cba983c79209956987d2b2db6455366c92c1fa747f2c7b9201c7a
277	2026-10-21 15:01:18	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0ed0d7f3654cba983c79209956987d2b2db6455366c92c1fa747f2c7b9201c7a	ea846c33600c15141cc8ab9737e19cd767f2129381735fd0cfc5247c70f4733f
278	2026-10-21 15:06:31	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ea846c33600c15141cc8ab9737e19cd767f2129381735fd0cfc5247c70f4733f	b758056e590feb00956f6dbcdf770383a27e3c73aad70295659406454573b86a
279	2026-10-21 15:06:34	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b758056e590feb00956f6dbcdf770383a27e3c73aad70295659406454573b86a	776d7cd05ada0ce79605dda90064b1e37e429f6c594f17549d6f531676584135
280	2026-10-21 15:06:39	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	776d7cd05ada0ce79605dda90064b1e37e429f6c594f17549d6f531676584135	7001e84f6b51b7cd691dc63c162fdf2c31362abfa4fea2305dfd136394ab58d7
281	2026-10-21 15:25:58	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7001e84f6b51b7cd691dc63c162fdf2c31362abfa4fea2305dfd136394ab58d7	7524395c7e419d24ed26f9d28d0ee619e59b359c771a53cdd7c5cc1ece72400a
282	2026-10-21 15:26:01	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7524395c7e419d24ed26f9d28d0ee619e59b359c771a53cdd7c5cc1ece72400a	fc6f4f99673fcc25ccf2bbd2369709fca6287f8850a9774c5a389050d09df3bb
283	2026-10-21 15:26:01	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fc6f4f99673fcc25ccf2bbd2369709fca6287f8850a9774c5a389050d09df3bb	a8d89c2f962913610c6cc46e3f5f6e118781d7377fad71199b2f370975f47178
284	2026-10-21 15:27:05	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a8d89c2f962913610c6cc46e3f5f6e118781d7377fad71199b2f370975f47178	593a8e6763f42d336277217a3db149d99c83fc2130d46a5e184f6a6fd9613bc6
285	2026-10-21 15:47:33	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	593a8e6763f42d336277217a3db149d99c83fc2130d46a5e184f6a6fd9613bc6	5d19251fe9e22dce1d666d77a9943de07a70388804041487cfd07f40b4d3decf
286	2026-10-21 15:47:33	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5d19251fe9e22dce1d666d77a9943de07a70388804041487cfd07f40b4d3decf	f564dac158f42755a17fe5842222c0239e0900f0cf12853fbc649a782ee1348c
287	2026-10-21 15:51:23	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f564dac158f42755a17fe5842222c0239e0900f0cf12853fbc649a782ee1348c	60c5a85de5bb2c6dc1f58ab54dcf8ed3b7b6e59e5120c250c6d1183e1942b478
288	2026-10-21 15:51:27	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	60c5a85de5bb2c6dc1f58ab54dcf8ed3b7b6e59e5120c250c6d1183e1942b478	e461c7eac46695f1a3d7b8a40d5ae003cc23df3fb253b6ad7c3e676c4c649dc0
289	2026-10-21 15:51:41	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e461c7eac46695f1a3d7b8a40d5ae003cc23df3fb253b6ad7c3e676c4c649dc0	6ca4ee8d7c515aa01e5644b18b4af73662273a97048c3894d623c217edc0dd9d
290	2026-10-21 15:58:09	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6ca4ee8d7c515aa01e5644b18b4af73662273a97048c3894d623c217edc0dd9d	ab074fb3c23e8c65aba3784a7f86c39c6c22d9f6f467817944bee4d6798d6c88
291	2026-10-21 15:58:09	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ab074fb3c23e8c65aba3784a7f86c39c6c22d9f6f467817944bee4d6798d6c88	a7de4b1a2976825f7144928908e8c64a052a2758206a4df0f37f9a771d9824de
292	2026-10-21 15:58:32	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a7de4b1a2976825f7144928908e8c64a052a2758206a4df0f37f9a771d9824de	83c8407c502a4ece5c095d33fc36c8245eb9e71efc60fe861808d0309d8dfa03
293	2026-10-21 15:58:42	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	83c8407c502a4ece5c095d33fc36c8245eb9e71efc60fe861808d0309d8dfa03	a4c9de5aa235eeddca7aff03b4d989069eb58d254fd2e0fa6d6e038d017766bf
294	2026-10-21 15:58:59	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a4c9de5aa235eeddca7aff03b4d989069eb58d254fd2e0fa6d6e038d017766bf	72a8b25c2824dc2329041b355890859b2c2c298d15a204cb059e3b0457a9462c
295	2026-10-21 15:59:36	INFO	LineasInvestigacion	gestionar	gestionar-lineas	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	72a8b25c2824dc2329041b355890859b2c2c298d15a204cb059e3b0457a9462c	e018ea31208dec492586ccc33a46143a4a2ec0d731aa1af65ff7681de22b4cbf
296	2026-10-21 15:59:40	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e018ea31208dec492586ccc33a46143a4a2ec0d731aa1af65ff7681de22b4cbf	0732dda036ed0be3b285888ed5ce0fc27eb1f7c80427ecd8d7266e7b74ce6cc4
297	2026-10-21 15:59:49	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0732dda036ed0be3b285888ed5ce0fc27eb1f7c80427ecd8d7266e7b74ce6cc4	a11f8c2b1a2b3d055efcf916d9906372acf3f465862127709bb79fad16684b3d
298	2026-10-21 16:01:45	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a11f8c2b1a2b3d055efcf916d9906372acf3f465862127709bb79fad16684b3d	60bb90765452ca6a6df2c0eb60d22b24c5907179d054138c111ac9fa7073a37a
299	2026-10-21 16:01:57	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	60bb90765452ca6a6df2c0eb60d22b24c5907179d054138c111ac9fa7073a37a	113475628622b504992e9b0a7ed22f1ccdca679e933ae47cbd6b633ca3acaa5f
300	2026-10-21 16:01:57	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	113475628622b504992e9b0a7ed22f1ccdca679e933ae47cbd6b633ca3acaa5f	f0d220748d14da1756a07424a27e82829dbf23ddf289ba36e6a03b80eb81791f
301	2026-10-21 16:28:31	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f0d220748d14da1756a07424a27e82829dbf23ddf289ba36e6a03b80eb81791f	756399c8fdbfcb5036e7e3f70a4d09d4f49657c9a371f5109e919e397cc58f5e
302	2026-10-21 16:28:35	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	756399c8fdbfcb5036e7e3f70a4d09d4f49657c9a371f5109e919e397cc58f5e	2812632dcc7944f30610125f19e82d6b8059583b21277bcca45032c20d491835
303	2026-10-21 16:39:00	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2812632dcc7944f30610125f19e82d6b8059583b21277bcca45032c20d491835	ffbb6bd57515e3aa298aa964946fb9adb46f28ec4fc442c831bfedf47e68814d
304	2026-10-21 16:39:13	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ffbb6bd57515e3aa298aa964946fb9adb46f28ec4fc442c831bfedf47e68814d	7af08edd4b226b1d53f34d6c01472abbcdf0f888b69cb3efbdc269b4dbe3a6e4
305	2026-10-21 16:39:21	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7af08edd4b226b1d53f34d6c01472abbcdf0f888b69cb3efbdc269b4dbe3a6e4	cf2fb86b6884cf740a0fb835a5d1399e08de7645cc15e4ba8924869867f45444
306	2026-10-21 16:49:11	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cf2fb86b6884cf740a0fb835a5d1399e08de7645cc15e4ba8924869867f45444	7ad2b32253dcb663f958e748c44f135cbe9259e5b5b470787f7ec844c007472f
307	2026-10-21 16:49:15	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7ad2b32253dcb663f958e748c44f135cbe9259e5b5b470787f7ec844c007472f	c2f7c5b0f47624963176d4b6527c46c5ef366ddc69abbd98580541cbf4d5e5fd
308	2026-10-21 16:49:48	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c2f7c5b0f47624963176d4b6527c46c5ef366ddc69abbd98580541cbf4d5e5fd	7bfe045b4a78b318fb08595cdf088b5b03f3bf11e1a9bc5a4c8f2a9d32bb4d7e
309	2026-10-21 16:49:50	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7bfe045b4a78b318fb08595cdf088b5b03f3bf11e1a9bc5a4c8f2a9d32bb4d7e	9d399336fa1aa4758365c8f760cdc44b0d872a6272d7bdc7371ffebbfac476ce
310	2026-10-21 16:49:52	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	9d399336fa1aa4758365c8f760cdc44b0d872a6272d7bdc7371ffebbfac476ce	5aa93de9d856ba44bcb7991e16b04df8df92b373f2570ff1ad1641f03dfb03e9
311	2026-10-21 16:55:30	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5aa93de9d856ba44bcb7991e16b04df8df92b373f2570ff1ad1641f03dfb03e9	735c7d11915b71e027961ca5803626cd9920c5f432193b528bf4edd99f3485c2
312	2026-10-21 16:55:36	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	735c7d11915b71e027961ca5803626cd9920c5f432193b528bf4edd99f3485c2	f9660605b79c62b1c9de42a38bcd3f969dabcdaae5fb045a52fdf508da7f27a0
313	2026-10-21 16:55:41	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f9660605b79c62b1c9de42a38bcd3f969dabcdaae5fb045a52fdf508da7f27a0	e1047dd311315f23ce6dc913e7a177e99c916b816971d75341b7800faac675e5
314	2026-10-21 16:56:55	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e1047dd311315f23ce6dc913e7a177e99c916b816971d75341b7800faac675e5	c27d126231b878a2d3a1021cf67827375609695a4f312095c700ea1a9271180d
315	2026-10-21 16:57:12	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	c27d126231b878a2d3a1021cf67827375609695a4f312095c700ea1a9271180d	43f7f8c3388979697cfd2673e11339f64f69f20deed622ecf407fa1b401ee3d0
316	2026-10-22 16:30:35	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	43f7f8c3388979697cfd2673e11339f64f69f20deed622ecf407fa1b401ee3d0	aa51636367891e9974cf82e4417f1078e1a642ac610a3a91486349d6d074aa42
317	2026-10-22 16:30:41	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	aa51636367891e9974cf82e4417f1078e1a642ac610a3a91486349d6d074aa42	61758e30fe55d3c56b49dff40364adcb3f302e0fe4e3d6320e172e1f4fd22d4b
318	2026-10-22 16:37:08	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	61758e30fe55d3c56b49dff40364adcb3f302e0fe4e3d6320e172e1f4fd22d4b	f58e00af196ca5acce5446565a1d19f3217c0e6e93a6d05820d80c5c4c70092b
319	2026-10-22 16:37:08	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f58e00af196ca5acce5446565a1d19f3217c0e6e93a6d05820d80c5c4c70092b	5b7d2f21082a69a5fcdb245161da78bb15fa92e2e29fc6c39d24fef881b5abf5
320	2026-10-22 16:37:21	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	5b7d2f21082a69a5fcdb245161da78bb15fa92e2e29fc6c39d24fef881b5abf5	b193befd751254fe3427dcea940db25fdfe9c5e9a1029421c4d0818bb42a4012
321	2026-10-22 16:44:09	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	b193befd751254fe3427dcea940db25fdfe9c5e9a1029421c4d0818bb42a4012	2b0e8610df53a73a0b17d4ed0c4d052ccf43942081ce493d9bdb81d2f3ff6be9
322	2026-10-22 16:44:30	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2b0e8610df53a73a0b17d4ed0c4d052ccf43942081ce493d9bdb81d2f3ff6be9	1967c5522e0fedab3d814ca1b431d409c8656d614835c11580944769539ae773
323	2026-10-22 16:57:15	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1967c5522e0fedab3d814ca1b431d409c8656d614835c11580944769539ae773	acf11b16d2af7bd560361e4760eb43226ef62e4fa13d4647e5d947f5ad0e1514
324	2026-10-22 16:57:15	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	acf11b16d2af7bd560361e4760eb43226ef62e4fa13d4647e5d947f5ad0e1514	4da0e3c5ed4fb50d255abbbf3e4ac201172e1119c20d9585af1ee20734c40340
325	2026-10-22 17:02:30	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4da0e3c5ed4fb50d255abbbf3e4ac201172e1119c20d9585af1ee20734c40340	a9b82834adb0041f19432b1da3d7ba27d01b7feac98fc093fa53e15ca91fe9bf
326	2026-10-22 17:02:44	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	a9b82834adb0041f19432b1da3d7ba27d01b7feac98fc093fa53e15ca91fe9bf	cf14aad3bd029864bbab6ed5c2b82f1ca92cc22943ecfc769957eb27a4517341
327	2026-10-23 11:15:51	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	cf14aad3bd029864bbab6ed5c2b82f1ca92cc22943ecfc769957eb27a4517341	0bc88acf412dc53589be8b0a57e742a29e5a2c400f7d73d66a0ffa04bbc7ef8c
328	2026-10-23 11:15:58	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	0bc88acf412dc53589be8b0a57e742a29e5a2c400f7d73d66a0ffa04bbc7ef8c	7281e2e584b79da7c8f828b53254082f08b95346b325d0ba5ef679076cb63fe4
329	2026-10-23 11:16:00	INFO	LineasInvestigacion	ver	analitica	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	7281e2e584b79da7c8f828b53254082f08b95346b325d0ba5ef679076cb63fe4	507481ed27d6c2ef208d1bcdd5abebeed281c87e116ee1d1e3178e503216c7c7
330	2026-10-23 11:16:01	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	507481ed27d6c2ef208d1bcdd5abebeed281c87e116ee1d1e3178e503216c7c7	fd3551063088c519a4bd62792de5bdf47662f2231a4e82670613d0e298e6dda6
331	2026-10-23 11:18:25	INFO	LineasInvestigacion	ver	api-prediccion-tendencias	PERMITIDO	7	Miguel González	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	fd3551063088c519a4bd62792de5bdf47662f2231a4e82670613d0e298e6dda6	ddbd5021e80a211a78e2159b91a6efe043c2ab1da3a19f593cff3d30282f9c1a
332	2026-10-28 15:05:09	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	ddbd5021e80a211a78e2159b91a6efe043c2ab1da3a19f593cff3d30282f9c1a	7725330ea37a54bcfc75b5cb7d879f9854b3f366cd236c67157cb2b270ccd4b5
333	2026-10-28 15:05:29	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	7725330ea37a54bcfc75b5cb7d879f9854b3f366cd236c67157cb2b270ccd4b5	87f8a0e70106624192f8a83247feae9dc0e65c7f057881086917ecd20453d312
334	2026-10-28 15:06:08	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	87f8a0e70106624192f8a83247feae9dc0e65c7f057881086917ecd20453d312	0fdeb01293c35d9fac909d143347e8eb40e4cc430418cef7d47ff33c7914a3af
335	2026-10-28 15:16:16	INFO	LineasInvestigacion	ver	lineas-investigacion	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	0fdeb01293c35d9fac909d143347e8eb40e4cc430418cef7d47ff33c7914a3af	e14f18598112ee18fb140d4ec2cc2ff213df7f1d5dae15a643d6ba7480bbe760
336	2026-10-28 15:19:03	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	e14f18598112ee18fb140d4ec2cc2ff213df7f1d5dae15a643d6ba7480bbe760	4e9d5c84689d1672c7044230b40095b739bc30d5791d480a878c610c1ff68c70
337	2026-10-28 15:20:48	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	4e9d5c84689d1672c7044230b40095b739bc30d5791d480a878c610c1ff68c70	769b29ef46ea55efd276018d4daacbcbdcb1cb397c70380759003a9ae8bf5ead
338	2026-10-28 15:21:43	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	769b29ef46ea55efd276018d4daacbcbdcb1cb397c70380759003a9ae8bf5ead	3471c060e7b0de82cb7b0c47266befeeba2d19e4829ec63fde6e5bb16da73363
339	2026-10-28 15:22:07	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	3471c060e7b0de82cb7b0c47266befeeba2d19e4829ec63fde6e5bb16da73363	b005abf821a4590c05ade0aa07f020ba2b1cc099aca7629944161bef6e763fe0
340	2026-10-28 15:23:16	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	b005abf821a4590c05ade0aa07f020ba2b1cc099aca7629944161bef6e763fe0	775160d113074e12f63192f9e55114bd4a8c8e9776e3b259155c5da60455a185
341	2026-10-28 15:23:37	INFO	VinculacionEmpresarial	ver	cartelera-oportunidades	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	775160d113074e12f63192f9e55114bd4a8c8e9776e3b259155c5da60455a185	bced84081a96d101ebb7b486eeaf15c707b579feb32be432d0363db84762f261
342	2026-10-28 15:24:02	INFO	VinculacionEmpresarial	ver	empresas-inicio	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Sector Productivo».	bced84081a96d101ebb7b486eeaf15c707b579feb32be432d0363db84762f261	4bb99deb09c8d978d100aa648eaf4db86e82d02a4085caed6815a26da2078809
371	2026-11-01 21:57:52	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	4bb99deb09c8d978d100aa648eaf4db86e82d02a4085caed6815a26da2078809	dea9988a0095c38619d8f475b2ecff4f2862fad52609cca12c6975d8e81dc9eb
372	2026-11-01 21:57:54	INFO	Investigaciones	ver	investigadores	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	dea9988a0095c38619d8f475b2ecff4f2862fad52609cca12c6975d8e81dc9eb	12110b1f064245571628caede9b91704df5f2e7f787887e1707704ead677b61e
373	2026-11-01 21:58:44	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	12110b1f064245571628caede9b91704df5f2e7f787887e1707704ead677b61e	6377a958a6ad37f7ee359b7f36eb933d1b3ec56fb6f9bb0b9dcc6d7562533a6a
374	2026-11-01 21:58:49	INFO	Investigaciones	gestionar	panel-investigaciones-admin	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6377a958a6ad37f7ee359b7f36eb933d1b3ec56fb6f9bb0b9dcc6d7562533a6a	f929bdd1ae7671182a12bfb3a7ac7faad648ea6ec4b589ac81b5247fe88470b9
375	2026-11-01 21:59:30	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel González	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f929bdd1ae7671182a12bfb3a7ac7faad648ea6ec4b589ac81b5247fe88470b9	8fe6863759b91bd22fe12ddec48647345fe16ce1c8de4dae9c7369af0e5a1043
376	2026-11-02 12:36:35	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	8fe6863759b91bd22fe12ddec48647345fe16ce1c8de4dae9c7369af0e5a1043	4fef684becd924a322aa132dded3fc9950cd12a047d36332ebfc73d6af580324
377	2026-11-02 12:36:41	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	4fef684becd924a322aa132dded3fc9950cd12a047d36332ebfc73d6af580324	84a018a936dc11e253722cb4df8f5263078dfe314d945b9f4bd164af9678135a
378	2026-11-02 12:36:43	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	84a018a936dc11e253722cb4df8f5263078dfe314d945b9f4bd164af9678135a	62cc7bcdf5161cbdc891d0057d5dd766d6077f1d30fd02f595b5d0b00063411a
379	2026-11-02 12:36:45	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	62cc7bcdf5161cbdc891d0057d5dd766d6077f1d30fd02f595b5d0b00063411a	9ff5841b68ecbc46f51ca07bab79e9e4de63b80c7002651367f4434989e473cb
380	2026-11-02 12:36:48	INFO	Cursos	ver	cursos	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	9ff5841b68ecbc46f51ca07bab79e9e4de63b80c7002651367f4434989e473cb	6a10d02e8cecae279974ed69378d59bca73e44de83f84671fcd3711d7fc2a21b
381	2026-11-02 12:36:56	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	6a10d02e8cecae279974ed69378d59bca73e44de83f84671fcd3711d7fc2a21b	c73c405db3bc0ef9cf48cdbf97a5681a8560bea93c5744993e88122c894e1959
382	2026-11-02 12:37:04	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	c73c405db3bc0ef9cf48cdbf97a5681a8560bea93c5744993e88122c894e1959	62b0abe6d182960d0e16451277cf1745f41c460520a103b5cef69dfe21fb91f1
383	2026-11-02 12:37:07	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	62b0abe6d182960d0e16451277cf1745f41c460520a103b5cef69dfe21fb91f1	07ef9719083e930d5323324b66f4db77d14135977d8938b274598531101400b3
384	2026-11-02 12:37:09	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	07ef9719083e930d5323324b66f4db77d14135977d8938b274598531101400b3	c5a0273943d7208bb0040505927ea9bae65371bb20e0f5d0fa07113356ab54f7
385	2026-11-02 12:37:10	INFO	Cursos	ver	cursos-detalle	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Formación».	c5a0273943d7208bb0040505927ea9bae65371bb20e0f5d0fa07113356ab54f7	fa051145d75ae1f191ad5782bfda63441ce21de0efc907edab3b60c8a24e1a02
386	2026-11-02 12:37:23	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	fa051145d75ae1f191ad5782bfda63441ce21de0efc907edab3b60c8a24e1a02	26cda89afcfb920cf88bf79bc713788c3ef5d5f06d371633eef8047d4cc72175
387	2026-11-02 12:37:25	INFO	Investigaciones	ver	investigadores	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	26cda89afcfb920cf88bf79bc713788c3ef5d5f06d371633eef8047d4cc72175	ec549d7cc10ebb1403211103a02cc659ad49683e9fa0674f45b1c0166b7e847a
388	2026-11-02 12:42:47	INFO	Investigaciones	ver	investigaciones	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	ec549d7cc10ebb1403211103a02cc659ad49683e9fa0674f45b1c0166b7e847a	5b699ec786b6b237ccfd2b2d2368975abcbcd5a6f5a35357e58b88dcd779f7db
389	2026-11-02 12:42:51	INFO	Investigaciones	ver	investigadores	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Investigaciones».	5b699ec786b6b237ccfd2b2d2368975abcbcd5a6f5a35357e58b88dcd779f7db	68327b2ec3e0ae02cefbaee09ce8cd5c460fce8b94f8e908032efe55825adb98
390	2026-11-02 12:42:54	INFO	LineasInvestigacion	ver	detalle-linea	PERMITIDO	\N	Visitante anónimo	999	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Matriz de permisos: Visitante (sin sesión) tiene «Ver» en «Líneas».	68327b2ec3e0ae02cefbaee09ce8cd5c460fce8b94f8e908032efe55825adb98	657a6772e89c427e276b533fc5ff535ebfb2f6d89bb90ab67c8dc46fed6abefe
391	2026-11-02 12:46:17	INFO	Investigaciones	ver	investigaciones	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	657a6772e89c427e276b533fc5ff535ebfb2f6d89bb90ab67c8dc46fed6abefe	99bdad5ce35efdf71323fee6539490f33d0528bf8079b9cc812bcb0d3916ec8c
392	2026-11-02 12:46:20	INFO	Investigaciones	gestionar	panel-investigaciones-admin	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	99bdad5ce35efdf71323fee6539490f33d0528bf8079b9cc812bcb0d3916ec8c	6b237e245ea0249415967aa907345765e8f51910284aca786b9dde450c960dc8
393	2026-11-02 12:50:09	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	6b237e245ea0249415967aa907345765e8f51910284aca786b9dde450c960dc8	93fb1ac9ea1f20b5b711d939359eb9e49c5cb7487fa103ab5e3229ad987b9837
394	2026-11-02 12:58:27	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	93fb1ac9ea1f20b5b711d939359eb9e49c5cb7487fa103ab5e3229ad987b9837	4cbd218d4ec09873e74ffe1668b466218d618dddcd4d01a58bb9eeef44506c05
395	2026-11-02 12:58:35	INFO	Investigaciones	gestionar	mis-investigaciones	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	4cbd218d4ec09873e74ffe1668b466218d618dddcd4d01a58bb9eeef44506c05	beed138d687f6bb3e1de70e3d13f039e8b87b9aae1782ba096dd0ef2f557f182
396	2026-11-02 12:58:37	INFO	Investigaciones	ver	investigadores	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	beed138d687f6bb3e1de70e3d13f039e8b87b9aae1782ba096dd0ef2f557f182	2b93d2581adc458a1f5c173443647a16c6e12130b2d1b6e33be3b3c551d17ee4
397	2026-11-02 13:03:24	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	2b93d2581adc458a1f5c173443647a16c6e12130b2d1b6e33be3b3c551d17ee4	17e062fe24f5d1c0bbeca93e2c6620be4f823ab2be136cb2d92fccbdf22fadd2
398	2026-11-02 13:03:28	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	17e062fe24f5d1c0bbeca93e2c6620be4f823ab2be136cb2d92fccbdf22fadd2	effe3ecb99e2a8a8f532e78953584e5f5cc45a2bd82d433ed909c2a3a7ea6a9a
399	2026-11-02 13:07:07	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	effe3ecb99e2a8a8f532e78953584e5f5cc45a2bd82d433ed909c2a3a7ea6a9a	1336c6a8e3650f90fde3c14931a77cc28f4b73afff164bcce4eef83f9cb96557
400	2026-11-02 13:27:16	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1336c6a8e3650f90fde3c14931a77cc28f4b73afff164bcce4eef83f9cb96557	e9d8e57a26c1887542658e464153a79e740751a61c57ff1c73fbfe7af3ec23d5
401	2026-11-02 13:27:19	INFO	Cursos	editar	cursos-editar	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	e9d8e57a26c1887542658e464153a79e740751a61c57ff1c73fbfe7af3ec23d5	1790be356af0f56a8a87b96f3d4dca7327baaf2a028166fe7c892d5426bb8ad3
402	2026-11-02 13:27:32	INFO	Cursos	editar	cursos-procesar-editar	PERMITIDO	7	Miguel Gonzalez	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	1790be356af0f56a8a87b96f3d4dca7327baaf2a028166fe7c892d5426bb8ad3	d619e5276bedee87ae57a95d0e42df64a223bf0748fb03259664a7b31562578f
403	2026-11-02 13:27:33	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d619e5276bedee87ae57a95d0e42df64a223bf0748fb03259664a7b31562578f	8f920e38b9ee7942e1732a0a8b20696bd36b1bd7f47b9db5bbdc9a4286d35c85
404	2026-11-02 13:27:40	INFO	Cursos	ver	cursos	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	8f920e38b9ee7942e1732a0a8b20696bd36b1bd7f47b9db5bbdc9a4286d35c85	f5e0827f47d4ceab02b53622538d5cc6e71c11576d21a6496edb770199473b0a
405	2026-11-02 13:27:43	INFO	Cursos	ver	cursos-formulario-google	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	f5e0827f47d4ceab02b53622538d5cc6e71c11576d21a6496edb770199473b0a	ae27f3d7b423b266bc87f6f01dfa28c926f66c2e9c86834a60064bf06583439c
406	2026-11-02 13:27:58	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	ae27f3d7b423b266bc87f6f01dfa28c926f66c2e9c86834a60064bf06583439c	d3f213be09a92b925f2535c3b7d36c322e5d8147f6985177264d053014fc9433
407	2026-11-02 13:28:00	INFO	Cursos	editar	cursos-editar	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	d3f213be09a92b925f2535c3b7d36c322e5d8147f6985177264d053014fc9433	14a22d125bcaea9433ac794c885411c4e23327f2f1270b1135c4dab4f14c91f8
408	2026-11-02 13:28:07	INFO	Cursos	editar	cursos-procesar-editar	PERMITIDO	7	Miguel Gonzalez	0	::1	POST	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	14a22d125bcaea9433ac794c885411c4e23327f2f1270b1135c4dab4f14c91f8	081609143670a7a8d644ecd35f6a2459a90b9f82ebdaae594bc2c7a04d9e368a
409	2026-11-02 13:28:08	INFO	Cursos	gestionar	cursos-gestion	PERMITIDO	7	Miguel Gonzalez	0	::1	GET	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36	Nivel 0 (SuperAdmin): acceso total, inmune a bloqueos.	081609143670a7a8d644ecd35f6a2459a90b9f82ebdaae594bc2c7a04d9e368a	9c76d741de92dd064672a9bfcdc47070884c300cd401cf9ab9d13fbddf7cd779
\.


--
-- TOC entry 5833 (class 0 OID 60368)
-- Dependencies: 296
-- Data for Name: seg_lineas_permisos; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_permisos (nivel, submodulo, accion, permitido, actualizado_por, actualizado_en) FROM stdin;
\.


--
-- TOC entry 5840 (class 0 OID 60444)
-- Dependencies: 303
-- Data for Name: seg_lineas_politicas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_politicas (clave, valor, actualizado_por, actualizado_en) FROM stdin;
modo_bloqueo	false	Miguel González (ID 7)	2026-10-12 21:57:46
bloqueo_nivel_maximo	0	Miguel González (ID 7)	2026-10-12 21:57:46
bloqueo_mensaje	"El macro módulo Líneas de Investigación se encuentra en modo de contención (Lockdown) por razones de seguridad."	Miguel González (ID 7)	2026-10-12 21:57:46
solo_lectura	false	Miguel González (ID 7)	2026-10-12 21:57:46
registrar_lecturas	true	Miguel González (ID 7)	2026-10-12 21:57:46
registrar_visitantes	true	Miguel González (ID 7)	2026-10-12 21:57:46
retencion_dias	90	Miguel González (ID 7)	2026-10-12 21:57:46
umbral_denegaciones	5	Miguel González (ID 7)	2026-10-12 21:57:46
ventana_minutos	10	Miguel González (ID 7)	2026-10-12 21:57:46
auto_bloqueo_ip	true	Miguel González (ID 7)	2026-10-12 21:57:46
duracion_bloqueo_min	30	Miguel González (ID 7)	2026-10-12 21:57:46
ips_confiables	[]	Miguel González (ID 7)	2026-10-12 21:57:46
refresco_feed_seg	100	Miguel González (ID 7)	2026-10-12 21:57:46
ultima_purga	"2026-10-22 16:30:37"	Miguel González (ID 7)	2026-10-22 16:30:37
\.


--
-- TOC entry 5841 (class 0 OID 60457)
-- Dependencies: 304
-- Data for Name: seg_lineas_rutas; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.seg_lineas_rutas (ruta, accion, actualizado_por, actualizado_en) FROM stdin;
\.


--
-- TOC entry 5845 (class 0 OID 60491)
-- Dependencies: 308
-- Data for Name: solicitudes_docentes_pst; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.solicitudes_docentes_pst (id, id_profesor, es_profesor_pst, trayectos, estado, motivo, fecha_solicitud, fecha_resolucion, id_revisor) FROM stdin;
1	6	t	[2, 3]	aprobado	Aprobado en prueba técnica	2026-10-12 19:14:13.853705	2026-10-12 19:14:13.860953	1
\.


--
-- TOC entry 5810 (class 0 OID 51702)
-- Dependencies: 273
-- Data for Name: system_audit_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.system_audit_log (id, fecha_hora, nivel, modulo, accion, detalles, responsable, ip, hash_anterior, hash_integridad) FROM stdin;
log_6aae33579b38a	2026-09-19 07:01:43	WARNING	SuperAdmin	Modificar Matriz RBAC	Se actualizaron los permisos granulares por Módulo y Nivel.	Miguel González (ID: 7)	::1	GENESIS_CIIDI_V1	7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5
log_6aae335f0804a	2026-09-19 07:01:51	WARNING	SuperAdmin	Modificar Matriz RBAC	Se actualizaron los permisos granulares por Módulo y Nivel.	Miguel González (ID: 7)	::1	7ce1ff0dbf2ae1f6bcfd13e8e1f43e3f22eb9785c9790be455571ddfbc027aa5	b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2
log_6aae3381958c6	2026-09-19 07:02:25	WARNING	SuperAdmin	Modificar Matriz RBAC	Se actualizaron los permisos granulares por Módulo y Nivel.	Miguel González (ID: 7)	::1	b92bffbeb139d260065cf1393677605a9164f8c200f4265b76d6a17ce53a05b2	92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da
log_6aae33853f3ff	2026-09-19 07:02:29	WARNING	SuperAdmin	Modificar Matriz RBAC	Se actualizaron los permisos granulares por Módulo y Nivel.	Miguel González (ID: 7)	::1	92bd5c01e500270acb0577a629f1ba38e01030a6c8f48011175fe43db49f11da	10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8
log_6aae346b666a8	2026-09-19 07:06:19	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-19_06-32-52.sql.gz	Miguel González (ID: 7)	::1	10728c9fbed4aaed7081884890652f4a51ca7732afdf7452bb54491753b978b8	7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b
log_6aae346e42a0f	2026-09-19 07:06:22	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-19_06-32-55.sql	Miguel González (ID: 7)	::1	7165f8e5f3d33d1d9c132ee3905aaa19e0b505521014637fd448096702e3f29b	989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740
log_6aae3470d43bb	2026-09-19 07:06:24	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-19_07-06-24.sql.gz generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	989e826b2ae9a6fe7bd9aaa9edc5514dbe52f58059ddd0041facdb691b27f740	63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0
log_6aaebe659d9cc	2026-09-19 16:55:01	WARNING	SuperAdmin	Restaurar BD	Base de datos restaurada exitosamente.	Miguel González (ID: 7)	::1	63aac83ff563a917cade4fa0383272872eb32923d19eda5ce4f53e737d20e5b0	349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b
log_6aaebe6f1390f	2026-09-19 16:55:11	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: pre_restore_checkpoint_2026-09-19_16-54-59.sql.gz	Miguel González (ID: 7)	::1	349aec4bff15575b26a32e4e827f5fa0b09c9c67540455b521bc655719099d4b	801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c
log_6aaebe75516f3	2026-09-19 16:55:17	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-19_16-55-16.sql generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	801ddd3377f74f80faf0d829ed9a4936124a1fa57770ffe290c6644645fea72c	b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53
log_6aaec0c63a16d	2026-09-19 17:05:10	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-19_07-06-27.sql	Miguel González (ID: 7)	::1	b8aeed769123fca37fbec08b1a4ce40e9b6000dd7b4433dba98361d52cb82b53	ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b
log_6aaec0c9790a9	2026-09-19 17:05:13	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-19_16-55-16.sql	Miguel González (ID: 7)	::1	ac1dc2a4b53a1009983ef217a2f5cf8cb889ec700941cf1d5bc8911706c84f5b	dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3
log_6aaec0cea62f3	2026-09-19 17:05:18	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-19_17-05-18.sql generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	dd3b69603536d8045b7c4ee32c271343ebfe05ecf606e4d214daf7bbe79c8ed3	65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973
log_6aaeca163cca5	2026-09-19 17:44:54	WARNING	SuperAdmin	Alternar Mantenimiento	Modo Mantenimiento cambiado a: ACTIVADO	Miguel González (ID: 7)	::1	65007dff8b8b666fd547968b7983bf2de71dfeadf9761976f4e2f049953aa973	4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b
log_6aaecf3f2e00d	2026-09-19 18:06:55	WARNING	SuperAdmin	Alternar Mantenimiento	Modo Mantenimiento cambiado a: ACTIVADO	Miguel González (ID: 7)	::1	4586eb896d5ba339932b8a61996fcd3a3124b5071244225196efca54a791511b	8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2
log_6aaecf464d72a	2026-09-19 18:07:02	WARNING	SuperAdmin	Cancelar Mantenimiento	Se canceló la programación de mantenimiento.	Miguel González (ID: 7)	::1	8ed5ed8574e2e69fa95fd4553f8bae317f6d1e13610566c143f179c7792cfbc2	d337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2
log_6aaed1b55d6b7	2026-09-19 18:17:25	WARNING	SuperAdmin	Alternar Mantenimiento	Modo Mantenimiento cambiado a: ACTIVADO	Miguel González (ID: 7)	::1	d337a13650b0f0a06ba7fd0b389310e945347643acc48af1cd935c86e8aa96a2	e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f
log_6aaed233578db	2026-09-19 18:19:31	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. 30469331 (ANDRUS). Sesión remota revocada.	Miguel González (ID: 7)	::1	e7a9c3adc156c3bf8c972ed917cc540d8b56f0cc1b13eecf59feafe3ac16169f	f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24
log_6aaed96f372d3	2026-09-19 18:50:23	WARNING	SuperAdmin	Restaurar BD	Base de datos restaurada exitosamente.	Miguel González (ID: 7)	::1	f21c7a96beef9a891da0ce2d7a2b30973006c2d7442c23b8cf409bd795beeb24	c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a
log_6aaedabf05687	2026-09-19 18:55:59	WARNING	SuperAdmin	Eliminar/Archivar Usuario	Usuario ID #12 archivado exitosamente. Credenciales liberadas.	Miguel González (ID: 7)	::1	c4db66aeafb4bab24307351232fe9c4c63483667607bdb6a29acf0d6f47e2f5a	bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2
log_6aaedb9bdf105	2026-09-19 18:59:39	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. 30469331 (adru). Sesión remota revocada.	Miguel González (ID: 7)	::1	bb6acf5c46a0c61371a549aa4812747848aef5099d21cccd226e747f9980f0a2	5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c
log_6aaedc24ecf08	2026-09-19 19:01:56	ERROR	SuperAdmin	Falla de Prueba SMTP	Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder	Miguel González (ID: 7)	::1	5aa477e581f9db3485b2cf6b648fa17766329c7ed071923f65ad5e9e1b8dab9c	eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b
log_6aaedeb86eab2	2026-09-19 19:12:56	ERROR	SuperAdmin	Falla de Prueba SMTP	Error al enviar correo vía SMTP: SMTP Error: Could not connect to SMTP host. Failed to connect to server SMTP server error: Failed to connect to server SMTP code: 10060 Additional SMTP info: Se produjo un error durante el intento de conexión ya que la parte conectada no respondió adecuadamente tras un periodo de tiempo, o bien se produjo un error en la conexión establecida ya que el host conectado no ha podido responder	Miguel González (ID: 7)	::1	eebe886d838449eb8d4f3e270c5e1f7f4e4a6b57b3111486e3ecddb6fd7b632b	bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe
log_6aaededc7c8cf	2026-09-19 19:13:32	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. 12000000 (DIOSs). Sesión remota revocada.	Miguel González (ID: 7)	::1	bed82f32e49dcb86f4e77232b6a578ef769f68953bbc1a19fe960dc00e1e44fe	242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99
log_6aaedee65ff6d	2026-09-19 19:13:42	WARNING	SuperAdmin	Eliminar/Archivar Usuario	Usuario ID #15 archivado exitosamente. Credenciales liberadas.	Miguel González (ID: 7)	::1	242c4f4c685154d95531fb44b69690f12783afa64a474e79cbc38dee11698f99	8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381
log_6aaee02b5ef05	2026-09-19 19:19:07	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.	Miguel González (ID: 7)	::1	8264b8ad8fb7df55feea033a101e69b801e8296607aae5c606ff15eb32d51381	2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270
log_6aaee0323afa9	2026-09-19 19:19:14	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. 30469331 (adruss). Sesión remota revocada.	Miguel González (ID: 7)	::1	2bf175c5db6f82acf3aff448d875d6d13638f66295bb93f44f2aa5e2273f9270	507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80
log_6ab593eecb01f4.27328092	2026-09-24 17:19:42	WARNING	SuperAdmin	Restaurar BD	Base de datos restaurada exitosamente.	Miguel González (ID: 7)	::1	507df31d69558dd537b201a78eb1a1dfc89de9d95df0274a9a653d97296d2c80	c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54
log_6ab59ed3238506.36285869	2026-09-24 18:06:11	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	c164b6c65778035a0544ddb503089c9730110a259266cd6a181feca17389ef54	5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528
log_6ab5a4aa2a2137.51481159	2026-09-24 18:31:06	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	5bf4ddbf99d32f7e140d3b192d4539f00d0960d7ab5346e0a2ad8a7e4cd1a528	6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0
log_6ab5a500ea1546.02583757	2026-09-24 18:32:32	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	6fc073f5d96e828c0caa98a5e7e123baf85a48f4466c139de387b3095f1956b0	b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44
log_6ab5a5088d0500.29959545	2026-09-24 18:32:40	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	b7fbdef23f8d6bb1d3061ceb485e49318baa3d66380ca33458a925158a5b8f44	38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd
log_6ab5a541bc6647.30407607	2026-09-24 18:33:37	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	38ff319019928760775a7f24e568b701c723e6b79d731daa65044eda6c34c9fd	9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8
log_6ab5a5cb411a84.57589918	2026-09-24 18:35:55	INFO	Autenticacion	Cierre de Sesión	El usuario 'Usuario' (ID: 0) cerró su sesión voluntariamente.	Anónimo / Sistema	::1	9454cd2a733a389f806ed252c25b8a8cf27aa9101af2e61957c0c29ee9356dc8	e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8
log_6ab5a5d5d60719.47566487	2026-09-24 18:36:05	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	e38927a84ac7e9f65f483b42c161f5b4574f456b72cf20814b05ad3708d1eee8	31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95
log_6ab5a5e98d5860.20797335	2026-09-24 18:36:25	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	31bc726932df4ce9990bbe301fdf6945e15265649586daa966ea1a7a2d453e95	8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3
log_6ab5a60fd00c08.81125505	2026-09-24 18:37:03	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	127.0.0.1	8c93ada41b8a0a4c4cbf71ef039250a0001677c7225a3198cdfd18a416526ee3	8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05
log_6ab5a69a3cf6f8.92796130	2026-09-24 18:39:22	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	8452ae821eccf561e7e67876865e329fa67968fbb2e1a8220958762f617e3b05	37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5
log_6ab5ab824ef6e1.77162492	2026-09-24 19:00:18	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	37031ecbe7b940a8c14a78f7fdda5bf094034df27b27a174acac85c783d9dfa5	17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53
log_6ab5ab9f7dc311.02229887	2026-09-24 19:00:47	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	17d6aa88a0e0810aa856b6105a92fe3f087c1757891a5d3b4f0acd5b9b69fa53	94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208
log_6ab5abba3afc76.06917239	2026-09-24 19:01:14	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	94bce1c39ee0d7681a12dc3ad0df642b46f235ebebbcb4eec5a564e852e34208	1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6
log_6ab5c20c587658.74181273	2026-09-24 20:36:28	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	1243582a67b2ecea0b004d6391de77c30754d3fcfb77676b9698dc39fe9673b6	fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82
log_6ab5c2119c0646.75356991	2026-09-24 20:36:33	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	fc38857a09d1a7bd5ed9ed7ba12ce07679dfc9f56e18fcd7f6ed30b6ca0e8e82	45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7
log_6ab5c23b56c262.01662172	2026-09-24 20:37:15	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	45326978d7906c59688ecb52d4e1ea7cc6dc9129a36b8e9c5debb3d80dbcf3f7	191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687
log_6ab5c497d80b16.73582852	2026-09-24 20:47:19	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	191fffdc6aa24c48338298ac5ad880071b659bcbc79cf83ebff05c0747864687	555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d
log_6ab5c49c012169.70473067	2026-09-24 20:47:24	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	555abac41003b5a2947ec3014614af60f786bf0292dfd73d85650d886d18844d	b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91
log_6ab5d571944a33.87726796	2026-09-24 21:59:13	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'Generar Embeddings Semánticos' (tarea_1790301553_528) guardada correctamente.	Miguel González (ID: 7)	::1	b9314cb716a43f7707854c1af03db59af5aa4bb81c2b64429ed47a3c495fbf91	e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895
log_6ab5dcb5b7e0d3.81594761	2026-09-24 22:30:13	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'Generar Embeddings Semánticos' (tarea_1790301553_528) guardada correctamente.	Miguel González (ID: 7)	::1	e953103b3232f485fa1e16f24bb819ec4626b9320e442da74400cbc010baf895	60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63
log_6ab5dd88ba4952.54635639	2026-09-24 22:33:44	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (325.13ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	60e6575d0765725a787ea20cea31a83593ffa9e0bfe19db7ae4b63b9ef27cf63	46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305
log_6ab5ebe769d5f8.32585648	2026-09-24 23:35:03	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	46b0944850e1fef0f240c5a7219d7a3f5335a8d6cce0a194abae967643177305	bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248
log_6ab5ebef7dc0c5.68948338	2026-09-24 23:35:11	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: 32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	bb0ca07139723430c908a6cd1ed96d8b4f945c5a438c702aed8a3d2350000248	a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20
log_6ab5ee9f6acaa8.80378739	2026-09-24 23:46:39	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-20_21-00-03.sql	Miguel González (ID: 7)	::1	a73d37a7459c32144e7987adba6921cadd1b2558025f886bd1d893c457956e20	9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209
log_6ab5eea5350548.04228995	2026-09-24 23:46:45	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-23_12-31-32.sql	Miguel González (ID: 7)	::1	9f9da3cf8a845ed5fcaf7268b81b7c80432508bafaeaa45b8ec077f593497209	a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698
log_6ab5eea88755c2.71231592	2026-09-24 23:46:48	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: pre_restore_checkpoint_2026-09-24_17-19-41.sql.gz	Miguel González (ID: 7)	::1	a52bae63cf20b2bb0509c95ed50717e786d6f5e6ec4be25e6cf3fe2a0b6a9698	a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0
log_6ab5eeabaf89e3.77502777	2026-09-24 23:46:51	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-24_17-02-25.sql.gz	Miguel González (ID: 7)	::1	a3c336121c91834c62dc07058358c7d24f3677b6a89f6829aea0bd9e8d8df8b0	523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923
log_6ab5eeb4554ee6.59306678	2026-09-24 23:47:00	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-24_23-46-59.sql generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	523cc34236028597efe5b15ef29178567ee028fa9e38740d2b53f9fd940f1923	7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704
log_6ab610b6a541b2.81849834	2026-09-25 02:12:06	WARNING	SuperAdmin	Eliminar Backup	Respaldo eliminado: backup_ciidi_2026-09-24_23-46-59.sql	Miguel González (ID: 7)	::1	7232d7def5c5964dd8ce0ab90266e5e4d20dc4eef27958ff112ba3a87c3a9704	6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a
log_6ab70b912729a0.27256257	2026-09-25 20:02:25	WARNING	SuperAdmin	Restaurar BD	Base de datos restaurada exitosamente.	Miguel González (ID: 7)	::1	6d353e52b1959ac5aa133154c91ef17dd24d549557879faf8dd1c7d30967e47a	3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69
log_6ab71c65db02e8.54816092	2026-09-25 21:14:13	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	3ceaaa8c731c76398df4a18c9ae76abf66a21c547258ca8e51edf2107d5a7f69	19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b
log_6ab71c756d2f31.50517848	2026-09-25 21:14:29	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	19bc814ad5a3bff737006cf37e30277339a6d5b34d67381736171f5b887f773b	d0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a
log_6ab73a17887213.71832384	2026-09-25 23:20:55	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	d0054623e3832e8ed7ad40d95985631e6ae2a64f029815c91255079667ea855a	42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f
log_6ab73a1baa8920.77773463	2026-09-25 23:20:59	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	42c1e4f565c91c4d7938aba471fbf1c43347605917a7607e17c15a8d0d2a552f	ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e
log_6ab73d5913fb96.78987378	2026-09-25 23:34:49	WARNING	SuperAdmin	Modificar Variables Globales	Se actualizaron las variables de entorno del sistema.	Miguel González (ID: 7)	::1	ffc3b2237e5faad0da47411bbe86443cf108296dc97d737d4b495a4a5939876e	c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4
log_6ab73fd9a749b1.66865439	2026-09-25 23:45:29	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry' (ID: #159).	Miguel González (ID: 7)	::1	c960f6fc55094e10a9011cb694c74c94afbf492b93d046f41840f2b24554d3d4	0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307
log_6ab743ce003df8.01116084	2026-09-26 00:02:22	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARadsasdadasdIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”' (ID: #162).	Miguel González (ID: 7)	::1	0d39d3bcaa02c78214fe24d24f809e7742b14ab64c323cbde5f5a72bd1146307	68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757
log_6ab74400d95f48.22006442	2026-09-26 00:03:12	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Acadésadasdsadasmicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales' (ID: #163).	Miguel González (ID: 7)	::1	68b097b9624c380e488f522ccafdca642924cd727daab87fe4d5b9da4fa91757	eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5
log_6ab74420405498.05037611	2026-09-26 00:03:44	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	eb4e28a14e71284e091db7c447eb543f50bcd752b5c7b9f21403c694654e44d5	f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b
log_6ab74424edf762.62531298	2026-09-26 00:03:48	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	f136fc7448f2920f72d994f3fd0f0d60d52ee7282ada3053ca62dfd8760d480b	30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670
log_6ab8130a6ed6c0.41201158	2026-09-26 14:46:34	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	30af9e6fd073985bd4cc0923642e22bc4cc05079c8a027538fa1cc7757589670	18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26
log_6ab813205a1172.16639687	2026-09-26 14:46:56	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	18c36835c1751b0f7fa3127da03de112da77a3cb38cf3c98ed1952d0e4300a26	b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4
log_6ab814627139a2.17495957	2026-09-26 14:52:18	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	b8f63e54c128cb9eb0f4f85a2d77b85f59d2ace3cc93d5d3f192e71cb83b51e4	07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b
log_6ab850ed39c7d0.55124032	2026-09-26 19:10:37	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	07c9398c309fe1189fbba5a31d5944480c7a02f5d57cd58e90a10f206b68174b	a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813
log_6ab851e5cd0946.14674728	2026-09-26 19:14:45	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Articulos cambiado a estado: offline	Miguel González (ID: 7)	::1	a6a77e8e1e03eb61c49e509b71466de7e2e0c454f653685dd01420cab0422813	c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e
log_6ab851e7c0e2d4.82296632	2026-09-26 19:14:47	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Articulos cambiado a estado: online	Miguel González (ID: 7)	::1	c2aaf13c9fdc6d10f980ee7132d5cfdb3af6c4555c63d983b9e7ba54a043805e	0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b
log_6ab851e94472b4.95421959	2026-09-26 19:14:49	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Articulos cambiado a estado: offline	Miguel González (ID: 7)	::1	0eb362f61efdb465cc9f53731f0bced200777c9fa3fbcbff72b6a6536c989d5b	fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681
log_6ab851ea611c46.80912087	2026-09-26 19:14:50	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Articulos cambiado a estado: online	Miguel González (ID: 7)	::1	fdde9b686d8461b8c7eff092fc68c75d2f00b11e8dc62df98778e917985fc681	0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd
log_6ab852ab554c79.73686163	2026-09-26 19:18:03	WARNING	SuperAdmin	Alternar Mantenimiento	Modo Mantenimiento cambiado a: ACTIVADO	Miguel González (ID: 7)	::1	0e0d6e08481995b3601f9098955eaae5e0196016bed172257809748fc4e548dd	1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136
log_6ab852adc206f8.64334637	2026-09-26 19:18:05	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	1fd667734c8070fcebb5c77e7e9b813f652f218e5dadc5c2c4b39e0f23258136	3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3
log_6ab852bb98a3d2.31949010	2026-09-26 19:18:19	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	3493dc92f747c8a3faf8c2fb3026078e60f6ad3fc024219b55cc05a11cb8e9b3	ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c
log_6ab852c6e3d2d4.83193988	2026-09-26 19:18:30	WARNING	SuperAdmin	Alternar Mantenimiento	Modo Mantenimiento cambiado a: DESACTIVADO	Miguel González (ID: 7)	::1	ddd3f69e0146a824b9a9a3bcc61ad121652be7ae9bcb2bd65d4cf69b3b2d672c	cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd
log_6abaf653d69cd4.91449578	2026-09-28 19:20:51	INFO	Autenticacion	Cierre de Sesión	El usuario 'Usuario' (ID: 7) cerró su sesión voluntariamente.	Anónimo / Sistema	::1	cc429cb286befeaffd47be2ceb15426c4eab711202574c1f064396b5ab110cfd	9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec
log_6abaf659e980f7.77464928	2026-09-28 19:20:57	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	9d15d0848ee574955f81b62cfe73300491c3622e1a835114176b4ad752b5c3ec	de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada
log_6abb00739e9ad6.86798317	2026-09-28 20:04:03	INFO	SuperAdmin	Configurar SMTP	Se actualizaron las credenciales del servidor SMTP institucional (cifrado AES-256).	Miguel González (ID: 7)	::1	de77e14ce29523c74199fa1dcf4b87ee14fe373a72562fe48b0cb40c46bcbada	ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1
log_6abb0086ba4219.64363834	2026-09-28 20:04:22	ERROR	SuperAdmin	Falla de Prueba SMTP	Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]	Miguel González (ID: 7)	::1	ff666bf04da76abc79d4ec5278eeb9bd0cb08b2f3caa283b4184ddc025183cc1	5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863
log_6abb009e0fa4d4.93609207	2026-09-28 20:04:46	ERROR	SuperAdmin	Falla de Prueba SMTP	Error al enviar correo vía SMTP: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed [Diagnóstico: SMTP Error: Could not authenticate. SMTP server error: QUIT command failed]	Miguel González (ID: 7)	::1	5e7e31fab63291ba85a7149ec204321e87b8aefe7183f020c769814bfc0d2863	bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c
log_6abb00e29e3920.44297895	2026-09-28 20:05:54	INFO	SuperAdmin	Crear Usuario	Nuevo usuario registrado: Juan Salcedo (C.I: V-31008131)	Miguel González (ID: 7)	::1	bfe4019951599b5f3c82808567be020b337ef2e7b039403f8999419c6ac8af6c	2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c
log_6abb0445b7fc21.13482923	2026-09-28 20:20:21	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #163 eliminado del repositorio.	Miguel González (ID: 7)	::1	2d4240191cdf4997d139fb807fcceb9db9aa4e38587887daeda0c1455cd27d1c	5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd
log_6abb05e4488908.23013098	2026-09-28 20:27:16	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #159 eliminado del repositorio.	Miguel González (ID: 7)	::1	5fd523a3c2f9dbef1f88eac1ca893433a412b370af4c00fac39fd8f3184f7cbd	3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52
log_6abb05e690a664.94528393	2026-09-28 20:27:18	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #158 eliminado del repositorio.	Miguel González (ID: 7)	::1	3f73986616271bc84d541dd9d6b53fac9081a05ad63088dea2ebabecea766b52	1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c
log_6abb14f1e8b789.04226522	2026-09-28 21:31:29	INFO	RepositorioPST	Modificar Proyecto	Proyecto PST ID #128 modificado exitosamente: 'Materia: Seguridad Informáticasss'.	Miguel González (ID: 7)	::1	1d08104cce4e68e03ea0f7ba33e87fe7dccc7ffc00f3ed5265e3468d3c07241c	634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93
log_6abb14fedf6bd4.59270778	2026-09-28 21:31:42	INFO	RepositorioPST	Modificar Proyecto	Proyecto PST ID #128 modificado exitosamente: 'Materia: Seguridad Informáticasssssss'.	Miguel González (ID: 7)	::1	634b97b673c3e3ed7fcd4559b8e9ca0e9be882e1e918b160d976a8c236b70c93	18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031
log_6abb25014d0803.91919015	2026-09-28 22:40:01	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (429.43ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	18da5d8afa7da284279271ca5e2de3b72bea60b4ee95f553436d20da90b2d031	56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38
log_6abb28a869ee84.05577904	2026-09-28 22:55:36	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-28_22-55-35.sql generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	56ddc06e39d3e60e5b3e3458d0b058d8d1da4831018c18dca2f3d38433f62a38	36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769
log_6abb2e572ef199.35321358	2026-09-28 23:19:51	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL asdasdasdasdasdCONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0' (ID: #164).	Miguel González (ID: 7)	::1	36b3c082ba301b71cfbc06109248da5d301b8bea81fe68fbcc74b5451a82d769	b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db
log_6abb3252338456.46689830	2026-09-28 23:36:50	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #181 eliminado del repositorio.	Miguel González (ID: 7)	::1	b8365716eda7ded6ad0c14f8c12745b712df2228af0a84f7a7f4dc296bbc45db	31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34
log_6abb32692036f8.48741519	2026-09-28 23:37:13	INFO	RepositorioPST	Modificar Proyecto	Proyecto PST ID #179 modificado exitosamente: 'CONFIGURACION E IMPLEMENTACION DE SERVIDORES INTERNET Y DISEÑO DE PAGINA WEB PARA LA EMPRESA DE TELECOMUNICACIONES DE NARIÑO TELENARIÑO'.	Miguel González (ID: 7)	::1	31ae2bf7daf18c50af5e62ceb0626fd3cf93625219c360b420fa64587cac2a34	9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c
log_6abb36af4e8757.34762637	2026-09-28 23:55:27	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos para el2222wssas Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales' (ID: #182).	Miguel González (ID: 7)	::1	9672ec51fe00c5d5b47338dcd1316e52bef4633cf892cac735ffb004276df59c	c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299
log_6abb36c856a079.23449967	2026-09-28 23:55:52	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos parsdasdadasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales' (ID: #183).	Miguel González (ID: 7)	::1	c815718cb9c8f8da9aefc3e16ffe622a4d43246fd7c280bd9fd56703ae975299	e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9
log_6abb3c18ecb3c9.50352832	2026-09-29 00:18:32	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #183 eliminado del repositorio.	Miguel González (ID: 7)	::1	e65dbbfa927ccd7772f1b50324d809abb11293ea783176147ab1f82f55b1b0c9	83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc
log_6abb3c22cddb53.18114712	2026-09-29 00:18:42	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #182 eliminado del repositorio.	Miguel González (ID: 7)	::1	83656905f50467d5f21fbcd472bea9b4b138ef48034dbefdbeee7215d5ab33dc	0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97
log_6abb431bd4e935.20488688	2026-09-29 00:48:27	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos parasdasdasdasda el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales' (ID: #184).	Miguel González (ID: 7)	::1	0e46abbec126274f43809ea281932b55be350a6112d9e75e4a37429344569a97	75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e
log_6abb433969d1e8.66693670	2026-09-29 00:48:57	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #184 eliminado del repositorio.	Miguel González (ID: 7)	::1	75ce1e34e0aaedcf429b6896f2693e67972de1c5edd3e3c037391776d3f4fe7e	267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0
log_6abb44ac675bb9.68582634	2026-09-29 00:55:08	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico sdasdasdInvestigador del PNF en Informática apoyado en Redes Neuronales' (ID: #185).	Miguel González (ID: 7)	::1	267b97774f9b814dd108ebf82f41a462728987f6a2e4959df6b0b434bb8353d0	09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b
log_6abb44b7afb3d0.87024796	2026-09-29 00:55:19	WARNING	RepositorioPST	Eliminar Proyecto	Proyecto PST ID #185 eliminado del repositorio.	Miguel González (ID: 7)	::1	09ca0db4faacf65e12f4d2f75eb100ae29e6bda6d08575375e233028945e598b	120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26
log_6abb4882ba9ba3.07735541	2026-09-29 01:11:30	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-29_01-11-30.sql generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	120d7d4fe1581832c14b9fc29c958d6c132f4c37000d82d5830e6b20f0801e26	f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2
log_6abb4ffb730527.24406994	2026-09-29 01:43:23	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (426.37ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	f2058ac273df26e745030a1ce2a8695fcae69f6fa630d4b9cda49b230c553ea2	ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405
log_6abb50140092f9.61063580	2026-09-29 01:43:48	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'Generar Embeddings Semánticos' (tarea_1790301553_528) guardada correctamente.	Miguel González (ID: 7)	::1	ebcc36e79e70373588cbcee4429224e44fb76e7e660551b3db2aa14c8f50b405	1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06
log_6abb501c9cc977.29822340	2026-09-29 01:43:56	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (446.92ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	1a18bdc61589f66e9549de22ea123e13c98c5f16cf3ca5bab04998c1ace07a06	884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708
log_6abb502fefc296.31272536	2026-09-29 01:44:15	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (423.66ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	884f3f20fa5fcaf7a68f01db2d0644b0561fad43d906403116160d7c99d3c708	a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c
log_6abb5048360852.15348832	2026-09-29 01:44:40	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'Generar Embeddings Semánticos' (tarea_1790301553_528) guardada correctamente.	Miguel González (ID: 7)	::1	a365eb9024fe26c9c16504255c0dc7af2a2460f7b26106b4829cd64d7cfbcf6c	ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e
log_6abb505267f512.32865247	2026-09-29 01:44:50	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (542.28ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	ed1204356eb67e5f4e2f856e802b5ed4c6c9943a3ea9840fde651a113c8cd69e	3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b
log_6abb50563e6010.66502540	2026-09-29 01:44:54	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'Limpieza de Archivos Temporales & Caché': ÉXITO (55.37ms): Limpieza completada. Depurados 1 archivos temporales y cachés.	Miguel González (ID: 7)	::1	3747d1ba251fe6a955dc60588a6df3ce686547f7b9c28a955849d0e51b4e546b	a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e
log_6abb5062f16395.04386794	2026-09-29 01:45:06	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (441.05ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	a69797197f87d22278df31f17fb95d93e33df9c7230cafe4c0ca1363a9ad638e	86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761
log_6abb508a02b552.38635244	2026-09-29 01:45:46	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'Generar Embeddings Semánticos' (tarea_1790301553_528) guardada correctamente.	Miguel González (ID: 7)	::1	86dadae0b80c28d8c5ab08d8e51cd7d34a40795ef1e82e960c81f7abe0386761	3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384
log_6abb50925ea7b6.59113930	2026-09-29 01:45:54	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (404.51ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	3356d3a407fc5ccbf03ecefd5a91dccddcfb9572161e0337d6dee392c5552384	d7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170
log_6abb513542c6a4.96699430	2026-09-29 01:48:37	INFO	SuperAdmin	Guardar Tarea Programada	Tarea 'hola' (tarea_1790660917_352) guardada correctamente.	Miguel González (ID: 7)	::1	d7c0bd665c98d17513e911c092a3db5687e9c60ae6c980736bddb973b6308170	e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c
log_6abb513c3ca385.57807938	2026-09-29 01:48:44	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (440.94ms): Error de ejecución CLI (Código 3).	Miguel González (ID: 7)	::1	e9cf36e9dd77825062aab32a2ad46875d36f24cf249a0feb2053d86e73c7b98c	d6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843
log_6abb5398dfd1a1.13820354	2026-09-29 01:58:48	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ÉXITO (171.64ms): Comando ejecutado con éxito (Código 0).	Anónimo / Sistema	::1	d6b2b92024188706e966365749bf4f7108838a6eef0161892a199a980ee70843	7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657
log_6abb53992629a5.36578563	2026-09-29 01:58:49	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ÉXITO (187.27ms): Comando ejecutado con éxito (Código 0).	Anónimo / Sistema	::1	7881cd7e4146b6552e161334d1fbbdf6c417606e90e12e2764c97ee27dd7e657	2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6
log_6abbc823dee0d0.34124357	2026-09-29 10:16:03	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	2ad72eb96b618d66a275db96bb5ce05d834c253ee9f5a1963391547a98c871e6	e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15
log_6abbc8d0bc21f4.91419125	2026-09-29 10:18:56	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	e20dcffe8fb3ab97e2b65f1345702ec93c6c3d800de31eab725784e04301fc15	d137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868
log_6abbc8d41eec09.95002671	2026-09-29 10:19:00	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	d137633b21100c7211f3e9dc52b90856f1b0703d390595d463b1b92500e29868	ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d
log_6abbc9265355d9.01353952	2026-09-29 10:20:22	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	ba5e17c7ed3eb4f8a337abac7f5140aa1ae796de5f93e0885cc908c44a7edb6d	bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b
log_6abd9d7ba34596.72048568	2026-09-30 19:38:35	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	bf6cc138c0a051d2b2688c74d024b54848500f2f78867c24a6186c4b9e3a067b	0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40
log_6abda01f864d07.15614187	2026-09-30 19:49:51	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULsadasdasdadA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0' (ID: #186).	Miguel González (ID: 7)	::1	0e921a0506811fe414bf3286d183361e964999f81beee654bb8d515428625a40	f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d
log_6abda27cbe1144.87841358	2026-09-30 19:59:56	INFO	SuperAdmin	Test Respuesta Core	Diagnóstico del Core ejecutado en 1.13ms. Memoria: 2 MB. Estado: SALUDABLE	Miguel González (ID: 7)	::1	f10750877195d170156035ff8dbba52556626024223555d34cf6af4e5649a16d	938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79
log_6abda2f203f4c2.41849811	2026-09-30 20:01:54	INFO	SuperAdmin	Crear Backup	Respaldo backup_ciidi_2026-09-30_20-01-53.sql.gz generado exitosamente. Respaldos antiguos purgados: 0	Miguel González (ID: 7)	::1	938d92c7add1807ae97b4ebfac0b81f0531192c1aa992958b960a45f89c65d79	f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc
log_6abda32829d946.58940367	2026-09-30 20:02:48	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	f433908d6da6e28e10a57bdf951519a79d2fcc508a9ca66b583e14edae7775dc	8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b
log_6abda32f3a6772.91320492	2026-09-30 20:02:55	CRITICAL	WAF_Security	Inyección SQL Interceptada	IP ::1 bloqueada por SQLi en parámetro 'password': admin&#039; OR 1=1 --	Anónimo / Sistema	::1	8c324541a1ad5416c1097e9ce93595abafb1b7e3db33d61ed4b173709408095b	3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31
log_6abda65b750c05.40259276	2026-09-30 20:16:27	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	3db074fa08be3bfd62bc5d051cc3d3473c398c1cc732816cb4611bfa1710cd31	47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e
log_6abda7d5a26db4.79353606	2026-09-30 20:22:45	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	47777696004338816bf2657fa77c33d9a54a540b370e9409e9d7d7454426e98e	efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646
log_6ac12828467c24.98604997	2026-10-03 12:07:04	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	efff0eaae89f3f9ad8ed354ddd680a927b97f741671a56cd49c5096760ecb646	edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27
log_6ac12ff22e6ad2.23847671	2026-10-03 12:40:18	INFO	Autenticacion	Nombre Modificado por SuperAdmin	El superusuario actualizó su nombre a 'Miguel Gonzálezzzzz' (ID: 7).	Miguel Gonzálezzzzz (ID: 7)	::1	edd17e1871510288f30d26b5844ed373d4bd31cae3534abb880b8e47cd6eca27	a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197
log_6ac130e8c4df47.38223243	2026-10-03 12:44:24	SECURITY	Autenticacion	Test Seguridad QA	Prueba automatizada de registro criptográfico.	Anónimo / Sistema	127.0.0.1	a85ba0dd3ab1ccacd5ae3daa3b432c7cf0ddf2545c65e48e19dd29e18e675197	edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14
log_6ac130faa9ef13.77888729	2026-10-03 12:44:42	SECURITY	Autenticacion	Test Seguridad QA	Prueba automatizada de registro criptográfico.	Anónimo / Sistema	127.0.0.1	edc060158deb954b1e6e99fe82878294a912db597c00a7bd9263ccd95598be14	f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f
log_6ac131494b62b0.95522024	2026-10-03 12:46:01	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel Gonzálezzzzz' (ID: 7) cerró su sesión voluntariamente.	Miguel Gonzálezzzzz (ID: 7)	::1	f8ec00caf82b79afb6e670730b18e5a26c59ca4c720615ea950e1e49d3cb930f	80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5
log_6ac13877298687.66874114	2026-10-03 13:16:39	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel Gonzálezzzzz' (C.I: V-32621284, Rol: Super Administrador).	Miguel Gonzálezzzzz (ID: 7)	::1	80b07da648fb55be759c9a831215eca6ba1e37e9ab41a3cfc850a1edb263a8f5	1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf
log_6ac13b093afc00.43901612	2026-10-03 13:27:37	INFO	Autenticacion	Nombre Modificado por SuperAdmin	El superusuario actualizó su nombre a 'Miguel González' (ID: 7).	Miguel González (ID: 7)	::1	1106bb48c7aa1fda3637120e7b7033df33d007656a6d5c3b1854ce09f26ff2bf	971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806
log_6ac13b4f07fca9.12965797	2026-10-03 13:28:47	INFO	Autenticacion	Correo Modificado	El usuario ID #7 actualizó su correo a 'erwazaaa@gmail.com'. Se envió enlace de verificación.	Miguel González (ID: 7)	::1	971473416dd92d6b1218124bfdff1002e4979709444e04e5f23b5917b233b806	5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171
log_6ac13b60b8a348.04621923	2026-10-03 13:29:04	INFO	Autenticacion	Correo Modificado	El usuario ID #7 actualizó su correo a 'erwesitodepollo@gmail.com'. Se envió enlace de verificación.	Miguel González (ID: 7)	::1	5e43eae56a7eb996936271677ef26cf70d4dddcf03321c38717768f63c5d9171	1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c
log_6ac13b66d6a3b3.39434338	2026-10-03 13:29:10	INFO	Autenticacion	Verificación de Correo Solicitada	Enlace de verificación enviado a 'erwesitodepollo@gmail.com' para usuario ID #7.	Miguel González (ID: 7)	::1	1f0a3831019ccaa7e1768ccb172e8c0a8eccb51f8c224fda70c339b19787591c	d3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86
log_6ac13e5bc96068.22485896	2026-10-03 13:41:47	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	d3e8e61cdc7ed3368c286665a0c8c12cfb1f043628c3c295def4a93beb8bfd86	f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf
log_6ac13e7ee99724.88249222	2026-10-03 13:42:22	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	f704369bb897d951980def2cce3f16071cf384f9fd1bf942e0499784fdee55bf	9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6
log_6ac13e8f294e28.07445518	2026-10-03 13:42:39	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	9a726c985aa96ef36890ee2337389fde6cd177fc8529c430c375fdba242e76a6	e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e
log_6ac13eb4a15e31.77988144	2026-10-03 13:43:16	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	e64cb0f4bb598e3241b2e47a64f9cdff9be135c76873f9bcd428c6f935848b5e	9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb
log_6ac13f64de6a85.11671560	2026-10-03 13:46:12	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'no soy miguel' (C.I: V-1234567).	Anónimo / Sistema	::1	9d2cb788fdb2f0e33d31576f219024a72e545e40fb1dd73858838c251a9a6fbb	0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846
log_6ac13fc2b079e8.57780152	2026-10-03 13:47:46	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	0ac0891790b300a322d0130cd3d0665818a37aa4b17c1206e84c4c9a111ac846	178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8
log_6ac13fcf644a83.29317983	2026-10-03 13:47:59	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	178c45b96a140288f62d71a55703007d10e06b1cbb4590179c79589b33ff45c8	e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c
log_6ac1400c7d21c2.36177170	2026-10-03 13:49:00	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	e8bf6c24dcef492672746f237babdb72a0cc17a3e5f2ad689aff1218e355512c	21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b
log_6ac140353a1e02.77789108	2026-10-03 13:49:41	WARNING	Autenticacion	Login Fallido	Intento de inicio de sesión con cédula no registrada: 'V-12345678'.	Anónimo / Sistema	::1	21954097c4d1be1baa8bc37260d41820d40b1b14c256b57e6be6c2c98dd3841b	2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18
log_6ac1404be5aea6.42915402	2026-10-03 13:50:03	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	2ce58546c6e534e7f65e60033a1062ffccdfc51508e62db7fabc9c5f05574a18	88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825
log_6ac140694bbc43.57753093	2026-10-03 13:50:33	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	88fd9cee2a3b6cee15c71633e7255fb1e9f749165bf8b1207097963c0c7ad825	45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f
log_6ac140838b6c09.31008577	2026-10-03 13:50:59	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	45d319ee6c0208e219acc7368e6a59ef265ef7b8e296c04075d35708f0d3d48f	b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f
log_6ac140c6df7b33.58916003	2026-10-03 13:52:06	WARNING	Autenticacion	Login Fallido	Intento de inicio de sesión con cédula no registrada: 'V-12345667'.	Anónimo / Sistema	::1	b64d1269e34ea0db4543dabab9714b83382eb11f1b232f30231e05177a80260f	d671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616
log_6ac140dfba3a66.37695673	2026-10-03 13:52:31	WARNING	Autenticacion	Login Fallido	Intento de inicio de sesión con cédula no registrada: 'V-12345567'.	Anónimo / Sistema	::1	d671829d292704fa60fc8544c420c79bb5ea306bbb0a360d9d1474591e2b0616	425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879
log_6ac1410c318ee8.12576183	2026-10-03 13:53:16	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	425b7064f6c8d97408badc8c3112cf0655cb54563c2c5f32ff9f5f541af78879	06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd
log_6ac141235905d9.76423907	2026-10-03 13:53:39	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	06ddda70b599a980f8b4fdd2526fdec2fff8b0634f55dcded7a9bc680aaf0fdd	2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d
log_6ac141b66375e1.41482376	2026-10-03 13:56:06	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	2915d31a89b4980dd668180e7b5535e1253b7b9c60caf6f2bd3b39de6319732d	05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4
log_6ac141be53d3f9.97944045	2026-10-03 13:56:14	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	05ee6ba847cc6c66940c7a4c30db8e771b3e21298acc4a3645bcf389813324a4	5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c
log_6ac141c612a8e7.73657758	2026-10-03 13:56:22	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	5c87dd958af494d8e49198f1c6962dcae232bb2b33fe479bfc2be737ca15c96c	9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88
log_6ac14283b15563.91767580	2026-10-03 13:59:31	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	9eb5ec4292371f625c4fafdb269ff5ee40341480183d55d704196a67a1d28a88	08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6
log_6ac1428d24f946.56878883	2026-10-03 13:59:41	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	08488e8d77111cce7d7e77d79a5c85e94b2980ec3c442c5d91ecc6d7247d46e6	26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5
log_6ac142965eccd4.57785406	2026-10-03 13:59:50	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	26052b9f0a427ccdc83b9b209201ca182f360b393a7b5ac7e2a42b0da39023a5	070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b
log_6ac1429c97d6e1.59083773	2026-10-03 13:59:56	INFO	SuperAdmin	Test Respuesta Core	Diagnóstico del Core ejecutado en 1.64ms. Memoria: 2 MB. Estado: SALUDABLE	Miguel González (ID: 7)	::1	070d4aad6e4acc2b99a4f113d4fd943a11d1efcce282265548a2dd0674722d6b	ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177
log_6ac142f633ae61.82043725	2026-10-03 14:01:26	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	ae8fe439a6967c852d5c4dfc38ae7bd1f4daf0cc22982ba9156b7b08088dd177	8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034
log_6ac14310748685.52547574	2026-10-03 14:01:52	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	8baa96cd979c16422a45e56250438a1264fdc7df65e6535ff18c45c0ff5b3034	dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9
log_6ac14351beb5d7.13362571	2026-10-03 14:02:57	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	dc0e15d95a619461489b5011cb959d2f71317f90d016a7f0fcb35a436c5f59e9	223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428
log_6ac143855b9691.65506208	2026-10-03 14:03:49	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	223b4207baab4fa533464869637b40a6ccfe0d5c8dc6551cf56dbd67d138a428	98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100
log_6ac144082ef9e1.16827141	2026-10-03 14:06:00	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	98c0243b8919aade73f84dd6114e296c5a89242eb70f91c8cab07f07ef21e100	d2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569
log_6ac144e48b8447.33270683	2026-10-03 14:09:40	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	d2167bf0405e407f2ab4cbb726fbec43a28fdd2103801dd12a6e3d7a4ac5a569	a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da
log_6ac1450cb24579.82197712	2026-10-03 14:10:20	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	a91975ad95b8f7244cecff5d707332f4a77c34600c9675de93b4d41afa3f54da	81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc
log_6ac14681285926.78836938	2026-10-03 14:16:33	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	81dd861687a81ea027b0cb5aa0cbd0706b00923bb7ff84a54bc79d9d20743bcc	a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0
log_6ac146b0ae9965.21887296	2026-10-03 14:17:20	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	a50e4258b98aad4192e1373d22d3bd7b2503370c440eeab52af0ef242aaedca0	f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b
log_6ac146cc15a824.74334312	2026-10-03 14:17:48	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	f3ce6c85bcdef9e8a2abb52e7d08d5e2a8aa92f684567a40ca066389c546845b	743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53
log_6ac1492e3bc309.07890168	2026-10-03 14:27:58	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	743d39561842c31beda2cb89af0265e2606255b7845a357de1aae58968b8fd53	43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd
log_6ac1494129f614.76362695	2026-10-03 14:28:17	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Miguel González' (C.I: V-32621284).	Anónimo / Sistema	::1	43d9e2aff5bf79925685d30111c367aa93ed5b1324f0c64d534248f186daf4dd	e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b
log_6ac14c4986d566.91856480	2026-10-03 14:41:13	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	e7bcb4877f35a9d28d7a22722bbbcc7a6bdc6fbbac37f448a667480ae96b252b	ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5
log_6ac290242a51b7.18332813	2026-10-04 13:43:00	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	ae3228180f235b94592f0161fe27112c7cec427aa10cd39121614dafd7e724e5	03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824
log_6ac2911ef16e10.78511822	2026-10-04 13:47:10	INFO	RepositorioPST	Actualizar Configuración	Se actualizaron los parámetros operativos del repositorio PST (límites de archivos, paginación, citas y filtros).	Miguel González (ID: 7)	::1	03643a1c639caad9c33be13262c201e9c0cdcf734609a065d9af4be886764824	f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc
log_6ac29731984260.15880698	2026-10-04 14:13:05	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Cursos cambiado a estado: offline	Miguel González (ID: 7)	::1	f37dff78ac16a97bef658a5f51addc2390769257cb9e9cbcbee643945264e8dc	0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988
log_6ac297340360a6.85677947	2026-10-04 14:13:08	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Investigaciones cambiado a estado: offline	Miguel González (ID: 7)	::1	0a0e6f1e32767a7d87036ecf8580409078411c882a7b8c1d149a330a5d410988	aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11
log_6ac29734eeea69.29357751	2026-10-04 14:13:08	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo LineasInvestigacion cambiado a estado: offline	Miguel González (ID: 7)	::1	aaf1d797278c99556eaaabb42d9bba73b39d47bacea468b482045a3283570a11	26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b
log_6ac297391c07f7.58886272	2026-10-04 14:13:13	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo VinculacionEmpresarial cambiado a estado: offline	Miguel González (ID: 7)	::1	26d3b0967555438a01f0f062b1b0794abac5124c1872e52e37a84ff2ae46f15b	ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00
log_6ac29986ccd537.36845720	2026-10-04 14:23:02	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Isadasdasdnvestigador del PNF en Informática apoyado en Redes Neuronales' (ID: #189).	Miguel González (ID: 7)	::1	ba643446bbe4c41bea9e4a2647725dbb473edf8f99935f2814bda222f9d54d00	dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029
log_6ac2b113ef3042.01181958	2026-10-04 16:03:31	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	dab79076d394b201f536cda5b59ee9b3b363430cd93b96550f6aea151824f029	9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd
log_6ac2b118ce98f2.42047090	2026-10-04 16:03:36	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	9496218a32a4a0b263b92a1a6aec47922bb682d0af33b46ccb5ccee81b4f96dd	3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783
log_6ac2ba6db1d628.29952574	2026-10-04 16:43:25	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos para el Comité Cien2222tífico Investigador del PNF en Informática apoyado en Redes Neuronales' (ID: #191).	Miguel González (ID: 7)	::1	3795a3337016be06159ef2c88700214f7ebb72f86197f74fe37728f29cd65783	3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59
log_6ac2bc6db08428.55733278	2026-10-04 16:51:57	INFO	RepositorioPST	Editar Nivel Académico	Se actualizó el nivel académico ID 2.	Miguel González (ID: 7)	::1	3d8b5474f1fb61079a3f46d09b385bc922ebe40b9dfa7505101e808a899e0a59	ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0
log_6ac2bcccab46d8.98270583	2026-10-04 16:53:32	INFO	RepositorioPST	Editar Nivel Académico	Se actualizó el nivel académico ID 2.	Miguel González (ID: 7)	::1	ecd4c736b36f877e6e53fbcc9636a1be9d7fd52fea0266a8e614f2fab88d53e0	f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804
log_6ac2c19ac0c805.51009240	2026-10-04 17:14:02	INFO	RepositorioPST	Registrar Proyecto	Proyecto PST registrado exitosamente: 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del P32323232NF en Informática apoyado en Redes Neuronales' (ID: #192).	Miguel González (ID: 7)	::1	f0df1978205c0e3be5065a24fc9893100f220034700c1b664e05347884305804	9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421
log_6ac2e9775836d0.04371946	2026-10-04 20:04:07	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	9130a1f630d54ad896f1cb6db74c8fdd7f633d2dacb86d31b8688ce38003b421	f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3
log_6ac2ea533d0ee2.65428414	2026-10-04 20:07:47	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	f2240b49535de448ce2d0ddc87389162d97c26a370d982cae91c1058782155b3	a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3
log_6ac2ea95d8edd8.61477690	2026-10-04 20:08:53	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	a11472988c3c54881059cd67096c54aa3245954de3294652d842b7a5f2e563e3	5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5
log_6ac2eade037e09.34485946	2026-10-04 20:10:06	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Cursos cambiado a estado: online	Miguel González (ID: 7)	::1	5a3927efec7374bbc369174d1dcb7f62ad1fb1c387dd6f1dc9e7e3aa087dcbd5	cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4
log_6ac2eadecdaf28.99002115	2026-10-04 20:10:06	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo LineasInvestigacion cambiado a estado: online	Miguel González (ID: 7)	::1	cf753724a9dd08c79e200483c0f3f13922c0c84301f2e258b33eaaf1453b3ac4	94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae
log_6ac2eadfbca4d7.74874338	2026-10-04 20:10:07	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Investigaciones cambiado a estado: online	Miguel González (ID: 7)	::1	94a5745b202c0f0333b4da52e624ed8c6bf504828b5d6909fa17640ab6eed0ae	0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c
log_6ac2eae0a654b1.94476951	2026-10-04 20:10:08	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo VinculacionEmpresarial cambiado a estado: online	Miguel González (ID: 7)	::1	0ebe5ab4ca10d22862aa7c9ec7694866a1c5f18e2c7d68045532c4b0d0bb165c	9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5
log_6ac2ec5601cdb9.62461585	2026-10-04 20:16:22	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	9da52a0251efd7bb07c24cdd4da13dcba63d0c3d0b20c4fb4f9f7a9d701fb0e5	2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e
log_6ac2ec5d8bb483.27381483	2026-10-04 20:16:29	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	2f05425b7a9e41d4a2bf398c9c515bc76bfd453a9bf32401d4aa5aaa3ec04d9e	fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05
log_6ac2ec687a6a52.51050372	2026-10-04 20:16:40	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Cursos cambiado a estado: offline	Miguel González (ID: 7)	::1	fc72247ad66020f08a8d2c52aa9efede5ed58fa362028152c404b26c49ad5c05	855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034
log_6ac2ec69a91fc5.76197883	2026-10-04 20:16:41	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo Investigaciones cambiado a estado: offline	Miguel González (ID: 7)	::1	855948127cbaba289450e5e763e1ff5c28f24e75120dd79ef760854988494034	371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9
log_6ac2ec6a953041.71829326	2026-10-04 20:16:42	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo LineasInvestigacion cambiado a estado: offline	Miguel González (ID: 7)	::1	371991b3f8bc2b0c3c2203924ae467d29338c4f934c30373591716552a4cdfb9	916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768
log_6ac2ec6d032786.51154961	2026-10-04 20:16:45	WARNING	SuperAdmin	Alternar Estado Módulo	Módulo VinculacionEmpresarial cambiado a estado: offline	Miguel González (ID: 7)	::1	916178f3021cd79d72b35126d7658a599465884928b856cf10697c6d7e62e768	e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1
log_6ac3ec262fd721.24836059	2026-10-05 14:27:50	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	e2e5964dbd71bda9544c525c416f0bfd9d117fb372b5182418a6b241c5f2c4d1	1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665
log_6ac47fd858dde1.61628395	2026-10-06 00:58:00	WARNING	SuperAdmin	Restaurar BD	Base de datos restaurada exitosamente.	Miguel González (ID: 7)	::1	1981741c5823b2a930ff831d43ff72ee0dd802037b433b57e897a53fab67a665	f8d574c8ebdfe56afe91f2012b61107f5c85ccd1a7b6e7e84c58b50e401e8f45
log_6ac4ccfc586e69.55520142	2026-10-06 06:27:08	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	f8d574c8ebdfe56afe91f2012b61107f5c85ccd1a7b6e7e84c58b50e401e8f45	69ef85bc6736bc58ed989c20ec8c7d7740f330b1427f742fc8bab6aa3133ab31
log_6ac50537dab567.63764479	2026-10-06 10:27:03	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	69ef85bc6736bc58ed989c20ec8c7d7740f330b1427f742fc8bab6aa3133ab31	a3ba388a639a3a4d3a3168853365400bb3fe2571fa2f27b3b63eeced3de40da7
log_6ac5094c48bd38.45910070	2026-10-06 10:44:28	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	a3ba388a639a3a4d3a3168853365400bb3fe2571fa2f27b3b63eeced3de40da7	40e35643d9bc47ae1b27e750f40140c34a2f875bc4beb5ee276bf53873f513ef
log_6ac509bd352b59.26988890	2026-10-06 10:46:21	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	40e35643d9bc47ae1b27e750f40140c34a2f875bc4beb5ee276bf53873f513ef	b50d3420cd706810a5ffa0c5e52b7f5afd65fc1498478d9fa750a8196c55bdf2
log_6ac50a10c2ddc5.96239309	2026-10-06 10:47:44	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	b50d3420cd706810a5ffa0c5e52b7f5afd65fc1498478d9fa750a8196c55bdf2	e437a53f853b58fc0a82a2f78f43f5b445048947ae761c324ed321fb7d839cd9
log_6ac50a25d84f77.19343003	2026-10-06 10:48:05	WARNING	Autenticacion	Login Fallido	Intento de inicio de sesión con cédula no registrada: 'V-32618284'.	Anónimo / Sistema	::1	e437a53f853b58fc0a82a2f78f43f5b445048947ae761c324ed321fb7d839cd9	282eb02ffbefef8a861c5a3f738a4ab54be14e0f92c1ad06c41231f8c1a5cb31
log_6ac50a467a0a26.97256185	2026-10-06 10:48:38	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	282eb02ffbefef8a861c5a3f738a4ab54be14e0f92c1ad06c41231f8c1a5cb31	7f5365843f9e0e6cc79ec08f79c4980e3fa52037dfb77b6426b82e10f7f81140
log_6aca62cd127774.53080807	2026-10-10 12:07:41	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	7f5365843f9e0e6cc79ec08f79c4980e3fa52037dfb77b6426b82e10f7f81140	c8c79245ffa603002053680ee8d111bb64dc88c26f4148e97a89e7145477d94d
log_6aca640999da45.50182270	2026-10-10 12:12:57	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	c8c79245ffa603002053680ee8d111bb64dc88c26f4148e97a89e7145477d94d	fd55a5db8390dbb3f1fda42a576228474164a2d683da4d33cdeeb34f9a007d67
log_6acba9e0eaf502.42611046	2026-10-11 11:23:12	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	fd55a5db8390dbb3f1fda42a576228474164a2d683da4d33cdeeb34f9a007d67	bc2b1d34130a49f2cbc9dfc46031a47d0b8f8d57890dee64e538cfdaa670ffa7
log_6acbc6c5f27549.15441998	2026-10-11 13:26:29	WARNING	SeguridadLineas	atender-alerta	Alerta #2 marcada como atendida.	Miguel González (ID: 7)	::1	bc2b1d34130a49f2cbc9dfc46031a47d0b8f8d57890dee64e538cfdaa670ffa7	3786f4d29153ed550fdcb592c073b509046ef20ec3742b0a540ef6a650024d44
log_6acbc6c84ef1d2.92504236	2026-10-11 13:26:32	WARNING	SeguridadLineas	atender-alerta	Alerta #1 marcada como atendida.	Miguel González (ID: 7)	::1	3786f4d29153ed550fdcb592c073b509046ef20ec3742b0a540ef6a650024d44	39d98c302c473a08d8ac506a79d183d7112ecf23a365cc7bacf69a9f55259e52
log_6acbc6e4145912.85636581	2026-10-11 13:27:00	WARNING	SeguridadLineas	atender-alerta	Alerta #3 marcada como atendida.	Miguel González (ID: 7)	::1	39d98c302c473a08d8ac506a79d183d7112ecf23a365cc7bacf69a9f55259e52	20836b658cb2e59e19c32e9fe4b27b43020e8f2fc26326e4b443f52255980b92
log_6acd04647a4777.40795585	2026-10-12 12:01:40	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	20836b658cb2e59e19c32e9fe4b27b43020e8f2fc26326e4b443f52255980b92	c314a3c91e6f8925f70a000a0fcf085a6fb2b6769c6b6ea18e75a3fb35dd04e3
log_6acd061f51ae38.41528318	2026-10-12 12:09:03	WARNING	SeguridadLineas	exportar-bitacora	Exportación CSV de la bitácora. Filtros: []	Miguel González (ID: 7)	::1	c314a3c91e6f8925f70a000a0fcf085a6fb2b6769c6b6ea18e75a3fb35dd04e3	2458747b957ad1f37b712db3419cd250cc17e1221e10cb13c392688e0eddfda4
log_6acd4f4d2c8072.56968398	2026-10-12 17:21:17	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	2458747b957ad1f37b712db3419cd250cc17e1221e10cb13c392688e0eddfda4	115f8632ee3404363af23387add40bad5b0e85d7260f377b9e3b63579cd6a65b
log_6acd55df2656f3.11428280	2026-10-12 17:49:19	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	115f8632ee3404363af23387add40bad5b0e85d7260f377b9e3b63579cd6a65b	2bac4fb921860fcb07df7c489d9a8c4313e80869d1c45d67778e89c83a8398b0
log_6acd563863a6d2.52823876	2026-10-12 17:50:48	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	2bac4fb921860fcb07df7c489d9a8c4313e80869d1c45d67778e89c83a8398b0	d4399d360080ca24c0eb8726244c9486f9b3666193f98fb0ec679d52a0988e08
log_6acd901a0948b1.26716663	2026-10-12 21:57:46	WARNING	SeguridadLineas	guardar-politicas	Políticas actualizadas: refresco_feed_seg=100	Miguel González (ID: 7)	::1	d4399d360080ca24c0eb8726244c9486f9b3666193f98fb0ec679d52a0988e08	3867a50364254d3df85a4a6d8513d97d0c9abf6e9eb82c18eaa98216670510a5
log_6acd93e6414ae0.09269483	2026-10-12 22:13:58	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	3867a50364254d3df85a4a6d8513d97d0c9abf6e9eb82c18eaa98216670510a5	b9572d5249aadd29722e03bbb0533890a12dde356995cdcc5e3e3b2deaa8c606
log_6acd94b6670c16.25945556	2026-10-12 22:17:26	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	b9572d5249aadd29722e03bbb0533890a12dde356995cdcc5e3e3b2deaa8c606	8c37125a794fdc26f2f345741057877828c600fb27ec569766a770430a23ab6a
log_6acfa4f853f4d6.74721852	2026-10-14 11:51:20	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	8c37125a794fdc26f2f345741057877828c600fb27ec569766a770430a23ab6a	f5063e4b700ac7669fffc10db8be48ce3be8f9ab13c2947be258d7363d68527b
log_6acfa9237de747.48044546	2026-10-14 12:09:07	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	f5063e4b700ac7669fffc10db8be48ce3be8f9ab13c2947be258d7363d68527b	1ee735464aedf6a1d5b482b80b05b31ace6c5de33666f3776bc3ad7d323993dd
log_6acfa94c151678.75802068	2026-10-14 12:09:48	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	1ee735464aedf6a1d5b482b80b05b31ace6c5de33666f3776bc3ad7d323993dd	ca96d674234c3e60f62aed4b5556769007085dace28bdb01aa4b8ae54df0d14d
log_6acfa98d55d231.33484826	2026-10-14 12:10:53	INFO	SuperAdmin	Crear Usuario	Nuevo usuario registrado: MAMA DE LANDO (C.I: V-12045552)	Miguel González (ID: 7)	::1	ca96d674234c3e60f62aed4b5556769007085dace28bdb01aa4b8ae54df0d14d	ab13ee58110a293e738b68fa79d163152ed28a7f42dca495a414ef2c31080e34
log_6acfa99455dc68.06633289	2026-10-14 12:11:00	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	ab13ee58110a293e738b68fa79d163152ed28a7f42dca495a414ef2c31080e34	9057edfbc04ea348fb678f0c206a20381cc8b0ae18339e5a5ff807266953fc4f
log_6acfa9a86b7392.31279889	2026-10-14 12:11:20	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'MAMA DE LANDO' (C.I: V-12045552).	Anónimo / Sistema	::1	9057edfbc04ea348fb678f0c206a20381cc8b0ae18339e5a5ff807266953fc4f	536aace84104cc48e5ce35698cba102151bd44487a0021a94a68f23f0869797f
log_6acfa9baa02b01.07090328	2026-10-14 12:11:38	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'MAMA DE LANDO' (C.I: V-12045552).	Anónimo / Sistema	::1	536aace84104cc48e5ce35698cba102151bd44487a0021a94a68f23f0869797f	e0dab3a57468e0f3151002377ed8e072a570ff042e0ec358397a3f8d8dcff161
log_6acfa9cc10edb2.57045688	2026-10-14 12:11:56	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	e0dab3a57468e0f3151002377ed8e072a570ff042e0ec358397a3f8d8dcff161	5b95ee67b19e09eeb9d51566aac8adddd45ce2bcc7f3c6fbfb3e07324f71b730
log_6acfaa15dd4b21.04292182	2026-10-14 12:13:09	INFO	SuperAdmin	Crear Usuario	Nuevo usuario registrado: LANDO2 (C.I: V-120455520)	Miguel González (ID: 7)	::1	5b95ee67b19e09eeb9d51566aac8adddd45ce2bcc7f3c6fbfb3e07324f71b730	042dd546e187d03b6f8b7e402e380e38d1b96a99dfe4a92008c153eefad00381
log_6acfaa2ac04130.94016626	2026-10-14 12:13:30	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	042dd546e187d03b6f8b7e402e380e38d1b96a99dfe4a92008c153eefad00381	00da4850f0b9e9aea00d891491211ba3d0edadafc81ba35fc0cbe68c48deada3
log_6acfaa3f7256f7.89874603	2026-10-14 12:13:51	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'LANDO2' (C.I: V-120455520).	Anónimo / Sistema	::1	00da4850f0b9e9aea00d891491211ba3d0edadafc81ba35fc0cbe68c48deada3	3b08f806b02456534671505217108e26d0b7625d3805b26a1f42e3a2e7fce64a
log_6acfab2c7a57c4.74112267	2026-10-14 12:17:48	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	3b08f806b02456534671505217108e26d0b7625d3805b26a1f42e3a2e7fce64a	707a4cf11ca942bf86f0ef7136aa12c3840c59ea007abc575b78cc35c3a478ac
log_6acfab388a65b5.98736674	2026-10-14 12:18:00	WARNING	SuperAdmin	Eliminar/Archivar Usuario	Usuario ID #23 archivado exitosamente. Credenciales liberadas.	Miguel González (ID: 7)	::1	707a4cf11ca942bf86f0ef7136aa12c3840c59ea007abc575b78cc35c3a478ac	a90e195467f9885209d3c68e4713155d1991294603c6fcf0879abefc2d79de79
log_6acfab40cac430.09088744	2026-10-14 12:18:08	WARNING	SuperAdmin	Eliminar/Archivar Usuario	Usuario ID #22 archivado exitosamente. Credenciales liberadas.	Miguel González (ID: 7)	::1	a90e195467f9885209d3c68e4713155d1991294603c6fcf0879abefc2d79de79	2f00bf4d289247f89fb2c05a687a9a3370f2b75cfbfc3f2bc125379061f3cbe3
log_6acfab74955b78.60561987	2026-10-14 12:19:00	INFO	SuperAdmin	Crear Usuario	Nuevo usuario registrado: landorus (C.I: V-12045552)	Miguel González (ID: 7)	::1	2f00bf4d289247f89fb2c05a687a9a3370f2b75cfbfc3f2bc125379061f3cbe3	28941694eefd4153cc2d0da073f8ad96ba7487f656dc30ff2808a3a5262700f6
log_6acfabd7de7de8.47035119	2026-10-14 12:20:39	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	28941694eefd4153cc2d0da073f8ad96ba7487f656dc30ff2808a3a5262700f6	3381ac9769b472ba2260d56deebc113ae23b399b22bd9b22d709b868068909af
log_6acfabee111647.51791932	2026-10-14 12:21:02	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'landorus' (C.I: V-12045552, Rol: Docentes).	landorus (ID: 24)	::1	3381ac9769b472ba2260d56deebc113ae23b399b22bd9b22d709b868068909af	694ca3a34b368feb067f5b34da2d674518b538e6b3730fe33c1f2a6704e5b756
log_6acfac77bb95f3.00003475	2026-10-14 12:23:19	INFO	Autenticacion	Cierre de Sesión	El usuario 'landorus' (ID: 24) cerró su sesión voluntariamente.	landorus (ID: 24)	::1	694ca3a34b368feb067f5b34da2d674518b538e6b3730fe33c1f2a6704e5b756	99330c5758175d492efb6443ee2d85f5ee92622e490c9b858686c4fc90b49db3
log_6acfacac25bfb8.85040765	2026-10-14 12:24:12	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	99330c5758175d492efb6443ee2d85f5ee92622e490c9b858686c4fc90b49db3	a36c763f15787657d6699dfa3f55dad7f1f7ee14bda91bcb2af885bfef82d2f2
log_6ad3ad25abc783.90798226	2026-10-17 13:15:17	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	a36c763f15787657d6699dfa3f55dad7f1f7ee14bda91bcb2af885bfef82d2f2	78d87146cd095ea11a371d1e1af3286df76e66b0331b13a85261adc27cd31e8a
log_6ad3ad7e068b41.03762366	2026-10-17 13:16:46	CRITICAL	SuperAdmin	Error Panel Admin	SQLSTATE[HY000]: General error: 7 no connection to the server	Miguel González (ID: 7)	::1	78d87146cd095ea11a371d1e1af3286df76e66b0331b13a85261adc27cd31e8a	6779b408049d4902381e2e65f06f39e2e4c8b1740c5fa549c9bda1de8a9b1ab8
log_6ad3b5a99d8598.51468420	2026-10-17 13:51:37	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	6779b408049d4902381e2e65f06f39e2e4c8b1740c5fa549c9bda1de8a9b1ab8	56002d6e918b5bfeacf42c0f2f8bb726e02944d7c51401f4d5fbbe3c3a6bb03c
log_6ad3b9e4ea7c35.12874231	2026-10-17 14:09:40	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	56002d6e918b5bfeacf42c0f2f8bb726e02944d7c51401f4d5fbbe3c3a6bb03c	96df4d5eb711b49d1e9544633799888ada37dc19ba96d952beb3211b68d2a137
log_6ad3bc368e7f64.16557484	2026-10-17 14:19:34	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	96df4d5eb711b49d1e9544633799888ada37dc19ba96d952beb3211b68d2a137	5bd19b0fea3d9bd863f3397473fdfe2375d349ffea58ea6c35c0247e58e4a561
log_6ad3bc42a72d68.51842138	2026-10-17 14:19:46	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'Juan Salcedo' (C.I: V-31008131).	Anónimo / Sistema	::1	5bd19b0fea3d9bd863f3397473fdfe2375d349ffea58ea6c35c0247e58e4a561	00c3bb950f42a42ba88023e3d653bc974c2c6ad4e7706a3818925a57fe2c72bb
log_6ad3bc510e36b3.57775073	2026-10-17 14:20:01	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	00c3bb950f42a42ba88023e3d653bc974c2c6ad4e7706a3818925a57fe2c72bb	edbbf5d40bc89d256221c5f3ebd0cddfa44bb072b10fb1e90fb98e9a9ccef60b
log_6ad3bcc9245070.52051139	2026-10-17 14:22:01	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	edbbf5d40bc89d256221c5f3ebd0cddfa44bb072b10fb1e90fb98e9a9ccef60b	762b22ed348147c0991967fa195a414ec17cceebbc4ca6766324457bc12680dd
log_6ad3bcd27cd5f7.43682811	2026-10-17 14:22:10	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	762b22ed348147c0991967fa195a414ec17cceebbc4ca6766324457bc12680dd	6439628afe5b3647f549f50289dc6f6b1d94f58a1486b63f95d010750a18b873
log_6ad72dbfa83548.42242755	2026-10-20 05:00:47	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (1.1ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	127.0.0.1	6439628afe5b3647f549f50289dc6f6b1d94f58a1486b63f95d010750a18b873	370daa10e440ecbc1c8a98c2fd1103115d8f43556c4f6867eeebf5ed984d13ad
log_6ad72dbfac2cf9.01453107	2026-10-20 05:00:47	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.13ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	127.0.0.1	370daa10e440ecbc1c8a98c2fd1103115d8f43556c4f6867eeebf5ed984d13ad	f220eb1b625ed0e4f676ba4bb0a5c630e43d83d4d562345cb440bd0e7676822a
log_6ad72f5cc9d762.23567832	2026-10-20 05:07:40	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.14ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	f220eb1b625ed0e4f676ba4bb0a5c630e43d83d4d562345cb440bd0e7676822a	96d1408f7a46685127016cb83c2600e0c9872c21f891a33317147423a3f3e044
log_6ad72f5ccc80e2.74005239	2026-10-20 05:07:40	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.14ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	96d1408f7a46685127016cb83c2600e0c9872c21f891a33317147423a3f3e044	1387433f23b8a779bc292124207148becdbc6bb3e5ca45155e6c50ea32ed36dc
log_6ad72f6d3a7cd2.02475106	2026-10-20 05:07:57	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	1387433f23b8a779bc292124207148becdbc6bb3e5ca45155e6c50ea32ed36dc	a34a097d7e0244af03dfa731feedf351a8c7b17405da7063441c2d24aa864967
log_6ad73018d01741.85577645	2026-10-20 05:10:48	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.14ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	a34a097d7e0244af03dfa731feedf351a8c7b17405da7063441c2d24aa864967	e3a4c0dd7f057199b621b858841238c00b1fc43f9c7b17b71e901795998b2ad3
log_6ad7a82ddcd709.49773068	2026-10-20 13:43:09	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (2.49ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	e3a4c0dd7f057199b621b858841238c00b1fc43f9c7b17b71e901795998b2ad3	9273018adeb28e35262eeab9f8758ebaa786bedd11b87abdc15220c92a901748
log_6ad7a82de79de1.71413068	2026-10-20 13:43:09	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.46ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	9273018adeb28e35262eeab9f8758ebaa786bedd11b87abdc15220c92a901748	56294a02fac23230f64ce91b07c39ba2cabd25c4f65ebf8555f448bebdfdcfaa
log_6ad7a92a8ef226.24222746	2026-10-20 13:47:22	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (1.06ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	56294a02fac23230f64ce91b07c39ba2cabd25c4f65ebf8555f448bebdfdcfaa	c6a937da2cd655600fdbf92bc1c785e7bcfe560d87e905fdf8e5e6213f704a96
log_6ad7a92acba855.67812542	2026-10-20 13:47:22	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	c6a937da2cd655600fdbf92bc1c785e7bcfe560d87e905fdf8e5e6213f704a96	2afacd8a667e5069371a1f62b1f1b5dfe64f206908a9180df7ebb4220704cc25
log_6ad7aa73611627.60645364	2026-10-20 13:52:51	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (1.24ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	2afacd8a667e5069371a1f62b1f1b5dfe64f206908a9180df7ebb4220704cc25	00162822ee80194e28bbe3a0ed1ca6934f70fb0d34ae90a4401dbeae4c0616cb
log_6ad7aa9e595dc2.87060036	2026-10-20 13:53:34	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. V-31008131 (Juan Salcedo). Sesión remota revocada.	Miguel González (ID: 7)	::1	00162822ee80194e28bbe3a0ed1ca6934f70fb0d34ae90a4401dbeae4c0616cb	febe29e903655be34b822088d525a50ae8a46673c081e203dadf29bb62ded1fb
log_6ad7ab44d841a8.87516808	2026-10-20 13:56:20	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.47ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	febe29e903655be34b822088d525a50ae8a46673c081e203dadf29bb62ded1fb	24a709b9610c415d9eadc35e21a33502375876505dc9cc15ad95e3799d06729f
log_6ad7ab45b87cb8.07279364	2026-10-20 13:56:21	INFO	SuperAdmin	Editar Usuario	Datos actualizados para el Usuario C.I. V-31008131 (Juan Salcedo). Se forzó cambio de contraseña. Sesión remota revocada.	Miguel González (ID: 7)	::1	24a709b9610c415d9eadc35e21a33502375876505dc9cc15ad95e3799d06729f	c112c5520b5fbc59b7b05370664c7abc13ce80f556ae4eb4a7d0a23eecb2eaeb
log_6ad7ada1efce71.45757198	2026-10-20 14:06:25	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.44ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	c112c5520b5fbc59b7b05370664c7abc13ce80f556ae4eb4a7d0a23eecb2eaeb	5e8ad59bc323e765c502d476927e839a3448a7ed6be74933cd626a4b862debc8
log_6ad7ada20286a7.27304836	2026-10-20 14:06:26	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.31ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	5e8ad59bc323e765c502d476927e839a3448a7ed6be74933cd626a4b862debc8	b7250f88ea63250c428b9a760bc6a99b46d14c5c119404da950d5bf740de72f2
log_6ad7b5a5648401.48336983	2026-10-20 14:40:37	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.59ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	b7250f88ea63250c428b9a760bc6a99b46d14c5c119404da950d5bf740de72f2	cb00b378fe74e37c4eb2482ef83569c0409ebe0d3bdd1428c87a58dc3f170ad7
log_6ad7b5a56f6948.85146398	2026-10-20 14:40:37	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	cb00b378fe74e37c4eb2482ef83569c0409ebe0d3bdd1428c87a58dc3f170ad7	a67f999452d6c66e6b6082e13050c8c420e41cbe44a3f8027010deae3f42e659
log_6ad7b7f8b732b7.07252623	2026-10-20 14:50:32	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.35ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	a67f999452d6c66e6b6082e13050c8c420e41cbe44a3f8027010deae3f42e659	1ca9d92d94c97d7bb8c44d69de5dabacf52fb1d7cd1d19a4ca691d4b047e7de2
log_6ad7baa8b338b9.28992952	2026-10-20 15:02:00	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.22ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	127.0.0.1	1ca9d92d94c97d7bb8c44d69de5dabacf52fb1d7cd1d19a4ca691d4b047e7de2	f6a15cf41ba37dd2d40ec9828d2592b60a3fcff03caf65d3d989ba3cdc0dd4c8
log_6ad7baa8b6f2a3.07508387	2026-10-20 15:02:00	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.17ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	127.0.0.1	f6a15cf41ba37dd2d40ec9828d2592b60a3fcff03caf65d3d989ba3cdc0dd4c8	ee311a970f98b407b43b57e9118dccebf5802222eec7670815e06dc0d44e23bb
log_6ad7c18834c745.31468097	2026-10-20 15:31:20	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (1.03ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	ee311a970f98b407b43b57e9118dccebf5802222eec7670815e06dc0d44e23bb	d3c598f7f047cba95d4f98c7ddb60783f2e66ae97047711eebe8dcc9961eea46
log_6ad7c238765877.18364359	2026-10-20 15:34:16	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.58ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	d3c598f7f047cba95d4f98c7ddb60783f2e66ae97047711eebe8dcc9961eea46	2f3ea00ab89eab5183b201df16f09ff2545ce8652e2bb56f3f94de6c60c0974c
log_6ad7c2387ced17.89118824	2026-10-20 15:34:16	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.53ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	2f3ea00ab89eab5183b201df16f09ff2545ce8652e2bb56f3f94de6c60c0974c	1a5eba35b74ead7280f99ac5fb89131e4331ec710bd124dc0e67633dd11fcabf
log_6ad7c37559d8e0.75615725	2026-10-20 15:39:33	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.53ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	1a5eba35b74ead7280f99ac5fb89131e4331ec710bd124dc0e67633dd11fcabf	bf09daab664e0f0c347bdb060e8ef1baed8e24dfe8bad7d9b3f9dabd9fa949e1
log_6ad7c375610fc9.88639558	2026-10-20 15:39:33	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.36ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	bf09daab664e0f0c347bdb060e8ef1baed8e24dfe8bad7d9b3f9dabd9fa949e1	97115a14ae130669f079286778b6cdc5aa550832d3cce06aef7a7d47c9650781
log_6ad7c512193133.80504337	2026-10-20 15:46:26	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.45ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	97115a14ae130669f079286778b6cdc5aa550832d3cce06aef7a7d47c9650781	32af78eaab57b81a2ded87de6a5fad8e19302a3f98ec22d7cb71f11c14f432d5
log_6ad7c512d0f2c1.84383157	2026-10-20 15:46:26	WARNING	Autenticacion	Login Fallido	Contraseña incorrecta para el usuario 'landorus' (C.I: V-12045552).	Anónimo / Sistema	::1	32af78eaab57b81a2ded87de6a5fad8e19302a3f98ec22d7cb71f11c14f432d5	0cbbab2ce540fdf30ba50c3f78857a60a3af6741365584bb0b79592a5f0fceaa
log_6ad7c8d0d855e9.96797090	2026-10-20 16:02:24	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.76ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	0cbbab2ce540fdf30ba50c3f78857a60a3af6741365584bb0b79592a5f0fceaa	4ab90ae1dedd7ac228b7539a72aab6f1f3c0df538e3f5b549d0a620e7646713f
log_6ad7c8d0def261.61042071	2026-10-20 16:02:24	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'hola': ERROR (0.54ms): El archivo de script 'script_tarea_1790660917_352.php' no existe en storage/scheduler/scripts/.	Anónimo / Sistema	::1	4ab90ae1dedd7ac228b7539a72aab6f1f3c0df538e3f5b549d0a620e7646713f	51b773ef60df8f4e99d6b4625c77e845fba8cfaf709550bc74597d900daffcaa
log_6ad7c940a05f08.92334635	2026-10-20 16:04:16	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	51b773ef60df8f4e99d6b4625c77e845fba8cfaf709550bc74597d900daffcaa	d40f5ec3d69202ca3cc9e987f03b0eafb30d93a8f4009417342cda4adbf70afb
log_6ad7c9a0ac2a57.08688091	2026-10-20 16:05:52	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.61ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	d40f5ec3d69202ca3cc9e987f03b0eafb30d93a8f4009417342cda4adbf70afb	4e66f4b7d5006167a59d47317ca2b3b6e2be51eb80f25a5cf6d0a318bcedd858
log_6ad7caf17a7ee9.32422561	2026-10-20 16:11:29	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (0.51ms): El archivo de script 'script_tarea_1790301553_528.php' no existe en storage/scheduler/scripts/.	Miguel González (ID: 7)	::1	4e66f4b7d5006167a59d47317ca2b3b6e2be51eb80f25a5cf6d0a318bcedd858	6d0f35be22ac08b4eec806edc96b3baa4038627eb70cbea2f753beef52b60482
log_6ad7cb3be22051.45190239	2026-10-20 16:12:43	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	6d0f35be22ac08b4eec806edc96b3baa4038627eb70cbea2f753beef52b60482	63009d6db4e05d4288d8200137bd5b4bfeb4d62861e8137475f997186542c7c4
log_6ad837a7bf5f74.39619599	2026-10-20 23:55:19	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (306.19ms): Error de ejecución CLI (Código 1).	Anónimo / Sistema	::1	63009d6db4e05d4288d8200137bd5b4bfeb4d62861e8137475f997186542c7c4	48557d320336456710175ae20502dd4e539b118077878f9acbdad6a366a241a9
log_6ad837ba65f815.88081055	2026-10-20 23:55:38	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	48557d320336456710175ae20502dd4e539b118077878f9acbdad6a366a241a9	3b6185ddd234af6060b2422527d826ee1c761f63093043ec774cf9dd50f94c3f
log_6ad90b72bd8c92.63718946	2026-10-21 14:58:58	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'Limpieza de Archivos Temporales & Caché': ÉXITO (6.14ms): Limpieza completada. Depurados 2 archivos temporales y cachés.	Anónimo / Sistema	::1	3b6185ddd234af6060b2422527d826ee1c761f63093043ec774cf9dd50f94c3f	f11f761b13869942a208bf1c06c3fbb30223d4b648c8c903ec479e0930836b00
log_6ad90b736b5f26.11215277	2026-10-21 14:58:59	INFO	SuperAdmin	Crear Backup	Respaldo auto_cron_backup_2026-10-21_14-58-58.sql.gz generado exitosamente. Respaldos antiguos purgados: 0	Anónimo / Sistema	::1	f11f761b13869942a208bf1c06c3fbb30223d4b648c8c903ec479e0930836b00	ea2d02dfa2eeeadb39c00caabfd63a7d5462c2294ca2fbfbc1e30604235f50d2
log_6ad90b736c1c18.67782513	2026-10-21 14:58:59	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'Respaldo Automático de Medianoche (PostgreSQL Dump)': ÉXITO (652.19ms): Respaldo generado con éxito: auto_cron_backup_2026-10-21_14-58-58.sql.gz	Anónimo / Sistema	::1	ea2d02dfa2eeeadb39c00caabfd63a7d5462c2294ca2fbfbc1e30604235f50d2	4e96d338fa9f44055ca2f6561effc7459cf99dd3a0395188947b2b7b2c7143f9
log_6ad90b73c5f678.79297853	2026-10-21 14:58:59	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (358.2ms): Error de ejecución CLI (Código 1).	Anónimo / Sistema	::1	4e96d338fa9f44055ca2f6561effc7459cf99dd3a0395188947b2b7b2c7143f9	cd3aa975e003460e68f2a153b7c90a6739b23b001ec2de7c29d6ad3fc7c948e9
log_6ad90bab82b637.88112506	2026-10-21 14:59:55	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	cd3aa975e003460e68f2a153b7c90a6739b23b001ec2de7c29d6ad3fc7c948e9	9dc89c4098e0ca00fdeefc75701fb037cf3d36499874ee471bca0c594289907a
log_6ad90bdd5c5f93.69139901	2026-10-21 15:00:45	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (311.82ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	9dc89c4098e0ca00fdeefc75701fb037cf3d36499874ee471bca0c594289907a	a3c005ee10254b12f2f18be3865bcbebf3a542450b8523f17a93e8aba02340f3
log_6ad90d37e428b7.36745626	2026-10-21 15:06:31	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (197.84ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	a3c005ee10254b12f2f18be3865bcbebf3a542450b8523f17a93e8aba02340f3	551ebfd45d131157181397446b6d06f794d99862db761a8c061c53a2be2566f1
log_6ad911c6c40bc6.69726691	2026-10-21 15:25:58	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (175.31ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	551ebfd45d131157181397446b6d06f794d99862db761a8c061c53a2be2566f1	79a72ff98d6b6236918ae428b452d4868474113d08d97324f77990bd42ac3d64
log_6ad916d51fef52.33281643	2026-10-21 15:47:33	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (167.54ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	79a72ff98d6b6236918ae428b452d4868474113d08d97324f77990bd42ac3d64	c7fffc57caa0bdc7c153b52f5a9067c3948b5c51eceb463e4dc6840c00bfa0a4
log_6ad917bb5acf01.44170069	2026-10-21 15:51:23	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (171.36ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	c7fffc57caa0bdc7c153b52f5a9067c3948b5c51eceb463e4dc6840c00bfa0a4	0efae8a9262d09313ec2d4f4151c05e1eadeb3e9d09f9981eba6d90c32588216
log_6ad9195171cc65.67157071	2026-10-21 15:58:09	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (281.76ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	0efae8a9262d09313ec2d4f4151c05e1eadeb3e9d09f9981eba6d90c32588216	fddcfcb12bfe77f80481db89964a974de50e15ba47ceb67e6815bafdf29a62a7
log_6ad91a0c4fb0f5.47653246	2026-10-21 16:01:16	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (221.34ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	fddcfcb12bfe77f80481db89964a974de50e15ba47ceb67e6815bafdf29a62a7	0a39d692e28908dd40d41220854069ddd795ec3ded61cd7f54605e79d6050f97
log_6ad92006a53629.10800061	2026-10-21 16:26:46	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (188.19ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	0a39d692e28908dd40d41220854069ddd795ec3ded61cd7f54605e79d6050f97	d3d617de7d393779a2dd02ba82f49e6cb70d05a9d2351cb562e5620d4ab56312
log_6ad9205c220de3.51298319	2026-10-21 16:28:12	INFO	Autenticacion	Actualización Líneas y Dimensiones Especialidad	El docente/investigador ID #7 actualizó su especialidad (1 líneas y 3 dimensiones operativas).	Miguel González (ID: 7)	::1	d3d617de7d393779a2dd02ba82f49e6cb70d05a9d2351cb562e5620d4ab56312	48492cb3345bc5df08403dd3186e2948495bcb475a6569c2eb40a96e95aba362
log_6ad922e49064f1.65088229	2026-10-21 16:39:00	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (149.04ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	48492cb3345bc5df08403dd3186e2948495bcb475a6569c2eb40a96e95aba362	43c80f5f12fbaee2c67442bce156bb206d68c2481ff3ad5a69aac80ece5db85d
log_6ad925471aeab9.69213721	2026-10-21 16:49:11	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (184.99ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	43c80f5f12fbaee2c67442bce156bb206d68c2481ff3ad5a69aac80ece5db85d	ce8d6d278812c605417c98963411ab77498e4177ad755257f1695351af9a53eb
log_6ad926c200bb25.95452165	2026-10-21 16:55:30	ERROR	SuperAdmin	Ejecución Tarea Programada	Tarea 'Generar Embeddings Semánticos': ERROR (176.41ms): Error de ejecución CLI (Código 1).	Miguel González (ID: 7)	::1	ce8d6d278812c605417c98963411ab77498e4177ad755257f1695351af9a53eb	b7b4db84bcf092fda018c4f7cfe3a22ed0768eee57d9328a67f67ff7d9774152
log_6ad9274755a383.25081219	2026-10-21 16:57:43	WARNING	RepositorioPST	Reiniciar Vectores ONNX	Se marcaron 12 proyectos para re-vectorización con los nuevos parámetros.	Miguel González (ID: 7)	::1	b7b4db84bcf092fda018c4f7cfe3a22ed0768eee57d9328a67f67ff7d9774152	27c205fdaa6f2c136605ad90ee9def5782229760159a3000e918aeb446c17be0
log_6ada5829a2fbb3.05916451	2026-10-22 14:38:33	INFO	SuperAdmin	Ejecución Tarea Programada	Tarea 'Limpieza de Archivos Temporales & Caché': ÉXITO (4092.2ms): Limpieza completada. Depurados 0 archivos temporales y cachés.	Anónimo / Sistema	::1	27c205fdaa6f2c136605ad90ee9def5782229760159a3000e918aeb446c17be0	01c793c2468be8ed330c5197d4e6deba680b6c461175c10601e1f554014f65ea
log_6ada5adf606823.94758455	2026-10-22 14:50:07	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	01c793c2468be8ed330c5197d4e6deba680b6c461175c10601e1f554014f65ea	2a9b2c71da9a78e1ae5b07bbfcae022a13e27aee786bf1fd4349c92331e76300
log_6ada5bf982b597.74257076	2026-10-22 14:54:49	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	2a9b2c71da9a78e1ae5b07bbfcae022a13e27aee786bf1fd4349c92331e76300	34ce989b55211a820c3ccfdcfe90d598c56cf3d83b6be5f2d2fd8c8c12a9158f
log_6ada6f85c02ea3.09144651	2026-10-22 16:18:13	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	34ce989b55211a820c3ccfdcfe90d598c56cf3d83b6be5f2d2fd8c8c12a9158f	35b85440679baab3744a2a724444538c84e65e7f6e87309e791bf2232568ba18
log_6adb7a19b7e147.80705061	2026-10-23 11:15:37	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	35b85440679baab3744a2a724444538c84e65e7f6e87309e791bf2232568ba18	4f627925fdfa2235c9bb6c5090f14b61a6ac673819ced298a9387de9b5b8ea0f
log_6ae247caa47719.55249240	2026-10-28 15:06:50	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	4f627925fdfa2235c9bb6c5090f14b61a6ac673819ced298a9387de9b5b8ea0f	c86b861c40097105e980db41a15e2bc3ef4e27d3d4b40c976b60f1c2069e20f4
log_6ae247fbec0d04.53787400	2026-10-28 15:07:39	INFO	Autenticacion	Cierre de Sesión	El usuario 'Miguel González' (ID: 7) cerró su sesión voluntariamente.	Miguel González (ID: 7)	::1	c86b861c40097105e980db41a15e2bc3ef4e27d3d4b40c976b60f1c2069e20f4	daf994b1809a971dae2317307b31c29c4d5f48a677501bfe0d06c0a4b36678f1
log_6ae7ee4d12f436.71427635	2026-11-01 21:58:37	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'Miguel González' (C.I: V-32621284, Rol: Super Administrador).	Miguel González (ID: 7)	::1	daf994b1809a971dae2317307b31c29c4d5f48a677501bfe0d06c0a4b36678f1	b063650fe1c7b46d6e611029d51a2aa7d40e9f44b30a2cef3416f4893dc10bad
log_6ae8bdc564c5f5.75007848	2026-11-02 12:43:49	INFO	Autenticacion	Inicio de Sesión	Acceso exitoso al sistema de 'MSc. Carlos Mendoza' (C.I: V-32621284, Rol: Super Administrador).	MSc. Carlos Mendoza (ID: 7)	::1	b063650fe1c7b46d6e611029d51a2aa7d40e9f44b30a2cef3416f4893dc10bad	156357fa42b281a5a5f3b0f71e4f6e8993d1420e6de6883274b4c071c77eefc9
log_6ae8bded3d92e8.44335997	2026-11-02 12:44:29	INFO	Autenticacion	Nombre Modificado por SuperAdmin	El superusuario actualizó su nombre a 'Miguel Gonzalez' (ID: 7).	Miguel Gonzalez (ID: 7)	::1	156357fa42b281a5a5f3b0f71e4f6e8993d1420e6de6883274b4c071c77eefc9	f2077386a4475010c03d08fdcb8128b87852772077d59f4a0b53c073e2f90105
\.


--
-- TOC entry 5811 (class 0 OID 51709)
-- Dependencies: 274
-- Data for Name: telemetria_cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.telemetria_cache (id, datos) FROM stdin;
1	{"timestamp": 1793637832, "storage_mb": 787.56, "files_count": 154}
\.


--
-- TOC entry 5812 (class 0 OID 51716)
-- Dependencies: 275
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_recurso (id, nombre, descripcion) FROM stdin;
1	PST / Trabajo de Grado	Proyectos Socio-Tecnológicos y Tesis
2	Investigación Docente	Papers y artículos de investigación del personal académico
3	Material de Apoyo / Didáctico	Recursos adicionales para estudiantes
\.


--
-- TOC entry 5814 (class 0 OID 51724)
-- Dependencies: 277
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_tutor (id, nombre, descripcion) FROM stdin;
1	Director	Director principal del proyecto
2	Coordinador	Asesor metodológico
3	Tutor Académico	Especialista en el área
4	Tutor Comunitario	Representante de la comunidad
\.


--
-- TOC entry 5816 (class 0 OID 51732)
-- Dependencies: 279
-- Data for Name: trayectos; Type: TABLE DATA; Schema: public; Owner: miki
--

COPY public.trayectos (id, id_carrera, nombre, numero, descripcion, activo, created_at) FROM stdin;
1	1	Trayecto I	1	Trayecto I del PNF en Informática	t	2026-09-28 23:01:27.263246
2	1	Trayecto II	2	Trayecto II del PNF en Informática	t	2026-09-28 23:01:27.263246
3	1	Trayecto III	3	Trayecto III del PNF en Informática	t	2026-09-28 23:01:27.263246
4	1	Trayecto IV	4	Trayecto IV del PNF en Informática	t	2026-09-28 23:01:27.263246
5	2	Trayecto I	1	Trayecto I del PNF en Electricidad	t	2026-09-28 23:01:27.263246
6	2	Trayecto II	2	Trayecto II del PNF en Electricidad	t	2026-09-28 23:01:27.263246
7	2	Trayecto III	3	Trayecto III del PNF en Electricidad	t	2026-09-28 23:01:27.263246
8	2	Trayecto IV	4	Trayecto IV del PNF en Electricidad	t	2026-09-28 23:01:27.263246
9	3	Trayecto I	1	Trayecto I del PNF en Administración	t	2026-09-28 23:01:27.263246
10	3	Trayecto II	2	Trayecto II del PNF en Administración	t	2026-09-28 23:01:27.263246
11	3	Trayecto III	3	Trayecto III del PNF en Administración	t	2026-09-28 23:01:27.263246
12	3	Trayecto IV	4	Trayecto IV del PNF en Administración	t	2026-09-28 23:01:27.263246
13	4	Trayecto I	1	Trayecto I del PNF en Agroalimentación	t	2026-09-28 23:01:27.263246
14	4	Trayecto II	2	Trayecto II del PNF en Agroalimentación	t	2026-09-28 23:01:27.263246
15	4	Trayecto III	3	Trayecto III del PNF en Agroalimentación	t	2026-09-28 23:01:27.263246
16	4	Trayecto IV	4	Trayecto IV del PNF en Agroalimentación	t	2026-09-28 23:01:27.263246
17	5	Trayecto I	1	Trayecto I del PNF en Construcción Civil	t	2026-09-28 23:01:27.263246
18	5	Trayecto II	2	Trayecto II del PNF en Construcción Civil	t	2026-09-28 23:01:27.263246
19	5	Trayecto III	3	Trayecto III del PNF en Construcción Civil	t	2026-09-28 23:01:27.263246
20	5	Trayecto IV	4	Trayecto IV del PNF en Construcción Civil	t	2026-09-28 23:01:27.263246
\.


--
-- TOC entry 5818 (class 0 OID 51744)
-- Dependencies: 281
-- Data for Name: tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tutores (id, nombre_completo, cedula) FROM stdin;
1	Lando	V-12345678
2	Mikeyisito	V-18765432
3	María Antonieta Pérez	V-15444333
7	aaaaa aaa	22222
8	aaa aaaa	33333
9	aaaaa aaaaaa	444444
10	Karina Gutiérrez	2222231312
11	asdasdas faasdas	12312312
12	Karina Gutiérrez	3123123
13	Prof. Tutor Académico Prueba	V-15888999
14	Dr. Asesor Edumático	V-12000333
15	Prof. Asesor	V-14555666
16	Prof. Asesor Prueba	V-11223344
17	Karla Rodríguez	\N
18	Karina Araujo	\N
19	Helen Gonzales	\N
20	Msc Néstor Araujo	\N
21	Msc Julio Abreu	\N
22	Karina Gutierrez	\N
25	asdasd	\N
26	Ricardo Dos Santosss	\N
27	Karina Gutiérrezxczxc	\N
28	Ricardo Dos Santos	\N
29	María Luisa Colmenares	\N
30	Rossana Virgilio	\N
31	Carlos Simancas	\N
32	Tutor Prueba Academico	V-11111111
33	Tutor Prueba Institucional	V-22222222
34	Tutor Prueba Comunitario	V-33333333
35	Winston Méndez	\N
36	Carmen Muchacho	\N
37	Yajaira Franco	\N
38	Mary Moreno	\N
39	Estella Berríos	\N
40	KarinAI	Karina
41	Gusman Muchacho	\N
42	Diana Hernández	\N
43	Drover Briceño	V-13896043
44	Rodolfo Blanco	V-15584551
45	Wesley Bentacourt	\N
46	Jean Carlos Villarreal	\N
47	Maria Leida Graterol	\N
48	Dra. Wesley Betancourt	\N
49	Bentancourt Wesley	\N
50	Castillo Marily	\N
51	Benítez Ledgin	\N
52	Marzzia Gil	\N
53	Ing. Karina Araujo	\N
54	Yenny Araujo	\N
55	Grisseth Araujo	\N
56	Janneth Palomares	\N
57	Dr. Ricardo Dos Santos	\N
58	Lilia Flores	\N
59	Wesley Bentancourt Gonzales	\N
60	Homero De J Urbina R	\N
61	Yoximar Rangel	\N
62	María Mónica Mendoza	\N
63	Jorge López	\N
64	Miguel Morales	\N
65	Rodríguez Karla	\N
66	Dos Santos Ricardo	\N
67	Durán Mariely	\N
68	Gutiérrez Karina	\N
69	Montilla Carlos	\N
70	Yenni Hernández	\N
71	José Gregorio Lugo	\N
72	María Valecillos	\N
73	Gabriel García	\N
74	Yelitza Terán	\N
75	Luis Mogollón	\N
76	Yadisneidis Reverol	\N
77	José Leonardo Fernández	\N
78	Karina Rodriguez	\N
79	Eduardo Peñaloza	\N
80	Militza Victorá	\N
81	Jenifer Rojas	\N
82	Yajaira Torres	\N
83	Yazmine Castellanos	\N
84	Dra. Betancourt González Wesley	\N
85	Méndez A. Winston	\N
86	Lic. Rivera Morillo Walquirian del Valle	\N
87	Montilla Elías	\N
88	Marielys Torres	\N
89	Eleida Blanco	\N
90	Rubén Jiménez	\N
91	Marlene Sosa	\N
92	Jhonder Duran	\N
93	Rosa Quintero	\N
94	Araujo Darlin	\N
95	Juan Daboin	\N
96	Henrichs Gonzalez	\N
97	Hernán Valera	\N
98	Maily Nava	\N
99	Darling Araujo	\N
100	María Carmen Briceño	\N
101	Doctora Yajaira Franco	\N
102	Licenciada Egluibet Vásquez	\N
103	Alvaro Castillo	\N
104	Ray Arias	\N
105	Jose Caldera	\N
106	Juleiba Abreu	\N
107	José Páez	\N
108	Sugheil Araujo	\N
109	Freddy Segovia	\N
110	Rodríguez Andreina	V-32000225
111	Balza Roger	V-31728855
112	Vásquez Ronald	V-17346213
113	Bentacourt Wesley	\N
114	Duran Jhonder	\N
115	Eiber Palomares	\N
116	Briceño Anmary	V-17093751
117	Briceño José	V-23250536
118	Karelis Briceño	\N
119	Yesenia Toro	\N
120	Nancy Gavidia	\N
121	Egluibet Vásquez	\N
122	Gil Marzzia	\N
123	Lilia Laguna	\N
124	Kelly Rivas	\N
125	Fidel Balestrini	\N
126	Diego Andres Villegas Rivero V	V-15408726
127	Engriz Azuaje De Chirinos V	\N
128	Norelis Matheus	\N
129	Rondón Yosimar	V-17321336
130	Reinaldo Rivas	V-11898586
131	Neiza Romero	\N
132	Carlos García	\N
133	Richard Páez Olmos	\N
134	Jackelin Ramirez	\N
135	Yenifer Araujo	\N
136	Jesús Medina	\N
137	Ninoska Villareal	\N
138	Licda Lorena Abreu	\N
139	Zulay Torres	\N
\.


--
-- TOC entry 5820 (class 0 OID 51750)
-- Dependencies: 283
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, nombre_completo, email, cedula, contrasena, id_rol, activo, reset_token, reset_expires, telefono, email_verified, activation_token, estado_registro, es_profesor_pst, es_investigador, foto_perfil, orcid, sitio_web, grado_academico) FROM stdin;
4	ale	ale@yaju.com	44444444	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
5	bibi	bibi@gmail.com	4444111	\N	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
8	Yisu Monte	yisu@gmail.com	30866991	$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
9	Pedro Perez	iaiaia@gmail.com	4123123	$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
11	Juan	123@gmail.com	1234	$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
14	Sixsevenaldo González	676767@gmail.com	67	$2y$10$XIdtQdP6d.bZSGCINdEbIuTtuqee9E3EEKYp5Ogbm/I2JW5XQbP/O	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
13	Cepillíno	cepillin@gmail.com	80	$2y$10$ML6M4RYmR0f2yCoxOpRvaONIE/nUvwOmcsmGySeBOOBGjy9xUmbk6	3	t	\N	\N	\N	f	\N	\N	f	f	\N	\N	\N	\N
16	no soy miguel	orlando5711666@gmail.com	1234567	$2y$10$g1LIDvziBpq3KrEilAhp3.RC1ziliq9hOpkdhWFISEjtJ.Y00SUHS	3	t	\N	\N	\N	t	\N	\N	f	f	\N	\N	\N	\N
3	miki	miki@gmail.com	33333333	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	3	t	\N	\N	\N	t	\N	\N	f	f	\N	\N	\N	\N
1	Adrus	andru@gmail.com	11111111	$2y$10$1sBy413YpJ9MQGlRt/g6y.OGkfno7aRuKxShONKSeWrvhQNS53YDO	1	t	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
2	lando	lando@gmail.com	22222222	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	2	t	\N	\N	\N	t	\N	\N	f	t	\N	\N	\N	\N
10	Dr. Ricardo Dos Santos	ricardojdsg@upttmbi.edu.ve	123	$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O	4	t	\N	\N	\N	t	\N	\N	t	t	public/uploads/perfiles/ricardo_dos_santos.jpg	0000-0001-8752-8550	https://ricardojdsg.github.io/	Ingeniero de Sistemas · Magister Scientiae en Computación · Doctor en Ciencias Aplicadas
17	adrusss	andrusramirez2020@gmail.com	30469331	$2y$10$SFowO4NOxSgKqx35qYr7iOiJU2PJ6hJ.uTO2zdxSSXMQjX64sRwiu	2	t	\N	\N	\N	t	\N	\N	f	t	\N	\N	\N	\N
6	Ing. José Rivero	profe@test.com	1	$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a	4	f	\N	\N	\N	f	\N	\N	t	t	https://ui-avatars.com/api/?name=Jose+Rivero&size=256&background=0f172a&color=38bdf8&bold=true	0000-0002-4183-9102	https://ciidi.iutetvirtual.org.ve/	Ingeniero en Informática · MSc. en Ingeniería de Software
12	[Archivado] ANDRUS	andrusramirez2020@gmail.com_deleted_1789844158	30469331_x9844158	$2y$10$Vii.OSXjYhxOk.Xq.dx2EODQb3U9cahqa401C48PNPR3mlXdi85Ii	2	f	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
15	[Archivado] DIOSs	DIOS@gmail.com_deleted_1789845222	12000000_x9845222	$2y$10$eaICZWMAWr11BS/RL0ju0O49pEw.3lQIpEGrLoe6FweXprGxkjttu	1	f	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
23	[Archivado] LANDO2	orlando57116666@gmail.com_deleted_1791994680	V1204555_x1994680	$2y$12$5ndVuVPD0Gv3d3ptJhtbceu5dxpmLV9gnQPMeE5x7sVtVcyO2l696	4	f	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
22	[Archivado] MAMA DE LANDO	yisus@gmail.com_deleted_1791994688	V1204555_x1994688	$2y$12$mJYxbywl4.ooS2EECW0GVewBe.21DQalwt1SfgvQr12dg6bVPy7hO	4	f	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
19	Juan Salcedo	juanxzall0701@gmail.com	V-31008131	$2y$12$8gDx2QuKUTCrj4svuy3duej5NOvCh0442.vScjUAn5OM5Xp99JUKy	1	t	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
24	landorus	lando1609721@gmail.com	V-12045552	$2y$12$rUd7xVKKNVAscZ6pXWVMzuzsieoCopAsBwMhE1IKQgcL9vWNol4a2	4	t	\N	\N	\N	f	\N	\N	f	t	\N	\N	\N	\N
7	Miguel González	erwesitodepollo@gmail.com	32621284	$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.	1	t	\N	\N	\N	f	dc1c777c1cdc20c388ae51c653f684a4c5e4673e509adc494000642ed4e1cd0d	\N	f	t	public/uploads/perfiles/perfil_u7_1793638872.jpg	\N	\N	\N
\.


--
-- TOC entry 5822 (class 0 OID 51763)
-- Dependencies: 285
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.visitantes (id, ip_address, user_agent, pagina_origen) FROM stdin;
\.


--
-- TOC entry 5824 (class 0 OID 51771)
-- Dependencies: 287
-- Data for Name: waf_rate_limiter; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.waf_rate_limiter (ip, tipo, intentos, primer_intento, ultimo_intento, bloqueado_hasta, razon, datos_adicionales, creado_el) FROM stdin;
127.0.0.1	whitelist	0	\N	2026-09-30 20:14:31.796931	\N	Desarrollador Local (Localhost IPv4)	{"responsable": "SuperAdmin"}	2026-09-30 20:14:31.796931
::1	whitelist	0	\N	2026-09-30 20:14:31.797469	\N	Desarrollador Local (Localhost IPv6)	{"responsable": "SuperAdmin"}	2026-09-30 20:14:31.797469
\.


--
-- TOC entry 5910 (class 0 OID 0)
-- Dependencies: 222
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, true);


--
-- TOC entry 5911 (class 0 OID 0)
-- Dependencies: 314
-- Name: analitica_snapshots_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.analitica_snapshots_id_seq', 4, true);


--
-- TOC entry 5912 (class 0 OID 0)
-- Dependencies: 224
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 1388, true);


--
-- TOC entry 5913 (class 0 OID 0)
-- Dependencies: 226
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 280, true);


--
-- TOC entry 5914 (class 0 OID 0)
-- Dependencies: 228
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- TOC entry 5915 (class 0 OID 0)
-- Dependencies: 230
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 18, true);


--
-- TOC entry 5916 (class 0 OID 0)
-- Dependencies: 232
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 3, true);


--
-- TOC entry 5917 (class 0 OID 0)
-- Dependencies: 305
-- Name: cursos_preinscripciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.cursos_preinscripciones_id_seq', 1, true);


--
-- TOC entry 5918 (class 0 OID 0)
-- Dependencies: 236
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 24, true);


--
-- TOC entry 5919 (class 0 OID 0)
-- Dependencies: 238
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 8, true);


--
-- TOC entry 5920 (class 0 OID 0)
-- Dependencies: 240
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 17, true);


--
-- TOC entry 5921 (class 0 OID 0)
-- Dependencies: 242
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historico_versiones_pst_id_seq', 1, true);


--
-- TOC entry 5922 (class 0 OID 0)
-- Dependencies: 244
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 4, true);


--
-- TOC entry 5923 (class 0 OID 0)
-- Dependencies: 311
-- Name: investigadores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.investigadores_id_seq', 26, true);


--
-- TOC entry 5924 (class 0 OID 0)
-- Dependencies: 246
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 14, true);


--
-- TOC entry 5925 (class 0 OID 0)
-- Dependencies: 249
-- Name: niveles_academicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.niveles_academicos_id_seq', 6, true);


--
-- TOC entry 5926 (class 0 OID 0)
-- Dependencies: 251
-- Name: notificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_id_seq', 6, true);


--
-- TOC entry 5927 (class 0 OID 0)
-- Dependencies: 253
-- Name: password_resets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.password_resets_id_seq', 1, true);


--
-- TOC entry 5928 (class 0 OID 0)
-- Dependencies: 255
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.postulaciones_estudiantes_id_seq', 9, true);


--
-- TOC entry 5929 (class 0 OID 0)
-- Dependencies: 258
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 10, true);


--
-- TOC entry 5930 (class 0 OID 0)
-- Dependencies: 289
-- Name: profesores_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.profesores_pst_id_seq', 2, true);


--
-- TOC entry 5931 (class 0 OID 0)
-- Dependencies: 260
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 18, true);


--
-- TOC entry 5932 (class 0 OID 0)
-- Dependencies: 268
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 305, true);


--
-- TOC entry 5933 (class 0 OID 0)
-- Dependencies: 270
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 5, true);


--
-- TOC entry 5934 (class 0 OID 0)
-- Dependencies: 272
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- TOC entry 5935 (class 0 OID 0)
-- Dependencies: 301
-- Name: seg_lineas_alertas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.seg_lineas_alertas_id_seq', 3, true);


--
-- TOC entry 5936 (class 0 OID 0)
-- Dependencies: 299
-- Name: seg_lineas_bloqueos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.seg_lineas_bloqueos_id_seq', 1, false);


--
-- TOC entry 5937 (class 0 OID 0)
-- Dependencies: 297
-- Name: seg_lineas_excepciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.seg_lineas_excepciones_id_seq', 1, false);


--
-- TOC entry 5938 (class 0 OID 0)
-- Dependencies: 294
-- Name: seg_lineas_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.seg_lineas_logs_id_seq', 409, true);


--
-- TOC entry 5939 (class 0 OID 0)
-- Dependencies: 307
-- Name: solicitudes_docentes_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.solicitudes_docentes_pst_id_seq', 1, true);


--
-- TOC entry 5940 (class 0 OID 0)
-- Dependencies: 276
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 3, true);


--
-- TOC entry 5941 (class 0 OID 0)
-- Dependencies: 278
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- TOC entry 5942 (class 0 OID 0)
-- Dependencies: 280
-- Name: trayectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: miki
--

SELECT pg_catalog.setval('public.trayectos_id_seq', 20, true);


--
-- TOC entry 5943 (class 0 OID 0)
-- Dependencies: 282
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 139, true);


--
-- TOC entry 5944 (class 0 OID 0)
-- Dependencies: 284
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 24, true);


--
-- TOC entry 5945 (class 0 OID 0)
-- Dependencies: 286
-- Name: visitantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visitantes_id_seq', 1, true);


--
-- TOC entry 5372 (class 2606 OID 51809)
-- Name: accesos_recursos accesos_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5544 (class 2606 OID 60669)
-- Name: analitica_snapshots analitica_snapshots_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.analitica_snapshots
    ADD CONSTRAINT analitica_snapshots_pkey PRIMARY KEY (id);


--
-- TOC entry 5374 (class 2606 OID 51811)
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- TOC entry 5376 (class 2606 OID 51813)
-- Name: autores autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_pkey PRIMARY KEY (id);


--
-- TOC entry 5378 (class 2606 OID 51815)
-- Name: carreras carreras_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_nombre_key UNIQUE (nombre);


--
-- TOC entry 5380 (class 2606 OID 51817)
-- Name: carreras carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id);


--
-- TOC entry 5382 (class 2606 OID 51819)
-- Name: categorias categorias_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nombre_key UNIQUE (nombre);


--
-- TOC entry 5384 (class 2606 OID 51821)
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- TOC entry 5534 (class 2606 OID 60548)
-- Name: cursos_dimensiones cursos_dimensiones_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_pkey PRIMARY KEY (id_curso, id_dimension);


--
-- TOC entry 5530 (class 2606 OID 60528)
-- Name: cursos_lineas cursos_lineas_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_pkey PRIMARY KEY (id_curso, id_linea);


--
-- TOC entry 5386 (class 2606 OID 51823)
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5524 (class 2606 OID 60482)
-- Name: cursos_preinscripciones cursos_preinscripciones_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_preinscripciones
    ADD CONSTRAINT cursos_preinscripciones_pkey PRIMARY KEY (id);


--
-- TOC entry 5388 (class 2606 OID 51825)
-- Name: cursos cursos_slug_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_slug_key UNIQUE (slug);


--
-- TOC entry 5392 (class 2606 OID 51827)
-- Name: detalles_investigaciones detalles_investigaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT detalles_investigaciones_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5490 (class 2606 OID 52128)
-- Name: detalles_proyectos detalles_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5390 (class 2606 OID 51829)
-- Name: detalles_articulos detalles_revistas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5395 (class 2606 OID 51831)
-- Name: dimensiones_operativas dimensiones_operativas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT dimensiones_operativas_pkey PRIMARY KEY (id);


--
-- TOC entry 5397 (class 2606 OID 51833)
-- Name: editoriales editoriales_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_nombre_key UNIQUE (nombre);


--
-- TOC entry 5399 (class 2606 OID 51835)
-- Name: editoriales editoriales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_pkey PRIMARY KEY (id);


--
-- TOC entry 5401 (class 2606 OID 51837)
-- Name: etiquetas etiquetas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_nombre_key UNIQUE (nombre);


--
-- TOC entry 5403 (class 2606 OID 51839)
-- Name: etiquetas etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_pkey PRIMARY KEY (id);


--
-- TOC entry 5405 (class 2606 OID 51841)
-- Name: historico_versiones_pst historico_versiones_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT historico_versiones_pst_pkey PRIMARY KEY (id);


--
-- TOC entry 5407 (class 2606 OID 51843)
-- Name: investigaciones_ofertadas investigaciones_ofertadas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT investigaciones_ofertadas_pkey PRIMARY KEY (id);


--
-- TOC entry 5538 (class 2606 OID 60589)
-- Name: investigadores investigadores_orcid_key; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_orcid_key UNIQUE (orcid);


--
-- TOC entry 5540 (class 2606 OID 60587)
-- Name: investigadores investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigadores
    ADD CONSTRAINT investigadores_pkey PRIMARY KEY (id);


--
-- TOC entry 5409 (class 2606 OID 51845)
-- Name: lineas_investigacion lineas_investigacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_pkey PRIMARY KEY (id);


--
-- TOC entry 5411 (class 2606 OID 51847)
-- Name: matriz_rbac matriz_rbac_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matriz_rbac
    ADD CONSTRAINT matriz_rbac_pkey PRIMARY KEY (nivel_privilegio, modulo);


--
-- TOC entry 5413 (class 2606 OID 51849)
-- Name: niveles_academicos niveles_academicos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_codigo_key UNIQUE (codigo);


--
-- TOC entry 5415 (class 2606 OID 51851)
-- Name: niveles_academicos niveles_academicos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.niveles_academicos
    ADD CONSTRAINT niveles_academicos_pkey PRIMARY KEY (id);


--
-- TOC entry 5417 (class 2606 OID 51853)
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


--
-- TOC entry 5419 (class 2606 OID 51855)
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (id);


--
-- TOC entry 5502 (class 2606 OID 52225)
-- Name: investigaciones_dimensiones pk_inv_dim; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigaciones_dimensiones
    ADD CONSTRAINT pk_inv_dim PRIMARY KEY (id_investigacion, id_dimension);


--
-- TOC entry 5500 (class 2606 OID 52208)
-- Name: investigaciones_lineas pk_inv_lin; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT pk_inv_lin PRIMARY KEY (id_investigacion, id_linea);


--
-- TOC entry 5498 (class 2606 OID 52190)
-- Name: profesor_linea pk_profesor_linea; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT pk_profesor_linea PRIMARY KEY (id_usuario, id_linea);


--
-- TOC entry 5437 (class 2606 OID 51857)
-- Name: proyecto_carreras_vinculadas pk_proyecto_carreras_vinculadas; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT pk_proyecto_carreras_vinculadas PRIMARY KEY (id_recurso, id_carrera);


--
-- TOC entry 5422 (class 2606 OID 51859)
-- Name: postulaciones_estudiantes postulaciones_estudiantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT postulaciones_estudiantes_pkey PRIMARY KEY (id);


--
-- TOC entry 5426 (class 2606 OID 51861)
-- Name: preferencias_usuario preferencias_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 5549 (class 2606 OID 60685)
-- Name: profesor_dimension profesor_dimension_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_pkey PRIMARY KEY (id_usuario, id_dimension);


--
-- TOC entry 5493 (class 2606 OID 52155)
-- Name: profesores_pst profesores_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT profesores_pst_pkey PRIMARY KEY (id);


--
-- TOC entry 5431 (class 2606 OID 51863)
-- Name: propuestas_empresa propuestas_empresa_codigo_seguimiento_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_codigo_seguimiento_key UNIQUE (codigo_seguimiento);


--
-- TOC entry 5433 (class 2606 OID 51865)
-- Name: propuestas_empresa propuestas_empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_pkey PRIMARY KEY (id);


--
-- TOC entry 5439 (class 2606 OID 51867)
-- Name: proyecto_tutores proyecto_tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_pkey PRIMARY KEY (id_recurso, id_tutor);


--
-- TOC entry 5441 (class 2606 OID 51869)
-- Name: recurso_autores recurso_autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_pkey PRIMARY KEY (id_recurso, id_autor);


--
-- TOC entry 5443 (class 2606 OID 51871)
-- Name: recurso_categorias recurso_categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria);


--
-- TOC entry 5447 (class 2606 OID 51873)
-- Name: recurso_clasificaciones recurso_clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT recurso_clasificaciones_pkey PRIMARY KEY (id_recurso, id_linea_investigacion);


--
-- TOC entry 5449 (class 2606 OID 51875)
-- Name: recurso_etiquetas recurso_etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT recurso_etiquetas_pkey PRIMARY KEY (id_recurso, id_etiqueta);


--
-- TOC entry 5542 (class 2606 OID 60596)
-- Name: recurso_investigadores recurso_investigadores_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT recurso_investigadores_pkey PRIMARY KEY (id_recurso, id_investigador);


--
-- TOC entry 5451 (class 2606 OID 51877)
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5453 (class 2606 OID 51879)
-- Name: registro_actividad registro_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_pkey PRIMARY KEY (id);


--
-- TOC entry 5455 (class 2606 OID 51881)
-- Name: roles roles_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_key UNIQUE (nombre);


--
-- TOC entry 5457 (class 2606 OID 51883)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 5518 (class 2606 OID 60442)
-- Name: seg_lineas_alertas seg_lineas_alertas_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_alertas
    ADD CONSTRAINT seg_lineas_alertas_pkey PRIMARY KEY (id);


--
-- TOC entry 5515 (class 2606 OID 60421)
-- Name: seg_lineas_bloqueos seg_lineas_bloqueos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_bloqueos
    ADD CONSTRAINT seg_lineas_bloqueos_pkey PRIMARY KEY (id);


--
-- TOC entry 5512 (class 2606 OID 60400)
-- Name: seg_lineas_excepciones seg_lineas_excepciones_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_excepciones
    ADD CONSTRAINT seg_lineas_excepciones_pkey PRIMARY KEY (id);


--
-- TOC entry 5507 (class 2606 OID 60364)
-- Name: seg_lineas_logs seg_lineas_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_logs
    ADD CONSTRAINT seg_lineas_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 5509 (class 2606 OID 60380)
-- Name: seg_lineas_permisos seg_lineas_permisos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_permisos
    ADD CONSTRAINT seg_lineas_permisos_pkey PRIMARY KEY (nivel, submodulo, accion);


--
-- TOC entry 5520 (class 2606 OID 60456)
-- Name: seg_lineas_politicas seg_lineas_politicas_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_politicas
    ADD CONSTRAINT seg_lineas_politicas_pkey PRIMARY KEY (clave);


--
-- TOC entry 5522 (class 2606 OID 60467)
-- Name: seg_lineas_rutas seg_lineas_rutas_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.seg_lineas_rutas
    ADD CONSTRAINT seg_lineas_rutas_pkey PRIMARY KEY (ruta);


--
-- TOC entry 5528 (class 2606 OID 60508)
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_pkey PRIMARY KEY (id);


--
-- TOC entry 5459 (class 2606 OID 51885)
-- Name: system_audit_log system_audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.system_audit_log
    ADD CONSTRAINT system_audit_log_pkey PRIMARY KEY (id);


--
-- TOC entry 5461 (class 2606 OID 51887)
-- Name: telemetria_cache telemetria_cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.telemetria_cache
    ADD CONSTRAINT telemetria_cache_pkey PRIMARY KEY (id);


--
-- TOC entry 5463 (class 2606 OID 51889)
-- Name: tipo_recurso tipo_recurso_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_nombre_key UNIQUE (nombre);


--
-- TOC entry 5465 (class 2606 OID 51891)
-- Name: tipo_recurso tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_pkey PRIMARY KEY (id);


--
-- TOC entry 5467 (class 2606 OID 51893)
-- Name: tipo_tutor tipo_tutor_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_nombre_key UNIQUE (nombre);


--
-- TOC entry 5469 (class 2606 OID 51895)
-- Name: tipo_tutor tipo_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_pkey PRIMARY KEY (id);


--
-- TOC entry 5472 (class 2606 OID 51897)
-- Name: trayectos trayectos_pkey; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_pkey PRIMARY KEY (id);


--
-- TOC entry 5476 (class 2606 OID 51899)
-- Name: tutores tutores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_cedula_key UNIQUE (cedula);


--
-- TOC entry 5478 (class 2606 OID 51901)
-- Name: tutores tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_pkey PRIMARY KEY (id);


--
-- TOC entry 5428 (class 2606 OID 51903)
-- Name: privilegios unique_nivel_privilegio; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT unique_nivel_privilegio UNIQUE (nivel_privilegio);


--
-- TOC entry 5424 (class 2606 OID 51905)
-- Name: postulaciones_estudiantes unique_postulacion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT unique_postulacion UNIQUE (id_investigacion, id_estudiante);


--
-- TOC entry 5474 (class 2606 OID 51907)
-- Name: trayectos uq_carrera_trayecto; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT uq_carrera_trayecto UNIQUE (id_carrera, numero);


--
-- TOC entry 5495 (class 2606 OID 52157)
-- Name: profesores_pst uq_profesores_pst; Type: CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT uq_profesores_pst UNIQUE (id_profesor, trayecto, trimestre);


--
-- TOC entry 5480 (class 2606 OID 51909)
-- Name: usuarios usuarios_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cedula_key UNIQUE (cedula);


--
-- TOC entry 5482 (class 2606 OID 51911)
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- TOC entry 5484 (class 2606 OID 51913)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 5486 (class 2606 OID 51915)
-- Name: visitantes visitantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes
    ADD CONSTRAINT visitantes_pkey PRIMARY KEY (id);


--
-- TOC entry 5488 (class 2606 OID 51917)
-- Name: waf_rate_limiter waf_rate_limiter_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.waf_rate_limiter
    ADD CONSTRAINT waf_rate_limiter_pkey PRIMARY KEY (ip, tipo);


--
-- TOC entry 5545 (class 1259 OID 68860)
-- Name: idx_analitica_snapshots_fecha; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_analitica_snapshots_fecha ON public.analitica_snapshots USING btree (fecha_snapshot DESC);


--
-- TOC entry 5535 (class 1259 OID 60559)
-- Name: idx_cursos_dimensiones_curso; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_cursos_dimensiones_curso ON public.cursos_dimensiones USING btree (id_curso);


--
-- TOC entry 5536 (class 1259 OID 60560)
-- Name: idx_cursos_dimensiones_dimension; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_cursos_dimensiones_dimension ON public.cursos_dimensiones USING btree (id_dimension);


--
-- TOC entry 5531 (class 1259 OID 60539)
-- Name: idx_cursos_lineas_curso; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_cursos_lineas_curso ON public.cursos_lineas USING btree (id_curso);


--
-- TOC entry 5532 (class 1259 OID 60540)
-- Name: idx_cursos_lineas_linea; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_cursos_lineas_linea ON public.cursos_lineas USING btree (id_linea);


--
-- TOC entry 5393 (class 1259 OID 51918)
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- TOC entry 5434 (class 1259 OID 51919)
-- Name: idx_pcv_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_carrera ON public.proyecto_carreras_vinculadas USING btree (id_carrera);


--
-- TOC entry 5435 (class 1259 OID 51920)
-- Name: idx_pcv_recurso; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_pcv_recurso ON public.proyecto_carreras_vinculadas USING btree (id_recurso);


--
-- TOC entry 5420 (class 1259 OID 52174)
-- Name: idx_postulaciones_profesor_pst; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_postulaciones_profesor_pst ON public.postulaciones_estudiantes USING btree (id_profesor_pst);


--
-- TOC entry 5546 (class 1259 OID 60702)
-- Name: idx_prof_dim_linea; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_prof_dim_linea ON public.profesor_dimension USING btree (id_linea);


--
-- TOC entry 5547 (class 1259 OID 60701)
-- Name: idx_prof_dim_usuario; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_prof_dim_usuario ON public.profesor_dimension USING btree (id_usuario);


--
-- TOC entry 5496 (class 1259 OID 52201)
-- Name: idx_profesor_linea_linea; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_profesor_linea_linea ON public.profesor_linea USING btree (id_linea);


--
-- TOC entry 5491 (class 1259 OID 52163)
-- Name: idx_profesores_pst_tt; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_profesores_pst_tt ON public.profesores_pst USING btree (trayecto, trimestre);


--
-- TOC entry 5429 (class 1259 OID 52182)
-- Name: idx_propuestas_empresa_eliminado_en; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_propuestas_empresa_eliminado_en ON public.propuestas_empresa USING btree (eliminado_en);


--
-- TOC entry 5444 (class 1259 OID 51921)
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- TOC entry 5445 (class 1259 OID 51922)
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


--
-- TOC entry 5516 (class 1259 OID 60443)
-- Name: idx_seg_lineas_alertas_estado; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_alertas_estado ON public.seg_lineas_alertas USING btree (estado, fecha);


--
-- TOC entry 5513 (class 1259 OID 60422)
-- Name: idx_seg_lineas_bloq_ip; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_bloq_ip ON public.seg_lineas_bloqueos USING btree (ip, activo);


--
-- TOC entry 5510 (class 1259 OID 60401)
-- Name: idx_seg_lineas_exc_usuario; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_exc_usuario ON public.seg_lineas_excepciones USING btree (usuario_id);


--
-- TOC entry 5503 (class 1259 OID 60365)
-- Name: idx_seg_lineas_logs_fecha; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_logs_fecha ON public.seg_lineas_logs USING btree (fecha);


--
-- TOC entry 5504 (class 1259 OID 60366)
-- Name: idx_seg_lineas_logs_ip_res; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_logs_ip_res ON public.seg_lineas_logs USING btree (ip, resultado, fecha);


--
-- TOC entry 5505 (class 1259 OID 60367)
-- Name: idx_seg_lineas_logs_usuario; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_seg_lineas_logs_usuario ON public.seg_lineas_logs USING btree (usuario_id, fecha);


--
-- TOC entry 5525 (class 1259 OID 60520)
-- Name: idx_solicitudes_pst_estado; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_solicitudes_pst_estado ON public.solicitudes_docentes_pst USING btree (estado);


--
-- TOC entry 5526 (class 1259 OID 60519)
-- Name: idx_solicitudes_pst_prof; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_solicitudes_pst_prof ON public.solicitudes_docentes_pst USING btree (id_profesor);


--
-- TOC entry 5470 (class 1259 OID 51923)
-- Name: idx_trayectos_carrera; Type: INDEX; Schema: public; Owner: miki
--

CREATE INDEX idx_trayectos_carrera ON public.trayectos USING btree (id_carrera);


--
-- TOC entry 5606 (class 2620 OID 51924)
-- Name: recursos tg_auditoria_recursos_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_delete BEFORE DELETE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- TOC entry 5607 (class 2620 OID 51925)
-- Name: recursos tg_auditoria_recursos_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_insert AFTER INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- TOC entry 5608 (class 2620 OID 51926)
-- Name: usuarios tg_auditoria_usuarios_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_delete BEFORE DELETE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5609 (class 2620 OID 51927)
-- Name: usuarios tg_auditoria_usuarios_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_insert AFTER INSERT ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5610 (class 2620 OID 51928)
-- Name: usuarios tg_auditoria_usuarios_update; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_update AFTER UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5550 (class 2606 OID 51929)
-- Name: accesos_recursos accesos_recursos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5551 (class 2606 OID 51934)
-- Name: accesos_recursos accesos_recursos_id_registro_actividad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_registro_actividad_fkey FOREIGN KEY (id_registro_actividad) REFERENCES public.registro_actividad(id) ON DELETE CASCADE;


--
-- TOC entry 5552 (class 2606 OID 51939)
-- Name: auditoria auditoria_usuario_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_usuario_responsable_fkey FOREIGN KEY (usuario_responsable) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5599 (class 2606 OID 60549)
-- Name: cursos_dimensiones cursos_dimensiones_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- TOC entry 5600 (class 2606 OID 60554)
-- Name: cursos_dimensiones cursos_dimensiones_id_dimension_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_dimensiones
    ADD CONSTRAINT cursos_dimensiones_id_dimension_fkey FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE;


--
-- TOC entry 5553 (class 2606 OID 51944)
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5597 (class 2606 OID 60529)
-- Name: cursos_lineas cursos_lineas_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- TOC entry 5598 (class 2606 OID 60534)
-- Name: cursos_lineas cursos_lineas_id_linea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_lineas
    ADD CONSTRAINT cursos_lineas_id_linea_fkey FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5594 (class 2606 OID 60483)
-- Name: cursos_preinscripciones cursos_preinscripciones_id_curso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.cursos_preinscripciones
    ADD CONSTRAINT cursos_preinscripciones_id_curso_fkey FOREIGN KEY (id_curso) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- TOC entry 5554 (class 2606 OID 51949)
-- Name: detalles_articulos detalles_revistas_id_editorial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_editorial_fkey FOREIGN KEY (id_editorial) REFERENCES public.editoriales(id) ON DELETE SET NULL;


--
-- TOC entry 5555 (class 2606 OID 51954)
-- Name: detalles_articulos detalles_revistas_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5556 (class 2606 OID 51959)
-- Name: detalles_investigaciones fk_detalles_investigaciones_ofertada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_ofertada FOREIGN KEY (id_investigacion_ofertada) REFERENCES public.investigaciones_ofertadas(id) ON DELETE SET NULL;


--
-- TOC entry 5557 (class 2606 OID 51964)
-- Name: detalles_investigaciones fk_detalles_investigaciones_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5558 (class 2606 OID 51969)
-- Name: dimensiones_operativas fk_dimension_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT fk_dimension_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5578 (class 2606 OID 51974)
-- Name: recurso_clasificaciones fk_dimension_operativa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_dimension_operativa FOREIGN KEY (id_dimension_operativa) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- TOC entry 5581 (class 2606 OID 51979)
-- Name: recurso_etiquetas fk_etiqueta_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_etiqueta_recurso FOREIGN KEY (id_etiqueta) REFERENCES public.etiquetas(id) ON DELETE CASCADE;


--
-- TOC entry 5593 (class 2606 OID 52226)
-- Name: investigaciones_dimensiones fk_inv_dim_inv; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigaciones_dimensiones
    ADD CONSTRAINT fk_inv_dim_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- TOC entry 5560 (class 2606 OID 51984)
-- Name: investigaciones_ofertadas fk_inv_dimension; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_dimension FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- TOC entry 5591 (class 2606 OID 52209)
-- Name: investigaciones_lineas fk_inv_lin_inv; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT fk_inv_lin_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- TOC entry 5592 (class 2606 OID 52214)
-- Name: investigaciones_lineas fk_inv_lin_lin; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.investigaciones_lineas
    ADD CONSTRAINT fk_inv_lin_lin FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5561 (class 2606 OID 51989)
-- Name: investigaciones_ofertadas fk_inv_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE RESTRICT;


--
-- TOC entry 5562 (class 2606 OID 51994)
-- Name: investigaciones_ofertadas fk_inv_profesor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_profesor FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5601 (class 2606 OID 60602)
-- Name: recurso_investigadores fk_investigador; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_investigador FOREIGN KEY (id_investigador) REFERENCES public.investigadores(id) ON DELETE CASCADE;


--
-- TOC entry 5579 (class 2606 OID 51999)
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5570 (class 2606 OID 52004)
-- Name: proyecto_carreras_vinculadas fk_pcv_carrera; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_carrera FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- TOC entry 5571 (class 2606 OID 52009)
-- Name: proyecto_carreras_vinculadas fk_pcv_recurso; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.proyecto_carreras_vinculadas
    ADD CONSTRAINT fk_pcv_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5589 (class 2606 OID 52196)
-- Name: profesor_linea fk_pl_linea; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT fk_pl_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5590 (class 2606 OID 52191)
-- Name: profesor_linea fk_pl_usuario; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_linea
    ADD CONSTRAINT fk_pl_usuario FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5565 (class 2606 OID 52014)
-- Name: postulaciones_estudiantes fk_postulacion_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5566 (class 2606 OID 52019)
-- Name: postulaciones_estudiantes fk_postulacion_inv; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- TOC entry 5567 (class 2606 OID 52166)
-- Name: postulaciones_estudiantes fk_postulacion_profesor_pst; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_profesor_pst FOREIGN KEY (id_profesor_pst) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5588 (class 2606 OID 52158)
-- Name: profesores_pst fk_profesores_pst_usuario; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesores_pst
    ADD CONSTRAINT fk_profesores_pst_usuario FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5569 (class 2606 OID 52177)
-- Name: propuestas_empresa fk_propuestas_archivado_por; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT fk_propuestas_archivado_por FOREIGN KEY (archivado_por) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5580 (class 2606 OID 52024)
-- Name: recurso_clasificaciones fk_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5582 (class 2606 OID 52029)
-- Name: recurso_etiquetas fk_recurso_etiqueta; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_recurso_etiqueta FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5602 (class 2606 OID 60597)
-- Name: recurso_investigadores fk_recurso_inv; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.recurso_investigadores
    ADD CONSTRAINT fk_recurso_inv FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5559 (class 2606 OID 52034)
-- Name: historico_versiones_pst fk_version_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5563 (class 2606 OID 52039)
-- Name: lineas_investigacion lineas_investigacion_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- TOC entry 5564 (class 2606 OID 52044)
-- Name: notificaciones notificaciones_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5568 (class 2606 OID 52049)
-- Name: preferencias_usuario preferencias_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5603 (class 2606 OID 60691)
-- Name: profesor_dimension profesor_dimension_id_dimension_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_dimension_fkey FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE CASCADE;


--
-- TOC entry 5604 (class 2606 OID 60696)
-- Name: profesor_dimension profesor_dimension_id_linea_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_linea_fkey FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5605 (class 2606 OID 60686)
-- Name: profesor_dimension profesor_dimension_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.profesor_dimension
    ADD CONSTRAINT profesor_dimension_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5572 (class 2606 OID 52054)
-- Name: proyecto_tutores proyecto_tutores_id_tutor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_tutor_fkey FOREIGN KEY (id_tutor) REFERENCES public.tutores(id) ON DELETE CASCADE;


--
-- TOC entry 5573 (class 2606 OID 52059)
-- Name: proyecto_tutores proyecto_tutores_tipo_tutor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey FOREIGN KEY (tipo_tutor_id) REFERENCES public.tipo_tutor(id) ON DELETE SET NULL;


--
-- TOC entry 5574 (class 2606 OID 52064)
-- Name: recurso_autores recurso_autores_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES public.autores(id) ON DELETE CASCADE;


--
-- TOC entry 5575 (class 2606 OID 52069)
-- Name: recurso_autores recurso_autores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5576 (class 2606 OID 52074)
-- Name: recurso_categorias recurso_categorias_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE CASCADE;


--
-- TOC entry 5577 (class 2606 OID 52079)
-- Name: recurso_categorias recurso_categorias_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5583 (class 2606 OID 52084)
-- Name: recursos recursos_id_tipo_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_id_tipo_recurso_fkey FOREIGN KEY (id_tipo_recurso) REFERENCES public.tipo_recurso(id) ON DELETE RESTRICT;


--
-- TOC entry 5584 (class 2606 OID 52089)
-- Name: registro_actividad registro_actividad_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5585 (class 2606 OID 52094)
-- Name: registro_actividad registro_actividad_id_visitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_visitante_fkey FOREIGN KEY (id_visitante) REFERENCES public.visitantes(id) ON DELETE SET NULL;


--
-- TOC entry 5595 (class 2606 OID 60509)
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_id_profesor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_id_profesor_fkey FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5596 (class 2606 OID 60514)
-- Name: solicitudes_docentes_pst solicitudes_docentes_pst_id_revisor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.solicitudes_docentes_pst
    ADD CONSTRAINT solicitudes_docentes_pst_id_revisor_fkey FOREIGN KEY (id_revisor) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5586 (class 2606 OID 52099)
-- Name: trayectos trayectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: miki
--

ALTER TABLE ONLY public.trayectos
    ADD CONSTRAINT trayectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- TOC entry 5587 (class 2606 OID 52104)
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


-- Completed on 2026-11-02 13:34:48

--
-- PostgreSQL database dump complete
--

\unrestrict ZQwHmG6IRv6QHD0aotmucgIPcJYkmFKQdgIPLaw6V3MgauinYY9uTPsRkapK8Bl

