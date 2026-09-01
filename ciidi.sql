--
-- PostgreSQL database dump
--

\restrict GANtUHTwOg6LQ7RjBjUo7h6egqgcaAOfaGxFrbcZrA3YbVZAq0rzn7XgKfmWOLq

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
    imagen_portada character varying(255),
    estado public.estado_curso_enum DEFAULT 'borrador'::public.estado_curso_enum NOT NULL,
    nota_minima_aprobacion numeric(5,2) DEFAULT 70.00 NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
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
    id_categoria integer,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    imagen_portada character varying(255) DEFAULT 'default_article.jpg'::character varying,
    resumen text
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
    url_repositorio text
);


ALTER TABLE public.detalles_proyectos OWNER TO postgres;

--
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
    descripcion text
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
    ejemplares_totales integer DEFAULT 1,
    ejemplares_disponibles integer DEFAULT 1,
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
    activo boolean DEFAULT true
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
-- Name: postulaciones_estudiantes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postulaciones_estudiantes ALTER COLUMN id SET DEFAULT nextval('public.postulaciones_estudiantes_id_seq'::regclass);


--
-- Name: privilegios privilegio_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios ALTER COLUMN privilegio_id SET DEFAULT nextval('public.privilegios_privilegio_id_seq'::regclass);


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

COPY public.accesos_recursos (id, id_registro_actividad, id_recurso, accion, fecha_acceso) FROM stdin;
\.


--
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
\.


--
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
13	ale	E-1231231
30	Mariano Rajoy	V-9857492
31	Alejandro Alicante	V-12312391
33	Luis Enrique Morelos	E-5184865
34	Jesús Montilla	V-30866991
35	Luis Miguel	V-17855689
36	Fausto Hernandez	V-21314132
37	miki	V-1234
41	aaaa aaa aaa	2222222
42	González González Miguel Alejandro	V-32621284
43	Rojo Ramírez José Alejandro	V-30536364
44	Ramírez Duarte Andrus Ruben	V-30469331
45	Pérez Marín José Gregorio	V-31177398
46	González Victoria	V-30931145
47	Estudiante Prueba Uno	V-30111222
48	Estudiante Prueba Dos	V-30333444
49	María Autor Prueba	V-31000111
50	Favian Herrera	V-30600230
51	Jesús Linares	V-30600950
52	Araujo Oliver	V-30866964
53	Nava Ailberth	V-30738034
54	David Lidmar	V-25111222
55	Estudiante Pruebas Uno	V-99887766
56	Estudiante Pruebas Dos	V-99887767
57	Daniel ángel	V-30379710
58	Araujo Rivas Isamar Andreina	V-31029609
59	Collantes Peña José Manuel	V-31602776
60	León Custode María Fernanda	V-31094982
61	Ocanto Morales ángel David	V-31239885
62	Briceño Brandon	V-29814531
63	Carrizo Franyeski	V-31602854
64	Ramírez Oriana	V-30671745
65	Valero Alejandro	V-29814164
66	Roberto Saavedra	V-30671594
67	Adrian Maldonado	V-30600276
68	Alberth Barreto	V-30438316
69	Escobar Morales Gelany Paola	V-33573889
70	Ruza Ferrebus Jhon David	V-32282366
71	Ortega Gonzalez Orlando Manuel	V-27889926
72	Piña Materan Juan Diego	V-31413623
73	Salcedo Angel Juan Diego	V-31008131
74	Andrés David Parra Cabrera	V-31029492
75	Jesús Alejandro Lobo Briceño	V-27677098
76	Orlando José González Moreno	V-31168262
77	Sebastián Jesús Blanco Rojas	V-30600412
78	Tsu David Galíndez	1231323
79	Estudiante Pruebas	V-99999999
80	Test Author	V-88888888
81	Analy De Los Angeles Hernández Cortéz	V-30601065
82	Anyela Alejandra Briceño Guerra	V-31413272
83	Abraham David Graterol Villamizar	V-31167863
84	Isaac José Figuera García	V-31239364
\.


