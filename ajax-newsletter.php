<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = trim(strip_tags($_POST['email'] ?? ''));

// Validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Check if already subscribed
$chk = $pdo->prepare("SELECT id, is_active FROM newsletter_subscribers WHERE email = ?");
$chk->execute([$email]);
$existing = $chk->fetch();

if ($existing) {
    if ($existing['is_active']) {
        echo json_encode(['success' => false, 'message' => 'You are already subscribed!']);
    } else {
        // Reactivate
        $pdo->prepare("UPDATE newsletter_subscribers SET is_active = 1, subscribed_at = NOW() WHERE id = ?")
            ->execute([$existing['id']]);
        echo json_encode(['success' => true, 'message' => 'Welcome back! You have been re-subscribed.']);
    }
} else {
    $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)")->execute([$email]);
    echo json_encode(['success' => true, 'message' => 'Subscribed successfully! You\'ll receive our latest updates.']);
}
