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

        // Si es SuperAdmin/Admin Total jamás se oculta ningún menú.
        if (!$esAdminTotal && $nivelUsuario > $privilegioExigido) {
            continue; 
        }
        ?>
        
        <?php 
            // Inyectar clase Phosphor de forma segura (Cero Emojis)
            $icono_clase = $item['icono'];
            $icono_html = strpos($icono_clase, '<i') !== false ? $icono_clase : '<i class="' . $icono_clase . '"></i>';
        ?>
        
        <?php if ($item['tipo'] === 'link'): // Si es un botón simple directo ?>
            
            <a href="<?php echo $item['enlace']; ?>" class="nav-item <?php echo ($ruta == $item['enlace']) ? 'active' : ''; ?>">
                <span class="nav-icon"><?php echo $icono_html; ?></span> 
                <span class="nav-text"><?php echo $item['titulo']; ?></span>
            </a>
            
        <?php elseif ($item['tipo'] === 'parent'): // Si es un grupo con submenús ?>
            
            <?php 
                $is_active_parent = in_array($ruta, $item['activadores']); 
            ?>
            
            <div class="nav-parent">
                <a href="<?php echo $item['enlace']; ?>" class="nav-item <?php echo $is_active_parent ? 'active' : ''; ?>">
                  <span class="nav-icon"><?php echo $icono_html; ?></span> 
                  <span class="nav-text"><?php echo $item['titulo']; ?></span>
                </a>
                
                <?php if ($is_active_parent && !empty($item['subitems'])): ?>
                <div class="sub-menu">
                    <?php foreach ($item['subitems'] as $sub): ?>
                        <?php 
                            $subPriv = $sub['privilegio_minimo'] ?? 999;
                            if (!$esAdminTotal && $nivelUsuario > $subPriv) continue;
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