--
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
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nombre) FROM stdin;
1	Tecnología
2	Ciencia
3	Ingeniería
4	Sociales
5	Innovación
6	Ciencias Sociales
7	Salud y Biociencias
8	Agronomía
\.


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cursos (id, id_docente, titulo, descripcion, imagen_portada, estado, nota_minima_aprobacion, fecha_creacion, fecha_actualizacion) FROM stdin;
1	4	Introducción a la Metodología de la Investigación	Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.	\N	publicado	70.00	2026-04-03 03:28:04	2026-04-03 03:28:04
2	4	Fundamentos de Inteligencia Artificial	Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.	\N	publicado	70.00	2026-04-03 03:28:04	2026-04-03 03:28:04
3	1	Normas APA y Redacción Científica	Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.	\N	publicado	60.00	2026-04-03 03:28:04	2026-04-03 03:53:35
4	1	tamaños de jose	los pn que jose ha tenido segun tamaño	\N	publicado	70.00	2026-04-03 04:40:03	2026-04-03 04:40:03
\.


--
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_articulos (id_recurso, id_editorial, volumen, numero, issn, id_categoria, created_at, imagen_portada, resumen) FROM stdin;
24	2	12	1	1	7	2026-07-05 12:02:34.181399	https://images2.imgbox.com/bb/d8/rjWe1Baf_o.png	\N
27	\N	12	13	12231	\N	2026-07-05 12:29:10.583616	art_1783268950_6a4a865688760.png	\N
28	4	3	4	1	5	2026-07-05 12:38:15.349753	art_1783269495_6a4a88774f639.png	\N
31	3	123	123	13	1	2026-07-05 12:57:22.541344	art_1783270642_6a4a8cf27d8de.png	Hey\r\nWho are you?\r\nMi memoria ha conservado lo que se ha llevado el viento\r\nY yo estoy estancado en esos tiempos\r\nCuando tú me amabas y con gran fulgor sentía tus besos\r\nDime, quítame esta duda\r\n¿Quién es esta extraña que se ha apoderado de tu ser?\r\n¿Dónde está la amante loca que me erizaba la piel?\r\nPorque ya tú no me tocas como lo hacía esa mujer\r\nAlgo no anda bien\r\nEsta noche me hago el interrogante\r\nY le pongo fin a la impostora, usurpadora\r\nExijo contigo una entrevista\r\nSospecho plagio a mi señora, mala imitadora\r\nDime, tengo unas preguntas\r\n¿Dónde fue bajo la lluvia que te di ese primer beso?\r\nDime, también, relátame el momento\r\nNúmero de alojamiento donde yo te hice mujer\r\nConfírmame\r\n¿Qué me enciende en el sexo?\r\n¿Qué me encanta de tu cuerpo?\r\nNuestra primer aventura\r\nQuiero detalles\r\n¿Será el cuello o el ombliguito\r\nTu punto favorito?\r\nPorque yo sí sé cuál es\r\nSi en verdad eres la original\r\nDemuéstramelo ahora\r\nEsta noche me hago el interrogante\r\nY le pongo fin a la impostora, usurpadora\r\nExijo contigo una entrevista\r\nSospecho plagio a mi señora, mala imitadora\r\nDime, tengo unas preguntas\r\n¿Dónde fue bajo la lluvia que te di ese primer beso?\r\nDime, también, relátame el momento\r\nNúmero de alojamiento donde yo te hice mujer\r\nConfírmame\r\n¿Qué me enciende en el sexo?\r\n¿Qué me encanta de tu cuerpo?\r\nNuestra primer aventura\r\nQuiero detalles\r\n¿Será tu cuello o el ombliguito\r\nTu punto favorito?\r\nPorque yo sí sé cual es\r\nSi en verdad eres la original\r\nDemuéstramelo ahora\r\nTú no era' así cuando te conocí\r\nTell me where she's at\r\n¿Quién es esta imitadora hoy en su lugar?\r\nTell me where she's at\r\nYo la extraño, ¿a dónde se me perdió?\r\nTell me where she's at?\r\nQue regrese mi amada porque tú\r\nNo eres tú
21	\N	1	2	\N	\N	2026-07-04 23:19:52.515406	art_1783278672_6a4aac508f21c.jpg	Y bebewiski
32	\N	3	1	\N	\N	2026-07-05 14:44:32.452702	art_1783277072_6a4aa6106837d.jpg	Y de repente entonces Victor le dice a Joel, montate, en mi motera, pero de repente entonces Joel le dice a Victor, desayuna con webo
25	3	1	2	1	8	2026-07-05 12:18:57.684684	https://images2.imgbox.com/61/60/Ofhk5A6w_o.png	Luisito el que comunica comunicativamente
34	2	1	1	12312	8	2026-07-05 15:24:54.658482	art_1783279494_6a4aaf8699ffd.jpg	Waos
35	6	1	1	1	4	2026-07-05 15:25:19.934157	art_1783279519_6a4aaf9fdd9a8.png	123213
36	4	12	123	123	2	2026-07-05 15:25:52.428559	art_1783279552_6a4aafc062085.jpg	123
37	1	12	123	123	3	2026-07-05 15:26:06.835471	default_article.jpg	123
38	\N	123	123	123	\N	2026-07-05 15:26:22.136069	default_article.jpg	123
39	6	123	123	123	4	2026-07-05 15:26:32.188843	default_article.jpg	123
40	6	\N	123	123123	7	2026-07-05 15:26:43.872553	default_article.jpg	123123
41	4	123	123	123	2	2026-07-05 15:28:49.036024	default_article.jpg	123123
42	1	123	1231	1231	2	2026-07-05 15:28:59.682383	default_article.jpg	123
43	4	213	123	123	7	2026-07-05 15:29:13.986916	art_1783280693_6a4ab435b8303.png	123123
44	1	11	1	162739	8	2026-07-05 16:15:08.182516	art_1783282508_6a4abb4c1007d.jpeg	uwu nya
\.


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_investigaciones (id_recurso, planteamiento_problema, objetivo_general, id_investigacion_ofertada, created_at) FROM stdin;
\.


