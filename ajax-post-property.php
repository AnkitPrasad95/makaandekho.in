<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$role   = in_array($_POST['role'] ?? '', ['owner','agent','builder']) ? $_POST['role'] : 'owner';
$name   = trim(strip_tags($_POST['name']  ?? ''));
$phone  = trim(preg_replace('/[^0-9]/', '', $_POST['phone'] ?? ''));
$email  = trim(strip_tags($_POST['email'] ?? ''));
$city   = trim(strip_tags($_POST['city']  ?? ''));
$state  = trim(strip_tags($_POST['state'] ?? ''));

// Validation
$errors = [];
if (!$name || strlen($name) < 2)                     $errors[] = 'Valid name is required (min 2 chars).';
if (strlen($name) > 100)                              $errors[] = 'Name too long (max 100 chars).';
if (!preg_match('/^[6-9][0-9]{9}$/', $phone))         $errors[] = 'Valid 10-digit Indian mobile number required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))        $errors[] = 'Valid email required.';
if (!$city || strlen($city) < 2)                       $errors[] = 'City is required.';
if (!$state)                                           $errors[] = 'State is required.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Check if user already exists
$chk = $pdo->prepare("SELECT id, status FROM users WHERE email = ? AND is_deleted=0");
$chk->execute([$email]);
$user = $chk->fetch();

$isNewUser   = false;
$rawPassword = '';

if ($user) {
    $user_id = $user['id'];
} else {
    // Generate a readable password
    $rawPassword = strtolower(substr(preg_replace('/\s+/', '', $name), 0, 4)) . rand(1000, 9999);
    if (strlen($rawPassword) < 6) $rawPassword = 'user' . rand(1000, 9999);

    $hash = password_hash($rawPassword, PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (name, email, phone, password, role, status, city, state) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)")
        ->execute([$name, $email, $phone, $hash, $role, $city, $state]);
    $user_id = (int)$pdo->lastInsertId();
    $isNewUser = true;
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

// Build response
$response = [
    'success'  => true,
    'message'  => 'Submitted successfully!',
    'new_user' => $isNewUser,
];

if ($isNewUser) {
    $response['password'] = $rawPassword;
    $response['email']    = $email;
}

// Send JSON response FIRST, then send email in background
// This prevents the browser from timing out while waiting for SMTP
ob_end_clean();
header('Connection: close');
header('Content-Length: ' . strlen(json_encode($response)));
echo json_encode($response);
flush();

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

// Now send email after response is delivered
if ($isNewUser) {
    require_once __DIR__ . '/includes/mailer.php';
    sendRegistrationEmail($email, $name, $settings);
}
