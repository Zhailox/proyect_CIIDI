<?php
// templates/forum/category.php
$db = \App\Infrastructure\Database::getConnection();
$catId = (int)($_GET['id'] ?? 0);
$category = null;
$topics = [];

if ($db && $catId) {
    $stmt = $db->prepare("SELECT * FROM forum_categories WHERE id=?");
    $stmt->execute([$catId]);
    $category = $stmt->fetch();

    $stmt2 = $db->prepare(
        "SELECT ft.*, u.name as user_name,
                (SELECT COUNT(*) FROM forum_replies fr WHERE fr.topic_id=ft.id) as reply_count
         FROM forum_topics ft
         JOIN users u ON ft.user_id=u.id
         WHERE ft.category_id=?
         ORDER BY ft.is_pinned DESC, ft.updated_at DESC"
    );
    $stmt2->execute([$catId]);
    $topics = $stmt2->fetchAll();
}

ob_start();
if (!$category):
    echo '<div class="layout full"><div class="alert alert-info">Categoría no encontrada.</div></div>';
else: ?>
<div class="layout wide">
  <aside class="sidebar">
    <?php if ($db):
      $cats = $db->query("SELECT * FROM forum_categories ORDER BY order_num")->fetchAll();
    ?>
    <div class="sidebar-card">
      <div class="sidebar-title">Categorías</div>
      <ul class="sidebar-nav">
        <?php foreach ($cats as $c): ?>
        <li><a href="<?= APP_URL ?>/forum/category&id=<?= $c['id'] ?>" class="<?= $c['id']==$catId?'active':'' ?>">
          <span class="icon"><?<i class="bi bi-folder2"></i></span> <?= htmlspecialchars($c['name']) ?>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum/new&category=<?= $catId ?>" class="btn btn-primary" style="width:100%;justify-content:center;">✏️ Nuevo Tema</a>
    </div>
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">← Volver al Foro</a>
    </div>
  </aside>

  <main>
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Inicio</a><span>/</span>
      <a href="<?= APP_URL ?>/forum">Foro</a><span>/</span>
      <span><?= htmlspecialchars($category['name']) ?></span>
    </div>
    <div class="section-header">
      <h1 class="section-title"><?<i class="bi bi-folder2-open"></i> <?= htmlspecialchars($category['name']) ?></h1>
      <p class="section-subtitle"><?= htmlspecialchars($category['description']) ?></p>
    </div>

    <div class="forum-category">
      <?php foreach ($topics as $t): ?>
      <div class="forum-topic-row">
        <div class="forum-topic-icon"><?= $t['is_pinned'] ? '📌' : '💬' ?></div>
        <div style="flex:1;">
          <a href="<?= APP_URL ?>/forum/topic&id=<?= $t['id'] ?>" class="forum-topic-link">
            <?= htmlspecialchars($t['title']) ?>
            <?php if ($t['is_pinned']): ?><span class="pinned-badge">Fijado</span><?php endif; ?>
            <?php if ($t['is_closed']): ?><span class="badge badge-pending" style="font-size:.7rem;">Cerrado</span><?php endif; ?>
          </a>
          <div class="forum-topic-meta">
            <span>👤 <?= htmlspecialchars($t['user_name']) ?></span>
            <span>📅 <?= date('d M Y', strtotime($t['created_at'])) ?></span>
          </div>
        </div>
        <div class="forum-topic-stats">
          <span>👁️ <?= $t['views'] ?></span>
          <span>💬 <?= $t['reply_count'] ?></span>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($topics)): ?>
      <div style="padding:2rem;text-align:center;color:var(--text-muted);">No hay temas en esta categoría. ¡Sé el primero!</div>
      <?php endif; ?>
    </div>
  </main>
</div>
<?php
endif;
$content = ob_get_clean();
$title = $category ? $category['name'] : 'Foro';
$activeNav = 'forum';
require __DIR__ . '/../layout.php';
