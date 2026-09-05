--
-- PostgreSQL database dump
--

\restrict GFwQfzQqRth4dECTCdxsPOVEfQhjujUxK7poy6sendoyXBknD0EAh1UAJOZCPlm

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
ALTER TABLE ONLY public.recurso_categorias DROP CONSTRAINT recurso_categorias_id_recurso_fkey;
ALTER TABLE ONLY public.recurso_categorias DROP CONSTRAINT recurso_categorias_id_categoria_fkey;
ALTER TABLE ONLY public.recurso_autores DROP CONSTRAINT recurso_autores_id_recurso_fkey;
ALTER TABLE ONLY public.recurso_autores DROP CONSTRAINT recurso_autores_id_autor_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_tipo_tutor_id_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_id_tutor_fkey;
ALTER TABLE ONLY public.proyecto_tutores DROP CONSTRAINT proyecto_tutores_id_recurso_fkey;
ALTER TABLE ONLY public.roles DROP CONSTRAINT privilegio_fk;
ALTER TABLE ONLY public.preferencias_usuario DROP CONSTRAINT preferencias_usuario_id_usuario_fkey;
ALTER TABLE ONLY public.notificaciones DROP CONSTRAINT notificaciones_id_usuario_fkey;
ALTER TABLE ONLY public.lineas_investigacion DROP CONSTRAINT lineas_investigacion_id_carrera_fkey;
ALTER TABLE ONLY public.historico_versiones_pst DROP CONSTRAINT fk_version_recurso;
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
ALTER TABLE ONLY public.propuestas_empresa DROP CONSTRAINT propuestas_empresa_pkey;
ALTER TABLE ONLY public.privilegios DROP CONSTRAINT privilegios_pkey;
ALTER TABLE ONLY public.preferencias_usuario DROP CONSTRAINT preferencias_usuario_pkey;
ALTER TABLE ONLY public.postulaciones_estudiantes DROP CONSTRAINT postulaciones_estudiantes_pkey;
ALTER TABLE ONLY public.notificaciones DROP CONSTRAINT notificaciones_pkey;
ALTER TABLE ONLY public.lineas_investigacion DROP CONSTRAINT lineas_investigacion_pkey;
ALTER TABLE ONLY public.investigaciones_ofertadas DROP CONSTRAINT investigaciones_ofertadas_pkey;
ALTER TABLE ONLY public.historico_versiones_pst DROP CONSTRAINT historico_versiones_pst_pkey;
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
ALTER TABLE public.propuestas_empresa ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.privilegios ALTER COLUMN privilegio_id DROP DEFAULT;
ALTER TABLE public.postulaciones_estudiantes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.notificaciones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.lineas_investigacion ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.investigaciones_ofertadas ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.historico_versiones_pst ALTER COLUMN id DROP DEFAULT;
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
DROP SEQUENCE public.propuestas_empresa_id_seq;
DROP TABLE public.propuestas_empresa;
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
DROP SEQUENCE public.historico_versiones_pst_id_seq;
DROP TABLE public.historico_versiones_pst;
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
DROP TYPE public.estado_propuesta_enum;
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
    resumen text,
    activo boolean DEFAULT true
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
INSERT INTO public.autores VALUES (13, 'ale', 'E-1231231');
INSERT INTO public.autores VALUES (30, 'Mariano Rajoy', 'V-9857492');
INSERT INTO public.autores VALUES (31, 'Alejandro Alicante', 'V-12312391');
INSERT INTO public.autores VALUES (33, 'Luis Enrique Morelos', 'E-5184865');
INSERT INTO public.autores VALUES (34, 'Jesús Montilla', 'V-30866991');
INSERT INTO public.autores VALUES (35, 'Luis Miguel', 'V-17855689');
INSERT INTO public.autores VALUES (36, 'Fausto Hernandez', 'V-21314132');
INSERT INTO public.autores VALUES (37, 'miki', 'V-1234');
INSERT INTO public.autores VALUES (41, 'aaaa aaa aaa', '2222222');
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
INSERT INTO public.autores VALUES (81, 'Analy De Los Angeles Hernández Cortéz', 'V-30601065');
INSERT INTO public.autores VALUES (82, 'Anyela Alejandra Briceño Guerra', 'V-31413272');
INSERT INTO public.autores VALUES (83, 'Abraham David Graterol Villamizar', 'V-31167863');
INSERT INTO public.autores VALUES (84, 'Isaac José Figuera García', 'V-31239364');
INSERT INTO public.autores VALUES (88, 'Jesus Francisco Montilla Olmos', 'V-30886991');
INSERT INTO public.autores VALUES (89, 'Miguel Alejandro Gonzalez Gonzalez', 'V-32621283');
INSERT INTO public.autores VALUES (90, 'Juan Piña', 'V-8398');
INSERT INTO public.autores VALUES (91, '@''''¿1''23¿12''3¿ñ{ñ{--__´ñ´ñ´!"!"#!"$"%#$%%&%&/&()&/', 'E-14');


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


--
-- Data for Name: cursos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cursos VALUES (1, 4, 'Introducción a la Metodología de la Investigación', 'Curso fundamental para comprender los métodos y técnicas de investigación científica aplicados al PNF en Informática. Incluye diseño experimental, recolección de datos y análisis estadístico básico.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04');
INSERT INTO public.cursos VALUES (2, 4, 'Fundamentos de Inteligencia Artificial', 'Curso introductorio sobre los conceptos básicos de la IA, redes neuronales, aprendizaje automático y sus aplicaciones en el contexto venezolano.', NULL, 'publicado', 70.00, '2026-04-03 03:28:04', '2026-04-03 03:28:04');
INSERT INTO public.cursos VALUES (4, 1, 'tamaños de jose', 'los pn que jose ha tenido segun tamaño', NULL, 'borrador', 69.96, '2026-04-03 04:40:03', '2026-09-02 21:38:28.289267');
INSERT INTO public.cursos VALUES (3, 10, 'Normas APA y Redacción Científica', 'Aprende a redactar documentos académicos siguiendo las normas APA 7ma edición. Ideal para la elaboración de tu Proyecto Socio-Tecnológico.', NULL, 'archivado', 67.00, '2026-04-03 03:28:04', '2026-09-02 21:39:04.419332');
INSERT INTO public.cursos VALUES (6, 9, 'e', 'e', NULL, 'publicado', 70.00, '2026-09-02 21:40:20.207518', '2026-09-02 21:40:27.256632');


--
-- Data for Name: detalles_articulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_articulos VALUES (121, 8, '93', '241', '0012-7353', '2026-07-09 20:19:28.855723', 'art_1783642768_6a503a90ca313.png', 'La discapacidad motora en Colombia afecta a un porcentaje significativo de la población, constituye una problemática relevante de salud pública,  asociada  con  diversos  factores  del  país.  Este  proyecto  desarrolla  un  sistema  de  control  de  robots  asistenciales  controlados  por  señales electrooculográficas (EOG), logrando que aquellas personas con movilidad reducida tengan acceso a este tipo de tecnologías. Para el desarrollo se adquirieron señales con el hardware Bitalino para generar y normalizar un conjunto de datos, que luego se procesa con Python y Open Signals para establecer comandos confiables. El entorno de simulación se realizó en CoppeliaSim. Durante el proceso de desarrollo, se encontraron obstáculos como el ruido y la exactitud de las señales. No obstante, se ha terminado la interfaz y la conexión entre CoppeliaSim, Python y las señales EOG, permitiendo que el robot se mueva en tiempo real. En la actualidad, se realizan pruebas de funcionamiento, exactitud y precisión de los movimientos.', true);
INSERT INTO public.detalles_articulos VALUES (120, NULL, '93', '241', '0012-7353', '2026-07-09 20:16:51.643727', 'art_1783642611_6a5039f396bec.png', 'La industria de la construcción enfrenta un serio impacto ambiental por las altas emisiones del cemento, lo que impulsa la búsqueda de alternativas sostenibles como el concreto reforzado con fibras de polipropileno (FPP). Para ello se analizó su efecto en las propiedades del concreto a través de una revisión sistemática y filtrada de 66 artículos recientes entre los años 2021 y 2025 extraídos de Scopus, ScienceDirect y MDPI. Los estudios muestran que la FPP mejora la resistencia a compresión, flexión y tracción, especialmente en proporciones cercanas al 0.5%. También aumenta la durabilidad frente a agentes agresivos y mejora la microestructura al controlar grietas, aunque, puede reducir la trabajabilidad y aumentar la porosidad, efectos mitigables mediante el uso de fibras metálicas o adiciones puzolánicas. En conclusión, el uso de FPP es una opción viable para reducir el impacto ambiental del concreto y mejorar su desempeño cuando se aplica en proporciones adecuada', true);
INSERT INTO public.detalles_articulos VALUES (119, 8, '93', '241', '0012-7353', '2026-07-09 20:13:49.50881', 'art_1783642429_6a50393d74626.png', 'Los techos verdes representan una estrategia pasiva eficaz para reducir la transferencia de calor hacia el interior de los edificios, especialmente en climas  cálidos  y  húmedos.  En  este  trabajo  se  presenta  un  modelo  dinámico  unidimensional  de  balance  de  calor  y  masa  para  evaluar  el  comportamiento térmico de un techo verde extensivo en condiciones de trópico húmedo. El modelo considera procesos de conducción, convección, radiación y transferencia de humedad, incorporando la evapotranspiración y parámetros de la vegetación dependientes de la especie. La calibración y simulación se realizaron usando datos experimentales obtenidos de una base experimental de techos verde ubicada en Tabasco, México, con las especies Tradescantia  spathaceay Tradescantia  pallida.  El  desempeño  del  sistema  se  evaluó  bajo  tres  escenarios  climáticos  representativos:  temporada de estiaje, temporada de lluvia y de frente frío. Los resultados muestran que la capa vegetal reduce la transferencia de calor hacia el interior del edificio, además de contribuir a la estabilización térmica del microclima del techo. El análisis de sensibilidad indica que parámetros asociados a la vegetación, en particular el índice de área foliar y la resistencia interna de las hojas, ejercen una influencia dominante en la respuesta del sistema. Aunque el modelo se limita al caso unidimensional y a especies específicas, constituye una herramienta útil para la evaluación del desempeño térmico de techos verdes en climas tropicales húmedos', true);
INSERT INTO public.detalles_articulos VALUES (118, 5, '87', '213', '0012-7353', '2026-07-09 15:19:57.897052', 'https://revistas.unal.edu.co/public/journals/21/cover_issue_5423_es_ES.png', 'Este artículo propone una ampliación de las capacidades del middleware MiSCi, al agregar una nueva capa denominada Datos Enlazados, para  identificar,  describir,  conectar,  relacionar  y  explotar  los  distintos  datos  generados  por  los  usuarios  y  las  aplicaciones  de  la  ciudad  inteligente usando el paradigma de datos enlazados. Esta nueva capa está compuestas por distintos agentes que permiten automatizar las etapas  de  especificación,  modelado,  generación,  vinculación,  publicación  y  explotación  de  los  datos  basados  en  MEDAWEDE.  Dichos  agentes  pueden  enriquecer  ontologías  existentes  en  MiSCi,  generar  modelos  de  conocimiento  requeridos  por  los  servicios  de  MiSCi, generar datos para construir modelos de conocimiento para MiSCi, y recomendar información en contextos de incertidumbre a través de una inferencia híbrida basada en lógica descriptiva/dialéctica. Además en este trabajo se especifica un caso de estudio, donde se muestran las capacidades del MiSCi para manejar distintas situaciones críticas, apoyado en la nueva capa de enlazado de dato', true);
INSERT INTO public.detalles_articulos VALUES (122, 8, '93', '241', '0012-7353', '2026-07-09 20:08:48.117741', 'art_1783642127_6a50380feed3b.png', 'La  banca  móvil  se  ha  consolidado  como  una  herramienta  clave  para  la  inclusión  financiera,  particularmente  en  zonas  rurales  donde  las  barreras geográficas y de infraestructura limitan el acceso a servicios bancarios tradicionales. Este estudio analiza los determinantes de la aceptación de la banca móvil en ganaderos del occidente de Antioquia, Colombia, utilizando el modelo UTAUT. Se aplicó una metodología cuantitativa  basada  en  encuestas  estructuradas  a  132  productores  rurales,  evaluando  variables  como  la  expectativa  de  rendimiento,  la  expectativa de esfuerzo, la influencia social, el riesgo y la confianza. Los resultados revelan que la expectativa de rendimiento y la facilidad de uso son los principales factores que influyen en la adopción de la banca móvil, mientras que la confianza, el riesgo y la influencia social no  mostraron  un  impacto  significativo.  Estos  hallazgos  destacan  la  necesidad  de  desarrollar  estrategias  que  promuevan  el  acceso  a  plataformas digitales intuitivas y capacitaciones enfocadas en el uso de estas herramientas.', false);


--
-- Data for Name: detalles_investigaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: detalles_proyectos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.detalles_proyectos VALUES (46, '2025-11-20', 'Pregrado', 'Estudio de la gesti¢n de memoria y el ciclo de vida de los sprites utilizando Lua dentro del motor TIC-80. El proyecto demuestra c¢mo estructurar el c¢digo para videojuegos con est‚tica retro-tech sin saturar el l¡mite de procesamiento de la consola virtual.', 1, 'Estudiantes de Computaci¢n Gr fica', 'Lua, TIC-80, Retro, GameDev, M quina de Estados', '2026-07-05 17:21:44.350197', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (47, '2026-07-02', 'Pregrado', 'Metodolog¡a pr ctica para revivir equipos de torre de principios de los 2000. El caso de estudio se centra en una Utech Pentium 4, abordando el reemplazo de condensadores inflados y la instalaci¢n limpia de sistemas operativos legacy para la preservaci¢n de software antiguo.', 1, 'Laboratorios de Arquitectura del Computador', 'Pentium 4, Hardware, Restauraci¢n, Condensadores, Legacy', '2026-07-05 17:21:44.350197', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (48, '2026-05-10', 'Pregrado', 'Creaci¢n de un n£cleo de procesamiento (Core) capaz de cargar m¢dulos MVC de forma independiente. Se detalla la construcci¢n del QueryBuilder, gesti¢n de conexiones PostgreSQL y un sistema de enrutamiento estricto para evitar acoplamientos.', 1, 'Departamento de Sistemas de la Universidad', 'Microkernel, PHP, PostgreSQL, MVC, Arquitectura', '2026-07-05 17:21:44.350197', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (49, '2026-03-15', 'Pregrado', 'Desarrollo de un sistema tradicional para optimizar los m‚todos y procedimientos del inventario m‚dico. Sigue un patr¢n arquitect¢nico modular para agilizar los procesos organizacionales.', 1, 'Ambulatorio Urbano Tipo II', 'Sistemas de Informaci¢n, PostgreSQL, Gesti¢n, Inventario', '2026-07-05 17:39:35.498485', NULL, 'Trayecto II', NULL, NULL, NULL);
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
INSERT INTO public.detalles_proyectos VALUES (81, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:48:05.72936', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (82, '2026-08-05', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo  ------------------------------------------------Naturaleza de la Comunidad: El CAIPA-Trujillo, Valera Estado Trujillo', '', '2026-08-05 09:48:05.817964', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (83, '2026-08-05', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-05 09:48:05.91852', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (84, '2026-08-05', 'Pregrado', 'El propósito principal de este proyecto es realizar soporte técnico a los equipos de la institución (Escuela Técnica Comercial Madre Rafols)del Estado Trujillo municipio Valera. Y de igual forma dictar varias sesiones de capacitación formativas a los estudiantes de dicha institución cerca de software, hardware, partes, usos adecuados de un computador, donde podamos ofrecer nuevos conocimientos a los estudiantes. Todo esto aplicando nuevas tecnologías de aprendizaje que permitan el crecimiento y desarrollo del área de informática de la institución', 1, 'Escuela Técnica Comercial Madre Rafols', '', '2026-08-05 09:48:06.00888', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (85, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 09:56:42.188313', NULL, 'Trayecto II', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (86, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:02:04.774776', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (87, '2026-08-05', 'Pregrado', 'Resumen de prueba automatizada para verificación de duplicados.', 1, 'Comunidad Test', 'Prueba, Duplicados, PST', '2026-08-05 10:34:46.219889', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (116, '2026-08-31', 'Doctorado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-31 09:47:06.862144', NULL, NULL, NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (58, '2026-07-07', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio. Palabras clave: Gestión doc', 1, 'asdasdasdasd', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-07-07 00:01:54.74783', NULL, 'Trayecto III', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (79, '2026-08-05', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-05 09:45:15.201621', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (89, '2018-01-10', 'Pregrado', 'El presente proyecto sociotecnológico se centra en el desarrollo de un módulo avanzado para la administración y proyección de las líneas de investigación del PNFI, en el cual la innovación principal radica en la integración de modelos de Inteligencia Artificial (Machine Learning) orientados al análisis predictivo, esta herramienta procesa el volumen y la tipología de las investigaciones registradas para identificar tendencias emergentes, predecir el crecimiento de áreas temáticas y asistir al Comité Científico Investigador en la toma de decisiones estratégicas, todo ello operando sobre la arquitectura base del Sistema Integral de Gestión.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Líneas de investigación, PNFI, Machine Learning, Análisis predictivo, Toma de decisiones, Comité científico, Gestión del conocimiento, Sistema integral de gestión', '2026-08-10 10:35:39.007693', NULL, 'Trayecto IV', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (90, '2026-08-10', 'Pregrado', 'Una descripción de proyectos es una visión general de alto nivel de por qué está haciendo el mismo. De igual manera el documento explica los objetivos y sus cualidades esenciales, donde la descripción es fundamental debido a que va ayudar en la realización del estudio ya que se requiere de la aplicación de varias metodologías que abordan aspectos desde la identificación del problema, hasta la selección de la alternativa más adecuada, haciendo uso de herramientas y técnicas que permiten la recolección y análisis de información de manera concreta y adecuada, aumentando así el nivel de objetividad del problema a resolver', 1, 'CAIPA Trujillo', '', '2026-08-10 10:45:42.226083', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (91, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 10:52:47.26864', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (92, '2026-08-10', 'Pregrado', 'La descripción del proyecto ofrece una visión general de la iniciativa que se va a desarrollar, la cual, debe incluir información clave que permita entender el contexto, los objetivos y la relevancia de la propuesta. Así que, este apartado actúa como un marco de referencia para todos los aspectos esenciales del proyecto, facilitando así, una comprensión clara de lo que se pretende lograr.', 1, 'Escuela Nacional “Antonio Pérez Carmona”, se encuentra registrada con el Registro de Información Fiscal (RIF) J-403419957', '', '2026-08-10 11:34:04.575523', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (93, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 11:34:42.145006', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (94, '2026-08-10', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-10 11:40:58.141559', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (97, '2026-08-10', 'Pregrado', 'En este sentido, el presente proyecto se desarrolla dentro de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry”, específicamente en el núcleo universitario “Dr. Pablo Viloria”, ubicado en la ciudad de Valera, estado Trujillo. Dentro de esta institución se encuentra el Programa Nacional de Formación en Contaduría Pública, donde se identificó la necesidad de optimizar los procesos relacionados con la gestión de los Proyectos de Investigación Comunitaria Integradora (PCI), así como el manejo de la información académica de los estudiantes vinculados a dichos proyectos. El análisis del contexto institucional permite comprender cómo se gestionan actualmente estos procesos, cuáles son las herramientas utilizadas para el registro y control de la información académica y cuáles son las limitaciones presentes en dichos procedimientos. En este sentido, la descripción del contexto se convierte en un elemento fundamental para sustentar la pertinencia del desarrollo de una solución informática orientada a mejorar la organización y gestión de la información dentro del programa académico', 1, 'Departamento del Programa Nacional de Formación (PNF) en Contaduría Pública de la Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorr', 'sistema informático, gestión académica, proyectos PCI, información académica, automatización', '2026-08-10 12:03:37.275866', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (99, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:10:58.390178', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (100, '2026-08-10', 'Pregrado', 'El proyecto socio tecnológico tuvo como propósito desarrollar una Aplicación Web Móvil para el proceso de ascensos en la Coordinación de Formación Permanente y Docencia de la UPTTMBI. Se destaca la importancia que tienen las aplicaciones web en la vida cotidiana, dado que facilitan obtener, modificar información inmediata, dado que las mismas se ejecutan a través de internet, los datos son procesados y almacenados dentro de la web. La metodología utilizada fue programación extrema, metodología ágil de gestión de proyectos que se centra en la velocidad y la simplicidad con ciclos de desarrollo cortos y con menos documentación. De acuerdo con los objetivos establecidos, se utilizó la entrevista, encuesta, reuniones con los actores para desarrollar las historias de usuarios, se planifico, diseño, programo y realizaron pruebas a la aplicación. Como producto resultante se desarrolló una App móvil para el apoyo de los docentes en la solicitud de los procesos manejados en la Coordinación de Formación permanente y docente de la UPTTMBI, utilizando tecnologías de software libre como son PHP, Java y como gestor de base de datos se utilizó MySQL. La aplicación web móvil tiene como finalidad automatizar procesos que permitan una adecuada administración en lo referente al proceso de ascenso y solicitud de bono didáctico por parte de los docentes de la UPTTMBI, ayudando a la coordinación obtener información inmediata en tiempo real con resultados favorables, que contribuyen al desarrollo óptimo de los procesos y dando un mejor control a las necesidades de los docentes', 1, 'Coordinación de Formación Permanente y Docencia de la Universidad Politécnica Territorial del estado Trujillo Mario Briceño Iragorry', 'App, Aplicación móvil, Coordinación, Ascensos', '2026-08-10 12:14:42.285533', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (108, '2026-08-10', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-10 12:20:17.959675', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (109, '2026-08-11', 'Pregrado', 'El Proyecto Socio Tecnológico realizado en el Departamento de Sistemas del Centro Clínico "María Edelmira Araujo", S.A. tiene como objetivo general ofrecer soporte técnico a usuarios y equipos de computación, utilizando mantenimiento correctivo y preventivo tanto a nivel de software como de hardware. Para la implementación del proyecto, se utilizarán técnicas de entrevista y observación como estrategias de recolección de datos, además de la realización de un inventario. Se espera mejorar la eficiencia y productividad del departamento a través de estas acciones.', 1, 'Centro Clínico “María Edelmira Araujo”', 'Soporte técnico, correctivo, preventivo, software, hardware', '2026-08-11 10:10:03.006883', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (110, '2026-08-25', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio.', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-25 19:01:06.312899', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (111, '2026-08-25', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-25 19:01:06.640902', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (112, '2026-08-25', 'Pregrado', 'Ofrecer a nuestros clientes accesorios para dispositivos móviles de calidad, brindando soluciones prácticas y accesibles que protejan, complementen y mejoren la experiencia diaria con su celular, a través de una atención personalizada y un catálogo de productos variado que se adapte a las necesidades de cada usuario.', 1, 'Smarthphone World C', '', '2026-08-25 19:01:06.749048', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (113, '2026-08-27', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-08-27 09:08:37.073105', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (114, '2026-08-27', 'Pregrado', 'El objetivo general del proyecto Socio Tecnológico fue realizar Soporte Técnico a Equipos de Computación y Usuarios en la Escuela Técnica Comercial “Madre Rafols”. Se utilizó la metodología del marco lógico para determinar los problemas, causas y consecuencias, se complementó con la metodología cuantitativa. Proyecto factible, de carácter descriptiva, se realizó en tres fases. Como técnica de recolección de datos se utilizó la encuesta y como instrumento el cuestionario, La fase de la elaboración de la propuesta, consistió en un Plan de mantenimiento preventivo y correctivo a los equipos de computación, y taller al usuario. Los resultados obtenidos evidencian colocar parte de los problemas da hardware y software. Este proyecto permitió aplicar los conocimientos adquiridos en arquitectura del computador', 1, 'Escuela Técnica Comercial “Madre Rafols”', 'computadoras, mantenimiento, instalación, hardware, software', '2026-08-27 10:17:48.017574', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (117, '2026-08-31', 'Doctorado', 'El presente proyecto de investigación, desarrollado bajo el enfoque de la Investigación Acción Participativa (IAP), tiene como propósito fundamental desarrollar un sistema inteligente basado en algoritmos genéticos para la optimización automática de horarios en la Coordinación del Programa Nacional de Formación en Informática (PNFI) de la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry" Núcleo La Beatriz. A través de un diagnóstico participativo que incluyó entrevistas, observación directa y la aplicación de matrices FODA y CAME, se identificó que el proceso actual de elaboración de horarios se realiza de manera completamente manual, consumiendo entre tres y cuatro semanas por trimestre y generando frecuentes conflictos de asignación. La solución propuesta, seleccionada mediante matriz de decisión multicriterio, consiste en el desarrollo de un sistema con arquitectura web que emplea algoritmos genéticos multiobjetivo para procesar restricciones complejas, minimizando errores en un 95% y reduciendo el tiempo de planificación en un 90%. El proyecto beneficiará directamente a coordinadores, docentes y estudiantes del PNFI, contribuyendo a una gestión académica más eficiente y tecnológicamente confiable', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” NUES Dr', 'Algoritmos genéticos, horarios universitarios, optimización, sistema inteligente, Investigación Acción Participativa', '2026-08-31 10:08:06.559751', NULL, NULL, 'https://github.com/Zhailox/proyect_CIIDI', NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (127, '2026-09-02', 'Pregrado', 'Aunado a esto, el proyecto responde a una necesidad real: la baja fluidez en teclado y la falta de incentivos lúdicos para aprender mecanografía, problema especialmente urgente en entornos con brecha digital; por su diseño ligero y opciones offline, Tippen Tag es viable en mercados con acceso limitado a internet (Venezuela) y adaptable/competitivo en mercados con alta adopción tecnológica (Alemania).', 1, 'Comunidad / Organización No Específicamente Nombrada', '', '2026-09-02 15:10:22.258855', NULL, 'Trayecto I', 'https://github.com/Zhailox/proyect_CIIDI/tree/Zhailox', NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (128, '2026-09-02', 'Pregrado', 'En el ámbito de la seguridad informática, la autenticación basada en contraseñas sigue siendo uno de los eslabones más vulnerables en la protección de sistemas y datos, el presente informe documenta un ejercicio práctico de auditoría de credenciales, cuyo objetivo principal es demostrar la susceptibilidad de las contraseñas débiles ante técnicas de criptoanálisis, específicamente mediante ataques de diccionario.', 1, 'Comunidad / Organización No Específicamente Nombrada', 'Waos', '2026-09-02 17:22:38.760639', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (129, '2026-09-04', 'Pregrado', 'e', 1, 'Comunidad / Organización No Específicamente Nombrada', '', '2026-09-02 17:24:52.328736', NULL, 'Trayecto I', NULL, NULL, NULL);
INSERT INTO public.detalles_proyectos VALUES (132, '2026-09-04', 'Pregrado', 'El presente proyecto tiene como finalidad el desarrollo de un Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales en la Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry". Esta iniciativa surge de un diagnóstico situacional bajo el enfoque de Investigación Acción Participativa (IAP), el cual identificó deficiencias críticas en la recuperación manual de información y riesgos en la preservación del material institucional. Para abordar estas necesidades, el equipo desarrollador propone una solución basada en una arquitectura modular e interoperable con tecnologías de código abierto, gestionada bajo los marcos ágiles de desarrollo, Scrum y XP. El sistema integra un motor de búsqueda híbrido asistido por redes neuronales, optimizando drásticamente los tiempos de localización de material investigativo y garantizando la integridad de los datos mediante un esquema de seguridad RBAC. El proyecto busca transformar los procesos operativos, democratizar el acceso al conocimiento científico y fortalecer la soberanía tecnológica de la institución, estableciendo un modelo de gestión documental escalable para el territorio', 1, 'Universidad Politécnica Territorial del Estado Trujillo “Mario Briceño Iragorry” Núcleo “Dr', 'Gestión documental, Inteligencia científica, Repositorio digital, Redes neuronales, PNFI, Soberanía tecnológica, Metodologías Ágiles, IAP', '2026-09-04 10:35:11.057661', NULL, 'Trayecto I', NULL, 'Desarrollar un Sistema Integral de Gestión Documentos Académicos, basado en una arquitectura modular, para la automatización de la búsqueda híbrida de información y la centralización de recursos académicos en beneficio de la comunidad del PNF en Informática.', true);


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
INSERT INTO public.dimensiones_operativas VALUES (20, 9, 'Aplicaciones multiplataforma', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.');
INSERT INTO public.dimensiones_operativas VALUES (21, 9, 'Aplicaciones web interactivas', 'diseño y desarrollo de soluciones que pueden ejecutarse en distintos entornos (web, móvil, escritorio, híbrido), utilizando frameworks como Flutter, React Native o Electron. Esta dimensión favorece la portabilidad, la eficiencia en el mantenimiento y la cobertura de usuarios diversos.');
INSERT INTO public.dimensiones_operativas VALUES (22, 9, 'Aplicaciones móviles y ubicuas', 'desarrollo de soluciones adaptadas a dispositivos móviles y contextos de movilidad, integrando sensores, geolocalización, notificaciones y conectividad. Se promueve la experiencia de usuario y el acceso remoto a servicios en tiempo real.');
INSERT INTO public.dimensiones_operativas VALUES (23, 9, 'Modelado y gestión de datos', 'diseño conceptual, lógico y físico de estructuras de datos que sustentan el funcionamiento de las aplicaciones, Incluye el uso de modelos entidad-relación, normalización, diseño de esquemas relacionales y no relacionales, así como la implementación en sistemas gestores de bases de datos. Esta dimensión garantiza la integridad, consistencia y eficiencia en el almacenamiento, recuperación y procesamiento de la información.');
INSERT INTO public.dimensiones_operativas VALUES (24, 9, 'Seguridad y auditoría de aplicaciones', 'incorporación de prácticas de desarrollo seguro, autenticación, autorización, cifrado y trazabilidad. Se abordan normativas como ISO/IEC 27001 y principios de privacidad por diseño, garantizando la integridad y confidencialidad de los sistemas.');


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
INSERT INTO public.etiquetas VALUES (2, 'Machine Learning', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (3, 'Educación', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (4, 'Redes Neuronales', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (5, 'Desarrollo Web', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (8, 'Teoría Matemática', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (9, 'Modelo Económico', '#0ea5e9');
INSERT INTO public.etiquetas VALUES (10, 'Construcción', '#0ea5e9');


--
-- Data for Name: historico_versiones_pst; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: investigaciones_ofertadas; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: lineas_investigacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.lineas_investigacion VALUES (7, 'SISTEMAS DE INFORMACION Y MODELADO DE DATOS', 1, 'Desarrollar y gestionar sistemas de informaci¢n dentro del  mbito social. Aplicando soluciones efectivas para el uso adecuado y ¢ptimo de los sistemas de informaci¢n.');
INSERT INTO public.lineas_investigacion VALUES (8, 'EDUMATICA', 1, 'Aplicar las Tecnolog¡as de la Informaci¢n y Comunicaci¢n (TIC) para apoyar el proceso de aprendizaje, y as¡ contribuir al mejoramiento de la educaci¢n en todos sus niveles.');
INSERT INTO public.lineas_investigacion VALUES (10, 'REDES Y TELECOMUNICACIONES', 1, 'Desarrollar aplicaciones que permitan analizar, verificar y simular la transmisi¢n de datos, como tambi‚n la detecci¢n de fallas dentro de una red.');
INSERT INTO public.lineas_investigacion VALUES (9, 'DESARROLLO DE APLICACIONES', 1, 'Desarrollar aplicaciones informáticas que respondan a las necesidades de gestión, control e intercambio de información en diversos entornos organizacionales, educativos y sociales, mediante el uso de tecnologías multiplataforma y arquitecturas orientadas a servicios, tanto en entornos locales como distribuidos.');


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
-- Data for Name: propuestas_empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.propuestas_empresa VALUES (1, 'Punto Yali', '213123123', 'Yohan Estrada', '0416-6777467', 'yohan@gmail.com', 'facturacion', 'Hace falta que venga un mardito chavista pa matarlo', 'aceptada', '2026-09-02 21:41:41.17287', 'Trayecto II (T2)');
INSERT INTO public.propuestas_empresa VALUES (2, '123', '123', '123', '123', '123@gmail.com', 'inventario', '123', 'aceptada', '2026-09-02 21:42:33.312089', 'Trayecto IV (T4)');


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
INSERT INTO public.proyecto_tutores VALUES (129, 40, 3);
INSERT INTO public.proyecto_tutores VALUES (132, 28, 2);
INSERT INTO public.proyecto_tutores VALUES (132, 10, 4);


--
-- Data for Name: recurso_autores; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_autores VALUES (3, 1);
INSERT INTO public.recurso_autores VALUES (57, 41);
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
INSERT INTO public.recurso_autores VALUES (111, 81);
INSERT INTO public.recurso_autores VALUES (111, 82);
INSERT INTO public.recurso_autores VALUES (111, 83);
INSERT INTO public.recurso_autores VALUES (111, 84);
INSERT INTO public.recurso_autores VALUES (112, 52);
INSERT INTO public.recurso_autores VALUES (112, 53);
INSERT INTO public.recurso_autores VALUES (113, 42);
INSERT INTO public.recurso_autores VALUES (113, 43);
INSERT INTO public.recurso_autores VALUES (113, 44);
INSERT INTO public.recurso_autores VALUES (113, 45);
INSERT INTO public.recurso_autores VALUES (114, 81);
INSERT INTO public.recurso_autores VALUES (114, 82);
INSERT INTO public.recurso_autores VALUES (114, 83);
INSERT INTO public.recurso_autores VALUES (114, 84);
INSERT INTO public.recurso_autores VALUES (116, 62);
INSERT INTO public.recurso_autores VALUES (116, 63);
INSERT INTO public.recurso_autores VALUES (116, 64);
INSERT INTO public.recurso_autores VALUES (116, 65);
INSERT INTO public.recurso_autores VALUES (117, 46);
INSERT INTO public.recurso_autores VALUES (127, 42);
INSERT INTO public.recurso_autores VALUES (127, 88);
INSERT INTO public.recurso_autores VALUES (127, 73);
INSERT INTO public.recurso_autores VALUES (128, 34);
INSERT INTO public.recurso_autores VALUES (128, 42);
INSERT INTO public.recurso_autores VALUES (129, 89);
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


--
-- Data for Name: recurso_clasificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_clasificaciones VALUES (49, 7, 5);
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
INSERT INTO public.recurso_clasificaciones VALUES (129, 7, 9);
INSERT INTO public.recurso_clasificaciones VALUES (132, 10, 18);


--
-- Data for Name: recurso_etiquetas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.recurso_etiquetas VALUES (122, 1);
INSERT INTO public.recurso_etiquetas VALUES (122, 2);
INSERT INTO public.recurso_etiquetas VALUES (122, 4);
INSERT INTO public.recurso_etiquetas VALUES (121, 10);
INSERT INTO public.recurso_etiquetas VALUES (120, 8);
INSERT INTO public.recurso_etiquetas VALUES (119, 3);
INSERT INTO public.recurso_etiquetas VALUES (119, 1);
INSERT INTO public.recurso_etiquetas VALUES (118, 1);


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
INSERT INTO public.recursos VALUES (58, 'Sistema Integral de Gestión de Documasdasdasdasentos Académicos para el Comité Científico Investigaasdasdasdasdor del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1783396914.pdf');
INSERT INTO public.recursos VALUES (59, 'SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ', 1, 2026, 1, 1, 'documentos/pst/pst_sistema_de_optimizaci__n_basad_1785849778.pdf');
INSERT INTO public.recursos VALUES (69, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .', 1, 2023, 1, 1, 'documentos/pst/pst_nues_dr__pablo_viloria_____la__1785851934.pdf');
INSERT INTO public.recursos VALUES (72, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.', 1, 2026, 1, 1, 'documentos/pst/pst_sistema_integral_de_gesti__n_c_1785852671.pdf');
INSERT INTO public.recursos VALUES (78, 'PST Prueba Carga por Lotes - 20260805134326', 1, 2026, 1, 1, 'documentos/pst/pst_pst_prueba_carga_por_lotes___2_1785937406.pdf');
INSERT INTO public.recursos VALUES (79, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937515.pdf');
INSERT INTO public.recursos VALUES (80, 'NUES DR. PABLO VILORIA – LA BEATRIZ SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJO”, S.A. VALERA ESTADO TRUJILLO .', 1, 2023, 1, 1, 'documentos/pst/pst_nues_dr__pablo_viloria_____la__1785937685.pdf');
INSERT INTO public.recursos VALUES (81, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'documentos/pst/pst_sistema_integral_de_gesti__n_d_1785937685.pdf');
INSERT INTO public.recursos VALUES (82, 'OPTIMIZACIÓN DEL SISTEMA DE INFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 1, 1, 'documentos/pst/pst_optimizaci__n_del_sistema_de_i_1785937685.pdf');
INSERT INTO public.recursos VALUES (83, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 1, 1, 'documentos/pst/pst_sistema_inteligente_para_la_ge_1785937685.pdf');
INSERT INTO public.recursos VALUES (84, 'SOPORTE TECNICO A EQUIPOS Y USUARIOS DE LABORATORIO I EN LA E.T.C MADRE RAFOLS', 1, 2023, 1, 1, 'documentos/pst/pst_soporte_tecnico_a_equipos_y_us_1785937686.pdf');
INSERT INTO public.recursos VALUES (85, 'PST Prueba Duplicados - 20260805135642', 1, 2026, 1, 1, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785938202.pdf');
INSERT INTO public.recursos VALUES (86, 'PST Prueba Duplicados - 20260805140204', 1, 2026, 1, 1, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785938524.pdf');
INSERT INTO public.recursos VALUES (87, 'PST Prueba Duplicados - 20260805143446', 1, 2026, 1, 1, 'documentos/pst/pst_pst_prueba_duplicados___202608_1785940486.pdf');
INSERT INTO public.recursos VALUES (88, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN CORPOELEC', 1, 2021, 1, 1, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1786372014_343.docx');
INSERT INTO public.recursos VALUES (89, 'MÓDULO INTELIGENTE BASADO EN MACHINE LEARNING PARA LA GESTIÓN DE LAS LÍNEAS DE INVESTIGACIÓN PARA PROYECTOS ACADÉMICOS DE LA UPTTMBI - NÚCLEO LA BEATRIZ', 1, 2026, 1, 1, 'storage/documentos/pst/pst_m__dulo_inteligente_basado_en__1786372449_773.docx');
INSERT INTO public.recursos VALUES (90, 'OPTIMIZACIÓN DEL SISTEMA DE sdasdasdINFORMACION PARA EL CONTROL DE MATRICULA EN EL CENTRO DE ATENCIÓN INTEGRAL PARA PERSONAS CON AUTISMO “CAIPA TRUJILLO” VERSIÓN 2.0', 1, 2026, 1, 1, NULL);
INSERT INTO public.recursos VALUES (91, 'Sistema Inteligente de Redes Neurosdasdasdasdasdasdsadnales para la Gestión Integral de la Coordinación PNF de Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786373559_627.docx');
INSERT INTO public.recursos VALUES (94, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WORLD C.A.2222', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1786376454_286.docx');
INSERT INTO public.recursos VALUES (93, 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación P2222NF de Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786376074_943.docx');
INSERT INTO public.recursos VALUES (92, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMIN2wwdasdaISTRATIVA EN LA ESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1786376037_906.docx');
INSERT INTO public.recursos VALUES (112, 'SISTEMA INTEGRAL DE GESTIÓN COMERCIAL Y TIENDA VIRTUAL PARA SMARTPHONE WOssssssssssssssssssRLD C.A.', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_c_1787698715_771.docx');
INSERT INTO public.recursos VALUES (97, 'Sistema Inteligente de Redes Neuronales para la Gestión Integral de la Coordinación PNF desdasdasd Contaduría Pública UPTT Mario Briceño Iragorry', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_inteligente_de_redes_n_1786377809_260.docx');
INSERT INTO public.recursos VALUES (99, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Inves222222tigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378254_697.docx');
INSERT INTO public.recursos VALUES (100, 'il para el proceso de Ascensos en la Coordin222222ación de Formación Permanente y Docencia de la UPTTMBI Docente Asesor: Dra.  María Luisa Colmenares Representante Institucional: Dra. Rossana Virgilio Representante Organizacional: Dr. Carlos Simancas', 1, 2023, 1, 1, NULL);
INSERT INTO public.recursos VALUES (108, 'Sistema Integral de Gestión de Documentos Académicos para el C222222222omité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1786378813_891.docx');
INSERT INTO public.recursos VALUES (109, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACION Y USUARIOS EN CENTRO CLÍNICO “MARÍA EDELMIRA ARAUJOooo”', 1, 2023, 1, 1, 'storage/documentos/pst/pst_nues_dr__pablo_viloria_____la__1786457302_317.pdf');
INSERT INTO public.recursos VALUES (110, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Científico Investigador del PNsssssssF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787698529_393.docx');
INSERT INTO public.recursos VALUES (111, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LssssssssssssA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 1, 1, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787698700_582.pdf');
INSERT INTO public.recursos VALUES (113, 'Sistema Integral de Gestión de Documentos Académicos para el 22312312312312213123Comité Científico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1787836105_952.docx');
INSERT INTO public.recursos VALUES (114, 'SOPORTE TÉCNICO A EQUIPOS DE COMPUTACIÓN Y USUARIOS EN LA ESCUELA TÉCNICA COMERCIAL “MADRE RAFOLS”', 1, 2024, 1, 1, 'storage/documentos/pst/pst_soporte_t__cnico_a_equipos_de__1787840266_406.pdf');
INSERT INTO public.recursos VALUES (116, 'SISTEMA INTELIGENTE PARA LA GESTIÓN ACADÉMICA Y ADMINISTRATIVA EN LA asdasdasdasdESCUELA NACIONAL “ANTONIO PÉREZ CARMONA”, ESCUQUE, ESTADO TRUJILLO', 1, 2026, 1, 1, 'storage/documentos/pst/pst_sistema_inteligente_para_la_ge_1788184014_102.docx');
INSERT INTO public.recursos VALUES (117, 'SISTEMA DE OPTIMIZACIÓN BASADO EN ALGORITMOS GENÉTICOS PARA LA GESTIsadasdasdÓN DE HORARIOS DEL PNFI DE LA UPTTMBI, NÚCLEO LA BEATRIZ', 1, 2026, 1, 1, 'PST 4 David LidmarFinal.docx');
INSERT INTO public.recursos VALUES (127, 'ACTIVIDADES ACREDITABLES IV INFORME DE MERCADEO: TIPPEN TAG', 1, 2026, 1, 1, 'storage/documentos/pst/pst_actividades_acreditables_iv_in_1788376198_636.docx');
INSERT INTO public.recursos VALUES (128, 'Materia: Seguridad Informática', 1, 2026, 1, 1, 'storage/documentos/pst/pst_materia__seguridad_inform__tic_1788384113_508.docx');
INSERT INTO public.recursos VALUES (129, 'Verde   Gestion de BD', 1, 2021, 1, 1, 'storage/documentos/pst/pst_verde___gestion_de_bd_1788384263_817.docx');
INSERT INTO public.recursos VALUES (132, 'Sistema Integral de Gestión de Documentos Académicos para el Comité Casdasdasientífico Investigador del PNF en Informática apoyado en Redes Neuronales', 1, 2025, 1, 1, 'storage/documentos/pst/pst_sistema_integral_de_gesti__n_d_1788532202_175.docx');
INSERT INTO public.recursos VALUES (122, 'Revisión sistemática del impacto de las fibras de polipropileno en las propiedades físico-mecánicas, microestructurales y de durabilidad del Concreto', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121649/97474');
INSERT INTO public.recursos VALUES (121, 'Modelo matemático para el balance de calor de un techo verde en condiciones de trópico húmedo', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/123977/97473');
INSERT INTO public.recursos VALUES (120, 'Determinantes de la aceptación del uso de la banca móvil por parte de ganaderos', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/121522/97457');
INSERT INTO public.recursos VALUES (119, 'Entorno virtual de capacitación con EOG para manipular robots asistenciales', 3, 2026, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/124310/98135');
INSERT INTO public.recursos VALUES (118, 'Middleware MiSCi para ciudades inteligentes extendido con datos enlazados', 3, 2020, 1, 1, 'https://revistas.unal.edu.co/index.php/dyna/article/view/83226');


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

SELECT pg_catalog.setval('public.auditoria_id_seq', 258, true);


--
-- Name: autores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.autores_id_seq', 91, true);


--
-- Name: carreras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carreras_id_seq', 5, true);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 9, true);


--
-- Name: cursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cursos_id_seq', 6, true);


--
-- Name: dimensiones_operativas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dimensiones_operativas_id_seq', 24, true);


--
-- Name: editoriales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.editoriales_id_seq', 7, true);


--
-- Name: etiquetas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.etiquetas_id_seq', 12, true);


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

SELECT pg_catalog.setval('public.lineas_investigacion_id_seq', 15, true);


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
-- Name: propuestas_empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.propuestas_empresa_id_seq', 2, true);


--
-- Name: recursos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.recursos_id_seq', 141, true);


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

SELECT pg_catalog.setval('public.tutores_id_seq', 40, true);


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

\unrestrict GFwQfzQqRth4dECTCdxsPOVEfQhjujUxK7poy6sendoyXBknD0EAh1UAJOZCPlm

