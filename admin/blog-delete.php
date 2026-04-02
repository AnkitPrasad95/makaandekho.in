<?php
ob_start();
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'blogs.php');
    exit;
}

verify_csrf();

$id = (int) ($_POST['id'] ?? 0);
if (!$id) {
    flash('error', 'Invalid blog ID.');
    header('Location: ' . BASE_URL . 'blogs.php');
    exit;
}

// Fetch blog to get image filename
$stmt = $pdo->prepare("SELECT title, featured_image FROM blogs WHERE id=?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    flash('error', 'Blog not found.');
    header('Location: ' . BASE_URL . 'blogs.php');
    exit;
}

// Delete featured image file
if ($blog['featured_image']) {
    $img_path = UPLOAD_DIR . 'blogs/' . $blog['featured_image'];
    if (file_exists($img_path)) {
        unlink($img_path);
    }
}

// Delete blog record
$stmt = $pdo->prepare("DELETE FROM blogs WHERE id=?");
$stmt->execute([$id]);

flash('success', 'Blog "' . $blog['title'] . '" deleted successfully.');
header('Location: ' . BASE_URL . 'blogs.php');
exit;
