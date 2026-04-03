<?php
// -------------------------------------------------------
// User authentication helpers (frontend)
// -------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) session_start();

function user_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_user_auth(): void {
    if (!user_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . SITE_URL . 'login.php');
        exit;
    }
}

function current_user_id(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function current_user(): ?array {
    return $_SESSION['user_data'] ?? null;
}

function user_flash(string $type, string $msg): void {
    $_SESSION['user_flash'] = ['type' => $type, 'msg' => $msg];
}

function get_user_flash(): ?array {
    if (!empty($_SESSION['user_flash'])) {
        $f = $_SESSION['user_flash'];
        unset($_SESSION['user_flash']);
        return $f;
    }
    return null;
}
