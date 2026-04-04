<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Sanitize inputs
$property_id = (int)($_POST['property_id'] ?? 0);
$name    = trim(strip_tags($_POST['name']  ?? ''));
$email   = trim(strip_tags($_POST['email'] ?? ''));
$phone   = trim(preg_replace('/[^0-9]/', '', $_POST['phone'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validation
$errors = [];
if (!$name || strlen($name) < 2)          $errors[] = 'Valid name is required (min 2 chars).';
if (strlen($name) > 100)                  $errors[] = 'Name too long (max 100 chars).';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email address is required.';
if (!preg_match('/^[6-9][0-9]{9}$/', $phone))   $errors[] = 'Valid 10-digit Indian mobile number required.';
if (strlen($message) > 2000)              $errors[] = 'Message too long (max 2000 chars).';

// Rate limiting — max 5 enquiries per email per hour
if (empty($errors)) {
    $rateCheck = $pdo->prepare("SELECT COUNT(*) FROM enquiries WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $rateCheck->execute([$email]);
    if ($rateCheck->fetchColumn() >= 5) {
        $errors[] = 'Too many enquiries. Please try again after some time.';
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Insert with prepared statement (SQL injection safe)
$stmt = $pdo->prepare("INSERT INTO enquiries (property_id, name, email, phone, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
$stmt->execute([$property_id ?: null, $name, $email, $phone, $message]);

echo json_encode(['success' => true, 'message' => 'Enquiry sent successfully! We\'ll get back to you soon.']);
