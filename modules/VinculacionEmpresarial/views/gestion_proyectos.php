<?php
// modules/VinculacionEmpresarial/views/gestion_proyectos.php
require_once __DIR__ . '/../../../core/Security/Auth.php';
require_once __DIR__ . '/../../SuperAdmin/services/SystemConfigService.php';
$nivelAdmin = SystemConfigService::get('accesos_modulos.vinculacion_empresarial.admin', 1);
Auth::requierePrivilegioMinimo($nivelAdmin, 'auditar', 'VinculacionEmpresarial');

$tab = $_GET['tab'] ?? 'propuestas'; // 'propuestas' o 'equipos'

?>
<style>
.gp-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem 2rem 0 2rem;
    margin-bottom: 2rem;
}
.gp-title {
    color: #121a3e;
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.gp-subtitle {
    color: #64748b;
    margin: 0 0 1.5rem 0;
    font-size: 0.95rem;
}
.gp-main-tabs {
    display: flex;
    gap: 2rem;
}
.gp-main-tab {
    padding: 1rem 0;
    font-weight: 600;
    color: #94a3b8;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.gp-main-tab:hover {
    color: #505984;
}
.gp-main-tab.active {
    color: #121a3e;
    border-bottom-color: #121a3e;
}
</style>

<div class="gp-header">
    <h2 class="gp-title"><i class="ph-bold ph-briefcase"></i> Gestión de Solicitudes</h2>
    <p class="gp-subtitle">Panel unificado para evaluar las propuestas de las empresas y asignar los equipos de estudiantes.</p>
    
    <div class="gp-main-tabs">
        <a href="?ruta=gestion-proyectos&tab=propuestas" class="gp-main-tab <?= $tab === 'propuestas' ? 'active' : '' ?>">
            <i class="ph-bold ph-folder-open"></i> Evaluación de Propuestas Empresariales
        </a>
        <a href="?ruta=gestion-proyectos&tab=equipos" class="gp-main-tab <?= $tab === 'equipos' ? 'active' : '' ?>">
            <i class="ph-bold ph-users-three"></i> Asignación de Equipo Estudiantil
        </a>
    </div>
</div>

<div class="gp-content" style="padding: 0 2rem 2rem 2rem;">
    <?php
    if ($tab === 'propuestas') {
        // Reducimos el padding de ge-wrapper porque ya tenemos padding en gp-content
        echo '<style>.ge-wrapper { padding: 0 !important; } .ge-header { display: none !important; }</style>';
        require __DIR__ . '/banco_propuestas.php';
    } else {
        echo '<style>.ge-wrapper { padding: 0 !important; } .ge-header { display: none !important; }</style>';
        require __DIR__ . '/gestion_equipos.php';
    }
    ?>
</div>
