<?php
// modules/SeguridadLineas/services/SeguridadSchema.php

/**
 * Instalador autónomo del esquema de base de datos del módulo de Seguridad
 * del macro módulo "Líneas de Investigación".
 *
 * Se ejecuta una sola vez por versión (candado en storage/seg_lineas_schema.json).
 * Si alguna tabla desaparece (p. ej. restauración de un respaldo antiguo), basta con
 * invocar SeguridadSchema::invalidar() y el siguiente request la recrea.
 */
final class SeguridadSchema {

    public const VERSION = 1;

    private static ?bool $verificado = null;

    private static function rutaCandado(): string {
        $base = defined('STORAGE_PATH') ? STORAGE_PATH : __DIR__ . '/../../../storage/';
        return rtrim($base, '/\\') . '/seg_lineas_schema.json';
    }

    public static function asegurar(PDO $db): bool {
        if (self::$verificado !== null) {
            return self::$verificado;
        }

        $candado = self::rutaCandado();
        if (is_file($candado)) {
            $data = json_decode((string) @file_get_contents($candado), true) ?: [];
            if ((int) ($data['version'] ?? 0) === self::VERSION) {
                return self::$verificado = true;
            }
        }

        try {
            $db->beginTransaction();
            foreach (self::sentencias() as $sql) {
                $db->exec($sql);
            }
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return self::$verificado = false;
        }

        @file_put_contents($candado, json_encode([
            'version'     => self::VERSION,
            'instalado_en' => date('Y-m-d H:i:s'),
        ], JSON_PRETTY_PRINT));

        return self::$verificado = true;
    }

    public static function invalidar(): void {
        @unlink(self::rutaCandado());
        self::$verificado = null;
    }

    /**
     * DDL idempotente. Se mantiene sincronizado con migrations/004_seguridad_lineas.sql
     */
    public static function sentencias(): array {
        return [
            // Bitácora con encadenamiento criptográfico (HMAC-SHA256)
            "CREATE TABLE IF NOT EXISTS seg_lineas_logs (
                id              BIGSERIAL PRIMARY KEY,
                fecha           TIMESTAMP(0) NOT NULL,
                severidad       VARCHAR(10)  NOT NULL DEFAULT 'INFO',
                submodulo       VARCHAR(80)  NOT NULL DEFAULT '',
                accion          VARCHAR(30)  NOT NULL DEFAULT '',
                ruta            VARCHAR(160) NOT NULL DEFAULT '',
                resultado       VARCHAR(12)  NOT NULL DEFAULT 'PERMITIDO',
                usuario_id      INTEGER      NULL,
                usuario_nombre  VARCHAR(160) NOT NULL DEFAULT '',
                nivel           INTEGER      NOT NULL DEFAULT 999,
                ip              VARCHAR(64)  NOT NULL DEFAULT '',
                metodo          VARCHAR(10)  NOT NULL DEFAULT 'GET',
                user_agent      VARCHAR(255) NOT NULL DEFAULT '',
                detalles        TEXT         NOT NULL DEFAULT '',
                hash_anterior   VARCHAR(64)  NOT NULL,
                hash            VARCHAR(64)  NOT NULL
            )",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_logs_fecha   ON seg_lineas_logs (fecha)",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_logs_ip_res  ON seg_lineas_logs (ip, resultado, fecha)",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_logs_usuario ON seg_lineas_logs (usuario_id, fecha)",

            // Matriz de permisos: nivel de privilegio × submódulo × acción
            "CREATE TABLE IF NOT EXISTS seg_lineas_permisos (
                nivel            INTEGER      NOT NULL,
                submodulo        VARCHAR(80)  NOT NULL,
                accion           VARCHAR(30)  NOT NULL,
                permitido        BOOLEAN      NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (nivel, submodulo, accion)
            )",

            // Excepciones individuales por usuario (tienen prioridad sobre la matriz)
            "CREATE TABLE IF NOT EXISTS seg_lineas_excepciones (
                id          SERIAL PRIMARY KEY,
                usuario_id  INTEGER      NOT NULL,
                submodulo   VARCHAR(80)  NOT NULL,
                accion      VARCHAR(30)  NOT NULL,
                efecto      VARCHAR(10)  NOT NULL CHECK (efecto IN ('permitir', 'denegar')),
                motivo      TEXT         NOT NULL DEFAULT '',
                expira_en   TIMESTAMP(0) NULL,
                creado_por  VARCHAR(160) NOT NULL DEFAULT '',
                creado_en   TIMESTAMP(0) NOT NULL
            )",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_exc_usuario ON seg_lineas_excepciones (usuario_id)",

            // Bloqueos de IP (manuales o automáticos por ráfaga de denegaciones)
            "CREATE TABLE IF NOT EXISTS seg_lineas_bloqueos (
                id             SERIAL PRIMARY KEY,
                ip             VARCHAR(64)  NOT NULL,
                motivo         TEXT         NOT NULL DEFAULT '',
                origen         VARCHAR(10)  NOT NULL DEFAULT 'manual',
                creado_en      TIMESTAMP(0) NOT NULL,
                expira_en      TIMESTAMP(0) NULL,
                creado_por     VARCHAR(160) NOT NULL DEFAULT '',
                activo         BOOLEAN      NOT NULL DEFAULT TRUE,
                levantado_por  VARCHAR(160) NULL,
                levantado_en   TIMESTAMP(0) NULL
            )",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_bloq_ip ON seg_lineas_bloqueos (ip, activo)",

            // Alertas de seguridad
            "CREATE TABLE IF NOT EXISTS seg_lineas_alertas (
                id            SERIAL PRIMARY KEY,
                fecha         TIMESTAMP(0) NOT NULL,
                tipo          VARCHAR(40)  NOT NULL,
                severidad     VARCHAR(10)  NOT NULL,
                titulo        VARCHAR(200) NOT NULL,
                detalle       TEXT         NOT NULL DEFAULT '',
                ip            VARCHAR(64)  NOT NULL DEFAULT '',
                usuario_id    INTEGER      NULL,
                estado        VARCHAR(10)  NOT NULL DEFAULT 'abierta',
                atendida_por  VARCHAR(160) NULL,
                atendida_en   TIMESTAMP(0) NULL
            )",
            "CREATE INDEX IF NOT EXISTS idx_seg_lineas_alertas_estado ON seg_lineas_alertas (estado, fecha)",

            // Políticas (clave → valor JSON)
            "CREATE TABLE IF NOT EXISTS seg_lineas_politicas (
                clave            VARCHAR(60)  PRIMARY KEY,
                valor            JSONB        NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
            )",

            // Reclasificación manual de rutas → acción
            "CREATE TABLE IF NOT EXISTS seg_lineas_rutas (
                ruta             VARCHAR(160) PRIMARY KEY,
                accion           VARCHAR(30)  NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
            )",
        ];
    }
}
