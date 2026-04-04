<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? ($settings['meta_title'] ?? 'MakaanDekho – Find Your Dream Home')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? ($settings['meta_description'] ?? 'Search properties for sale and rent across India.')) ?>">
    <?php if (!empty($settings['meta_keywords'])): ?>
    <meta name="keywords" content="<?= htmlspecialchars($settings['meta_keywords']) ?>">
    <?php endif; ?>
    <?php if (!empty($settings['favicon'])): ?>
    <link rel="icon" href="<?= UPLOAD_URL . 'settings/' . $settings['favicon'] ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? ($settings['meta_title'] ?? 'MakaanDekho')) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDesc ?? ($settings['meta_description'] ?? '')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= SITE_URL ?>">
    <?php if (!empty($settings['site_logo'])): ?>
    <meta property="og:image" content="<?= UPLOAD_URL . 'settings/' . $settings['site_logo'] ?>">
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= SITE_URL ?>assets/fonts/fontawesome-pro-5/css/all.css" rel="stylesheet">
    <link href="<?= SITE_URL ?>assets/css/animate.css" rel="stylesheet">
    <link href="<?= SITE_URL ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= SITE_URL ?>assets/css/style2.css" rel="stylesheet">
    <script>var SITE_URL = '<?= SITE_URL ?>';</script>