--
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalles_proyectos (id_recurso, fecha_defensa, nivel_academico, resumen, id_carrera, comunidad_beneficiada, palabras_clave, created_at, id_investigacion_padre, trayecto, url_repositorio) FROM stdin;
46	2025-11-20	Pregrado	Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.	1	Estudiantes de Computaci¢n Gr fica	Lua, TIC-80, Retro, GameDev, M quina de Estados	2026-07-05 17:21:44.350197	\N	Trayecto III	\N
47	2026-07-02	Pregrado	Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.	1	Laboratorios de Arquitectura del Computador	Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy	2026-07-05 17:21:44.350197	\N	Trayecto IV	\N
48	2026-05-10	Pregrado	Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.	1	Departamento de Sistemas de la Universidad	Microkernel, PHP, PostgreSQL, MVC, Arquitectura	2026-07-05 17:21:44.350197	\N	Trayecto I	\N
49	2026-03-15	Pregrado	Desarrollo de un sistema tradicional para optimizar los m‚todos y procedimientos del inventario m‚dico. Sigue un patr¢n arquitect¢nico modular para agilizar los procesos organizacionales.	1	Ambulatorio Urbano Tipo II	Sistemas de Informaci¢n, PostgreSQL, Gesti¢n, Inventario	2026-07-05 17:39:35.498485	\N	Trayecto II	\N
50	2026-04-22	Pregrado	Aplicaci¢n interactiva dise¤ada como medio did ctico para facilitar los procesos de ense¤anza. Combina fundamentos comunicacionales y l¢gicos mediante una interfaz interactiva de alto rendimiento.	1	µrea de Ciencias B sicas de la Instituci¢n	Edum tica, Software Educativo, Multimedia, µlgebra	2026-07-05 17:39:35.498485	\N	Trayecto III	\N
51	2025-07-10	Pregrado	Dise¤o de un sistema distribuido cooperativo entre clientes y un servidor centralizado. Permite la gesti¢n din mica de solicitudes concurrentes controlando de manera efectiva las peticiones HTTP contra la base de datos.	1	Coordinaci¢n de Control de Estudios	Web, Cliente-Servidor, PHP, PostgreSQL	2026-07-05 17:39:35.498485	\N	Trayecto IV	\N
52	2026-06-18	Pregrado	Herramienta de simulaci¢n orientada al testeo preventivo de la transmisi¢n de datos. Permite modelar el comportamiento de las decisiones de routing antes de iniciar el despliegue f¡sico de una infraestructura de red.	1	Laboratorio de Redes y Telecomunicaciones	Simulaci¢n, Routing, Algoritmos, Redes, Topolog¡a	2026-07-05 17:39:35.498485	\N	Trayecto I	\N
57	2026-07-05	Pregrado	ahsdhajsdhahakjfhafggfjhgfkjh	1	asdasdasdasd	asdasdasdasdasd	2026-07-05 18:21:33.639701	\N	Trayecto II	\N
59	2026-11-10	Pregrado	El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable.	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”	Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa	2026-08-04 09:22:58.539505	\N	Trayecto IV	\N
69	2026-08-04	Pregrado	El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.	1	Centro Clínico “María Edelmira Araujo”	Soporte técnico, correctivo, preventivo, software, hardware	2026-08-04 09:58:54.904249	\N	Trayecto II	\N
72	2026-08-04	Pregrado	Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.	1	Smarthphone World C		2026-08-04 10:11:11.510708	\N	Trayecto I	\N
78	2026-08-05	Pregrado	Este es un resumen de prueba automatizada para verificar la carga por lotes via AJAX.	1	Comunidad de Pruebas	Prueba, AJAX, Lotes, PHP	2026-08-05 09:43:26.945146	\N	Trayecto III	\N
45	2026-06-15	Pregrado	Dise¤o e implementaci¢n de un motor de renderizado ligero y de alto rendimiento. Se evit¢ el uso de frameworks pesados para garantizar una ejecuci¢n "metal pure", optimizando el consumo de RAM y CPU en equipos de bajos recursos.	1	Comunidad de Desarrolladores Independientes	Rust, Tauri, Novela Visual, Nativo, Optimizaci¢n	2026-07-05 17:21:44.350197	\N	Trayecto II	\N
80	2026-08-05	Pregrado	El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones	1	Centro Clínico “María Edelmira Araujo”, S	Soporte técnico, correctivo, preventivo, software, hardware	2026-08-05 09:48:05.633265	\N	Trayecto I	\N
81	2026-08-05	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-05 09:48:05.72936	\N	Trayecto II	\N
82	2026-08-05	Pregrado	Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver	1	CAIPA Trujillo  ------------------------------------------------Naturaleza de la Comunidad: El CAIPA-Trujillo, Valera Estado Trujillo		2026-08-05 09:48:05.817964	\N	Trayecto III	\N
83	2026-08-05	Pregrado	La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.	1	Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957		2026-08-05 09:48:05.91852	\N	Trayecto IV	\N
84	2026-08-05	Pregrado	El propósito principal de este proyecto es realizar soporte técnico a los equipos de la institución (Escuela Técnica Comercial Madre Rafols)del Estado Trujillo municipio Valera. Y de igual forma dictar varias sesiones de capacitación formativas a los estudiantes de dicha institución cerca de software, hardware, partes, usos adecuados de un computador, donde podamos ofrecer nuevos conocimientos a los estudiantes. Todo esto aplicando nuevas tecnologías de aprendizaje que permitan el crecimiento y desarrollo del área de informática de la institución	1	Escuela Técnica Comercial Madre Rafols		2026-08-05 09:48:06.00888	\N	Trayecto I	\N
85	2026-08-05	Pregrado	Resumen de prueba automatizada para verificación de duplicados.	1	Comunidad Test	Prueba, Duplicados, PST	2026-08-05 09:56:42.188313	\N	Trayecto II	\N
86	2026-08-05	Pregrado	Resumen de prueba automatizada para verificación de duplicados.	1	Comunidad Test	Prueba, Duplicados, PST	2026-08-05 10:02:04.774776	\N	Trayecto III	\N
87	2026-08-05	Pregrado	Resumen de prueba automatizada para verificación de duplicados.	1	Comunidad Test	Prueba, Duplicados, PST	2026-08-05 10:34:46.219889	\N	Trayecto IV	\N
116	2026-08-31	Doctorado	La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.	1	Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957		2026-08-31 09:47:06.862144	\N	\N	\N
58	2026-07-07	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio. Palabras clave: Gestión doc	1	asdasdasdasd	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-07-07 00:01:54.74783	\N	Trayecto III	\N
79	2026-08-05	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-05 09:45:15.201621	\N	Trayecto IV	\N
88	2026-08-10	Pregrado	Según Arboleda (2014), un proyecto representa un esfuerzo temporal diseñado para producir un resultado o entregable único de forma gradual. Para enriquecer la fundamentación, Project Management Institute (2021), lo define como un esfuerzo temporal emprendido para crear un producto, servicio o resultado único.	1	Corporación Eléctrica Nacional (CORPOELEC) de Venezuela		2026-08-10 10:26:58.264555	\N	Trayecto I	\N
89	2018-01-10	Pregrado	El presente proyecto sociotecnológico se centra en el desarrollo de un módulo avanzado para la administración y proyección de las líneas de investigación del PNFI, en el cual la innovación principal radica en la integración de modelos de Inteligencia Artificial (Machine Learning) orientados al análisis predictivo, esta herramienta procesa el volumen y la tipología de las investigaciones registradas para identificar tendencias emergentes, predecir el crecimiento de áreas temáticas y asistir al Comité Científico Investigador en la toma de decisiones estratégicas, todo ello operando sobre la arquitectura base del Sistema Integral de Gestión.	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Líneas de investigación, PNFI, Machine Learning, Análisis predictivo, Toma de decisiones, Comité científico, Gestión del conocimiento, Sistema integral de gestión	2026-08-10 10:35:39.007693	\N	Trayecto IV	\N
90	2026-08-10	Pregrado	Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver	1	CAIPA Trujillo		2026-08-10 10:45:42.226083	\N	Trayecto I	\N
91	2026-08-10	Pregrado	En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico	1	Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr	sistema informático, gestión académica, proyectos PCI, información académica, automatización	2026-08-10 10:52:47.26864	\N	Trayecto I	\N
92	2026-08-10	Pregrado	La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.	1	Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957		2026-08-10 11:34:04.575523	\N	Trayecto I	\N
93	2026-08-10	Pregrado	En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico	1	Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr	sistema informático, gestión académica, proyectos PCI, información académica, automatización	2026-08-10 11:34:42.145006	\N	Trayecto I	\N
94	2026-08-10	Pregrado	Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.	1	Smarthphone World C		2026-08-10 11:40:58.141559	\N	Trayecto I	\N
97	2026-08-10	Pregrado	En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico	1	Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr	sistema informático, gestión académica, proyectos PCI, información académica, automatización	2026-08-10 12:03:37.275866	\N	Trayecto I	\N
99	2026-08-10	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-10 12:10:58.390178	\N	Trayecto I	\N
100	2026-08-10	Pregrado	El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes	1	Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry	App, Aplicación móvil, Coordinación, Ascensos	2026-08-10 12:14:42.285533	\N	Trayecto I	\N
108	2026-08-10	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-10 12:20:17.959675	\N	Trayecto I	\N
109	2026-08-11	Pregrado	El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.	1	Centro Clínico “María Edelmira Araujo”	Soporte técnico, correctivo, preventivo, software, hardware	2026-08-11 10:10:03.006883	\N	Trayecto I	\N
110	2026-08-25	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio.	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-25 19:01:06.312899	\N	Trayecto I	\N
111	2026-08-25	Pregrado	El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	Escuela Técnica Comercial “Madre Rafols”	computadoras, mantenimiento, instalación, hardware, software	2026-08-25 19:01:06.640902	\N	Trayecto I	\N
112	2026-08-25	Pregrado	Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.	1	Smarthphone World C		2026-08-25 19:01:06.749048	\N	Trayecto I	\N
113	2026-08-27	Pregrado	El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr	Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP	2026-08-27 09:08:37.073105	\N	Trayecto I	\N
114	2026-08-27	Pregrado	El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador	1	Escuela Técnica Comercial “Madre Rafols”	computadoras, mantenimiento, instalación, hardware, software	2026-08-27 10:17:48.017574	\N	Trayecto I	\N
117	2026-08-31	Doctorado	El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable	1	Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” NUES Dr	Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa	2026-08-31 10:08:06.559751	\N	\N	https://github.com/Zhailox/proyect_CIIDI
\.


