--
-- PostgreSQL database dump
--

\restrict jVeyHc51CPzX4B5uLIBIuaf2yh8S64o4P10rSahkBbDpkd6DdkaUsQrPoUA35fg

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-08-20 13:45:09

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
-- TOC entry 909 (class 1247 OID 16390)
-- Name: accion_acceso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_acceso_enum AS ENUM (
    'visualizacion',
    'descarga'
);


ALTER TYPE public.accion_acceso_enum OWNER TO postgres;

--
-- TOC entry 912 (class 1247 OID 16396)
-- Name: accion_auditoria_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.accion_auditoria_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE'
);


ALTER TYPE public.accion_auditoria_enum OWNER TO postgres;

--
-- TOC entry 915 (class 1247 OID 16404)
-- Name: estado_curso_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_curso_enum AS ENUM (
    'borrador',
    'publicado',
    'archivado'
);


ALTER TYPE public.estado_curso_enum OWNER TO postgres;

--
-- TOC entry 1023 (class 1247 OID 16993)
-- Name: estado_propuesta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.estado_propuesta_enum AS ENUM (
    'pendiente',
    'aceptada',
    'rechazada'
);


ALTER TYPE public.estado_propuesta_enum OWNER TO postgres;

--
-- TOC entry 918 (class 1247 OID 16412)
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
-- TOC entry 921 (class 1247 OID 16424)
-- Name: tipo_interaccion_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_enum AS ENUM (
    'like',
    'bookmark'
);


ALTER TYPE public.tipo_interaccion_enum OWNER TO postgres;

--
-- TOC entry 924 (class 1247 OID 16430)
-- Name: tipo_interaccion_usuario_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_interaccion_usuario_enum AS ENUM (
    'like',
    'guardado'
);


ALTER TYPE public.tipo_interaccion_usuario_enum OWNER TO postgres;

--
-- TOC entry 927 (class 1247 OID 16436)
-- Name: tipo_pregunta_enum; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.tipo_pregunta_enum AS ENUM (
    'multiple',
    'v_f',
    'corta'
);


ALTER TYPE public.tipo_pregunta_enum OWNER TO postgres;

--
-- TOC entry 274 (class 1255 OID 16443)
-- Name: fn_auditoria_recursos(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_auditoria_recursos() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    -- Intenta leer una variable de sesión configurada por el backend, si no hay, asigna NULL
    v_usuario_actual INT := NULLIF(current_setting('app.usuario_actual', true), '')::INT; 
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', OLD.id, 'DELETE', v_usuario_actual, jsonb_build_object('titulo', OLD.titulo, 'id_tipo_recurso', OLD.id_tipo_recurso), NULL, CURRENT_TIMESTAMP);
        RETURN OLD;
        
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO auditoria (tabla_afectada, id_registro, accion, usuario_responsable, datos_anteriores, datos_nuevos, fecha_hora)
        VALUES ('recursos', NEW.id, 'INSERT', v_usuario_actual, NULL, jsonb_build_object('titulo', NEW.titulo, 'id_tipo_recurso', NEW.id_tipo_recurso, 'ejemplares_totales', NEW.ejemplares_totales), CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    
    RETURN NULL;
END;
$$;


ALTER FUNCTION public.fn_auditoria_recursos() OWNER TO postgres;

--
-- TOC entry 286 (class 1255 OID 16444)
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
-- TOC entry 287 (class 1255 OID 16445)
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
-- TOC entry 219 (class 1259 OID 16446)
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
-- TOC entry 220 (class 1259 OID 16454)
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
-- TOC entry 5425 (class 0 OID 0)
-- Dependencies: 220
-- Name: accesos_recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.accesos_recursos_id_seq OWNED BY public.accesos_recursos.id;


--
-- TOC entry 221 (class 1259 OID 16455)
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
-- TOC entry 222 (class 1259 OID 16465)
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
-- TOC entry 5426 (class 0 OID 0)
-- Dependencies: 222
-- Name: auditoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.auditoria_id_seq OWNED BY public.auditoria.id;


--
-- TOC entry 223 (class 1259 OID 16466)
-- Name: autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.autores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.autores OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16471)
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
-- TOC entry 5427 (class 0 OID 0)
-- Dependencies: 224
-- Name: autores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.autores_id_seq OWNED BY public.autores.id;


--
-- TOC entry 225 (class 1259 OID 16472)
-- Name: carreras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carreras (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text
);


ALTER TABLE public.carreras OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16479)
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
-- TOC entry 5428 (class 0 OID 0)
-- Dependencies: 226
-- Name: carreras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carreras_id_seq OWNED BY public.carreras.id;


--
-- TOC entry 227 (class 1259 OID 16480)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16485)
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
-- TOC entry 5429 (class 0 OID 0)
-- Dependencies: 228
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- TOC entry 229 (class 1259 OID 16486)
-- Name: cursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cursos (
    id integer NOT NULL,
    id_docente integer NOT NULL,
    titulo character varying(255) NOT NULL,
    descripcion text,
    imagen_portada character varying(255),
    estado public.estado_curso_enum DEFAULT 'borrador'::public.estado_curso_enum NOT NULL,
    nota_minima_aprobacion numeric(5,2) DEFAULT 70.00 NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cursos OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16500)
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
-- TOC entry 5430 (class 0 OID 0)
-- Dependencies: 230
-- Name: cursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cursos_id_seq OWNED BY public.cursos.id;


--
-- TOC entry 231 (class 1259 OID 16501)
-- Name: detalles_articulos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalles_articulos (
    id_recurso integer CONSTRAINT detalles_revistas_id_recurso_not_null NOT NULL,
    id_editorial integer,
    volumen character varying(50),
    numero character varying(50),
    issn character varying(20),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    imagen_portada character varying(255) DEFAULT 'default_article.jpg'::character varying,
    resumen text
);


ALTER TABLE public.detalles_articulos OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16509)
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
-- TOC entry 233 (class 1259 OID 16518)
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
    id_investigacion_padre integer
);


ALTER TABLE public.detalles_proyectos OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16526)
-- Name: dimensiones_operativas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dimensiones_operativas (
    id integer NOT NULL,
    id_linea integer NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text
);


ALTER TABLE public.dimensiones_operativas OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16534)
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
-- TOC entry 5431 (class 0 OID 0)
-- Dependencies: 235
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dimensiones_operativas_id_seq OWNED BY public.dimensiones_operativas.id;


--
-- TOC entry 236 (class 1259 OID 16535)
-- Name: editoriales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.editoriales (
    id integer NOT NULL,
    nombre character varying(150) NOT NULL
);


ALTER TABLE public.editoriales OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 16540)
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
-- TOC entry 5432 (class 0 OID 0)
-- Dependencies: 237
-- Name: editoriales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.editoriales_id_seq OWNED BY public.editoriales.id;


--
-- TOC entry 238 (class 1259 OID 16541)
-- Name: etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.etiquetas (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    color_hex character varying(7) DEFAULT '#0ea5e9'::character varying
);


ALTER TABLE public.etiquetas OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 16547)
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
-- TOC entry 5433 (class 0 OID 0)
-- Dependencies: 239
-- Name: etiquetas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.etiquetas_id_seq OWNED BY public.etiquetas.id;


--
-- TOC entry 240 (class 1259 OID 16548)
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
    CONSTRAINT investigaciones_ofertadas_estado_check CHECK (((estado)::text = ANY (ARRAY[('Abierta'::character varying)::text, ('Cerrada'::character varying)::text, ('En Desarrollo'::character varying)::text, ('Finalizada'::character varying)::text])))
);


ALTER TABLE public.investigaciones_ofertadas OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 16563)
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
-- TOC entry 5434 (class 0 OID 0)
-- Dependencies: 241
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.investigaciones_ofertadas_id_seq OWNED BY public.investigaciones_ofertadas.id;


--
-- TOC entry 242 (class 1259 OID 16564)
-- Name: lineas_investigacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lineas_investigacion (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL,
    id_carrera integer NOT NULL,
    descripcion text
);


ALTER TABLE public.lineas_investigacion OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 16572)
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
-- TOC entry 5435 (class 0 OID 0)
-- Dependencies: 243
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lineas_investigacion_id_seq OWNED BY public.lineas_investigacion.id;


--
-- TOC entry 244 (class 1259 OID 16573)
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
-- TOC entry 245 (class 1259 OID 16582)
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
-- TOC entry 5436 (class 0 OID 0)
-- Dependencies: 245
-- Name: notificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_id_seq OWNED BY public.notificaciones.id;


--
-- TOC entry 246 (class 1259 OID 16583)
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
    CONSTRAINT postulaciones_estudiantes_estado_check CHECK (((estado)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Aceptado'::character varying)::text, ('Rechazado'::character varying)::text])))
);


ALTER TABLE public.postulaciones_estudiantes OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 16594)
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
-- TOC entry 5437 (class 0 OID 0)
-- Dependencies: 247
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.postulaciones_estudiantes_id_seq OWNED BY public.postulaciones_estudiantes.id;


--
-- TOC entry 248 (class 1259 OID 16595)
-- Name: preferencias_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.preferencias_usuario (
    id_usuario integer NOT NULL,
    tema character varying(50) DEFAULT 'light'::character varying,
    notificaciones_sistema boolean DEFAULT true
);


ALTER TABLE public.preferencias_usuario OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 16601)
-- Name: privilegios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios (
    privilegio_id integer NOT NULL,
    nivel_privilegio integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.privilegios OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 16607)
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
-- TOC entry 5438 (class 0 OID 0)
-- Dependencies: 250
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.privilegios_privilegio_id_seq OWNED BY public.privilegios.privilegio_id;


--
-- TOC entry 273 (class 1259 OID 17000)
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
    nivel_trayecto character varying(50)
);


ALTER TABLE public.propuestas_empresa OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 16999)
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
-- TOC entry 5439 (class 0 OID 0)
-- Dependencies: 272
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.propuestas_empresa_id_seq OWNED BY public.propuestas_empresa.id;


--
-- TOC entry 251 (class 1259 OID 16608)
-- Name: proyecto_tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proyecto_tutores (
    id_recurso integer NOT NULL,
    id_tutor integer NOT NULL,
    tipo_tutor_id integer
);


ALTER TABLE public.proyecto_tutores OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 16613)
-- Name: recurso_autores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_autores (
    id_recurso integer NOT NULL,
    id_autor integer NOT NULL
);


ALTER TABLE public.recurso_autores OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 16618)
-- Name: recurso_categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_categorias (
    id_recurso integer NOT NULL,
    id_categoria integer NOT NULL
);


ALTER TABLE public.recurso_categorias OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 16623)
-- Name: recurso_clasificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_clasificaciones (
    id_recurso integer NOT NULL,
    id_linea_investigacion integer NOT NULL,
    id_dimension_operativa integer
);


ALTER TABLE public.recurso_clasificaciones OWNER TO postgres;

--
-- TOC entry 255 (class 1259 OID 16628)
-- Name: recurso_etiquetas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recurso_etiquetas (
    id_recurso integer NOT NULL,
    id_etiqueta integer NOT NULL
);


ALTER TABLE public.recurso_etiquetas OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 16633)
-- Name: recursos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.recursos (
    id integer NOT NULL,
    titulo character varying(255) NOT NULL,
    id_tipo_recurso integer NOT NULL,
    anio_publicacion integer,
    ejemplares_totales integer DEFAULT 1,
    ejemplares_disponibles integer DEFAULT 1,
    archivo_pdf character varying(255)
);


ALTER TABLE public.recursos OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 16643)
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
-- TOC entry 5440 (class 0 OID 0)
-- Dependencies: 257
-- Name: recursos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.recursos_id_seq OWNED BY public.recursos.id;


