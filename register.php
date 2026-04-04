<?php
// Registration is now handled through the "Post Property" popup on any page.
// This page redirects to homepage and opens the popup automatically.
require_once __DIR__ . '/includes/db.php';
header('Location: ' . SITE_URL . '?register=1');
exit;
