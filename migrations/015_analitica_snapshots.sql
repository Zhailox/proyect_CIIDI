-- Migración 011: Tabla de Historial de Snapshots de Predicción (Auditoría Predictiva IA)
CREATE TABLE IF NOT EXISTS public.analitica_snapshots (
    id SERIAL PRIMARY KEY,
    fecha_snapshot TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    usuario_nombre VARCHAR(150) DEFAULT 'Sistema CIIDI',
    id_carrera INTEGER NULL,
    carrera_nombre VARCHAR(150) DEFAULT 'Todas las Carreras / PNF',
    modelo_usado VARCHAR(80) NOT NULL,
    modelo_label VARCHAR(120) NOT NULL,
    fuente_datos VARCHAR(30) DEFAULT 'pst',
    ventana_historial VARCHAR(30) DEFAULT '2y',
    horizonte_trimestres INTEGER NOT NULL DEFAULT 4,
    trimestre_base VARCHAR(20) NULL,
    trimestre_meta VARCHAR(20) NULL,
    rmse_global NUMERIC(10, 4) DEFAULT 0,
    mae_global NUMERIC(10, 4) DEFAULT 0,
    confianza_pct NUMERIC(5, 2) DEFAULT 0,
    total_historico INTEGER DEFAULT 0,
    total_proyectado INTEGER DEFAULT 0,
    estacionalidad_activa BOOLEAN DEFAULT TRUE,
    resumen_json JSONB NOT NULL DEFAULT '[]'::jsonb
);

CREATE INDEX IF NOT EXISTS idx_analitica_snapshots_fecha ON public.analitica_snapshots (fecha_snapshot DESC);
