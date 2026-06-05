<?php
// templates/layout.php
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Investigación') ?> | UPTTMBI</title>
  <meta name="description" content="Plataforma de Investigación - Universidad Politécnica Territorial del Estado Trujillo Mario Briceño Iragorry">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= ASSET_URL ?>/css/style.css">
</head>
<body>

<!-- ═══════════════════════════════════════
     NAVBAR — Bootstrap 5 nativo horizontal
═══════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg app-navbar sticky-top">
  <div class="container-xl">

    <!-- BRAND -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= APP_URL ?>/">
      <img src="<?= ASSET_URL ?>/img/logo.png" alt="UPTTMBI" height="36"
           onerror="this.replaceWith(document.createElement('span'))"
           class="d-inline-block">
      <div class="lh-1">
        <span class="brand-name">UPTTMBI</span>
        <span class="brand-sub d-block">Investiga</span>
      </div>
    </a>

    <!-- TOGGLER (mobile) -->
    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#mainNav">
      <i class="bi bi-list fs-4"></i>
    </button>

    <!-- COLLAPSIBLE MENU -->
    <div class="collapse navbar-collapse" id="mainNav">

      <!-- SEARCH -->
      <div class="navbar-search-wrap mx-lg-3 my-2 my-lg-0 flex-grow-1">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search text-muted"></i>
          </span>
          <input id="globalSearch" type="text"
                 class="form-control border-start-0 ps-0"
                 placeholder="Buscar investigaciones, expertos...">
        </div>
      </div>

      <!-- NAV LINKS -->
      <ul class="navbar-nav align-items-lg-center gap-lg-1 ms-auto">
        <?php
        $navItems = [
          ['/', 'home', 'bi-house-door', 'Inicio'],
          ['/research', 'research', 'bi-journal-text', 'Investigaciones'],
          ['/experts', 'experts', 'bi-people', 'Expertos'],
          ['/postulations', 'postulations', 'bi-card-list', 'Postulaciones'],
          ['/dashboard', 'dashboard', 'bi-bar-chart-line', 'Dashboard'],
          ['/forum', 'forum', 'bi-chat-square-text', 'Foro'],
        ];
        foreach ($navItems as [$url, $key, $icon, $label]):
          $isActive = $activeNav === $key;
        ?>
        <li class="nav-item">
          <a class="nav-link <?= $isActive ? 'nav-link-active' : '' ?>"
             href="<?= APP_URL ?><?= $url ?>">
            <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
          </a>
        </li>
        <?php endforeach; ?>

        <!-- USER AVATAR (placeholder) -->
        <li class="nav-item ms-lg-2">
          <a class="nav-link p-0" href="#">
            <div class="user-avatar">SA</div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══ CONTENIDO ═══ -->
<?= $content ?? '' ?>

<!-- ═══ FOOTER ═══ -->
<footer class="app-footer">
  <div class="container-xl d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2">
      <img src="<?= ASSET_URL ?>/img/logo.png" alt="" height="28"
           onerror="this.style.display='none'">
      <span>© <?= date('Y') ?> Universidad Politécnica Territorial del Estado Trujillo "Mario Briceño Iragorry"</span>
    </div>
    <div class="footer-links d-flex gap-3">
      <a href="<?= APP_URL ?>/research">Investigaciones</a>
      <a href="<?= APP_URL ?>/forum">Foro</a>
      <a href="<?= APP_URL ?>/dashboard">Dashboard</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ─── Búsqueda global ───────────────────────────────────────
const gs = document.getElementById('globalSearch');
if (gs) {
  gs.addEventListener('keydown', e => {
    if (e.key === 'Enter' && gs.value.trim()) {
      window.location.href = '<?= APP_URL ?>/research&q=' + encodeURIComponent(gs.value.trim());
    }
  });
}

// ─── Tooltips Bootstrap ────────────────────────────────────
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
  new bootstrap.Tooltip(el);
});

// ─── Animación de barras del dashboard ────────────────────
document.querySelectorAll('.bar-fill').forEach(bar => {
  const w = bar.dataset.width || bar.style.width;
  bar.style.width = '0%';
  setTimeout(() => { bar.style.width = w; }, 150);
});
</script>
</body>
</html>
