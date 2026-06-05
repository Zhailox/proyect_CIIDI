<?php
// templates/forum/topic.php
$db = \App\Infrastructure\Database::getConnection();
$topicId = (int)($_GET['id'] ?? 0);
$topic = null;
$replies = [];

if ($db && $topicId) {
    // Incrementar vistas
    $db->prepare("UPDATE forum_topics SET views = views + 1 WHERE id = ?")->execute([$topicId]);

    $stmt = $db->prepare(
        "SELECT ft.*, fc.name as cat_name, fc.id as cat_id, fc.icon as cat_icon,
                u.name as user_name, r.name as user_role
         FROM forum_topics ft
         JOIN forum_categories fc ON ft.category_id = fc.id
         JOIN users u ON ft.user_id = u.id
         JOIN roles r ON u.role_id = r.id
         WHERE ft.id = ?"
    );
    $stmt->execute([$topicId]);
    $topic = $stmt->fetch();

    $stmt2 = $db->prepare(
        "SELECT fr.*, u.name as user_name, r.name as user_role
         FROM forum_replies fr
         JOIN users u ON fr.user_id = u.id
         JOIN roles r ON u.role_id = r.id
         WHERE fr.topic_id = ? ORDER BY fr.created_at ASC"
    );
    $stmt2->execute([$topicId]);
    $replies = $stmt2->fetchAll();
}

// Procesar nueva respuesta
$replyMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_content']) && $db && $topic) {
    $content_r = trim($_POST['reply_content'] ?? '');
    if ($content_r) {
        try {
            $db->prepare("INSERT INTO forum_replies (topic_id, user_id, content) VALUES (?,?,?)")
               ->execute([$topicId, 7, $content_r]);
            $db->prepare("UPDATE forum_topics SET updated_at=NOW() WHERE id=?")->execute([$topicId]);
            $replyMsg = 'success';
            // Recargar respuestas
            $stmt2->execute([$topicId]);
            $replies = $stmt2->fetchAll();
        } catch (\Exception $e) { $replyMsg = 'error'; }
    }
}

function initials(string $name): string {
    $parts = explode(' ', $name);
    return strtoupper(implode('', array_map(fn($p)=>$p[0]??'', array_slice($parts,0,2))));
}

ob_start();
if (!$topic):
    echo '<div class="layout full"><div class="alert alert-info">Tema no encontrado.</div></div>';
else: ?>
<div class="layout wide">
  <aside class="sidebar">
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum/category&id=<?= $topic['cat_id'] ?>" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">← Volver</a>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">Info del Tema</div>
      <div style="font-size:.83rem;color:var(--text-muted);display:flex;flex-direction:column;gap:.5rem;">
        <div>💬 <?= count($replies) ?> respuestas</div>
        <div>👁️ <?= $topic['views'] ?> vistas</div>
        <div>📅 <?= date('d M Y', strtotime($topic['created_at'])) ?></div>
        <div>👤 <?= htmlspecialchars($topic['user_name']) ?></div>
      </div>
    </div>
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum/new" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">✏️ Nuevo Tema</a>
    </div>
  </aside>

  <main>
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Inicio</a><span>/</span>
      <a href="<?= APP_URL ?>/forum">Foro</a><span>/</span>
      <a href="<?= APP_URL ?>/forum/category&id=<?= $topic['cat_id'] ?>"><?<i class="bi bi-folder2"></i> <?= htmlspecialchars($topic['cat_name']) ?></a><span>/</span>
      <span><?= htmlspecialchars(substr($topic['title'],0,40)) ?>...</span>
    </div>

    <!-- POST PRINCIPAL -->
    <div class="post-card" style="border-left:3px solid var(--primary);">
      <div class="post-header">
        <div class="avatar"><?= initials($topic['user_name']) ?></div>
        <div>
          <div class="post-author-name"><?= htmlspecialchars($topic['user_name']) ?></div>
          <div class="post-author-role"><span class="badge badge-blue"><?= htmlspecialchars($topic['user_role']) ?></span></div>
        </div>
        <div class="post-time">📅 <?= date('d M Y, H:i', strtotime($topic['created_at'])) ?></div>
      </div>
      <h1 style="font-size:1.2rem;font-weight:800;margin-bottom:1rem;color:var(--text-main);">
        <?= $topic['is_pinned'] ? '📌 ' : '' ?><?= htmlspecialchars($topic['title']) ?>
      </h1>
      <div class="post-body"><?= nl2br(htmlspecialchars($topic['content'])) ?></div>
    </div>

    <!-- RESPUESTAS -->
    <?php if ($replyMsg==='success'): ?>
    <div class="alert alert-success">✅ Tu respuesta fue publicada.</div>
    <?php endif; ?>

    <?php foreach ($replies as $i => $rep): ?>
    <div class="post-card">
      <div class="post-header">
        <div class="avatar" style="background:linear-gradient(135deg,#0277bd,#64b5f6);"><?= initials($rep['user_name']) ?></div>
        <div>
          <div class="post-author-name"><?= htmlspecialchars($rep['user_name']) ?></div>
          <div class="post-author-role"><span class="badge badge-blue"><?= htmlspecialchars($rep['user_role']) ?></span></div>
        </div>
        <div class="post-time">#<?= $i+1 ?> · <?= date('d M Y, H:i', strtotime($rep['created_at'])) ?></div>
      </div>
      <div class="post-body"><?= nl2br(htmlspecialchars($rep['content'])) ?></div>
    </div>
    <?php endforeach; ?>

    <!-- FORMULARIO DE RESPUESTA -->
    <?php if (!$topic['is_closed']): ?>
    <div class="reply-form">
      <h3 style="font-weight:700;margin-bottom:1rem;font-size:.95rem;">💬 Escribe tu respuesta</h3>
      <form method="POST" action="<?= APP_URL ?>/forum/topic&id=<?= $topicId ?>">
        <div class="form-group">
          <textarea name="reply_content" class="form-control" rows="4" placeholder="Comparte tu conocimiento, pregunta o punto de vista..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publicar Respuesta</button>
      </form>
    </div>
    <?php else: ?>
    <div class="alert alert-info">Este tema está cerrado y no acepta nuevas respuestas.</div>
    <?php endif; ?>
  </main>
</div>
<?php
endif;
$content = ob_get_clean();
$title = $topic ? htmlspecialchars($topic['title']) : 'Tema';
$activeNav = 'forum';
require __DIR__ . '/../layout.php';