--
-- TOC entry 258 (class 1259 OID 16644)
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
-- TOC entry 259 (class 1259 OID 16651)
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
-- TOC entry 5441 (class 0 OID 0)
-- Dependencies: 259
-- Name: registro_actividad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registro_actividad_id_seq OWNED BY public.registro_actividad.id;


--
-- TOC entry 260 (class 1259 OID 16652)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    privilegio_id integer DEFAULT 1 CONSTRAINT roles_privilegios_id_not_null NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 261 (class 1259 OID 16659)
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
-- TOC entry 5442 (class 0 OID 0)
-- Dependencies: 261
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 262 (class 1259 OID 16660)
-- Name: tipo_recurso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_recurso (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_recurso OWNER TO postgres;

--
-- TOC entry 263 (class 1259 OID 16667)
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
-- TOC entry 5443 (class 0 OID 0)
-- Dependencies: 263
-- Name: tipo_recurso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_recurso_id_seq OWNED BY public.tipo_recurso.id;


--
-- TOC entry 264 (class 1259 OID 16668)
-- Name: tipo_tutor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_tutor (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text
);


ALTER TABLE public.tipo_tutor OWNER TO postgres;

--
-- TOC entry 265 (class 1259 OID 16675)
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
-- TOC entry 5444 (class 0 OID 0)
-- Dependencies: 265
-- Name: tipo_tutor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_tutor_id_seq OWNED BY public.tipo_tutor.id;


--
-- TOC entry 266 (class 1259 OID 16676)
-- Name: tutores; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutores (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    cedula character varying(20)
);


ALTER TABLE public.tutores OWNER TO postgres;

--
-- TOC entry 267 (class 1259 OID 16681)
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
-- TOC entry 5445 (class 0 OID 0)
-- Dependencies: 267
-- Name: tutores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tutores_id_seq OWNED BY public.tutores.id;


--
-- TOC entry 268 (class 1259 OID 16682)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nombre_completo character varying(150) NOT NULL,
    email character varying(100),
    cedula character varying(20),
    contrasena character varying(255),
    id_rol integer,
    activo boolean DEFAULT true
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 269 (class 1259 OID 16690)
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
-- TOC entry 5446 (class 0 OID 0)
-- Dependencies: 269
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- TOC entry 270 (class 1259 OID 16691)
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
-- TOC entry 271 (class 1259 OID 16698)
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
-- TOC entry 5447 (class 0 OID 0)
-- Dependencies: 271
-- Name: visitantes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.visitantes_id_seq OWNED BY public.visitantes.id;


--
-- TOC entry 5029 (class 2604 OID 16699)
-- Name: accesos_recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos ALTER COLUMN id SET DEFAULT nextval('public.accesos_recursos_id_seq'::regclass);


--
-- TOC entry 5032 (class 2604 OID 16700)
-- Name: auditoria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria ALTER COLUMN id SET DEFAULT nextval('public.auditoria_id_seq'::regclass);


--
-- TOC entry 5034 (class 2604 OID 16701)
-- Name: autores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores ALTER COLUMN id SET DEFAULT nextval('public.autores_id_seq'::regclass);


--
-- TOC entry 5035 (class 2604 OID 16702)
-- Name: carreras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras ALTER COLUMN id SET DEFAULT nextval('public.carreras_id_seq'::regclass);


--
-- TOC entry 5036 (class 2604 OID 16703)
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- TOC entry 5037 (class 2604 OID 16704)
-- Name: cursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos ALTER COLUMN id SET DEFAULT nextval('public.cursos_id_seq'::regclass);


--
-- TOC entry 5047 (class 2604 OID 16705)
-- Name: dimensiones_operativas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas ALTER COLUMN id SET DEFAULT nextval('public.dimensiones_operativas_id_seq'::regclass);


--
-- TOC entry 5048 (class 2604 OID 16706)
-- Name: editoriales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales ALTER COLUMN id SET DEFAULT nextval('public.editoriales_id_seq'::regclass);


--
-- TOC entry 5049 (class 2604 OID 16707)
-- Name: etiquetas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas ALTER COLUMN id SET DEFAULT nextval('public.etiquetas_id_seq'::regclass);


--
-- TOC entry 5051 (class 2604 OID 16708)
-- Name: investigaciones_ofertadas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas ALTER COLUMN id SET DEFAULT nextval('public.investigaciones_ofertadas_id_seq'::regclass);


--
-- TOC entry 5055 (class 2604 OID 16709)
-- Name: lineas_investigacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion ALTER COLUMN id SET DEFAULT nextval('public.lineas_investigacion_id_seq'::regclass);


--
-- TOC entry 5056 (class 2604 OID 16710)
-- Name: notificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_id_seq'::regclass);


--
-- TOC entry 5060 (class 2604 OID 16711)
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- TOC entry 5065 (class 2604 OID 16712)
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


--
-- TOC entry 5082 (class 2604 OID 17003)
-- Name: propuestas_empresa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa ALTER COLUMN id SET DEFAULT nextval('public.propuestas_empresa_id_seq'::regclass);


--
-- TOC entry 5067 (class 2604 OID 16713)
-- Name: recursos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos ALTER COLUMN id SET DEFAULT nextval('public.recursos_id_seq'::regclass);


--
-- TOC entry 5070 (class 2604 OID 16714)
-- Name: registro_actividad id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad ALTER COLUMN id SET DEFAULT nextval('public.registro_actividad_id_seq'::regclass);


--
-- TOC entry 5074 (class 2604 OID 16715)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 5076 (class 2604 OID 16716)
-- Name: tipo_recurso id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso ALTER COLUMN id SET DEFAULT nextval('public.tipo_recurso_id_seq'::regclass);


--
-- TOC entry 5077 (class 2604 OID 16717)
-- Name: tipo_tutor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor ALTER COLUMN id SET DEFAULT nextval('public.tipo_tutor_id_seq'::regclass);


--
-- TOC entry 5078 (class 2604 OID 16718)
-- Name: tutores id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores ALTER COLUMN id SET DEFAULT nextval('public.tutores_id_seq'::regclass);


--
-- TOC entry 5079 (class 2604 OID 16719)
-- Name: usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- TOC entry 5081 (class 2604 OID 16720)
-- Name: visitantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes ALTER COLUMN id SET DEFAULT nextval('public.visitantes_id_seq'::regclass);


--
-- TOC entry 5365 (class 0 OID 16446)
-- Dependencies: 219
-- Data for Name: accesos_recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.accesos_recursos (id, id_registro_actividad, id_recurso, accion, fecha_acceso) FROM stdin;
\.


--
-- TOC entry 5367 (class 0 OID 16455)
-- Dependencies: 221
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
123	recursos	61	INSERT	\N	\N	\N	{"titulo": "ASASDA", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 16:59:31.204976
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
120	recursos	58	INSERT	\N	\N	\N	{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 15:19:57.897052
121	recursos	59	INSERT	\N	\N	\N	{"titulo": "E", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 16:31:13.973946
122	recursos	60	INSERT	\N	\N	\N	{"titulo": "123", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 16:40:45.294611
124	recursos	21	DELETE	\N	\N	{"titulo": "La Bebecita Bebelin", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:00.506138
125	recursos	59	DELETE	\N	\N	{"titulo": "E", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:05.068211
126	recursos	61	DELETE	\N	\N	{"titulo": "Juan", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:07.154446
127	recursos	60	DELETE	\N	\N	{"titulo": "123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:08.869819
128	recursos	44	DELETE	\N	\N	{"titulo": "auuuu", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:10.961748
129	recursos	43	DELETE	\N	\N	{"titulo": "123123123123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:12.868199
130	recursos	42	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:14.485366
131	recursos	41	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:16.590235
132	recursos	40	DELETE	\N	\N	{"titulo": "123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:18.156327
133	recursos	39	DELETE	\N	\N	{"titulo": "123123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:19.628119
134	recursos	38	DELETE	\N	\N	{"titulo": "23", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:21.323411
135	recursos	37	DELETE	\N	\N	{"titulo": "23123", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:22.704036
136	recursos	36	DELETE	\N	\N	{"titulo": "Waos 3", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:25.469091
137	recursos	35	DELETE	\N	\N	{"titulo": "Waos 2", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:28.095958
138	recursos	34	DELETE	\N	\N	{"titulo": "Waos 1", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:33.17519
139	recursos	32	DELETE	\N	\N	{"titulo": "Manguagua", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:34.779212
140	recursos	31	DELETE	\N	\N	{"titulo": "Imitadora", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:36.557448
141	recursos	28	DELETE	\N	\N	{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:38.469427
142	recursos	27	DELETE	\N	\N	{"titulo": "Que la guagua", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:40.153464
143	recursos	25	DELETE	\N	\N	{"titulo": "Luisito comunicando", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:42.016396
144	recursos	24	DELETE	\N	\N	{"titulo": "Pepe", "id_tipo_recurso": 3}	\N	2026-07-09 20:02:43.469089
145	recursos	62	INSERT	\N	\N	\N	{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 20:08:48.117741
146	recursos	63	INSERT	\N	\N	\N	{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 20:13:49.50881
147	recursos	64	INSERT	\N	\N	\N	{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 20:16:51.643727
148	recursos	65	INSERT	\N	\N	\N	{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3, "ejemplares_totales": 1}	2026-07-09 20:19:28.855723
149	usuarios	12	INSERT	\N	\N	\N	{"email": "orlanndo@gmail.com", "id_rol": 1, "nombre": "mando"}	2026-07-09 22:44:06.213847
150	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "mando"}	{"activo": true, "id_rol": 1, "nombre": "mando"}	2026-07-09 22:45:11.322849
151	usuarios	12	UPDATE	\N	\N	{"activo": true, "id_rol": 1, "nombre": "mando"}	{"activo": true, "id_rol": 2, "nombre": "mando"}	2026-07-09 22:52:03.914865
152	usuarios	12	DELETE	\N	\N	{"email": "orlanndo@gmail.com", "id_rol": 2, "nombre": "mando"}	\N	2026-07-09 22:52:03.914865
153	usuarios	13	INSERT	\N	\N	\N	{"email": "lando1609721@gmail.com", "id_rol": 3, "nombre": "landorus"}	2026-07-09 22:52:09.392899
154	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "landorus"}	{"activo": true, "id_rol": 2, "nombre": "landorus"}	2026-07-10 00:15:15.117147
155	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "landorus"}	{"activo": true, "id_rol": 3, "nombre": "landorus"}	2026-07-10 00:32:43.609875
156	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "landorus"}	{"activo": true, "id_rol": 2, "nombre": "landorus"}	2026-07-10 13:56:49.450118
157	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 2, "nombre": "landorus"}	{"activo": true, "id_rol": 3, "nombre": "landorus"}	2026-07-11 08:20:17.188625
158	usuarios	13	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "landorus"}	{"activo": true, "id_rol": 2, "nombre": "landorus"}	2026-07-11 08:29:49.150563
159	usuarios	14	INSERT	\N	\N	\N	{"email": "pipa1234@gmail.com", "id_rol": 3, "nombre": "Pipin"}	2026-08-20 13:12:32.533521
160	usuarios	14	UPDATE	\N	\N	{"activo": true, "id_rol": 3, "nombre": "Pipin"}	{"activo": true, "id_rol": 2, "nombre": "Pipin"}	2026-08-20 13:13:06.265487
\.


--
-- TOC entry 5369 (class 0 OID 16466)
-- Dependencies: 223
-- Data for Name: autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.autores (id, nombre_completo, cedula) FROM stdin;
1	Prof. Andrus	V-11223344
2	Estudiante Dev	V-27000111
3	Estudiante Electrónica	V-28000222
4	Juan Pérez	\N
5	María García	\N
6	Ing. Pedro Díaz	\N
7	Carlos López	\N
8	Ana Martínez	\N
9	Dra. Sofía Rojas	\N
14	Dr. Ramón Fuentes	V-10111213
15	Dra. Clara Vásquez	V-10222333
16	Ing. Luis Morelo	V-10333444
17	Prof. Yolanda Díaz	V-10444555
18	Ing. Pedro Ríos	V-10555666
19	Prof. Ana Suárez	V-10666777
20	Ángel Ferrer	V-27100001
21	Mariela Colón	V-27100002
22	Javier Navas	V-27100003
23	Luisa Paredes	V-27100004
24	Tomás Guerrero	V-27100005
25	Valentina Soto	V-27100006
26	Rodrigo Méndez	V-27100007
27	Gabriela López	V-27100008
28	Hernán Castro	V-27100009
29	Isabel Ramos	V-27100010
32	Fernando Carmino	V-12312313
30	Mariano Rajoy	V-9857492
31	Alejandro Alicante	V-12312391
33	Luis Enrique Morelos	E-5184865
34	Jesús Montilla	V-30866991
35	Luis Miguel	V-17855689
36	Fausto Hernandez	V-21314132
37	miki	V-1234
41	aaaa aaa aaa	2222222
42	Ricardo Dos Santos	V-24503215
43	Jose Aguilar-Castro	V-14584964
44	Taniana Rodríguez	V-18458789
45	Rafael Aldana	V-123123123
46	Rafiña	V-123123
13	Jose Alejandro Rojo	E-2323232
47	Andrés Felipe Sarmiento-Fernández	V-15487956
48	Dursun Barrios	V-17890100
49	Adriana de la Cruz-Uribe	E-1452145
50	Erika Escalante-Espinosa	E-1231231
51	José Roberto Hernández-Barajas	V-14515
52	José Ramón Laines-Canepa	E-124411
53	Piero Antonio Chávez-Malque	E-123123
54	Gérlin Milquito López-Meléndez	V-1314114
55	Edwin Olano-Inga	V-2151413
56	Sócrates Pedro Muñoz-Pérez	V-15151123
57	Karen Elizabeth Mora-Mora	V-1231315
58	David Santiago Sanchez-Garcia	V-1451515
59	Maria Paula Rodriguez-Alba	V-412414
60	Jhon Andres Gomez-Portilla	V-5414213
\.


--
-- TOC entry 5371 (class 0 OID 16472)
-- Dependencies: 225
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
-- TOC entry 5373 (class 0 OID 16480)
-- Dependencies: 227
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
2	ACEMA
13	Matemáticas
14	Literatura
15	Psicología
16	Economía
17	Contaduría
18	Ingeniería Civil
\.


--
-- TOC entry 5375 (class 0 OID 16486)
-- Dependencies: 229
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cursos (id, id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion, fecha_creacion, fecha_actualizacion) FROM stdin;
1	4	Introducción a la Metodología de la Investigación	Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.	\N	publicado	70.00	2026-04-03 03:28:04	2026-04-03 03:28:04
2	4	Fundamentos de Inteligencia Artificial	Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.	\N	publicado	70.00	2026-04-03 03:28:04	2026-04-03 03:28:04
3	1	Normas APA y Redacción Científica	Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.	\N	publicado	60.00	2026-04-03 03:28:04	2026-04-03 03:53:35
4	1	tamaños de jose	los pn que jose ha tenido segun tamaño	\N	publicado	70.00	2026-04-03 04:40:03	2026-04-03 04:40:03
\.


--
-- TOC entry 5377 (class 0 OID 16501)
-- Dependencies: 231
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_articulos (id_recurso, id_editorial, volumen, numero, issn, created_at, imagen_portada, resumen) FROM stdin;
58	5	87	213	0012-7353	2026-07-09 15:19:57.897052	https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png	Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato
63	8	93	241	0012-7353	2026-07-09 20:13:49.50881	art_1783642429_6a50393d74626.png	Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos
64	\N	93	241	0012-7353	2026-07-09 20:16:51.643727	art_1783642611_6a5039f396bec.png	La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada
65	8	93	241	0012-7353	2026-07-09 20:19:28.855723	art_1783642768_6a503a90ca313.png	La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.
62	8	93	241	0012-7353	2026-07-09 20:08:48.117741	art_1783642127_6a50380feed3b.png	La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.
\.


--
-- TOC entry 5378 (class 0 OID 16509)
-- Dependencies: 232
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_investigaciones (id_recurso, planteamiento_problema, objetivo_general, id_investigacion_ofertada, created_at) FROM stdin;
\.


--
-- TOC entry 5379 (class 0 OID 16518)
-- Dependencies: 233
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, resumen, id_carrera, comunidad_beneficiada, palabras_clave, created_at, id_investigacion_padre) FROM stdin;
45	2026-06-15	Pregrado	Dise¤o e implementaci¢n de un motor de renderizado ligero y de alto rendimiento. Se evit¢ el uso de frameworks pesados para garantizar una ejecuci¢n "metal pure", optimizando el consumo de RAM y CPU en equipos de bajos recursos.	1	Comunidad de Desarrolladores Independientes	Rust, Tauri, Novela Visual, Nativo, Optimizaci¢n	2026-07-05 17:21:44.350197	\N
46	2025-11-20	TSU	Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.	1	Estudiantes de Computaci¢n Gr fica	Lua, TIC-80, Retro, GameDev, M quina de Estados	2026-07-05 17:21:44.350197	\N
47	2026-07-02	Pregrado	Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.	1	Laboratorios de Arquitectura del Computador	Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy	2026-07-05 17:21:44.350197	\N
48	2026-05-10	Pregrado	Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.	1	Departamento de Sistemas de la Universidad	Microkernel, PHP, PostgreSQL, MVC, Arquitectura	2026-07-05 17:21:44.350197	\N
49	2026-03-15	Pregrado	Desarrollo de un sistema tradicional para optimizar los m‚todos y procedimientos del inventario m‚dico. Sigue un patr¢n arquitect¢nico modular para agilizar los procesos organizacionales.	1	Ambulatorio Urbano Tipo II	Sistemas de Informaci¢n, PostgreSQL, Gesti¢n, Inventario	2026-07-05 17:39:35.498485	\N
50	2026-04-22	Pregrado	Aplicaci¢n interactiva dise¤ada como medio did ctico para facilitar los procesos de ense¤anza. Combina fundamentos comunicacionales y l¢gicos mediante una interfaz interactiva de alto rendimiento.	1	µrea de Ciencias B sicas de la Instituci¢n	Edum tica, Software Educativo, Multimedia, µlgebra	2026-07-05 17:39:35.498485	\N
51	2025-07-10	TSU	Dise¤o de un sistema distribuido cooperativo entre clientes y un servidor centralizado. Permite la gesti¢n din mica de solicitudes concurrentes controlando de manera efectiva las peticiones HTTP contra la base de datos.	1	Coordinaci¢n de Control de Estudios	Web, Cliente-Servidor, PHP, PostgreSQL	2026-07-05 17:39:35.498485	\N
52	2026-06-18	Pregrado	Herramienta de simulaci¢n orientada al testeo preventivo de la transmisi¢n de datos. Permite modelar el comportamiento de las decisiones de routing antes de iniciar el despliegue f¡sico de una infraestructura de red.	1	Laboratorio de Redes y Telecomunicaciones	Simulaci¢n, Routing, Algoritmos, Redes, Topolog¡a	2026-07-05 17:39:35.498485	\N
57	2026-07-05	Pregrado	ahsdhajsdhahakjfhafggfjhgfkjh	1	asdasdasdasd	asdasdasdasdasd	2026-07-05 18:21:33.639701	\N
\.


--
-- TOC entry 5380 (class 0 OID 16526)
-- Dependencies: 234
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dimensiones_operativas (id, id_linea, nombre, descripcion) FROM stdin;
5	7	Sistemas de informaci¢n tradicionales	Est  constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e informaci¢n y programas y aplicaciones; que interact£an entre si dentro de una organizaci¢n con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.
6	7	Sistemas de informaci¢n con propiedades geogr ficas	Son sistemas que permiten evaluar propiedades geogr ficas de un entorno, generando informaci¢n referente a una entidad geogr fica desplegando im genes e informaci¢n en un hipermapa.
7	7	Sistemas de informaci¢n web	Son primeramente sistemas de informaci¢n que para su desarrollo se debe considerar la misma disciplina de construcci¢n de sistemas de informaci¢n no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van m s all  de ser un conjunto de p ginas Web.
8	7	Sistemas de informaci¢n colaborativos	Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribuci¢n de la informaci¢n en una organizaci¢n o entre organizaciones.
9	7	Gesti¢n tecnol¢gica	Procesos relacionados con la implantaci¢n de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos m¢dulos, adem s de comprobar el correcto funcionamiento de los componentes de un sistema de informaci¢n que puede abarcar auditor¡as, t‚cnicas de control, evaluaci¢n de la calidad.
10	8	Software educativo	Programas para el computador creados con la finalidad espec¡fica de ser utilizados como medio did ctico, es decir, para facilitar los procesos de ense¤anza y de aprendizaje. Combina conocimiento educacional, comunicacional e inform tico.
11	8	Gu¡as de estudio web	Representan un material instruccional utilizados para cursos de educaci¢n a distancia y como complemento a la educaci¢n presencial, lo cual provee una estructura para un curso.
12	8	Tutoriales	Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realizaci¢n de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.
13	8	Juegos did cticos	El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploraci¢n y expresi¢n, un instrumento para la organizaci¢n y aplicaci¢n de habilidades y, un factor de socializaci¢n e integraci¢n.
14	8	Entornos interactivos de ense¤anza	Proyectos donde el profesor y los alumnos se encuentran en lugares f¡sicamente distintos. El proceso de ense¤anza-aprendizaje se lleva a cabo a trav‚s de Internet, en cualquier momento y en cualquier lugar.
15	8	Sistemas e-learning	Programas que faciliten la creaci¢n, adopci¢n y distribuci¢n de contenidos, as¡ como la adaptaci¢n del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de l¡mites horarios o geogr ficos.
16	9	Aplicaciones cliente - servidor	Sistema distribuido entre m£ltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma m s adecuada.
17	9	Servicios de integraci¢n para aplicaciones web	Medio para exponer y hacer disponible la funcionalidad de los sistemas de informaci¢n mediante las tecnolog¡as est ndar Web, permitiendo reducci¢n de la heterogeneidad por uso de tecnolog¡as est ndar.
18	10	Simulaci¢n y herramientas de simulaci¢n	Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulaci¢n o test donde sea posible modelizar y probar el sistema que est  desarrollando. Reduce tiempo y chequea decisiones a priori.
19	10	Modelos de transmisi¢n de datos	Se discute la conceptualizaci¢n integral de un sistema de transmisi¢n desde un marco com£n a diferentes tecnolog¡as, tales como: sistemas de comunicaci¢n por cable, radio enlaces fijos, m¢viles y satelitales.
\.


--
-- TOC entry 5382 (class 0 OID 16535)
-- Dependencies: 236
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
2	ACM
\.


--
-- TOC entry 5384 (class 0 OID 16541)
-- Dependencies: 238
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.etiquetas (id, nombre, color_hex) FROM stdin;
1	Inteligencia Artificial	#0ea5e9
2	Machine Learning	#0ea5e9
3	Educación	#0ea5e9
4	Redes Neuronales	#0ea5e9
5	Desarrollo Web	#0ea5e9
8	Teoría Matemática	#0ea5e9
9	Modelo Económico	#0ea5e9
10	Construcción	#0ea5e9
\.


--
-- TOC entry 5386 (class 0 OID 16548)
-- Dependencies: 240
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.investigaciones_ofertadas (id, id_profesor, titulo, planteamiento_problema, objetivo_general, id_linea, id_dimension, cupos_disponibles, estado, fecha_creacion) FROM stdin;
1	7	Diseño de una arquitectura de bases de datos NoSQL 591	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-12-23 17:06:10
2	5	Propuesta de mejora para sistemas embebidos 932	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-07-08 17:06:10
3	6	Evaluación de rendimiento en bases de datos NoSQL 433	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-12-05 17:06:10
4	11	Análisis del impacto de redes neuronales 434	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-04-23 17:06:10
5	6	Desarrollo de un sistema gestión de inventarios 773	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-06-21 17:06:10
6	10	Diseño de una arquitectura de computación cuántica 511	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-05-28 17:06:10
7	2	Implementación de algoritmo de procesamiento de lenguaje natural 536	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-12-19 17:06:10
8	10	Análisis del impacto de redes neuronales 204	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-08-06 17:06:10
9	6	Propuesta de mejora para aplicaciones web distribuidas 440	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2024-10-18 17:06:10
10	6	Implementación de algoritmo de procesamiento de lenguaje natural 780	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-11-22 17:06:10
11	5	Propuesta de mejora para bases de datos NoSQL 245	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-12-06 17:06:10
12	1	Análisis del impacto de sistemas embebidos 984	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-01-19 17:06:10
13	8	Optimización de procesos mediante gestión de inventarios 377	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-11-04 17:06:10
14	9	Evaluación de rendimiento en gestión de inventarios 644	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2024-12-17 17:06:10
15	1	Implementación de algoritmo de seguridad informática 278	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-06-03 17:06:10
16	11	Evaluación de rendimiento en visión por computadora 545	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2026-08-18 17:06:10
17	8	Diseño de una arquitectura de bases de datos NoSQL 899	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-07-06 17:06:10
18	3	Estudio comparativo sobre gestión de inventarios 667	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-05-23 17:06:10
19	2	Diseño de una arquitectura de bases de datos NoSQL 655	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-02-19 17:06:10
20	1	Propuesta de mejora para aplicaciones web distribuidas 507	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2025-01-28 17:06:10
21	5	Estudio comparativo sobre bases de datos NoSQL 997	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-04-10 17:06:10
22	4	Desarrollo de un sistema bases de datos NoSQL 182	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-05-19 17:06:10
23	6	Implementación de algoritmo de procesamiento de lenguaje natural 573	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2024-09-22 17:06:10
24	9	Optimización de procesos mediante visión por computadora 155	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-04-08 17:06:10
25	3	Desarrollo de un sistema computación cuántica 792	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-02-11 17:06:10
26	2	Optimización de procesos mediante seguridad informática 704	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-02-25 17:06:10
27	4	Optimización de procesos mediante redes neuronales 187	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2026-02-03 17:06:10
28	9	Diseño de una arquitectura de gestión de inventarios 835	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-07-14 17:06:10
29	7	Evaluación de rendimiento en procesamiento de lenguaje natural 862	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-05-11 17:06:10
30	5	Estudio comparativo sobre procesamiento de lenguaje natural 302	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-12-23 17:06:10
31	6	Estudio comparativo sobre gestión de inventarios 129	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-08-24 17:06:10
32	9	Propuesta de mejora para seguridad informática 828	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-04-07 17:06:10
33	3	Análisis del impacto de computación cuántica 839	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-04-01 17:06:10
34	2	Desarrollo de un sistema redes neuronales 217	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-08-06 17:06:10
35	9	Evaluación de rendimiento en gestión de inventarios 395	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2024-09-02 17:06:10
36	13	Análisis del impacto de gestión de inventarios 942	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-10-29 17:06:10
37	9	Estudio comparativo sobre seguridad informática 685	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2024-11-09 17:06:10
38	6	Análisis del impacto de computación cuántica 243	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2026-03-31 17:06:10
39	8	Diseño de una arquitectura de seguridad informática 157	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-10-10 17:06:10
40	6	Análisis del impacto de bases de datos NoSQL 592	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2026-04-30 17:06:10
41	8	Desarrollo de un sistema sistemas embebidos 331	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-08-08 17:06:10
42	8	Optimización de procesos mediante redes neuronales 725	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-12-26 17:06:10
43	1	Desarrollo de un sistema bases de datos NoSQL 341	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-12-10 17:06:10
44	4	Optimización de procesos mediante sistemas embebidos 338	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-11-10 17:06:10
45	6	Propuesta de mejora para sistemas embebidos 683	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-03-27 17:06:10
46	2	Estudio comparativo sobre aplicaciones web distribuidas 399	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-03-08 17:06:10
47	10	Diseño de una arquitectura de bases de datos NoSQL 516	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2025-04-28 17:06:10
48	5	Optimización de procesos mediante seguridad informática 714	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2026-02-28 17:06:10
49	2	Estudio comparativo sobre bases de datos NoSQL 373	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-06-02 17:06:10
50	4	Diseño de una arquitectura de aplicaciones web distribuidas 598	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-07-21 17:06:10
177	11	Estudio comparativo sobre sistemas embebidos 640	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-08-15 17:06:10
51	6	Evaluación de rendimiento en computación cuántica 776	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-06-27 17:06:10
52	13	Propuesta de mejora para computación cuántica 879	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-05-05 17:06:10
53	4	Optimización de procesos mediante visión por computadora 682	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2026-08-03 17:06:10
54	2	Optimización de procesos mediante seguridad informática 956	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-11-14 17:06:10
55	11	Optimización de procesos mediante visión por computadora 742	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-06-08 17:06:10
56	1	Optimización de procesos mediante bases de datos NoSQL 268	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-06-05 17:06:10
57	13	Estudio comparativo sobre computación cuántica 737	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-05-27 17:06:10
58	8	Evaluación de rendimiento en bases de datos NoSQL 421	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-08-08 17:06:10
59	3	Propuesta de mejora para procesamiento de lenguaje natural 622	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-09-23 17:06:10
60	1	Desarrollo de un sistema sistemas embebidos 951	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-10-09 17:06:10
61	4	Propuesta de mejora para visión por computadora 406	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2026-07-08 17:06:10
62	2	Análisis del impacto de computación cuántica 224	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-08-18 17:06:10
63	11	Estudio comparativo sobre seguridad informática 609	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-11-15 17:06:10
64	8	Implementación de algoritmo de procesamiento de lenguaje natural 663	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2025-03-06 17:06:10
65	6	Estudio comparativo sobre computación cuántica 875	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2024-08-25 17:06:10
66	13	Implementación de algoritmo de sistemas embebidos 822	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-12-16 17:06:10
67	3	Implementación de algoritmo de sistemas embebidos 865	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2026-02-15 17:06:10
68	8	Propuesta de mejora para visión por computadora 331	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-09-13 17:06:10
69	2	Desarrollo de un sistema visión por computadora 642	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-06-22 17:06:10
70	2	Diseño de una arquitectura de sistemas embebidos 631	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-08-21 17:06:10
71	7	Propuesta de mejora para bases de datos NoSQL 660	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-10-30 17:06:10
72	7	Estudio comparativo sobre computación cuántica 755	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-03-21 17:06:10
73	8	Implementación de algoritmo de procesamiento de lenguaje natural 907	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-06-14 17:06:10
74	5	Estudio comparativo sobre procesamiento de lenguaje natural 967	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2024-11-24 17:06:10
75	2	Implementación de algoritmo de aplicaciones web distribuidas 221	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2024-11-16 17:06:10
76	2	Propuesta de mejora para computación cuántica 629	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-07-05 17:06:10
77	13	Análisis del impacto de visión por computadora 637	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-05-22 17:06:10
78	11	Desarrollo de un sistema aplicaciones web distribuidas 269	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-06-21 17:06:10
79	13	Desarrollo de un sistema gestión de inventarios 733	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-10-22 17:06:10
80	4	Análisis del impacto de seguridad informática 413	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-12-03 17:06:10
81	8	Evaluación de rendimiento en seguridad informática 586	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-12-17 17:06:10
82	4	Optimización de procesos mediante seguridad informática 169	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-03-16 17:06:10
83	9	Propuesta de mejora para visión por computadora 315	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-05-31 17:06:10
84	3	Implementación de algoritmo de sistemas embebidos 684	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2025-08-18 17:06:10
85	1	Desarrollo de un sistema seguridad informática 312	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-11-08 17:06:10
86	13	Optimización de procesos mediante redes neuronales 735	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2024-09-30 17:06:10
87	3	Propuesta de mejora para bases de datos NoSQL 425	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-08-09 17:06:10
88	7	Propuesta de mejora para computación cuántica 607	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-02-03 17:06:10
89	11	Desarrollo de un sistema procesamiento de lenguaje natural 664	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2025-01-06 17:06:10
90	13	Evaluación de rendimiento en sistemas embebidos 973	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-03-02 17:06:10
91	8	Análisis del impacto de computación cuántica 793	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-09-09 17:06:10
92	3	Diseño de una arquitectura de seguridad informática 859	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-06-01 17:06:10
93	8	Propuesta de mejora para redes neuronales 815	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-03-27 17:06:10
94	2	Diseño de una arquitectura de sistemas embebidos 469	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2026-02-12 17:06:10
95	2	Análisis del impacto de seguridad informática 505	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-12-04 17:06:10
96	4	Implementación de algoritmo de computación cuántica 523	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2025-05-23 17:06:10
97	7	Implementación de algoritmo de visión por computadora 999	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-03-13 17:06:10
98	7	Diseño de una arquitectura de aplicaciones web distribuidas 243	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-01-24 17:06:10
99	7	Optimización de procesos mediante visión por computadora 384	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2026-08-11 17:06:10
100	2	Estudio comparativo sobre seguridad informática 596	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-02-20 17:06:10
101	10	Evaluación de rendimiento en procesamiento de lenguaje natural 329	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2026-04-14 17:06:10
102	4	Evaluación de rendimiento en aplicaciones web distribuidas 690	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-12-21 17:06:10
103	10	Análisis del impacto de aplicaciones web distribuidas 289	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-08-19 17:06:10
104	3	Implementación de algoritmo de computación cuántica 719	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2026-03-04 17:06:10
105	1	Implementación de algoritmo de visión por computadora 872	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-05-18 17:06:10
106	5	Implementación de algoritmo de redes neuronales 966	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-10-06 17:06:10
107	5	Optimización de procesos mediante procesamiento de lenguaje natural 262	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-02-04 17:06:10
108	2	Diseño de una arquitectura de computación cuántica 240	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-04-26 17:06:10
109	6	Análisis del impacto de gestión de inventarios 590	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-01-03 17:06:10
110	9	Implementación de algoritmo de procesamiento de lenguaje natural 562	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-01-21 17:06:10
111	3	Optimización de procesos mediante aplicaciones web distribuidas 296	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2025-01-02 17:06:10
112	1	Diseño de una arquitectura de seguridad informática 189	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2026-06-25 17:06:10
113	2	Análisis del impacto de procesamiento de lenguaje natural 776	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-08-30 17:06:10
114	8	Propuesta de mejora para bases de datos NoSQL 709	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-02-17 17:06:10
115	8	Optimización de procesos mediante redes neuronales 403	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-09-09 17:06:10
116	1	Desarrollo de un sistema seguridad informática 459	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-06-04 17:06:10
117	7	Optimización de procesos mediante gestión de inventarios 559	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2026-02-06 17:06:10
118	13	Desarrollo de un sistema sistemas embebidos 439	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-10-19 17:06:10
119	7	Propuesta de mejora para bases de datos NoSQL 782	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2026-03-02 17:06:10
120	3	Desarrollo de un sistema sistemas embebidos 391	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2025-08-19 17:06:10
121	7	Estudio comparativo sobre sistemas embebidos 966	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-10-30 17:06:10
122	10	Propuesta de mejora para redes neuronales 976	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-11-01 17:06:10
123	9	Análisis del impacto de seguridad informática 131	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2026-03-19 17:06:10
124	1	Evaluación de rendimiento en bases de datos NoSQL 491	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-12-13 17:06:10
125	3	Diseño de una arquitectura de sistemas embebidos 906	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-01-19 17:06:10
126	7	Optimización de procesos mediante redes neuronales 150	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-09-26 17:06:10
127	13	Diseño de una arquitectura de visión por computadora 299	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2025-12-04 17:06:10
128	9	Estudio comparativo sobre computación cuántica 224	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-03-08 17:06:10
129	1	Estudio comparativo sobre gestión de inventarios 908	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-06-10 17:06:10
130	8	Desarrollo de un sistema redes neuronales 562	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2026-08-15 17:06:10
131	1	Evaluación de rendimiento en computación cuántica 875	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-02-20 17:06:10
132	2	Evaluación de rendimiento en computación cuántica 942	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-06-03 17:06:10
133	2	Desarrollo de un sistema procesamiento de lenguaje natural 359	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-03-30 17:06:10
134	6	Propuesta de mejora para visión por computadora 871	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-12-20 17:06:10
135	8	Optimización de procesos mediante procesamiento de lenguaje natural 165	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-10-05 17:06:10
136	3	Evaluación de rendimiento en aplicaciones web distribuidas 377	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-02-03 17:06:10
137	10	Evaluación de rendimiento en gestión de inventarios 657	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-01-31 17:06:10
138	3	Implementación de algoritmo de seguridad informática 686	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-03-24 17:06:10
139	5	Diseño de una arquitectura de sistemas embebidos 296	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-08-31 17:06:10
140	1	Estudio comparativo sobre bases de datos NoSQL 255	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-09-28 17:06:10
141	9	Optimización de procesos mediante sistemas embebidos 250	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2026-03-23 17:06:10
142	10	Optimización de procesos mediante seguridad informática 120	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-04-16 17:06:10
143	5	Implementación de algoritmo de bases de datos NoSQL 533	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-01-17 17:06:10
144	4	Diseño de una arquitectura de sistemas embebidos 518	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-08-12 17:06:10
145	6	Implementación de algoritmo de gestión de inventarios 993	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-09-29 17:06:10
146	8	Estudio comparativo sobre visión por computadora 932	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-06-17 17:06:10
147	4	Estudio comparativo sobre bases de datos NoSQL 957	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2026-07-18 17:06:10
148	6	Desarrollo de un sistema aplicaciones web distribuidas 419	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2026-08-01 17:06:10
149	2	Desarrollo de un sistema bases de datos NoSQL 603	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-11-16 17:06:10
150	2	Evaluación de rendimiento en bases de datos NoSQL 957	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2026-04-01 17:06:10
151	8	Optimización de procesos mediante aplicaciones web distribuidas 920	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2024-12-09 17:06:10
152	9	Propuesta de mejora para sistemas embebidos 962	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-03-20 17:06:10
153	4	Optimización de procesos mediante visión por computadora 145	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-01-27 17:06:10
154	8	Análisis del impacto de sistemas embebidos 352	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-11-03 17:06:10
155	11	Implementación de algoritmo de procesamiento de lenguaje natural 425	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-05-02 17:06:10
156	7	Estudio comparativo sobre procesamiento de lenguaje natural 655	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-01-09 17:06:10
157	5	Diseño de una arquitectura de procesamiento de lenguaje natural 546	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2024-10-02 17:06:10
158	13	Optimización de procesos mediante redes neuronales 406	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2025-01-02 17:06:10
159	11	Evaluación de rendimiento en aplicaciones web distribuidas 437	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-09-13 17:06:10
160	5	Análisis del impacto de sistemas embebidos 515	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2024-11-10 17:06:10
161	4	Implementación de algoritmo de sistemas embebidos 654	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2024-09-10 17:06:10
162	3	Optimización de procesos mediante sistemas embebidos 174	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-04-18 17:06:10
163	6	Análisis del impacto de seguridad informática 483	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-12-03 17:06:10
164	7	Propuesta de mejora para procesamiento de lenguaje natural 662	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-12-07 17:06:10
165	11	Diseño de una arquitectura de computación cuántica 806	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-01-24 17:06:10
166	7	Diseño de una arquitectura de computación cuántica 278	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2026-05-21 17:06:10
167	13	Análisis del impacto de sistemas embebidos 338	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2025-05-22 17:06:10
168	5	Diseño de una arquitectura de gestión de inventarios 201	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-04-27 17:06:10
169	3	Desarrollo de un sistema procesamiento de lenguaje natural 430	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-10-11 17:06:10
170	2	Desarrollo de un sistema redes neuronales 858	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-03-29 17:06:10
171	13	Análisis del impacto de procesamiento de lenguaje natural 182	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-06-04 17:06:10
172	13	Evaluación de rendimiento en aplicaciones web distribuidas 799	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-09-07 17:06:10
173	11	Evaluación de rendimiento en aplicaciones web distribuidas 265	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-11-28 17:06:10
174	8	Evaluación de rendimiento en visión por computadora 230	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2024-12-04 17:06:10
175	13	Implementación de algoritmo de gestión de inventarios 835	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-04-04 17:06:10
176	8	Evaluación de rendimiento en visión por computadora 138	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-12-10 17:06:10
178	6	Implementación de algoritmo de sistemas embebidos 511	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2025-03-16 17:06:10
179	2	Implementación de algoritmo de procesamiento de lenguaje natural 164	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-07-08 17:06:10
180	7	Estudio comparativo sobre aplicaciones web distribuidas 684	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-01-17 17:06:10
181	4	Propuesta de mejora para seguridad informática 613	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-07-19 17:06:10
182	1	Optimización de procesos mediante bases de datos NoSQL 986	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-01-29 17:06:10
183	8	Análisis del impacto de redes neuronales 785	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2026-04-10 17:06:10
184	9	Implementación de algoritmo de visión por computadora 155	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-09-30 17:06:10
185	10	Análisis del impacto de gestión de inventarios 628	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2025-06-01 17:06:10
186	1	Implementación de algoritmo de aplicaciones web distribuidas 527	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-09-30 17:06:10
187	13	Desarrollo de un sistema computación cuántica 173	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2026-02-10 17:06:10
188	7	Implementación de algoritmo de redes neuronales 808	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-01-26 17:06:10
189	9	Propuesta de mejora para gestión de inventarios 354	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2026-03-07 17:06:10
190	11	Análisis del impacto de gestión de inventarios 441	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2026-01-11 17:06:10
191	6	Optimización de procesos mediante computación cuántica 218	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-10-08 17:06:10
192	7	Estudio comparativo sobre bases de datos NoSQL 378	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-08-27 17:06:10
193	2	Análisis del impacto de aplicaciones web distribuidas 325	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-06-11 17:06:10
194	1	Implementación de algoritmo de gestión de inventarios 241	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-09-14 17:06:10
195	9	Análisis del impacto de aplicaciones web distribuidas 863	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-03-22 17:06:10
196	3	Evaluación de rendimiento en redes neuronales 139	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2024-12-08 17:06:10
197	3	Implementación de algoritmo de gestión de inventarios 637	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-07-24 17:06:10
198	3	Implementación de algoritmo de bases de datos NoSQL 359	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-11-17 17:06:10
199	6	Optimización de procesos mediante seguridad informática 322	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-08-28 17:06:10
200	7	Diseño de una arquitectura de visión por computadora 912	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-09-20 17:06:10
201	10	Estudio comparativo sobre gestión de inventarios 942	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2026-02-20 17:06:10
202	8	Propuesta de mejora para seguridad informática 691	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-08-16 17:06:10
203	6	Propuesta de mejora para gestión de inventarios 497	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-11-07 17:06:10
204	2	Propuesta de mejora para procesamiento de lenguaje natural 537	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2024-12-26 17:06:10
205	10	Análisis del impacto de seguridad informática 869	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-10-07 17:06:10
206	4	Optimización de procesos mediante aplicaciones web distribuidas 980	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-06-19 17:06:10
207	8	Implementación de algoritmo de visión por computadora 284	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-04-13 17:06:10
208	9	Desarrollo de un sistema gestión de inventarios 801	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2025-12-29 17:06:10
209	7	Optimización de procesos mediante procesamiento de lenguaje natural 665	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2024-10-03 17:06:10
210	7	Evaluación de rendimiento en aplicaciones web distribuidas 690	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-01-25 17:06:10
211	11	Análisis del impacto de sistemas embebidos 442	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-09-23 17:06:10
212	11	Optimización de procesos mediante bases de datos NoSQL 109	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2024-12-21 17:06:10
213	3	Propuesta de mejora para procesamiento de lenguaje natural 930	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-02-27 17:06:10
214	11	Optimización de procesos mediante sistemas embebidos 843	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2026-01-24 17:06:10
215	2	Estudio comparativo sobre visión por computadora 720	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2026-01-12 17:06:10
216	11	Optimización de procesos mediante sistemas embebidos 647	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-10-20 17:06:10
217	1	Análisis del impacto de visión por computadora 233	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-02-22 17:06:10
218	6	Diseño de una arquitectura de visión por computadora 592	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-12-23 17:06:10
219	10	Estudio comparativo sobre gestión de inventarios 930	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2024-12-17 17:06:10
220	13	Evaluación de rendimiento en redes neuronales 951	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-10-03 17:06:10
221	11	Propuesta de mejora para bases de datos NoSQL 920	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-07-13 17:06:10
222	7	Diseño de una arquitectura de bases de datos NoSQL 729	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-09-03 17:06:10
223	5	Estudio comparativo sobre bases de datos NoSQL 410	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-09-14 17:06:10
224	10	Estudio comparativo sobre seguridad informática 863	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2025-05-16 17:06:10
225	4	Evaluación de rendimiento en aplicaciones web distribuidas 664	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-07-17 17:06:10
226	4	Diseño de una arquitectura de redes neuronales 857	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-10-12 17:06:10
227	2	Evaluación de rendimiento en bases de datos NoSQL 321	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2024-10-18 17:06:10
228	13	Desarrollo de un sistema bases de datos NoSQL 779	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-07-23 17:06:10
229	13	Análisis del impacto de computación cuántica 612	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-10-09 17:06:10
230	5	Propuesta de mejora para sistemas embebidos 116	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-11-17 17:06:10
231	7	Diseño de una arquitectura de computación cuántica 461	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2024-10-12 17:06:10
232	1	Diseño de una arquitectura de aplicaciones web distribuidas 795	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-06-24 17:06:10
233	13	Implementación de algoritmo de visión por computadora 442	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2024-09-18 17:06:10
234	10	Análisis del impacto de bases de datos NoSQL 485	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-01-01 17:06:10
235	8	Estudio comparativo sobre bases de datos NoSQL 400	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-06-07 17:06:10
236	3	Optimización de procesos mediante visión por computadora 472	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-07-23 17:06:10
237	6	Estudio comparativo sobre seguridad informática 464	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-11-29 17:06:10
238	6	Análisis del impacto de gestión de inventarios 365	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2025-07-18 17:06:10
239	6	Diseño de una arquitectura de redes neuronales 480	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-04-24 17:06:10
240	4	Análisis del impacto de gestión de inventarios 826	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-12-15 17:06:10
241	7	Desarrollo de un sistema seguridad informática 352	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	12	3	Cerrada	2025-12-23 17:06:10
242	6	Estudio comparativo sobre computación cuántica 261	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-10-09 17:06:10
243	5	Estudio comparativo sobre sistemas embebidos 116	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-03-07 17:06:10
244	4	Diseño de una arquitectura de bases de datos NoSQL 343	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-02-03 17:06:10
245	10	Estudio comparativo sobre procesamiento de lenguaje natural 677	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-02-18 17:06:10
246	10	Implementación de algoritmo de seguridad informática 384	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-02-22 17:06:10
247	5	Evaluación de rendimiento en bases de datos NoSQL 349	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2025-01-29 17:06:10
248	5	Evaluación de rendimiento en seguridad informática 179	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-10-31 17:06:10
249	7	Propuesta de mejora para gestión de inventarios 664	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-03-07 17:06:10
250	11	Análisis del impacto de gestión de inventarios 887	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-01-28 17:06:10
251	6	Desarrollo de un sistema seguridad informática 773	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2024-08-24 17:06:10
252	5	Implementación de algoritmo de seguridad informática 762	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-11-18 17:06:10
253	6	Implementación de algoritmo de aplicaciones web distribuidas 527	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-12-23 17:06:10
254	3	Propuesta de mejora para sistemas embebidos 312	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2025-01-11 17:06:10
255	3	Análisis del impacto de bases de datos NoSQL 350	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	13	3	Cerrada	2024-12-08 17:06:10
256	10	Propuesta de mejora para sistemas embebidos 679	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2026-03-26 17:06:10
257	5	Implementación de algoritmo de gestión de inventarios 550	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2024-12-26 17:06:10
258	13	Optimización de procesos mediante sistemas embebidos 349	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	6	3	Cerrada	2026-02-26 17:06:10
259	10	Evaluación de rendimiento en visión por computadora 914	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-05-10 17:06:10
260	10	Optimización de procesos mediante procesamiento de lenguaje natural 747	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-10-24 17:06:10
261	7	Diseño de una arquitectura de visión por computadora 886	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2025-11-21 17:06:10
262	3	Optimización de procesos mediante visión por computadora 191	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-01-05 17:06:10
263	6	Implementación de algoritmo de visión por computadora 565	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-02-12 17:06:10
264	11	Optimización de procesos mediante computación cuántica 599	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-11-08 17:06:10
265	4	Evaluación de rendimiento en bases de datos NoSQL 944	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-04-08 17:06:10
266	1	Análisis del impacto de computación cuántica 222	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2026-06-29 17:06:10
267	6	Propuesta de mejora para sistemas embebidos 881	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2026-06-16 17:06:10
268	3	Evaluación de rendimiento en visión por computadora 679	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-03-23 17:06:10
269	5	Propuesta de mejora para sistemas embebidos 901	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-10-25 17:06:10
270	8	Diseño de una arquitectura de computación cuántica 507	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-12-17 17:06:10
271	1	Diseño de una arquitectura de visión por computadora 512	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2024-10-02 17:06:10
272	6	Diseño de una arquitectura de sistemas embebidos 627	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-04-13 17:06:10
273	9	Optimización de procesos mediante gestión de inventarios 268	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-08-04 17:06:10
274	1	Análisis del impacto de aplicaciones web distribuidas 506	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-12-25 17:06:10
275	1	Evaluación de rendimiento en visión por computadora 715	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-04-05 17:06:10
276	1	Desarrollo de un sistema seguridad informática 969	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-05-05 17:06:10
277	3	Evaluación de rendimiento en redes neuronales 880	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2026-08-17 17:06:10
278	8	Implementación de algoritmo de procesamiento de lenguaje natural 357	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-10-20 17:06:10
279	8	Optimización de procesos mediante computación cuántica 388	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-04-04 17:06:10
280	4	Propuesta de mejora para computación cuántica 356	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-12-06 17:06:10
281	2	Diseño de una arquitectura de aplicaciones web distribuidas 631	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2025-10-06 17:06:10
282	13	Evaluación de rendimiento en aplicaciones web distribuidas 710	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-06-18 17:06:10
283	2	Estudio comparativo sobre sistemas embebidos 513	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2025-05-26 17:06:10
284	6	Optimización de procesos mediante sistemas embebidos 697	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	17	3	Cerrada	2025-10-14 17:06:10
285	10	Desarrollo de un sistema redes neuronales 683	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2025-06-01 17:06:10
286	2	Optimización de procesos mediante redes neuronales 911	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2026-07-09 17:06:10
287	6	Propuesta de mejora para gestión de inventarios 480	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-03-10 17:06:10
288	3	Propuesta de mejora para procesamiento de lenguaje natural 411	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-07-31 17:06:10
289	2	Evaluación de rendimiento en sistemas embebidos 116	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	15	3	Cerrada	2026-07-08 17:06:10
290	9	Evaluación de rendimiento en procesamiento de lenguaje natural 663	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	7	3	Cerrada	2025-11-04 17:06:10
291	5	Diseño de una arquitectura de seguridad informática 938	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	9	3	Cerrada	2026-07-30 17:06:10
292	5	Análisis del impacto de gestión de inventarios 705	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	11	3	Cerrada	2025-12-07 17:06:10
293	2	Implementación de algoritmo de sistemas embebidos 750	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	8	3	Cerrada	2025-02-09 17:06:10
294	5	Propuesta de mejora para bases de datos NoSQL 835	Este proyecto surge por la necesidad de resolver problemas en el área de SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	Mejorar las métricas de rendimiento y evaluar alternativas viables en SISTEMAS DE INFORMACION Y MODELADO DE DATOS.	7	5	3	Cerrada	2025-09-01 17:06:10
295	8	Análisis del impacto de computación cuántica 824	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	18	3	Cerrada	2025-05-06 17:06:10
296	11	Diseño de una arquitectura de redes neuronales 429	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	10	3	Cerrada	2024-10-24 17:06:10
297	11	Análisis del impacto de redes neuronales 354	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-02-16 17:06:10
298	10	Implementación de algoritmo de sistemas embebidos 140	Este proyecto surge por la necesidad de resolver problemas en el área de EDUMATICA.	Mejorar las métricas de rendimiento y evaluar alternativas viables en EDUMATICA.	8	14	3	Cerrada	2026-02-13 17:06:10
299	13	Análisis del impacto de seguridad informática 468	Este proyecto surge por la necesidad de resolver problemas en el área de APLICACIONES WEB.	Mejorar las métricas de rendimiento y evaluar alternativas viables en APLICACIONES WEB.	9	16	3	Cerrada	2026-03-23 17:06:10
300	4	Diseño de una arquitectura de procesamiento de lenguaje natural 434	Este proyecto surge por la necesidad de resolver problemas en el área de REDES Y TELECOMUNICACIONES.	Mejorar las métricas de rendimiento y evaluar alternativas viables en REDES Y TELECOMUNICACIONES.	10	19	3	Cerrada	2026-08-10 17:06:10
\.


--
-- TOC entry 5388 (class 0 OID 16564)
-- Dependencies: 242
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lineas_investigacion (id, nombre, id_carrera, descripcion) FROM stdin;
7	SISTEMAS DE INFORMACION Y MODELADO DE DATOS	1	Desarrollar y gestionar sistemas de informaci¢n dentro del  mbito social. Aplicando soluciones efectivas para el uso adecuado y ¢ptimo de los sistemas de informaci¢n.
8	EDUMATICA	1	Aplicar las Tecnolog¡as de la Informaci¢n y Comunicaci¢n (TIC) para apoyar el proceso de aprendizaje, y as¡ contribuir al mejoramiento de la educaci¢n en todos sus niveles.
9	APLICACIONES WEB	1	Desarrollar aplicaciones Web para cubrir las necesidades de gesti¢n, control e intercambio de informaci¢n de la empresa y el entorno que la rodea a trav‚s de la Internet o Intranet.
10	REDES Y TELECOMUNICACIONES	1	Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisi¢n de datos, como tambi‚n la detecci¢n de fallas dentro de una red.
\.


--
-- TOC entry 5390 (class 0 OID 16573)
-- Dependencies: 244
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
-- TOC entry 5392 (class 0 OID 16583)
-- Dependencies: 246
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.postulaciones_estudiantes (id, id_investigacion, id_estudiante, mensaje_motivacion, estado, fecha_postulacion, fecha_respuesta) FROM stdin;
\.


--
-- TOC entry 5394 (class 0 OID 16595)
-- Dependencies: 248
-- Data for Name: preferencias_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.preferencias_usuario (id_usuario, tema, notificaciones_sistema) FROM stdin;
1	ocean	t
3	sunset	t
4	ocean	t
6	sunset	t
\.


--
-- TOC entry 5395 (class 0 OID 16601)
-- Dependencies: 249
-- Data for Name: privilegios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.privilegios (privilegio_id, nivel_privilegio) FROM stdin;
1	0
2	1
3	2
4	3
5	4
6	5
\.


--
-- TOC entry 5419 (class 0 OID 17000)
-- Dependencies: 273
-- Data for Name: propuestas_empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.propuestas_empresa (id, nombre_empresa, rif_empresa, persona_contacto, telefono_contacto, correo_contacto, area_afectada, descripcion_problema, estado, fecha_creacion, nivel_trayecto) FROM stdin;
4	Punto G De Yali	G-30676767-0	Iojan	4147755888	Puntogdeyali@gmail.com	redes	Quiero un sistema de clasificacion de los pelos de mi anito riko mmm sisisii	aceptada	2026-07-10 14:02:25.411413	Trayecto I (T1)
1	Megacell	J-12045552-	ELLL PRIMOOOO	04121609721	lando1609721@gmail.com	facturacion	NECESITAMOS UN SISTEMA PARA CLASIFICAR FEMBOY, FURROS Y KPOPERAS 	aceptada	2026-07-10 00:05:36.297999	Trayecto I (T1)
\.


--
-- TOC entry 5397 (class 0 OID 16608)
-- Dependencies: 251
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.proyecto_tutores (id_recurso, id_tutor, tipo_tutor_id) FROM stdin;
57	7	3
57	8	2
57	9	4
\.


--
-- TOC entry 5398 (class 0 OID 16613)
-- Dependencies: 252
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_autores (id_recurso, id_autor) FROM stdin;
3	1
57	41
58	42
58	43
58	44
62	47
62	48
63	49
63	50
63	51
63	52
64	53
64	54
64	55
64	56
65	57
65	58
65	59
65	60
\.


--
-- TOC entry 5399 (class 0 OID 16618)
-- Dependencies: 253
-- Data for Name: recurso_categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_categorias (id_recurso, id_categoria) FROM stdin;
59	6
59	3
59	5
60	5
27	2
27	6
27	3
27	5
27	11
61	4
62	1
63	3
63	13
64	18
65	6
65	5
65	1
\.


--
-- TOC entry 5400 (class 0 OID 16623)
-- Dependencies: 254
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_clasificaciones (id_recurso, id_linea_investigacion, id_dimension_operativa) FROM stdin;
49	7	5
50	8	10
51	9	16
52	10	18
57	9	17
\.


--
-- TOC entry 5401 (class 0 OID 16628)
-- Dependencies: 255
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_etiquetas (id_recurso, id_etiqueta) FROM stdin;
58	1
62	3
62	1
63	8
64	10
65	1
65	2
65	4
\.


--
-- TOC entry 5402 (class 0 OID 16633)
-- Dependencies: 256
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recursos (id, titulo, id_tipo_recurso, anio_publicacion, ejemplares_totales, ejemplares_disponibles, archivo_pdf) FROM stdin;
1	Sistema de Reconocimiento Biométrico Facial para Comedor Universitario	1	2026	1	1	\N
2	Prototipo de Cerradura Digital con Matriz de Teclado y Arduino	1	2025	1	1	\N
3	Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos	2	2026	1	1	\N
4	Impacto del Cambio Climático en Trujillo - Parte 8	2	2023	1	1	dummy.pdf
5	Simulación de Cargas Estáticas en Puentes - Parte 7	2	2024	1	1	dummy.pdf
6	Big Data en Finanzas Institucionales - Parte 9	1	2024	1	1	dummy.pdf
7	Optimización de CPU en Servidores Locales - Parte 7	1	2026	1	1	dummy.pdf
8	Sistemas de Riego Automatizado - Parte 5	1	2023	1	1	dummy.pdf
9	Bioinformática y Análisis de ADN - Parte 5	3	2025	1	1	dummy.pdf
10	Inteligencia Artificial en Diagnóstico Médico - Parte 6	2	2025	1	1	dummy.pdf
11	Robótica Educativa para Escuelas - Parte 5	3	2023	1	1	dummy.pdf
12	Software Libre para Bibliotecas - Parte 1	3	2026	1	1	dummy.pdf
13	E-Learning para Zonas Desfavorecidas - Parte 1	3	2018	1	1	dummy.pdf
14	Telecomunicaciones de Fibra Óptica Rural - Parte 1	2	2022	1	1	dummy.pdf
15	Criptografía Cuántica Post-RSA - Parte 2	1	2024	1	1	dummy.pdf
16	Criptografía Cuántica Post-RSA - Parte 7	3	2026	1	1	dummy.pdf
17	Criptografía Cuántica Post-RSA - Parte 5	1	2024	1	1	dummy.pdf
18	Criptografía Cuántica Post-RSA - Parte 8	1	2021	1	1	dummy.pdf
19	Software Libre para Bibliotecas - Parte 6	1	2023	1	1	dummy.pdf
20	Inteligencia Artificial en Diagnóstico Médico - Parte 1	3	2020	1	1	dummy.pdf
45	Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri	1	2026	2	2	motor_rust_tauri_v1.pdf
46	Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80	1	2025	1	1	juego_aislamiento_tic80.pdf
47	Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478	1	2026	3	3	restauracion_pentium4.pdf
48	Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro	1	2026	1	1	microkernel_php_routing.pdf
49	Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos	1	2026	1	1	proyecto_inventario_medico.pdf
50	Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal	1	2026	1	1	software_educativo_algebra.pdf
51	Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas	1	2025	1	1	plataforma_web_citas.pdf
52	Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas	1	2026	1	1	simulador_routing_topologias.pdf
57	hola adios	1	2026	1	1	documentos/pst/pst_hola_adios_1783290093.pdf
58	Middleware MiSCi para ciudades inteligentes extendido con datos enlazados	3	2020	1	1	https://revistas.unal.edu.co/index.php/dyna/article/view/83226
65	Entorno virtual de capacitación con EOG para manipular robots asistenciales	3	2026	1	1	https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135
62	Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos	3	2026	1	1	https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457
63	Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo	3	2026	1	1	https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473
64	Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto	3	2026	1	1	https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474
\.


--
-- TOC entry 5404 (class 0 OID 16644)
-- Dependencies: 258
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registro_actividad (id, id_usuario, id_visitante, fecha_inicial, ultima_actividad, conteo_accesos) FROM stdin;
1	1	\N	2026-03-23 14:49:58	2026-03-23 14:49:58	1
\.


--
-- TOC entry 5406 (class 0 OID 16652)
-- Dependencies: 260
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, nombre, privilegio_id) FROM stdin;
3	Estudiante	1
1	Super Administrador	4
4	Profesor	2
2	Comite	3
\.


--
-- TOC entry 5408 (class 0 OID 16660)
-- Dependencies: 262
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_recurso (id, nombre, descripcion) FROM stdin;
1	PST / Trabajo de Grado	Proyectos Socio-Tecnológicos y Tesis
2	Investigación Docente	Papers y artículos de investigación del personal académico
3	Material de Apoyo / Didáctico	Recursos adicionales para estudiantes
\.


--
-- TOC entry 5410 (class 0 OID 16668)
-- Dependencies: 264
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_tutor (id, nombre, descripcion) FROM stdin;
1	Director	Director principal del proyecto
2	Coordinador	Asesor metodológico
3	Tutor Académico	Especialista en el área
4	Tutor Comunitario	Representante de la comunidad
\.


--
-- TOC entry 5412 (class 0 OID 16676)
-- Dependencies: 266
-- Data for Name: tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tutores (id, nombre_completo, cedula) FROM stdin;
1	Lando	V-12345678
2	Mikeyisito	V-18765432
3	María Antonieta Pérez	V-15444333
7	aaaaa aaa	22222
8	aaa aaaa	33333
9	aaaaa aaaaaa	444444
\.


--
-- TOC entry 5414 (class 0 OID 16682)
-- Dependencies: 268
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, nombre_completo, email, cedula, contrasena, id_rol, activo) FROM stdin;
1	Adrus	andru@gmail.com	11111111	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	1	t
2	lando	lando@gmail.com	22222222	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	2	t
3	miki	miki@gmail.com	33333333	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	3	t
4	ale	ale@yaju.com	44444444	$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa	3	t
5	bibi	bibi@gmail.com	4444111	\N	3	t
8	Yisu Monte	yisu@gmail.com	30866991	$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa	3	t
9	Pedro Perez	iaiaia@gmail.com	4123123	$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC	3	t
11	Juan	123@gmail.com	1234	$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC	3	t
7	Miguel González	erwazaaaa@gmail.com	32621284	$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.	1	t
6	Piñin Piña	pina@hotmail.com	1	$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a	4	f
10	Wazaaaa	wazaaa@gmail.com	123	$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O	4	t
13	landorus	lando1609721@gmail.com	12045552	$2y$10$6MR9qTtPcacvG/iRWwpMQ.9TGdT.6aCztcGs5EXdeSBqDT1xUSNqO	2	t
14	Pipin	pipa1234@gmail.com	070901	$2y$10$xGRULeH8pgYcuPKA4Asnbe0S8i5Nv2c8x4SE6Z.W9FkWoPB7afbUa	2	t
\.


--
-- TOC entry 5416 (class 0 OID 16691)
-- Dependencies: 270
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.visitantes (id, ip_address, user_agent, pagina_origen) FROM stdin;
\.


--
-- TOC entry 5448 (class 0 OID 0)
-- Dependencies: 220
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, false);


--
-- TOC entry 5449 (class 0 OID 0)
-- Dependencies: 222
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 160, true);


--
-- TOC entry 5450 (class 0 OID 0)
-- Dependencies: 224
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 60, true);


--
-- TOC entry 5451 (class 0 OID 0)
-- Dependencies: 226
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- TOC entry 5452 (class 0 OID 0)
-- Dependencies: 228
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 18, true);


--
-- TOC entry 5453 (class 0 OID 0)
-- Dependencies: 230
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 4, true);


--
-- TOC entry 5454 (class 0 OID 0)
-- Dependencies: 235
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 19, true);


--
-- TOC entry 5455 (class 0 OID 0)
-- Dependencies: 237
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 9, true);


--
-- TOC entry 5456 (class 0 OID 0)
-- Dependencies: 239
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 10, true);


--
-- TOC entry 5457 (class 0 OID 0)
-- Dependencies: 241
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 300, true);


--
-- TOC entry 5458 (class 0 OID 0)
-- Dependencies: 243
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 10, true);


--
-- TOC entry 5459 (class 0 OID 0)
-- Dependencies: 245
-- Name: notificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_id_seq', 6, true);


--
-- TOC entry 5460 (class 0 OID 0)
-- Dependencies: 247
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.postulaciones_estudiantes_id_seq', 1, false);


--
-- TOC entry 5461 (class 0 OID 0)
-- Dependencies: 250
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 6, true);


--
-- TOC entry 5462 (class 0 OID 0)
-- Dependencies: 272
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 4, true);


--
-- TOC entry 5463 (class 0 OID 0)
-- Dependencies: 257
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 65, true);


--
-- TOC entry 5464 (class 0 OID 0)
-- Dependencies: 259
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 1, true);


--
-- TOC entry 5465 (class 0 OID 0)
-- Dependencies: 261
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- TOC entry 5466 (class 0 OID 0)
-- Dependencies: 263
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 4, true);


--
-- TOC entry 5467 (class 0 OID 0)
-- Dependencies: 265
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- TOC entry 5468 (class 0 OID 0)
-- Dependencies: 267
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 9, true);


--
-- TOC entry 5469 (class 0 OID 0)
-- Dependencies: 269
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 14, true);


