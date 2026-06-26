<?php
require_once 'php/config.php';
$pageTitle  = 'Public Services Directory';
$activePage = 'services';
$db = getDB();

$services = $db->query("SELECT * FROM services ORDER BY name ASC");

// Map names to icons
function serviceIcon(string $name): string {
    $name = strtolower($name);
    if (str_contains($name,'water'))     return '💧';
    if (str_contains($name,'electric'))  return '⚡';
    if (str_contains($name,'hospital'))  return '🏥';
    if (str_contains($name,'bus'))       return '🚌';
    if (str_contains($name,'emergency')) return '🚨';
    if (str_contains($name,'fire'))      return '🔥';
    if (str_contains($name,'police'))    return '👮';
    if (str_contains($name,'park'))      return '🌳';
    if (str_contains($name,'waste'))     return '♻️';
    return '🏢';
}

include 'php/header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="index.php">🏠 Home</a> <span>›</span> <span>Public Services</span>
    </div>
    <h1>🔍 Public Services Directory</h1>
    <p>Find contact details and locations for all key municipal services in the city.</p>
  </div>
</div>

<section class="py-60">
  <div class="container">

    <!-- Search Box -->
    <div class="search-box-wrap reveal">
      <span class="search-icon">🔍</span>
      <input type="search" id="servicesSearch" class="search-input"
             placeholder="Search by service name, address, or phone…"
             aria-label="Search public services">
    </div>

    <?php if ($services->num_rows === 0): ?>
      <div class="alert alert-info reveal" style="max-width:520px;margin:0 auto">
        ℹ️ No services listed yet. Please check back soon.
      </div>
    <?php else:
      $rows = $services->fetch_all(MYSQLI_ASSOC);
    ?>

      <div class="services-grid" id="servicesGrid">
        <?php foreach ($rows as $s): ?>
          <div class="service-card reveal" data-name="<?= e($s['name']) ?>">
            <div class="service-icon-wrap"><?= serviceIcon($s['name']) ?></div>
            <div class="service-name"><?= e($s['name']) ?></div>
            <div class="service-detail">
              <span class="icon">📍</span>
              <span><?= e($s['address']) ?></span>
            </div>
            <div class="service-detail">
              <span class="icon">📞</span>
              <a href="tel:<?= e($s['phone']) ?>" style="color:var(--primary);font-weight:500"><?= e($s['phone']) ?></a>
            </div>
            <div class="service-detail">
              <span class="icon">🕐</span>
              <span><?= e($s['hours']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div id="no-results" class="no-results" style="display:none">
        😕 No services match your search. Try a different keyword.
      </div>

    <?php endif; ?>
  </div>
</section>

<!-- Emergency Banner -->
<div style="background:var(--danger);color:#fff;padding:32px 0;margin-top:20px">
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
    <div>
      <div style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;margin-bottom:4px">🚨 Emergency? Call Now</div>
      <div style="font-size:0.9rem;opacity:0.85">For medical, fire, or security emergencies, contact these numbers immediately.</div>
    </div>
    <div style="display:flex;gap:14px;flex-wrap:wrap">
      <a href="tel:1122" class="btn" style="background:#fff;color:var(--danger)">🚑 Rescue: 1122</a>
      <a href="tel:15"   class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4)">👮 Police: 15</a>
      <a href="tel:1021" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4)">🔥 Fire: 1021</a>
    </div>
  </div>
</div>

<?php include 'php/footer.php'; ?>
