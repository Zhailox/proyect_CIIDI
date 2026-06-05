<?php
// templates/home/index.php
$db = \App\Infrastructure\Database::getConnection();
$featured = [];
$lines = [];
$stats = ['total'=>0,'active'=>0,'experts'=>0];

if ($db) {
    $featured = $db->query(
        "SELECT p.id, p.title, p.impact_score, tl.name as line_name, tl.icon, tl.color,
                u.name as inv_name
         FROM projects p
         JOIN taxonomic_lines tl ON p.taxonomic_line_id=tl.id
         JOIN users u ON p.investigator_id=u.id
         WHERE p.status='active' ORDER BY p.impact_score DESC LIMIT 4"
    )->fetchAll();

    $lines = $db->query("SELECT * FROM taxonomic_lines WHERE status='active' ORDER BY id")->fetchAll();
    $stats['total'] = $db->query("SELECT COUNT(*) FROM projects WHERE status!='cancelled'")->fetchColumn();
    $stats['active'] = $db->query("SELECT COUNT(*) FROM projects WHERE status='active'")->fetchColumn();
    $stats['experts'] = $db->query("SELECT COUNT(*) FROM users WHERE role_id IN (2,3)")->fetchColumn();
}

function stars(float $s): string { $f=floor($s/2); return str_repeat('★',$f).str_repeat('☆',5-$f); }

ob_start(); ?>