--
-- TOC entry 5470 (class 0 OID 0)
-- Dependencies: 271
-- Name: visitantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visitantes_id_seq', 1, false);


--
-- TOC entry 5088 (class 2606 OID 16722)
-- Name: accesos_recursos accesos_recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5090 (class 2606 OID 16724)
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- TOC entry 5092 (class 2606 OID 16726)
-- Name: autores autores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_cedula_key UNIQUE (cedula);


--
-- TOC entry 5094 (class 2606 OID 16728)
-- Name: autores autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_pkey PRIMARY KEY (id);


--
-- TOC entry 5096 (class 2606 OID 16730)
-- Name: carreras carreras_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_nombre_key UNIQUE (nombre);


--
-- TOC entry 5098 (class 2606 OID 16732)
-- Name: carreras carreras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carreras
    ADD CONSTRAINT carreras_pkey PRIMARY KEY (id);


--
-- TOC entry 5100 (class 2606 OID 16734)
-- Name: categorias categorias_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nombre_key UNIQUE (nombre);


--
-- TOC entry 5102 (class 2606 OID 16736)
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- TOC entry 5104 (class 2606 OID 16738)
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5108 (class 2606 OID 16740)
-- Name: detalles_investigaciones detalles_investigaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT detalles_investigaciones_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5111 (class 2606 OID 16742)
-- Name: detalles_proyectos detalles_proyectos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5106 (class 2606 OID 16744)
-- Name: detalles_articulos detalles_revistas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_pkey PRIMARY KEY (id_recurso);


