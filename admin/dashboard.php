<?php
require_once 'auth.php';
require_once '../php/config.php';
$db = getDB();

// Stats
$totalComplaints  = $db->query("SELECT COUNT(*) FROM complaints")->fetch_row()[0];
$pendingCount     = $db->query("SELECT COUNT(*) FROM complaints WHERE status='Pending'")->fetch_row()[0];
$progressCount    = $db->query("SELECT COUNT(*) FROM complaints WHERE status='In Progress'")->fetch_row()[0];
$resolvedCount    = $db->query("SELECT COUNT(*) FROM complaints WHERE status='Resolved'")->fetch_row()[0];
$annCount         = $db->query("SELECT COUNT(*) FROM announcements")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | City Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { margin: 0; }
    .admin-header {
      background: var(--primary);
      color: #fff;
      padding: 0 28px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .admin-header strong { font-family: 'Playfair Display', serif; font-size: 1.05rem; }
    .admin-header .right { display: flex; align-items: center; gap: 14px; font-size: 0.85rem; opacity: 0.8; }
    .admin-header .right a { color: #fff; opacity: 0.7; }
    .admin-header .right a:hover { opacity: 1; }
  </style>
</head>
<body>

<div class="admin-header">
  <strong>🏙️ CityConnect Admin</strong>
  <div class="right">
    <span>👤 <?= e($_SESSION['admin_name'] ?? 'Admin') ?></span>
    <a href="../index.php">🌐 View Portal</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-user">
      <strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong>
      <span>Municipal Staff</span>
    </div>
    <ul class="sidebar-nav">
      <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
      <li><a href="complaints.php">📋 Complaints</a></li>
      <li><a href="announcements.php">📢 Announcements</a></li>
      <li><a href="../index.php" target="_blank">🌐 View Portal</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </aside>

  <!-- Main Content -->
  <main class="admin-content">
    <div style="margin-bottom:28px">
      <h1 style="font-family:'Playfair Display',serif;color:var(--primary);font-size:1.7rem">Dashboard</h1>
      <p style="color:var(--text-muted);font-size:0.88rem">Welcome back! Here's an overview of the portal.</p>
    </div>

    <!-- Stats -->
    <div class="admin-stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue">📋</div>
        <div class="stat-info">
          <strong><?= $totalComplaints ?></strong>
          <span>Total Complaints</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon yellow">⏳</div>
        <div class="stat-info">
          <strong><?= $pendingCount ?></strong>
          <span>Pending</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue">🔄</div>
        <div class="stat-info">
          <strong><?= $progressCount ?></strong>
          <span>In Progress</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-info">
          <strong><?= $resolvedCount ?></strong>
          <span>Resolved</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:32px">
      <div class="card" style="padding:24px">
        <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:1.05rem">📋 Manage Complaints</div>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px">View, filter and update status of all submitted complaints.</p>
        <a href="complaints.php" class="btn btn-primary btn-sm">Go to Complaints →</a>
      </div>
      <div class="card" style="padding:24px">
        <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:1.05rem">📢 Manage Announcements</div>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px">Add or delete official announcements visible to the public.</p>
        <a href="announcements.php" class="btn btn-accent btn-sm">Go to Announcements →</a>
      </div>
    </div>

    <!-- Recent Complaints -->
    <div class="card">
      <div class="card-header-bar">📋 Recent Complaints</div>
      <?php
      $recent = $db->query("SELECT * FROM complaints ORDER BY date DESC LIMIT 8");
      if ($recent->num_rows === 0):
      ?>
        <div style="padding:30px;text-align:center;color:var(--text-muted)">No complaints submitted yet.</div>
      <?php else: ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th><th>Name</th><th>Category</th><th>Area</th><th>Date</th><th>Status</th><th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($c = $recent->fetch_assoc()): ?>
                <tr>
                  <td><?= $c['id'] ?></td>
                  <td><?= e($c['name']) ?></td>
                  <td><span class="tag"><?= e($c['category']) ?></span></td>
                  <td><?= e($c['area']) ?></td>
                  <td><?= fdate($c['date']) ?></td>
                  <td>
                    <?php
                    $badge = match($c['status']) {
                      'In Progress' => 'badge-progress',
                      'Resolved'    => 'badge-resolved',
                      default       => 'badge-pending',
                    };
                    ?>
                    <span class="badge <?= $badge ?>"><?= e($c['status']) ?></span>
                  </td>
                  <td>
                    <a href="complaints.php?update=<?= $c['id'] ?>" class="btn btn-sm btn-info">Update</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div style="padding:14px 20px;text-align:right">
          <a href="complaints.php" style="font-size:0.83rem;color:var(--primary);font-weight:600">View all complaints →</a>
        </div>
      <?php endif; ?>
    </div>

  </main>
</div>

<script src="../js/main.js"></script>
</body>
</html>
