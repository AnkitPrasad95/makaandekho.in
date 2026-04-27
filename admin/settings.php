<?php require_once 'includes/header.php'; ?>

<?php
$settings = $pdo->query("SELECT * FROM settings WHERE id=1 LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $site_name       = trim($_POST['site_name']       ?? '');
    $email           = trim($_POST['email']            ?? '');
    $whatsapp        = trim($_POST['whatsapp_number']  ?? '');
    $phone           = trim($_POST['phone']            ?? '');
    $address         = trim($_POST['address']          ?? '');
    $footer_text     = trim($_POST['footer_text']      ?? '');
    $facebook        = trim($_POST['facebook']         ?? '');
    $instagram       = trim($_POST['instagram']        ?? '');
    $twitter         = trim($_POST['twitter']          ?? '');
    $youtube         = trim($_POST['youtube']          ?? '');
    $linkedin        = trim($_POST['linkedin']         ?? '');
    $smtp_host       = trim($_POST['smtp_host']        ?? '');
    $smtp_user       = trim($_POST['smtp_user']        ?? '');
    $smtp_pass       = trim($_POST['smtp_pass']        ?? '');
    $smtp_port       = (int) ($_POST['smtp_port']      ?? 465);
    $meta_title      = trim($_POST['meta_title']       ?? '');
    $meta_description= trim($_POST['meta_description'] ?? '');
    $meta_keywords   = trim($_POST['meta_keywords']    ?? '');

    // Ensure upload dir exists
    if (!is_dir(UPLOAD_DIR . 'settings/')) {
        mkdir(UPLOAD_DIR . 'settings/', 0755, true);
    }

    // Logo upload
    $logo = $settings['site_logo'] ?? '';
    if (!empty($_FILES['site_logo']['name'])) {
        $ext      = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','svg','webp'];
        if (!in_array($ext, $allowed)) {
            flash('error', 'Logo must be JPG, PNG, SVG or WebP.');
            header('Location: ' . BASE_URL . 'settings.php');
            exit;
        }
        if ($_FILES['site_logo']['size'] > 2 * 1024 * 1024) {
            flash('error', 'Logo file size must be under 2 MB.');
            header('Location: ' . BASE_URL . 'settings.php');
            exit;
        }
        $filename = 'logo_' . time() . '.' . $ext;
        $dest     = UPLOAD_DIR . 'settings/' . $filename;
        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $dest)) {
            if ($logo && file_exists(UPLOAD_DIR . 'settings/' . $logo)) {
                @unlink(UPLOAD_DIR . 'settings/' . $logo);
            }
            $logo = $filename;
        }
    }

    // Favicon upload
    $favicon = $settings['favicon'] ?? '';
    if (!empty($_FILES['favicon']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
        $allowed = ['ico','png','jpg','jpeg','svg','webp'];
        if (!in_array($ext, $allowed)) {
            flash('error', 'Favicon must be ICO, PNG, SVG or WebP.');
            header('Location: ' . BASE_URL . 'settings.php');
            exit;
        }
        if ($_FILES['favicon']['size'] > 512 * 1024) {
            flash('error', 'Favicon file size must be under 512 KB.');
            header('Location: ' . BASE_URL . 'settings.php');
            exit;
        }
        $fav_filename = 'favicon_' . time() . '.' . $ext;
        $dest         = UPLOAD_DIR . 'settings/' . $fav_filename;
        if (move_uploaded_file($_FILES['favicon']['tmp_name'], $dest)) {
            if ($favicon && file_exists(UPLOAD_DIR . 'settings/' . $favicon)) {
                @unlink(UPLOAD_DIR . 'settings/' . $favicon);
            }
            $favicon = $fav_filename;
        }
    }

    // Keep existing SMTP password if left blank
    if (empty($smtp_pass) && !empty($settings['smtp_pass'])) {
        $smtp_pass = $settings['smtp_pass'];
    }

    if (!$settings) {
        $pdo->prepare("INSERT INTO settings
            (site_name,email,whatsapp_number,phone,address,footer_text,facebook,instagram,twitter,youtube,linkedin,smtp_host,smtp_user,smtp_pass,smtp_port,site_logo,favicon,meta_title,meta_description,meta_keywords)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$site_name,$email,$whatsapp,$phone,$address,$footer_text,$facebook,$instagram,$twitter,$youtube,$linkedin,$smtp_host,$smtp_user,$smtp_pass,$smtp_port,$logo,$favicon,$meta_title,$meta_description,$meta_keywords]);
    } else {
        $pdo->prepare("UPDATE settings SET
            site_name=?, email=?, whatsapp_number=?, phone=?, address=?,
            footer_text=?, facebook=?, instagram=?, twitter=?, youtube=?, linkedin=?,
            smtp_host=?, smtp_user=?, smtp_pass=?,
            smtp_port=?, site_logo=?, favicon=?,
            meta_title=?, meta_description=?, meta_keywords=?,
            updated_at=NOW()
            WHERE id=1")
            ->execute([$site_name,$email,$whatsapp,$phone,$address,$footer_text,$facebook,$instagram,$twitter,$youtube,$linkedin,$smtp_host,$smtp_user,$smtp_pass,$smtp_port,$logo,$favicon,$meta_title,$meta_description,$meta_keywords]);
    }

    flash('success', 'Settings saved successfully.');
    header('Location: ' . BASE_URL . 'settings.php');
    exit;
}
?>

