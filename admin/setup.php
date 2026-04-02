<?php
// ============================================================
// SETUP SCRIPT – Run once to create the admin user.
// DELETE this file after running!
// ============================================================
require_once __DIR__ . '/includes/db.php';

$name     = 'Super Admin';
$email    = 'admin@makaandekho.in';
$password = 'Admin@123';

// Check if admin already exists
$stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo '<p style="color:orange;font-family:monospace;">⚠ Admin user already exists. Setup skipped.</p>';
} else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins  = $pdo->prepare("INSERT INTO admin_users (name, email, password) VALUES (?, ?, ?)");
    $ins->execute([$name, $email, $hash]);
    echo '<p style="color:green;font-family:monospace;">✅ Admin user created successfully!</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MakaanDekho Setup</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body style="background:#f0f2f5;">
<div class="container mt-5">
  <div class="card shadow-sm" style="max-width:500px;border-radius:12px;">
    <div class="card-body p-4">
      <h4 class="font-weight-bold mb-1">MakaanDekho Setup</h4>
      <p class="text-muted mb-4">Initial admin account has been configured.</p>

      <table class="table table-bordered table-sm">
        <tr><th>Email</th><td><code><?= htmlspecialchars($email) ?></code></td></tr>
        <tr><th>Password</th><td><code><?= htmlspecialchars($password) ?></code></td></tr>
      </table>

      <div class="alert alert-warning py-2 mt-3" style="font-size:13px;">
        <strong>⚠ Security Notice:</strong> Delete <code>setup.php</code> immediately after logging in.
      </div>

      <a href="<?= BASE_URL ?>login.php" class="btn btn-primary btn-block mt-3">Go to Login →</a>
    </div>
  </div>
</div>
</body>
</html>
