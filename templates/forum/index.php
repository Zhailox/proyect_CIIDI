<?php
// templates/forum/index.php
$db = \App\Infrastructure\Database::getConnection();
$categories = [];

if ($db) {
    $categories = $db->query(
        "SELECT fc.*,
                COUNT(DISTINCT ft.id) as topic_count,
                COUNT(DISTINCT fr.id) as reply_count,
                (SELECT ft2.title FROM forum_topics ft2 WHERE ft2.category_id=fc.id ORDER BY ft2.updated_at DESC LIMIT 1) as last_topic,
                (SELECT ft2.updated_at FROM forum_topics ft2 WHERE ft2.category_id=fc.id ORDER BY ft2.updated_at DESC LIMIT 1) as last_activity
         FROM forum_categories fc
         LEFT JOIN forum_topics ft ON ft.category_id = fc.id
         LEFT JOIN forum_replies fr ON fr.topic_id = ft.id
         GROUP BY fc.id ORDER BY fc.order_num"
    )->fetchAll();
}

ob_start(); ?>
<div class="layout wide">
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Foro</div>
      <ul class="sidebar-nav">
        <?php foreach ($categories as $cat): ?>
        <li><a href="<?= APP_URL ?>/forum/category&id=<?= $cat['id'] ?>">
          <span class="icon"><?<i class="bi bi-folder2-open text-primary" style="font-size:1.5rem"></i></span> <?= htmlspecialchars($cat['name']) ?>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum/new" class="btn btn-primary" style="width:100%;justify-content:center;">✏️ Nuevo Tema</a>
    </div>
  </aside>

  <main>
    <div class="section-header">
      <h1 class="section-title">💬 Foro Científico</h1>
      <p class="section-subtitle">Debates, recursos y noticias de la comunidad investigadora UPTTMBI.</p>
    </div>

    <?php foreach ($categories as $cat): ?>
    <div class="forum-category">
      <div class="forum-category-header">
        <div class="forum-cat-icon"><?<i class="bi bi-folder2-open text-primary" style="font-size:1.5rem"></i></div>
        <div>
          <div class="forum-cat-name">
            <a href="<?= APP_URL ?>/forum/category&id=<?= $cat['id'] ?>" style="color:inherit;"><?= htmlspecialchars($cat['name']) ?></a>
          </div>
          <div class="forum-cat-desc"><?= htmlspecialchars($cat['description']) ?></div>
        </div>
        <div style="margin-left:auto;text-align:right;font-size:.78rem;color:var(--text-muted);">
          <div><strong><?= $cat['topic_count'] ?></strong> temas</div>
          <div><strong><?= $cat['reply_count'] ?></strong> respuestas</div>
        </div>
      </div>
      <?php
      // Últimos 3 temas de esta categoría
      if ($db) {
          $topics = $db->prepare(
              "SELECT ft.*, u.name as user_name,
                      (SELECT COUNT(*) FROM forum_replies fr WHERE fr.topic_id=ft.id) as reply_count
               FROM forum_topics ft JOIN users u ON ft.user_id=u.id
               WHERE ft.category_id=? ORDER BY ft.is_pinned DESC, ft.updated_at DESC LIMIT 3"
          );
          $topics->execute([$cat['id']]);
          foreach ($topics->fetchAll() as $t): ?>
      <div class="forum-topic-row">
        <div class="forum-topic-icon"><?= $t['is_pinned'] ? '📌' : '💬' ?></div>
        <div style="flex:1;">
          <a href="<?= APP_URL ?>/forum/topic&id=<?= $t['id'] ?>" class="forum-topic-link">
            <?= htmlspecialchars($t['title']) ?>
            <?php if ($t['is_pinned']): ?><span class="pinned-badge">Fijado</span><?php endif; ?>
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
          <?php endforeach;
      } ?>
      <div style="padding:.6rem 1.3rem;background:var(--bg-page);font-size:.8rem;color:var(--primary);font-weight:600;">
        <a href="<?= APP_URL ?>/forum/category&id=<?= $cat['id'] ?>">Ver todos los temas →</a>
      </div>
    </div>
    <?php endforeach; ?>
  </main>
</div>
<?php
$content = ob_get_clean();
$title = 'Foro Científico';
$activeNav = 'forum';
require __DIR__ . '/../layout.php';
