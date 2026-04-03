<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$role   = in_array($_POST['role'] ?? '', ['owner','agent','builder']) ? $_POST['role'] : 'owner';
$name   = trim($_POST['name']  ?? '');
$phone  = trim($_POST['phone'] ?? '');
$email  = trim($_POST['email'] ?? '');
$city   = trim($_POST['city']  ?? '');
$state  = trim($_POST['state'] ?? '');

// Validation
$errors = [];
if (!$name)                                  $errors[] = 'Name is required.';
if (!preg_match('/^[0-9]{10}$/', $phone))    $errors[] = 'Valid 10-digit phone required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
if (!$city)                                  $errors[] = 'City is required.';
if (!$state)                                 $errors[] = 'State is required.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Get or create user
$chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND is_deleted=0");
$chk->execute([$email]);
$user = $chk->fetch();

if ($user) {
    $user_id = $user['id'];
} else {
    $hash = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, 'pending')")
        ->execute([$name, $email, $phone, $hash, $role]);
    $user_id = (int)$pdo->lastInsertId();
}

// Find or create location
$locStmt = $pdo->prepare("SELECT id FROM locations WHERE city = ? AND state = ? AND is_deleted=0 LIMIT 1");
$locStmt->execute([$city, $state]);
$loc = $locStmt->fetch();

if ($loc) {
    $location_id = $loc['id'];
} else {
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $city . ' ' . $state));
    $pdo->prepare("INSERT INTO locations (city, state, slug) VALUES (?, ?, ?)")
        ->execute([$city, $state, $slug]);
    $location_id = (int)$pdo->lastInsertId();
}

// Create property entry (pending approval)
$title = "Property in $city by $name";
$slug_base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
$slug = $slug_base;
$i = 1;
while (true) {
    $s = $pdo->prepare("SELECT id FROM properties WHERE slug = ?");
    $s->execute([$slug]);
    if (!$s->fetch()) break;
    $slug = $slug_base . '-' . (++$i);
}

$pdo->prepare("
    INSERT INTO properties (title, slug, location_id, user_id, address, status, availability, publish_status, created_at)
    VALUES (?, ?, ?, ?, ?, 'pending', 'available', 'draft', NOW())
")->execute([$title, $slug, $location_id, $user_id, "$city, $state"]);

echo json_encode(['success' => true, 'message' => 'Property submitted successfully!']);