--
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
-- Data for Name: editoriales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.editoriales (id, nombre) FROM stdin;
1	IEEE
2	ACM
3	Springer
4	Elsevier
5	UPTTMBI Ediciones
6	UNESCO
7	SciELO Venezuela
\.


--
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.etiquetas (id, nombre, color_hex) FROM stdin;
1	Inteligencia Artificial	#0ea5e9
2	Machine Learning	#0ea5e9
3	Educación	#0ea5e9
4	Redes Neuronales	#0ea5e9
5	Desarrollo Web	#0ea5e9
\.


--
-- Data for Name: historico_versiones_pst; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.historico_versiones_pst (id, id_recurso, archivo_pdf, usuario_id, motivo, created_at) FROM stdin;
\.


--
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.investigaciones_ofertadas (id, id_profesor, titulo, planteamiento_problema, objetivo_general, id_linea, id_dimension, cupos_disponibles, estado, fecha_creacion) FROM stdin;
\.


--
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lineas_investigacion (id, nombre, id_carrera, descripcion) FROM stdin;
7	SISTEMAS DE INFORMACION Y MODELADO DE DATOS	1	Desarrollar y gestionar sistemas de informaci¢n dentro del  mbito social. Aplicando soluciones efectivas para el uso adecuado y ¢ptimo de los sistemas de informaci¢n.
8	EDUMATICA	1	Aplicar las Tecnolog¡as de la Informaci¢n y Comunicaci¢n (TIC) para apoyar el proceso de aprendizaje, y as¡ contribuir al mejoramiento de la educaci¢n en todos sus niveles.
9	APLICACIONES WEB	1	Desarrollar aplicaciones Web para cubrir las necesidades de gesti¢n, control e intercambio de informaci¢n de la empresa y el entorno que la rodea a trav‚s de la Internet o Intranet.
10	REDES Y TELECOMUNICACIONES	1	Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisi¢n de datos, como tambi‚n la detecci¢n de fallas dentro de una red.
\.


