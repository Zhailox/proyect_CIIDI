<?php
// templates/experts/index.php
$db = \App\Infrastructure\Database::getConnection();
$lineFilter = $_GET['line'] ?? '';
$experts = [];
$lines = [];

if ($db) {
    $lines = $db->query("SELECT * FROM taxonomic_lines WHERE status='active' ORDER BY id")->fetchAll();

    // Traemos investigadores y docentes con sus perfiles y número de proyectos
    $sql = "SELECT u.id, u.name, u.department, u.bio, r.name as role_name,
                   cp.skills,
                   (SELECT p.taxonomic_line_id FROM projects p WHERE p.investigator_id = u.id ORDER BY p.impact_score DESC LIMIT 1) as main_line_id,
                   (SELECT tl2.name FROM projects p2 JOIN taxonomic_lines tl2 ON p2.taxonomic_line_id=tl2.id WHERE p2.investigator_id=u.id ORDER BY p2.impact_score DESC LIMIT 1) as main_line,
                   (SELECT tl3.icon FROM projects p3 JOIN taxonomic_lines tl3 ON p3.taxonomic_line_id=tl3.id WHERE p3.investigator_id=u.id ORDER BY p3.impact_score DESC LIMIT 1) as main_line_icon,
                   (SELECT COUNT(*) FROM projects p4 WHERE p4.investigator_id = u.id AND p4.status != 'cancelled') as proj_count
            FROM users u
            JOIN roles r ON u.role_id = r.id
            LEFT JOIN curriculum_profiles cp ON cp.user_id = u.id
            WHERE u.role_id IN (2,3)";
    $params = [];
    if ($lineFilter) {
        $sql .= " HAVING main_line_id = ?";
        $params[] = $lineFilter;
    }
    $sql .= " ORDER BY proj_count DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $experts = $stmt->fetchAll();
}

ob_start(); ?>
<div class="layout">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Filtrar por Línea</div>
      <ul class="sidebar-nav">
        <li><a href="<?= APP_URL ?>/experts" class="<?= !$lineFilter?'active':'' ?>"><span class="icon">👥</span> Todos</a></li>
        <?php foreach ($lines as $l): ?>
        <li><a href="<?= APP_URL ?>/experts&line=<?= $l['id'] ?>" class="<?= $lineFilter==$l['id']?'active':'' ?>">
          <span class="icon"><?<i class="bi bi-tag"></i></span> <?= htmlspecialchars($l['name']) ?>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="section-header">
      <h1 class="section-title">👨‍🔬 Directorio de Expertos</h1>
      <p class="section-subtitle">Investigadores y docentes especializados por línea de investigación en UPTTMBI.</p>
    </div>

    <div class="experts-grid">
      <?php foreach ($experts as $e):
        $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $e['name']),0,2)));
        $skills = array_slice(array_filter(array_map('trim', explode(',', $e['skills'] ?? ''))), 0, 4);
      ?>
      <div class="expert-card">
        <div class="expert-avatar"><?= $initials ?></div>
        <div class="expert-name"><?= htmlspecialchars($e['name']) ?></div>
        <div class="expert-dept">🏛️ <?= htmlspecialchars($e['department'] ?? '') ?></div>
        <?php if ($e['main_line']): ?>
        <div class="expert-line"><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars($e['main_line']) ?></div>
        <?php endif; ?>
        <div class="expert-bio"><?= htmlspecialchars(substr($e['bio'] ?? '', 0, 120)) ?>...</div>
        <?php if ($skills): ?>
        <div class="skills-list">
          <?php foreach ($skills as $sk): ?>
          <span class="skill-tag"><?= htmlspecialchars($sk) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div style="margin-top:.8rem;font-size:.8rem;color:var(--text-muted);text-align:center;">
          🔬 <?= $e['proj_count'] ?> proyecto<?= $e['proj_count']!=1?'s':'' ?> &nbsp;·&nbsp;
          <span class="badge badge-blue"><?= $e['role_name'] ?></span>
        </div>
        <a href="<?= APP_URL ?>/research&investigator=<?= $e['id'] ?>" class="btn-profile">Ver investigaciones →</a>
      </div>
      <?php endforeach; ?>
      <?php if (empty($experts)): ?>
      <p style="color:var(--text-muted);grid-column:1/-1;text-align:center;padding:2rem;">No se encontraron expertos.</p>
      <?php endif; ?>
    </div>
  </main>

  <!-- RIGHT SIDEBAR -->
  <aside class="right-sidebar sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">¿Eres investigador?</div>
      <p style="font-size:.83rem;color:var(--text-muted);margin-bottom:.8rem;">Completa tu perfil curricular para aparecer en el directorio.</p>
      <a href="<?= APP_URL ?>/curriculum/create" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Mi Perfil</a>
    </div>
  </aside>
</div>
<?php
$content = ob_get_clean();
$title = 'Directorio de Expertos';
$activeNav = 'experts';
require __DIR__ . '/../layout.php';
