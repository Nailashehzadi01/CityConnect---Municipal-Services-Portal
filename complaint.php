<?php
require_once 'php/config.php';
$pageTitle  = 'Report an Issue';
$activePage = 'complaint';

$success = false;
$error   = '';
$old     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();

    // Sanitize & validate inputs
    $name     = trim($_POST['resName']   ?? '');
    $contact  = trim($_POST['contact']   ?? '');
    $category = trim($_POST['category']  ?? '');
    $desc     = trim($_POST['description'] ?? '');
    $area     = trim($_POST['area']      ?? '');

    $old = ['name'=>$name,'contact'=>$contact,'category'=>$category,'desc'=>$desc,'area'=>$area];

    if (!$name || !$contact || !$category || !$desc || !$area) {
        $error = 'Please fill in all required fields.';
    } elseif (!preg_match('/^\d{10,15}$/', $contact)) {
        $error = 'Contact number must be 10–15 digits.';
    } else {
        $stmt = $db->prepare(
            "INSERT INTO complaints (name, contact, category, description, area, status, date)
             VALUES (?, ?, ?, ?, ?, 'Pending', CURDATE())"
        );
        $stmt->bind_param('sssss', $name, $contact, $category, $desc, $area);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Database error. Please try again later.';
        }
        $stmt->close();
    }
}

include 'php/header.php';
?>

<div class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="index.php">🏠 Home</a> <span>›</span> <span>Report an Issue</span>
    </div>
    <h1>📝 Report a Civic Issue</h1>
    <p>Submit your complaint and our team will address it as soon as possible. No login required.</p>
  </div>
</div>

<section class="py-60">
  <div class="container complaint-form-wrap">

    <?php if ($success): ?>
      <div class="alert alert-success reveal" style="font-size:1rem;padding:20px 24px">
        ✅ <strong>Complaint submitted successfully!</strong> Our team will review it shortly. You may close this page or submit another.
      </div>
      <div class="text-center reveal mt-20">
        <a href="index.php" class="btn btn-primary">← Back to Home</a>
        <a href="complaint.php" class="btn btn-outline" style="margin-left:12px">Submit Another</a>
      </div>

    <?php else: ?>

      <?php if ($error): ?>
        <div class="alert alert-danger reveal">⚠️ <?= e($error) ?></div>
      <?php endif; ?>

      <div class="form-card reveal">
        <div class="form-card-header">
          <div class="icon">📝</div>
          <div>
            <h2>Citizen Complaint Form</h2>
            <p>All fields marked with <span style="color:var(--accent-light)">*</span> are required</p>
          </div>
        </div>

        <form class="form-card-body" id="complaintForm" method="POST" action="complaint.php" novalidate>

          <!-- Personal Info -->
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="resName">Full Name <span class="required">*</span></label>
              <input type="text" id="resName" name="resName" class="form-control"
                     placeholder="e.g. Muhammad Ali" value="<?= e($old['name'] ?? '') ?>" autocomplete="name">
              <span class="form-error">Please enter your full name.</span>
            </div>
            <div class="form-group">
              <label class="form-label" for="contact">Contact Number <span class="required">*</span></label>
              <input type="tel" id="contact" name="contact" class="form-control"
                     placeholder="e.g. 03001234567" maxlength="15" value="<?= e($old['contact'] ?? '') ?>">
              <span class="form-error">Enter a valid 10–15 digit number.</span>
            </div>
          </div>

          <!-- Category Selection -->
          <div class="form-group">
            <label class="form-label">Complaint Category <span class="required">*</span></label>
            <div class="category-grid">
              <?php
              $categories = [
                'Road Damage'    => '🛣️',
                'Water Supply'   => '💧',
                'Street Lighting'=> '💡',
                'Sanitation'     => '🗑️',
                'Park Damage'    => '🌳',
                'Other'          => '📌',
              ];
              foreach ($categories as $cat => $icon):
                $checked = (($old['category'] ?? '') === $cat) ? 'checked' : '';
              ?>
                <input type="radio" id="cat-<?= strtolower(str_replace(' ','-',$cat)) ?>"
                       name="category" value="<?= e($cat) ?>" class="cat-option" <?= $checked ?>>
                <label class="cat-label" for="cat-<?= strtolower(str_replace(' ','-',$cat)) ?>">
                  <span><?= $icon ?></span><?= e($cat) ?>
                </label>
              <?php endforeach; ?>
            </div>
            <span class="form-error" id="cat-error" style="margin-top:6px">Please select a complaint category.</span>
          </div>

          <!-- Description -->
          <div class="form-group">
            <label class="form-label" for="description">Issue Description <span class="required">*</span></label>
            <textarea id="description" name="description" class="form-control" rows="4"
                      placeholder="Describe the issue in detail — what's wrong, how long has it been there, severity, etc."><?= e($old['desc'] ?? '') ?></textarea>
            <span class="form-error">Please describe the issue.</span>
          </div>

          <!-- Area -->
          <div class="form-group">
            <label class="form-label" for="area">Street / Area <span class="required">*</span></label>
            <input type="text" id="area" name="area" class="form-control"
                   placeholder="e.g. G-10/2, Near Main Market, Islamabad"
                   value="<?= e($old['area'] ?? '') ?>">
            <span class="form-error">Please specify the area or street name.</span>
          </div>

          <!-- Notice -->
          <div class="alert alert-info" style="font-size:0.84rem">
            🔒 Your information is kept confidential and used only for resolving the reported issue. No login required.
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:15px">
            📤 Submit Complaint
          </button>

        </form>
      </div>

    <?php endif; ?>
  </div>
</section>

<?php include 'php/footer.php'; ?>
