<?php
// core/System/Kernel.php
date_default_timezone_set('America/Caracas');

require_once CORE_PATH . 'Interfaces/ModuleContract.php';
require_once CORE_PATH . 'Security/SessionManager.php';
require_once CORE_PATH . 'Security/EmergencyRescueService.php';
require_once CORE_PATH . 'Security/AuditLogger.php';
require_once CORE_PATH . 'Installer/InstallerHook.php';
require_once CORE_PATH . 'Services/MaintenanceService.php';
require_once CORE_PATH . 'System/ModuleLoader.php';
require_once CORE_PATH . 'System/AssetResolver.php';
require_once CORE_PATH . 'Http/FeatureFlagMiddleware.php';
require_once CORE_PATH . 'Http/Router.php';
require_once CORE_PATH . 'Http/Dispatcher.php';

class Kernel {
    private SessionManager $sessionManager;
    private InstallerHook $installerHook;
    private MaintenanceService $maintenanceService;
    private ModuleLoader $moduleLoader;
    private AssetResolver $assetResolver;
    private FeatureFlagMiddleware $featureFlagMiddleware;
    private Router $router;
    private Dispatcher $dispatcher;

    public function __construct() {
        $this->sessionManager = new SessionManager();
        $this->installerHook = new InstallerHook();
        $this->maintenanceService = new MaintenanceService();
        $this->moduleLoader = new ModuleLoader();
        $this->assetResolver = new AssetResolver();
        $this->featureFlagMiddleware = new FeatureFlagMiddleware();
        $this->router = new Router();
        $this->dispatcher = new Dispatcher($this);

        $archivo_candado = __DIR__ . '/../../storage/installed.lock';
        if (file_exists($archivo_candado)) {
            $this->moduleLoader->loadModules();
        }
    }

    public function run(): void {
        $this->sessionManager->start();

        $ruta = $_GET['ruta'] ?? 'inicio';

        // 1. Intercepción por Instalación Autónoma
        if ($this->installerHook->intercept($ruta)) {
            return;
        }

        // 2. Intercepción por Ventanas de Mantenimiento
        if ($this->maintenanceService->intercept($ruta)) {
            return;
        }

        // 3. Intercepción por Feature Flags (Modo Offline / Solo Lectura)
        if ($this->featureFlagMiddleware->intercept($ruta)) {
            return;
        }

        // 4. Enrutamiento y Ejecución MVC
        $rutasGlobales = $this->moduleLoader->getRutasGlobales();
        $match = $this->router->resolve($ruta, $rutasGlobales);

        $this->dispatcher->dispatch(
            $ruta,
            $match,
            $this->moduleLoader->getModulosInstalados(),
            $this->moduleLoader->getMenuGlobal(),
            $this->assetResolver
        );
    }

    // Métodos delegados para mantener retrocompatibilidad total con SuperAdmin y Vistas

    public function getInfoModulosAdmin(): array {
        return $this->moduleLoader->getInfoModulosAdmin();
    }

    public function getTarjetasInicio(): array {
        return $this->assetResolver->getTarjetasInicio($this->moduleLoader->getModulosInstalados());
    }

    public function getControlesHeader(): array {
        return $this->assetResolver->getControlesHeader($this->moduleLoader->getModulosInstalados());
    }

    public function getGlobalCss(): array {
        return $this->assetResolver->getGlobalCss($this->moduleLoader->getModulosInstalados());
    }

    public function getHomeCss(): array {
        return $this->assetResolver->getHomeCss($this->moduleLoader->getModulosInstalados());
    }
}