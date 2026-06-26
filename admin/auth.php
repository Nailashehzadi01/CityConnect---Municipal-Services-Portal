<?php
// admin/auth.php — Include at top of every admin page
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
