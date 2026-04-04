<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Privacy Policy - ' . ($settings['site_name'] ?? 'MakaanDekho');
$pageDesc  = 'Privacy Policy for ' . ($settings['site_name'] ?? 'MakaanDekho');

// Try to fetch from CMS pages
$cmsPage = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM cms_pages WHERE page_slug = 'privacy' AND is_deleted=0 LIMIT 1");
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
                <li class="breadcrumb-item active">Privacy Policy</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($cmsPage && !empty($cmsPage['content'])): ?>
                    <h1 class="mb-4" style="font-size:28px;font-weight:700;"><?= htmlspecialchars($cmsPage['page_name'] ?? 'Privacy Policy') ?></h1>
                    <div class="cms-content"><?= $cmsPage['content'] ?></div>
                <?php else: ?>
                    <h1 class="mb-4" style="font-size:28px;font-weight:700;">Privacy Policy</h1>
                    <p class="text-muted mb-4">Last updated: <?= date('F Y') ?></p>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">1. Information We Collect</h5>
                        <p><?= htmlspecialchars($siteName) ?> collects the following information when you use our platform:</p>
                        <ul>
                            <li><strong>Personal Information:</strong> Name, email address, phone number, city, and state provided during registration.</li>
                            <li><strong>Property Information:</strong> Details about properties you list including title, description, price, location, images, and amenities.</li>
                            <li><strong>Usage Data:</strong> Browser type, IP address, pages visited, and time spent on the Website.</li>
                            <li><strong>Enquiry Data:</strong> Contact information submitted through property enquiry forms.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">2. How We Use Your Information</h5>
                        <ul>
                            <li>To create and manage your account.</li>
                            <li>To display your property listings to potential buyers/renters.</li>
                            <li>To facilitate communication between property owners and enquirers.</li>
                            <li>To send important updates about your account and listings.</li>
                            <li>To improve our Website and services.</li>
                            <li>To send newsletters (if subscribed) — you can unsubscribe anytime.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">3. Information Sharing</h5>
                        <p>We do not sell or rent your personal information to third parties. Your contact information may be shared with:</p>
                        <ul>
                            <li>Users who enquire about your listed properties (if you are a property owner/agent).</li>
                            <li>Property owners/agents when you submit an enquiry.</li>
                            <li>Law enforcement agencies when required by law.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">4. Data Security</h5>
                        <p>We implement industry-standard security measures to protect your data, including:</p>
                        <ul>
                            <li>Password hashing using secure algorithms.</li>
                            <li>CSRF protection on all forms.</li>
                            <li>Prepared SQL statements to prevent injection attacks.</li>
                            <li>HTTPS encryption for data transmission.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">5. Cookies</h5>
                        <p>We use session cookies to maintain your login state and provide a better user experience. These cookies are essential for the Website to function properly and are automatically deleted when you close your browser or log out.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">6. Your Rights</h5>
                        <p>You have the right to:</p>
                        <ul>
                            <li>Access and update your personal information through your profile page.</li>
                            <li>Request deletion of your account by contacting our support.</li>
                            <li>Opt out of marketing communications.</li>
                            <li>Request a copy of your data.</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">7. Changes to This Policy</h5>
                        <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date.</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold">8. Contact Us</h5>
                        <p>For any privacy-related questions or concerns, contact us:</p>
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