--
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
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.postulaciones_estudiantes (id, id_investigacion, id_estudiante, mensaje_motivacion, estado, fecha_postulacion, fecha_respuesta) FROM stdin;
\.


--
-- Data for Name: preferencias_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.preferencias_usuario (id_usuario, tema, notificaciones_sistema) FROM stdin;
1	ocean	t
3	sunset	t
4	ocean	t
6	sunset	t
\.


--
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
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.proyecto_tutores (id_recurso, id_tutor, tipo_tutor_id) FROM stdin;
57	7	3
57	8	2
57	9	4
58	10	3
58	11	2
58	12	4
78	16	3
85	16	3
86	16	3
87	16	3
90	17	3
90	18	2
90	19	4
91	10	3
92	10	3
92	20	2
92	21	4
93	10	3
94	22	3
94	10	3
97	10	3
97	25	2
99	28	2
99	10	4
100	29	3
100	30	2
100	31	4
108	28	2
108	10	4
109	35	3
109	36	4
110	28	2
110	10	4
111	37	3
111	38	4
112	22	3
113	28	2
113	10	4
114	37	3
114	38	4
116	10	3
116	20	2
116	21	4
117	10	2
117	39	4
\.


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_autores (id_recurso, id_autor) FROM stdin;
3	1
27	32
28	33
31	34
21	29
21	1
32	13
34	31
35	32
36	9
37	13
38	31
39	31
40	31
41	31
42	32
43	31
43	36
44	37
57	41
58	42
58	43
58	44
58	45
59	46
69	50
69	51
72	52
72	53
78	55
78	56
79	42
79	43
79	44
79	45
80	50
80	51
80	57
81	42
81	43
81	44
81	45
82	58
82	59
82	60
82	61
83	62
83	63
83	64
83	65
84	66
84	67
84	68
85	55
86	55
87	55
88	69
88	70
89	34
89	71
89	72
89	73
90	58
90	59
90	60
90	61
91	74
91	75
91	76
91	77
92	62
92	63
92	64
92	65
93	74
93	75
93	76
93	77
94	52
94	53
97	74
97	75
97	76
97	77
99	42
99	43
99	44
99	45
100	78
108	42
108	43
108	44
108	45
109	50
109	51
109	57
110	42
110	43
110	44
110	45
111	81
111	82
111	83
111	84
112	52
112	53
113	42
113	43
113	44
113	45
114	81
114	82
114	83
114	84
116	62
116	63
116	64
116	65
117	46
\.


