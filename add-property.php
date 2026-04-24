<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

$user = current_user();
$locations = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY state, city")->fetchAll();
$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Basic Info
    $title        = trim($_POST['title'] ?? '');
    $prop_type    = $_POST['property_type'] ?? 'apartment';
    $category     = trim($_POST['category'] ?? '');
    $listing_type = $_POST['listing_type'] ?? 'sale';
    $price        = is_numeric($_POST['price'] ?? '') ? (float)$_POST['price'] : null;
    $price_type   = $_POST['price_type'] ?? 'total';
    $description  = trim($_POST['description'] ?? '');
    $short_desc   = trim($_POST['short_description'] ?? '');
    $bedrooms     = is_numeric($_POST['bedrooms'] ?? '') ? (int)$_POST['bedrooms'] : null;
    $bathrooms    = is_numeric($_POST['bathrooms'] ?? '') ? (int)$_POST['bathrooms'] : null;
    $area_sqft    = is_numeric($_POST['area_sqft'] ?? '') ? (float)$_POST['area_sqft'] : null;
    $floor        = is_numeric($_POST['floor'] ?? '') ? (int)$_POST['floor'] : null;
    $total_floors = is_numeric($_POST['total_floors'] ?? '') ? (int)$_POST['total_floors'] : null;
    $furnishing   = $_POST['furnishing'] ?? '';
    $property_age = trim($_POST['property_age'] ?? '');
    $availability = $_POST['availability'] ?? 'available';

    // Location
    $country     = trim($_POST['country'] ?? 'India');
    $state       = trim($_POST['state'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $area        = trim($_POST['area'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $pincode     = trim($_POST['pincode'] ?? '');
    $google_map  = trim($_POST['google_map'] ?? '');
    $location_id = is_numeric($_POST['location_id'] ?? '') ? (int)$_POST['location_id'] : null;

    // Step 3: RERA
    $rera_number     = trim($_POST['rera_number'] ?? '');
    $registry_number = trim($_POST['registry_number'] ?? '');

    // Amenities
    $amenities = json_encode($_POST['amenities'] ?? []);

    // Validation
    if (!$title) $errors[] = 'Property title is required.';
    if (!$price) $errors[] = 'Price is required.';
    if (!$rera_number && !$registry_number) $errors[] = 'RERA Number or Registry Number is required for verification.';

    if (empty($errors)) {
        // Featured image
        $featured_image = '';
        if (!empty($_FILES['featured_image']['name']) && !$_FILES['featured_image']['error']) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $fname = 'prop_' . time() . '_feat.' . $ext;
                move_uploaded_file($_FILES['featured_image']['tmp_name'], __DIR__ . '/uploads/properties/' . $fname);
                $featured_image = $fname;
            }
        }

        // Slug
        $slug_base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
        $slug = $slug_base; $si = 1;
        while ($pdo->prepare("SELECT id FROM properties WHERE slug=?")->execute([$slug]) && $pdo->query("SELECT FOUND_ROWS()")->fetchColumn()) {
            $chk = $pdo->prepare("SELECT id FROM properties WHERE slug=?");
            $chk->execute([$slug]);
            if (!$chk->fetch()) break;
            $slug = $slug_base . '-' . (++$si);
        }

        // Insert property
        $pdo->prepare("
            INSERT INTO properties (title, slug, short_description, description, price, price_type,
                property_type, category, listing_type, bedrooms, bathrooms, area_sqft, floor, total_floors,
                furnishing, property_age, address, country, pincode, google_map, location_id,
                featured_image, amenities, user_id, rera_number, registry_number, availability,
                status, publish_status, created_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'pending','draft',NOW())
        ")->execute([
            $title, $slug, $short_desc, $description, $price, $price_type,
            $prop_type, $category, $listing_type, $bedrooms, $bathrooms, $area_sqft, $floor, $total_floors,
            $furnishing, $property_age, $address, $country, $pincode, $google_map, $location_id,
            $featured_image, $amenities, current_user_id(), $rera_number, $registry_number, $availability
        ]);
        $property_id = (int)$pdo->lastInsertId();

        // Gallery images
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $k => $tmp) {
                if (!$_FILES['gallery']['error'][$k] && $tmp) {
                    $ext = strtolower(pathinfo($_FILES['gallery']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                        $gname = 'prop_' . $property_id . '_' . time() . '_' . $k . '.' . $ext;
                        move_uploaded_file($tmp, __DIR__ . '/uploads/properties/' . $gname);
                        $pdo->prepare("INSERT INTO property_images (property_id, image, is_primary) VALUES (?,?,0)")
                            ->execute([$property_id, $gname]);
                    }
                }
            }
        }

        // Legal documents
        if (!empty($_FILES['documents']['name'][0])) {
            foreach ($_FILES['documents']['tmp_name'] as $k => $tmp) {
                if (!$_FILES['documents']['error'][$k] && $tmp) {
                    $ext = strtolower(pathinfo($_FILES['documents']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext, ['pdf','jpg','jpeg','png'])) {
                        $dname = 'doc_' . $property_id . '_' . time() . '_' . $k . '.' . $ext;
                        $origName = $_FILES['documents']['name'][$k];
                        move_uploaded_file($tmp, __DIR__ . '/uploads/properties/' . $dname);
                        $pdo->prepare("INSERT INTO property_documents (property_id, doc_type, file_name, original_name) VALUES (?,?,?,?)")
                            ->execute([$property_id, 'registration', $dname, $origName]);
                    }
                }
            }
        }

        user_flash('success', 'Property submitted for verification! You will be notified once approved.');
        header('Location: ' . SITE_URL . 'my-properties.php');
        exit;
    }
}

