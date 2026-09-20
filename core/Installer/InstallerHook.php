<?php
// core/Installer/InstallerHook.php

class InstallerHook {
    private string $lockFilePath;
    private array $installerRoutes = ['install', 'installer-test-db', 'installer-create-db', 'installer-run'];

    public function __construct(?string $lockPath = null) {
        if ($lockPath !== null) {
            $this->lockFilePath = $lockPath;
        } elseif (defined('STORAGE_PATH')) {
            $this->lockFilePath = STORAGE_PATH . 'installed.lock';
        } else {
            $this->lockFilePath = __DIR__ . '/../../storage/installed.lock';
        }
    }

    /**
     * Intercepta la solicitud si el sistema aún no está instalado o si se intenta acceder al instalador estado ya instalado.
     * Retorna true si la solicitud fue manejada (interceptada), false para continuar normalmente.
     */
    public function intercept(string $ruta): bool {
        $isInstalled = file_exists($this->lockFilePath);

        if (!$isInstalled) {
            require_once CORE_PATH . 'Installer/InstallerController.php';
            $installerController = new InstallerController();

            if ($ruta === 'installer-test-db') {
                $installerController->testConnectionAjax();
                exit;
            } elseif ($ruta === 'installer-create-db') {
                $installerController->createDbAjax();
                exit;
            } elseif ($ruta === 'installer-run') {
                $installerController->installSystemAjax();
                exit;
            } else {
                $datosVista = $installerController->index();
                if (is_array($datosVista)) {
                    extract($datosVista);
                }
                require_once CORE_PATH . 'Installer/views/wizard.php';
                exit;
            }
            return true;
        } elseif (in_array($ruta, $this->installerRoutes, true)) {
            // Si la aplicación ya está instalada e intentan ir a /install, redirigir a login
            header("Location: login");
            exit;
        }

        return false;
    }
}
