<?php
// Obtenemos el nivel y rol del usuario actual
require_once CORE_PATH . 'Security/Auth.php';
$usuarioActivo = Auth::check() ? Auth::usuario() : null;
$nivelUsuario  = $usuarioActivo ? (int)$usuarioActivo['nivel'] : 999;
$rolNombre     = $usuarioActivo ? ($usuarioActivo['rol'] ?? '') : '';

// Es SuperAdmin si nivel es 0 O si el nombre del rol contiene "admin" o "super"
$esAdminTotal  = ($nivelUsuario === 0) || (stripos($rolNombre, 'admin') !== false) || (stripos($rolNombre, 'super') !== false);
$menu_dinamico = $menu_dinamico ?? [];
if (!empty($menu_dinamico)) {
    usort($menu_dinamico, function($a, $b) {
        $pesoA = $a['orden'] ?? 100;
        $pesoB = $b['orden'] ?? 100;
        return $pesoA <=> $pesoB;
    });
}
$ruta          = $ruta ?? '';

// ¿El enlace es visible según los guardianes de rutas del core? (p. ej. Seguridad del macro módulo)
$sidebarRutaVisible = function(?string $rutaEnlace): bool {
    if ($rutaEnlace === null || $rutaEnlace === '' || !class_exists('RouteGuardMiddleware')) {
        return true;
    }
    return RouteGuardMiddleware::rutaVisible($rutaEnlace);
};

// ¿El usuario puede ver este elemento de menú? (módulo offline + RBAC / nivel + guardianes de rutas + roles)
$sidebarPuedeVer = function(array $item) use ($esAdminTotal, $nivelUsuario, $rolNombre, $sidebarRutaVisible, &$sidebarPuedeVer): bool {
    // El módulo SuperAdmin se renderiza exclusivamente en el menú desplegable del perfil superior
    if (($item['modulo_origen'] ?? '') === 'SuperAdmin') {
        return false;
    }

    $privilegioExigido = $item['privilegio_minimo'] ?? 999;
    $permisoRbac = $item['permiso_rbac'] ?? null;
    $moduloRbac = $item['modulo_rbac'] ?? null;
    $moduloEstado = $item['modulo_estado'] ?? 'online';
    $rolesPermitidos = $item['roles_permitidos'] ?? null;

    // Si el usuario no es SuperAdmin/Admin y el módulo está offline, omitirlo por completo
    if (!$esAdminTotal && $moduloEstado === 'offline') {
        return false;
    }

    // Filtrar por rol estricto si está definido (ej. 'roles_permitidos' => ['Profesor'])
    if (!$esAdminTotal && is_array($rolesPermitidos) && !in_array($rolNombre, $rolesPermitidos, true)) {
        return false;
    }

    // Si la opción requiere un permiso específico (como 'auditar'), preguntamos si lo tiene.
    // Si no lo tiene, no se dibuja el botón en pantalla.
    if ($permisoRbac && $moduloRbac) {
        if (!Auth::requierePrivilegioMinimo($privilegioExigido, $permisoRbac, $moduloRbac, false)) return false;
    } elseif (!$esAdminTotal && $nivelUsuario > $privilegioExigido) {
        return false;
    }

    // Submódulo (subitem): visible si su ruta lo es
    if (isset($item['ruta'])) {
        return $sidebarRutaVisible($item['ruta']);
    }
    // Módulo con submódulos: visible si al menos un submódulo es visible (o, si ninguno, su enlace principal)
    if (!empty($item['subitems'])) {
        foreach ($item['subitems'] as $sub) {
            if ($sidebarPuedeVer($sub)) return true;
        }
    }
    return $sidebarRutaVisible($item['enlace'] ?? null);
};

$sidebarIcono = function(string $icono_clase): string {
    return strpos($icono_clase, '<i') !== false ? $icono_clase : '<i class="' . $icono_clase . '"></i>';
};