<div class="page-header">
  <h4>Settings</h4>
  <p>Configure site-wide settings, branding and email</p>
</div>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

  <div class="row">

    <!-- General -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header"><i class="fas fa-globe mr-2 text-primary"></i>General Settings</div>
        <div class="card-body">

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Site Name</label>
            <input type="text" name="site_name" class="form-control"
                   value="<?= htmlspecialchars($settings['site_name'] ?? 'MakaanDekho') ?>" required>
          </div>

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Contact Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($settings['email'] ?? '') ?>"
                   placeholder="info@makaandekho.in">
          </div>

          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Phone Number</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">+91</span></div>
                  <input type="text" name="phone" class="form-control"
                         value="<?= htmlspecialchars($settings['phone'] ?? '') ?>"
                         placeholder="9999999999" maxlength="15">
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">WhatsApp Number</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">+91</span></div>
                  <input type="text" name="whatsapp_number" class="form-control"
                         value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>"
                         placeholder="9999999999" maxlength="15">
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Office Address</label>
            <textarea name="address" class="form-control" rows="2"
                      placeholder="Full address"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
          </div>

          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">Footer Text</label>
            <input type="text" name="footer_text" class="form-control"
                   value="<?= htmlspecialchars($settings['footer_text'] ?? '© 2024 MakaanDekho. All rights reserved.') ?>">
          </div>

        </div>
      </div>
    </div>

    <!-- Logo & SMTP -->
    <div class="col-lg-6 mb-4">

      <!-- Logo & Favicon -->
      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-image mr-2 text-success"></i>Site Logo &amp; Favicon</div>
        <div class="card-body">

          <!-- Logo -->
          <?php if (!empty($settings['site_logo'])): ?>
          <div class="mb-2">
            <img src="<?= UPLOAD_URL ?>settings/<?= htmlspecialchars($settings['site_logo']) ?>"
                 style="max-height:60px;max-width:200px;object-fit:contain;background:#f0f2f5;padding:8px;border-radius:6px;"
                 alt="Logo" onerror="this.style.display='none'">
          </div>
          <?php endif; ?>
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Upload New Logo</label>
            <input type="file" name="site_logo" class="form-control-file" accept="image/*">
            <small class="text-muted">JPG, PNG, SVG or WebP · Max 2 MB</small>
          </div>

          <hr>

          <!-- Favicon -->
          <label style="font-size:12.5px;font-weight:600;">Favicon</label>
          <?php if (!empty($settings['favicon'])): ?>
          <div class="mb-2 d-flex align-items-center">
            <img src="<?= UPLOAD_URL ?>settings/<?= htmlspecialchars($settings['favicon']) ?>"
                 style="width:32px;height:32px;object-fit:contain;background:#f0f2f5;padding:4px;border-radius:4px;margin-right:8px;"
                 alt="Favicon" onerror="this.style.display='none'">
            <small class="text-muted">Current favicon</small>
          </div>
          <?php endif; ?>
          <div class="form-group mb-0">
            <input type="file" name="favicon" class="form-control-file" accept="image/*,.ico">
            <small class="text-muted">ICO, PNG or WebP · Max 512 KB · Recommended: 32×32 or 64×64 px</small>
          </div>

        </div>
      </div>

      <!-- SMTP -->
      <div class="card">
        <div class="card-header"><i class="fas fa-envelope mr-2 text-warning"></i>Email (SMTP)</div>
        <div class="card-body">
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">SMTP Host</label>
            <input type="text" name="smtp_host" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>"
                   placeholder="websrv.htshosting.org">
          </div>
          <div class="row">
            <div class="col-8">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">SMTP Username</label>
                <input type="text" name="smtp_user" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>"
                       placeholder="info@makaandekho.in">
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Port</label>
                <input type="number" name="smtp_port" class="form-control form-control-sm"
                       value="<?= (int)($settings['smtp_port'] ?? 465) ?>"
                       placeholder="465">
              </div>
            </div>
          </div>
          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">SMTP Password</label>
            <input type="password" name="smtp_pass" class="form-control form-control-sm"
                   placeholder="Leave blank to keep current"
                   autocomplete="new-password">
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- Social Media -->
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header"><i class="fas fa-share-alt mr-2 text-danger"></i>Social Media Links</div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;"><i class="fab fa-facebook text-primary mr-1"></i> Facebook</label>
                <input type="url" name="facebook" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>"
                       placeholder="https://facebook.com/yourpage">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;"><i class="fab fa-instagram text-danger mr-1"></i> Instagram</label>
                <input type="url" name="instagram" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>"
                       placeholder="https://instagram.com/yourpage">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;"><i class="fab fa-twitter text-info mr-1"></i> Twitter / X</label>
                <input type="url" name="twitter" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['twitter'] ?? '') ?>"
                       placeholder="https://twitter.com/yourpage">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group mb-0">
                <label style="font-size:12.5px;font-weight:600;"><i class="fab fa-youtube text-danger mr-1"></i> YouTube</label>
                <input type="url" name="youtube" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['youtube'] ?? '') ?>"
                       placeholder="https://youtube.com/yourchannel">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group mb-0">
                <label style="font-size:12.5px;font-weight:600;"><i class="fab fa-linkedin text-primary mr-1"></i> LinkedIn</label>
                <input type="url" name="linkedin" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['linkedin'] ?? '') ?>"
                       placeholder="https://linkedin.com/company/yourpage">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SEO Settings -->
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header"><i class="fas fa-search mr-2 text-info"></i>SEO Settings <small class="text-muted font-weight-normal">(applied site-wide on all pages)</small></div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Default Meta Title</label>
                <input type="text" name="meta_title" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['meta_title'] ?? '') ?>"
                       placeholder="e.g. MakaanDekho – Buy, Sell &amp; Rent Properties in India"
                       maxlength="70">
                <small class="text-muted">Recommended: 50–60 characters</small>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Default Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($settings['meta_keywords'] ?? '') ?>"
                       placeholder="real estate, property, buy, sell, rent, India">
                <small class="text-muted">Comma-separated keywords</small>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group mb-0">
                <label style="font-size:12.5px;font-weight:600;">Default Meta Description</label>
                <textarea name="meta_description" class="form-control form-control-sm" rows="2"
                          placeholder="Brief description of your site for search engines (150–160 characters)"
                          maxlength="160"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
                <small class="text-muted">Recommended: 150–160 characters</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-right mb-4">
    <button type="submit" class="btn btn-primary px-4">
      <i class="fas fa-save mr-2"></i>Save Settings
    </button>
  </div>

</form>

<?php require_once 'includes/footer.php'; ?>
