<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$property_id = (int)($_POST['property_id'] ?? 0);
$name   = trim($_POST['name']  ?? '');
$email  = trim($_POST['email'] ?? '');
$phone  = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Name, email and phone are required.']);
    exit;
}

$pdo->prepare("INSERT INTO enquiries (property_id, name, email, phone, message, status) VALUES (?,?,?,?,?,?)")
    ->execute([$property_id ?: null, $name, $email, $phone, $message, 'new']);

echo json_encode(['success' => true, 'message' => 'Enquiry sent successfully! We\'ll get back to you soon.']);
