<?php
require_once __DIR__ . '/includes/db.php';

// ---- Fetch data for homepage ----

// Site settings shortcuts
$siteName  = $settings['site_name'] ?? 'MakaanDekho';
$sitePhone = $settings['phone'] ?? $settings['whatsapp_number'] ?? '';
$siteEmail = $settings['email'] ?? '';

// Locations for "Explore Property Types"
$stmtLoc = $pdo->query("SELECT l.*, COUNT(p.id) as prop_count
    FROM locations l
    LEFT JOIN properties p ON p.location_id = l.id AND p.status='approved' AND p.is_deleted=0
    WHERE l.is_deleted=0
    GROUP BY l.id
    ORDER BY prop_count DESC, l.city ASC LIMIT 8");
$locations = $stmtLoc->fetchAll();

// Trending properties (approved + is_trending)
$stmtTrending = $pdo->prepare("
    SELECT p.*, l.city, l.area,
           (SELECT pi.image FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary=1 AND pi.is_deleted=0 LIMIT 1) as primary_image
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_trending = 1 AND p.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtTrending->execute();
$trendingProperties = $stmtTrending->fetchAll();

// Recommended properties
$stmtRecommended = $pdo->prepare("
    SELECT p.*, l.city, l.area,
           (SELECT pi.image FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary=1 AND pi.is_deleted=0 LIMIT 1) as primary_image
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_recommended = 1 AND p.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtRecommended->execute();
$recommendedProperties = $stmtRecommended->fetchAll();

// High demand = featured properties
$stmtHighDemand = $pdo->prepare("
    SELECT p.*, l.city, l.area,
           (SELECT pi.image FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary=1 AND pi.is_deleted=0 LIMIT 1) as primary_image
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.featured = 1 AND p.is_deleted=0
    ORDER BY p.views DESC, p.created_at DESC LIMIT 9
");
$stmtHighDemand->execute();
$highDemandProperties = $stmtHighDemand->fetchAll();

// Newly launched = latest approved
$stmtNew = $pdo->prepare("
    SELECT p.*, l.city, l.area,
           (SELECT pi.image FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary=1 AND pi.is_deleted=0 LIMIT 1) as primary_image
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtNew->execute();
$newProperties = $stmtNew->fetchAll();

// Blogs (latest 6 published)
$stmtBlogs = $pdo->prepare("SELECT * FROM blogs WHERE status = 'published' AND is_deleted=0 ORDER BY created_at DESC LIMIT 6");
$stmtBlogs->execute();
$blogs = $stmtBlogs->fetchAll();

// Property types for search dropdown
$propertyTypes = ['apartment' => 'Apartment', 'villa' => 'Villa', 'plot' => 'Plot', 'commercial' => 'Commercial', 'office' => 'Office'];

// Testimonials
$stmtTestimonials = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 AND is_deleted=0 ORDER BY sort_order ASC, id DESC LIMIT 6");
$testimonials = $stmtTestimonials->fetchAll();

// Banners (for hero slider)
$stmtBanners = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND is_deleted=0 ORDER BY sort_order ASC LIMIT 5");
$banners = $stmtBanners->fetchAll();

// Stats counts
$totalProperties = $pdo->query("SELECT COUNT(*) FROM properties WHERE status='approved' AND is_deleted=0")->fetchColumn();
$totalUsers      = $pdo->query("SELECT COUNT(*) FROM users WHERE status='active' AND is_deleted=0")->fetchColumn();
$totalLocations  = $pdo->query("SELECT COUNT(DISTINCT city) FROM locations WHERE is_deleted=0")->fetchColumn();

// Top developers/builders
$stmtDevs = $pdo->query("SELECT u.id, u.name, COUNT(p.id) as prop_count
    FROM users u
    LEFT JOIN properties p ON p.user_id = u.id AND p.status='approved' AND p.is_deleted=0
    WHERE u.role = 'builder' AND u.status='active' AND u.is_deleted=0
    GROUP BY u.id
    ORDER BY prop_count DESC LIMIT 6");
$topDevelopers = $stmtDevs->fetchAll();

// Top agents
$stmtAgents = $pdo->query("SELECT u.id, u.name, u.role, u.city, u.state, COUNT(p.id) as prop_count
    FROM users u
    LEFT JOIN properties p ON p.user_id = u.id AND p.status='approved' AND p.is_deleted=0
    WHERE u.role IN ('agent','builder') AND u.status='active' AND u.is_deleted=0
    GROUP BY u.id
    ORDER BY prop_count DESC LIMIT 6");
$topAgents = $stmtAgents->fetchAll();

// ---- SEO ----
$pageTitle    = $settings['meta_title'] ?? ($siteName . ' – Find Your Dream Home in India | Properties for Sale & Rent');
$pageDesc     = $settings['meta_description'] ?? ('Search ' . number_format($totalProperties) . '+ verified properties for sale and rent across ' . $totalLocations . '+ cities in India. Apartments, villas, plots, commercial spaces & more on ' . $siteName . '.');
$pageKeywords = $settings['meta_keywords'] ?? 'real estate India, properties for sale, properties for rent, apartments, villas, flats, plots, commercial property, MakaanDekho';
$pageCanonical = SITE_URL;
$pageOgType    = 'website';
if (!empty($banners[0]['image']) && file_exists(UPLOAD_DIR . 'banners/' . $banners[0]['image'])) {
    $pageOgImage = UPLOAD_URL . 'banners/' . $banners[0]['image'];
}

// Preload hero banner image (LCP) for faster paint
if (!empty($banners[0]['image']) && file_exists(UPLOAD_DIR . 'banners/' . $banners[0]['image'])) {
    $pagePreloadImage = UPLOAD_URL . 'banners/' . $banners[0]['image'];
} else {
    $pagePreloadImage = SITE_URL . 'assets/img/banner.jpg';
}

include __DIR__ . '/includes/header.php';
?>

<!-- ========== HERO SECTION ========== -->
<section class="d-flex flex-column p-0">
    <?php
        $bannerBg = 'assets/img/banner.jpg';
        if (!empty($banners[0]['image']) && file_exists(UPLOAD_DIR . 'banners/' . $banners[0]['image'])) {
            $bannerBg = UPLOAD_URL . 'banners/' . htmlspecialchars($banners[0]['image']);
        }
    ?>
    <div style="background-image: url('<?= $bannerBg ?>')" class="bg-cover d-flex align-items-center custom-vh-100">
        <div class="container pt-20 pb-15" data-animate="zoomIn">
            <p class="text-white fs-md-22 fs-18 font-weight-500 letter-spacing-367 mb-1 text-center text-uppercase">
                <?= htmlspecialchars($banners[0]['subtitle'] ?? 'Let us guide your home') ?>
            </p>
            <h2 class="text-white display-2 text-center mb-sm-8 mb-8">
                <?= htmlspecialchars($banners[0]['title'] ?? 'Find Your Dream Home') ?>
            </h2>

            <!-- Desktop Search Form -->
            <form class="property-search py-lg-0 z-index-2 position-relative d-none d-lg-block" action="<?= SITE_URL ?>properties.php" method="GET">
                <input type="hidden" name="status" value="for-sale" id="listingType">

                <!-- Tabs -->
                <ul class="nav nav-pills property-search-status-tab">
                    <li class="nav-item bg-secondary rounded-top">
                        <a href="#" class="nav-link btn shadow-none rounded-bottom-0 text-white text-btn-focus-secondary text-uppercase d-flex align-items-center fs-13 rounded-bottom-0 bg-active-white text-active-secondary letter-spacing-087 flex-md-1 px-4 py-2 active" data-toggle="pill" data-value="for-sale"
                           onclick="document.getElementById('listingType').value='for-sale'; setActiveTab(this)">
                            <svg class="icon icon-villa fs-22 mr-2"><use xlink:href="#icon-villa"></use></svg>
                            For Sale
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link btn shadow-none rounded-bottom-0 text-white text-btn-focus-secondary text-uppercase d-flex align-items-center fs-13 rounded-bottom-0 bg-active-white text-active-secondary letter-spacing-087 flex-md-1 px-4 py-2" data-toggle="pill" data-value="for-rent"
                           onclick="document.getElementById('listingType').value='for-rent'; setActiveTab(this)">
                            <svg class="icon icon-building fs-22 mr-2"><use xlink:href="#icon-building"></use></svg>
                            For Rent
                        </a>
                    </li>
                </ul>

                <!-- Search Fields -->
                <div class="bg-white px-6 py-6 rounded-bottom rounded-top-right pb-6 pb-lg-0">
                    <div class="row align-items-center" id="accordion-4">
                        <!-- Home Type -->
                        <div class="col-md-6 col-lg-4 col-xl-4 pt-6 pt-lg-0 order-1">
                            <label class="text-uppercase font-weight-500 letter-spacing-093 mb-1">Home Type</label>
                            <select class="form-control selectpicker bg-transparent border-bottom rounded-0 border-color-input" name="type" title="Select">
                                <?php foreach ($propertyTypes as $val => $label): ?>
                                    <option value="<?= $val ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Keyword Search -->
                        <div class="col-md-6 col-lg-4 col-xl-5 pt-6 pt-lg-0 order-2">
                            <label class="text-uppercase font-weight-500 letter-spacing-093">Search</label>
                            <div class="position-relative">
                                <input type="text" name="search" id="heroSearch" class="form-control bg-transparent shadow-none border-top-0 border-right-0 border-left-0 border-bottom rounded-0 h-24 lh-17 pl-0 pr-4 font-weight-600 border-color-input placeholder-muted" placeholder="Search project, locality or builder..." autocomplete="off">
                                <i class="far fa-search position-absolute pos-fixed-right-center pr-0 fs-18 mt-n3"></i>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-sm pt-6 pt-lg-0 order-sm-4 order-5">
                            <button type="submit" class="btn btn-primary shadow-none fs-16 font-weight-600 w-100 py-lg-2 lh-213">Search</button>
                        </div>

                        <!-- Amenities / Features -->
                        <div class="col-12 pt-4 pb-sm-4 order-sm-5 order-4">
                            <div class="row pt-2">
                                <?php
                                $searchFeatures = [
                                    'ready_to_move'      => 'Ready to Move',
                                    'under_construction'  => 'Under Construction',
                                    'new_launch'          => 'New Launch',
                                    'verified'            => 'Verified Only',
                                ];
                                $i = 1;
                                foreach($searchFeatures as $val => $label):
                                ?>
                                    <div class="col-sm-6 col-md-3 col-lg-3 py-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="check<?= $i ?>-4" name="features[]" value="<?= $val ?>">
                                            <label class="custom-control-label" for="check<?= $i ?>-4"><?= $label ?></label>
                                        </div>
                                    </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- ========== EXPLORE PROPERTY TYPES (Locations) ========== -->
<section>
    <div class="container">
        <div class="section-heading">
            <h2>Explore Property Types</h2>
            <p>Browse properties by type and find your perfect match</p>
        </div>
        <div class="swiper location-slider">
            <div class="swiper-wrapper">
                <?php
                $typeCards = [
                    ['type' => 'apartment', 'label' => 'Apartments', 'icon' => 'fas fa-building',        'img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&q=75'],
                    ['type' => 'villa',     'label' => 'Villas',     'icon' => 'fas fa-home',             'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400&q=75'],
                    ['type' => 'plot',      'label' => 'Plots & Land','icon' => 'fas fa-seedling',        'img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&q=75'],
                    ['type' => 'commercial','label' => 'Commercial', 'icon' => 'fas fa-store',            'img' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&q=75'],
                    ['type' => 'office',    'label' => 'Office Space','icon' => 'fas fa-briefcase',       'img' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=400&q=75'],
                ];
                // Get counts per type
                $typeCounts = [];
                $tcStmt = $pdo->query("SELECT property_type, COUNT(*) as cnt FROM properties WHERE status='approved' AND is_deleted=0 GROUP BY property_type");
                foreach ($tcStmt as $tc) $typeCounts[$tc['property_type']] = $tc['cnt'];

                foreach ($typeCards as $tc):
                    $count = $typeCounts[$tc['type']] ?? 0;
                ?>
                <div class="swiper-slide">
                    <a href="<?= SITE_URL ?>properties.php?type=<?= $tc['type'] ?>">
                        <div class="location-card">
                            <img src="<?= $tc['img'] ?>" alt="<?= $tc['label'] ?>" loading="lazy" decoding="async">
                            <div class="overlay">
                                <h4><?= $tc['label'] ?></h4>
                                <p style="color:#fff;font-size:13px;margin:0;"><?= $count ?> Properties</p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>

                <?php
                // Also add top cities as location cards
                foreach ($locations as $loc):
                ?>
                <div class="swiper-slide">
                    <a href="<?= SITE_URL ?>properties.php?city=<?= urlencode($loc['city']) ?>">
                        <div class="location-card">
                            <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=400&q=75" alt="<?= htmlspecialchars($loc['city']) ?>" loading="lazy" decoding="async">
                            <div class="overlay">
                                <h4><?= htmlspecialchars($loc['city']) ?></h4>
                                <p style="color:#fff;font-size:13px;margin:0;"><?= $loc['prop_count'] ?> Properties</p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev loc-prev"></div>
            <div class="swiper-button-next loc-next"></div>
        </div>
    </div>
</section>

<!-- ========== STATS COUNTER ========== -->
<section class="about-property section-white">
    <div class="container">
        <h2>Why Choose <?= htmlspecialchars($siteName) ?>?</h2>
        <div class="row g-3">
            <?php
            $stats = [
                ['icon' => 'fas fa-building',       'label' => 'TOTAL PROPERTIES', 'value' => number_format($totalProperties) . '+'],
                ['icon' => 'fas fa-users',           'label' => 'HAPPY USERS',      'value' => number_format($totalUsers) . '+'],
                ['icon' => 'fas fa-map-marker-alt',  'label' => 'CITIES COVERED',   'value' => $totalLocations . '+'],
                ['icon' => 'fas fa-check-circle',    'label' => 'VERIFIED LISTINGS','value' => number_format($totalProperties)],
                ['icon' => 'fas fa-home',            'label' => 'APARTMENTS',       'value' => number_format($typeCounts['apartment'] ?? 0)],
                ['icon' => 'fas fa-store',           'label' => 'COMMERCIAL',       'value' => number_format($typeCounts['commercial'] ?? 0)],
                ['icon' => 'fas fa-seedling',        'label' => 'PLOTS & LAND',     'value' => number_format($typeCounts['plot'] ?? 0)],
                ['icon' => 'fas fa-crown',           'label' => 'LUXURY VILLAS',    'value' => number_format($typeCounts['villa'] ?? 0)],
            ];
            foreach ($stats as $stat):
            ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="<?= $stat['icon'] ?>"></i></div>
                    <div class="stat-info">
                        <div class="stat-label"><?= $stat['label'] ?></div>
                        <div class="stat-value"><?= $stat['value'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
// ---- Helper: Render a property slider section ----
function renderPropertySection($title, $subtitle, $properties, $siteUrl, $uploadUrl, $showSeeAll = true) {
    if (empty($properties)) return;
    ?>
    <section>
        <div class="container">
            <div class="section-heading d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h2><?= htmlspecialchars($title) ?></h2>
                    <p><?= htmlspecialchars($subtitle) ?></p>
                </div>
                <?php if ($showSeeAll): ?>
                <a href="<?= $siteUrl ?>properties.php" class="see-all">See all properties <i class="fas fa-arrow-right ms-1"></i></a>
                <?php endif; ?>
            </div>
            <div class="swiper property-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($properties as $prop): ?>
                    <div class="swiper-slide">
                        <div class="property-card">
                            <div class="card-thumb">
                                <?php
                                $thumb = '';
                                if (!empty($prop['primary_image'])) {
                                    $thumb = $uploadUrl . 'properties/' . $prop['primary_image'];
                                } elseif (!empty($prop['featured_image'])) {
                                    $thumb = $uploadUrl . 'properties/' . $prop['featured_image'];
                                } else {
                                    $thumb = 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&q=75';
                                }
                                ?>
                                <a href="<?= $siteUrl ?>property-detail.php?slug=<?= htmlspecialchars($prop['slug'] ?? '') ?>">
                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($prop['title']) ?>" loading="lazy" decoding="async">
                                </a>
                                <div class="badges">
                                    <?php if (!empty($prop['featured'])): ?>
                                    <span class="badge-tag badge-featured">Featured</span>
                                    <?php endif; ?>
                                    <span class="badge-tag <?= ($prop['listing_type'] ?? 'sale') === 'rent' ? 'badge-rent' : 'badge-sale' ?>">
                                        FOR <?= strtoupper($prop['listing_type'] ?? 'SALE') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5><a href="<?= $siteUrl ?>property-detail.php?slug=<?= htmlspecialchars($prop['slug'] ?? '') ?>" style="color:inherit;text-decoration:none;">
                                    <?= htmlspecialchars($prop['title']) ?>
                                </a></h5>
                                <p class="location">
                                    <i class="fas fa-map-marker-alt" style="color:#0d6efd;margin-right:4px;font-size:12px;"></i>
                                    <?= htmlspecialchars(($prop['area'] ?? '') . ($prop['area'] && $prop['city'] ? ', ' : '') . ($prop['city'] ?? '')) ?>
                                </p>
                                <div class="property-meta">
                                    <?php if (!empty($prop['bedrooms'])): ?>
                                    <span><i class="fas fa-bed"></i> <?= $prop['bedrooms'] ?> Br</span>
                                    <?php endif; ?>
                                    <?php if (!empty($prop['bathrooms'])): ?>
                                    <span><i class="fas fa-bath"></i> <?= $prop['bathrooms'] ?> Ba</span>
                                    <?php endif; ?>
                                    <?php if (!empty($prop['area_sqft'])): ?>
                                    <span><i class="fas fa-ruler-combined"></i> <?= number_format($prop['area_sqft']) ?> Sq.Ft</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer-row">
                                    <div class="price">
                                        <?php
                                        $price = $prop['price'] ?? 0;
                                        $priceType = $prop['price_type'] ?? 'total';
                                        if ($price >= 10000000) {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price / 10000000, 2) . ' Cr';
                                        } elseif ($price >= 100000) {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price / 100000, 2) . ' Lac';
                                        } else {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price);
                                        }
                                        if ($priceType === 'per_month') echo ' <small>/ month</small>';
                                        elseif ($priceType === 'per_sqft') echo ' <small>/ sq.ft</small>';
                                        ?>
                                    </div>
                                    <a href="<?= $siteUrl ?>property-detail.php?slug=<?= htmlspecialchars($prop['slug'] ?? '') ?>" class="arrow-link">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php
}
?>

<!-- ========== TRENDING PROPERTIES ========== -->
<?php renderPropertySection('Trending Properties', 'Most viewed properties this week', $trendingProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== RECOMMENDED ========== -->
<?php renderPropertySection('Recommended For You', 'Handpicked properties based on demand', $recommendedProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== HIGH DEMAND ========== -->
<?php renderPropertySection('High Demand Properties', 'Featured properties with most enquiries', $highDemandProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== NEWLY LAUNCHED ========== -->
<?php renderPropertySection('Newly Launched', 'Latest properties added on ' . htmlspecialchars($siteName), $newProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== TOP AGENTS & BUILDERS ========== -->
<?php if (!empty($topAgents)): ?>
<section class="section-gray">
    <div class="container">
        <div class="section-heading">
            <h2>Top Agents & Builders</h2>
            <p>Connect with verified real estate professionals</p>
        </div>
        <div class="row g-4">
            <?php
            $agentColors = ['#3b82f6','#f97316','#10b981','#8b5cf6','#ef4444','#06b6d4'];
            foreach ($topAgents as $idx => $ag):
                $color = $agentColors[$idx % count($agentColors)];
                $initial = strtoupper(substr($ag['name'], 0, 1));
            ?>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-white rounded shadow-sm h-100">
                    <div style="width:70px;height:70px;border-radius:50%;background:<?= $color ?>15;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <span style="font-size:24px;font-weight:800;color:<?= $color ?>"><?= $initial ?></span>
                    </div>
                    <h6 style="font-size:13px;font-weight:700;margin-bottom:4px;"><?= htmlspecialchars($ag['name']) ?></h6>
                    <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:<?= $ag['role'] === 'builder' ? '#fff7ed' : '#eff6ff' ?>;color:<?= $ag['role'] === 'builder' ? '#ea580c' : '#2563eb' ?>;">
                        <?= ucfirst($ag['role']) ?>
                    </span>
                    <?php if (!empty($ag['city'])): ?>
                    <p style="font-size:11px;color:#94a3b8;margin:6px 0 0;"><i class="fas fa-map-marker-alt" style="color:#3b82f6;font-size:10px;"></i> <?= htmlspecialchars($ag['city']) ?></p>
                    <?php endif; ?>
                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 8px;"><?= $ag['prop_count'] ?> Properties</p>
                    <a href="<?= SITE_URL ?>properties.php?agent=<?= $ag['id'] ?>" style="display:block;padding:6px;background:#2563eb;color:#fff;border-radius:8px;font-size:10px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:1px;">
                        <i class="fas fa-eye" style="font-size:9px;"></i> View Properties
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== FIND YOUR NEIGHBORHOOD ========== -->
<section class="neighborhood-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="neighborhood-img">
                    <img src="assets/img/single-image-02.png" alt="Find your neighborhood" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&q=80'">
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="content">
                    <h2>Find your<br>neighborhood</h2>
                    <p>Search properties by locality, city or project name across India</p>
                    <form class="neighborhood-search" action="<?= SITE_URL ?>properties.php" method="GET">
                        <input type="text" name="search" id="neighborhoodSearch" placeholder="Enter city, locality or project name" autocomplete="off">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES ========== -->
<section class="services-section bg-patten-04">
    <div class="container">
        <div class="top-text">
            <h2>We have the most listings and constant updates. So you'll never miss out.</h2>
        </div>
        <div class="row g-4">
            <?php
            $services = [
                ['icon' => 'fas fa-home',             'title' => 'Buy a Home',       'desc' => 'Browse ' . number_format($totalProperties) . '+ verified properties across ' . $totalLocations . '+ cities. Find your dream home today.'],
                ['icon' => 'fas fa-hand-holding-usd', 'title' => 'Sell Your Property','desc' => 'List your property for FREE and connect with thousands of genuine buyers. Quick and easy process.'],
                ['icon' => 'fas fa-key',              'title' => 'Rent a Home',       'desc' => 'Find affordable rental properties near you. Verified listings with no brokerage options available.'],
            ];
            foreach ($services as $svc):
            ?>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="icon"><i class="<?= $svc['icon'] ?>"></i></div>
                        </div>
                        <div class="col-md-9">
                            <h5><?= $svc['title'] ?></h5>
                            <p><?= $svc['desc'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== BLOG & NEWS ========== -->
<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <h3>BLOG & NEWS</h3>
                    <h2>Interesting Articles Updated Daily</h2>
                    <?php
                    // Show latest blog titles as sidebar links
                    if (!empty($blogs)):
                        foreach (array_slice($blogs, 0, 5) as $sb):
                    ?>
                    <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($sb['slug']) ?>" class="article-link">
                        <i class="fas fa-arrow-right"></i> <?= htmlspecialchars(mb_substr($sb['title'], 0, 50)) ?><?= mb_strlen($sb['title']) > 50 ? '...' : '' ?>
                    </a>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <a href="<?= SITE_URL ?>blogs.php" class="article-link"><i class="fas fa-arrow-right"></i> View all articles</a>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>blogs.php" class="btn btn-outline-primary btn-sm mt-3">View All Articles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row g-4">
                    <?php
                    if (!empty($blogs)):
                        foreach (array_slice($blogs, 0, 2) as $blog):
                            $blogImg = !empty($blog['featured_image'])
                                ? UPLOAD_URL . 'blogs/' . $blog['featured_image']
                                : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&q=75';
                    ?>
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-thumb hover-shine">
                                <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>">
                                    <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" loading="lazy" decoding="async">
                                </a>
                                <span class="blog-category"><?= htmlspecialchars($blog['category'] ?? 'News') ?></span>
                            </div>
                            <div class="blog-body">
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> <?= date('jS M, Y', strtotime($blog['created_at'])) ?></span>
                                    <span><i class="far fa-eye"></i> <?= $blog['views'] ?? 0 ?> views</span>
                                </div>
                                <h5><a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>" style="color:inherit;text-decoration:none;">
                                    <?= htmlspecialchars($blog['title']) ?>
                                </a></h5>
                                <p><?= htmlspecialchars(mb_substr($blog['short_description'] ?? '', 0, 120)) ?>...</p>
                                <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>" class="read-more">
                                    Read more <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No blog posts yet. Check back soon!</p>
                        <a href="<?= SITE_URL ?>blogs.php" class="btn btn-primary">View Blog</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CLIENT TESTIMONIALS ========== -->
<?php if (!empty($testimonials)): ?>
<section class="section-gray">
    <div class="container">
        <div class="section-heading text-center" style="text-align:center;">
            <h2 style="display:inline-block;">What Our Clients Say</h2>
            <p>Trusted by thousands of happy property buyers and sellers</p>
        </div>
        <div class="swiper testimonial-slider" style="padding-bottom:45px;">
            <div class="swiper-wrapper">
                <?php foreach ($testimonials as $t): ?>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                            <i class="fas fa-star" style="color:<?= $s <= $t['rating'] ? '#f59e0b' : '#ddd' ?>;font-size:14px;"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="testimonial-text"><?= htmlspecialchars($t['content']) ?></p>
                        <div class="testimonial-author">
                            <?php if (!empty($t['photo'])): ?>
                            <img src="<?= UPLOAD_URL ?>testimonials/<?= htmlspecialchars($t['photo']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" decoding="async">
                            <?php else: ?>
                            <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <div>
                                <strong><?= htmlspecialchars($t['name']) ?></strong>
                                <?php if (!empty($t['designation'])): ?><small><?= htmlspecialchars($t['designation']) ?></small><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== CTA CONTACT ========== -->
<section class="bg-single-image-02 bg-accent py-lg-13 py-11">
    <div class="container">
        <div class="row">
            <div class="col-ld-6 col-sm-7" data-animate="fadeInLeft">
                <div class="pl-6 border-4x border-left border-primary">
                    <h2 class="text-heading lh-15 fs-md-32 fs-25">For more information about our services,<span class="text-primary"> get in touch</span> with our expert consultants</h2>
                    <p class="lh-2 fs-md-15 mb-0"><?= number_format($totalProperties) ?> properties listed. <?= number_format($totalUsers) ?> registered users. Trusted by a growing community.</p>
                </div>
            </div>
            <div class="col-ld-6 col-sm-5 text-center mt-sm-0 mt-8" data-animate="fadeInRight">
                <i class="fa fa-phone fs-40 text-primary"></i>
                <p class="fs-13 font-weight-500 letter-spacing-173 text-uppercase lh-2 mt-3">Call for help now!</p>
                <p class="fs-md-42 fs-32 font-weight-600 text-secondary lh-1">
                    <?php if ($sitePhone): ?>
                        <a href="tel:+91<?= htmlspecialchars($sitePhone) ?>" style="color:inherit;text-decoration:none;">
                            <?= htmlspecialchars($sitePhone) ?>
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>contact.php" style="color:inherit;text-decoration:none;">Contact Us</a>
                    <?php endif; ?>
                </p>
                <a href="<?= SITE_URL ?>contact.php" class="btn btn-lg btn-primary mt-2 px-10">Contact us</a>
            </div>
        </div>
    </div>
</section>

<!-- ========== TOP DEVELOPERS ========== -->
<?php if (!empty($topDevelopers)): ?>
<section>
    <div class="container">
        <div class="section-heading">
            <h2>Top Developers</h2>
            <p>India's most trusted builders on <?= htmlspecialchars($siteName) ?></p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($topDevelopers as $dev): ?>
            <div class="col-lg-2 col-md-3 col-4">
                <a href="<?= SITE_URL ?>properties.php?agent=<?= $dev['id'] ?>" style="text-decoration:none;">
                    <div class="text-center p-3 bg-white rounded shadow-sm h-100 border" style="transition:all .3s;">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#eff6ff,#e0e7ff);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:22px;">
                            🏢
                        </div>
                        <h6 style="font-size:12px;font-weight:700;color:#1e293b;margin:0;"><?= htmlspecialchars($dev['name']) ?></h6>
                        <p style="font-size:11px;font-weight:600;color:#2563eb;margin:4px 0 0;"><?= $dev['prop_count'] ?> Projects</p>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== PARTNER LOGOS ========== -->
<section class="partners-section">
    <div class="container">
        <div class="row align-items-center">
            <?php
            $partners = [
                ['icon' => 'fas fa-shield-alt', 'name' => 'RERA Verified'],
                ['icon' => 'fas fa-home',       'name' => htmlspecialchars($siteName)],
                ['icon' => 'fas fa-building',   'name' => 'Trusted Builders'],
                ['icon' => 'fas fa-leaf',       'name' => 'Eco Homes'],
                ['icon' => 'fas fa-handshake',  'name' => 'No Brokerage'],
            ];
            foreach ($partners as $p):
            ?>
            <div class="col">
                <div class="partner-logo">
                    <i class="<?= $p['icon'] ?>"></i>
                    <span><?= $p['name'] ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
