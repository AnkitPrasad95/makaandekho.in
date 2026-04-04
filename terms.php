<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Terms of Use - ' . ($settings['site_name'] ?? 'MakaanDekho');
$pageDesc  = 'Terms of Use for ' . ($settings['site_name'] ?? 'MakaanDekho');

// Try to fetch from CMS pages
$cmsPage = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM cms_pages WHERE page_slug = 'terms' AND is_deleted=0 LIMIT 1");
    $stmt->execute();
    $cmsPage = $stmt->fetch();
} catch(Exception $e) {}

include __DIR__ . '/includes/header.php';
$siteName = $settings['site_name'] ?? 'MakaanDekho';
?>

<!-- Breadcrumb -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:14px;">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active">Terms of Use</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($cmsPage && !empty($cmsPage['content'])): ?>
                    <h1 class="mb-4" style="font-size:28px;font-weight:700;"><?= htmlspecialchars($cmsPage['page_name'] ?? 'Terms of Use') ?></h1>
                    <div class="cms-content"><?= $cmsPage['content'] ?></div>
                <?php else: ?>
                    <h1 class="mb-4" style="font-size:28px;font-weight:700;">Terms of Use</h1>
                    <p class="text-muted mb-4">Last updated: <?= date('F Y') ?></p>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">1. Acceptance of Terms</h5>
                        <p>By accessing and using <?= htmlspecialchars($siteName) ?> (the "Website"), you agree to be bound by these Terms of Use. If you do not agree with any part of these terms, you must not use our Website.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">2. Use of the Website</h5>
                        <p>The Website is a real estate listing platform that allows users to browse, list, and enquire about properties. You agree to use the Website only for lawful purposes and in accordance with these Terms.</p>
                        <ul>
                            <li>You must be at least 18 years old to register an account.</li>
                            <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
                            <li>You agree not to post false, misleading, or fraudulent property listings.</li>
                            <li>You must not use the Website to harass, spam, or deceive other users.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">3. Property Listings</h5>
                        <p>All property listings are subject to review and approval by our team. We reserve the right to remove any listing that violates our guidelines. <?= htmlspecialchars($siteName) ?> does not guarantee the accuracy of any property information posted by users.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">4. User Accounts</h5>
                        <p>Users can register as an Owner, Agent, or Builder. Each account type has specific capabilities. All accounts are subject to admin approval before activation.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">5. Intellectual Property</h5>
                        <p>All content, logos, images, and design elements on <?= htmlspecialchars($siteName) ?> are the property of the Website owner and are protected by intellectual property laws. You may not reproduce, distribute, or use any content without prior written consent.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">6. Limitation of Liability</h5>
                        <p><?= htmlspecialchars($siteName) ?> acts as a platform connecting property buyers, sellers, and renters. We are not responsible for any transactions, disputes, or losses arising from interactions between users. Users are advised to verify all property details independently.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">7. Changes to Terms</h5>
                        <p>We reserve the right to modify these Terms at any time. Continued use of the Website after changes constitutes acceptance of the updated Terms.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">8. Contact Information</h5>
                        <p>If you have any questions about these Terms, please contact us:</p>
                        <ul class="list-unstyled">
                            <?php if (!empty($settings['email'])): ?>
                            <li><i class="fas fa-envelope me-2 text-primary"></i> <?= htmlspecialchars($settings['email']) ?></li>
                            <?php endif; ?>
                            <?php if (!empty($settings['phone'])): ?>
                            <li><i class="fas fa-phone me-2 text-primary"></i> +91-<?= htmlspecialchars($settings['phone']) ?></li>
                            <?php endif; ?>
                            <?php if (!empty($settings['address'])): ?>
                            <li><i class="fas fa-map-marker-alt me-2 text-primary"></i> <?= htmlspecialchars($settings['address']) ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
