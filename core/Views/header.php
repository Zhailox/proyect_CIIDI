<?php
// Se obtienen las configuraciones del header recolectadas por el Kernel
$elementosCabecera = $this->getControlesHeader();
?>

<header class="main-header">
  <div class="logo-container">
    <a href="inicio">
      <i class="ph-fill ph-circles-four logo-icon"></i>
      <span class="logo-text">Repositorio</span> <span class="logo-highlight">CIIDI</span>
    </a>
  </div>
  
  <div class="header-actions">
    
    <?php foreach ($elementosCabecera as $control): ?>
        
        <?php if ($control['tipo'] === 'search'): ?>
            <form action="<?php echo $control['accion']; ?>" method="GET" class="search-container">
                <i class="<?php echo $control['icono']; ?> search-icon"></i>
                <input type="text" name="q" class="search-input" placeholder="<?php echo $control['placeholder']; ?>">
            </form>
            
        <?php elseif ($control['tipo'] === 'button'): ?>
            <a href="<?php echo $control['enlace']; ?>" class="<?php echo $control['clase']; ?>">
                <i class="<?php echo $control['icono']; ?>"></i> <?php echo $control['texto']; ?>
            </a>
            
        <?php elseif ($control['tipo'] === 'custom_view'): ?>
            <?php 
                if (file_exists($control['ruta_vista'])) {
                    include $control['ruta_vista'];
                }
            ?>
            
        <?php endif; ?>
        
    <?php endforeach; ?>

  </div>
</header>