--
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_clasificaciones (id_recurso, id_linea_investigacion, id_dimension_operativa) FROM stdin;
49	7	5
50	8	10
51	9	16
52	10	18
57	9	17
58	7	\N
59	7	9
69	8	14
72	7	5
78	9	\N
79	10	18
80	7	5
81	10	18
82	7	9
83	8	12
84	8	10
85	9	\N
86	9	\N
87	9	\N
88	7	9
89	7	9
90	7	9
91	7	5
92	8	12
93	7	5
94	7	5
97	7	5
99	10	18
100	9	17
108	10	18
109	7	5
110	9	\N
111	8	13
112	7	5
113	10	18
114	8	13
116	8	12
117	7	7
\.


--
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.recurso_etiquetas (id_recurso, id_etiqueta) FROM stdin;
24	5
24	3
24	1
24	2
27	5
27	3
28	5
28	3
28	1
28	2
31	5
31	3
31	1
31	4
21	5
21	3
21	2
21	4
32	2
25	5
25	4
34	5
34	4
35	3
35	1
35	2
36	5
36	1
36	4
37	2
38	5
38	3
40	5
40	4
42	5
43	3
44	3
44	1
\.


--
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
24	Pepe	3	2026	1	1	https://www.wikipedia.org/
27	Que la guagua	3	2026	1	1	https://www.wikipedia.org/
28	En los tiempos de los apostoles	3	2026	1	1	https://www.wikipedia.org/
31	Imitadora	3	2026	1	1	https://www.wikipedia.org/
21	La Bebecita Bebelin	3	2026	1	1	https://www.youtube.com/
32	Manguagua	3	2026	1	1	https://www.wikipedia.org/
25	Luisito comunicando	3	2026	1	1	https://www.wikipedia.org/
34	Waos 1	3	2026	1	1	https://www.wikipedia.org/
35	Waos 2	3	2026	1	1	https://www.wikipedia.org/
36	Waos 3	3	2026	1	1	https://www.wikipedia.org/
37	23123	3	2026	1	1	https://www.wikipedia.org/
38	23	3	2020	1	1	https://www.wikipedia.org/
39	123123	3	2026	1	1	https://www.wikipedia.org/
40	123	3	2026	1	1	https://www.wikipedia.org/
41	123123	3	2026	1	1	https://www.wikipedia.org/
42	123123	3	2026	1	1	https://www.wikipedia.org/
43	123123123123	3	2026	1	1	https://www.wikipedia.org/
44	auuuu	3	2026	1	1	https://www.youtube.com/watch?v=s8PH7SmGGqc&list=RDs8PH7SmGGqc&start_radio=1
45	Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri	1	2026	2	2	motor_rust_tauri_v1.pdf
46	Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80	1	2025	1	1	juego_aislamiento_tic80.pdf
47	Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478	1	2026	3	3	restauracion_pentium4.pdf
48	Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro	1	2026	1	1	microkernel_php_routing.pdf
49	Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos	1	2026	1	1	proyecto_inventario_medico.pdf
50	Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal	1	2026	1	1	software_educativo_algebra.pdf
51	Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas	1	2025	1	1	plataforma_web_citas.pdf
52	Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas	1	2026	1	1	simulador_routing_topologias.pdf
57	hola adios	1	2026	1	1	documentos/pst/pst_hola_adios_1783290093.pdf
58	Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	documentos/pst/pst_sistema_integral_de_gesti__n_d_1783396914.pdf
59	SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ	1	2026	1	1	documentos/pst/pst_sistema_de_optimizaci__n_basad_1785849778.pdf
69	NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .	1	2023	1	1	documentos/pst/pst_nues_dr__pablo_viloria_____la__1785851934.pdf
72	SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.	1	2026	1	1	documentos/pst/pst_sistema_integral_de_gesti__n_c_1785852671.pdf
78	PST Prueba Carga por Lotes - 20260805134326	1	2026	1	1	documentos/pst/pst_pst_prueba_carga_por_lotes___2_1785937406.pdf
79	Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937515.pdf
80	NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .	1	2023	1	1	documentos/pst/pst_nues_dr__pablo_viloria_____la__1785937685.pdf
81	Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937685.pdf
82	OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0	1	2026	1	1	documentos/pst/pst_optimizaci__n_del_sistema_de_i_1785937685.pdf
83	SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO	1	2026	1	1	documentos/pst/pst_sistema_inteligente_para_la_ge_1785937685.pdf
84	SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS	1	2023	1	1	documentos/pst/pst_soporte_tecnico_a_equipos_y_us_1785937686.pdf
85	PST Prueba Duplicados - 20260805135642	1	2026	1	1	documentos/pst/pst_pst_prueba_duplicados___202608_1785938202.pdf
86	PST Prueba Duplicados - 20260805140204	1	2026	1	1	documentos/pst/pst_pst_prueba_duplicados___202608_1785938524.pdf
87	PST Prueba Duplicados - 20260805143446	1	2026	1	1	documentos/pst/pst_pst_prueba_duplicados___202608_1785940486.pdf
88	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC	1	2021	1	1	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1786372014_343.docx
89	MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ	1	2026	1	1	storage/documentos/pst/pst_m__dulo_inteligente_basado_en__1786372449_773.docx
90	OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0	1	2026	1	1	\N
91	Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry	1	2026	1	1	storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786373559_627.docx
94	SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222	1	2026	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1786376454_286.docx
93	Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry	1	2026	1	1	storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786376074_943.docx
92	SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO	1	2026	1	1	storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1786376037_906.docx
112	SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.	1	2026	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1787698715_771.docx
97	Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry	1	2026	1	1	storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786377809_260.docx
99	Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378254_697.docx
100	il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas	1	2023	1	1	\N
108	Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378813_891.docx
109	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”	1	2023	1	1	storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1786457302_317.pdf
110	Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales	1	2025	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787698529_393.docx
111	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”	1	2024	1	1	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787698700_582.pdf
113	Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales	1	2025	1	1	storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787836105_952.docx
114	SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”	1	2024	1	1	storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787840266_406.pdf
116	SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO	1	2026	1	1	storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1788184014_102.docx
117	SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ	1	2026	1	1	PST 4 David LidmarFinal.docx
\.


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registro_actividad (id, id_usuario, id_visitante, fecha_inicial, ultima_actividad, conteo_accesos) FROM stdin;
1	1	\N	2026-03-23 14:49:58	2026-03-23 14:49:58	1
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, nombre, privilegio_id) FROM stdin;
3	Estudiante	1
1	Super Administrador	4
4	Profesor	2
2	Comite	3
\.


