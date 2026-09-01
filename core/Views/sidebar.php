<?php
// Obtenemos el nivel del usuario actual (si es visitante, su nivel es -1)
require_once CORE_PATH . 'Security/Auth.php';
$nivelUsuario = Auth::check() ? Auth::usuario()['nivel'] : -1;
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
        // 1. Verificamos el nivel exigido por este botón (si no dice nada, asumimos 0 = Estudiante)
        $privilegioExigido = $item['privilegio_minimo'] ?? 0;

        // 2. Si el usuario tiene menos nivel del exigido, SALTAMOS al siguiente botón (lo ocultamos)
        if ($nivelUsuario < $privilegioExigido) {
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
                        $subPrivilegio = $sub['privilegio_minimo'] ?? 0;
                        if ($nivelUsuario < $subPrivilegio) {
                            continue;
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