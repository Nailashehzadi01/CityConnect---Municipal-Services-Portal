<?php
$pageTitle  = $pageTitle  ?? 'City Community Services Portal';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | City Municipal Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <meta name="description" content="Official City Community Services Portal - Report issues, find services, and stay informed.">
</head>
<body>

<!-- ╔══════════════════════════════════════╗ -->
<!--   HEADER                               -->
<!-- ╚══════════════════════════════════════╝ -->
<header>
  <div class="header-top">
    <div class="container">
      <span>🏛️ City Municipal Office &nbsp;|&nbsp; Official Government Portal</span>
      <span>📞 Helpline: 1122 &nbsp;|&nbsp; Emergency: 1122</span>
    </div>
  </div>
  <nav>
    <div class="container">
      <div class="nav-inner">
        <a href="index.php" class="logo">
          <div class="logo-icon">🏙️</div>
          <div class="logo-text">
            <strong>CityConnect</strong>
            <span>Municipal Services Portal</span>
          </div>
        </a>
        <ul class="nav-links" role="navigation">
          <li><a href="index.php"        class="<?= $activePage==='home'         ? 'active' : '' ?>">🏠 Home</a></li>
          <li><a href="announcements.php" class="<?= $activePage==='announcements' ? 'active' : '' ?>">📢 Announcements</a></li>
          <li><a href="complaint.php"     class="<?= $activePage==='complaint'     ? 'active' : '' ?>">📝 Report Issue</a></li>
          <li><a href="services.php"      class="<?= $activePage==='services'      ? 'active' : '' ?>">🔍 Services</a></li>
          <li><a href="admin/login.php"   class="nav-cta <?= $activePage==='admin' ? 'active' : '' ?>">🔐 Admin</a></li>
        </ul>
        <button class="hamburger" aria-label="Toggle navigation" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </nav>
</header>
