<?php
require_once __DIR__ . '/includes/db.php';

$citySlug = $_GET['city'] ?? '';
if (!$citySlug) { header('Location: ' . SITE_URL . 'properties.php'); exit; }

// Find location by slug
$locStmt = $pdo->prepare("SELECT * FROM locations WHERE slug = ? AND is_deleted=0 LIMIT 1");
$locStmt->execute([$citySlug]);
$location = $locStmt->fetch();

if (!$location) {
    // Try matching by city name
    $slug = str_replace('-', ' ', $citySlug);
    $locStmt = $pdo->prepare("SELECT * FROM locations WHERE LOWER(city) = LOWER(?) AND is_deleted=0 LIMIT 1");
    $locStmt->execute([$slug]);
    $location = $locStmt->fetch();
}

if (!$location) {
    include __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center"><h3>Location Not Found</h3><a href="'.SITE_URL.'properties.php" class="btn btn-primary mt-3">Browse All Properties</a></div>';
    include __DIR__ . '/includes/footer.php'; exit;
}

// Properties in this location
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM properties WHERE status='approved' AND is_deleted=0 AND location_id=?");
$countStmt->execute([$location['id']]);
$total = (int)$countStmt->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));

$propStmt = $pdo->prepare("
    SELECT p.*, l.city, l.area FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status='approved' AND p.is_deleted=0 AND p.location_id=?
    ORDER BY p.featured DESC, p.created_at DESC
    LIMIT $per_page OFFSET $offset
");
$propStmt->execute([$location['id']]);
$properties = $propStmt->fetchAll();

$cityName = $location['city'] . ($location['area'] ? ' - ' . $location['area'] : '') . ', ' . $location['state'];

$pageTitle = "Properties in $cityName | MakaanDekho";
$pageDesc  = "Browse $total verified properties in $cityName. Find apartments, villas, plots and commercial properties.";
include __DIR__ . '/includes/header.php';

function maFmtPrice($p) {
    if (!$p) return '₹N/A';
    if ($p >= 10000000) return '₹'.number_format($p/10000000,2).' Cr';
    if ($p >= 100000) return '₹'.number_format($p/100000,2).' Lac';
    return '₹'.number_format($p);
}
?>

<!-- Market Area Page -->
<div class="page-banner">
    <div class="container">
        <h1>Properties in <?= htmlspecialchars($cityName) ?></h1>
        <p><?= $total ?> verified properties available</p>
    </div>
</div>

<section class="static-page">
<div class="container">
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": "Properties in <?= htmlspecialchars($cityName) ?>",
        "numberOfItems": <?= $total ?>,
        "itemListElement": [
            <?php foreach ($properties as $i => $p): ?>
            <?= $i > 0 ? ',' : '' ?>
            {
                "@type": "ListItem",
                "position": <?= $i + 1 ?>,
                "item": {
                    "@type": "RealEstateListing",
                    "name": <?= json_encode($p['title']) ?>,
                    "url": "<?= SITE_URL ?>property/<?= $p['slug'] ?>",
                    "price": "<?= $p['price'] ?>",
                    "priceCurrency": "INR"
                }
            }
            <?php endforeach; ?>
        ]
    }
    </script>

    <?php if (empty($properties)): ?>
    <div class="no-results">
        <i class="fas fa-map-marker-alt"></i>
        <h4>No Properties Found in <?= htmlspecialchars($location['city']) ?></h4>
        <p>Properties will appear here once listed and verified.</p>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($properties as $p):
            $thumb = !empty($p['featured_image']) ? UPLOAD_URL.'properties/'.$p['featured_image'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&q=75';
        ?>
        <div class="col-md-6 col-lg-4">
            <a href="<?= SITE_URL ?>property/<?= htmlspecialchars($p['slug']) ?>" class="listing-card-link">
            <div class="property-card">
                <div class="card-thumb">
                    <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    <div class="badges">
                        <?php if (!empty($p['featured'])): ?><span class="badge-tag badge-featured">Verified</span><?php endif; ?>
                        <span class="badge-tag badge-sale">FOR <?= strtoupper($p['listing_type'] ?? 'SALE') ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <h5><?= htmlspecialchars($p['title']) ?></h5>
                    <p class="location"><i class="fas fa-map-marker-alt" style="font-size:11px;color:var(--primary);"></i> <?= htmlspecialchars(($p['area']??'').($p['city']?', '.$p['city']:'')) ?></p>
                    <div class="property-meta">
                        <?php if($p['bedrooms']): ?><span><i class="fas fa-bed"></i> <?= $p['bedrooms'] ?> BHK</span><?php endif; ?>
                        <?php if($p['area_sqft']): ?><span><i class="fas fa-ruler-combined"></i> <?= number_format($p['area_sqft']) ?> sqft</span><?php endif; ?>
                    </div>
                    <div class="card-footer-row"><div class="price"><?= maFmtPrice($p['price']) ?></div></div>
                </div>
            </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav class="pagination-wrap">
        <ul class="pagination">
            <?php for ($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?city=<?= urlencode($citySlug) ?>&page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