</head>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
// Site name from settings
$siteName = $settings['site_name'] ?? 'MakaanDekho';
?>
<body>
<header class="main-header">
<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand mr-10 px-0 w-100 w-xl-auto" href="<?= SITE_URL ?>">
            <?php if (!empty($settings['site_logo'])): ?>
                <img src="<?= UPLOAD_URL . 'settings/' . $settings['site_logo'] ?>" alt="<?= htmlspecialchars($siteName) ?>" class="nav-logo">
            <?php else: ?>
                <div class="logo-text">
                    <span class="logo-icon"><i class="fas fa-home"></i></span>
                    <span>makaan<br><strong>dekho</strong><small>.in</small></span>
                </div>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <?php
            // Fetch all active mega menu items, grouped
            $megaStmt = $pdo->query("SELECT * FROM mega_menu_items WHERE is_active=1 AND is_deleted=0 ORDER BY column_order ASC, item_order ASC");
            $megaAll = $megaStmt->fetchAll();
            $megaMenus = [];
            foreach ($megaAll as $mi) {
                $megaMenus[$mi['menu_slug']][$mi['column_heading']][] = $mi;
            }

            // Dynamic "For Buyers" — show latest property titles grouped by category
            $categoryMap = [
                'Residential' => ['types' => ['apartment','villa'], 'param' => 'apartment'],
                'Commercial'  => ['types' => ['commercial','office'], 'param' => 'commercial'],
                'Plots'       => ['types' => ['plot'], 'param' => 'plot'],
            ];
            $dynamicBuyer = [];
            foreach ($categoryMap as $catName => $catInfo) {
                $inTypes = implode("','", $catInfo['types']);
                $catStmt = $pdo->query("
                    SELECT p.title, p.slug, p.property_type, l.city
                    FROM properties p
                    LEFT JOIN locations l ON p.location_id = l.id
                    WHERE p.status='approved' AND p.is_deleted=0
                      AND p.property_type IN ('$inTypes')
                    ORDER BY p.created_at DESC LIMIT 8
                ");
                $props = $catStmt->fetchAll();
                if (!empty($props)) {
                    foreach ($props as $p) {
                        $label = $p['title'];
                        if ($p['city']) $label .= ' - ' . $p['city'];
                        $dynamicBuyer[$catName][] = [
                            'item_title' => $label,
                            'item_url'   => 'property-detail.php?slug=' . $p['slug'],
                        ];
                    }
                    $dynamicBuyer[$catName][] = [
                        'item_title' => 'View All ' . $catName . ' →',
                        'item_url'   => 'properties.php?type=' . $catInfo['param'],
                    ];
                }
            }
            if (!empty($dynamicBuyer)) {
                $megaMenus['for_buyers'] = $dynamicBuyer;
            }

            // Dynamic "Insights" — blog categories with latest titles
            $blogCats = $pdo->query("
                SELECT category, COUNT(*) as cnt
                FROM blogs
                WHERE status='published' AND is_deleted=0 AND category IS NOT NULL AND category != ''
                GROUP BY category ORDER BY cnt DESC
            ")->fetchAll();

            if (!empty($blogCats)) {
                $dynamicInsights = [];
                foreach ($blogCats as $bc) {
                    $catName = $bc['category'];
                    // Fetch latest 6 blogs in this category
                    $catBlogs = $pdo->prepare("
                        SELECT title, slug FROM blogs
                        WHERE status='published' AND is_deleted=0 AND category=?
                        ORDER BY created_at DESC LIMIT 6
                    ");
                    $catBlogs->execute([$catName]);
                    $catPosts = $catBlogs->fetchAll();

                    foreach ($catPosts as $cp) {
                        $dynamicInsights[$catName][] = [
                            'item_title' => $cp['title'],
                            'item_url'   => 'blog-detail.php?slug=' . $cp['slug'],
                        ];
                    }
                    // "View All" link for this category
                    $dynamicInsights[$catName][] = [
                        'item_title' => 'View All ' . $catName . ' →',
                        'item_url'   => 'blogs.php?category=' . urlencode($catName),
                    ];
                }
                $megaMenus['insights'] = $dynamicInsights;
            }

            $navItems = [
                ['label' => 'Home',              'slug' => null,              'href' => SITE_URL],
                ['label' => 'For Buyers',        'slug' => 'for_buyers',     'href' => '#'],
                ['label' => 'For Owners',        'slug' => 'for_owners',     'href' => '#'],
                ['label' => 'Insights',          'slug' => 'insights',       'href' => '#'],
                ['label' => 'Builders & Agents', 'slug' => 'builders_agents','href' => '#'],
            ];
            ?>
            <ul class="navbar-nav main-menu ms-auto me-auto">
                <?php foreach ($navItems as $nav): ?>
                <?php if ($nav['slug'] === null): ?>
                <!-- Simple link (Home) -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $nav['href'] ?>"><?= $nav['label'] ?></a>
                </li>
                <?php elseif (!empty($megaMenus[$nav['slug']])): ?>
                <!-- Mega menu dropdown -->
                <li class="nav-item dropdown mega-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?= $nav['label'] ?></a>
                    <div class="dropdown-menu mega-menu">
                        <div class="mega-menu-inner">
                            <?php foreach ($megaMenus[$nav['slug']] as $colHeading => $colItems): ?>
                            <div class="mega-col">
                                <h6 class="mega-heading"><?= htmlspecialchars($colHeading) ?></h6>
                                <ul>
                                    <?php foreach ($colItems as $mi):
                                        $mUrl = $mi['item_url'];
                                        if ($mUrl && $mUrl !== '#' && strpos($mUrl,'http') !== 0) {
                                            $mUrl = SITE_URL . ltrim($mUrl, '/');
                                        }
                                    ?>
                                    <li><a href="<?= htmlspecialchars($mUrl) ?>"><?= htmlspecialchars($mi['item_title']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endforeach; ?>
                            <?php if ($nav['slug'] === 'builders_agents'): ?>
                            <!-- CTA Card for Builders & Agents -->
                            <div class="mega-col mega-cta">
                                <div class="mega-cta-card">
                                    <h6>GROW YOUR REAL ESTATE BUSINESS</h6>
                                    <p>Get verified leads and premium visibility.</p>
                                    <a href="<?= SITE_URL ?>?register=1" class="btn btn-light btn-sm">Join Network</a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php else: ?>
                <!-- Fallback: simple dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><?= $nav['label'] ?></a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><?= $nav['label'] ?></a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="nav-right d-flex align-items-center gap-3">
                <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="<?= SITE_URL ?>add-property.php" class="btn btn-add-listing btn btn-outline-primary bg-primary btn-lg text-white rounded-lg bg-hover-primary border-hover-primary hover-white d-none d-lg-block">
                    <i class="fas fa-home me-1"></i> Add listing
                </a>
                <?php else: ?>
                <a href="#" class="btn btn-add-listing btn btn-outline-primary bg-primary btn-lg text-white rounded-lg bg-hover-primary border-hover-primary hover-white d-none d-lg-block" data-bs-toggle="modal" data-bs-target="#postPropertyModal">
                    <i class="fas fa-home me-1"></i> Add listing
                </a>
                <?php endif; ?>
                <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle login-link" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($_SESSION['user_data']['name'] ?? 'Account') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>dashboard.php"><i class="fas fa-chart-pie me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>my-properties.php"><i class="fas fa-building me-2"></i>My Properties</a></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>my-favourites.php"><i class="fas fa-heart me-2"></i>Saved</a></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>logout-user.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle login-link" href="#" data-bs-toggle="dropdown">Login</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>login.php"><i class="fas fa-user me-2"></i>User Login</a></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>?register=1"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>admin/login.php"><i class="fas fa-shield-alt me-2"></i>Admin Login</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
</header>

<!-- ========== POST PROPERTY MODAL ========== -->
<div class="modal fade" id="postPropertyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" style="max-width:520px;margin:60px auto;">
    <div class="modal-content" style="border:none;border-radius:14px;overflow:visible;display:block !important;">
      <!-- Header -->
      <div class="pp-modal-header">
        <h5>Post Your Property</h5>
        <p>Get listed quickly. We'll contact you for details.</p>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body -->
      <div class="pp-modal-body">
        <!-- Success: New User Registration -->
        <div id="ppSuccessNew" style="display:none;" class="py-3">
          <div style="text-align:center;">
            <div style="width:70px;height:70px;border-radius:50%;background:#d1fae5;color:#059669;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
              <i class="fas fa-user-check"></i>
            </div>
            <h5 style="font-weight:700;color:#1a2332;margin-bottom:4px;">Registration Successful!</h5>
            <p style="font-size:13px;color:#6b7280;margin-bottom:0;">Your account has been created. Save your login details below.</p>
          </div>

          <!-- Credentials Box -->
          <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:16px;margin:16px 0;text-align:left;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:12px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:12px;color:#6b7280;"><i class="fas fa-envelope" style="margin-right:4px;"></i> Email</span>
                <strong id="ppCredEmail" style="font-size:13px;color:#1a2332;"></strong>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:12px;color:#6b7280;"><i class="fas fa-lock" style="margin-right:4px;"></i> Password</span>
                <div style="display:flex;align-items:center;gap:8px;">
                  <strong id="ppCredPass" style="font-size:15px;color:#1e40af;letter-spacing:1px;font-family:monospace;"></strong>
                  <button type="button" onclick="copyPassword()" style="background:#e0e7ff;border:none;border-radius:6px;padding:4px 10px;font-size:11px;cursor:pointer;color:#1e40af;font-weight:600;" title="Copy password">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
              </div>
            </div>

            <div style="background:#fef3c7;border-radius:8px;padding:10px;font-size:12px;color:#92400e;">
              <i class="fas fa-clock" style="margin-right:4px;"></i>
              <strong>Next Steps:</strong>
              <ol style="margin:6px 0 0;padding-left:18px;line-height:1.8;">
                <li>Admin will review & approve your account (1-24 hrs)</li>
                <li>You'll receive an email with a <strong>Set Password</strong> link</li>
                <li>Set your password and start posting properties!</li>
              </ol>
            </div>
          </div>

          <div style="display:flex;gap:8px;margin-top:12px;">
            <button class="btn btn-primary flex-fill" onclick="resetPostForm()" style="border-radius:8px;font-size:13px;"><i class="fas fa-plus" style="margin-right:4px;"></i> Post Another</button>
            <a href="<?= SITE_URL ?>login.php" class="btn btn-outline-secondary flex-fill" style="border-radius:8px;font-size:13px;"><i class="fas fa-sign-in-alt" style="margin-right:4px;"></i> Go to Login</a>
          </div>
        </div>

        <!-- Success: Existing User (just property submitted) -->
        <div id="ppSuccessExisting" style="display:none;" class="text-center py-4">
          <div style="width:70px;height:70px;border-radius:50%;background:#d1fae5;color:#059669;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
            <i class="fas fa-check"></i>
          </div>
          <h5 style="font-weight:700;color:#1a2332;">Property Submitted!</h5>
          <p style="font-size:14px;color:#6b7280;">Your property listing is under review.<br>We'll contact you shortly with updates.</p>
          <div style="display:flex;gap:8px;justify-content:center;margin-top:16px;">
            <button class="btn btn-primary" onclick="resetPostForm()" style="border-radius:8px;font-size:13px;"><i class="fas fa-plus" style="margin-right:4px;"></i> Post Another</button>
            <a href="<?= SITE_URL ?>login.php" class="btn btn-outline-secondary" style="border-radius:8px;font-size:13px;"><i class="fas fa-sign-in-alt" style="margin-right:4px;"></i> Go to Login</a>
          </div>
        </div>

        <!-- Form -->
        <form id="postPropertyForm">
          <!-- I am -->
          <label class="pp-label">I am</label>
          <div class="pp-role-tabs">
            <label class="pp-role active">
              <input type="radio" name="role" value="owner" checked> Owner
            </label>
            <label class="pp-role">
              <input type="radio" name="role" value="agent"> Agent
            </label>
            <label class="pp-role">
              <input type="radio" name="role" value="builder"> Builder
            </label>
          </div>

          <!-- Name -->
          <label class="pp-label">Name</label>
          <input type="text" name="name" class="pp-input" placeholder="Your Name" required data-v="req|name|safe" data-msg="Name is required.">

          <!-- Phone & Email -->
          <div class="row g-3">
            <div class="col-6">
              <label class="pp-label">Phone</label>
              <input type="tel" name="phone" class="pp-input" placeholder="9876543210" maxlength="10" required data-v="req|phone" data-msg="Phone is required.">
            </div>
            <div class="col-6">
              <label class="pp-label">Email</label>
              <input type="email" name="email" class="pp-input" placeholder="name@mail.com" required data-v="req|email" data-msg="Email is required.">
            </div>
          </div>

          <!-- City -->
          <label class="pp-label">City</label>
          <div class="pp-input-icon">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="city" class="pp-input" placeholder="e.g. Noida" required data-v="req|safe" data-msg="City is required.">
          </div>

          <!-- State -->
          <label class="pp-label">State</label>
          <select name="state" class="pp-input" required>
            <option value="">Select State</option>
            <?php
            $indian_states = ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Delhi','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'];
            foreach ($indian_states as $st):
            ?>
            <option value="<?= $st ?>"><?= $st ?></option>
            <?php endforeach; ?>
          </select>

          <!-- Error -->
          <div id="ppError" class="alert alert-danger mt-3" style="display:none;font-size:13px;border-radius:8px;"></div>

          <!-- Submit -->
          <button type="submit" class="pp-submit" id="ppSubmitBtn">
            Post Property Now
          </button>
          <p class="text-center mt-2" style="font-size:12px;color:#999;">
            By posting, you agree to our <a href="<?= SITE_URL ?>terms.php" style="color:#2196F3;">Terms & Conditions</a>.
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ========== WHATSAPP FLOATING BUTTON ========== -->
<?php if (!empty($settings['whatsapp_number'])): ?>
<a href="https://wa.me/91<?= htmlspecialchars($settings['whatsapp_number']) ?>" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<style>
.whatsapp-float{position:fixed;bottom:25px;right:25px;z-index:9999;width:56px;height:56px;background:#25D366;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;box-shadow:0 4px 15px rgba(37,211,102,.4);transition:transform .3s,box-shadow .3s;}
.whatsapp-float:hover{transform:scale(1.1);box-shadow:0 6px 20px rgba(37,211,102,.5);color:#fff;}
</style>
<?php endif; ?>

<!-- Auto-open popup if ?register=1 -->
<script>
if (window.location.search.indexOf('register=1') !== -1) {
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('postPropertyModal');
        if (modal) {
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    });
}
</script>
