<?php
ob_start();
session_start();
require_once __DIR__ . '/includes/db.php';

// Already logged in?
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . 'dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_deleted=0");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } elseif ($user['status'] === 'pending') {
            $errors[] = 'Your account is pending admin approval.';
        } elseif ($user['status'] === 'blocked') {
            $errors[] = 'Your account has been blocked. Contact support.';
        } else {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_data'] = $user;

            $redirect = $_SESSION['redirect_after_login'] ?? SITE_URL . 'dashboard.php';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }
    }
}

$pageTitle = 'Login | MakaanDekho';
include __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
<div class="container">
<div class="auth-card">
    <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Login to manage your properties</p>
    </div>
    <div class="auth-body">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="border-radius:8px;font-size:13px;">
            <?php foreach ($errors as $e): ?><p class="mb-1"><i class="fas fa-exclamation-circle me-1"></i><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="auth-field">
                <label>Email Address</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>
            <div class="auth-field">
                <label>Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label style="font-size:13px;color:#666;cursor:pointer;">
                    <input type="checkbox" style="margin-right:4px;"> Remember me
                </label>
                <a href="<?= SITE_URL ?>forgot-password.php" style="font-size:13px;color:var(--primary);font-weight:600;">Forgot Password?</a>
            </div>
            <button type="submit" class="auth-submit-btn">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="<?= SITE_URL ?>register.php">Register Now</a>
        </div>
    </div>
</div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
