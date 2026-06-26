<?php
session_start();
require_once '../php/config.php';

// Redirect if already logged in
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Simple credentials — change these in production!
    // In a real system, store hashed passwords in the DB.
    $ADMIN_USER = 'admin';
    $ADMIN_PASS = 'admin123'; // CHANGE THIS!

    if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name']      = 'Municipal Admin';
        session_regenerate_id(true);
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | City Municipal Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-login-page">
  <div class="login-card">
    <div class="login-card-top">
      <div class="shield-icon">🔐</div>
      <h2>Admin Portal</h2>
      <p>City Municipal Services — Staff Only</p>
    </div>
    <div class="login-card-body">

      <?php if ($error): ?>
        <div class="alert alert-danger">⚠️ <?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control"
                 placeholder="Enter admin username" autocomplete="username" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control"
                 placeholder="Enter password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;margin-top:6px">
          🔓 Sign In
        </button>
      </form>

      <div style="margin-top:20px;text-align:center">
        <a href="../index.php" style="font-size:0.83rem;color:var(--text-muted)">← Back to Portal</a>
      </div>

      <div class="alert alert-info" style="margin-top:22px;font-size:0.8rem">
        🔒 Authorized municipal staff only. Contact your system administrator for access.
      </div>

    </div>
  </div>
</div>

<script src="../js/main.js"></script>
</body>
</html>