--
-- TOC entry 5113 (class 2606 OID 16746)
-- Name: dimensiones_operativas dimensiones_operativas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT dimensiones_operativas_pkey PRIMARY KEY (id);


--
-- TOC entry 5115 (class 2606 OID 16748)
-- Name: editoriales editoriales_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_nombre_key UNIQUE (nombre);


--
-- TOC entry 5117 (class 2606 OID 16750)
-- Name: editoriales editoriales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.editoriales
    ADD CONSTRAINT editoriales_pkey PRIMARY KEY (id);


--
-- TOC entry 5119 (class 2606 OID 16752)
-- Name: etiquetas etiquetas_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_nombre_key UNIQUE (nombre);


--
-- TOC entry 5121 (class 2606 OID 16754)
-- Name: etiquetas etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.etiquetas
    ADD CONSTRAINT etiquetas_pkey PRIMARY KEY (id);


--
-- TOC entry 5123 (class 2606 OID 16756)
-- Name: investigaciones_ofertadas investigaciones_ofertadas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT investigaciones_ofertadas_pkey PRIMARY KEY (id);


--
-- TOC entry 5125 (class 2606 OID 16758)
-- Name: lineas_investigacion lineas_investigacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_pkey PRIMARY KEY (id);


--
-- TOC entry 5127 (class 2606 OID 16760)
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