--
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_recurso (id, nombre, descripcion) FROM stdin;
1	PST / Trabajo de Grado	Proyectos Socio-Tecnológicos y Tesis
2	Investigación Docente	Papers y artículos de investigación del personal académico
3	Material de Apoyo / Didáctico	Recursos adicionales para estudiantes
\.


--
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_tutor (id, nombre, descripcion) FROM stdin;
1	Director	Director principal del proyecto
2	Coordinador	Asesor metodológico
3	Tutor Académico	Especialista en el área
4	Tutor Comunitario	Representante de la comunidad
\.


--
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
\.


--
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
\.


--
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.visitantes (id, ip_address, user_agent, pagina_origen) FROM stdin;
\.


--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, false);


--
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 204, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 84, true);


--
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 8, true);


--
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 4, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 19, true);


--
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 7, true);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 5, true);


--
-- Name: historico_versiones_pst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.historico_versiones_pst_id_seq', 1, false);


--
-- Name: investigaciones_ofertadas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.investigaciones_ofertadas_id_seq', 1, false);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 10, true);


--
-- Name: notificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_id_seq', 6, true);


--
-- Name: postulaciones_estudiantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.postulaciones_estudiantes_id_seq', 1, false);


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 6, true);


--
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 117, true);


--
-- Name: registro_actividad_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registro_actividad_id_seq', 1, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 4, true);


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- Name: tutores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutores_id_seq', 39, true);


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 11, true);


--
-- Name: visitantes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.visitantes_id_seq', 1, false);


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
-- Name: autores autores_cedula_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_cedula_key UNIQUE (cedula);


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
-- Name: notificaciones notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id);


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
-- Name: privilegios privilegios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios
    ADD CONSTRAINT privilegios_pkey PRIMARY KEY (privilegio_id);


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
-- Name: detalles_articulos detalles_revistas_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalles_articulos
    ADD CONSTRAINT detalles_revistas_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categorias(id) ON DELETE SET NULL;


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
-- Name: roles privilegio_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT privilegio_fk FOREIGN KEY (privilegio_id) REFERENCES public.privilegios(privilegio_id) NOT VALID;


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

\unrestrict GANtUHTwOg6LQ7RjBjUo7h6egqgcaAOfaGxFrbcZrA3YbVZAq0rzn7XgKfmWOLq