$pageTitle   = 'Add Property | MakaanDekho';
$pageNoIndex = true;
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>add-property.php" class="dash-nav-item active"><i class="fas fa-plus"></i> Add Property</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item"><i class="fas fa-user"></i> Profile</a>
    </div>

    <div class="wizard-card">
        <h3 class="wizard-title"><i class="fas fa-home me-2"></i>Add New Property</h3>

        <!-- Step Indicators -->
        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1"><span>1</span> Basic Info</div>
            <div class="wizard-step" data-step="2"><span>2</span> Photos & Docs</div>
            <div class="wizard-step" data-step="3"><span>3</span> RERA Verification</div>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="border-radius:10px;font-size:13px;margin-top:16px;">
            <strong><i class="fas fa-exclamation-circle me-1"></i>Please fix:</strong>
            <ul class="mb-0 mt-1 ps-3"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="addPropertyForm">

        <!-- ===== STEP 1: Basic Info ===== -->
        <div class="wizard-panel active" id="step1">
            <h5 class="wizard-section-title"><i class="fas fa-info-circle"></i> Property Details</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="wz-label">Property Title *</label>
                    <input type="text" name="title" class="wz-input" placeholder="e.g. 3 BHK Apartment in Sector 56" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Property Type</label>
                    <select name="property_type" class="wz-input">
                        <?php foreach(['apartment'=>'Apartment/Flat','villa'=>'Villa','plot'=>'Plot/Land','commercial'=>'Commercial','office'=>'Office'] as $v=>$l): ?>
                        <option value="<?=$v?>" <?= ($_POST['property_type']??'apartment')===$v?'selected':'' ?>><?=$l?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Listing Type</label>
                    <select name="listing_type" class="wz-input">
                        <option value="sale" <?= ($_POST['listing_type']??'sale')==='sale'?'selected':'' ?>>For Sale</option>
                        <option value="rent" <?= ($_POST['listing_type']??'')==='rent'?'selected':'' ?>>For Rent</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Status</label>
                    <select name="availability" class="wz-input">
                        <option value="available">Ready to Move</option>
                        <option value="under_construction">Under Construction</option>
                        <option value="new_launch">New Launch</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Price (₹) *</label>
                    <input type="number" name="price" class="wz-input" placeholder="e.g. 5000000" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Bedrooms</label>
                    <select name="bedrooms" class="wz-input">
                        <option value="">Select</option>
                        <?php for($b=1;$b<=7;$b++): ?><option value="<?=$b?>" <?= ($_POST['bedrooms']??'')==$b?'selected':'' ?>><?=$b?> BHK</option><?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Bathrooms</label>
                    <select name="bathrooms" class="wz-input">
                        <option value="">Select</option>
                        <?php for($b=1;$b<=6;$b++): ?><option value="<?=$b?>" <?= ($_POST['bathrooms']??'')==$b?'selected':'' ?>><?=$b?></option><?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Area (sq.ft)</label>
                    <input type="number" name="area_sqft" class="wz-input" placeholder="e.g. 1200" value="<?= htmlspecialchars($_POST['area_sqft'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Furnishing</label>
                    <select name="furnishing" class="wz-input">
                        <option value="">Select</option>
                        <option value="unfurnished">Unfurnished</option>
                        <option value="semi-furnished">Semi-Furnished</option>
                        <option value="furnished">Furnished</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">State</label>
                    <input type="text" name="state" id="apState" class="wz-input" placeholder="e.g. Uttar Pradesh" value="<?= htmlspecialchars($_POST['state'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="wz-label">City</label>
                    <input type="text" name="city" id="apCity" class="wz-input" placeholder="e.g. Ghaziabad" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Area / Locality</label>
                    <input type="text" name="area" id="apArea" class="wz-input" placeholder="e.g. Sector 56" value="<?= htmlspecialchars($_POST['area'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="wz-label">Full Address</label>
                    <input type="text" name="address" id="apAddress" class="wz-input" placeholder="Street, Landmark, Area" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" autocomplete="off" spellcheck="false">
                </div>
                <div class="col-12">
                    <label class="wz-label">Description</label>
                    <textarea name="description" class="wz-input" rows="4" placeholder="Describe your property..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="wizard-actions">
                <div></div>
                <button type="button" class="btn-wz-next" onclick="goStep(2)">Next: Photos & Docs <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
        </div>

        <!-- ===== STEP 2: Photos & Documents ===== -->
        <div class="wizard-panel" id="step2">
            <h5 class="wizard-section-title"><i class="fas fa-images"></i> Photos & Documents</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="wz-label">Featured Image *</label>
                    <div class="wz-upload" onclick="this.querySelector('input').click()">
                        <input type="file" name="featured_image" accept="image/*" style="display:none" onchange="previewFile(this,'featPrev')">
                        <i class="fas fa-camera"></i>
                        <p>Click to upload main photo<br><small>JPG, PNG, WebP</small></p>
                    </div>
                    <img id="featPrev" src="" style="display:none;width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;">
                </div>
                <div class="col-md-6">
                    <label class="wz-label">Gallery Images</label>
                    <div class="wz-upload" onclick="this.querySelector('input').click()">
                        <input type="file" name="gallery[]" accept="image/*" multiple style="display:none" onchange="previewMulti(this,'gallPrev')">
                        <i class="fas fa-images"></i>
                        <p>Click to upload gallery (multiple)<br><small>JPG, PNG, WebP</small></p>
                    </div>
                    <div id="gallPrev" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>
                <div class="col-12">
                    <label class="wz-label"><i class="fas fa-file-pdf me-1 text-danger"></i>Legal Documents (Registration / Sale Deed)</label>
                    <div class="wz-upload" onclick="this.querySelector('input').click()">
                        <input type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" multiple style="display:none" onchange="showDocNames(this,'docNames')">
                        <i class="fas fa-file-upload"></i>
                        <p>Upload scanned documents<br><small>PDF, JPG, PNG – for admin verification</small></p>
                    </div>
                    <div id="docNames" class="mt-2" style="font-size:13px;"></div>
                </div>
            </div>
            <div class="wizard-actions">
                <button type="button" class="btn-wz-prev" onclick="goStep(1)"><i class="fas fa-arrow-left me-1"></i> Back</button>
                <button type="button" class="btn-wz-next" onclick="goStep(3)">Next: RERA Verification <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
        </div>

        <!-- ===== STEP 3: RERA Verification ===== -->
        <div class="wizard-panel" id="step3">
            <h5 class="wizard-section-title"><i class="fas fa-shield-alt"></i> Registration & Verification</h5>
            <div class="wz-rera-notice">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Mandatory Verification</strong>
                    <p>All properties must provide a RERA Number or Registry/Sale Deed Number. Properties are reviewed by admin and go live only after verification.</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="wz-label">RERA Registration Number</label>
                    <input type="text" name="rera_number" class="wz-input" placeholder="e.g. UPRERAPRJ12345" value="<?= htmlspecialchars($_POST['rera_number'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="wz-label">Registry / Sale Deed Number</label>
                    <input type="text" name="registry_number" class="wz-input" placeholder="e.g. SD-2024-00123" value="<?= htmlspecialchars($_POST['registry_number'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="wz-label">Google Map Embed URL <small class="text-muted">(optional)</small></label>
                    <input type="url" name="google_map" class="wz-input" placeholder="https://www.google.com/maps/embed?..." value="<?= htmlspecialchars($_POST['google_map'] ?? '') ?>">
                </div>
            </div>
            <div class="wizard-actions">
                <button type="button" class="btn-wz-prev" onclick="goStep(2)"><i class="fas fa-arrow-left me-1"></i> Back</button>
                <button type="submit" class="btn-wz-submit"><i class="fas fa-paper-plane me-2"></i>Submit for Verification</button>
            </div>
        </div>

        <input type="hidden" name="country" value="India">
        <input type="hidden" name="price_type" value="total">
        </form>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
