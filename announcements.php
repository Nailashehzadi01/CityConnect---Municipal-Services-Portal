<?php
require_once 'php/config.php';
$pageTitle  = 'Announcements & Notices';
$activePage = 'announcements';
$db = getDB();

// Fetch all announcements ordered by date
$anns = $db->query("SELECT * FROM announcements ORDER BY date DESC");

include 'php/header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="index.php">🏠 Home</a> <span>›</span> <span>Announcements</span>
    </div>
    <h1>📢 Announcements &amp; Notices</h1>
    <p>Official updates from the City Municipal Office — maintenance, events, alerts &amp; more.</p>
  </div>
</div>

<section class="py-60">
  <div class="container" style="max-width:820px">

    <?php if ($anns->num_rows === 0): ?>
      <div class="alert alert-info reveal">
        ℹ️ No announcements have been published yet. Please check back later.
      </div>
    <?php else: ?>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px" class="reveal">
        <p style="color:var(--text-muted);font-size:0.88rem">
          <?= $anns->num_rows ?> announcement(s) found
        </p>
        <span class="tag">📅 Latest First</span>
      </div>

      <?php while ($ann = $anns->fetch_assoc()): ?>
        <div class="announcement-card reveal">
          <div class="ann-date">📅 <?= fdate($ann['date']) ?></div>
          <h2 class="ann-title"><?= e($ann['title']) ?></h2>
          <div class="ann-body"><?= nl2br(e($ann['body'])) ?></div>
        </div>
      <?php endwhile; ?>

    <?php endif; ?>

  </div>
</section>

<?php include 'php/footer.php'; ?>