--
-- TOC entry 5129 (class 2606 OID 16762)
-- Name: postulaciones_estudiantes postulaciones_estudiantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT postulaciones_estudiantes_pkey PRIMARY KEY (id);


--
-- TOC entry 5133 (class 2606 OID 16764)
-- Name: preferencias_usuario preferencias_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 5135 (class 2606 OID 16766)
-- Name: privilegios privilegios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT privilegios_pkey PRIMARY KEY (privilegio_id);


--
-- TOC entry 5177 (class 2606 OID 17016)
-- Name: propuestas_empresa propuestas_empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.propuestas_empresa
    ADD CONSTRAINT propuestas_empresa_pkey PRIMARY KEY (id);


--
-- TOC entry 5137 (class 2606 OID 16768)
-- Name: proyecto_tutores proyecto_tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_pkey PRIMARY KEY (id_recurso, id_tutor);


--
-- TOC entry 5139 (class 2606 OID 16770)
-- Name: recurso_autores recurso_autores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_pkey PRIMARY KEY (id_recurso, id_autor);


--
-- TOC entry 5141 (class 2606 OID 16772)
-- Name: recurso_categorias recurso_categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_categorias
    ADD CONSTRAINT recurso_categorias_pkey PRIMARY KEY (id_recurso, id_categoria);


