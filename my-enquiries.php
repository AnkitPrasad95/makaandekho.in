<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

$stmt = $pdo->prepare("
    SELECT e.*, p.title as prop_title, p.slug as prop_slug
    FROM enquiries e
    JOIN properties p ON e.property_id = p.id
    WHERE p.user_id = ? AND e.is_deleted=0 AND p.is_deleted=0
    ORDER BY e.created_at DESC
");
$stmt->execute([current_user_id()]);
$enquiries = $stmt->fetchAll();

$pageTitle = 'My Enquiries | MakaanDekho';
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>my-enquiries.php" class="dash-nav-item active"><i class="fas fa-envelope"></i> Enquiries</a>
        <a href="<?= SITE_URL ?>my-favourites.php" class="dash-nav-item"><i class="fas fa-heart"></i> Saved</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item"><i class="fas fa-user"></i> Profile</a>
    </div>

    <div class="dash-card">
        <div class="dash-card-header"><h5>Enquiries Received (<?= count($enquiries) ?>)</h5></div>
        <?php if (empty($enquiries)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-envelope-open" style="font-size:40px;opacity:.3;"></i>
            <p class="mt-2">No enquiries received yet.</p>
        </div>
        <?php else: ?>
        <div class="dash-table-wrap">
        <table class="dash-table">
            <thead><tr><th>From</th><th>Property</th><th>Phone</th><th>Message</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($enquiries as $e): ?>
            <tr>
                <td><strong style="font-size:13px;"><?= htmlspecialchars($e['name']) ?></strong><br><small><?= htmlspecialchars($e['email']) ?></small></td>
                <td><a href="<?= SITE_URL ?>property-detail.php?slug=<?= htmlspecialchars($e['prop_slug']) ?>" style="font-size:13px;color:var(--primary);"><?= htmlspecialchars(mb_substr($e['prop_title'],0,30)) ?></a></td>
                <td><a href="tel:<?= $e['phone'] ?>" style="font-size:13px;"><?= htmlspecialchars($e['phone']) ?></a></td>
                <td style="font-size:12px;max-width:200px;"><?= htmlspecialchars(mb_substr($e['message']??'',0,60)) ?></td>
                <td><small><?= date('M d, Y', strtotime($e['created_at'])) ?></small></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
