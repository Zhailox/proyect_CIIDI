<?php
// templates/forum/new.php
$db = \App\Infrastructure\Database::getConnection();
$categories = [];
$defaultCat = (int)($_GET['category'] ?? 0);
if ($db) {
    $categories = $db->query("SELECT * FROM forum_categories ORDER BY order_num")->fetchAll();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db) {
    $catId = (int)($_POST['category_id'] ?? 0);
    $ttitle = trim($_POST['title'] ?? '');
    $tcontent = trim($_POST['content'] ?? '');
    if ($catId && $ttitle && $tcontent) {
        try {
            $st = $db->prepare("INSERT INTO forum_topics (category_id, user_id, title, content) VALUES (?,?,?,?)");
            $st->execute([$catId, 7, $ttitle, $tcontent]);
            $newId = $db->lastInsertId();
            header("Location: " . APP_URL . "/forum/topic&id=$newId");
            exit;
        } catch (\Exception $e) { $msg = 'error'; }
    } else { $msg = 'validation'; }
}

ob_start(); ?>
<div class="layout wide">
  <aside class="sidebar">
    <div class="sidebar-card">
      <a href="<?= APP_URL ?>/forum" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">← Volver al Foro</a>
    </div>
    <div class="sidebar-card">
      <div class="sidebar-title">Categorías</div>
      <ul class="sidebar-nav">
        <?php foreach ($categories as $c): ?>
        <li><a href="<?= APP_URL ?>/forum/category&id=<?= $c['id'] ?>"><span class="icon"><?<i class="bi bi-folder2"></i></span> <?= htmlspecialchars($c['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </aside>

  <main>
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Inicio</a><span>/</span>
      <a href="<?= APP_URL ?>/forum">Foro</a><span>/</span>
      <span>Nuevo Tema</span>
    </div>
    <div class="section-header">
      <h1 class="section-title">✏️ Crear Nuevo Tema</h1>
      <p class="section-subtitle">Inicia una discusión con la comunidad científica de la UPTTMBI.</p>
    </div>

    <?php if ($msg==='validation'): ?>
    <div class="alert" style="background:#fee2e2;color:#dc2626;border-left:4px solid #dc2626;">Por favor completa todos los campos.</div>
    <?php elseif ($msg==='error'): ?>
    <div class="alert" style="background:#fee2e2;color:#dc2626;border-left:4px solid #dc2626;">Error al publicar. Intenta de nuevo.</div>
    <?php endif; ?>

    <div class="post-card">
      <form method="POST" action="<?= APP_URL ?>/forum/new">
        <div class="form-group">
          <label class="form-label">Categoría</label>
          <select name="category_id" class="form-control" required>
            <option value="">Selecciona una categoría...</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$defaultCat?'selected':'' ?>>
              <?<i class="bi bi-folder2"></i> <?= htmlspecialchars($c['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Título del tema</label>
          <input type="text" name="title" class="form-control" required placeholder="Escribe un título claro y descriptivo...">
        </div>
        <div class="form-group">
          <label class="form-label">Contenido</label>
          <textarea name="content" class="form-control" rows="8" required placeholder="Desarrolla tu pregunta, idea o información..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">🚀 Publicar Tema</button>
      </form>
    </div>
  </main>
</div>
<?php
$content = ob_get_clean();
$title = 'Nuevo Tema';
$activeNav = 'forum';
require __DIR__ . '/../layout.php';
