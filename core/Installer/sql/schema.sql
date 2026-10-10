--
-- PostgreSQL database dump
--


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
-- Name: vector; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public;


--
-- Name: EXTENSION vector; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION vector IS 'vector data type and ivfflat and hnsw access methods';


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

        -- Aquí se removió la referencia a NEW.ejemplares_totales que hacía explotar la BD

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
    orcid character varying(50) DEFAULT NULL::character varying,
    biografia text,
    pagina_web character varying(255) DEFAULT NULL::character varying,
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
    nivel character varying(50) DEFAULT 'B sico'::character varying,
    duracion character varying(80),
    cupo_maximo integer,
    fecha_inicio date,
    fecha_fin date,
    url_video_preview text,
    estado_inscripcion character varying(50) DEFAULT 'Abierta'::character varying
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
-- Name: detalles_articulos; Type: TABLE; Schema: public; Owner: -
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
    vector_semantico public.vector(384),
    id_trayecto integer,
    vistas integer DEFAULT 0 NOT NULL
);


--
-- Name: COLUMN detalles_proyectos.vistas; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.detalles_proyectos.vistas IS 'Contador de visualizaciones de la ficha del proyecto';


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
    motivo character varying(255) DEFAULT 'Actualizaci¢n'::character varying,
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
    CONSTRAINT postulaciones_estudiantes_estado_check CHECK (((estado)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Aceptado'::character varying)::text, ('Rechazado'::character varying)::text])))
);


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
    motivo_rechazo text
);


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

COMMENT ON TABLE public.proyecto_carreras_vinculadas IS 'Relación N:M para vincular proyectos PST con una o varias carreras secundarias adicionales a su carrera base.';


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
    privilegio_id integer DEFAULT 1 CONSTRAINT roles_privilegios_id_not_null NOT NULL
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
    activation_token character varying(255) DEFAULT NULL::character varying
);


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
-- Name: auditoria auditoria_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auditoria
    ADD CONSTRAINT auditoria_pkey PRIMARY KEY (id);


--
-- Name: autores autores_orcid_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.autores
    ADD CONSTRAINT autores_orcid_key UNIQUE (orcid);


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
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


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

CREATE INDEX idx_detalles_vector_hnsw ON public.detalles_proyectos USING hnsw (vector_semantico public.vector_cosine_ops) WITH (m='16', ef_construction='64');


--
-- Name: idx_detalles_vector_null; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_detalles_vector_null ON public.detalles_proyectos USING btree (id_recurso) WHERE (vector_semantico IS NULL);


--
-- Name: idx_pcv_carrera; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_pcv_carrera ON public.proyecto_carreras_vinculadas USING btree (id_carrera);


--
-- Name: idx_pcv_recurso; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_pcv_recurso ON public.proyecto_carreras_vinculadas USING btree (id_recurso);


--
-- Name: idx_recurso_clasif_dimension; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_recurso_clasif_dimension ON public.recurso_clasificaciones USING btree (id_dimension_operativa);


--
-- Name: idx_recurso_clasif_linea; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_recurso_clasif_linea ON public.recurso_clasificaciones USING btree (id_linea_investigacion);


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
-- Name: cursos cursos_id_docente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_id_docente_fkey FOREIGN KEY (id_docente) REFERENCES public.usuarios(id) ON DELETE CASCADE;


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
-- Name: historico_versiones_pst fk_version_recurso; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.historico_versiones_pst
    ADD CONSTRAINT fk_version_recurso FOREIGN KEY (id_recurso) REFERENCES public.recursos(id) ON DELETE CASCADE;


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



-- DATOS SEMILLA DE CATALOGOS INSTITUCIONALES

--
-- PostgreSQL database dump
--


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
-- Data for Name: carreras; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.carreras VALUES (1, 'PNF en Informática', 'Ingeniería y TSU en Informática');
INSERT INTO public.carreras VALUES (2, 'PNF en Electricidad', 'Ingeniería y TSU en Electricidad');
INSERT INTO public.carreras VALUES (3, 'PNF en Administración', 'Licenciatura y TSU en Administración');
INSERT INTO public.carreras VALUES (4, 'PNF en Agroalimentación', 'Ingeniería y TSU Agroalimentario');
INSERT INTO public.carreras VALUES (5, 'PNF en Construcción Civil', 'Ingeniería y TSU en Construcción Civil');