--
-- TOC entry 5145 (class 2606 OID 16774)
-- Name: recurso_clasificaciones recurso_clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT recurso_clasificaciones_pkey PRIMARY KEY (id_recurso, id_linea_investigacion);


--
-- TOC entry 5147 (class 2606 OID 16776)
-- Name: recurso_etiquetas recurso_etiquetas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT recurso_etiquetas_pkey PRIMARY KEY (id_recurso, id_etiqueta);


--
-- TOC entry 5149 (class 2606 OID 16778)
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- TOC entry 5151 (class 2606 OID 16780)
-- Name: registro_actividad registro_actividad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_pkey PRIMARY KEY (id);


--
-- TOC entry 5153 (class 2606 OID 16782)
-- Name: roles roles_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_nombre_key UNIQUE (nombre);


--
-- TOC entry 5155 (class 2606 OID 16784)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 5157 (class 2606 OID 16786)
-- Name: tipo_recurso tipo_recurso_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_nombre_key UNIQUE (nombre);


--
-- TOC entry 5159 (class 2606 OID 16788)
-- Name: tipo_recurso tipo_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_recurso
    ADD CONSTRAINT tipo_recurso_pkey PRIMARY KEY (id);


--
-- TOC entry 5161 (class 2606 OID 16790)
-- Name: tipo_tutor tipo_tutor_nombre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_nombre_key UNIQUE (nombre);


