<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

$user_id = current_user_id();
$flash = get_user_flash();

// Refresh user data
$uStmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_deleted=0");
$uStmt->execute([$user_id]);
$user = $uStmt->fetch();
$_SESSION['user_data'] = $user;

// Stats
$totalProps   = (int)$pdo->prepare("SELECT COUNT(*) FROM properties WHERE user_id = ?")->execute([$user_id]) ? $pdo->query("SELECT FOUND_ROWS()")->fetchColumn() : 0;
$stmtCounts = $pdo->prepare("SELECT status, COUNT(*) as cnt FROM properties WHERE user_id = ? AND is_deleted=0 GROUP BY status");
$stmtCounts->execute([$user_id]);
$statusCounts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
while ($row = $stmtCounts->fetch()) {
    $statusCounts[$row['status']] = (int)$row['cnt'];
}
$totalProps = array_sum($statusCounts);

$totalEnquiries = 0;
$eStmt = $pdo->prepare("SELECT COUNT(*) FROM enquiries e JOIN properties p ON e.property_id = p.id WHERE p.user_id = ? AND e.is_deleted=0 AND p.is_deleted=0");
$eStmt->execute([$user_id]);
$totalEnquiries = (int)$eStmt->fetchColumn();

$totalViews = 0;
$vStmt = $pdo->prepare("SELECT COALESCE(SUM(views), 0) FROM properties WHERE user_id = ? AND is_deleted=0");
$vStmt->execute([$user_id]);
$totalViews = (int)$vStmt->fetchColumn();

// Recent properties
$recentProps = $pdo->prepare("SELECT p.*, l.city, l.area FROM properties p LEFT JOIN locations l ON p.location_id = l.id WHERE p.user_id = ? AND p.is_deleted=0 ORDER BY p.created_at DESC LIMIT 5");
$recentProps->execute([$user_id]);
$recentProperties = $recentProps->fetchAll();

// Recent enquiries on user's properties
$recentEnq = $pdo->prepare("SELECT e.*, p.title as prop_title FROM enquiries e JOIN properties p ON e.property_id = p.id WHERE p.user_id = ? AND e.is_deleted=0 AND p.is_deleted=0 ORDER BY e.created_at DESC LIMIT 5");
$recentEnq->execute([$user_id]);
$recentEnquiries = $recentEnq->fetchAll();

$pageTitle = 'Dashboard | MakaanDekho';
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <!-- Dashboard Header -->
    <div class="dash-header">
        <div>
            <h2>Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
            <p><span class="dash-role-badge"><?= ucfirst($user['role']) ?></span> <span class="text-muted">|</span> <?= htmlspecialchars($user['email']) ?></p>
        </div>
        <a href="<?= SITE_URL ?>add-property.php" class="btn-dash-add">
            <i class="fas fa-plus me-2"></i>Add Property
        </a>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" style="border-radius:10px;">
        <?= htmlspecialchars($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dash-stat" style="border-left:4px solid var(--primary);">
                <div class="dash-stat-num"><?= $totalProps ?></div>
                <div class="dash-stat-label">Total Properties</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat" style="border-left:4px solid #22c55e;">
                <div class="dash-stat-num"><?= $statusCounts['approved'] ?></div>
                <div class="dash-stat-label">Approved / Live</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat" style="border-left:4px solid #f59e0b;">
                <div class="dash-stat-num"><?= $statusCounts['pending'] ?></div>
                <div class="dash-stat-label">Pending Review</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat" style="border-left:4px solid #ef4444;">
                <div class="dash-stat-num"><?= $totalEnquiries ?></div>
                <div class="dash-stat-label">Enquiries Received</div>
            </div>
        </div>
    </div>

    <!-- Dashboard Nav -->
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item active"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>my-enquiries.php" class="dash-nav-item"><i class="fas fa-envelope"></i> Enquiries</a>
        <a href="<?= SITE_URL ?>my-favourites.php" class="dash-nav-item"><i class="fas fa-heart"></i> Saved</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item"><i class="fas fa-user"></i> Profile</a>
        <a href="<?= SITE_URL ?>logout-user.php" class="dash-nav-item" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="row">
        <!-- Recent Properties -->
        <div class="col-lg-7">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5>Recent Properties</h5>
                    <a href="<?= SITE_URL ?>my-properties.php">View All</a>
                </div>
                <?php if (empty($recentProperties)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-home" style="font-size:30px;opacity:.3;"></i>
                    <p class="mt-2">No properties yet. <a href="<?= SITE_URL ?>add-property.php">Add your first property</a></p>
                </div>
                <?php else: ?>
                <div class="dash-table-wrap">
                <table class="dash-table">
                    <thead><tr><th>Property</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentProperties as $p): ?>
                    <tr>
                        <td>
                            <strong style="font-size:13px;"><?= htmlspecialchars(mb_substr($p['title'], 0, 40)) ?></strong>
                            <br><small class="text-muted"><?= htmlspecialchars(($p['area'] ?? '') . ($p['city'] ? ', ' . $p['city'] : '')) ?></small>
                        </td>
                        <td>
                            <span class="dash-status dash-status-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span>
                        </td>
                        <td><small><?= date('M d, Y', strtotime($p['created_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Enquiries -->
        <div class="col-lg-5">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h5>Recent Enquiries</h5>
                    <a href="<?= SITE_URL ?>my-enquiries.php">View All</a>
                </div>
                <?php if (empty($recentEnquiries)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-envelope" style="font-size:30px;opacity:.3;"></i>
                    <p class="mt-2">No enquiries yet.</p>
                </div>
                <?php else: ?>
                <?php foreach ($recentEnquiries as $e): ?>
                <div class="dash-enquiry-item">
                    <div class="dash-enq-avatar"><?= strtoupper(substr($e['name'], 0, 1)) ?></div>
                    <div>
                        <strong style="font-size:13px;"><?= htmlspecialchars($e['name']) ?></strong>
                        <br><small class="text-muted"><?= htmlspecialchars(mb_substr($e['prop_title'], 0, 30)) ?> · <?= date('M d', strtotime($e['created_at'])) ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
