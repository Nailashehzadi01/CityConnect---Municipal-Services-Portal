<?php
require_once 'auth.php';
require_once '../php/config.php';
$db = getDB();

$msg = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['complaint_id'];
    $status = $_POST['status'] ?? '';
    $allowed = ['Pending', 'In Progress', 'Resolved'];
    if (in_array($status, $allowed, true)) {
        $stmt = $db->prepare("UPDATE complaints SET status=? WHERE id=?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $stmt->close();
        $msg = "✅ Complaint #$id status updated to '$status'.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id   = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM complaints WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $msg = "🗑️ Complaint #$id deleted.";
}

// Filter
$filter = $_GET['filter'] ?? 'all';
$where  = match($filter) {
    'pending'    => "WHERE status='Pending'",
    'progress'   => "WHERE status='In Progress'",
    'resolved'   => "WHERE status='Resolved'",
    default      => '',
};

$complaints = $db->query("SELECT * FROM complaints $where ORDER BY date DESC");

// Update modal target
$updateTarget = null;
if (isset($_GET['update'])) {
    $uid  = (int)$_GET['update'];
    $stmt = $db->prepare("SELECT * FROM complaints WHERE id=?");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $updateTarget = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complaints | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { margin: 0; }
    .admin-header { background:var(--primary);color:#fff;padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100; }
    .admin-header strong { font-family:'Playfair Display',serif;font-size:1.05rem; }
    .admin-header .right { display:flex;align-items:center;gap:14px;font-size:0.85rem;opacity:0.8; }
    .admin-header .right a { color:#fff;opacity:0.7; }
    /* Modal */
    .modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:999;align-items:center;justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#fff;border-radius:var(--radius);max-width:480px;width:90%;padding:32px;box-shadow:0 20px 60px rgba(0,0,0,0.3); }
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
      <li><a href="complaints.php" class="active">📋 Complaints</a></li>
      <li><a href="announcements.php">📢 Announcements</a></li>
      <li><a href="../index.php" target="_blank">🌐 View Portal</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </aside>

  <main class="admin-content">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
      <div>
        <h1 style="font-family:'Playfair Display',serif;color:var(--primary);font-size:1.6rem">Complaints</h1>
        <p style="color:var(--text-muted);font-size:0.85rem">Manage and update citizen complaints</p>
      </div>
      <!-- Filter Tabs -->
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <?php foreach(['all'=>'All','pending'=>'⏳ Pending','progress'=>'🔄 In Progress','resolved'=>'✅ Resolved'] as $key=>$label): ?>
          <a href="?filter=<?= $key ?>" class="btn btn-sm <?= $filter===$key ? 'btn-primary' : 'btn-outline' ?>">
            <?= $label ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>

    <div class="card">
      <?php if ($complaints->num_rows === 0): ?>
        <div style="padding:40px;text-align:center;color:var(--text-muted)">
          No complaints found for this filter.
        </div>
      <?php else: ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Resident</th>
                <th>Contact</th>
                <th>Category</th>
                <th>Area</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($c = $complaints->fetch_assoc()): ?>
                <tr>
                  <td><strong><?= $c['id'] ?></strong></td>
                  <td><?= e($c['name']) ?></td>
                  <td><a href="tel:<?= e($c['contact']) ?>" style="color:var(--primary)"><?= e($c['contact']) ?></a></td>
                  <td><span class="tag"><?= e($c['category']) ?></span></td>
                  <td><?= e($c['area']) ?></td>
                  <td><?= fdate($c['date']) ?></td>
                  <td>
                    <?php $badge = match($c['status']){'In Progress'=>'badge-progress','Resolved'=>'badge-resolved',default=>'badge-pending'}; ?>
                    <span class="badge <?= $badge ?>"><?= e($c['status']) ?></span>
                  </td>
                  <td style="display:flex;gap:6px">
                    <a href="?update=<?= $c['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-info">✏️</a>
                    <a href="?delete=<?= $c['id'] ?>&filter=<?= $filter ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete complaint #<?= $c['id'] ?>?')">🗑️</a>
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

<!-- Update Status Modal -->
<?php if ($updateTarget): ?>
<div class="modal-overlay open" id="updateModal">
  <div class="modal-box">
    <h3 style="font-family:'Playfair Display',serif;color:var(--primary);margin-bottom:6px">Update Complaint #<?= $updateTarget['id'] ?></h3>
    <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:20px">
      <strong><?= e($updateTarget['name']) ?></strong> — <?= e($updateTarget['category']) ?> — <?= e($updateTarget['area']) ?>
    </p>
    <p style="font-size:0.87rem;color:var(--text);margin-bottom:20px;background:var(--bg);padding:12px;border-radius:8px">
      <?= nl2br(e($updateTarget['description'])) ?>
    </p>
    <form method="POST" action="complaints.php?filter=<?= $filter ?>">
      <input type="hidden" name="complaint_id" value="<?= $updateTarget['id'] ?>">
      <div class="form-group">
        <label class="form-label">New Status</label>
        <select name="status" class="form-control">
          <?php foreach(['Pending','In Progress','Resolved'] as $s): ?>
            <option value="<?= $s ?>" <?= $updateTarget['status']===$s?'selected':'' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" name="update_status" class="btn btn-primary">💾 Save</button>
        <a href="complaints.php?filter=<?= $filter ?>" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script src="../js/main.js"></script>
</body>
</html>
