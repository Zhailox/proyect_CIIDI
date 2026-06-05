<?php
// templates/dashboard/index.php
$db = \App\Infrastructure\Database::getConnection();
$stats = ['total'=>0,'active'=>0,'completed'=>0,'experts'=>0,'students'=>0,'collabs'=>0];
$byLine = [];
$topProj = [];
$recent = [];

if ($db) {
    $r = $db->query("SELECT COUNT(*) as t FROM projects WHERE status!='cancelled'")->fetch();
    $stats['total'] = $r['t'];
    $r = $db->query("SELECT COUNT(*) as t FROM projects WHERE status='active'")->fetch();
    $stats['active'] = $r['t'];
    $r = $db->query("SELECT COUNT(*) as t FROM projects WHERE status='completed'")->fetch();
    $stats['completed'] = $r['t'];
    $r = $db->query("SELECT COUNT(*) as t FROM users WHERE role_id IN (2,3)")->fetch();
    $stats['experts'] = $r['t'];
    $r = $db->query("SELECT COUNT(*) as t FROM users WHERE role_id=4")->fetch();
    $stats['students'] = $r['t'];
    $r = $db->query("SELECT COUNT(*) as t FROM project_collaborations WHERE status='accepted'")->fetch();
    $stats['collabs'] = $r['t'];

    $byLine = $db->query(
        "SELECT tl.name, tl.icon, tl.color, COUNT(p.id) as total,
                SUM(CASE WHEN p.status='active' THEN 1 ELSE 0 END) as activos,
                ROUND(AVG(p.impact_score),1) as avg_impact
         FROM taxonomic_lines tl
         LEFT JOIN projects p ON p.taxonomic_line_id = tl.id
         WHERE tl.status='active'
         GROUP BY tl.id ORDER BY total DESC"
    )->fetchAll();

    $topProj = $db->query(
        "SELECT p.title, p.impact_score, tl.name as line_name, tl.icon, u.name as inv_name
         FROM projects p
         JOIN taxonomic_lines tl ON p.taxonomic_line_id=tl.id
         JOIN users u ON p.investigator_id=u.id
         WHERE p.status!='cancelled'
         ORDER BY p.impact_score DESC LIMIT 5"
    )->fetchAll();

    $maxCount = max(array_column($byLine,'total') ?: [1]);
}

ob_start(); ?>
<div class="layout">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Navegación</div>
      <ul class="sidebar-nav">
        <li><a href="<?= APP_URL ?>/research"><span class="icon">🔬</span> Ver Investigaciones</a></li>
        <li><a href="<?= APP_URL ?>/experts"><span class="icon">👨‍🔬</span> Directorio Expertos</a></li>
        <li><a href="<?= APP_URL ?>/postulations"><span class="icon">📋</span> Convocatorias</a></li>
        <li><a href="<?= APP_URL ?>/forum"><span class="icon">💬</span> Foro Científico</a></li>
      </ul>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="section-header">
      <h1 class="section-title">📊 Dashboard de Investigación</h1>
      <p class="section-subtitle">Panorama de la actividad científica de la UPTTMBI.</p>
    </div>

    <!-- MÉTRICAS CLAVE -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">🔬</div>
        <div><div class="stat-number"><?= $stats['total'] ?></div><div class="stat-label">Proyectos Totales</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">✅</div>
        <div><div class="stat-number" style="color:var(--success);"><?= $stats['active'] ?></div><div class="stat-label">En Curso</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#f3e8ff;">🏆</div>
        <div><div class="stat-number" style="color:#7c3aed;"><?= $stats['completed'] ?></div><div class="stat-label">Completados</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">👨‍🔬</div>
        <div><div class="stat-number"><?= $stats['experts'] ?></div><div class="stat-label">Investigadores</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;">🎓</div>
        <div><div class="stat-number" style="color:#ca8a04;"><?= $stats['students'] ?></div><div class="stat-label">Estudiantes</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;">🤝</div>
        <div><div class="stat-number" style="color:var(--danger);"><?= $stats['collabs'] ?></div><div class="stat-label">Colaboraciones</div></div>
      </div>
    </div>

    <!-- GRÁFICO: Proyectos por Línea -->
    <div class="chart-card">
      <div class="chart-title">📊 Proyectos por Línea de Investigación</div>
      <div class="bar-chart">
        <?php foreach ($byLine as $l):
          $pct = $maxCount > 0 ? round($l['total'] / $maxCount * 100) : 0;
        ?>
        <div class="bar-row">
          <div class="bar-label"><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars($l['name']) ?></div>
          <div class="bar-track">
            <div class="bar-fill" style="width:<?= $pct ?>%;background:linear-gradient(90deg,<?= htmlspecialchars($l['color']) ?>,<?= htmlspecialchars($l['color']) ?>88);">
              <?php if ($pct > 15): ?><?= $l['total'] ?> proy.<?php endif; ?>
            </div>
          </div>
          <div class="bar-count"><?= $l['total'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- TOP PROYECTOS + IMPACTO POR LÍNEA -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
      <div class="chart-card">
        <div class="chart-title">🏅 Top Proyectos por Impacto</div>
        <?php foreach ($topProj as $i => $p): ?>
        <div style="display:flex;align-items:center;gap:.7rem;padding:.6rem 0;border-bottom:1px solid var(--border);">
          <div style="width:26px;height:26px;border-radius:50%;background:var(--tag-bg);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;color:var(--primary);"><?= $i+1 ?></div>
          <div style="flex:1;">
            <div style="font-size:.82rem;font-weight:600;"><?= htmlspecialchars(substr($p['title'],0,45)) ?>...</div>
            <div style="font-size:.73rem;color:var(--text-muted);"><?<i class="bi bi-tag-fill text-muted"></i> <?= htmlspecialchars($p['line_name']) ?></div>
          </div>
          <div style="font-size:.85rem;font-weight:700;color:var(--star);">★ <?= $p['impact_score'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="chart-card">
        <div class="chart-title">📈 Impacto Promedio por Línea</div>
        <?php foreach ($byLine as $l):
          if (!$l['avg_impact']) continue;
          $pct = round($l['avg_impact'] / 10 * 100);
        ?>
        <div style="margin-bottom:.8rem;">
          <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.25rem;">
            <span><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars($l['name']) ?></span>
            <strong><?= $l['avg_impact'] ?></strong>
          </div>
          <div style="height:8px;background:var(--bg-page);border-radius:4px;overflow:hidden;">
            <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,<?= htmlspecialchars($l['color']) ?>,<?= htmlspecialchars($l['color']) ?>88);border-radius:4px;"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <!-- RIGHT SIDEBAR -->
  <aside class="right-sidebar sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Distribución de Roles</div>
      <?php if ($db):
        $roleCounts = $db->query("SELECT r.name, COUNT(u.id) as cnt FROM users u JOIN roles r ON u.role_id=r.id GROUP BY r.id")->fetchAll();
        foreach ($roleCounts as $rc):
      ?>
      <div style="display:flex;justify-content:space-between;font-size:.83rem;margin-bottom:.5rem;">
        <span><?= htmlspecialchars($rc['name']) ?></span>
        <strong><?= $rc['cnt'] ?></strong>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </aside>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard';
$activeNav = 'dashboard';
require __DIR__ . '/../layout.php';
