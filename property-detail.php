<?php
require_once __DIR__ . '/includes/db.php';

$slug = $_GET['slug'] ?? '';
$locationSlug = $_GET['location'] ?? '';
if (!$slug) { header('Location: ' . SITE_URL . 'properties.php'); exit; }

// Fetch property with location slug
$stmt = $pdo->prepare("
    SELECT p.*, l.city, l.area, l.state, l.slug AS location_slug
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.slug = ? AND p.status = 'approved' AND p.is_deleted=0
    LIMIT 1
");
$stmt->execute([$slug]);
$prop = $stmt->fetch();
if (!$prop) { include __DIR__ . '/includes/header.php'; echo '<div class="container py-5 text-center"><h3>Property Not Found</h3><a href="'.SITE_URL.'properties.php" class="btn btn-primary mt-3">Browse Properties</a></div>'; include __DIR__ . '/includes/footer.php'; exit; }

// If accessed via /property/slug, redirect to SEO URL /location-slug/property-slug
if (empty($locationSlug) && !empty($prop['location_slug'])) {
    header('Location: ' . SITE_URL . $prop['location_slug'] . '/' . $prop['slug'], true, 301);
    exit;
}

// Gallery images
$imgStmt = $pdo->prepare("SELECT * FROM property_images WHERE property_id = ? AND is_deleted=0 ORDER BY is_primary DESC, id ASC");
$imgStmt->execute([$prop['id']]);
$gallery = $imgStmt->fetchAll();

// Builder / Owner info
$builder = null;
if ($prop['user_id']) {
    $bStmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_deleted=0");
    $bStmt->execute([$prop['user_id']]);
    $builder = $bStmt->fetch();
}

// Assigned Agent info
$agent = null;
if (!empty($prop['agent_id'])) {
    $aStmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_deleted=0");
    $aStmt->execute([$prop['agent_id']]);
    $agent = $aStmt->fetch();
}

// Amenities
$amenities = [];
if (!empty($prop['amenities'])) {
    $decoded = json_decode($prop['amenities'], true);
    if (is_array($decoded)) $amenities = $decoded;
}
$amenityIcons = [
    'parking'=>['fa-car','Parking'], 'lift'=>['fa-elevator','Lift'], 'security'=>['fa-shield-alt','Security'],
    'power_backup'=>['fa-bolt','Power Backup'], 'gym'=>['fa-dumbbell','Gym'], 'swimming_pool'=>['fa-swimming-pool','Swimming Pool'],
    'garden'=>['fa-tree','Garden'], 'clubhouse'=>['fa-users','Club House'], 'playground'=>['fa-child','Playground'],
    'cctv'=>['fa-video','CCTV'], 'fire_safety'=>['fa-fire-extinguisher','Fire Safety'], 'wifi'=>['fa-wifi','Wi-Fi'],
    'ac'=>['fa-snowflake','AC'], 'laundry'=>['fa-tshirt','Laundry'], 'washer'=>['fa-water','Washer'],
    'refrigerator'=>['fa-box','Refrigerator'], 'intercom'=>['fa-phone-alt','Intercom'], 'gas_pipeline'=>['fa-burn','Gas Pipeline'],
];

// Similar / newly launched properties
$similarStmt = $pdo->prepare("
    SELECT p.*, l.city, l.area FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status='approved' AND p.id != ? AND p.location_id = ? AND p.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 6
");
$similarStmt->execute([$prop['id'], $prop['location_id']]);
$similarProps = $similarStmt->fetchAll();

// Format price helper
function fmtPrice($p) {
    if (!$p) return '₹ N/A';
    if ($p >= 10000000) return '₹' . number_format($p / 10000000, 2) . ' Cr';
    if ($p >= 100000) return '₹' . number_format($p / 100000, 2) . ' Lac';
    return '₹' . number_format($p);
}

$areaCity = ($prop['area'] ? $prop['area'] . ', ' : '') . ($prop['city'] ?? '');

// Featured image (computed early so it can be used as OG image)
$featImg = 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80';
if (!empty($prop['featured_image'])) $featImg = UPLOAD_URL . 'properties/' . $prop['featured_image'];

// ---- SEO ----
$pageTitle     = $prop['title'] . ' in ' . $areaCity . ' | MakaanDekho';
$pageDesc      = $prop['meta_description'] ?? $prop['short_description'] ?? ('Find ' . $prop['title'] . ' in ' . $areaCity . ', ' . ($prop['state'] ?? '') . '. View price, photos, amenities and more on MakaanDekho.');
$pageKeywords  = $prop['title'] . ', property in ' . ($prop['city'] ?? '') . ', ' . ($prop['area'] ?? '') . ', real estate ' . ($prop['city'] ?? '') . ', ' . ($prop['state'] ?? '');
$pageCanonical = !empty($prop['location_slug']) ? SITE_URL . $prop['location_slug'] . '/' . $prop['slug'] : SITE_URL . 'property/' . $prop['slug'];
$pageOgType    = 'product';
$pageOgImage   = $featImg;
include __DIR__ . '/includes/header.php';
?>

<!-- ========== PROPERTY DETAIL ========== -->
<div class="pd-page">
<div class="container">

    <!-- ---- Breadcrumb + Title Bar ---- -->
    <div class="pd-topbar">
        <div>
            <?php if (!empty($prop['featured'])): ?>
            <span class="pd-verified"><i class="fas fa-star"></i> VERIFIED</span>
            <?php endif; ?>
            <h1 class="pd-title"><?= htmlspecialchars($prop['title']) ?></h1>
            <p class="pd-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(($prop['area'] ?? '') . ($prop['area'] && $prop['city'] ? ', ' : '') . ($prop['city'] ?? '')) ?></p>
        </div>
        <div class="pd-price-box">
            <span class="pd-price"><?= fmtPrice($prop['price']) ?></span>
            <?php if ($prop['price_type'] === 'per_month'): ?><small>/month</small>
            <?php elseif ($prop['price_type'] === 'per_sqft'): ?><small>/sq.ft</small><?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- ===== LEFT COLUMN ===== -->
        <div class="col-lg-8">

            <!-- ---- Image Gallery ---- -->
            <div class="pd-gallery">
                <div class="pd-gallery-main">
                    <img src="<?= htmlspecialchars($featImg) ?>" alt="<?= htmlspecialchars($prop['title']) ?>" id="mainImage">
                </div>
                <?php if (!empty($gallery)): ?>
                <div class="pd-gallery-thumbs">
                    <div class="thumb-item active" onclick="changeMainImg(this)" data-src="<?= htmlspecialchars($featImg) ?>">
                        <img src="<?= htmlspecialchars($featImg) ?>" alt="Main">
                    </div>
                    <?php foreach ($gallery as $gi): ?>
                    <div class="thumb-item" onclick="changeMainImg(this)" data-src="<?= UPLOAD_URL . 'properties/' . $gi['image'] ?>">
                        <img src="<?= UPLOAD_URL . 'properties/' . $gi['image'] ?>" alt="Gallery">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- ---- Quick Stats ---- -->
            <div class="pd-quick-stats">
                <?php if ($prop['created_at']): ?>
                <div class="qs-item"><i class="far fa-calendar-alt"></i><div><small>On</small><strong><?= date('M Y', strtotime($prop['created_at'])) ?></strong></div></div>
                <?php endif; ?>
                <div class="qs-item"><i class="fas fa-info-circle"></i><div><small>Status</small><strong><?= ucfirst(str_replace('_',' ',$prop['availability'] ?? 'Available')) ?></strong></div></div>
                <div class="qs-item"><i class="fas fa-couch"></i><div><small>Furnishing</small><strong><?= ucfirst(str_replace('-',' ',$prop['furnishing'] ?? 'N/A')) ?></strong></div></div>
                <?php if ($prop['area_sqft']): ?>
                <div class="qs-item"><i class="fas fa-ruler-combined"></i><div><small>Area</small><strong><?= number_format($prop['area_sqft']) ?> sq.ft</strong></div></div>
                <?php endif; ?>
            </div>

            <!-- ---- Project Description ---- -->
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="far fa-file-alt"></i> PROJECT DESCRIPTION</h3>
                <div class="pd-description">
                    <?php if (!empty($prop['description'])): ?>
                        <?= nl2br(htmlspecialchars($prop['description'])) ?>
                    <?php elseif (!empty($prop['short_description'])): ?>
                        <?= nl2br(htmlspecialchars($prop['short_description'])) ?>
                    <?php else: ?>
                        <p class="text-muted">No description available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ---- Lifestyle Amenities ---- -->
            <?php if (!empty($amenities)): ?>
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="fas fa-star"></i> LIFESTYLE AMENITIES</h3>
                <div class="pd-amenities-grid">
                    <?php foreach ($amenities as $a):
                        $icon = $amenityIcons[$a][0] ?? 'fa-check';
                        $label = $amenityIcons[$a][1] ?? ucfirst(str_replace('_',' ',$a));
                    ?>
                    <div class="pd-amenity">
                        <div class="pd-amenity-icon"><i class="fas <?= $icon ?>"></i></div>
                        <span><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ---- Technical Specifications ---- -->
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="fas fa-cog"></i> TECHNICAL SPECIFICATIONS</h3>
                <div class="pd-specs-grid">
                    <?php
                    $specs = [];
                    if ($prop['property_type']) $specs[] = ['Type', ucfirst($prop['property_type'])];
                    if ($prop['bedrooms']) $specs[] = ['Bedrooms', $prop['bedrooms'] . ' BHK'];
                    if ($prop['bathrooms']) $specs[] = ['Bathrooms', $prop['bathrooms']];
                    if ($prop['area_sqft']) $specs[] = ['Area', number_format($prop['area_sqft']) . ' sq.ft'];
                    if ($prop['floor']) $specs[] = ['Floor', $prop['floor'] . ($prop['total_floors'] ? ' of ' . $prop['total_floors'] : '')];
                    if ($prop['property_age']) $specs[] = ['Property Age', $prop['property_age']];
                    if ($prop['furnishing']) $specs[] = ['Furnishing', ucfirst(str_replace('-',' ',$prop['furnishing']))];
                    if ($prop['listing_type']) $specs[] = ['Listing', 'For ' . ucfirst($prop['listing_type'])];
                    foreach ($specs as $s):
                    ?>
                    <div class="pd-spec">
                        <small><?= $s[0] ?></small>
                        <strong><?= $s[1] ?></strong>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ---- Configuration & Pricing ---- -->
            <?php if ($prop['bedrooms']): ?>
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="fas fa-rupee-sign"></i> CONFIGURATION & PRICING</h3>
                <div class="pd-config-card">
                    <div class="config-header">
                        <span><i class="fas fa-bed"></i> <?= $prop['bedrooms'] ?> BHK<?= $prop['bathrooms'] ? ' - ' . $prop['bathrooms'] . 'T' : '' ?></span>
                        <span><?= $prop['area_sqft'] ? number_format($prop['area_sqft']) . ' sq.ft' : '' ?></span>
                    </div>
                    <div class="config-price"><?= fmtPrice($prop['price']) ?></div>
                    <a href="#enquirySection" class="btn-config-enquire">ENQUIRE NOW</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- ---- Interactive Map ---- -->
            <?php if (!empty($prop['google_map'])): ?>
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="fas fa-map-marked-alt"></i> INTERACTIVE MAP</h3>
                <div class="pd-map">
                    <iframe src="<?= htmlspecialchars($prop['google_map']) ?>" width="100%" height="350" style="border:0;border-radius:10px;" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            <?php elseif ($prop['address']): ?>
            <div class="pd-section">
                <h3 class="pd-section-title"><i class="fas fa-map-marked-alt"></i> LOCATION</h3>
                <div class="pd-map">
                    <iframe src="https://maps.google.com/maps?q=<?= urlencode($prop['address']) ?>&output=embed" width="100%" height="350" style="border:0;border-radius:10px;" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            <?php endif; ?>

            <!-- ---- User Reviews / Enquiry Form ---- -->
            <div class="pd-section" id="enquirySection">
                <h3 class="pd-section-title"><i class="fas fa-envelope"></i> ENQUIRE ABOUT THIS PROPERTY</h3>
                <form class="pd-enquiry-form" id="enquiryForm">
                    <input type="hidden" name="property_id" value="<?= $prop['id'] ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="pd-input" placeholder="Your Name *" required data-v="req|name|safe" data-msg="Name is required.">
                        </div>
                        <div class="col-md-6">
                            <input type="tel" name="phone" class="pd-input" placeholder="Phone Number *" maxlength="10" required data-v="req|phone" data-msg="Phone is required.">
                        </div>
                        <div class="col-12">
                            <input type="email" name="email" class="pd-input" placeholder="Email Address *" required data-v="req|email" data-msg="Email is required.">
                        </div>
                        <div class="col-12">
                            <textarea name="message" class="pd-input" rows="3" placeholder="I'm interested in this property..." data-v="safe"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="pd-enquiry-btn" id="enquiryBtn">
                                <i class="fas fa-paper-plane"></i> Send Enquiry
                            </button>
                        </div>
                    </div>
                    <div id="enquiryMsg" class="mt-3" style="display:none;"></div>
                </form>
            </div>

        </div>

        <!-- ===== RIGHT SIDEBAR ===== -->
        <div class="col-lg-4">

            <!-- Listing Agent / Propreneur Card -->
            <?php
            $displayAgent = $agent ?: $builder; // Show assigned agent, fallback to owner
            if ($displayAgent):
                $agentPhoto = !empty($displayAgent['profile_image'])
                    ? UPLOAD_URL . 'users/' . $displayAgent['profile_image']
                    : '';
                $agentInitial = strtoupper(substr($displayAgent['name'], 0, 1));
                $agentRole = ucfirst($displayAgent['role'] ?? 'Agent');
            ?>
            <div class="pd-sidebar-card" style="padding:0;overflow:hidden;border:none;">
                <!-- Agent Header -->
                <div style="background:#1a2332;padding:16px 20px;">
                    <p style="color:#f0a500;font-size:12px;font-weight:700;margin:0;letter-spacing:1px;">LISTING PROPRENEUR</p>
                </div>
                <!-- Agent Body -->
                <div style="background:#253042;padding:20px;display:flex;align-items:center;gap:16px;">
                    <!-- Photo -->
                    <?php if ($agentPhoto): ?>
                    <div style="width:70px;height:70px;border-radius:50%;overflow:hidden;border:3px solid #f0a500;flex-shrink:0;">
                        <img src="<?= htmlspecialchars($agentPhoto) ?>" alt="<?= htmlspecialchars($displayAgent['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <?php else: ?>
                    <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,#f0a500,#e88d00);display:flex;align-items:center;justify-content:center;border:3px solid #f0a500;flex-shrink:0;">
                        <span style="font-size:26px;font-weight:800;color:#fff;"><?= $agentInitial ?></span>
                    </div>
                    <?php endif; ?>
                    <!-- Info -->
                    <div>
                        <h5 style="color:#fff;font-weight:700;font-size:16px;margin:0 0 4px;"><?= htmlspecialchars($displayAgent['name']) ?></h5>
                        <?php if (!empty($displayAgent['email'])): ?>
                        <p style="color:#adb5bd;font-size:13px;margin:0 0 3px;">
                            <i class="fas fa-envelope" style="color:#f0a500;width:16px;font-size:11px;"></i>
                            <?= htmlspecialchars($displayAgent['email']) ?>
                        </p>
                        <?php endif; ?>
                        <?php if (!empty($displayAgent['phone'])): ?>
                        <p style="color:#adb5bd;font-size:13px;margin:0;">
                            <i class="fas fa-phone" style="color:#f0a500;width:16px;font-size:11px;"></i>
                            <?= htmlspecialchars($displayAgent['phone']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Actions -->
                <div style="background:#253042;padding:0 20px 20px;display:flex;gap:8px;">
                    <?php if (!empty($displayAgent['phone'])): ?>
                    <a href="tel:+91<?= htmlspecialchars($displayAgent['phone']) ?>" style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#f0a500;color:#1a2332;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;">
                        <i class="fas fa-phone-alt"></i> Call Now
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['whatsapp_number']) || !empty($displayAgent['phone'])): ?>
                    <a href="https://wa.me/91<?= htmlspecialchars($displayAgent['phone'] ?? $settings['whatsapp_number']) ?>?text=<?= urlencode('Hi, I am interested in: ' . $prop['title']) ?>" target="_blank" style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#25D366;color:#fff;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Fallback: No agent assigned -->
            <div class="pd-sidebar-card pd-builder-card">
                <div class="builder-logo"><i class="fas fa-home"></i></div>
                <h5><?= htmlspecialchars($settings['site_name'] ?? 'MakaanDekho') ?></h5>
                <a href="#enquirySection" class="btn-contact-builder">
                    <i class="fas fa-phone-alt"></i> Enquire Now
                </a>
            </div>
            <?php endif; ?>

            <!-- Key Highlights -->
            <div class="pd-sidebar-card">
                <h6 class="sidebar-card-title"><i class="fas fa-list-ul"></i> KEY HIGHLIGHTS</h6>
                <ul class="pd-highlights">
                    <?php if ($prop['bedrooms']): ?><li><i class="fas fa-check"></i> <?= $prop['bedrooms'] ?> BHK <?= ucfirst($prop['property_type'] ?? '') ?></li><?php endif; ?>
                    <?php if ($prop['area_sqft']): ?><li><i class="fas fa-check"></i> <?= number_format($prop['area_sqft']) ?> sq.ft area</li><?php endif; ?>
                    <?php if ($prop['furnishing']): ?><li><i class="fas fa-check"></i> <?= ucfirst(str_replace('-',' ',$prop['furnishing'])) ?></li><?php endif; ?>
                    <?php if ($prop['floor']): ?><li><i class="fas fa-check"></i> Floor <?= $prop['floor'] ?><?= $prop['total_floors'] ? ' of ' . $prop['total_floors'] : '' ?></li><?php endif; ?>
                    <li><i class="fas fa-check"></i> For <?= ucfirst($prop['listing_type'] ?? 'Sale') ?></li>
                    <li><i class="fas fa-check"></i> <?= ucfirst(str_replace('_',' ',$prop['availability'] ?? 'Available')) ?></li>
                </ul>
            </div>

            <!-- EMI Calculator (hidden for now) -->
            <!-- <div class="pd-sidebar-card">
                <h6 class="sidebar-card-title"><i class="fas fa-calculator"></i> EMI Calculator</h6>
                <p style="font-size:12px;color:#888;margin-bottom:14px;">Calculate your monthly payments</p>
                <div class="emi-field">
                    <label>Loan Amount</label>
                    <div class="emi-input-wrap">
                        <span>₹</span>
                        <input type="number" id="emiLoan" value="<?= round(($prop['price'] ?? 5000000) * 0.8) ?>" onchange="calcEMI()">
                    </div>
                </div>
                <div class="emi-field">
                    <label>Interest Rate</label>
                    <div class="emi-input-wrap">
                        <input type="number" id="emiRate" value="8.5" step="0.1" onchange="calcEMI()">
                        <span>%p.a</span>
                    </div>
                </div>
                <div class="emi-field">
                    <label>Tenure</label>
                    <div class="emi-input-wrap">
                        <input type="number" id="emiTenure" value="20" onchange="calcEMI()">
                        <span>Years</span>
                    </div>
                </div>
                <div class="emi-results">
                    <div class="emi-result-row highlight"><span>Monthly EMI</span><strong id="emiMonthly">-</strong></div>
                    <div class="emi-result-row"><span>Principal Amount</span><span id="emiPrincipal">-</span></div>
                    <div class="emi-result-row"><span>Total Interest</span><span id="emiInterest">-</span></div>
                    <div class="emi-result-row total"><span>Total Amount</span><strong id="emiTotal">-</strong></div>
                </div>
            </div> -->

            <!-- Share / Actions -->
            <div class="pd-sidebar-card">
                <div class="pd-share-actions">
                    <button onclick="navigator.share?.({title:'<?= htmlspecialchars($prop['title']) ?>', url:window.location.href})" class="pd-share-btn"><i class="fas fa-share-alt"></i> Share</button>
                    <button class="pd-share-btn"><i class="far fa-heart"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ---- Newly Launched / Similar ---- -->
    <?php if (!empty($similarProps)): ?>
    <div class="pd-section" style="margin-top:40px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="pd-section-title" style="margin-bottom:0;"><i class="fas fa-rocket"></i> Newly Launched in <?= htmlspecialchars($prop['city'] ?? 'Your Area') ?></h3>
            <a href="<?= SITE_URL ?>properties.php?location_id=<?= $prop['location_id'] ?>" class="btn btn-outline-primary btn-sm" style="border-radius:20px;">Explore All</a>
        </div>
        <div class="swiper property-slider">
            <div class="swiper-wrapper">
                <?php foreach ($similarProps as $sp): ?>
                <div class="swiper-slide">
                    <a href="<?= SITE_URL ?>property-detail.php?slug=<?= htmlspecialchars($sp['slug']) ?>" class="listing-card-link">
                    <div class="property-card">
                        <div class="card-thumb">
                            <?php $spImg = !empty($sp['featured_image']) ? UPLOAD_URL.'properties/'.$sp['featured_image'] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&q=75'; ?>
                            <img src="<?= htmlspecialchars($spImg) ?>" alt="<?= htmlspecialchars($sp['title']) ?>">
                        </div>
                        <div class="card-body">
                            <h5><?= htmlspecialchars($sp['title']) ?></h5>
                            <p class="location"><i class="fas fa-map-marker-alt" style="font-size:11px;color:var(--primary);"></i> <?= htmlspecialchars(($sp['area']??'') . ', ' . ($sp['city']??'')) ?></p>
                            <div class="card-footer-row">
                                <div class="price"><?= fmtPrice($sp['price']) ?></div>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <?php endif; ?>

</div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
// Gallery image switcher
function changeMainImg(el) {
    document.getElementById('mainImage').src = el.dataset.src;
    document.querySelectorAll('.thumb-item').forEach(function(t){ t.classList.remove('active'); });
    el.classList.add('active');
}

// EMI Calculator
function calcEMI() {
    var P = parseFloat(document.getElementById('emiLoan').value) || 0;
    var r = (parseFloat(document.getElementById('emiRate').value) || 0) / 12 / 100;
    var n = (parseInt(document.getElementById('emiTenure').value) || 1) * 12;
    if (P <= 0 || r <= 0 || n <= 0) return;

    var emi = P * r * Math.pow(1+r, n) / (Math.pow(1+r, n) - 1);
    var totalAmt = emi * n;
    var totalInt = totalAmt - P;

    function fmt(v) {
        if (v >= 10000000) return '₹' + (v/10000000).toFixed(2) + ' Cr';
        if (v >= 100000) return '₹' + (v/100000).toFixed(2) + ' L';
        return '₹' + Math.round(v).toLocaleString('en-IN');
    }

    document.getElementById('emiMonthly').textContent = fmt(emi);
    document.getElementById('emiPrincipal').textContent = fmt(P);
    document.getElementById('emiInterest').textContent = fmt(totalInt);
    document.getElementById('emiTotal').textContent = fmt(totalAmt);
}
calcEMI();

// Enquiry form AJAX
document.getElementById('enquiryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (typeof MKV !== 'undefined' && !MKV.validate(this)) return;
    var btn = document.getElementById('enquiryBtn');
    var msg = document.getElementById('enquiryMsg');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

    var fd = new FormData(this);
    fetch(SITE_URL + 'ajax-enquiry.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(d){
            msg.style.display = 'block';
            msg.className = 'mt-3 alert alert-' + (d.success ? 'success' : 'danger');
            msg.innerHTML = '<i class="fas fa-' + (d.success ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + d.message;
            if (d.success) { e.target.reset(); }
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Enquiry';
        })
        .catch(function(){
            msg.style.display = 'block';
            msg.className = 'mt-3 alert alert-danger';
            msg.textContent = 'Something went wrong.';
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Enquiry';
        });
});
</script>
