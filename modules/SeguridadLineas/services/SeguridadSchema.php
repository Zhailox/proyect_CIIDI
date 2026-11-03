<?php
// modules/SeguridadLineas/services/SeguridadSchema.php

/**
 * Instalador autónomo del esquema de base de datos del módulo de Seguridad
 * del macro módulo "Líneas de Investigación" (MariaDB 10.6 InnoDB).
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
            foreach (self::sentencias() as $sql) {
                $db->exec($sql);
            }
        } catch (Throwable $e) {
            if (class_exists('Connection')) {
                Connection::logSystemError($e);
            }
            return self::$verificado = false;
        }

        @file_put_contents($candado, json_encode([
            'version'      => self::VERSION,
            'instalado_en' => date('Y-m-d H:i:s'),
        ], JSON_PRETTY_PRINT));

        return self::$verificado = true;
    }

    public static function invalidar(): void {
        @unlink(self::rutaCandado());
        self::$verificado = null;
    }

    /**
     * DDL idempotente para MariaDB 10.6.
     */
    public static function sentencias(): array {
        return [
            "CREATE TABLE IF NOT EXISTS seg_lineas_logs (
                id              BIGINT       NOT NULL AUTO_INCREMENT PRIMARY KEY,
                fecha           DATETIME     NOT NULL,
                severidad       VARCHAR(10)  NOT NULL DEFAULT 'INFO',
                submodulo       VARCHAR(80)  NOT NULL DEFAULT '',
                accion          VARCHAR(30)  NOT NULL DEFAULT '',
                ruta            VARCHAR(160) NOT NULL DEFAULT '',
                resultado       VARCHAR(12)  NOT NULL DEFAULT 'PERMITIDO',
                usuario_id      INT          NULL,
                usuario_nombre  VARCHAR(160) NOT NULL DEFAULT '',
                nivel           INT          NOT NULL DEFAULT 999,
                ip              VARCHAR(64)  NOT NULL DEFAULT '',
                metodo          VARCHAR(10)  NOT NULL DEFAULT 'GET',
                user_agent      VARCHAR(255) NOT NULL DEFAULT '',
                detalles        TEXT         NULL,
                hash_anterior   VARCHAR(64)  NOT NULL,
                hash            VARCHAR(64)  NOT NULL,
                INDEX idx_seg_lineas_logs_fecha (fecha),
                INDEX idx_seg_lineas_logs_ip_res (ip, resultado, fecha),
                INDEX idx_seg_lineas_logs_usuario (usuario_id, fecha)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_permisos (
                nivel            INT          NOT NULL,
                submodulo        VARCHAR(80)  NOT NULL,
                accion           VARCHAR(30)  NOT NULL,
                permitido        TINYINT(1)   NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (nivel, submodulo, accion)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_excepciones (
                id          INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
                usuario_id  INT          NOT NULL,
                submodulo   VARCHAR(80)  NOT NULL,
                accion      VARCHAR(30)  NOT NULL,
                efecto      VARCHAR(10)  NOT NULL,
                motivo      TEXT         NULL,
                expira_en   DATETIME     NULL,
                creado_por  VARCHAR(160) NOT NULL DEFAULT '',
                creado_en   DATETIME     NOT NULL,
                INDEX idx_seg_lineas_exc_usuario (usuario_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_bloqueos (
                id             INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
                ip             VARCHAR(64)  NOT NULL,
                motivo         TEXT         NULL,
                origen         VARCHAR(10)  NOT NULL DEFAULT 'manual',
                creado_en      DATETIME     NOT NULL,
                expira_en      DATETIME     NULL,
                creado_por     VARCHAR(160) NOT NULL DEFAULT '',
                activo         TINYINT(1)   NOT NULL DEFAULT 1,
                levantado_por  VARCHAR(160) NULL,
                levantado_en   DATETIME     NULL,
                INDEX idx_seg_lineas_bloq_ip (ip, activo)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_alertas (
                id            INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
                fecha         DATETIME     NOT NULL,
                tipo          VARCHAR(40)  NOT NULL,
                severidad     VARCHAR(10)  NOT NULL,
                titulo        VARCHAR(200) NOT NULL,
                detalle       TEXT         NULL,
                ip            VARCHAR(64)  NOT NULL DEFAULT '',
                usuario_id    INT          NULL,
                estado        VARCHAR(10)  NOT NULL DEFAULT 'abierta',
                atendida_por  VARCHAR(160) NULL,
                atendida_en   DATETIME     NULL,
                INDEX idx_seg_lineas_alertas_estado (estado, fecha)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_politicas (
                clave            VARCHAR(60)  PRIMARY KEY,
                valor            LONGTEXT     NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS seg_lineas_rutas (
                ruta             VARCHAR(160) PRIMARY KEY,
                accion           VARCHAR(30)  NOT NULL,
                actualizado_por  VARCHAR(160) NOT NULL DEFAULT '',
                actualizado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];
    }
}