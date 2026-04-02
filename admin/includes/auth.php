<?php
// -------------------------------------------------------
// Auth & utility helpers
// -------------------------------------------------------

function require_auth(): void
{
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

// CSRF helpers
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid security token. Please go back and try again.');
    }
}

// Flash messages
function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// Generate a URL-friendly slug
function slugify(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^\w\s-]/u', '', $text);
    $text = preg_replace('/[\s_-]+/', '-', $text);
    return trim($text, '-');
}

// Format price in Indian number system
function format_inr(float $amount): string
{
    if ($amount >= 1_00_00_000) {
        return '₹' . number_format($amount / 1_00_00_000, 2) . ' Cr';
    }
    if ($amount >= 1_00_000) {
        return '₹' . number_format($amount / 1_00_000, 2) . ' L';
    }
    return '₹' . number_format($amount, 0);
}