<!-- HERO BANNER -->
<div style="background:linear-gradient(135deg,#0d47a1 0%,#1565c0 40%,#1e88e5 100%);color:white;padding:3rem 1.5rem;">
  <div style="max-width:1280px;margin:0 auto;display:grid;grid-template-columns:1fr auto;gap:2rem;align-items:center;">
    <div>
      <div style="font-size:.8rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.8;margin-bottom:.5rem;">
        Universidad Politécnica Territorial del Estado Trujillo
      </div>
      <h1 style="font-size:2.2rem;font-weight:800;margin-bottom:.8rem;line-height:1.2;">
        "Mario Briceño Iragorry"<br>
        <span style="font-weight:400;font-size:1.4rem;">Plataforma de Investigación Científica</span>
      </h1>
      <p style="font-size:1rem;opacity:.9;margin-bottom:1.5rem;max-width:520px;">
        Descubre proyectos, conecta con investigadores y colabora en el avance del conocimiento para el estado Trujillo y Venezuela.
      </p>
      <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
        <a href="<?= APP_URL ?>/research" class="btn" style="background:white;color:var(--primary);"><i class="bi bi-search"></i> Ver Investigaciones</a>
        <a href="<?= APP_URL ?>/postulations" class="btn" style="background:rgba(255,255,255,.15);color:white;border:1.5px solid rgba(255,255,255,.4);"><i class="bi bi-file-earmark-text"></i> Convocatorias Abiertas</a>
      </div>
    </div>
    <div style="display:flex;gap:1.5rem;flex-direction:column;align-items:flex-end;">
      <?php foreach ([['bi-journal-check',$stats['total'],'Proyectos'],['bi-check-circle-fill',$stats['active'],'Activos'],['bi-people-fill',$stats['experts'],'Expertos']] as [$ic,$val,$lbl]): ?>
      <div style="text-align:center;background:rgba(255,255,255,.12);padding:.8rem 1.2rem;border-radius:12px;min-width:90px;">
        <div style="font-size:1.5rem;"><i class="bi <?= $ic ?>"></i></div>
        <div style="font-size:1.8rem;font-weight:800;"><?= $val ?></div>
        <div style="font-size:.75rem;opacity:.85;"><?= $lbl ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="layout" style="margin-top:0;padding-top:1.5rem;">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Líneas de Investigación</div>
      <ul class="sidebar-nav">
        <?php foreach ($lines as $l): ?>
        <li><a href="<?= APP_URL ?>/research&line=<?= $l['id'] ?>">
          <span class="icon"><i class="bi bi-tags"></i></span> <?= htmlspecialchars($l['name']) ?>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">Acceso Rápido</div>
      <ul class="sidebar-nav">
        <li><a href="<?= APP_URL ?>/dashboard"><span class="icon"><i class="bi bi-bar-chart"></i></span> Dashboard</a></li>
        <li><a href="<?= APP_URL ?>/experts"><span class="icon"><i class="bi bi-person-badge"></i></span> Directorio Expertos</a></li>
        <li><a href="<?= APP_URL ?>/forum"><span class="icon"><i class="bi bi-chat-left-text"></i></span> Foro Científico</a></li>
        <li><a href="<?= APP_URL ?>/curriculum/create"><span class="icon"><i class="bi bi-file-person"></i></span> Mi Currículo</a></li>
      </ul>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <div class="section-header">
      <h2 class="section-title" style="font-size:1.4rem;"><i class="bi bi-star"></i> Investigaciones Destacadas</h2>
      <p class="section-subtitle">Las investigaciones con mayor impacto académico en nuestra institución.</p>
    </div>

    <!-- Filtros pill -->
    <div class="line-filters">
      <a href="<?= APP_URL ?>/research" class="line-filter-btn active">Todas las Líneas</a>
      <?php foreach ($lines as $l): ?>
      <a href="<?= APP_URL ?>/research&line=<?= $l['id'] ?>" class="line-filter-btn"><?= htmlspecialchars($l['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="research-grid">
      <?php foreach ($featured as $p): ?>
      <div class="research-card">
        <div class="research-card-img" style="background:url('https://picsum.photos/seed/<?= $p['id'] ?>/600/400') center/cover no-repeat;">
        </div>
        <div class="research-card-body">
          <span class="research-card-tag"><i class="bi bi-tag-fill"></i> <?= htmlspecialchars($p['line_name']) ?></span>
          <h3 class="research-card-title"><?= htmlspecialchars($p['title']) ?></h3>
          <div class="research-card-author">
            <div class="avatar-sm" style="background:linear-gradient(135deg,var(--primary),var(--accent-light));display:inline-flex;align-items:center;justify-content:center;color:white;font-size:.55rem;font-weight:700;">
              <?= strtoupper(substr($p['inv_name'],0,2)) ?>
            </div>
            <?= htmlspecialchars($p['inv_name']) ?>
          </div>
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

    <div style="text-align:center;margin-top:1.5rem;">
      <a href="<?= APP_URL ?>/research" class="btn btn-primary">Ver todas las investigaciones →</a>
    </div>
  </main>

  <!-- RIGHT SIDEBAR -->
  <aside class="right-sidebar sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Foro: Últimos temas</div>
      <?php if ($db):
        $forumTopics = $db->query("SELECT ft.id, ft.title, u.name FROM forum_topics ft JOIN users u ON ft.user_id=u.id ORDER BY ft.updated_at DESC LIMIT 4")->fetchAll();
        foreach ($forumTopics as $ft): ?>
      <div style="margin-bottom:.8rem;padding-bottom:.8rem;border-bottom:1px solid var(--border);">
        <a href="<?= APP_URL ?>/forum/topic&id=<?= $ft['id'] ?>" style="font-size:.82rem;font-weight:600;color:var(--text-main);display:block;margin-bottom:.2rem;line-height:1.4;">
          <?= htmlspecialchars(substr($ft['title'],0,60)) ?>...
        </a>
        <span style="font-size:.73rem;color:var(--text-muted);"><i class="bi bi-person"></i> <?= htmlspecialchars($ft['name']) ?></span>
      </div>
      <?php endforeach; endif; ?>
      <a href="<?= APP_URL ?>/forum" style="font-size:.82rem;color:var(--primary);font-weight:600;">Ver foro completo →</a>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">Convocatorias Activas</div>
      <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:.8rem;"><?= $stats['active'] ?> proyectos buscan colaboradores.</p>
      <a href="<?= APP_URL ?>/postulations" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Ver Convocatorias</a>
    </div>
  </aside>
</div>

<?php
$content = ob_get_clean();
$title = 'Inicio';
$activeNav = 'home';
require __DIR__ . '/../layout.php';