--
-- TOC entry 5163 (class 2606 OID 16792)
-- Name: tipo_tutor tipo_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_tutor
    ADD CONSTRAINT tipo_tutor_pkey PRIMARY KEY (id);


--
-- TOC entry 5165 (class 2606 OID 16794)
-- Name: tutores tutores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_cedula_key UNIQUE (cedula);


--
-- TOC entry 5167 (class 2606 OID 16796)
-- Name: tutores tutores_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutores
    ADD CONSTRAINT tutores_pkey PRIMARY KEY (id);


--
-- TOC entry 5131 (class 2606 OID 16798)
-- Name: postulaciones_estudiantes unique_postulacion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT unique_postulacion UNIQUE (id_investigacion, id_estudiante);


--
-- TOC entry 5169 (class 2606 OID 16800)
-- Name: usuarios usuarios_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_cedula_key UNIQUE (cedula);


--
-- TOC entry 5171 (class 2606 OID 16802)
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- TOC entry 5173 (class 2606 OID 16804)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 5175 (class 2606 OID 16806)
-- Name: visitantes visitantes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.visitantes
    ADD CONSTRAINT visitantes_pkey PRIMARY KEY (id);


--
-- TOC entry 5109 (class 1259 OID 16807)
-- Name: idx_detalles_inv_ofertada; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detalles_inv_ofertada ON public.detalles_investigaciones USING btree (id_investigacion_ofertada);


