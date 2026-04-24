<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

// Remove favourite
if (!empty($_GET['remove']) && is_numeric($_GET['remove'])) {
    $pdo->prepare("UPDATE favourites SET is_deleted=1, deleted_at=NOW() WHERE user_id = ? AND property_id = ?")
        ->execute([current_user_id(), (int)$_GET['remove']]);
    header('Location: ' . SITE_URL . 'my-favourites.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, l.city, l.area, f.created_at as saved_at
    FROM favourites f
    JOIN properties p ON f.property_id = p.id
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE f.user_id = ? AND f.is_deleted=0 AND p.is_deleted=0
    ORDER BY f.created_at DESC
");
$stmt->execute([current_user_id()]);
$favourites = $stmt->fetchAll();

$pageTitle   = 'Saved Properties | MakaanDekho';
$pageNoIndex = true;
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>my-enquiries.php" class="dash-nav-item"><i class="fas fa-envelope"></i> Enquiries</a>
        <a href="<?= SITE_URL ?>my-favourites.php" class="dash-nav-item active"><i class="fas fa-heart"></i> Saved</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item"><i class="fas fa-user"></i> Profile</a>
    </div>

    <div class="dash-card">
        <div class="dash-card-header"><h5>Saved Properties (<?= count($favourites) ?>)</h5></div>
        <?php if (empty($favourites)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-heart" style="font-size:40px;opacity:.3;"></i>
            <p class="mt-2">No saved properties. Browse and save properties you like.</p>
            <a href="<?= SITE_URL ?>properties.php" class="btn btn-primary btn-sm" style="border-radius:6px;">Browse Properties</a>
        </div>
        <?php else: ?>
        <?php foreach ($favourites as $p):
            $thumb = !empty($p['featured_image']) ? UPLOAD_URL.'properties/'.$p['featured_image'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=300&q=60';
        ?>
        <div class="my-prop-card">
            <div class="my-prop-thumb"><img src="<?= htmlspecialchars($thumb) ?>" alt=""></div>
            <div class="my-prop-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <a href="<?= SITE_URL ?>property-detail.php?slug=<?= htmlspecialchars($p['slug']) ?>" style="font-size:15px;font-weight:600;color:var(--text-dark);"><?= htmlspecialchars($p['title']) ?></a>
                        <p class="text-muted" style="font-size:13px;"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars(($p['area']??'').($p['city']?', '.$p['city']:'')) ?></p>
                    </div>
                    <a href="?remove=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm" style="border-radius:6px;font-size:11px;" onclick="return confirm('Remove from saved?')">
                        <i class="fas fa-heart-broken"></i> Remove
                    </a>
                </div>
                <div class="my-prop-price mt-1">
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
