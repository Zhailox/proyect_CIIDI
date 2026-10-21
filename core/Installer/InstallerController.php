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
            echo json_encode(['exito' => false, 'mensaje' => 'El sistema ya se encuentra instalado y bloqueado por seguridad.']);
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

        $res = InstallerService::createDatabase($host, $port, $user, $pass, $dbName);
        echo json_encode($res);
        exit;
    }

    public function cleanDbAjax() {
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

        $res = InstallerService::cleanDatabaseSchema($host, $port, $user, $pass, $dbName);
        echo json_encode($res);
        exit;
    }

    public function testSmtpAjax() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (InstallerService::lockFileExists()) {
            echo json_encode(['exito' => false, 'mensaje' => 'El sistema ya se encuentra instalado.']);
            exit;
        }

        $host      = trim($_POST['smtp_host'] ?? '');
        $port      = (int)($_POST['smtp_port'] ?? 587);
        $user      = trim($_POST['smtp_user'] ?? '');
        $pass      = $_POST['smtp_pass'] ?? '';
        $fromEmail = trim($_POST['smtp_from_email'] ?? $user);
        $testEmail = trim($_POST['smtp_test_to'] ?? $user);

        if (empty($host) || empty($user) || empty($testEmail)) {
            echo json_encode(['exito' => false, 'mensaje' => 'Debe ingresar el servidor Host, el Usuario y el Correo destinatario para la prueba.']);
            exit;
        }

        $res = InstallerService::testSmtpConnection($host, $port, $user, $pass, $fromEmail, $testEmail);
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

        // Parámetros de Base de Datos
        $host       = trim($_POST['host'] ?? 'localhost');
        $port       = trim($_POST['port'] ?? '5432');
        $user       = trim($_POST['user'] ?? 'postgres');
        $pass       = $_POST['pass'] ?? '';
        $dbName     = trim($_POST['db'] ?? 'ciidi');
        $reinstalar = !empty($_POST['reinstalar']);

        // Parámetros de Identidad Institucional y Red
        $extraEnv = [
            'inst_nombre' => trim($_POST['inst_nombre'] ?? 'Universidad Politécnica Territorial de Trujillo Mario Briceño Iragorry'),
            'inst_siglas' => trim($_POST['inst_siglas'] ?? 'UPTTMBI'),
            'app_url'     => trim($_POST['app_url'] ?? 'http://localhost/proyect_CIIDI'),
            'timezone'    => trim($_POST['timezone'] ?? 'America/Caracas'),
            'app_env'     => trim($_POST['app_env'] ?? 'production'),
            'app_debug'   => !empty($_POST['app_debug'])
        ];

        // Parámetros de Seguridad de Sesión
        $seguridad = [
            'timeout_minutos' => (int)($_POST['session_timeout'] ?? 120),
            'intentos_login'  => (int)($_POST['waf_attempts'] ?? 5)
        ];

        // Parámetros de Tareas Programadas (Scheduler)
        $enableScheduler = !empty($_POST['enable_scheduler']);

        // Parámetros de Correo SMTP
        $enableSmtp = !empty($_POST['enable_smtp']);
        $smtpData = null;
        if ($enableSmtp) {
            $smtpData = [
                'habilitado' => true,
                'host'       => trim($_POST['smtp_host'] ?? ''),
                'port'       => (int)($_POST['smtp_port'] ?? 587),
                'user'       => trim($_POST['smtp_user'] ?? ''),
                'pass'       => $_POST['smtp_pass'] ?? '',
                'from_email' => trim($_POST['smtp_from_email'] ?? ''),
                'from_name'  => trim($_POST['smtp_from_name'] ?? 'Sistema CIIDI - UPTTMBI')
            ];
        }

        // Parámetros del SuperAdmin
        $adminData = [
            'cedula'        => trim($_POST['admin_cedula'] ?? ''),
            'nombre'        => trim($_POST['admin_nombre'] ?? ''),
            'correo'        => trim($_POST['admin_correo'] ?? ''),
            'clave'         => $_POST['admin_clave'] ?? '',
            'clave_confirm' => $_POST['admin_clave_confirm'] ?? ''
        ];

        // 1. Validaciones previas de integridad del SuperAdmin
        if (!empty($adminData['clave_confirm']) && $adminData['clave'] !== $adminData['clave_confirm']) {
            echo json_encode(['exito' => false, 'mensaje' => 'La confirmación de la contraseña no coincide con la contraseña maestra.']);
            exit;
        }

        if (strlen($adminData['clave']) < 8) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña del SuperAdmin debe tener al menos 8 caracteres.']);
            exit;
        }

        if (!filter_var($adminData['correo'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['exito' => false, 'mensaje' => 'El correo electrónico ingresado no tiene un formato válido.']);
            exit;
        }

        // 2. Probar conexión al servidor PostgreSQL
        $testConn = InstallerService::testDbConnection($host, $port, $user, $pass, $dbName);
        if (!$testConn['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión: ' . $testConn['mensaje']]);
            exit;
        }

        // 3. Crear base de datos o limpiar esquema si se solicitó
        if (empty($testConn['bd_existe'])) {
            $createDbRes = InstallerService::createDatabase($host, $port, $user, $pass, $dbName);
            if (!$createDbRes['exito']) {
                echo json_encode(['exito' => false, 'mensaje' => 'No se pudo crear la base de datos: ' . $createDbRes['mensaje']]);
                exit;
            }
        } elseif ($reinstalar && !empty($testConn['tiene_tablas'])) {
            $cleanRes = InstallerService::cleanDatabaseSchema($host, $port, $user, $pass, $dbName);
            if (!$cleanRes['exito']) {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al limpiar esquema previo: ' . $cleanRes['mensaje']]);
                exit;
            }
            $testConn['tiene_tablas'] = false;
        }

        // 4. Sincronizar archivo .env con APP_KEY segura y storage/db_config.json
        $syncRes = InstallerService::syncEnvAndKey($host, $port, $user, $pass, $dbName, $extraEnv);
        if (!$syncRes['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => $syncRes['mensaje']]);
            exit;
        }

        // 5. Configurar Tareas Programadas (Scheduler)
        InstallerService::configureSchedulerTasks($enableScheduler);

        // 6. Configurar Parámetros del Sistema (Seguridad y SMTP)
        InstallerService::configureSystemSettings($seguridad, $smtpData);

        // 7. Ejecutar importación de esquema base si la base de datos está vacía o se reinstaló
        if (empty($testConn['tiene_tablas']) || $reinstalar) {
            $schemaRes = InstallerService::runSqlSchema($host, $port, $user, $pass, $dbName);
            if (!$schemaRes['exito']) {
                echo json_encode(['exito' => false, 'mensaje' => 'Fallo en la importación del esquema base: ' . $schemaRes['mensaje']]);
                exit;
            }
        }

        // 8. Ejecutar migraciones pendientes
        $migRes = InstallerService::runPendingMigrations($host, $port, $user, $pass, $dbName);

        // 9. Crear / actualizar usuario SuperAdmin principal
        $adminRes = InstallerService::createSuperAdmin($host, $port, $user, $pass, $dbName, $adminData);
        if (!$adminRes['exito']) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo al registrar la cuenta de SuperAdmin: ' . $adminRes['mensaje']]);
            exit;
        }

        // 10. Generar candado de seguridad inmutable installed.lock
        if (!InstallerService::createLockFile()) {
            echo json_encode(['exito' => false, 'mensaje' => 'Fallo crítico al crear el candado de seguridad storage/installed.lock']);
            exit;
        }

        echo json_encode([
            'exito'       => true,
            'mensaje'     => '¡Despliegue y configuración de CIIDI completados con éxito! Redirigiendo...',
            'migraciones' => $migRes['aplicadas'] ?? 0,
            'scheduler'   => $enableScheduler ? 'Activo' : 'Inactivo',
            'smtp'        => $enableSmtp ? 'Configurado' : 'Omitido'
        ]);
        exit;
    }
}
