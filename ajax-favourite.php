<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/includes/db.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.', 'login' => true]);
    exit;
}

$property_id = (int)($_POST['property_id'] ?? 0);
if (!$property_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid property.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Toggle
$check = $pdo->prepare("SELECT id FROM favourites WHERE user_id = ? AND property_id = ? AND is_deleted=0");
$check->execute([$user_id, $property_id]);

if ($check->fetch()) {
    $pdo->prepare("UPDATE favourites SET is_deleted=1, deleted_at=NOW() WHERE user_id = ? AND property_id = ?")->execute([$user_id, $property_id]);
    echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Removed from saved.']);
} else {
    $pdo->prepare("INSERT INTO favourites (user_id, property_id) VALUES (?, ?)")->execute([$user_id, $property_id]);
    echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Property saved!']);
}
