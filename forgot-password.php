<?php
ob_start();
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mailer.php';

$step    = 'email'; // email -> sent -> reset -> done
$errors  = [];
$success = '';

// Step: Reset password (via token)
if (!empty($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW() AND is_deleted=0");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $step = 'email';
        $errors[] = 'Invalid or expired reset link. Please try again.';
    } else {
        $step = 'reset';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
            $pass    = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (strlen($pass) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            } elseif ($pass !== $confirm) {
                $errors[] = 'Passwords do not match.';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?")
                    ->execute([$hash, $user['id']]);
                $step = 'done';
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Step: Send reset link
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND is_deleted=0");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+48 hours'));
            $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?")
                ->execute([$token, $expires, $user['id']]);

            $resetLink = SITE_URL . "forgot-password.php?token=$token";

            // Send password reset email
            $siteName = $settings['site_name'] ?? 'MakaanDekho';
            $emailBody = '
            <h2 style="color:#1e40af;margin:0 0 16px;font-size:20px;">Password Reset Request</h2>
            <p>Hi, you requested a password reset for your <strong>' . htmlspecialchars($siteName) . '</strong> account.</p>
            <div style="text-align:center;margin:24px 0;">
                <a href="' . htmlspecialchars($resetLink) . '"
                   style="display:inline-block;background:linear-gradient(135deg,#1e40af,#1d4ed8);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:10px;font-weight:700;font-size:15px;">
                    Reset Password →
                </a>
            </div>
            <p style="font-size:12px;color:#6b7280;">If you didn\'t request this, you can safely ignore this email.</p>
            <p style="font-size:12px;color:#6b7280;">Link expires in 48 hours.</p>';

            sendMail($email, "Password Reset - $siteName", $emailBody, $settings);

            $success = "Password reset link has been sent to <strong>" . htmlspecialchars($email) . "</strong>. Please check your inbox (and spam folder).";
        }
        $step = 'sent';
    }
}

$pageTitle = 'Forgot Password | MakaanDekho';
include __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
<div class="container">
<div class="auth-card">
    <div class="auth-header">
        <?php if ($step === 'done'): ?>
        <div style="font-size:50px;color:#22c55e;margin-bottom:10px;"><i class="fas fa-check-circle"></i></div>
        <h2>Password Reset!</h2>
        <p>Your password has been updated successfully</p>
        <?php elseif ($step === 'reset'): ?>
        <h2>Set New Password</h2>
        <p>Create a strong password for your account</p>
        <?php else: ?>
        <h2>Forgot Password</h2>
        <p>Enter your email to receive a reset link</p>
        <?php endif; ?>
    </div>
    <div class="auth-body">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="border-radius:8px;font-size:13px;">
            <?php foreach ($errors as $e): ?><p class="mb-1"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success" style="border-radius:8px;font-size:13px;"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($step === 'done'): ?>
        <a href="<?= SITE_URL ?>login.php" class="auth-submit-btn d-block text-center text-decoration-none">
            <i class="fas fa-sign-in-alt me-2"></i>Login Now
        </a>

        <?php elseif ($step === 'reset'): ?>
        <form method="POST">
            <div class="auth-field">
                <label>New Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="new_password" placeholder="Min 6 characters" required minlength="6">
                </div>
            </div>
            <div class="auth-field">
                <label>Confirm Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Re-enter password" required>
                </div>
            </div>
            <button type="submit" class="auth-submit-btn">Reset Password</button>
        </form>

        <?php elseif ($step === 'sent'): ?>
        <p style="text-align:center;color:#666;font-size:14px;">If the email exists in our system, a reset link has been sent.</p>
        <a href="<?= SITE_URL ?>login.php" class="auth-submit-btn d-block text-center text-decoration-none mt-3">
            Back to Login
        </a>

        <?php else: ?>
        <form method="POST">
            <div class="auth-field">
                <label>Email Address</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="your@email.com" required>
                </div>
            </div>
            <button type="submit" class="auth-submit-btn">Send Reset Link</button>
        </form>
        <?php endif; ?>

        <div class="auth-footer">
            <a href="<?= SITE_URL ?>login.php">Back to Login</a>
        </div>
    </div>
</div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
