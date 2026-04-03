<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';

// Already logged in → redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ? AND is_deleted=0 LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_name']  = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            header('Location: ' . BASE_URL . 'dashboard.php');
            exit;
        }
        $error = 'Invalid email address or password.';
    } else {
        $error = 'Please enter your email and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login – MakaanDekho</title>
<link rel="icon" type="image/png" href="<?= SITE_URL ?>favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
  background: linear-gradient(135deg, #1a2332 0%, #1a6ebd 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  font-family: 'Segoe UI', system-ui, sans-serif;
}
.login-wrap { max-width: 400px; width: 100%; margin: 0 auto; padding: 20px; }
.login-card { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,.4); }
.login-brand {
  background: #111b27;
  padding: 32px 36px 28px;
  text-align: center;
}
.login-brand .logo      { color: #fff; font-size: 26px; font-weight: 800; margin: 0; }
.login-brand .logo span { color: #f0a500; }
.login-brand small      { color: #5d7a96; font-size: 12px; }
.login-body { padding: 32px 36px 36px; background: #fff; }
.login-body h5 { font-size: 18px; font-weight: 700; color: #1a2332; margin-bottom: 24px; }

.form-group label { font-size: 12.5px; font-weight: 600; color: #495057; margin-bottom: 6px; }
.input-group-text {
  background: #f8f9fa; border-right: none;
  color: #6c757d; border-radius: 8px 0 0 8px;
}
.form-control {
  height: 44px; font-size: 14px;
  border-left: none; border-radius: 0 8px 8px 0;
}
.form-control:focus {
  box-shadow: none; border-color: #ced4da;
  border-left: 1px solid #ced4da;
}
.input-group:focus-within .input-group-text {
  border-color: #86b7fe;
}
.btn-login {
  height: 44px; border-radius: 8px;
  font-size: 15px; font-weight: 600;
  background: linear-gradient(135deg, #0d6efd, #0a58ca);
  border: none;
}
.btn-login:hover { opacity: .92; }
</style>
</head>
<body>

<div class="login-wrap">
  <div class="card login-card">
    <div class="login-brand">
      <?php
      $logo_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . SITE_ROOT . '/assets/img/logo.png';
      if (file_exists($logo_path)): ?>
        <img src="<?= SITE_URL ?>assets/img/logo.png"
             style="max-height:44px;max-width:200px;object-fit:contain;filter:brightness(0) invert(1);"
             alt="MakaanDekho">
      <?php else: ?>
        <h1 class="logo">Makaan<span>Dekho</span></h1>
      <?php endif; ?>
      <small>Real Estate Admin Panel</small>
    </div>

    <div class="login-body">
      <h5>Sign in to your account</h5>

      <?php if ($error): ?>
      <div class="alert alert-danger py-2" style="font-size:13px;">
        <i class="fas fa-exclamation-circle mr-1"></i><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" novalidate>
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span>
            </div>
            <input
              type="email" id="email" name="email"
              class="form-control"
              placeholder="admin@makaandekho.in"
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              required autofocus
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span>
            </div>
            <input
              type="password" id="password" name="password"
              class="form-control"
              placeholder="••••••••"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-login mt-2">
          <i class="fas fa-sign-in-alt mr-2"></i>Sign In
        </button>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
