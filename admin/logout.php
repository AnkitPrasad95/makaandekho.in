<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Only remove admin session keys — don't destroy frontend user session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);

require_once __DIR__ . '/../includes/db.php';
header('Location: ' . BASE_URL . 'login.php');
exit;
