<?php
ob_start();
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { flash('error', 'Invalid property.'); header('Location: ' . BASE_URL . 'properties.php'); exit; }

$prop = $pdo->prepare("SELECT * FROM properties WHERE id=? AND is_deleted=0");
$prop->execute([$id]);
$prop = $prop->fetch();
if (!$prop) { flash('error', 'Property not found.'); header('Location: ' . BASE_URL . 'properties.php'); exit; }

// Existing gallery images
$gallery = $pdo->prepare("SELECT * FROM property_images WHERE property_id=? AND is_deleted=0 ORDER BY is_primary DESC, id ASC");
$gallery->execute([$id]);
$gallery = $gallery->fetchAll();

$locations = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY state,city")->fetchAll();
$users     = $pdo->query("SELECT id,name,email,role FROM users WHERE status='active' AND is_deleted=0 ORDER BY name")->fetchAll();

$amenity_list = [
    'parking'=>'Parking','lift'=>'Lift','security'=>'Security',
    'power_backup'=>'Power Backup','gym'=>'Gym','swimming_pool'=>'Swimming Pool',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    // Delete gallery images if requested
    if (!empty($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $img_id) {
            $img = $pdo->prepare("SELECT image FROM property_images WHERE id=? AND property_id=?");
            $img->execute([(int)$img_id, $id]);
            $img = $img->fetch();
            if ($img) {
                @unlink(UPLOAD_DIR . 'properties/' . $img['image']);
                $pdo->prepare("UPDATE property_images SET is_deleted=1, deleted_at=NOW() WHERE id=?")->execute([(int)$img_id]);
            }
        }
    }

    $title           = trim($_POST['title']            ?? '');
    $prop_type       = $_POST['property_type']         ?? 'apartment';
    $category        = trim($_POST['category']         ?? '');
    $listing_type    = $_POST['listing_type']          ?? 'sale';
    $price           = is_numeric($_POST['price']??'') ? (float)$_POST['price'] : null;
    $price_type      = $_POST['price_type']            ?? 'total';
    $country         = trim($_POST['country']          ?? 'India');
    $pincode         = trim($_POST['pincode']          ?? '');
    $google_map      = trim($_POST['google_map']       ?? '');
    $location_id     = is_numeric($_POST['location_id']??'') ? (int)$_POST['location_id'] : null;
    $address         = trim($_POST['address']          ?? '');
    $bedrooms        = is_numeric($_POST['bedrooms']??'')     ? (int)$_POST['bedrooms']    : null;
    $bathrooms       = is_numeric($_POST['bathrooms']??'')    ? (int)$_POST['bathrooms']   : null;
    $area_sqft       = is_numeric($_POST['area_sqft']??'')    ? (float)$_POST['area_sqft'] : null;
    $floor           = is_numeric($_POST['floor']??'')        ? (int)$_POST['floor']       : null;
    $total_floors    = is_numeric($_POST['total_floors']??'') ? (int)$_POST['total_floors']: null;
    $furnishing      = $_POST['furnishing']            ?? '';
    $property_age    = trim($_POST['property_age']     ?? '');
    $builder_name    = trim($_POST['builder_name']     ?? '');
    $contact_person  = trim($_POST['contact_person']   ?? '');
    $builder_phone   = trim($_POST['builder_phone']    ?? '');
    $builder_email   = trim($_POST['builder_email']    ?? '');
    $video_url       = trim($_POST['video_url']        ?? '');
    $amenities       = json_encode($_POST['amenities'] ?? []);
    $short_desc      = trim($_POST['short_description']?? '');
    $description     = trim($_POST['description']      ?? '');
    $availability    = $_POST['availability']          ?? 'available';
    $status          = $_POST['status']                ?? 'approved';
    $publish_status  = $_POST['publish_status']        ?? 'published';
    $featured        = isset($_POST['featured']) ? 1 : 0;
    $is_trending     = isset($_POST['is_trending']) ? 1 : 0;
    $is_recommended  = isset($_POST['is_recommended']) ? 1 : 0;
    $meta_title      = trim($_POST['meta_title']       ?? '');
    $meta_desc       = trim($_POST['meta_description'] ?? '');
    $meta_keywords   = trim($_POST['meta_keywords']    ?? '');
    $user_id         = is_numeric($_POST['user_id']??'') ? (int)$_POST['user_id'] : null;
    $agent_id        = is_numeric($_POST['agent_id']??'') ? (int)$_POST['agent_id'] : null;

    if (!$title) $errors[] = 'Property title is required.';
    if (!$price) $errors[] = 'Price is required.';

    if (empty($errors)) {

        // Featured image — keep existing unless new uploaded
        $featured_image = $prop['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext,['jpg','jpeg','png','webp'])) {
                $fname = 'prop_'.time().'_feat.'.$ext;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], UPLOAD_DIR.'properties/'.$fname)) {
                    // Remove old featured image
                    if ($featured_image && file_exists(UPLOAD_DIR.'properties/'.$featured_image)) {
                        @unlink(UPLOAD_DIR.'properties/'.$featured_image);
                    }
                    $featured_image = $fname;
                }
            }
        }

        // Update slug only if title changed
        $slug = $prop['slug'];
        if ($title !== $prop['title']) {
            $slug_base = strtolower(preg_replace('/[^a-z0-9]+/i','-',$title));
            $slug=$slug_base; $i=1;
            while(true){
                $s=$pdo->prepare("SELECT id FROM properties WHERE slug=? AND id!=?");$s->execute([$slug,$id]);
                if(!$s->fetch()) break; $slug=$slug_base.'-'.(++$i);
            }
        }

        $pdo->prepare("
            UPDATE properties SET
              title=?,slug=?,short_description=?,description=?,price=?,price_type=?,property_type=?,category=?,
              listing_type=?,bedrooms=?,bathrooms=?,area_sqft=?,floor=?,total_floors=?,furnishing=?,property_age=?,
              address=?,country=?,pincode=?,google_map=?,location_id=?,
              builder_name=?,contact_person=?,builder_phone=?,builder_email=?,
              featured_image=?,video_url=?,amenities=?,user_id=?,agent_id=?,
              status=?,availability=?,publish_status=?,featured=?,is_trending=?,is_recommended=?,
              meta_title=?,meta_description=?,meta_keywords=?,
              updated_at=NOW()
            WHERE id=?
        ")->execute([
            $title,$slug,$short_desc,$description,$price,$price_type,$prop_type,$category,
            $listing_type,$bedrooms,$bathrooms,$area_sqft,$floor,$total_floors,$furnishing,$property_age,
            $address,$country,$pincode,$google_map,$location_id,
            $builder_name,$contact_person,$builder_phone,$builder_email,
            $featured_image,$video_url,$amenities,$user_id,$agent_id,
            $status,$availability,$publish_status,$featured,$is_trending,$is_recommended,
            $meta_title,$meta_desc,$meta_keywords,
            $id
        ]);

        // New gallery images
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $k => $tmp) {
                if (!$_FILES['gallery']['error'][$k] && $tmp) {
                    $ext=strtolower(pathinfo($_FILES['gallery']['name'][$k],PATHINFO_EXTENSION));
                    if (in_array($ext,['jpg','jpeg','png','webp'])) {
                        $gname='prop_'.$id.'_'.time().'_'.$k.'.'.$ext;
                        if (move_uploaded_file($tmp, UPLOAD_DIR.'properties/'.$gname)) {
                            $pdo->prepare("INSERT INTO property_images (property_id,image,is_primary) VALUES (?,?,0)")
                                ->execute([$id,$gname]);
                        }
                    }
                }
            }
        }

        flash('success', 'Property "' . $title . '" updated successfully.');
        header('Location: '.BASE_URL.'property-view.php?id='.$id);
        exit;
    }
}

