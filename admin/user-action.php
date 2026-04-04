<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'users.php');
    exit;
}

verify_csrf();

$id     = (int) ($_POST['id']     ?? 0);
$action = $_POST['action']        ?? '';

if (!$id || !in_array($action, ['approve','reject','toggle_status','role'])) {
    flash('error', 'Invalid request.');
    header('Location: ' . BASE_URL . 'users.php');
    exit;
}

require_once __DIR__ . '/../includes/mailer.php';

$check = $pdo->prepare("SELECT id, name, email, status FROM users WHERE id=? AND is_deleted=0");
$check->execute([$id]);
$user = $check->fetch();

if (!$user) {
    flash('error', 'User not found.');
    header('Location: ' . BASE_URL . 'users.php');
    exit;
}

switch ($action) {

    case 'approve':
        // Generate password reset token (48 hours validity)
        $resetToken = bin2hex(random_bytes(32));
        $resetExpires = date('Y-m-d H:i:s', strtotime('+48 hours'));

        $pdo->prepare("UPDATE users SET status='active', reset_token=?, reset_expires=? WHERE id=?")
            ->execute([$resetToken, $resetExpires, $id]);

        flash('success', "✅ {$user['name']} approved. Sending email to {$user['email']}...");

        // Redirect first, then send email
        header('Location: ' . BASE_URL . 'users.php');
        ob_end_flush();
        flush();
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        // Send email after redirect
        sendApprovalEmail($user['email'], $user['name'], $resetToken, $settings);
        exit;

    case 'reject':
        $pdo->prepare("UPDATE users SET status='blocked' WHERE id=?")->execute([$id]);
        flash('success', "🚫 {$user['name']} has been rejected.");
        break;

    case 'toggle_status':
        $new = $user['status'] === 'active' ? 'blocked' : 'active';
        $pdo->prepare("UPDATE users SET status=? WHERE id=?")->execute([$new, $id]);
        flash('success', "{$user['name']} has been " . ($new === 'blocked' ? 'blocked.' : 'unblocked.'));
        break;

    case 'role':
        $role = $_POST['role'] ?? '';
        if (!in_array($role, ['owner','agent','builder'])) {
            flash('error', 'Invalid role.');
            break;
        }
        $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role, $id]);
        flash('success', "Role updated to " . ucfirst($role) . " for {$user['name']}.");
        break;
}

header('Location: ' . BASE_URL . 'users.php');
exit;
