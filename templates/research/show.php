<?php
// templates/research/show.php
$db = \App\Infrastructure\Database::getConnection();
$id = (int)($_GET['id'] ?? 0);
$project = null;
$advances = [];
$team = [];

if ($db && $id) {
    $stmt = $db->prepare("SELECT p.*, tl.name as line_name, tl.icon as line_icon, tl.color as line_color,
                                 u.name as inv_name, u.bio as inv_bio, u.department
                          FROM projects p
                          JOIN taxonomic_lines tl ON p.taxonomic_line_id = tl.id
                          JOIN users u ON p.investigator_id = u.id
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();

    $stmtAdv = $db->prepare("SELECT pa.*, u.name as user_name FROM project_advances pa JOIN users u ON pa.user_id = u.id WHERE pa.project_id = ? ORDER BY pa.advance_date DESC");
    $stmtAdv->execute([$id]);
    $advances = $stmtAdv->fetchAll();

    $stmtTeam = $db->prepare("SELECT pc.*, u.name, u.department, r.name as role_name FROM project_collaborations pc JOIN users u ON pc.user_id = u.id JOIN roles r ON u.role_id = r.id WHERE pc.project_id = ? AND pc.status = 'accepted'");
    $stmtTeam->execute([$id]);
    $team = $stmtTeam->fetchAll();
}

// Manejar postulación
$postMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postulate']) && $db && $project) {
    $msg = trim($_POST['message'] ?? '');
    $role = $_POST['role_in_project'] ?? 'assistant';
    // Simular usuario logueado como ID 7
    try {
        $st = $db->prepare("INSERT IGNORE INTO project_collaborations (project_id, user_id, role_in_project, message) VALUES (?,?,?,?)");
        $st->execute([$id, 7, $role, $msg]);
        $postMsg = 'success';
    } catch (\Exception $e) { $postMsg = 'error'; }
}

function stars(float $s): string {
    $f = floor($s/2); return str_repeat('★',$f).str_repeat('☆',5-$f);
}

ob_start();
if (!$project):
    echo '<div class="layout full"><div class="alert alert-info">Proyecto no encontrado.</div></div>';