--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: -
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
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.lineas_investigacion VALUES (8, 'EDUMATICA', 1, 'Aplicar las Tecnologías de la Información y Comunicación (TIC) para apoyar el proceso de aprendizaje, y asá contribuir al mejoramiento de la educación en todos sus niveles.', true);
INSERT INTO public.lineas_investigacion VALUES (10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisión de datos, como tambi‚n la detección de fallas dentro de una red.', true);
INSERT INTO public.lineas_investigacion VALUES (9, 'DESARROLLO DE APLICACIONES', 1, 'Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.', true);
INSERT INTO public.lineas_investigacion VALUES (7, 'SISTEMAS DE INFORMACION Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de información dentro del  ámbito social. Aplicando soluciones efectivas para el uso adecuado y óptimo de los sistemas de información.', true);


--
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: -
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
-- Data for Name: matriz_rbac; Type: TABLE DATA; Schema: public; Owner: -
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
-- Data for Name: niveles_academicos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.niveles_academicos VALUES (1, 'Pregrado', 'Pregrado', 'Estudios conducentes a título universitario de pregrado o ingeniería', true, 1, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (3, 'Maestria', 'Maestría', 'Estudios de postgrado de investigación científica y magíster', false, 3, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (4, 'Doctorado', 'Doctorado', 'Máximo grado académico de investigación epistémica', false, 4, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:19:32.998269');
INSERT INTO public.niveles_academicos VALUES (2, 'Especializacion', 'Especialización', 'Estudios de postgrado para especialización técnica', false, 2, true, '2026-10-04 16:19:32.998269', '2026-10-04 16:53:32.699637');


--
-- Data for Name: privilegios; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.privilegios VALUES (1, 0);
INSERT INTO public.privilegios VALUES (2, 1);
INSERT INTO public.privilegios VALUES (3, 2);
INSERT INTO public.privilegios VALUES (4, 3);
INSERT INTO public.privilegios VALUES (5, 4);
INSERT INTO public.privilegios VALUES (6, 5);
INSERT INTO public.privilegios VALUES (10, 6);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.roles VALUES (1, 'Super Administrador', 1);
INSERT INTO public.roles VALUES (3, 'Estudiantes', 6);
INSERT INTO public.roles VALUES (2, 'Comité', 2);
INSERT INTO public.roles VALUES (4, 'Docentes', 3);


--
-- Data for Name: tipo_recurso; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tipo_recurso VALUES (1, 'PST / Trabajo de Grado', 'Proyectos Socio-Tecnológicos y Tesis');
INSERT INTO public.tipo_recurso VALUES (2, 'Investigación Docente', 'Papers y artículos de investigación del personal académico');
INSERT INTO public.tipo_recurso VALUES (3, 'Material de Apoyo / Didáctico', 'Recursos adicionales para estudiantes');


--
-- Data for Name: tipo_tutor; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.tipo_tutor VALUES (1, 'Director', 'Director principal del proyecto');
INSERT INTO public.tipo_tutor VALUES (2, 'Coordinador', 'Asesor metodológico');
INSERT INTO public.tipo_tutor VALUES (3, 'Tutor Académico', 'Especialista en el área');
INSERT INTO public.tipo_tutor VALUES (4, 'Tutor Comunitario', 'Representante de la comunidad');


--
-- Data for Name: trayectos; Type: TABLE DATA; Schema: public; Owner: -
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
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categorias_id_seq', 18, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 24, true);


--
-- Name: lineas_investigacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 11, true);


--
-- Name: niveles_academicos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.niveles_academicos_id_seq', 6, true);


--
-- Name: privilegios_privilegio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.privilegios_privilegio_id_seq', 10, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: tipo_recurso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipo_recurso_id_seq', 3, true);


--
-- Name: tipo_tutor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipo_tutor_id_seq', 4, true);


--
-- Name: trayectos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.trayectos_id_seq', 20, true);


--
-- PostgreSQL database dump complete
--

