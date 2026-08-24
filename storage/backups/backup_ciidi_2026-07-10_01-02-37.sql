--
-- PostgreSQL database dump
--

\restrict CmnSYvg5hIZ5KcRsygOzkH74Jyb0fNLYORcMNtN9zF7Y65eeHq8UFGwevf1KJcn

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

ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_id_rol_fkey;
ALTER TABLE ONLY public.registro_actividad DROP CONSTRAINT registro_actividad_id_visitante_fkey;
ALTER TABLE ONLY public.registro_actividad DROP CONSTRAINT registro_actividad_id_usuario_fkey;
ALTER TABLE ONLY public.recursos DROP CONSTRAINT recursos_id_tipo_recurso_fkey;
ALTER TABLE ONLY public.recurso_autores DROP CONSTRAINT recurso_autores_id_recurso_fkey;
ALTER TABLE ONLY public.recurso_autores DROP CONSTRAINT recurso_autores_id_autor_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_id_tutor_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_id_recurso_fkey;
ALTER TABLE ONLY public.roles DROP CONSTRAINT privilegio_fk;
ALTER TABLE ONLY public.preferencias_usuario DROP CONSTRAINT preferencias_usuario_id_usuario_fkey;
ALTER TABLE ONLY public.notificaciones DROP CONSTRAINT notificaciones_id_usuario_fkey;
ALTER TABLE ONLY public.lineas_investigacion DROP CONSTRAINT lineas_investigacion_id_carrera_fkey;
ALTER TABLE ONLY public.recurso_etiquetas DROP CONSTRAINT fk_recurso_etiqueta;
ALTER TABLE ONLY public.recurso_clasificaciones DROP CONSTRAINT fk_recurso;
ALTER TABLE ONLY public.postulaciones_estudiantes DROP CONSTRAINT fk_postulacion_inv;
ALTER TABLE ONLY public.postulaciones_estudiantes DROP CONSTRAINT fk_postulacion_estudiante;
ALTER TABLE ONLY public.recurso_clasificaciones DROP CONSTRAINT fk_linea_investigacion;
ALTER TABLE ONLY public.investigaciones_ofertadas DROP CONSTRAINT fk_inv_profesor;
ALTER TABLE ONLY public.investigaciones_ofertadas DROP CONSTRAINT fk_inv_linea;
ALTER TABLE ONLY public.investigaciones_ofertadas DROP CONSTRAINT fk_inv_dimension;
ALTER TABLE ONLY public.recurso_etiquetas DROP CONSTRAINT fk_etiqueta_recurso;
ALTER TABLE ONLY public.recurso_clasificaciones DROP CONSTRAINT fk_dimension_operativa;
ALTER TABLE ONLY public.dimensiones_operativas DROP CONSTRAINT fk_dimension_linea;
ALTER TABLE ONLY public.detalles_investigaciones DROP CONSTRAINT fk_detalles_investigaciones_recurso;
ALTER TABLE ONLY public.detalles_investigaciones DROP CONSTRAINT fk_detalles_investigaciones_ofertada;
ALTER TABLE ONLY public.detalles_articulos DROP CONSTRAINT detalles_revistas_id_recurso_fkey;
ALTER TABLE ONLY public.detalles_articulos DROP CONSTRAINT detalles_revistas_id_editorial_fkey;
ALTER TABLE ONLY public.detalles_proyectos DROP CONSTRAINT detalles_proyectos_id_recurso_fkey;
ALTER TABLE ONLY public.detalles_proyectos DROP CONSTRAINT detalles_proyectos_id_investigacion_padre_fkey;
ALTER TABLE ONLY public.detalles_proyectos DROP CONSTRAINT detalles_proyectos_id_carrera_fkey;
ALTER TABLE ONLY public.cursos DROP CONSTRAINT cursos_id_docente_fkey;
ALTER TABLE ONLY public.auditoria DROP CONSTRAINT auditoria_usuario_responsable_fkey;
ALTER TABLE ONLY public.accesos_recursos DROP CONSTRAINT accesos_recursos_id_registro_actividad_fkey;
ALTER TABLE ONLY public.accesos_recursos DROP CONSTRAINT accesos_recursos_id_recurso_fkey;
DROP TRIGGER tg_auditoria_usuarios_update ON public.usuarios;
DROP TRIGGER tg_auditoria_usuarios_insert ON public.usuarios;
DROP TRIGGER tg_auditoria_usuarios_delete ON public.usuarios;
DROP TRIGGER tg_auditoria_recursos_insert ON public.recursos;
DROP TRIGGER tg_auditoria_recursos_delete ON public.recursos;
DROP INDEX public.idx_recurso_clasif_linea;
DROP INDEX public.idx_recurso_clasif_dimension;
DROP INDEX public.idx_detalles_inv_ofertada;
ALTER TABLE ONLY public.visitantes DROP CONSTRAINT visitantes_pkey;
ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_pkey;
ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_email_key;
ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_cedula_key;
ALTER TABLE ONLY public.postulaciones_estudiantes DROP CONSTRAINT unique_postulacion;
ALTER TABLE ONLY public.tutores DROP CONSTRAINT tutores_pkey;
ALTER TABLE ONLY public.tutores DROP CONSTRAINT tutores_cedula_key;
ALTER TABLE ONLY public.tipo_tutor DROP CONSTRAINT tipo_tutor_pkey;
ALTER TABLE ONLY public.tipo_tutor DROP CONSTRAINT tipo_tutor_nombre_key;
ALTER TABLE ONLY public.tipo_recurso DROP CONSTRAINT tipo_recurso_pkey;
ALTER TABLE ONLY public.tipo_recurso DROP CONSTRAINT tipo_recurso_nombre_key;
ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_nombre_key;
ALTER TABLE ONLY public.registro_actividad DROP CONSTRAINT registro_actividad_pkey;
ALTER TABLE ONLY public.recursos DROP CONSTRAINT recursos_pkey;
ALTER TABLE ONLY public.recurso_etiquetas DROP CONSTRAINT recurso_etiquetas_pkey;
ALTER TABLE ONLY public.recurso_clasificaciones DROP CONSTRAINT recurso_clasificaciones_pkey;
ALTER TABLE ONLY public.recurso_categorias DROP CONSTRAINT recurso_categorias_pkey;
ALTER TABLE ONLY public.recurso_autores DROP CONSTRAINT recurso_autores_pkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_pkey;
ALTER TABLE ONLY public.privilegios DROP CONSTRAINT privilegios_pkey;
ALTER TABLE ONLY public.preferencias_usuario DROP CONSTRAINT preferencias_usuario_pkey;
ALTER TABLE ONLY public.postulaciones_estudiantes DROP CONSTRAINT postulaciones_estudiantes_pkey;
ALTER TABLE ONLY public.notificaciones DROP CONSTRAINT notificaciones_pkey;
ALTER TABLE ONLY public.lineas_investigacion DROP CONSTRAINT lineas_investigacion_pkey;
ALTER TABLE ONLY public.investigaciones_ofertadas DROP CONSTRAINT investigaciones_ofertadas_pkey;
ALTER TABLE ONLY public.etiquetas DROP CONSTRAINT etiquetas_pkey;
ALTER TABLE ONLY public.etiquetas DROP CONSTRAINT etiquetas_nombre_key;
ALTER TABLE ONLY public.editoriales DROP CONSTRAINT editoriales_pkey;
ALTER TABLE ONLY public.editoriales DROP CONSTRAINT editoriales_nombre_key;
ALTER TABLE ONLY public.dimensiones_operativas DROP CONSTRAINT dimensiones_operativas_pkey;
ALTER TABLE ONLY public.detalles_articulos DROP CONSTRAINT detalles_revistas_pkey;
ALTER TABLE ONLY public.detalles_proyectos DROP CONSTRAINT detalles_proyectos_pkey;
ALTER TABLE ONLY public.detalles_investigaciones DROP CONSTRAINT detalles_investigaciones_pkey;
ALTER TABLE ONLY public.cursos DROP CONSTRAINT cursos_pkey;
ALTER TABLE ONLY public.categorias DROP CONSTRAINT categorias_pkey;
ALTER TABLE ONLY public.categorias DROP CONSTRAINT categorias_nombre_key;
ALTER TABLE ONLY public.carreras DROP CONSTRAINT carreras_pkey;
ALTER TABLE ONLY public.carreras DROP CONSTRAINT carreras_nombre_key;
ALTER TABLE ONLY public.autores DROP CONSTRAINT autores_pkey;
ALTER TABLE ONLY public.autores DROP CONSTRAINT autores_cedula_key;
ALTER TABLE ONLY public.auditoria DROP CONSTRAINT auditoria_pkey;
ALTER TABLE ONLY public.accesos_recursos DROP CONSTRAINT accesos_recursos_pkey;
ALTER TABLE public.visitantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.usuarios ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.tutores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.tipo_tutor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.tipo_recurso ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.registro_actividad ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.recursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.privilegios ALTER COLUMN privilegio_id DROP DEFAULT;
ALTER TABLE public.postulaciones_estudiantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.notificaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.investigaciones_ofertadas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.etiquetas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.editoriales ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.dimensiones_operativas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.cursos ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.categorias ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.carreras ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.autores ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.auditoria ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.accesos_recursos ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE public.visitantes_id_seq;
DROP TABLE public.visitantes;
DROP SEQUENCE public.usuarios_id_seq;
DROP TABLE public.usuarios;
DROP SEQUENCE public.tutores_id_seq;
DROP TABLE public.tutores;
DROP SEQUENCE public.tipo_tutor_id_seq;
DROP TABLE public.tipo_tutor;
DROP SEQUENCE public.tipo_recurso_id_seq;
DROP TABLE public.tipo_recurso;
DROP SEQUENCE public.roles_id_seq;
DROP TABLE public.roles;
DROP SEQUENCE public.registro_actividad_id_seq;
DROP TABLE public.registro_actividad;
DROP SEQUENCE public.recursos_id_seq;
DROP TABLE public.recursos;
DROP TABLE public.recurso_etiquetas;
DROP TABLE public.recurso_clasificaciones;
DROP TABLE public.recurso_categorias;
DROP TABLE public.recurso_autores;
DROP TABLE public.proyecto_tutores;
DROP SEQUENCE public.privilegios_privilegio_id_seq;
DROP TABLE public.privilegios;
DROP TABLE public.preferencias_usuario;
DROP SEQUENCE public.postulaciones_estudiantes_id_seq;
DROP TABLE public.postulaciones_estudiantes;
DROP SEQUENCE public.notificaciones_id_seq;
DROP TABLE public.notificaciones;
DROP SEQUENCE public.lineas_investigacion_id_seq;
DROP TABLE public.lineas_investigacion;
DROP SEQUENCE public.investigaciones_ofertadas_id_seq;
DROP TABLE public.investigaciones_ofertadas;
DROP SEQUENCE public.etiquetas_id_seq;
DROP TABLE public.etiquetas;
DROP SEQUENCE public.editoriales_id_seq;
DROP TABLE public.editoriales;
DROP SEQUENCE public.dimensiones_operativas_id_seq;
DROP TABLE public.dimensiones_operativas;
DROP TABLE public.detalles_proyectos;
DROP TABLE public.detalles_investigaciones;
DROP TABLE public.detalles_articulos;
DROP SEQUENCE public.cursos_id_seq;
DROP TABLE public.cursos;
DROP SEQUENCE public.categorias_id_seq;
DROP TABLE public.categorias;
DROP SEQUENCE public.carreras_id_seq;
DROP TABLE public.carreras;
DROP SEQUENCE public.autores_id_seq;
DROP TABLE public.autores;
DROP SEQUENCE public.auditoria_id_seq;
DROP TABLE public.auditoria;
DROP SEQUENCE public.accesos_recursos_id_seq;
DROP TABLE public.accesos_recursos;
DROP PROCEDURE public.insertarproyectoaleatorio(IN fecha_creada timestamp without time zone);
DROP FUNCTION public.fn_auditoria_usuarios();
DROP FUNCTION public.fn_auditoria_recursos();
DROP TYPE public.tipo_pregunta_enum;
DROP TYPE public.tipo_interaccion_usuario_enum;
DROP TYPE public.tipo_interaccion_enum;
DROP TYPE public.nivel_academico_enum;
DROP TYPE public.estado_curso_enum;
DROP TYPE public.accion_auditoria_enum;
DROP TYPE public.accion_acceso_enum;
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
    id_investigacion_padre integer
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
INSERT INTO public.auditoria VALUES (123, 'recursos', 61, 'INSERT', NULL, NULL, NULL, '{"titulo": "ASASDA", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 16:59:31.204976');
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
INSERT INTO public.auditoria VALUES (120, 'recursos', 58, 'INSERT', NULL, NULL, NULL, '{"titulo": "Middleware MiSCi para ciudades inteligentes extendido con datos enlazados", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 15:19:57.897052');
INSERT INTO public.auditoria VALUES (121, 'recursos', 59, 'INSERT', NULL, NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 16:31:13.973946');
INSERT INTO public.auditoria VALUES (122, 'recursos', 60, 'INSERT', NULL, NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 16:40:45.294611');
INSERT INTO public.auditoria VALUES (124, 'recursos', 21, 'DELETE', NULL, NULL, '{"titulo": "La Bebecita Bebelin", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:00.506138');
INSERT INTO public.auditoria VALUES (125, 'recursos', 59, 'DELETE', NULL, NULL, '{"titulo": "E", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:05.068211');
INSERT INTO public.auditoria VALUES (126, 'recursos', 61, 'DELETE', NULL, NULL, '{"titulo": "Juan", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:07.154446');
INSERT INTO public.auditoria VALUES (127, 'recursos', 60, 'DELETE', NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:08.869819');
INSERT INTO public.auditoria VALUES (128, 'recursos', 44, 'DELETE', NULL, NULL, '{"titulo": "auuuu", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:10.961748');
INSERT INTO public.auditoria VALUES (129, 'recursos', 43, 'DELETE', NULL, NULL, '{"titulo": "123123123123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:12.868199');
INSERT INTO public.auditoria VALUES (130, 'recursos', 42, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:14.485366');
INSERT INTO public.auditoria VALUES (131, 'recursos', 41, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:16.590235');
INSERT INTO public.auditoria VALUES (132, 'recursos', 40, 'DELETE', NULL, NULL, '{"titulo": "123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:18.156327');
INSERT INTO public.auditoria VALUES (133, 'recursos', 39, 'DELETE', NULL, NULL, '{"titulo": "123123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:19.628119');
INSERT INTO public.auditoria VALUES (134, 'recursos', 38, 'DELETE', NULL, NULL, '{"titulo": "23", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:21.323411');
INSERT INTO public.auditoria VALUES (135, 'recursos', 37, 'DELETE', NULL, NULL, '{"titulo": "23123", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:22.704036');
INSERT INTO public.auditoria VALUES (136, 'recursos', 36, 'DELETE', NULL, NULL, '{"titulo": "Waos 3", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:25.469091');
INSERT INTO public.auditoria VALUES (137, 'recursos', 35, 'DELETE', NULL, NULL, '{"titulo": "Waos 2", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:28.095958');
INSERT INTO public.auditoria VALUES (138, 'recursos', 34, 'DELETE', NULL, NULL, '{"titulo": "Waos 1", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:33.17519');
INSERT INTO public.auditoria VALUES (139, 'recursos', 32, 'DELETE', NULL, NULL, '{"titulo": "Manguagua", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:34.779212');
INSERT INTO public.auditoria VALUES (140, 'recursos', 31, 'DELETE', NULL, NULL, '{"titulo": "Imitadora", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:36.557448');
INSERT INTO public.auditoria VALUES (141, 'recursos', 28, 'DELETE', NULL, NULL, '{"titulo": "En los tiempos de los apostoles", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:38.469427');
INSERT INTO public.auditoria VALUES (142, 'recursos', 27, 'DELETE', NULL, NULL, '{"titulo": "Que la guagua", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:40.153464');
INSERT INTO public.auditoria VALUES (143, 'recursos', 25, 'DELETE', NULL, NULL, '{"titulo": "Luisito comunicando", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:42.016396');
INSERT INTO public.auditoria VALUES (144, 'recursos', 24, 'DELETE', NULL, NULL, '{"titulo": "Pepe", "id_tipo_recurso": 3}', NULL, '2026-07-09 20:02:43.469089');
INSERT INTO public.auditoria VALUES (145, 'recursos', 62, 'INSERT', NULL, NULL, NULL, '{"titulo": "Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 20:08:48.117741');
INSERT INTO public.auditoria VALUES (146, 'recursos', 63, 'INSERT', NULL, NULL, NULL, '{"titulo": "Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 20:13:49.50881');
INSERT INTO public.auditoria VALUES (147, 'recursos', 64, 'INSERT', NULL, NULL, NULL, '{"titulo": "Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 20:16:51.643727');
INSERT INTO public.auditoria VALUES (148, 'recursos', 65, 'INSERT', NULL, NULL, NULL, '{"titulo": "Entorno virtual de capacitación con EOG para manipular robots asistenciales", "id_tipo_recurso": 3, "ejemplares_totales": 1}', '2026-07-09 20:19:28.855723');


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
INSERT INTO public.autores VALUES (20, 'Ángel Ferrer', 'V-27100001');
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
INSERT INTO public.autores VALUES (41, 'aaaa aaa aaa', '2222222');
INSERT INTO public.autores VALUES (42, 'Ricardo Dos Santos', 'V-24503215');
INSERT INTO public.autores VALUES (43, 'Jose Aguilar-Castro', 'V-14584964');
INSERT INTO public.autores VALUES (44, 'Taniana Rodríguez', 'V-18458789');
INSERT INTO public.autores VALUES (45, 'Rafael Aldana', 'V-123123123');
INSERT INTO public.autores VALUES (46, 'Rafiña', 'V-123123');
INSERT INTO public.autores VALUES (13, 'Jose Alejandro Rojo', 'E-2323232');
INSERT INTO public.autores VALUES (47, 'Andrés Felipe Sarmiento-Fernández', 'V-15487956');
INSERT INTO public.autores VALUES (48, 'Dursun Barrios', 'V-17890100');
INSERT INTO public.autores VALUES (49, 'Adriana de la Cruz-Uribe', 'E-1452145');
INSERT INTO public.autores VALUES (50, 'Erika Escalante-Espinosa', 'E-1231231');
INSERT INTO public.autores VALUES (51, 'José Roberto Hernández-Barajas', 'V-14515');
INSERT INTO public.autores VALUES (52, 'José Ramón Laines-Canepa', 'E-124411');
INSERT INTO public.autores VALUES (53, 'Piero Antonio Chávez-Malque', 'E-123123');
INSERT INTO public.autores VALUES (54, 'Gérlin Milquito López-Meléndez', 'V-1314114');
INSERT INTO public.autores VALUES (55, 'Edwin Olano-Inga', 'V-2151413');
INSERT INTO public.autores VALUES (56, 'Sócrates Pedro Muñoz-Pérez', 'V-15151123');
INSERT INTO public.autores VALUES (57, 'Karen Elizabeth Mora-Mora', 'V-1231315');
INSERT INTO public.autores VALUES (58, 'David Santiago Sanchez-Garcia', 'V-1451515');
INSERT INTO public.autores VALUES (59, 'Maria Paula Rodriguez-Alba', 'V-412414');
INSERT INTO public.autores VALUES (60, 'Jhon Andres Gomez-Portilla', 'V-5414213');


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
INSERT INTO public.categorias VALUES (2, 'ACEMA');
INSERT INTO public.categorias VALUES (13, 'Matemáticas');
INSERT INTO public.categorias VALUES (14, 'Literatura');
INSERT INTO public.categorias VALUES (15, 'Psicología');
INSERT INTO public.categorias VALUES (16, 'Economía');
INSERT INTO public.categorias VALUES (17, 'Contaduría');
INSERT INTO public.categorias VALUES (18, 'Ingeniería Civil');


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cursos VALUES (1, 4, 'Introducción a la Metodología de la Investigación', 'Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04');
INSERT INTO public.cursos VALUES (2, 4, 'Fundamentos de Inteligencia Artificial', 'Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04');
INSERT INTO public.cursos VALUES (3, 1, 'Normas APA y Redacción Científica', 'Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.', NULL, 'publicado', 60.00, '2026-04-03 03:28:04', '2026-04-03 03:53:35');
INSERT INTO public.cursos VALUES (4, 1, 'tamaños de jose', 'los pn que jose ha tenido segun tamaño', NULL, 'publicado', 70.00, '2026-04-03 04:40:03', '2026-04-03 04:40:03');


--
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_articulos VALUES (58, 5, '87', '213', '0012-7353', '2026-07-09 15:19:57.897052', 'https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png', 'Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato');
INSERT INTO public.detalles_articulos VALUES (63, 8, '93', '241', '0012-7353', '2026-07-09 20:13:49.50881', 'art_1783642429_6a50393d74626.png', 'Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos');
INSERT INTO public.detalles_articulos VALUES (64, NULL, '93', '241', '0012-7353', '2026-07-09 20:16:51.643727', 'art_1783642611_6a5039f396bec.png', 'La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada');
INSERT INTO public.detalles_articulos VALUES (65, 8, '93', '241', '0012-7353', '2026-07-09 20:19:28.855723', 'art_1783642768_6a503a90ca313.png', 'La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.');
INSERT INTO public.detalles_articulos VALUES (62, 8, '93', '241', '0012-7353', '2026-07-09 20:08:48.117741', 'art_1783642127_6a50380feed3b.png', 'La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.');


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_proyectos VALUES (45, '2026-06-15', 'Pregrado', 'Dise¤o e implementaci¢n de un motor de renderizado ligero y de alto rendimiento. Se evit¢ el uso de frameworks pesados para garantizar una ejecuci¢n "metal pure", optimizando el consumo de RAM y CPU en equipos de bajos recursos.', 1, 'Comunidad de Desarrolladores Independientes', 'Rust, Tauri, Novela Visual, Nativo, Optimizaci¢n', '2026-07-05 17:21:44.350197', NULL);
INSERT INTO public.detalles_proyectos VALUES (46, '2025-11-20', 'TSU', 'Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.', 1, 'Estudiantes de Computaci¢n Gr fica', 'Lua, TIC-80, Retro, GameDev, M quina de Estados', '2026-07-05 17:21:44.350197', NULL);
INSERT INTO public.detalles_proyectos VALUES (47, '2026-07-02', 'Pregrado', 'Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.', 1, 'Laboratorios de Arquitectura del Computador', 'Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy', '2026-07-05 17:21:44.350197', NULL);
INSERT INTO public.detalles_proyectos VALUES (48, '2026-05-10', 'Pregrado', 'Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.', 1, 'Departamento de Sistemas de la Universidad', 'Microkernel, PHP, PostgreSQL, MVC, Arquitectura', '2026-07-05 17:21:44.350197', NULL);
INSERT INTO public.detalles_proyectos VALUES (49, '2026-03-15', 'Pregrado', 'Desarrollo de un sistema tradicional para optimizar los m‚todos y procedimientos del inventario m‚dico. Sigue un patr¢n arquitect¢nico modular para agilizar los procesos organizacionales.', 1, 'Ambulatorio Urbano Tipo II', 'Sistemas de Informaci¢n, PostgreSQL, Gesti¢n, Inventario', '2026-07-05 17:39:35.498485', NULL);
INSERT INTO public.detalles_proyectos VALUES (50, '2026-04-22', 'Pregrado', 'Aplicaci¢n interactiva dise¤ada como medio did ctico para facilitar los procesos de ense¤anza. Combina fundamentos comunicacionales y l¢gicos mediante una interfaz interactiva de alto rendimiento.', 1, 'µrea de Ciencias B sicas de la Instituci¢n', 'Edum tica, Software Educativo, Multimedia, µlgebra', '2026-07-05 17:39:35.498485', NULL);
INSERT INTO public.detalles_proyectos VALUES (51, '2025-07-10', 'TSU', 'Dise¤o de un sistema distribuido cooperativo entre clientes y un servidor centralizado. Permite la gesti¢n din mica de solicitudes concurrentes controlando de manera efectiva las peticiones HTTP contra la base de datos.', 1, 'Coordinaci¢n de Control de Estudios', 'Web, Cliente-Servidor, PHP, PostgreSQL', '2026-07-05 17:39:35.498485', NULL);
INSERT INTO public.detalles_proyectos VALUES (52, '2026-06-18', 'Pregrado', 'Herramienta de simulaci¢n orientada al testeo preventivo de la transmisi¢n de datos. Permite modelar el comportamiento de las decisiones de routing antes de iniciar el despliegue f¡sico de una infraestructura de red.', 1, 'Laboratorio de Redes y Telecomunicaciones', 'Simulaci¢n, Routing, Algoritmos, Redes, Topolog¡a', '2026-07-05 17:39:35.498485', NULL);
INSERT INTO public.detalles_proyectos VALUES (57, '2026-07-05', 'Pregrado', 'ahsdhajsdhahakjfhafggfjhgfkjh', 1, 'asdasdasdasd', 'asdasdasdasdasd', '2026-07-05 18:21:33.639701', NULL);


--
-- Data for Name: dimensiones_operativas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.dimensiones_operativas VALUES (5, 7, 'Sistemas de informaci¢n tradicionales', 'Est  constituido por un conjunto de elementos de naturaleza diversa que incluyen: equipos, recursos humanos (usuario), datos e informaci¢n y programas y aplicaciones; que interact£an entre si dentro de una organizaci¢n con el fin de apoyar las actividades y funciones que cumplan con los objetivos propuestos de la misma.');
INSERT INTO public.dimensiones_operativas VALUES (6, 7, 'Sistemas de informaci¢n con propiedades geogr ficas', 'Son sistemas que permiten evaluar propiedades geogr ficas de un entorno, generando informaci¢n referente a una entidad geogr fica desplegando im genes e informaci¢n en un hipermapa.');
INSERT INTO public.dimensiones_operativas VALUES (7, 7, 'Sistemas de informaci¢n web', 'Son primeramente sistemas de informaci¢n que para su desarrollo se debe considerar la misma disciplina de construcci¢n de sistemas de informaci¢n no Web exitosos y de calidad, sirven para integrar procesos o sistemas dentro de una sola interfaz y a ellos se puede acceder por medio de una Intranet local o por la red global Internet van m s all  de ser un conjunto de p ginas Web.');
INSERT INTO public.dimensiones_operativas VALUES (8, 7, 'Sistemas de informaci¢n colaborativos', 'Son sistemas donde se pueden expresar ideas, experiencias, definiciones, entre otros; los cuales constituyen una red de distribuci¢n de la informaci¢n en una organizaci¢n o entre organizaciones.');
INSERT INTO public.dimensiones_operativas VALUES (9, 7, 'Gesti¢n tecnol¢gica', 'Procesos relacionados con la implantaci¢n de sistemas, tales como, verificar e instalar nuevos equipos, entrenar a los usuarios, instalar nuevas aplicaciones, agregar nuevos m¢dulos, adem s de comprobar el correcto funcionamiento de los componentes de un sistema de informaci¢n que puede abarcar auditor¡as, t‚cnicas de control, evaluaci¢n de la calidad.');
INSERT INTO public.dimensiones_operativas VALUES (10, 8, 'Software educativo', 'Programas para el computador creados con la finalidad espec¡fica de ser utilizados como medio did ctico, es decir, para facilitar los procesos de ense¤anza y de aprendizaje. Combina conocimiento educacional, comunicacional e inform tico.');
INSERT INTO public.dimensiones_operativas VALUES (11, 8, 'Gu¡as de estudio web', 'Representan un material instruccional utilizados para cursos de educaci¢n a distancia y como complemento a la educaci¢n presencial, lo cual provee una estructura para un curso.');
INSERT INTO public.dimensiones_operativas VALUES (12, 8, 'Tutoriales', 'Son programas que en mayor o menor medida dirigen el trabajo de los alumnos. Pretenden que, a partir de unas informaciones y mediante la realizaci¢n de ciertas actividades, los estudiantes pongan en juego determinadas capacidades.');
INSERT INTO public.dimensiones_operativas VALUES (13, 8, 'Juegos did cticos', 'El juego puede cumplir al menos tres funciones en el proceso de aprendizaje, al constituirse en un medio de exploraci¢n y expresi¢n, un instrumento para la organizaci¢n y aplicaci¢n de habilidades y, un factor de socializaci¢n e integraci¢n.');
INSERT INTO public.dimensiones_operativas VALUES (14, 8, 'Entornos interactivos de ense¤anza', 'Proyectos donde el profesor y los alumnos se encuentran en lugares f¡sicamente distintos. El proceso de ense¤anza-aprendizaje se lleva a cabo a trav‚s de Internet, en cualquier momento y en cualquier lugar.');
INSERT INTO public.dimensiones_operativas VALUES (15, 8, 'Sistemas e-learning', 'Programas que faciliten la creaci¢n, adopci¢n y distribuci¢n de contenidos, as¡ como la adaptaci¢n del ritmo de aprendizaje y la disponibilidad de las herramientas de aprendizaje independientemente de l¡mites horarios o geogr ficos.');
INSERT INTO public.dimensiones_operativas VALUES (16, 9, 'Aplicaciones cliente - servidor', 'Sistema distribuido entre m£ltiples procesadores donde hay clientes que solicitan servicios y servidores que los proporcionan. Separa los servicios situando cada uno en su plataforma m s adecuada.');
INSERT INTO public.dimensiones_operativas VALUES (17, 9, 'Servicios de integraci¢n para aplicaciones web', 'Medio para exponer y hacer disponible la funcionalidad de los sistemas de informaci¢n mediante las tecnolog¡as est ndar Web, permitiendo reducci¢n de la heterogeneidad por uso de tecnolog¡as est ndar.');
INSERT INTO public.dimensiones_operativas VALUES (18, 10, 'Simulaci¢n y herramientas de simulaci¢n', 'Antes de iniciar el desarrollo de cualquier sistema complejo, los ingenieros suelen utilizar alguna herramienta de simulaci¢n o test donde sea posible modelizar y probar el sistema que est  desarrollando. Reduce tiempo y chequea decisiones a priori.');
INSERT INTO public.dimensiones_operativas VALUES (19, 10, 'Modelos de transmisi¢n de datos', 'Se discute la conceptualizaci¢n integral de un sistema de transmisi¢n desde un marco com£n a diferentes tecnolog¡as, tales como: sistemas de comunicaci¢n por cable, radio enlaces fijos, m¢viles y satelitales.');


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
INSERT INTO public.editoriales VALUES (2, 'ACM');


--
-- Data for Name: etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.etiquetas VALUES (1, 'Inteligencia Artificial', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (2, 'Machine Learning', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (3, 'Educación', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (4, 'Redes Neuronales', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (5, 'Desarrollo Web', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (8, 'Teoría Matemática', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (9, 'Modelo Económico', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (10, 'Construcción', '#0ea5e9');


--
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.lineas_investigacion VALUES (7, 'SISTEMAS DE INFORMACION Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de informaci¢n dentro del  mbito social. Aplicando soluciones efectivas para el uso adecuado y ¢ptimo de los sistemas de informaci¢n.');
INSERT INTO public.lineas_investigacion VALUES (8, 'EDUMATICA', 1, 'Aplicar las Tecnolog¡as de la Informaci¢n y Comunicaci¢n (TIC) para apoyar el proceso de aprendizaje, y as¡ contribuir al mejoramiento de la educaci¢n en todos sus niveles.');
INSERT INTO public.lineas_investigacion VALUES (9, 'APLICACIONES WEB', 1, 'Desarrollar aplicaciones Web para cubrir las necesidades de gesti¢n, control e intercambio de informaci¢n de la empresa y el entorno que la rodea a trav‚s de la Internet o Intranet.');
INSERT INTO public.lineas_investigacion VALUES (10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisi¢n de datos, como tambi‚n la detecci¢n de fallas dentro de una red.');


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
-- Data for Name: postulaciones_estudiantes; Type: TABLE DATA; Schema: public; Owner: postgres
--



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


--
-- Data for Name: proyecto_tutores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.proyecto_tutores VALUES (57, 7, 3);
INSERT INTO public.proyecto_tutores VALUES (57, 8, 2);
INSERT INTO public.proyecto_tutores VALUES (57, 9, 4);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (3, 1);
INSERT INTO public.recurso_autores VALUES (57, 41);
INSERT INTO public.recurso_autores VALUES (58, 42);
INSERT INTO public.recurso_autores VALUES (58, 43);
INSERT INTO public.recurso_autores VALUES (58, 44);
INSERT INTO public.recurso_autores VALUES (62, 47);
INSERT INTO public.recurso_autores VALUES (62, 48);
INSERT INTO public.recurso_autores VALUES (63, 49);
INSERT INTO public.recurso_autores VALUES (63, 50);
INSERT INTO public.recurso_autores VALUES (63, 51);
INSERT INTO public.recurso_autores VALUES (63, 52);
INSERT INTO public.recurso_autores VALUES (64, 53);
INSERT INTO public.recurso_autores VALUES (64, 54);
INSERT INTO public.recurso_autores VALUES (64, 55);
INSERT INTO public.recurso_autores VALUES (64, 56);
INSERT INTO public.recurso_autores VALUES (65, 57);
INSERT INTO public.recurso_autores VALUES (65, 58);
INSERT INTO public.recurso_autores VALUES (65, 59);
INSERT INTO public.recurso_autores VALUES (65, 60);


--
-- Data for Name: recurso_categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_categorias VALUES (59, 6);
INSERT INTO public.recurso_categorias VALUES (59, 3);
INSERT INTO public.recurso_categorias VALUES (59, 5);
INSERT INTO public.recurso_categorias VALUES (60, 5);
INSERT INTO public.recurso_categorias VALUES (27, 2);
INSERT INTO public.recurso_categorias VALUES (27, 6);
INSERT INTO public.recurso_categorias VALUES (27, 3);
INSERT INTO public.recurso_categorias VALUES (27, 5);
INSERT INTO public.recurso_categorias VALUES (27, 11);
INSERT INTO public.recurso_categorias VALUES (61, 4);
INSERT INTO public.recurso_categorias VALUES (62, 1);
INSERT INTO public.recurso_categorias VALUES (63, 3);
INSERT INTO public.recurso_categorias VALUES (63, 13);
INSERT INTO public.recurso_categorias VALUES (64, 18);
INSERT INTO public.recurso_categorias VALUES (65, 6);
INSERT INTO public.recurso_categorias VALUES (65, 5);
INSERT INTO public.recurso_categorias VALUES (65, 1);


--
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_clasificaciones VALUES (49, 7, 5);
INSERT INTO public.recurso_clasificaciones VALUES (50, 8, 10);
INSERT INTO public.recurso_clasificaciones VALUES (51, 9, 16);
INSERT INTO public.recurso_clasificaciones VALUES (52, 10, 18);
INSERT INTO public.recurso_clasificaciones VALUES (57, 9, 17);


--
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_etiquetas VALUES (58, 1);
INSERT INTO public.recurso_etiquetas VALUES (62, 3);
INSERT INTO public.recurso_etiquetas VALUES (62, 1);
INSERT INTO public.recurso_etiquetas VALUES (63, 8);
INSERT INTO public.recurso_etiquetas VALUES (64, 10);
INSERT INTO public.recurso_etiquetas VALUES (65, 1);
INSERT INTO public.recurso_etiquetas VALUES (65, 2);
INSERT INTO public.recurso_etiquetas VALUES (65, 4);


--
-- Data for Name: recursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recursos VALUES (1, 'Sistema de Reconocimiento Biométrico Facial para Comedor Universitario', 1, 2026, 1, 1, NULL);
INSERT INTO public.recursos VALUES (2, 'Prototipo de Cerradura Digital con Matriz de Teclado y Arduino', 1, 2025, 1, 1, NULL);
INSERT INTO public.recursos VALUES (3, 'Aplicación de Redes Neuronales Convolucionales para la Detección de Plagas en Cultivos Trujillanos', 2, 2026, 1, 1, NULL);
INSERT INTO public.recursos VALUES (4, 'Impacto del Cambio Climático en Trujillo - Parte 8', 2, 2023, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (5, 'Simulación de Cargas Estáticas en Puentes - Parte 7', 2, 2024, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (6, 'Big Data en Finanzas Institucionales - Parte 9', 1, 2024, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (7, 'Optimización de CPU en Servidores Locales - Parte 7', 1, 2026, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (8, 'Sistemas de Riego Automatizado - Parte 5', 1, 2023, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (9, 'Bioinformática y Análisis de ADN - Parte 5', 3, 2025, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (10, 'Inteligencia Artificial en Diagnóstico Médico - Parte 6', 2, 2025, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (11, 'Robótica Educativa para Escuelas - Parte 5', 3, 2023, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (12, 'Software Libre para Bibliotecas - Parte 1', 3, 2026, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (13, 'E-Learning para Zonas Desfavorecidas - Parte 1', 3, 2018, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (14, 'Telecomunicaciones de Fibra Óptica Rural - Parte 1', 2, 2022, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (15, 'Criptografía Cuántica Post-RSA - Parte 2', 1, 2024, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (16, 'Criptografía Cuántica Post-RSA - Parte 7', 3, 2026, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (17, 'Criptografía Cuántica Post-RSA - Parte 5', 1, 2024, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (18, 'Criptografía Cuántica Post-RSA - Parte 8', 1, 2021, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (19, 'Software Libre para Bibliotecas - Parte 6', 1, 2023, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (20, 'Inteligencia Artificial en Diagnóstico Médico - Parte 1', 3, 2020, 1, 1, 'dummy.pdf');
INSERT INTO public.recursos VALUES (45, 'Desarrollo de un Motor para Novelas Visuales Nativas usando Rust y Tauri', 1, 2026, 2, 2, 'motor_rust_tauri_v1.pdf');
INSERT INTO public.recursos VALUES (46, 'Arquitectura de L¢gica de Estados para Videojuegos en Consolas Virtuales TIC-80', 1, 2025, 1, 1, 'juego_aislamiento_tic80.pdf');
INSERT INTO public.recursos VALUES (47, 'Protocolo de Restauraci¢n y Diagn¢stico de Capacitores en Tarjetas Madre Socket 478', 1, 2026, 3, 3, 'restauracion_pentium4.pdf');
INSERT INTO public.recursos VALUES (48, 'Implementaci¢n de un Enrutador Din mico basado en Arquitectura Microkernel con PHP Puro', 1, 2026, 1, 1, 'microkernel_php_routing.pdf');
INSERT INTO public.recursos VALUES (49, 'Sistema de Informaci¢n Automatizado para la Gesti¢n de Inventario y Suministros M‚dicos', 1, 2026, 1, 1, 'proyecto_inventario_medico.pdf');
INSERT INTO public.recursos VALUES (50, 'Software Educativo Multimedial para el Fortalecimiento del Aprendizaje de µlgebra Lineal', 1, 2026, 1, 1, 'software_educativo_algebra.pdf');
INSERT INTO public.recursos VALUES (51, 'Plataforma Web bajo Arquitectura Cliente-Servidor para el Control de Citas Acad‚micas', 1, 2025, 1, 1, 'plataforma_web_citas.pdf');
INSERT INTO public.recursos VALUES (52, 'Simulador de Enrutamiento por Estado de Enlace para la Validaci¢n de Topolog¡as Complejas', 1, 2026, 1, 1, 'simulador_routing_topologias.pdf');
INSERT INTO public.recursos VALUES (57, 'hola adios', 1, 2026, 1, 1, 'documentos/pst/pst_hola_adios_1783290093.pdf');
INSERT INTO public.recursos VALUES (58, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');
INSERT INTO public.recursos VALUES (65, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (62, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (63, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (64, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del concreto', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');


--
-- Data for Name: registro_actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.registro_actividad VALUES (1, 1, NULL, '2026-03-23 14:49:58', '2026-03-23 14:49:58', 1);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (3, 'Estudiante', 1);
INSERT INTO public.roles VALUES (1, 'Super Administrador', 4);
INSERT INTO public.roles VALUES (4, 'Profesor', 2);
INSERT INTO public.roles VALUES (2, 'Comite', 3);


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


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios VALUES (1, 'Adrus', 'andru@gmail.com', '11111111', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 1, true);
INSERT INTO public.usuarios VALUES (2, 'lando', 'lando@gmail.com', '22222222', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 2, true);
INSERT INTO public.usuarios VALUES (3, 'miki', 'miki@gmail.com', '33333333', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true);
INSERT INTO public.usuarios VALUES (4, 'ale', 'ale@yaju.com', '44444444', '$2y$10$o0Uk8V6gzXNSW/EZBWvd1OoC7O6UzrU3LRbDMIqxYDou2KJGRXdUa', 3, true);
INSERT INTO public.usuarios VALUES (5, 'bibi', 'bibi@gmail.com', '4444111', NULL, 3, true);
INSERT INTO public.usuarios VALUES (8, 'Yisu Monte', 'yisu@gmail.com', '30866991', '$2y$10$jOukhIGIbdJCmpHdS.MqWusufmhQgHf.O9UByeqN.NFue38kT47xa', 3, true);
INSERT INTO public.usuarios VALUES (9, 'Pedro Perez', 'iaiaia@gmail.com', '4123123', '$2y$10$xOgs5kJnv17wwzjNtnNUguWc7pxdYv.lMZGFejPOz7fIgLNEybLgC', 3, true);
INSERT INTO public.usuarios VALUES (11, 'Juan', '123@gmail.com', '1234', '$2y$10$HBPGRak0eIYzElwfC.bGuOvgFOfK.GbG40ct2e7X9CS7OgMARJRcC', 3, true);
INSERT INTO public.usuarios VALUES (7, 'Miguel González', 'erwazaaaa@gmail.com', '32621284', '$2y$10$tqm17pwan91BnMUfmCAB/O01faShLfeK3jo0jYVwpQcBpGr5iLiE.', 1, true);
INSERT INTO public.usuarios VALUES (6, 'Piñin Piña', 'pina@hotmail.com', '1', '$2y$10$wqwwyjK8T7ccki5IeOK4ueZRlW8K3g2xC42ZyOG01kDru0CNhba/a', 4, false);
INSERT INTO public.usuarios VALUES (10, 'Wazaaaa', 'wazaaa@gmail.com', '123', '$2y$10$G7tnCsgxNo7nFV93A4H7Ie86N2RYtbppgkB6iEPg.STWF4wn2qn7O', 4, true);


--
-- Data for Name: visitantes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: accesos_recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.accesos_recursos_id_seq', 1, false);


--
-- Name: auditoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.auditoria_id_seq', 148, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 60, true);


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

SELECT pg_catalog.setval('public.cursos_id_seq', 4, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 19, true);


--
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 9, true);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 10, true);


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

SELECT pg_catalog.setval('public.recursos_id_seq', 65, true);


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

SELECT pg_catalog.setval('public.tutores_id_seq', 9, true);


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

\unrestrict CmnSYvg5hIZ5KcRsygOzkH74Jyb0fNLYORcMNtN9zF7Y65eeHq8UFGwevf1KJcn

