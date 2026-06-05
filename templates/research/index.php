<?php
// templates/research/index.php
$db = \App\Infrastructure\Database::getConnection();
$lineFilter = $_GET['line'] ?? '';
$search = $_GET['q'] ?? '';

// Líneas
$lines = [];
if ($db) {
    $lines = $db->query("SELECT * FROM taxonomic_lines WHERE status='active' ORDER BY id")->fetchAll();
}

// Proyectos
$projects = [];
if ($db) {
    $sql = "SELECT p.*, tl.name as line_name, tl.icon as line_icon, tl.color as line_color,
                   u.name as inv_name, u.department
            FROM projects p
            JOIN taxonomic_lines tl ON p.taxonomic_line_id = tl.id
            JOIN users u ON p.investigator_id = u.id
            WHERE p.status != 'cancelled'";
    $params = [];
    if ($lineFilter) {
        $sql .= " AND p.taxonomic_line_id = ?";
        $params[] = $lineFilter;
    }
    if ($search) {
        $sql .= " AND (p.title LIKE ? OR p.abstract LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $sql .= " ORDER BY p.impact_score DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
}

function stars(float $score): string {
    $full = floor($score / 2);
    $half = ($score - $full*2) >= 1 ? 1 : 0;
    return str_repeat('★',$full) . ($half ? '½' : '') . str_repeat('☆', 5-$full-$half);
}

ob_start(); ?>
<div class="layout">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Líneas de Investigación</div>
      <ul class="sidebar-nav">
        <li><a href="<?= APP_URL ?>/research" class="<?= !$lineFilter?'active':'' ?>">
          <span class="icon"><i class="bi bi-grid-fill"></i></span> Todas las Líneas
        </a></li>
        <?php foreach ($lines as $l): ?>
        <li><a href="<?= APP_URL ?>/research&line=<?= $l['id'] ?>" class="<?= $lineFilter==$l['id']?'active':'' ?>">
          <span class="icon"><i class="bi bi-tag"></i></span> <?= htmlspecialchars($l['name']) ?>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">Acciones</div>
      <ul class="sidebar-nav">
        <li><a href="<?= APP_URL ?>/projects/create"><span class="icon"><i class="bi bi-plus-circle"></i></span> Nuevo Proyecto</a></li>
        <li><a href="<?= APP_URL ?>/dashboard"><span class="icon"><i class="bi bi-bar-chart"></i></span> Ver Dashboard</a></li>
        <li><a href="<?= APP_URL ?>/postulations"><span class="icon"><i class="bi bi-card-list"></i></span> Convocatorias</a></li>
      </ul>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="section-header">
      <h1 class="section-title"><i class="bi bi-search"></i> Investigaciones</h1>
      <p class="section-subtitle">Descubre la producción científica con mayor impacto académico en nuestra universidad.</p>
    </div>

    <!-- Filtros por línea (pills) -->
    <div class="line-filters">
      <a href="<?= APP_URL ?>/research" class="line-filter-btn <?= !$lineFilter?'active':'' ?>">Todas las Líneas</a>
      <?php foreach ($lines as $l): ?>
      <a href="<?= APP_URL ?>/research&line=<?= $l['id'] ?>" class="line-filter-btn <?= $lineFilter==$l['id']?'active':'' ?>">
        <?= htmlspecialchars($l['name']) ?>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if ($search): ?>
    <div class="alert alert-info" style="margin-bottom:1rem;">Resultados para: <strong><?= htmlspecialchars($search) ?></strong></div>
    <?php endif; ?>

    <!-- Grid de Proyectos -->
    <div class="research-grid">
      <?php if (empty($projects)): ?>
      <p style="color:var(--text-muted);grid-column:1/-1;padding:2rem;text-align:center;">No se encontraron investigaciones.</p>
      <?php endif; ?>
      <?php foreach ($projects as $p):
        $statusLabel = ['active'=>'En Curso','completed'=>'Completada','draft'=>'Borrador'][$p['status']] ?? $p['status'];
        $statusColor = ['active'=>'badge-active','completed'=>'badge-blue','draft'=>'badge-pending'][$p['status']] ?? 'badge-blue';
      ?>
      <div class="research-card">
        <div class="research-card-img" style="background:url('https://picsum.photos/seed/<?= $p['id'] ?>/600/400') center/cover no-repeat;">
        </div>
        <div class="research-card-body">
          <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.6rem;">
            <span class="research-card-tag"><i class="bi bi-tag-fill"></i> <?= htmlspecialchars($p['line_name']) ?></span>
            <span class="badge <?= $statusColor ?>"><?= $statusLabel ?></span>
          </div>
          <h3 class="research-card-title"><?= htmlspecialchars($p['title']) ?></h3>
          <p class="research-card-author">
            <span class="avatar-sm" style="background:linear-gradient(135deg,var(--primary),var(--accent-light));display:inline-flex;align-items:center;justify-content:center;color:white;font-size:.6rem;font-weight:700;">
              <?= strtoupper(substr($p['inv_name'],0,2)) ?>
            </span>
            <?= htmlspecialchars($p['inv_name']) ?>
          </p>
          <div class="research-card-footer">
            <div>
              <span class="stars"><?= stars((float)$p['impact_score']) ?></span>
              <span class="impact-score"> <?= $p['impact_score'] ?> Impacto</span>
            </div>
            <a href="<?= APP_URL ?>/research/show&id=<?= $p['id'] ?>" class="btn-explore">Explorar ›</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- RIGHT SIDEBAR -->
  <aside class="right-sidebar sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Estadísticas</div>
      <?php if ($db):
        $totals = $db->query("SELECT COUNT(*) as t FROM projects WHERE status!='cancelled'")->fetch();
        $active = $db->query("SELECT COUNT(*) as t FROM projects WHERE status='active'")->fetch();
        $experts = $db->query("SELECT COUNT(*) as t FROM users WHERE role_id IN (2,3)")->fetch();
      ?>
      <div style="display:flex;flex-direction:column;gap:.6rem;">
        <div style="display:flex;justify-content:space-between;font-size:.85rem;">
          <span><i class="bi bi-journal-text"></i> Proyectos Totales</span><strong><?= $totals['t'] ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.85rem;">
          <span><i class="bi bi-check-circle-fill text-success"></i> En Curso</span><strong style="color:var(--success)"><?= $active['t'] ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.85rem;">
          <span><i class="bi bi-people-fill"></i> Investigadores</span><strong><?= $experts['t'] ?></strong>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">¿Quieres colaborar?</div>
      <p style="font-size:.83rem;color:var(--text-muted);margin-bottom:.8rem;">Postúlate como asistente o asesor en proyectos activos.</p>
      <a href="<?= APP_URL ?>/postulations" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Ver Convocatorias</a>
    </div>
  </aside>
</div>
<?php
$content = ob_get_clean();
$title = 'Investigaciones';
$activeNav = 'research';
require __DIR__ . '/../layout.php';