else:
?>
<div class="layout wide">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar" style="grid-row:1/3;">
    <div class="sidebar-card">
      <div class="sidebar-title">Sobre el Proyecto</div>
      <div style="font-size:.85rem;color:var(--text-muted);display:flex;flex-direction:column;gap:.7rem;">
        <div><strong>Línea:</strong><br><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars($project['line_name']) ?></div>
        <div><strong>Investigador Principal:</strong><br><?= htmlspecialchars($project['inv_name']) ?></div>
        <div><strong>Departamento:</strong><br><?= htmlspecialchars($project['department']) ?></div>
        <div><strong>Inicio:</strong> <?= $project['start_date'] ?? 'N/D' ?></div>
        <div><strong>Fin previsto:</strong> <?= $project['end_date'] ?? 'N/D' ?></div>
        <div><strong>Impacto:</strong> <span class="stars"><?= stars((float)$project['impact_score']) ?></span> <?= $project['impact_score'] ?></div>
      </div>
    </div>
    <?php if ($team): ?>
    <div class="sidebar-card">
      <div class="sidebar-title">Equipo (<?= count($team) ?>)</div>
      <?php foreach ($team as $m): ?>
      <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.7rem;">
        <div class="avatar" style="width:34px;height:34px;font-size:.75rem;"><?= strtoupper(substr($m['name'],0,2)) ?></div>
        <div>
          <div style="font-size:.82rem;font-weight:600;"><?= htmlspecialchars($m['name']) ?></div>
          <div style="font-size:.72rem;color:var(--text-muted);"><?= $m['role_in_project']==='advisor'?'Asesor':'Asistente' ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/research" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">← Volver</a>
    </div>
  </aside>

  <!-- MAIN -->
  <main>
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Inicio</a><span>/</span>
      <a href="<?= APP_URL ?>/research">Investigaciones</a><span>/</span>
      <span><?= htmlspecialchars(substr($project['title'],0,50)) ?>...</span>
    </div>

    <!-- HERO -->
    <div class="project-hero" style="background:linear-gradient(135deg,<?= htmlspecialchars($project['line_color']) ?> 0%, #1976d2 100%);">
      <div class="project-hero-tag"><?<i class="bi bi-tag-fill"></i> <?= htmlspecialchars($project['line_name']) ?></div>
      <h1><?= htmlspecialchars($project['title']) ?></h1>
      <div class="project-hero-meta">
        <span>👤 <?= htmlspecialchars($project['inv_name']) ?></span>
        <span>🏛️ <?= htmlspecialchars($project['department']) ?></span>
        <span>⭐ <?= $project['impact_score'] ?> de impacto</span>
        <?php $sl=['active'=>'En Curso','completed'=>'Completada','draft'=>'Borrador'][$project['status']]??''; ?>
        <span style="background:rgba(255,255,255,.2);padding:.2rem .7rem;border-radius:20px;"><?= $sl ?></span>
      </div>
    </div>

    <!-- TABS navegación -->
    <div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:1.5rem;">
      <?php foreach (['resumen'=>'📄 Resumen','metodologia'=>'⚗️ Metodología','avances'=>'📈 Avances ('.count($advances).')','postular'=>'✋ Postularme'] as $tab=>$label): ?>
      <a href="#<?= $tab ?>" onclick="showTab('<?= $tab ?>');return false;"
         id="tab-<?= $tab ?>"
         style="padding:.7rem 1.1rem;font-size:.85rem;font-weight:600;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;cursor:pointer;">
         <?= $label ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- RESUMEN -->
    <div id="sec-resumen" class="tab-section">
      <div class="sidebar-card" style="margin-bottom:0;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.8rem;">Resumen / Abstract</h2>
        <p style="font-size:.9rem;color:var(--text-muted);line-height:1.7;"><?= nl2br(htmlspecialchars($project['abstract'])) ?></p>
      </div>
      <?php if ($project['objectives']): ?>
      <div class="sidebar-card" style="margin-top:1rem;margin-bottom:0;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.8rem;">🎯 Objetivos</h2>
        <p style="font-size:.9rem;color:var(--text-muted);line-height:1.7;"><?= nl2br(htmlspecialchars($project['objectives'])) ?></p>
      </div>
      <?php endif; ?>
    </div>

    <!-- METODOLOGÍA -->
    <div id="sec-metodologia" class="tab-section" style="display:none;">
      <div class="sidebar-card" style="margin-bottom:0;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.8rem;">⚗️ Metodología</h2>
        <p style="font-size:.9rem;color:var(--text-muted);line-height:1.7;"><?= nl2br(htmlspecialchars($project['methodology'] ?? 'No especificada.')) ?></p>
      </div>
    </div>

    <!-- AVANCES -->
    <div id="sec-avances" class="tab-section" style="display:none;">
      <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">📈 Línea de Tiempo de Avances</h2>
      <?php if (empty($advances)): ?>
        <p style="color:var(--text-muted);">Sin avances registrados aún.</p>
      <?php else: ?>
      <div class="timeline">
        <?php foreach ($advances as $adv): ?>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-card">
            <div class="timeline-date">📅 <?= htmlspecialchars($adv['advance_date']) ?> &nbsp;·&nbsp; <?= htmlspecialchars($adv['user_name']) ?></div>
            <div class="timeline-title"><?= htmlspecialchars($adv['title']) ?></div>
            <p style="font-size:.85rem;color:var(--text-muted);line-height:1.6;"><?= nl2br(htmlspecialchars($adv['content'])) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- POSTULAR -->
    <div id="sec-postular" class="tab-section" style="display:none;">
      <?php if ($postMsg==='success'): ?>
        <div class="alert alert-success">✅ Tu postulación fue enviada exitosamente. El investigador principal revisará tu solicitud.</div>
      <?php elseif ($postMsg==='error'): ?>
        <div class="alert" style="background:#fee2e2;color:#dc2626;border-left:4px solid #dc2626;">Ya tienes una postulación activa para este proyecto.</div>
      <?php endif; ?>
      <div class="post-card">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">✋ Solicitar Participación</h2>
        <form method="POST" action="<?= APP_URL ?>/research/show&id=<?= $id ?>">
          <input type="hidden" name="postulate" value="1">
          <div class="form-group">
            <label class="form-label">Tipo de participación</label>
            <select name="role_in_project" class="form-control">
              <option value="assistant">Asistente de Investigación (Estudiante)</option>
              <option value="advisor">Asesor / Co-investigador (Docente)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Motivación y experiencia relevante</label>
            <textarea name="message" class="form-control" rows="4" placeholder="Describe por qué quieres colaborar y qué puedes aportar..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Enviar Postulación</button>
        </form>
      </div>
    </div>

  </main>
</div>

<script>
function showTab(tab) {
  document.querySelectorAll('.tab-section').forEach(s => s.style.display='none');
  document.getElementById('sec-'+tab).style.display='block';
  document.querySelectorAll('[id^="tab-"]').forEach(t => {
    t.style.color='var(--text-muted)';
    t.style.borderBottomColor='transparent';
  });
  var active = document.getElementById('tab-'+tab);
  active.style.color='var(--primary)';
  active.style.borderBottomColor='var(--primary)';
}
showTab('resumen');
</script>
<?php
endif;
$content = ob_get_clean();
$title = $project ? htmlspecialchars($project['title']) : 'Proyecto';
$activeNav = 'research';
require __DIR__ . '/../layout.php';
