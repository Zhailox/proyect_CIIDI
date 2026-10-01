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
require_once CORE_PATH . 'Security/RateLimiter.php';
require_once CORE_PATH . 'Security/CSRF.php';
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
        // Cabeceras de Seguridad Web Estrictas (OWASP / ISO 27001 A.8.28)
        if (!headers_sent()) {
            header("X-Content-Type-Options: nosniff");
            header("X-Frame-Options: SAMEORIGIN");
            header("X-XSS-Protection: 1; mode=block");
            header("Referrer-Policy: strict-origin-when-cross-origin");
            header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
        }

        $this->sessionManager->start();

        // Escudo de Seguridad Global: WAF & Rate Limiter de Capa de Aplicación
        $bloqueo = RateLimiter::estaBloqueada();
        if ($bloqueo['bloqueada']) {
            http_response_code(403);
            $tituloSeguridad  = 'Acceso Restringido por Seguridad';
            $mensajeSeguridad = $bloqueo['razon'] ?? 'Su dirección IP ha sido temporalmente restringida por el sistema de seguridad institucional.';
            $tipoAmenaza      = ($bloqueo['tipo'] ?? '') === 'blacklist' ? 'Lista Negra Global' : 'Límite de Intentos Excedido';
            $parametroAmenaza = null;
            $ipCliente        = RateLimiter::obtenerIPCliente();
            $isStandalone     = true;

            $vistaSeguridad = defined('CORE_VIEWS') ? CORE_VIEWS . 'seguridad_bloqueo.php' : __DIR__ . '/../Views/seguridad_bloqueo.php';
            if (file_exists($vistaSeguridad)) {
                include $vistaSeguridad;
            } else {
                echo "<h2>Acceso Restringido por Seguridad</h2><p>" . htmlspecialchars($mensajeSeguridad) . "</p>";
            }
            exit;
        }

        $amenaza = RateLimiter::inspeccionarPayloadsSeguridad();
        if ($amenaza['amenaza_detectada']) {
            http_response_code(403);
            $tituloSeguridad  = 'Petición Interceptada por WAF Institucional';
            $mensajeSeguridad = 'Se ha detectado e interceptado un patrón de ataque sospechoso en la solicitud. Su dirección IP ha sido registrada y bloqueada temporalmente para salvaguardar la integridad de la infraestructura institucional.';
            $tipoAmenaza      = $amenaza['tipo'] ?? 'Amenaza Web';
            $parametroAmenaza = $amenaza['parametro'] ?? null;
            $ipCliente        = RateLimiter::obtenerIPCliente();
            $isStandalone     = true;

            $vistaSeguridad = defined('CORE_VIEWS') ? CORE_VIEWS . 'seguridad_bloqueo.php' : __DIR__ . '/../Views/seguridad_bloqueo.php';
            if (file_exists($vistaSeguridad)) {
                include $vistaSeguridad;
            } else {
                echo "<h2>Petición Bloqueada por WAF</h2><p>" . htmlspecialchars($mensajeSeguridad) . "</p>";
            }
            exit;
        }

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