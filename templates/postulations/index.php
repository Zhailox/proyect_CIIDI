<?php
// templates/postulations/index.php
$db = \App\Infrastructure\Database::getConnection();
$projects = [];
$myPostulations = [];

if ($db) {
    // Proyectos activos con postulaciones abiertas
    $projects = $db->query(
        "SELECT p.id, p.title, p.status, tl.name as line_name, tl.icon as line_icon,
                u.name as inv_name,
                (SELECT COUNT(*) FROM project_collaborations pc WHERE pc.project_id = p.id AND pc.status='accepted') as team_count
         FROM projects p
         JOIN taxonomic_lines tl ON p.taxonomic_line_id = tl.id
         JOIN users u ON p.investigator_id = u.id
         WHERE p.status = 'active'
         ORDER BY p.impact_score DESC"
    )->fetchAll();

    // Simulamos postulaciones del usuario actual (ID=7)
    $myPostulations = $db->query(
        "SELECT pc.*, p.title as project_title, tl.icon as line_icon
         FROM project_collaborations pc
         JOIN projects p ON pc.project_id = p.id
         JOIN taxonomic_lines tl ON p.taxonomic_line_id = tl.id
         WHERE pc.user_id = 7
         ORDER BY pc.created_at DESC"
    )->fetchAll();
}

ob_start(); ?>
<div class="layout wide">
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Mis Postulaciones</div>
      <?php if (empty($myPostulations)): ?>
        <p style="font-size:.82rem;color:var(--text-muted);">Aún no has postulado a ningún proyecto.</p>
      <?php else: ?>
        <?php foreach ($myPostulations as $mp): ?>
        <div style="margin-bottom:.8rem;padding-bottom:.8rem;border-bottom:1px solid var(--border);">
          <div style="font-size:.82rem;font-weight:600;"><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars(substr($mp['project_title'],0,40)) ?>...</div>
          <div style="margin-top:.2rem;display:flex;gap:.4rem;align-items:center;">
            <?php $badge=['accepted'=>'badge-active','pending'=>'badge-pending','rejected'=>'badge-rejected'][$mp['status']]??'badge-blue'; ?>
            <span class="badge <?= $badge ?>">
              <?= ['accepted'=>'✅ Aceptada','pending'=>'⏳ Pendiente','rejected'=>'❌ Rechazada'][$mp['status']] ?>
            </span>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">¿Tienes un proyecto?</div>
      <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:.8rem;">Crea tu investigación y recibe colaboradores.</p>
      <a href="<?= APP_URL ?>/projects/create" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">+ Nuevo Proyecto</a>
    </div>
  </aside>

  <main>
    <div class="section-header">
      <h1 class="section-title">📋 Convocatorias Abiertas</h1>
      <p class="section-subtitle">Postúlate como asistente de investigación o asesor en proyectos activos.</p>
    </div>

    <?php foreach ($projects as $p): ?>
    <div class="call-card">
      <div class="call-icon"><?<i class="bi bi-tag-fill text-primary" style="font-size:1.5rem"></i></div>
      <div style="flex:1;">
        <div class="call-title"><?= htmlspecialchars($p['title']) ?></div>
        <div class="call-meta">
          <span>👤 <?= htmlspecialchars($p['inv_name']) ?></span>
          <span>🔬 <?= htmlspecialchars($p['line_name']) ?></span>
          <span>👥 <?= $p['team_count'] ?> colaboradores</span>
          <span class="badge badge-active">Activa</span>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:.4rem;align-items:flex-end;">
        <a href="<?= APP_URL ?>/research/show&id=<?= $p['id'] ?>#postular" class="btn btn-primary btn-sm">Postularme</a>
        <a href="<?= APP_URL ?>/research/show&id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Ver detalles</a>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($projects)): ?>
    <div class="alert alert-info">No hay convocatorias abiertas en este momento.</div>
    <?php endif; ?>
  </main>
</div>
<?php
$content = ob_get_clean();
$title = 'Postulaciones';
$activeNav = 'postulations';
require __DIR__ . '/../layout.php';