function goStep(n) {
    document.querySelectorAll('.wizard-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.wizard-step').forEach(function(s, i){
        s.classList.toggle('active', i < n);
        s.classList.toggle('done', i < n - 1);
    });
    document.getElementById('step' + n).classList.add('active');
    window.scrollTo({top: 200, behavior: 'smooth'});
}
function previewFile(input, targetId) {
    if (input.files[0]) {
        var r = new FileReader();
        r.onload = function(e) { var img = document.getElementById(targetId); img.src = e.target.result; img.style.display = 'block'; };
        r.readAsDataURL(input.files[0]);
    }
}
function previewMulti(input, targetId) {
    var c = document.getElementById(targetId); c.innerHTML = '';
    Array.from(input.files).forEach(function(f) {
        var r = new FileReader();
        r.onload = function(e) { c.innerHTML += '<img src="'+e.target.result+'" style="width:65px;height:65px;object-fit:cover;border-radius:6px;">'; };
        r.readAsDataURL(f);
    });
}
function showDocNames(input, targetId) {
    var c = document.getElementById(targetId); c.innerHTML = '';
    Array.from(input.files).forEach(function(f) {
        c.innerHTML += '<div><i class="fas fa-file me-1 text-primary"></i>' + f.name + '</div>';
    });
}
</script>
