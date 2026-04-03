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
    <link href="<?= SITE_URL ?>assets/css/style.css" rel="stylesheet">
    <script>var SITE_URL = '<?= SITE_URL ?>';</script>
</head>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= SITE_URL ?>">
            <?php if (!empty($settings['site_logo'])): ?>
                <img src="<?= UPLOAD_URL . 'settings/' . $settings['site_logo'] ?>" alt="MakaanDekho" class="nav-logo">
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

            $navItems = [
                ['label' => 'Home',              'slug' => null,              'href' => SITE_URL],
                ['label' => 'For Buyers',        'slug' => 'for_buyers',     'href' => '#'],
                ['label' => 'For Owners',        'slug' => 'for_owners',     'href' => '#'],
                ['label' => 'Insights',          'slug' => 'insights',       'href' => '#'],
                ['label' => 'Builders & Agents', 'slug' => 'builders_agents','href' => '#'],
            ];
            ?>
            <ul class="navbar-nav ms-auto me-auto">
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
                                    <?php foreach ($colItems as $mi): ?>
                                    <li><a href="<?= htmlspecialchars($mi['item_url']) ?>"><?= htmlspecialchars($mi['item_title']) ?></a></li>
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
                                    <a href="<?= SITE_URL ?>register.php" class="btn btn-light btn-sm">Join Network</a>
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
                <a href="#" class="btn btn-add-listing" data-bs-toggle="modal" data-bs-target="#postPropertyModal">
                    <i class="fas fa-home me-1"></i> Add listing
                </a>
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
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>register.php"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>admin/login.php"><i class="fas fa-shield-alt me-2"></i>Admin Login</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- ========== POST PROPERTY MODAL ========== -->
<div class="modal fade" id="postPropertyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content pp-modal">
      <!-- Header -->
      <div class="pp-modal-header">
        <h5>Post Your Property</h5>
        <p>Get listed quickly. We'll contact you for details.</p>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body -->
      <div class="pp-modal-body">
        <!-- Success message (hidden by default) -->
        <div id="ppSuccess" style="display:none;" class="text-center py-4">
          <div style="width:70px;height:70px;border-radius:50%;background:#d1fae5;color:#059669;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
            <i class="fas fa-check"></i>
          </div>
          <h5 style="font-weight:700;color:#1a2332;">Property Submitted!</h5>
          <p class="text-muted" style="font-size:14px;">Your property is under review. We'll contact you shortly.</p>
          <button class="btn btn-primary mt-2" onclick="resetPostForm()" style="border-radius:8px;">Post Another</button>
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
          <input type="text" name="name" class="pp-input" placeholder="Your Name" required>

          <!-- Phone & Email -->
          <div class="row g-3">
            <div class="col-6">
              <label class="pp-label">Phone</label>
              <input type="tel" name="phone" class="pp-input" placeholder="9876543210" maxlength="10" pattern="[0-9]{10}" required>
            </div>
            <div class="col-6">
              <label class="pp-label">Email</label>
              <input type="email" name="email" class="pp-input" placeholder="name@mail.com" required>
            </div>
          </div>

          <!-- City -->
          <label class="pp-label">City</label>
          <div class="pp-input-icon">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="city" class="pp-input" placeholder="e.g. Noida" required>
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
            By posting, you agree to our <a href="#" style="color:#2196F3;">Terms & Conditions</a>.
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
