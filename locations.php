<?php
require_once __DIR__ . '/includes/db.php';

// Fetch all locations with property count, grouped by state
$locStmt = $pdo->query("
    SELECT l.*, COUNT(p.id) AS prop_count
    FROM locations l
    LEFT JOIN properties p ON p.location_id = l.id AND p.status='approved' AND p.is_deleted=0
    WHERE l.is_deleted=0
    GROUP BY l.id
    ORDER BY l.state ASC, l.city ASC, l.area ASC
");
$allLocations = $locStmt->fetchAll();

// Group by state
$byState = [];
foreach ($allLocations as $loc) {
    $byState[$loc['state']][] = $loc;
}

$totalLocations = count($allLocations);
$totalStates = count($byState);

// ---- SEO (cms_pages → settings → hardcoded fallback) ----
$cmsPage = $pdo->prepare("SELECT meta_title, meta_description, meta_keywords FROM cms_pages WHERE page_slug='locations' AND is_deleted=0 LIMIT 1");
$cmsPage->execute();
$cmsPage = $cmsPage->fetch() ?: [];
$pageTitle     = $cmsPage['meta_title']       ?: 'All Locations – Properties Across India | MakaanDekho';
$pageDesc      = $cmsPage['meta_description'] ?: ($settings['meta_description'] ?? "Browse properties in $totalLocations+ locations across $totalStates states in India. Find apartments, villas, plots and commercial spaces in your preferred city.");
$pageKeywords  = $cmsPage['meta_keywords']    ?: ($settings['meta_keywords']    ?? 'property locations India, real estate cities, buy property India, rent property India, MakaanDekho locations');
$pageCanonical = SITE_URL . 'locations.php';
$pageOgType    = 'website';

include __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
    <div class="container">
        <h1>All Locations</h1>
        <p>Browse <?= $totalLocations ?> locations across <?= $totalStates ?> states</p>
    </div>
</div>

<section class="static-page">
<div class="container">

    <?php foreach ($byState as $state => $locations): ?>
    <div class="loc-state-block">
        <h3 class="loc-state-title"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($state) ?></h3>
        <div class="row g-3">
            <?php foreach ($locations as $loc): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="<?= SITE_URL . htmlspecialchars($loc['slug']) ?>" class="loc-card">
                    <div class="loc-card-body">
                        <h5><?= htmlspecialchars($loc['city']) ?></h5>
                        <?php if (!empty($loc['area'])): ?>
                        <p class="loc-area"><?= htmlspecialchars($loc['area']) ?></p>
                        <?php endif; ?>
                        <span class="loc-count"><?= $loc['prop_count'] ?> <?= $loc['prop_count'] == 1 ? 'Property' : 'Properties' ?></span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>
</section>

<style>
.loc-state-block { margin-bottom: 35px; }
.loc-state-title {
    font-size: 18px; font-weight: 700; color: #1a2332;
    border-bottom: 2px solid #0d9488; padding-bottom: 10px; margin-bottom: 18px;
}
.loc-state-title i { color: #0d9488; margin-right: 8px; font-size: 16px; }
.loc-card {
    display: block; background: #fff; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 18px; text-decoration: none;
    transition: all 0.2s ease; height: 100%;
}
.loc-card:hover {
    border-color: #0d9488; box-shadow: 0 4px 12px rgba(13,148,136,.12);
    transform: translateY(-2px);
}
.loc-card-body h5 {
    font-size: 15px; font-weight: 700; color: #1a2332; margin: 0 0 4px;
}
.loc-card:hover h5 { color: #0d9488; }
.loc-area { font-size: 13px; color: #6b7280; margin: 0 0 8px; }
.loc-count {
    display: inline-block; font-size: 11px; font-weight: 600;
    color: #0d9488; background: #f0fdfa; padding: 3px 10px;
    border-radius: 20px;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