--
-- TOC entry 5142 (class 1259 OID 16808)
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- TOC entry 5143 (class 1259 OID 16809)
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


--
-- TOC entry 5213 (class 2620 OID 16810)
-- Name: recursos tg_auditoria_recursos_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_delete BEFORE DELETE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- TOC entry 5214 (class 2620 OID 16811)
-- Name: recursos tg_auditoria_recursos_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_recursos_insert AFTER INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_recursos();


--
-- TOC entry 5215 (class 2620 OID 16812)
-- Name: usuarios tg_auditoria_usuarios_delete; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_delete BEFORE DELETE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5216 (class 2620 OID 16813)
-- Name: usuarios tg_auditoria_usuarios_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_insert AFTER INSERT ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5217 (class 2620 OID 16814)
-- Name: usuarios tg_auditoria_usuarios_update; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tg_auditoria_usuarios_update AFTER UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.fn_auditoria_usuarios();


--
-- TOC entry 5178 (class 2606 OID 16815)
-- Name: accesos_recursos accesos_recursos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5179 (class 2606 OID 16820)
-- Name: accesos_recursos accesos_recursos_id_registro_actividad_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accesos_recursos
    ADD CONSTRAINT accesos_recursos_id_registro_actividad_fkey FOREIGN KEY (id_registro_actividad) REFERENCES public.registro_actividad(id) ON DELETE CASCADE;


--
-- TOC entry 5180 (class 2606 OID 16825)
-- Name: auditoria auditoria_usuario_responsable_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_usuario_responsable_fkey FOREIGN KEY (usuario_responsable) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5181 (class 2606 OID 16830)
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5186 (class 2606 OID 16835)
-- Name: detalles_proyectos detalles_proyectos_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE SET NULL;


--
-- TOC entry 5187 (class 2606 OID 16840)
-- Name: detalles_proyectos detalles_proyectos_id_investigacion_padre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_investigacion_padre_fkey FOREIGN KEY (id_investigacion_padre) REFERENCES public.recursos(id) ON DELETE SET NULL;


--
-- TOC entry 5188 (class 2606 OID 16845)
-- Name: detalles_proyectos detalles_proyectos_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_proyectos
    ADD CONSTRAINT detalles_proyectos_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5182 (class 2606 OID 16850)
-- Name: detalles_articulos detalles_revistas_id_editorial_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_editorial_fkey FOREIGN KEY (id_editorial) REFERENCES public.editoriales(id) ON DELETE SET NULL;


--
-- TOC entry 5183 (class 2606 OID 16855)
-- Name: detalles_articulos detalles_revistas_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5184 (class 2606 OID 16860)
-- Name: detalles_investigaciones fk_detalles_investigaciones_ofertada; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_ofertada FOREIGN KEY (id_investigacion_ofertada) REFERENCES public.investigaciones_ofertadas(id) ON DELETE SET NULL;


--
-- TOC entry 5185 (class 2606 OID 16865)
-- Name: detalles_investigaciones fk_detalles_investigaciones_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_investigaciones
    ADD CONSTRAINT fk_detalles_investigaciones_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5189 (class 2606 OID 16870)
-- Name: dimensiones_operativas fk_dimension_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dimensiones_operativas
    ADD CONSTRAINT fk_dimension_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5203 (class 2606 OID 16875)
-- Name: recurso_clasificaciones fk_dimension_operativa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_dimension_operativa FOREIGN KEY (id_dimension_operativa) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- TOC entry 5206 (class 2606 OID 16880)
-- Name: recurso_etiquetas fk_etiqueta_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_etiqueta_recurso FOREIGN KEY (id_etiqueta) REFERENCES public.etiquetas(id) ON DELETE CASCADE;


--
-- TOC entry 5190 (class 2606 OID 16885)
-- Name: investigaciones_ofertadas fk_inv_dimension; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_dimension FOREIGN KEY (id_dimension) REFERENCES public.dimensiones_operativas(id) ON DELETE SET NULL;


--
-- TOC entry 5191 (class 2606 OID 16890)
-- Name: investigaciones_ofertadas fk_inv_linea; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_linea FOREIGN KEY (id_linea) REFERENCES public.lineas_investigacion(id) ON DELETE RESTRICT;


--
-- TOC entry 5192 (class 2606 OID 16895)
-- Name: investigaciones_ofertadas fk_inv_profesor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.investigaciones_ofertadas
    ADD CONSTRAINT fk_inv_profesor FOREIGN KEY (id_profesor) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5204 (class 2606 OID 16900)
-- Name: recurso_clasificaciones fk_linea_investigacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_linea_investigacion FOREIGN KEY (id_linea_investigacion) REFERENCES public.lineas_investigacion(id) ON DELETE CASCADE;


--
-- TOC entry 5195 (class 2606 OID 16905)
-- Name: postulaciones_estudiantes fk_postulacion_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5196 (class 2606 OID 16910)
-- Name: postulaciones_estudiantes fk_postulacion_inv; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes
    ADD CONSTRAINT fk_postulacion_inv FOREIGN KEY (id_investigacion) REFERENCES public.investigaciones_ofertadas(id) ON DELETE CASCADE;


--
-- TOC entry 5205 (class 2606 OID 16915)
-- Name: recurso_clasificaciones fk_recurso; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_clasificaciones
    ADD CONSTRAINT fk_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5207 (class 2606 OID 16920)
-- Name: recurso_etiquetas fk_recurso_etiqueta; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_etiquetas
    ADD CONSTRAINT fk_recurso_etiqueta FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5193 (class 2606 OID 16925)
-- Name: lineas_investigacion lineas_investigacion_id_carrera_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lineas_investigacion
    ADD CONSTRAINT lineas_investigacion_id_carrera_fkey FOREIGN KEY (id_carrera) REFERENCES public.carreras(id) ON DELETE CASCADE;


--
-- TOC entry 5194 (class 2606 OID 16930)
-- Name: notificaciones notificaciones_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5197 (class 2606 OID 16935)
-- Name: preferencias_usuario preferencias_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.preferencias_usuario
    ADD CONSTRAINT preferencias_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- TOC entry 5211 (class 2606 OID 16940)
-- Name: roles privilegio_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT privilegio_fk FOREIGN KEY (privilegio_id) REFERENCES public.privilegios(privilegio_id) NOT VALID;


--
-- TOC entry 5198 (class 2606 OID 16945)
-- Name: proyecto_tutores proyecto_tutores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.detalles_proyectos(id_recurso) ON DELETE CASCADE;


--
-- TOC entry 5199 (class 2606 OID 16950)
-- Name: proyecto_tutores proyecto_tutores_id_tutor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_id_tutor_fkey FOREIGN KEY (id_tutor) REFERENCES public.tutores(id) ON DELETE CASCADE;


--
-- TOC entry 5200 (class 2606 OID 16955)
-- Name: proyecto_tutores proyecto_tutores_tipo_tutor_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proyecto_tutores
    ADD CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey FOREIGN KEY (tipo_tutor_id) REFERENCES public.tipo_tutor(id) ON DELETE SET NULL;


--
-- TOC entry 5201 (class 2606 OID 16960)
-- Name: recurso_autores recurso_autores_id_autor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_autor_fkey FOREIGN KEY (id_autor) REFERENCES public.autores(id) ON DELETE CASCADE;


--
-- TOC entry 5202 (class 2606 OID 16965)
-- Name: recurso_autores recurso_autores_id_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recurso_autores
    ADD CONSTRAINT recurso_autores_id_recurso_fkey FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


--
-- TOC entry 5208 (class 2606 OID 16970)
-- Name: recursos recursos_id_tipo_recurso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_id_tipo_recurso_fkey FOREIGN KEY (id_tipo_recurso) REFERENCES public.tipo_recurso(id) ON DELETE RESTRICT;


--
-- TOC entry 5209 (class 2606 OID 16975)
-- Name: registro_actividad registro_actividad_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE SET NULL;


--
-- TOC entry 5210 (class 2606 OID 16980)
-- Name: registro_actividad registro_actividad_id_visitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registro_actividad
    ADD CONSTRAINT registro_actividad_id_visitante_fkey FOREIGN KEY (id_visitante) REFERENCES public.visitantes(id) ON DELETE SET NULL;


--
-- TOC entry 5212 (class 2606 OID 16985)
-- Name: usuarios usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES public.roles(id) ON DELETE RESTRICT;


-- Completed on 2026-08-20 13:45:09

--
-- PostgreSQL database dump complete
--

\unrestrict jVeyHc51CPzX4B5uLIBIuaf2yh8S64o4P10rSahkBbDpkd6DdkaUsQrPoUA35fg

