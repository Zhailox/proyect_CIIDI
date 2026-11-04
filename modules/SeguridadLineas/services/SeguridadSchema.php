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
     * DDL idempotente compatible con MariaDB 10.6+ (InnoDB / utf8mb4_unicode_ci).
     */
    public static function sentencias(): array {
        return [
            // Bitácora con encadenamiento criptográfico (HMAC-SHA256)
            "CREATE TABLE IF NOT EXISTS `seg_lineas_logs` (
                `id`             BIGINT NOT NULL AUTO_INCREMENT,
                `fecha`          DATETIME NOT NULL,
                `severidad`      VARCHAR(10)  NOT NULL DEFAULT 'INFO',
                `submodulo`      VARCHAR(80)  NOT NULL DEFAULT '',
                `accion`         VARCHAR(30)  NOT NULL DEFAULT '',
                `ruta`           VARCHAR(160) NOT NULL DEFAULT '',
                `resultado`      VARCHAR(12)  NOT NULL DEFAULT 'PERMITIDO',
                `usuario_id`     INT          NULL,
                `usuario_nombre` VARCHAR(160) NOT NULL DEFAULT '',
                `nivel`          INT          NOT NULL DEFAULT 999,
                `ip`             VARCHAR(64)  NOT NULL DEFAULT '',
                `metodo`         VARCHAR(10)  NOT NULL DEFAULT 'GET',
                `user_agent`     VARCHAR(255) NOT NULL DEFAULT '',
                `detalles`       VARCHAR(2000) NOT NULL DEFAULT '',
                `hash_anterior`  VARCHAR(64)  NOT NULL,
                `hash`           VARCHAR(64)  NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_seg_lineas_logs_fecha` (`fecha`),
                KEY `idx_seg_lineas_logs_ip_res` (`ip`, `resultado`, `fecha`),
                KEY `idx_seg_lineas_logs_usuario` (`usuario_id`, `fecha`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Matriz de permisos: nivel de privilegio × submódulo × acción
            "CREATE TABLE IF NOT EXISTS `seg_lineas_permisos` (
                `nivel`           INT          NOT NULL,
                `submodulo`       VARCHAR(80)  NOT NULL,
                `accion`          VARCHAR(30)  NOT NULL,
                `permitido`       TINYINT(1)   NOT NULL,
                `actualizado_por` VARCHAR(160) NOT NULL DEFAULT '',
                `actualizado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`nivel`, `submodulo`, `accion`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Excepciones individuales por usuario (tienen prioridad sobre la matriz)
            "CREATE TABLE IF NOT EXISTS `seg_lineas_excepciones` (
                `id`          INT           NOT NULL AUTO_INCREMENT,
                `usuario_id`  INT           NOT NULL,
                `submodulo`   VARCHAR(80)   NOT NULL,
                `accion`      VARCHAR(30)   NOT NULL,
                `efecto`      VARCHAR(10)   NOT NULL,
                `motivo`      VARCHAR(2000) NOT NULL DEFAULT '',
                `expira_en`   DATETIME      NULL,
                `creado_por`  VARCHAR(160)  NOT NULL DEFAULT '',
                `creado_en`   DATETIME      NOT NULL,
                CONSTRAINT `seg_lineas_excepciones_efecto_check` CHECK (`efecto` IN ('permitir', 'denegar')),
                PRIMARY KEY (`id`),
                KEY `idx_seg_lineas_exc_usuario` (`usuario_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Bloqueos de IP (manuales o automáticos por ráfaga de denegaciones)
            "CREATE TABLE IF NOT EXISTS `seg_lineas_bloqueos` (
                `id`            INT           NOT NULL AUTO_INCREMENT,
                `ip`            VARCHAR(64)   NOT NULL,
                `motivo`        VARCHAR(2000) NOT NULL DEFAULT '',
                `origen`        VARCHAR(10)   NOT NULL DEFAULT 'manual',
                `creado_en`     DATETIME      NOT NULL,
                `expira_en`     DATETIME      NULL,
                `creado_por`    VARCHAR(160)  NOT NULL DEFAULT '',
                `activo`        TINYINT(1)    NOT NULL DEFAULT 1,
                `levantado_por` VARCHAR(160)  NULL,
                `levantado_en`  DATETIME      NULL,
                PRIMARY KEY (`id`),
                KEY `idx_seg_lineas_bloq_ip` (`ip`, `activo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Alertas de seguridad
            "CREATE TABLE IF NOT EXISTS `seg_lineas_alertas` (
                `id`           INT           NOT NULL AUTO_INCREMENT,
                `fecha`        DATETIME      NOT NULL,
                `tipo`         VARCHAR(40)   NOT NULL,
                `severidad`    VARCHAR(10)   NOT NULL,
                `titulo`       VARCHAR(200)  NOT NULL,
                `detalle`      VARCHAR(2000) NOT NULL DEFAULT '',
                `ip`           VARCHAR(64)   NOT NULL DEFAULT '',
                `usuario_id`   INT           NULL,
                `estado`       VARCHAR(10)   NOT NULL DEFAULT 'abierta',
                `atendida_por` VARCHAR(160)  NULL,
                `atendida_en`  DATETIME      NULL,
                PRIMARY KEY (`id`),
                KEY `idx_seg_lineas_alertas_estado` (`estado`, `fecha`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Políticas (clave → valor JSON)
            "CREATE TABLE IF NOT EXISTS `seg_lineas_politicas` (
                `clave`           VARCHAR(60)  NOT NULL,
                `valor`           JSON         NOT NULL,
                `actualizado_por` VARCHAR(160) NOT NULL DEFAULT '',
                `actualizado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`clave`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            // Reclasificación manual de rutas → acción
            "CREATE TABLE IF NOT EXISTS `seg_lineas_rutas` (
                `ruta`            VARCHAR(160) NOT NULL,
                `accion`          VARCHAR(30)  NOT NULL,
                `actualizado_por` VARCHAR(160) NOT NULL DEFAULT '',
                `actualizado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ruta`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];
    }
}
