<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/auth.php';
require_auth();

$current = basename($_SERVER['PHP_SELF']);
$flash   = get_flash();

// New enquiry badge count (used in sidebar)
$new_enq_count = (int) $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status='new' AND is_deleted=0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MakaanDekho Admin</title>
<link rel="icon" type="image/png" href="<?= SITE_URL ?>favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css">
<style>
:root {
  --sb-bg: #1a2332;
  --sb-hover: #243447;
  --sb-active-border: #f0a500;
  --topbar-h: 58px;
}
*, *::before, *::after { box-sizing: border-box; }
body  { background: #f0f2f5; font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; font-size: 14px; }
a     { text-decoration: none; }

/* ── Sidebar ── */
#sidebar {
  width: 240px; min-height: 100vh;
  background: var(--sb-bg);
  position: fixed; top: 0; left: 0; z-index: 1030;
  display: flex; flex-direction: column;
  transition: transform .25s ease;
}
.sb-brand {
  padding: 16px 20px; background: #111b27;
  border-bottom: 1px solid rgba(255,255,255,.06);
  flex-shrink: 0;
}
.sb-brand .logo    { color: #fff; font-size: 20px; font-weight: 800; margin: 0; line-height: 1; }
.sb-brand .logo span { color: #f0a500; }
.sb-brand small    { color: #5d7a96; font-size: 11px; }
.sb-nav           { flex: 1; overflow-y: auto; padding: 8px 0 20px; }
.sb-nav::-webkit-scrollbar { width: 4px; }
.sb-nav::-webkit-scrollbar-thumb { background: #2d4158; border-radius: 2px; }
.nav-section {
  color: #3d5872; font-size: 10px; text-transform: uppercase;
  letter-spacing: 1.3px; font-weight: 700;
  padding: 16px 20px 4px; list-style: none;
}
.nav-item { list-style: none; margin: 0; }
.nav-item > a {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 20px; color: #7fa8c8; font-size: 13.5px;
  transition: all .18s; border-left: 3px solid transparent;
}
.nav-item > a:hover,
.nav-item.active > a {
  color: #fff;
  background: var(--sb-hover);
  border-left-color: var(--sb-active-border);
}
.nav-item > a .nav-icon { width: 16px; text-align: center; font-size: 13px; flex-shrink: 0; }
.nav-item > a .angle    { margin-left: auto; font-size: 11px; transition: transform .2s; }
.nav-item.open > a .angle { transform: rotate(-90deg); }
.submenu { list-style: none; padding: 0; background: #111b27; display: none; }
.nav-item.open > .submenu { display: block; }
.submenu li a {
  display: block; padding: 9px 20px 9px 46px;
  color: #5d859e; font-size: 13px; transition: color .18s;
}
.submenu li a:hover,
.submenu li.active a { color: #fff; }

/* ── Topbar ── */
#topbar {
  height: var(--topbar-h); background: #fff;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 24px;
  box-shadow: 0 1px 0 #e4e8ed;
  position: sticky; top: 0; z-index: 1020;
  margin-left: 240px;
  transition: margin-left .25s;
}
#btn-toggle { background: none; border: none; color: #666; font-size: 17px; cursor: pointer; padding: 6px 10px; border-radius: 6px; }
#btn-toggle:hover { background: #f0f2f5; }
.topbar-right { display: flex; align-items: center; gap: 14px; }
.admin-avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: #0d6efd; color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700;
}

/* ── Page wrapper ── */
#page-content {
  margin-left: 240px;
  padding: 24px;
  min-height: calc(100vh - var(--topbar-h));
  transition: margin-left .25s;
}

/* ── Sidebar collapsed ── */
body.sb-collapsed #sidebar   { transform: translateX(-240px); }
body.sb-collapsed #topbar,
body.sb-collapsed #page-content { margin-left: 0; }

/* ── Cards ── */
.card { border: none; box-shadow: 0 1px 6px rgba(0,0,0,.08); border-radius: 10px; }
.card-header {
  background: #fff; padding: 14px 20px;
  border-bottom: 1px solid #eef1f5; font-weight: 600; color: #1a2332;
  border-radius: 10px 10px 0 0 !important;
  display: flex; align-items: center; justify-content: space-between;
}

/* ── Stat cards ── */
.stat-card { border-radius: 12px; padding: 20px 22px; color: #fff; }
.stat-card .stat-num   { font-size: 34px; font-weight: 700; line-height: 1.1; }
.stat-card .stat-label { font-size: 12.5px; opacity: .85; margin-top: 4px; }
.stat-card .stat-icon  { font-size: 42px; opacity: .2; }

/* ── Badges ── */
.badge-pending  { background: #fff3cd; color: #664d03; }
.badge-approved { background: #d1e7dd; color: #0a3622; }
.badge-rejected { background: #f8d7da; color: #58151c; }
.badge-active   { background: #d1e7dd; color: #0a3622; }
.badge-blocked  { background: #f8d7da; color: #58151c; }
.badge-new      { background: #cfe2ff; color: #084298; }
.badge-read     { background: #e2e3e5; color: #41464b; }
.badge-replied  { background: #d1e7dd; color: #0a3622; }
.badge-owner    { background: #e0cffc; color: #3d0a91; }
.badge-agent    { background: #cff4fc; color: #055160; }
.badge-builder  { background: #fde68a; color: #713f12; }

/* ── Tables ── */
.table thead th {
  background: #f8f9fb; font-size: 11.5px; text-transform: uppercase;
  letter-spacing: .5px; color: #6c757d; font-weight: 600;
  border-top: none; border-bottom: 1px solid #dee2e6;
}
.table td { vertical-align: middle; }

/* ── Misc ── */
.page-header { margin-bottom: 22px; }
.page-header h4 { font-size: 20px; font-weight: 700; color: #1a2332; margin: 0; }
.page-header p  { color: #6c757d; margin: 3px 0 0; font-size: 13px; }
.btn-xs { padding: 3px 9px !important; font-size: 12px !important; }

@media (max-width: 768px) {
  #sidebar { transform: translateX(-240px); }
  #topbar, #page-content { margin-left: 0; }
  body.sb-open #sidebar { transform: translateX(0); }
}
</style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════════ -->
<div id="sidebar">
  <div class="sb-brand">
    <?php
    $logo_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . str_replace('/admin/', '/', ADMIN_PATH) . 'assets/img/logo.png';
    if (file_exists($logo_path)): ?>
      <img src="<?= SITE_URL ?>assets/img/logo.png"
           style="max-height:38px;max-width:180px;object-fit:contain;filter:brightness(0) invert(1);"
           alt="MakaanDekho">
    <?php else: ?>
      <h1 class="logo">Makaan<span>Dekho</span></h1>
    <?php endif; ?>
    <small>Admin Panel</small>
  </div>

  <nav class="sb-nav">
    <ul style="padding:0;margin:0;">

      <li class="nav-item <?= $current === 'dashboard.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>dashboard.php">
          <i class="fas fa-chart-pie nav-icon"></i> Dashboard
        </a>
      </li>

      <li class="nav-item">
        <a href="<?= SITE_URL ?>" target="_blank">
          <i class="fas fa-external-link-alt nav-icon"></i> View Website
        </a>
      </li>

      <li class="nav-section">Management</li>

      <?php
        $prop_pages  = ['properties.php','property-view.php'];
        $prop_active = in_array($current, $prop_pages);
        $prop_status = $_GET['status'] ?? '';
      ?>
      <li class="nav-item has-sub <?= $prop_active ? 'open active' : '' ?>">
        <a href="#">
          <i class="fas fa-building nav-icon"></i> Properties
          <i class="fas fa-angle-left angle"></i>
        </a>
        <ul class="submenu">
          <li class="<?= $current==='property-add.php' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>property-add.php"><i class="fas fa-plus-circle mr-1" style="font-size:11px;color:#f0a500;"></i> Add Property</a>
          </li>
          <li class="<?= ($current==='properties.php' && $prop_status==='') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>properties.php">All Properties</a>
          </li>
          <li class="<?= $prop_status==='pending'  ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>properties.php?status=pending">Pending</a>
          </li>
          <li class="<?= $prop_status==='approved' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>properties.php?status=approved">Approved</a>
          </li>
          <li class="<?= $prop_status==='rejected' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>properties.php?status=rejected">Rejected</a>
          </li>
        </ul>
      </li>

      <li class="nav-item <?= $current === 'users.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>users.php">
          <i class="fas fa-users nav-icon"></i> Users
        </a>
      </li>

      <li class="nav-item <?= $current === 'enquiries.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>enquiries.php">
          <i class="fas fa-envelope nav-icon"></i> Enquiries
          <?php if ($new_enq_count > 0): ?>
            <span class="badge badge-danger ml-auto" style="font-size:10px;border-radius:10px;"><?= $new_enq_count ?></span>
          <?php endif; ?>
        </a>
      </li>

      <li class="nav-section">Content</li>

      <li class="nav-item <?= in_array($current, ['pages.php','page-edit.php']) ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>pages.php">
          <i class="fas fa-file-alt nav-icon"></i> CMS Pages
        </a>
      </li>

      <li class="nav-item <?= in_array($current, ['blogs.php','blog-add.php','blog-edit.php']) ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>blogs.php">
          <i class="fas fa-blog nav-icon"></i> Blogs
        </a>
      </li>

      <li class="nav-item <?= $current === 'locations.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>locations.php">
          <i class="fas fa-map-marker-alt nav-icon"></i> Locations
        </a>
      </li>

      <li class="nav-item <?= $current === 'mega-menu.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>mega-menu.php">
          <i class="fas fa-bars nav-icon"></i> Mega Menu
        </a>
      </li>

      <li class="nav-item <?= $current === 'banners.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>banners.php">
          <i class="fas fa-images nav-icon"></i> Banners
        </a>
      </li>

      <li class="nav-item <?= $current === 'testimonials.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>testimonials.php">
          <i class="fas fa-quote-left nav-icon"></i> Testimonials
        </a>
      </li>

      <li class="nav-section">System</li>

      <li class="nav-item <?= $current === 'settings.php' ? 'active' : '' ?>">
        <a href="<?= BASE_URL ?>settings.php">
          <i class="fas fa-cog nav-icon"></i> Settings
        </a>
      </li>

      <li class="nav-item">
        <a href="<?= BASE_URL ?>logout.php">
          <i class="fas fa-sign-out-alt nav-icon"></i> Logout
        </a>
      </li>

    </ul>
  </nav>
</div>

<!-- ══ TOPBAR ════════════════════════════════════════════ -->
<div id="topbar">
  <button id="btn-toggle" title="Toggle sidebar"><i class="fas fa-bars"></i></button>
  <div class="topbar-right">
    <small class="text-muted d-none d-md-inline"><?= date('D, d M Y') ?></small>
    <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?></div>
    <span style="font-weight:600;font-size:13px;"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
    <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-danger btn-sm btn-xs">Logout</a>
  </div>
</div>

<!-- ══ PAGE CONTENT ══════════════════════════════════════ -->
<div id="page-content">

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-3" role="alert">
  <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> mr-2"></i>
  <?= htmlspecialchars($flash['msg']) ?>
  <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
<?php endif; ?>
