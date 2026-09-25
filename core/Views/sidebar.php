<?php
// Obtenemos el nivel y rol del usuario actual
require_once CORE_PATH . 'Security/Auth.php';
$usuarioActivo = Auth::check() ? Auth::usuario() : null;
$nivelUsuario  = $usuarioActivo ? (int)$usuarioActivo['nivel'] : 999;
$rolNombre     = $usuarioActivo ? ($usuarioActivo['rol'] ?? '') : '';

// Es SuperAdmin si nivel es 0 O si el nombre del rol contiene "admin" o "super"
$esAdminTotal  = ($nivelUsuario === 0) || (stripos($rolNombre, 'admin') !== false) || (stripos($rolNombre, 'super') !== false);
$menu_dinamico = $menu_dinamico ?? [];
$ruta          = $ruta ?? '';
?>
<aside class="sidebar">
  <h2 class="sidebar-title">Navegación Global</h2>
  <nav class="nav-menu">
    
    <a href="inicio" class="nav-item <?php echo ($ruta == 'inicio') ? 'active' : ''; ?>">
        <span class="nav-icon"><i class="ph-fill ph-house"></i></span> 
        <span class="nav-text">Inicio</span>
    </a>
    
    <?php foreach ($menu_dinamico as $item): ?>
        <?php
        $privilegioExigido = $item['privilegio_minimo'] ?? 999;
        $permisoRbac = $item['permiso_rbac'] ?? null;
        $moduloRbac = $item['modulo_rbac'] ?? null;
        $moduloOrigen = $item['modulo_origen'] ?? '';
        $moduloEstado = $item['modulo_estado'] ?? 'online';

        // Si el usuario no es SuperAdmin/Admin y el módulo está offline, omitirlo por completo
        if (!$esAdminTotal && $moduloEstado === 'offline') {
            continue;
        }

        // Si la opción requiere un permiso específico (como 'auditar'), preguntamos si lo tiene.
        // Si no lo tiene, hacemos 'continue' para que el botón no se dibuje en pantalla.
        if ($permisoRbac && $moduloRbac) {
            if (!Auth::requierePrivilegioMinimo($privilegioExigido, $permisoRbac, $moduloRbac, false)) continue;
        } else {
            if (!$esAdminTotal && $nivelUsuario > $privilegioExigido) continue; 
        }
        
        $icono_clase = $item['icono'];
        $icono_html = strpos($icono_clase, '<i') !== false ? $icono_clase : '<i class="' . $icono_clase . '"></i>';

        $ocultoOffline = ($moduloEstado === 'offline');
        $inlineStyle = $ocultoOffline ? 'display: none;' : '';
        ?>
        
        <?php if ($item['tipo'] === 'link'): ?>
            <a href="<?php echo $item['enlace']; ?>" 
               class="nav-item <?php echo ($ruta == $item['enlace']) ? 'active' : ''; ?>"
               data-modulo="<?php echo htmlspecialchars($moduloOrigen); ?>"
               <?php if ($ocultoOffline): ?>style="display: none;"<?php endif; ?>>
                <span class="nav-icon"><?php echo $icono_html; ?></span> 
                <span class="nav-text"><?php echo $item['titulo']; ?></span>
            </a>
            
        <?php elseif ($item['tipo'] === 'parent'): ?>
        <?php 
            $is_active_parent = in_array($ruta, $item['activadores']); 
            $tieneSubitems = !empty($item['subitems']);
        ?>
        <div class="nav-parent <?php echo $is_active_parent ? 'open' : ''; ?>"
             data-modulo="<?php echo htmlspecialchars($moduloOrigen); ?>"
             <?php if ($ocultoOffline): ?>style="display: none;"<?php endif; ?>>
            <a href="<?php echo $item['enlace']; ?>" 
            class="nav-item nav-parent-link <?php echo $is_active_parent ? 'active' : ''; ?>">
            <span class="nav-icon"><?php echo $icono_html; ?></span> 
            <span class="nav-text" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <span><?php echo $item['titulo']; ?></span>
                <?php if ($tieneSubitems): ?>
                    <i class="ph-bold ph-caret-down nav-caret" 
                        style="font-size: 0.8rem; transition: transform 0.25s ease; cursor: pointer; padding: 4px;"
                        onclick="toggleSidebarParent(event)"></i>
                <?php endif; ?>
            </span>
            </a>
            
            <?php if ($tieneSubitems): ?>
            <div class="sub-menu">
                <?php foreach ($item['subitems'] as $sub): ?>
                    <?php 
                        $subPriv = $sub['privilegio_minimo'] ?? 999;
                        $subPermiso = $sub['permiso_rbac'] ?? null;
                        $subModulo = $sub['modulo_rbac'] ?? null;
                        
                        if ($subPermiso && $subModulo) {
                            if (!Auth::requierePrivilegioMinimo($subPriv, $subPermiso, $subModulo, false)) continue;
                        } else {
                            if (!$esAdminTotal && $nivelUsuario > $subPriv) continue;
                        }
                    ?>
                    <a href="<?php echo $sub['ruta']; ?>" class="sub-nav-item <?php echo ($ruta == $sub['ruta']) ? 'active' : ''; ?>">
                        <span class="nav-text"><?php echo $sub['titulo']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
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
}
</script>