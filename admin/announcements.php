<?php
require_once 'auth.php';
require_once '../php/config.php';
$db = getDB();

$msg     = '';
$msgType = 'success';

// Add announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_ann'])) {
    $title = trim($_POST['ann_title'] ?? '');
    $body  = trim($_POST['ann_body']  ?? '');
    if ($title && $body) {
        $stmt = $db->prepare("INSERT INTO announcements (title, body, date) VALUES (?, ?, CURDATE())");
        $stmt->bind_param('ss', $title, $body);
        $stmt->execute();
        $stmt->close();
        $msg = '✅ Announcement published successfully!';
    } else {
        $msg     = '⚠️ Both title and body are required.';
        $msgType = 'danger';
    }
}

// Delete announcement
if (isset($_GET['delete'])) {
    $id   = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM announcements WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $msg = "🗑️ Announcement #$id deleted.";
}

$anns = $db->query("SELECT * FROM announcements ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { margin: 0; }
    .admin-header { background:var(--primary);color:#fff;padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100; }
    .admin-header strong { font-family:'Playfair Display',serif;font-size:1.05rem; }
    .admin-header .right { display:flex;align-items:center;gap:14px;font-size:0.85rem;opacity:0.8; }
    .admin-header .right a { color:#fff;opacity:0.7; }
  </style>
</head>
<body>

<div class="admin-header">
  <strong>🏙️ CityConnect Admin</strong>
  <div class="right">
    <span>👤 <?= e($_SESSION['admin_name'] ?? 'Admin') ?></span>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong>
      <span>Municipal Staff</span>
    </div>
    <ul class="sidebar-nav">
      <li><a href="dashboard.php">📊 Dashboard</a></li>
      <li><a href="complaints.php">📋 Complaints</a></li>
      <li><a href="announcements.php" class="active">📢 Announcements</a></li>
      <li><a href="../index.php" target="_blank">🌐 View Portal</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </aside>

  <main class="admin-content">
    <h1 style="font-family:'Playfair Display',serif;color:var(--primary);font-size:1.6rem;margin-bottom:6px">Announcements</h1>
    <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:24px">Publish and manage official announcements</p>

    <?php if ($msg): ?>
      <div class="alert alert-<?= $msgType ?>"><?= e($msg) ?></div>
    <?php endif; ?>

    <!-- Add Form -->
    <div class="card" style="margin-bottom:28px">
      <div class="card-header-bar" style="background:rgba(26,58,92,0.04)">➕ Publish New Announcement</div>
      <form method="POST" action="announcements.php" style="padding:24px">
        <div class="form-group">
          <label class="form-label">Title <span class="required">*</span></label>
          <input type="text" name="ann_title" class="form-control" placeholder="e.g. Road Closure – Main Boulevard" required>
        </div>
        <div class="form-group">
          <label class="form-label">Body / Details <span class="required">*</span></label>
          <textarea name="ann_body" class="form-control" rows="4" placeholder="Enter full announcement details here…" required></textarea>
        </div>
        <button type="submit" name="add_ann" class="btn btn-accent">📢 Publish Announcement</button>
      </form>
    </div>

    <!-- Announcements List -->
    <div class="card">
      <div class="card-header-bar">📋 Published Announcements (<?= $anns->num_rows ?>)</div>

      <?php if ($anns->num_rows === 0): ?>
        <div style="padding:36px;text-align:center;color:var(--text-muted)">No announcements published yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>#</th><th>Title</th><th>Body</th><th>Date</th><th>Delete</th></tr>
            </thead>
            <tbody>
              <?php while ($a = $anns->fetch_assoc()): ?>
                <tr>
                  <td><?= $a['id'] ?></td>
                  <td><strong><?= e($a['title']) ?></strong></td>
                  <td style="max-width:340px;color:var(--text-muted);font-size:0.85rem">
                    <?= e(mb_strimwidth($a['body'], 0, 120, '…')) ?>
                  </td>
                  <td><?= fdate($a['date']) ?></td>
                  <td>
                    <a href="?delete=<?= $a['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this announcement?')">🗑️ Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</div>

<script src="../js/main.js"></script>
</body>
</html>
