<?php
$serviceFile = defined('MODULES_PATH') 
    ? MODULES_PATH . 'SuperAdmin/services/SystemConfigService.php' 
    : __DIR__ . '/../../modules/SuperAdmin/services/SystemConfigService.php';

if (!class_exists('SystemConfigService') && file_exists($serviceFile)) {
    require_once $serviceFile;
}

if (class_exists('SystemConfigService')) {
    $footerData = SystemConfigService::get('footer', SystemConfigService::getDefaultFooter());
} else {
    $footerData = [
        "columnas" => [],
        "bottom_izquierda" => "© 2026 UPTTMBI. Todos los derechos reservados.",
        "bottom_derecha"   => "Desarrollado por PNF Informática"
    ];
}

$columnasFooter = $footerData['columnas'] ?? [];
$bottomIzq      = $footerData['bottom_izquierda'] ?? '© 2026 UPTTMBI. Todos los derechos reservados.';
$bottomDer      = $footerData['bottom_derecha'] ?? 'Desarrollado por PNF Informática';
?>
<footer class="main-footer">
  <div class="footer-container">
    <?php foreach ($columnasFooter as $col): ?>
      <div class="footer-column">
        <?php if (!empty($col['titulo'])): ?>
          <h3 class="footer-title">
            <?php if (!empty($col['icono'])): ?>
              <i class="<?= htmlspecialchars($col['icono']) ?>"></i>
            <?php endif; ?>
            <?= htmlspecialchars($col['titulo']) ?>
          </h3>
        <?php endif; ?>

        <?php if (!empty($col['bloques_texto']) && is_array($col['bloques_texto'])): ?>
          <?php foreach ($col['bloques_texto'] as $bloque): ?>
            <p class="footer-text">
              <?= nl2br(strip_tags($bloque, '<strong><b><em><i><br><span>')) ?>
            </p>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($col['enlaces']) && is_array($col['enlaces'])): ?>
          <ul class="footer-links">
            <?php foreach ($col['enlaces'] as $enlace): ?>
              <li>
                <a href="<?= htmlspecialchars($enlace['url'] ?? '#') ?>" target="<?= str_starts_with($enlace['url'] ?? '', 'http') ? '_blank' : '_self' ?>" rel="noopener noreferrer">
                  <?php if (!empty($enlace['icono'])): ?>
                    <i class="<?= htmlspecialchars($enlace['icono']) ?>"></i>
                  <?php endif; ?>
                  <?= htmlspecialchars($enlace['texto'] ?? '') ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <?php if (!empty($col['lema'])): ?>
          <p class="footer-text footer-lema">
            <?= htmlspecialchars($col['lema']) ?>
          </p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="footer-bottom">
    <p><?= htmlspecialchars($bottomIzq) ?></p>
    <p><?= htmlspecialchars($bottomDer) ?></p>
  </div>
</footer>