<?php
require_once 'php/config.php';
$pageTitle  = 'Welcome';
$activePage = 'home';

// Fetch latest 5 announcements for ticker
$db   = getDB();
$res  = $db->query("SELECT title FROM announcements ORDER BY date DESC LIMIT 5");
$tickers = [];
while ($r = $res->fetch_assoc()) $tickers[] = $r['title'];
if (empty($tickers)) $tickers = ['Welcome to the City Community Services Portal!', 'Report civic issues online 24/7', 'Stay updated with official announcements'];

include 'php/header.php';
?>

<!-- ╔══════════ TICKER ══════════╗ -->
<div class="ticker-bar">
  <div class="container">
    <div class="ticker-inner">
      <div class="ticker-label">📢 NOTICE</div>
      <div class="ticker-content">
        <div class="ticker-scroll">
          <?php
          $items = array_merge($tickers, $tickers); // duplicate for seamless loop
          foreach ($items as $t): ?>
            <span>🔔 <?= e($t) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ╔══════════ HERO ══════════╗ -->
<section class="home-hero">
  <div class="container">
    <div class="hero-content">
      <div class="hero-eyebrow">
        <span>🏙️</span> Official Municipal Portal
      </div>
      <h1 class="hero-title">
        Your City.<br>
        <span>Connected.</span>
      </h1>
      <p class="hero-desc">
        Report civic issues, access public services, and stay informed with official announcements — all in one place, anytime.
      </p>
      <div class="hero-actions">
        <a href="complaint.php" class="btn btn-accent">📝 Report an Issue</a>
        <a href="services.php"  class="btn btn-outline" style="border-color:rgba(255,255,255,0.4);color:#fff">🔍 Find Services</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat">
          <strong>24/7</strong>
          <span>Online Access</span>
        </div>
        <div class="hero-stat">
          <strong>5+</strong>
          <span>Service Types</span>
        </div>
        <div class="hero-stat">
          <strong>Fast</strong>
          <span>Response Time</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ╔══════════ QUICK LINKS ══════════╗ -->
<section class="quick-links-section">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Navigate</div>
      <h2 class="section-title">What do you need today?</h2>
      <div class="section-divider"></div>
    </div>
    <div class="quick-links-grid">
      <a href="announcements.php" class="quick-link-card reveal">
        <div class="ql-icon">📢</div>
        <div class="ql-label">Announcements</div>
        <div class="ql-desc">Official notices &amp; updates from the municipal office</div>
      </a>
      <a href="complaint.php" class="quick-link-card reveal">
        <div class="ql-icon">📝</div>
        <div class="ql-label">Report an Issue</div>
        <div class="ql-desc">Submit a civic complaint — roads, water, lighting &amp; more</div>
      </a>
      <a href="services.php" class="quick-link-card reveal">
        <div class="ql-icon">🔍</div>
        <div class="ql-label">Public Services</div>
        <div class="ql-desc">Find offices, hospitals, utilities &amp; emergency contacts</div>
      </a>
      <a href="admin/login.php" class="quick-link-card reveal">
        <div class="ql-icon">🔐</div>
        <div class="ql-label">Admin Panel</div>
        <div class="ql-desc">Staff login to manage complaints &amp; announcements</div>
      </a>
    </div>
  </div>
</section>

<!-- ╔══════════ LATEST ANNOUNCEMENTS ══════════╗ -->
<section style="background:#fff;padding:70px 0;">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Latest</div>
      <h2 class="section-title">Recent Announcements</h2>
      <p class="section-sub">Official notices from the Municipal Office</p>
      <div class="section-divider"></div>
    </div>

    <?php
    $anns = $db->query("SELECT * FROM announcements ORDER BY date DESC LIMIT 4");
    if ($anns->num_rows === 0):
    ?>
      <div class="alert alert-info reveal" style="max-width:520px;margin:0 auto">
        ℹ️ No announcements yet. Check back soon.
      </div>
    <?php else: while ($ann = $anns->fetch_assoc()): ?>
      <div class="announcement-card reveal">
        <div class="ann-date">📅 <?= fdate($ann['date']) ?></div>
        <div class="ann-title"><?= e($ann['title']) ?></div>
        <div class="ann-body"><?= nl2br(e($ann['body'])) ?></div>
      </div>
    <?php endwhile; endif; ?>

    <div class="text-center mt-30 reveal">
      <a href="announcements.php" class="btn btn-outline">View All Announcements →</a>
    </div>
  </div>
</section>

<!-- ╔══════════ HOW IT WORKS ══════════╗ -->
<section class="py-80" style="background:var(--bg)">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Process</div>
      <h2 class="section-title">How to Report an Issue</h2>
      <div class="section-divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:28px">
      <?php
      $steps = [
        ['📝','Fill the Form','Go to Report an Issue and fill in your name, contact, and details.'],
        ['📋','Select Category','Choose from Road, Water, Lighting, Sanitation, or Other.'],
        ['📤','Submit','Hit submit — your complaint is saved instantly to our system.'],
        ['✅','Resolution','Our staff reviews and updates the status: Pending → In Progress → Resolved.'],
      ];
      foreach ($steps as $i => [$icon,$title,$desc]):
      ?>
        <div class="card reveal" style="text-align:center;padding:28px 22px">
          <div style="width:50px;height:50px;background:var(--accent);color:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 16px;font-weight:700">
            <?= $icon ?>
          </div>
          <div style="font-weight:700;font-size:0.95rem;margin-bottom:8px;color:var(--primary)"><?= $title ?></div>
          <div style="font-size:0.83rem;color:var(--text-muted);line-height:1.6"><?= $desc ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'php/footer.php'; ?>
