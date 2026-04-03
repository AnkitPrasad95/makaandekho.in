<?php
ob_start();
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=makaan_dekho;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('DB Error: ' . $e->getMessage());
}

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';
    $role     = in_array($_POST['role'] ?? '', ['owner','agent','builder'])
                ? $_POST['role'] : 'owner';

    // Validation
    if (!$name)
        $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Valid email address is required.';
    if (!preg_match('/^[0-9]{10}$/', $phone))
        $errors[] = 'Enter a valid 10-digit phone number.';
    if (strlen($password) < 6)
        $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)
        $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check email exists
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND is_deleted=0");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $errors[] = 'This email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("
                INSERT INTO users (name, email, phone, password, role, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ")->execute([$name, $email, $phone, $hash, $role]);

            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register – MakaanDekho</title>
<link rel="icon" type="image/png" href="/makaandekho.in/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { box-sizing: border-box; }
body {
  background: #f0f2f5;
  font-family: 'Segoe UI', system-ui, sans-serif;
  min-height: 100vh;
  display: flex; align-items: center;
}
.register-wrap { max-width: 500px; width: 100%; margin: 30px auto; padding: 16px; }

.reg-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0,0,0,.12);
  overflow: hidden;
}
.reg-header {
  background: linear-gradient(135deg, #0f172a, #1e40af);
  padding: 32px 36px 28px;
  text-align: center; color: #fff;
}
.reg-header .logo   { font-size: 26px; font-weight: 800; margin: 0; }
.reg-header .logo span { color: #f0a500; }
.reg-header p       { color: rgba(255,255,255,.65); font-size: 13px; margin: 6px 0 0; }

.reg-body { padding: 32px 36px 36px; }
.reg-body h5 { font-size: 18px; font-weight: 700; color: #1a2332; margin-bottom: 22px; }

/* Role tabs */
.role-tabs { display: flex; gap: 8px; margin-bottom: 22px; }
.role-tab {
  flex: 1; padding: 9px 6px; border: 2px solid #e5e7eb;
  border-radius: 8px; text-align: center; cursor: pointer;
  font-size: 13px; font-weight: 600; color: #6b7280;
  background: #f8fafc; transition: all .18s; user-select: none;
}
.role-tab input { display: none; }
.role-tab i { display: block; font-size: 20px; margin-bottom: 3px; }
.role-tab.selected { border-color: #1e40af; background: #eff6ff; color: #1e40af; }

/* Fields */
.form-label { font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }
.input-group-text {
  background: #f8fafc; border-right: none;
  border-radius: 8px 0 0 8px; color: #6b7280;
  border: 1.5px solid #e5e7eb;
}
.form-control {
  height: 44px; font-size: 14px; border-radius: 0 8px 8px 0;
  border: 1.5px solid #e5e7eb; border-left: none;
}
.form-control:focus {
  border-color: #1e40af; box-shadow: none;
  border-left: 1px solid #1e40af;
}
.input-group:focus-within .input-group-text { border-color: #1e40af; }

.btn-register {
  height: 48px; border-radius: 10px; font-size: 15px;
  font-weight: 700; background: linear-gradient(135deg,#1e40af,#1d4ed8);
  border: none; width: 100%; color: #fff; cursor: pointer;
  transition: opacity .2s;
}
.btn-register:hover { opacity: .9; }

/* Success */
.success-box {
  text-align: center; padding: 30px 10px;
}
.success-box .icon {
  width: 72px; height: 72px; border-radius: 50%;
  background: #d1fae5; color: #059669; font-size: 32px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 18px;
}
.success-box h4 { font-weight: 800; color: #1a2332; }
.success-box p  { color: #6b7280; font-size: 14px; line-height: 1.6; }

.already-login { text-align: center; margin-top: 18px; font-size: 13px; color: #6b7280; }
.already-login a { color: #1e40af; font-weight: 600; }
</style>
</head>
<body>

<div class="register-wrap">
  <div class="reg-card">
    <div class="reg-header">
      <?php if (file_exists(__DIR__ . '/assets/img/logo.png')): ?>
        <img src="/makaandekho.in/assets/img/logo.png"
             style="max-height:44px;object-fit:contain;filter:brightness(0) invert(1);margin-bottom:6px;"
             alt="MakaanDekho">
      <?php else: ?>
        <h1 class="logo">Makaan<span>Dekho</span></h1>
      <?php endif; ?>
      <p>Real Estate Platform</p>
    </div>

    <div class="reg-body">

      <?php if ($success): ?>
      <!-- ── Success ── -->
      <div class="success-box">
        <div class="icon"><i class="fas fa-check"></i></div>
        <h4>Registration Successful!</h4>
        <p>
          Your account has been created and is <strong>pending approval</strong>.<br>
          Our team will review and activate your account shortly.<br>
          You'll be notified on <strong><?= htmlspecialchars($_POST['email'] ?? '') ?></strong>.
        </p>
        <div class="alert alert-warning mt-3" style="border-radius:10px;font-size:13px;">
          <i class="fas fa-clock mr-2"></i>
          Approval usually takes <strong>1–24 hours</strong>.
        </div>
        <a href="register.php" class="btn btn-outline-primary mt-2" style="border-radius:8px;">
          Register Another Account
        </a>
      </div>

      <?php else: ?>
      <!-- ── Form ── -->
      <h5>Create Your Account</h5>

      <?php if (!empty($errors)): ?>
      <div class="alert alert-danger mb-3" style="border-radius:10px;font-size:13px;">
        <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix:</strong>
        <ul class="mb-0 mt-1 pl-3">
          <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <form method="POST" novalidate>

        <!-- Role -->
        <div class="form-group">
          <label class="form-label">I am a</label>
          <div class="role-tabs">
            <?php foreach ([
              'owner'   => ['fa-user',     'Owner'],
              'agent'   => ['fa-id-badge', 'Agent'],
              'builder' => ['fa-hard-hat', 'Builder'],
            ] as $val => [$icon, $label]): ?>
            <label class="role-tab <?= ($_POST['role']??'owner')===$val?'selected':'' ?>">
              <input type="radio" name="role" value="<?=$val?>"
                     <?= ($_POST['role']??'owner')===$val?'checked':'' ?>>
              <i class="fas <?=$icon?>"></i><?=$label?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Name -->
        <div class="form-group">
          <label class="form-label">Full Name <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user fa-sm"></i></span></div>
            <input type="text" name="name" class="form-control"
                   placeholder="Your full name"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required autofocus>
          </div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label class="form-label">Email Address <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span></div>
            <input type="email" name="email" class="form-control"
                   placeholder="your@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          </div>
        </div>

        <!-- Phone -->
        <div class="form-group">
          <label class="form-label">Phone Number <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone fa-sm"></i></span></div>
            <input type="tel" name="phone" class="form-control"
                   placeholder="10-digit mobile number"
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                   pattern="[0-9]{10}" maxlength="10" required>
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span></div>
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="Min. 6 characters" required>
          </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
          <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span></div>
            <input type="password" name="confirm" id="confirm" class="form-control"
                   placeholder="Re-enter password" required>
          </div>
          <small id="matchMsg" style="font-size:12px;"></small>
        </div>

        <button type="submit" class="btn-register mt-2">
          <i class="fas fa-user-plus mr-2"></i>Register Now
        </button>

      </form>

      <div class="already-login">
        Already have an account? <a href="login-user.php">Sign In</a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Role tab toggle
$('.role-tab').on('click', function () {
  $('.role-tab').removeClass('selected');
  $(this).addClass('selected');
  $(this).find('input').prop('checked', true);
});

// Password match indicator
$('#confirm').on('input', function () {
  var msg = $('#matchMsg');
  if ($(this).val() === $('#password').val()) {
    msg.text('✓ Passwords match').css('color','#059669');
  } else {
    msg.text('✗ Passwords do not match').css('color','#dc2626');
  }
});
</script>
</body>
</html>
