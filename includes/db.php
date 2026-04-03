<?php
// -------------------------------------------------------
// Single database & URL configuration (shared by admin + frontend)
// Change SITE_ROOT if your project is in a sub-folder.
// e.g. '' if hosted at root, '/makaandekho.in' for XAMPP
// -------------------------------------------------------
define('SITE_ROOT',  '/makaandekho.in');
define('ADMIN_PATH', SITE_ROOT . '/admin/');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

define('BASE_URL',   $protocol . '://' . $host . ADMIN_PATH);
define('SITE_URL',   $protocol . '://' . $host . SITE_ROOT . '/');
define('UPLOAD_URL', $protocol . '://' . $host . SITE_ROOT . '/uploads/');
define('UPLOAD_DIR', rtrim(str_replace('\\', '/', realpath(__DIR__ . '/../uploads')), '/') . '/');

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=makaan_dekho;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die(
        '<div style="background:#f8d7da;color:#721c24;padding:20px;font-family:monospace;border-radius:6px;margin:20px">'
        . '<strong>Database Error:</strong> ' . htmlspecialchars($e->getMessage())
        . '<br><small>Check includes/db.php configuration.</small></div>'
    );
}

// Fetch site settings
$stmtSettings = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmtSettings->fetch() ?: [];
