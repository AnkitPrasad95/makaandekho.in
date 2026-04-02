<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/db.php';
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

$check = $pdo->prepare("SELECT id, name, status FROM users WHERE id=?");
$check->execute([$id]);
$user = $check->fetch();

if (!$user) {
    flash('error', 'User not found.');
    header('Location: ' . BASE_URL . 'users.php');
    exit;
}

switch ($action) {

    case 'approve':
        $pdo->prepare("UPDATE users SET status='active' WHERE id=?")->execute([$id]);
        flash('success', "✅ {$user['name']} has been approved and activated.");
        break;

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
