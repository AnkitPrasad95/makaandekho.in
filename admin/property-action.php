<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'properties.php');
    exit;
}

verify_csrf();

$id       = (int) ($_POST['id']     ?? 0);
$action   = $_POST['action']        ?? '';
$redirect = $_POST['redirect']      ?? 'list';

if (!$id || !in_array($action, ['approve','reject','toggle_featured'])) {
    flash('error', 'Invalid request.');
    header('Location: ' . BASE_URL . 'properties.php');
    exit;
}

// Verify property exists
$check = $pdo->prepare("SELECT id, title FROM properties WHERE id = ? AND is_deleted=0");
$check->execute([$id]);
$prop = $check->fetch();

if (!$prop) {
    flash('error', 'Property not found.');
    header('Location: ' . BASE_URL . 'properties.php');
    exit;
}

switch ($action) {

    case 'approve':
        $stmt = $pdo->prepare("UPDATE properties SET status='approved', rejection_reason=NULL WHERE id=?");
        $stmt->execute([$id]);
        flash('success', "Property "{$prop['title']}" has been approved.");
        break;

    case 'reject':
        $reason = trim($_POST['rejection_reason'] ?? '');
        if (!$reason) {
            flash('error', 'Please provide a rejection reason.');
            header('Location: ' . BASE_URL . 'property-view.php?id=' . $id);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE properties SET status='rejected', rejection_reason=? WHERE id=?");
        $stmt->execute([$reason, $id]);
        flash('success', "Property "{$prop['title']}" has been rejected.");
        break;

    case 'toggle_featured':
        $stmt = $pdo->prepare("UPDATE properties SET featured = 1 - featured WHERE id=?");
        $stmt->execute([$id]);
        flash('success', "Featured status updated.");
        break;
}

if ($redirect === 'view') {
    header('Location: ' . BASE_URL . 'property-view.php?id=' . $id);
} else {
    header('Location: ' . BASE_URL . 'properties.php');
}
exit;
