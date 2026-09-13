<?php
// core/Installer/InstallerController.php

require_once __DIR__ . '/InstallerService.php';
require_once __DIR__ . '/../Database/Connection.php';

class InstallerController {

    public function index() {
        if (InstallerService::lockFileExists()) {
            header("Location: login");
            exit;
        }

        $envCheck = InstallerService::checkSystemEnvironment();

        return [
            'envCheck' => $envCheck
        ];
    }

    public function testConnectionAjax() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        if (InstallerService::lockFileExists()) {
            echo json_encode(['exito' => false, 'mensaje' => 'El sistema ya se encuentra instalado y bloqueado.']);
            exit;
        }

        $host   = trim($_POST['host'] ?? 'localhost');
        $port   = trim($_POST['port'] ?? '5432');
        $user   = trim($_POST['user'] ?? 'postgres');
        $pass   = $_POST['pass'] ?? '';
        $dbName = trim($_POST['db'] ?? 'ciidi');

        $res = InstallerService::testDbConnection($host, $port, $user, $pass, $dbName);
        echo json_encode($res);
        exit;
    }

    public function createDbAjax() {
        header('Content-Type: application/json');
        if (InstallerService::lockFileExists()) {
            echo json_encode(['exito' => false, 'mensaje' => 'El sistema ya se encuentra instalado.']);
            exit;
        }

        $host   = trim($_POST['host'] ?? 'localhost');
        $port   = trim($_POST['port'] ?? '5432');
        $user   = trim($_POST['user'] ?? 'postgres');
        $pass   = $_POST['pass'] ?? '';
        $dbName = trim($_POST['db'] ?? 'ciidi');

        $res = InstallerService::createDatabase($host, $port, $user, $pass, $dbName);
        echo json_encode($res);
        exit;
    }

    public function installSystemAjax() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        if (InstallerService::lockFileExists()) {
            echo json_encode(['exito' => false, 'mensaje' => 'El sistema ya se encuentra instalado.']);
            exit;
        }

        $host   = trim($_POST['host'] ?? 'localhost');
        $port   = trim($_POST['port'] ?? '5432');
        $user   = trim($_POST['user'] ?? 'postgres');
        $pass   = $_POST['pass'] ?? '';
        $dbName = trim($_POST['db'] ?? 'ciidi');

        $adminData = [
            'cedula' => trim($_POST['admin_cedula'] ?? ''),
            'nombre' => trim($_POST['admin_nombre'] ?? ''),
            'correo' => trim($_POST['admin_correo'] ?? ''),
            'clave'  => $_POST['admin_clave'] ?? ''
        ];

        // 1. Probar conexión nuevamente
        $testConn = InstallerService::testDbConnection($host, $port, $user, $pass, $dbName);
        if (!$testConn['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión: ' . $testConn['mensaje']]);
            exit;
        }

        // 2. Si la base de datos no existe, intentar crearla
        if (empty($testConn['bd_existe'])) {
            $createDbRes = InstallerService::createDatabase($host, $port, $user, $pass, $dbName);
            if (!$createDbRes['exito']) {
                echo json_encode(['exito' => false, 'mensaje' => 'No se pudo crear la BD: ' . $createDbRes['mensaje']]);
                exit;
            }
        }

        // 3. Guardar credenciales verificadas en storage/db_config.json
        if (!Connection::saveCredentials($host, $port, $dbName, $user, $pass)) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo al escribir el archivo de configuración storage/db_config.json']);
            exit;
        }

        // 4. Ejecutar migración de ciidi.sql
        $schemaRes = InstallerService::runSqlSchema($host, $port, $user, $pass, $dbName);
        if (!$schemaRes['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo en la migración SQL: ' . $schemaRes['mensaje']]);
            exit;
        }

        // 5. Crear usuario SuperAdmin principal
        $adminRes = InstallerService::createSuperAdmin($host, $port, $user, $pass, $dbName, $adminData);
        if (!$adminRes['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo al registrar SuperAdmin: ' . $adminRes['mensaje']]);
            exit;
        }

        // 6. Generar candado de seguridad installed.lock
        if (!InstallerService::createLockFile()) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo al crear el candado storage/installed.lock']);
            exit;
        }

        echo json_encode([
            'exito' => true,
            'mensaje' => '¡Instalación y despliegue del sistema completado con éxito! Redirigiendo...'
        ]);
        exit;
    }
}