// Dibuja un módulo (link o parent con submódulos). $anidado = true cuando vive dentro de un macro módulo.
$sidebarRenderItem = function(array $item, bool $anidado = false) use ($ruta, $sidebarPuedeVer, $sidebarIcono) {
    $moduloOrigen = $item['modulo_origen'] ?? '';
    $ocultoOffline = (($item['modulo_estado'] ?? 'online') === 'offline');
    $icono_html = $sidebarIcono($item['icono'] ?? 'ph ph-app-window');
    $titulo = $anidado ? ($item['titulo_en_grupo'] ?? $item['titulo']) : $item['titulo'];
    $claseAnidado = $anidado ? ' nav-item--nested' : '';
    ?>
    <?php if ($item['tipo'] === 'link'): ?>
        <a href="<?php echo $item['enlace']; ?>" 
           class="nav-item<?php echo $claseAnidado; ?> <?php echo ($ruta == $item['enlace']) ? 'active' : ''; ?>"
           data-modulo="<?php echo htmlspecialchars($moduloOrigen); ?>"
           <?php if ($ocultoOffline): ?>style="display: none;"<?php endif; ?>>
            <span class="nav-icon"><?php echo $icono_html; ?></span> 
            <span class="nav-text"><?php echo $titulo; ?></span>
        </a>
        
    <?php elseif ($item['tipo'] === 'parent'): ?>
    <?php 
        $is_active_parent = in_array($ruta, $item['activadores'] ?? []); 
        $subitemsVisibles = array_filter($item['subitems'] ?? [], $sidebarPuedeVer);
        $tieneSubitems = !empty($subitemsVisibles);
    ?>
    <div class="nav-parent<?php echo $anidado ? ' nav-parent--nested' : ''; ?> <?php echo $is_active_parent ? 'open' : ''; ?>"
         data-modulo="<?php echo htmlspecialchars($moduloOrigen); ?>"
         <?php if ($ocultoOffline): ?>style="display: none;"<?php endif; ?>>
        <a href="<?php echo $item['enlace']; ?>" 
        class="nav-item nav-parent-link<?php echo $claseAnidado; ?> <?php echo $is_active_parent ? 'active' : ''; ?>">
        <span class="nav-icon"><?php echo $icono_html; ?></span> 
        <span class="nav-text" style="display: flex; justify-content: space-between; align-items: center; flex: 1; min-width: 0;">
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $titulo; ?></span>
            <?php if ($tieneSubitems): ?>
                <i class="ph-bold ph-caret-down nav-caret" 
                    style="font-size: 0.8rem; transition: transform 0.25s ease; cursor: pointer; padding: 4px; flex-shrink: 0; margin-left: 0.5rem;"
                    onclick="toggleSidebarParent(event)"></i>
            <?php endif; ?>
        </span>
        </a>
        
        <?php if ($tieneSubitems): ?>
        <div class="sub-menu">
            <?php foreach ($subitemsVisibles as $sub): ?>
                <a href="<?php echo $sub['ruta']; ?>" class="sub-nav-item <?php echo ($ruta == $sub['ruta']) ? 'active' : ''; ?>">
                    <span class="nav-text"><?php echo $sub['titulo']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php
};
?>
<aside class="sidebar">
  <h2 class="sidebar-title">Navegación Global</h2>
  <nav class="nav-menu">
    
    <a href="inicio" class="nav-item <?php echo ($ruta == 'inicio') ? 'active' : ''; ?>">
        <span class="nav-icon"><i class="ph-fill ph-house"></i></span> 
        <span class="nav-text">Inicio</span>
    </a>
    
    <?php foreach ($menu_dinamico as $item): ?>
        <?php if (($item['tipo'] ?? '') === 'grupo'): ?>
            <?php
            // Macro Módulo: solo se dibuja si el usuario puede ver al menos uno de sus módulos
            $hijosVisibles = array_filter($item['hijos'] ?? [], $sidebarPuedeVer);
            if (empty($hijosVisibles)) continue;

            $is_active_grupo = in_array($ruta, $item['activadores'] ?? []);
            $todosOffline = true;
            foreach ($hijosVisibles as $hijo) {
                if (($hijo['modulo_estado'] ?? 'online') !== 'offline') { $todosOffline = false; break; }
            }
            ?>
            <div class="nav-parent nav-grupo <?php echo $is_active_grupo ? 'open' : ''; ?>"
                 data-grupo="<?php echo htmlspecialchars($item['id_grupo'] ?? ''); ?>"
                 <?php if ($todosOffline): ?>style="display: none;"<?php endif; ?>>
                <a href="#" role="button" aria-expanded="<?php echo $is_active_grupo ? 'true' : 'false'; ?>"
                   class="nav-item nav-parent-link nav-grupo-link <?php echo $is_active_grupo ? 'active' : ''; ?>"
                   onclick="toggleSidebarParent(event)">
                    <span class="nav-icon"><?php echo $sidebarIcono($item['icono']); ?></span>
                    <span class="nav-text" style="display: flex; justify-content: space-between; align-items: center; flex: 1; min-width: 0;">
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $item['titulo']; ?></span>
                        <i class="ph-bold ph-caret-down nav-caret" 
                           style="font-size: 0.8rem; transition: transform 0.25s ease; cursor: pointer; padding: 4px; flex-shrink: 0; margin-left: 0.5rem;"></i>
                    </span>
                </a>
                <div class="sub-menu nav-grupo-menu">
                    <?php foreach ($hijosVisibles as $hijo): ?>
                        <?php $sidebarRenderItem($hijo, true); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <?php if (!$sidebarPuedeVer($item)) continue; ?>
            <?php $sidebarRenderItem($item); ?>
        <?php endif; ?>
        
    <?php endforeach; ?>

  </nav>
</aside>

<script>
function toggleSidebarParent(ev) {
    // Evitamos que el click se propague al enlace padre
    ev.preventDefault();
    ev.stopPropagation();

    // Encontramos el contenedor .nav-parent más cercano al elemento clickeado
    const parentContainer = ev.target.closest('.nav-parent');
    if (!parentContainer) return;

    // Alternamos la clase 'open'
    parentContainer.classList.toggle('open');

    const link = parentContainer.querySelector(':scope > .nav-grupo-link');
    if (link) link.setAttribute('aria-expanded', parentContainer.classList.contains('open') ? 'true' : 'false');
}

// Macro Módulos: se ocultan si todos sus módulos hijos quedan ocultos
// (p. ej. cuando el SuperAdmin pone módulos offline en tiempo real) y reaparecen al reactivarlos.
(function () {
    const grupos = document.querySelectorAll('.sidebar .nav-grupo');
    if (!grupos.length) return;

    const sincronizarGrupo = (grupo) => {
        const hijos = grupo.querySelectorAll(':scope > .nav-grupo-menu > [data-modulo]');
        const algunoVisible = Array.from(hijos).some(h => h.style.display !== 'none');
        if (algunoVisible && grupo.style.display === 'none') {
            grupo.style.display = '';
        } else if (!algunoVisible && grupo.style.display !== 'none') {
            grupo.style.display = 'none';
        }
    };

    grupos.forEach(grupo => {
        const menu = grupo.querySelector(':scope > .nav-grupo-menu');
        if (!menu) return;
        new MutationObserver(() => sincronizarGrupo(grupo))
            .observe(menu, { attributes: true, attributeFilter: ['style'], subtree: true });
    });
})();
</script>