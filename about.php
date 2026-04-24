<?php
require_once __DIR__ . '/includes/db.php';

// Get CMS page content
$stmt = $pdo->prepare("SELECT * FROM cms_pages WHERE page_slug = 'about' AND is_deleted=0");
$stmt->execute();
$page = $stmt->fetch();

// ---- SEO (cms_pages → settings → hardcoded fallback) ----
$pageTitle     = ($page['meta_title']       ?? '') ?: 'About Us | MakaanDekho';
$pageDesc      = ($page['meta_description'] ?? '') ?: ($settings['meta_description'] ?? 'Learn about MakaanDekho — India\'s trusted real estate portal connecting property buyers, sellers, owners, agents and builders. Verified listings, transparent process.');
$pageKeywords  = ($page['meta_keywords']    ?? '') ?: ($settings['meta_keywords']    ?? 'about MakaanDekho, real estate platform India, property portal, verified listings');
$pageCanonical = SITE_URL . 'about.php';
$pageOgType    = 'website';
include __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
    <div class="container"><h1>About Us</h1><p>Learn more about MakaanDekho and our mission</p></div>
</div>

<section class="static-page">
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="page-content-card">
                <?php if (!empty($page['content'])): ?>
                    <?= $page['content'] ?>
                <?php else: ?>
                <h2>Welcome to MakaanDekho</h2>
                <p>MakaanDekho.in is a trusted real estate portal that connects property buyers, sellers, owners, agents, and builders on a single platform. We believe finding your dream property should be simple, transparent, and trustworthy.</p>

                <h3>Our Mission</h3>
                <p>To create India's most reliable property listing platform where every property is verified, every listing is genuine, and every buyer finds their perfect home.</p>

                <h3>Why Choose Us?</h3>
                <ul>
                    <li><strong>Verified Listings</strong> — Every property goes through admin verification with RERA/Registration number checks.</li>
                    <li><strong>Multi-Role Platform</strong> — Owners, Agents, Builders, and Sellers can list properties easily.</li>
                    <li><strong>Advanced Search</strong> — Filter by location, price, BHK, amenities, and more.</li>
                    <li><strong>Free to List</strong> — Property owners can list for free.</li>
                    <li><strong>Trusted Community</strong> — Thousands of verified properties across India.</li>
                </ul>

                <h3>How It Works</h3>
                <ol>
                    <li>Register as Owner, Agent, or Builder</li>
                    <li>Add your property with photos and documents</li>
                    <li>Admin verifies your RERA/Registration details</li>
                    <li>Your property goes live with a Verified badge</li>
                    <li>Buyers contact you directly through the platform</li>
                </ol>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-sidebar-card">
                <h5><i class="fas fa-phone-alt me-2 text-primary"></i>Contact Us</h5>
                <p><?= htmlspecialchars($settings['address'] ?? 'India') ?></p>
                <p><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($settings['email'] ?? 'info@makaandekho.in') ?></p>
                <p><i class="fas fa-phone me-2"></i><?= !empty($settings['whatsapp_number']) ? '+91-' . $settings['whatsapp_number'] : '' ?></p>
            </div>
            <div class="page-sidebar-card" style="background:var(--primary);border-color:var(--primary);text-align:center;">
                <h5 style="color:#fff;">List Your Property</h5>
                <p style="color:rgba(255,255,255,.8);font-size:13px;">Post your property for FREE</p>
                <a href="<?= SITE_URL ?>add-property.php" class="btn btn-light btn-sm" style="border-radius:20px;font-weight:600;">Post Now</a>
            </div>
        </div>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