// Use POST data if validation failed, otherwise use DB data
$d = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $prop;
$existing_amenities = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $existing_amenities = $_POST['amenities'] ?? [];
} else {
    $existing_amenities = json_decode($prop['amenities'] ?? '[]', true) ?: [];
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Edit Property</h4>
    <p>
      <a href="<?=BASE_URL?>properties.php" class="text-muted">Properties</a>
      <i class="fas fa-angle-right mx-1 text-muted" style="font-size:11px;"></i>
      <?= htmlspecialchars($prop['title']) ?>
    </p>
  </div>
  <div>
    <a href="<?=BASE_URL?>property-view.php?id=<?=$id?>" class="btn btn-outline-info btn-sm mr-1">
      <i class="fas fa-eye mr-1"></i>View
    </a>
    <a href="<?=BASE_URL?>properties.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i>Back
    </a>
  </div>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-4" style="border-radius:10px;">
  <strong><i class="fas fa-exclamation-circle mr-1"></i>Fix errors:</strong>
  <ul class="mb-0 mt-1 pl-3"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

<div class="row">

  <!-- ── LEFT COLUMN ── -->
  <div class="col-lg-8">

    <!-- Basic Details -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-home mr-2 text-primary"></i>Basic Details</div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Property Title *</label>
          <input type="text" name="title" class="form-control" placeholder="e.g. 3 BHK Apartment in Sector 56" value="<?= htmlspecialchars($d['title']??'') ?>" required>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Property Type</label>
            <select name="property_type" class="form-control">
              <?php foreach(['apartment'=>'Apartment','villa'=>'Villa','plot'=>'Plot/Land','commercial'=>'Commercial','office'=>'Office'] as $v=>$l): ?>
              <option value="<?=$v?>" <?= ($d['property_type']??'apartment')===$v?'selected':'' ?>><?=$l?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Category</label>
            <select name="category" class="form-control">
              <option value="">– Select –</option>
              <?php foreach(['Residential','Commercial','Industrial','Agricultural','Other'] as $c): ?>
              <option value="<?=$c?>" <?= ($d['category']??'')===$c?'selected':'' ?>><?=$c?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Listing Type</label>
            <select name="listing_type" class="form-control">
              <option value="sale" <?= ($d['listing_type']??'sale')==='sale'?'selected':'' ?>>For Sale</option>
              <option value="rent" <?= ($d['listing_type']??'')==='rent'?'selected':'' ?>>For Rent</option>
            </select>
          </div></div>
          <div class="col-md-6"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Price (₹) *</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text" style="border-radius:8px 0 0 8px;">₹</span></div>
              <input type="number" name="price" class="form-control" style="border-radius:0 8px 8px 0;border-left:none;" placeholder="e.g. 5000000" value="<?= htmlspecialchars($d['price']??'') ?>" required>
            </div>
          </div></div>
          <div class="col-md-6"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Price Type</label>
            <select name="price_type" class="form-control">
              <option value="total"     <?= ($d['price_type']??'total')==='total'?'selected':'' ?>>Total Price</option>
              <option value="per_sqft"  <?= ($d['price_type']??'')==='per_sqft'?'selected':'' ?>>Per Sq.ft</option>
              <option value="per_month" <?= ($d['price_type']??'')==='per_month'?'selected':'' ?>>Per Month</option>
            </select>
          </div></div>
        </div>
      </div>
    </div>

    <!-- Location -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>Location Details</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($d['country']??'India') ?>">
          </div></div>
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Location (from list)</label>
            <select name="location_id" class="form-control">
              <option value="">– Select –</option>
              <?php foreach($locations as $loc): ?>
              <option value="<?=$loc['id']?>" <?= ($d['location_id']??'')==$loc['id']?'selected':'' ?>>
                <?= htmlspecialchars($loc['city'].($loc['area']?' – '.$loc['area']:'').', '.$loc['state']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Pincode</label>
            <input type="text" name="pincode" class="form-control" maxlength="10" placeholder="110001" value="<?= htmlspecialchars($d['pincode']??'') ?>">
          </div></div>
          <div class="col-12"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Full Address</label>
            <input type="text" name="address" class="form-control" placeholder="Street, Locality, City" value="<?= htmlspecialchars($d['address']??'') ?>">
          </div></div>
          <div class="col-12"><div class="form-group mb-0">
            <label class="form-label" style="font-size:12.5px;font-weight:600;"><i class="fas fa-map mr-1 text-danger"></i>Google Map Link</label>
            <div class="input-group">
              <input type="url" name="google_map" id="googleMapUrl" class="form-control" placeholder="Auto-generated or paste manually" value="<?= htmlspecialchars($d['google_map']??'') ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-primary" id="generateMapLink" title="Generate from address fields">
                  <i class="fas fa-sync-alt mr-1"></i>Generate
                </button>
                <a href="#" class="btn btn-outline-success" id="testMapLink" target="_blank" title="Test link in Google Maps">
                  <i class="fas fa-external-link-alt"></i>
                </a>
              </div>
            </div>
            <small class="text-muted">Click "Generate" to create link from address fields, or paste your own Google Maps URL</small>
          </div></div>
        </div>
      </div>
    </div>

    <!-- Property Details -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-building mr-2 text-success"></i>Property Details</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">BHK</label>
            <select name="bedrooms" class="form-control">
              <option value="">–</option>
              <?php for($b=1;$b<=7;$b++): ?><option value="<?=$b?>" <?= ($d['bedrooms']??'')==$b?'selected':'' ?>><?=$b?> BHK</option><?php endfor; ?>
            </select>
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Bathrooms</label>
            <select name="bathrooms" class="form-control">
              <option value="">–</option>
              <?php for($b=1;$b<=6;$b++): ?><option value="<?=$b?>" <?= ($d['bathrooms']??'')==$b?'selected':'' ?>><?=$b?></option><?php endfor; ?>
            </select>
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Area (sq.ft)</label>
            <input type="number" name="area_sqft" class="form-control" placeholder="1200" value="<?= htmlspecialchars($d['area_sqft']??'') ?>">
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Floor No.</label>
            <input type="number" name="floor" class="form-control" placeholder="3" value="<?= htmlspecialchars($d['floor']??'') ?>">
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Total Floors</label>
            <input type="number" name="total_floors" class="form-control" placeholder="10" value="<?= htmlspecialchars($d['total_floors']??'') ?>">
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Furnishing</label>
            <select name="furnishing" class="form-control">
              <option value="">–</option>
              <?php foreach(['unfurnished'=>'Unfurnished','semi-furnished'=>'Semi Furnished','furnished'=>'Fully Furnished'] as $v=>$l): ?>
              <option value="<?=$v?>" <?= ($d['furnishing']??'')===$v?'selected':'' ?>><?=$l?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-3"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Property Age</label>
            <select name="property_age" class="form-control">
              <option value="">–</option>
              <?php foreach(['New Construction','Less than 1 year','1-3 years','3-5 years','5-10 years','10+ years'] as $a): ?>
              <option value="<?=$a?>" <?= ($d['property_age']??'')===$a?'selected':'' ?>><?=$a?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
        </div>
      </div>
    </div>

    <!-- Builder Info -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-hard-hat mr-2 text-warning"></i>Builder / Agent Info</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Builder / Company Name</label>
            <input type="text" name="builder_name" class="form-control" value="<?= htmlspecialchars($d['builder_name']??'') ?>">
          </div></div>
          <div class="col-md-6"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($d['contact_person']??'') ?>">
          </div></div>
          <div class="col-md-6"><div class="form-group">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Phone</label>
            <input type="tel" name="builder_phone" class="form-control" value="<?= htmlspecialchars($d['builder_phone']??'') ?>">
          </div></div>
          <div class="col-md-6"><div class="form-group mb-0">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Email</label>
            <input type="email" name="builder_email" class="form-control" value="<?= htmlspecialchars($d['builder_email']??'') ?>">
          </div></div>
        </div>
      </div>
    </div>

    <!-- Media -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-images mr-2 text-info"></i>Media Upload</div>
      <div class="card-body">

        <!-- Current Featured Image -->
        <?php if (!empty($prop['featured_image'])): ?>
        <div class="mb-3">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Current Featured Image</label>
          <div>
            <img src="<?= UPLOAD_URL ?>properties/<?= htmlspecialchars($prop['featured_image']) ?>"
                 style="height:100px;object-fit:cover;border-radius:8px;" alt="Featured"
                 onerror="this.style.display='none'">
          </div>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-5">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Change Featured Image</label>
            <label style="border:2px dashed #d1d5db;border-radius:10px;padding:24px;text-align:center;cursor:pointer;display:block;background:#fafafa;">
              <input type="file" name="featured_image" accept="image/*" id="featInput" style="display:none;">
              <i class="fas fa-camera fa-2x text-muted mb-2" id="featIcon"></i>
              <div id="featText" style="font-size:13px;color:#6b7280;">Click to upload<br><small>JPG, PNG · Max 5MB</small></div>
            </label>
            <img id="featPreview" src="" style="display:none;width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;">
          </div>
          <div class="col-md-7">
            <label class="form-label" style="font-size:12.5px;font-weight:600;">Add More Gallery Images</label>
            <label style="border:2px dashed #d1d5db;border-radius:10px;padding:24px;text-align:center;cursor:pointer;display:block;background:#fafafa;">
              <input type="file" name="gallery[]" accept="image/*" multiple id="galleryInput" style="display:none;">
              <i class="fas fa-images fa-2x text-muted mb-2"></i>
              <div style="font-size:13px;color:#6b7280;">Select multiple files<br><small>JPG, PNG · Max 5MB each</small></div>
            </label>
            <div id="galleryPreview" class="d-flex flex-wrap mt-2"></div>
          </div>
        </div>

        <!-- Existing Gallery -->
        <?php if (!empty($gallery)): ?>
        <hr>
        <label class="form-label" style="font-size:12.5px;font-weight:600;">Existing Gallery Images <small class="text-muted">(check to delete)</small></label>
        <div class="d-flex flex-wrap">
          <?php foreach($gallery as $img): ?>
          <div class="position-relative mr-2 mb-2" style="width:90px;">
            <img src="<?= UPLOAD_URL ?>properties/<?= htmlspecialchars($img['image']) ?>"
                 style="width:90px;height:90px;object-fit:cover;border-radius:6px;" alt="Gallery"
                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 90 90%22><rect fill=%22%23eee%22 width=%2290%22 height=%2290%22/></svg>'">
            <label style="position:absolute;top:2px;right:2px;background:rgba(220,53,69,0.9);color:#fff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;">
              <input type="checkbox" name="delete_images[]" value="<?=$img['id']?>" style="display:none;">
              <i class="fas fa-times"></i>
            </label>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="form-group mt-3 mb-0">
          <label class="form-label" style="font-size:12.5px;font-weight:600;"><i class="fab fa-youtube mr-1 text-danger"></i>Video URL</label>
          <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..." value="<?= htmlspecialchars($d['video_url']??'') ?>">
        </div>
      </div>
    </div>

    <!-- Amenities -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-star mr-2 text-warning"></i>Amenities</div>
      <div class="card-body">
        <div class="row">
          <?php
          $icons=['parking'=>'fa-car','lift'=>'fa-elevator','security'=>'fa-shield-alt','power_backup'=>'fa-bolt','gym'=>'fa-dumbbell','swimming_pool'=>'fa-swimming-pool'];
          foreach($amenity_list as $key=>$label):
            $chk=in_array($key,$existing_amenities);
          ?>
          <div class="col-md-4 mb-2">
            <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid <?=$chk?'#1e40af':'#e5e7eb'?>;border-radius:8px;background:<?=$chk?'#eff6ff':'#fff'?>;cursor:pointer;font-size:13px;font-weight:500;margin:0;" class="amenity-lbl">
              <input type="checkbox" name="amenities[]" value="<?=$key?>" <?=$chk?'checked':''?> style="display:none;">
              <i class="fas <?=$icons[$key]?> text-primary"></i> <?=$label?>
            </label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-align-left mr-2"></i>Description</div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Short Description</label>
          <textarea name="short_description" class="form-control" rows="2" placeholder="Brief 1-2 line summary"><?= htmlspecialchars($d['short_description']??'') ?></textarea>
        </div>
        <div class="form-group mb-0">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Full Description</label>
          <textarea name="description" class="form-control" rows="6" placeholder="Detailed description…"><?= htmlspecialchars($d['description']??'') ?></textarea>
        </div>
      </div>
    </div>

    <!-- SEO -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-search mr-2 text-success"></i>SEO Settings</div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" maxlength="255" placeholder="SEO title" value="<?= htmlspecialchars($d['meta_title']??'') ?>">
        </div>
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="2" placeholder="SEO description"><?= htmlspecialchars($d['meta_description']??'') ?></textarea>
        </div>
        <div class="form-group mb-0">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Meta Keywords</label>
          <input type="text" name="meta_keywords" class="form-control"
                 placeholder="apartment, delhi, 3bhk, sale"
                 value="<?= htmlspecialchars($d['meta_keywords']??'') ?>">
          <small class="text-muted">Comma-separated keywords</small>
        </div>
      </div>
    </div>

  </div>

  <!-- ── RIGHT COLUMN ── -->
  <div class="col-lg-4">

    <!-- Status -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-toggle-on mr-2"></i>Status & Visibility</div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Approval Status</label>
          <select name="status" class="form-control">
            <option value="approved" <?= ($d['status']??'')==='approved'?'selected':'' ?>>Approved</option>
            <option value="pending"  <?= ($d['status']??'')==='pending'?'selected':'' ?>>Pending</option>
            <option value="rejected" <?= ($d['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Publish Status</label>
          <select name="publish_status" class="form-control">
            <option value="published" <?= ($d['publish_status']??'')==='published'?'selected':'' ?>>Published</option>
            <option value="draft"     <?= ($d['publish_status']??'')==='draft'?'selected':'' ?>>Draft</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Availability</label>
          <select name="availability" class="form-control">
            <option value="available" <?= ($d['availability']??'')==='available'?'selected':'' ?>>Available</option>
            <option value="sold"      <?= ($d['availability']??'')==='sold'?'selected':'' ?>>Sold</option>
          </select>
        </div>
        <div class="custom-control custom-switch mb-2">
          <input type="checkbox" class="custom-control-input" id="featuredSwitch" name="featured" value="1" <?= !empty($d['featured'])?'checked':'' ?>>
          <label class="custom-control-label" for="featuredSwitch" style="font-size:13px;font-weight:600;">Mark as Featured ⭐</label>
        </div>
        <div class="custom-control custom-switch mb-2">
          <input type="checkbox" class="custom-control-input" id="trendingSwitch" name="is_trending" value="1" <?= !empty($d['is_trending'])?'checked':'' ?>>
          <label class="custom-control-label" for="trendingSwitch" style="font-size:13px;font-weight:600;">Mark as Trending 🔥</label>
        </div>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="recommendedSwitch" name="is_recommended" value="1" <?= !empty($d['is_recommended'])?'checked':'' ?>>
          <label class="custom-control-label" for="recommendedSwitch" style="font-size:13px;font-weight:600;">Mark as Recommended 👍</label>
        </div>
      </div>
    </div>

    <!-- Owner -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-user mr-2"></i>Assign to User</div>
      <div class="card-body">
        <div class="form-group mb-0">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Select User <small class="text-muted">(optional)</small></label>
          <select name="user_id" class="form-control">
            <option value="">– None –</option>
            <?php foreach($users as $u): ?>
            <option value="<?=$u['id']?>" <?= ($d['user_id']??'')==$u['id']?'selected':'' ?>>
              <?= htmlspecialchars($u['name'].' ('.$u['email'].')') ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <!-- Assign Agent -->
    <div class="card mb-4">
      <div class="card-header" style="background:#fff8e1;"><i class="fas fa-user-tie mr-2 text-warning"></i>Listing Agent / Propreneur</div>
      <div class="card-body">
        <div class="form-group mb-2">
          <label class="form-label" style="font-size:12.5px;font-weight:600;">Assign Agent <small class="text-muted">(shown on property page)</small></label>
          <select name="agent_id" class="form-control">
            <option value="">– No Agent Assigned –</option>
            <?php foreach($users as $u): ?>
            <option value="<?=$u['id']?>" <?= ($d['agent_id']??'')==$u['id']?'selected':'' ?>>
              <?= htmlspecialchars($u['name'].' ('.$u['role'].') - '.$u['email']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php if (!empty($d['agent_id'])):
            $assignedAgent = null;
            foreach($users as $u) { if($u['id']==$d['agent_id']) { $assignedAgent=$u; break; } }
            if ($assignedAgent):
        ?>
        <div style="background:#f8fafc;border-radius:8px;padding:10px;font-size:12px;margin-top:8px;">
          <strong style="color:#1a2332;"><i class="fas fa-user-check text-success mr-1"></i> Currently Assigned:</strong>
          <?= htmlspecialchars($assignedAgent['name']) ?> (<?= ucfirst($assignedAgent['role']) ?>)
        </div>
        <?php endif; endif; ?>
      </div>
    </div>

    <!-- Info -->
    <div class="card mb-4">
      <div class="card-header"><i class="fas fa-info-circle mr-2"></i>Property Info</div>
      <div class="card-body" style="font-size:13px;">
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">ID:</span><strong>#<?=$prop['id']?></strong></div>
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Slug:</span><strong><?= htmlspecialchars($prop['slug']) ?></strong></div>
        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Created:</span><strong><?= date('d M Y, h:i A', strtotime($prop['created_at'])) ?></strong></div>
        <?php if (!empty($prop['updated_at'])): ?>
        <div class="d-flex justify-content-between"><span class="text-muted">Updated:</span><strong><?= date('d M Y, h:i A', strtotime($prop['updated_at'])) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Submit -->
    <div class="card">
      <div class="card-body">
        <button type="submit" class="btn btn-success btn-block" style="height:46px;font-size:15px;font-weight:700;border-radius:10px;">
          <i class="fas fa-save mr-2"></i>Update Property
        </button>
        <a href="<?=BASE_URL?>property-view.php?id=<?=$id?>" class="btn btn-outline-secondary btn-block mt-2">Cancel</a>
      </div>
    </div>

  </div>
</div>
</form>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
  $('#featInput').on('change',function(){
    var f=this.files[0];if(!f)return;
    var r=new FileReader();r.onload=function(e){$('#featPreview').attr('src',e.target.result).show();$('#featIcon,#featText').hide();}
    r.readAsDataURL(f);
  });
  $('#galleryInput').on('change',function(){
    $('#galleryPreview').empty();
    $.each(this.files,function(i,f){var r=new FileReader();r.onload=function(e){$('#galleryPreview').append('<img src="'+e.target.result+'" style="width:66px;height:66px;object-fit:cover;border-radius:6px;margin:3px;">');}r.readAsDataURL(f);});
  });
  $('.amenity-lbl').on('click',function(){
    var cb=$(this).find('input');var chk=!cb.prop('checked');cb.prop('checked',chk);
    $(this).css({'border-color':chk?'#1e40af':'#e5e7eb','background':chk?'#eff6ff':'#fff'});
  });

  // Delete image toggle
  $('input[name="delete_images[]"]').closest('label').on('click',function(){
    var cb=$(this).find('input');
    var chk=!cb.prop('checked');
    cb.prop('checked',chk);
    $(this).closest('.position-relative').find('img').first().css('opacity',chk?'0.3':'1');
  });

  // Google Map link generator
  $('#generateMapLink').on('click', function(){
    var parts = [];
    var address = $('input[name="address"]').val().trim();
    var locText = $('select[name="location_id"] option:selected').text().trim();
    var pincode = $('input[name="pincode"]').val().trim();
    var country = $('input[name="country"]').val().trim();

    if (address) parts.push(address);
    if (locText && locText !== '– Select –') parts.push(locText);
    if (pincode) parts.push(pincode);
    if (country) parts.push(country);

    if (parts.length === 0) {
      alert('Please fill at least one address field first.');
      return;
    }
    var query = parts.join(', ');
    var url = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(query);
    $('#googleMapUrl').val(url);
    $('#testMapLink').attr('href', url);
  });

  $('#googleMapUrl').on('input', function(){
    var val = $(this).val().trim();
    $('#testMapLink').attr('href', val || '#');
  });
  $('#testMapLink').attr('href', $('#googleMapUrl').val() || '#');
});
</script>
