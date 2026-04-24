<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

$flash = get_user_flash();
$filter = $_GET['status'] ?? '';
$where  = "p.user_id = ? AND p.is_deleted=0";
$params = [current_user_id()];

if ($filter && in_array($filter, ['pending','approved','rejected'])) {
    $where .= " AND p.status = ?";
    $params[] = $filter;
}

$stmt = $pdo->prepare("SELECT p.*, l.city, l.area FROM properties p LEFT JOIN locations l ON p.location_id = l.id WHERE $where ORDER BY p.created_at DESC");
$stmt->execute($params);
$properties = $stmt->fetchAll();

// Counts
$cStmt = $pdo->prepare("SELECT status, COUNT(*) as cnt FROM properties WHERE user_id = ? AND is_deleted=0 GROUP BY status");
$cStmt->execute([current_user_id()]);
$counts = ['pending'=>0,'approved'=>0,'rejected'=>0];
while ($r = $cStmt->fetch()) $counts[$r['status']] = (int)$r['cnt'];

$pageTitle   = 'My Properties | MakaanDekho';
$pageNoIndex = true;
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item active"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>my-enquiries.php" class="dash-nav-item"><i class="fas fa-envelope"></i> Enquiries</a>
        <a href="<?= SITE_URL ?>my-favourites.php" class="dash-nav-item"><i class="fas fa-heart"></i> Saved</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item"><i class="fas fa-user"></i> Profile</a>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?>" style="border-radius:10px;">
        <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?> me-1"></i>
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <div class="dash-card">
        <div class="dash-card-header">
            <h5>My Properties (<?= count($properties) ?>)</h5>
            <a href="<?= SITE_URL ?>add-property.php" class="btn btn-primary btn-sm" style="border-radius:6px;font-size:12px;"><i class="fas fa-plus me-1"></i>Add New</a>
        </div>

        <!-- Status tabs -->
        <div class="dash-tabs">
            <a href="?status=" class="dash-tab <?= !$filter?'active':'' ?>">All (<?= array_sum($counts) ?>)</a>
            <a href="?status=approved" class="dash-tab <?= $filter==='approved'?'active':'' ?>">Approved (<?= $counts['approved'] ?>)</a>
            <a href="?status=pending" class="dash-tab <?= $filter==='pending'?'active':'' ?>">Pending (<?= $counts['pending'] ?>)</a>
            <a href="?status=rejected" class="dash-tab <?= $filter==='rejected'?'active':'' ?>">Rejected (<?= $counts['rejected'] ?>)</a>
        </div>

        <?php if (empty($properties)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-home" style="font-size:40px;opacity:.3;"></i>
            <p class="mt-2">No properties found. <a href="<?= SITE_URL ?>add-property.php">Add your first property</a></p>
        </div>
        <?php else: ?>
        <?php foreach ($properties as $p):
            $thumb = !empty($p['featured_image']) ? UPLOAD_URL.'properties/'.$p['featured_image'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=300&q=60';
        ?>
        <div class="my-prop-card">
            <div class="my-prop-thumb">
                <img src="<?= htmlspecialchars($thumb) ?>" alt="">
            </div>
            <div class="my-prop-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5><?= htmlspecialchars($p['title']) ?></h5>
                        <p class="text-muted" style="font-size:13px;"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars(($p['area']??'').($p['city']?', '.$p['city']:'')) ?></p>
                    </div>
                    <span class="dash-status dash-status-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span>
                </div>
                <div class="d-flex gap-3 mt-2" style="font-size:13px;color:#666;">
                    <?php if($p['bedrooms']): ?><span><i class="fas fa-bed me-1"></i><?= $p['bedrooms'] ?> BHK</span><?php endif; ?>
                    <?php if($p['area_sqft']): ?><span><i class="fas fa-ruler-combined me-1"></i><?= number_format($p['area_sqft']) ?> sqft</span><?php endif; ?>
                    <span><i class="fas fa-eye me-1"></i><?= $p['views'] ?? 0 ?> views</span>
                    <span><i class="far fa-calendar me-1"></i><?= date('M d, Y', strtotime($p['created_at'])) ?></span>
                </div>
                <?php if ($p['status'] === 'rejected' && !empty($p['rejection_reason'])): ?>
                <div class="mt-2 p-2" style="background:#fef2f2;border-radius:6px;font-size:12px;color:#991b1b;">
                    <i class="fas fa-exclamation-triangle me-1"></i><strong>Reason:</strong> <?= htmlspecialchars($p['rejection_reason']) ?>
                </div>
                <?php endif; ?>
                <div class="my-prop-price mt-2">
                    <?php
                    $pr = $p['price'] ?? 0;
                    if ($pr >= 10000000) echo '₹'.number_format($pr/10000000,2).' Cr';
                    elseif ($pr >= 100000) echo '₹'.number_format($pr/100000,2).' Lac';
                    else echo '₹'.number_format($pr);
                    ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
