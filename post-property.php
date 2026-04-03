<?php
ob_start();
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=makaan_dekho;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) { die('DB Error: ' . $e->getMessage()); }

$locations = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY state,city")->fetchAll();
$success   = false;
$errors    = [];

$amenity_list = [
    'parking'      => 'Parking',
    'lift'         => 'Lift',
    'security'     => 'Security',
    'power_backup' => 'Power Backup',
    'gym'          => 'Gym',
    'swimming_pool'=> 'Swimming Pool',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic
    $role         = in_array($_POST['role']??'', ['owner','agent','builder']) ? $_POST['role'] : 'owner';
    $name         = trim($_POST['name']          ?? '');
    $email        = trim($_POST['email']         ?? '');
    $phone        = trim($_POST['phone']         ?? '');
    $title        = trim($_POST['title']         ?? '');
    $prop_type    = $_POST['property_type']      ?? 'apartment';
    $category     = trim($_POST['category']      ?? '');
    $listing_type = $_POST['listing_type']       ?? 'sale';
    $price        = is_numeric($_POST['price']??'') ? (float)$_POST['price'] : null;
    $price_type   = $_POST['price_type']         ?? 'total';

    // Location
    $country      = trim($_POST['country']       ?? 'India');
    $state        = trim($_POST['state']         ?? '');
    $city         = trim($_POST['city']          ?? '');
    $area         = trim($_POST['area']          ?? '');
    $address      = trim($_POST['address']       ?? '');
    $pincode      = trim($_POST['pincode']       ?? '');
    $google_map   = trim($_POST['google_map']    ?? '');
    $location_id  = is_numeric($_POST['location_id']??'') ? (int)$_POST['location_id'] : null;

    // Property details
    $bedrooms     = is_numeric($_POST['bedrooms']??'')     ? (int)$_POST['bedrooms']    : null;
    $bathrooms    = is_numeric($_POST['bathrooms']??'')    ? (int)$_POST['bathrooms']   : null;
    $area_sqft    = is_numeric($_POST['area_sqft']??'')    ? (float)$_POST['area_sqft'] : null;
    $floor        = is_numeric($_POST['floor']??'')        ? (int)$_POST['floor']       : null;
    $total_floors = is_numeric($_POST['total_floors']??'') ? (int)$_POST['total_floors']: null;
    $furnishing   = $_POST['furnishing']         ?? '';
    $property_age = trim($_POST['property_age']  ?? '');

    // Builder info
    $builder_name    = trim($_POST['builder_name']    ?? '');
    $contact_person  = trim($_POST['contact_person']  ?? '');
    $builder_phone   = trim($_POST['builder_phone']   ?? '');
    $builder_email   = trim($_POST['builder_email']   ?? '');

    // Media
    $video_url    = trim($_POST['video_url']     ?? '');

    // Amenities
    $amenities    = json_encode($_POST['amenities'] ?? []);

    // Description
    $short_desc   = trim($_POST['short_description'] ?? '');
    $description  = trim($_POST['description']       ?? '');

    // Validation
    if (!$name)  $errors[] = 'Your name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (!preg_match('/^[0-9]{10}$/', $phone))       $errors[] = '10-digit phone number required.';
    if (!$title) $errors[] = 'Property title is required.';
    if (!$price) $errors[] = 'Price is required.';

    if (empty($errors)) {

        // Handle featured image upload
        $featured_image = '';
        if (!empty($_FILES['featured_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $fname = 'prop_' . time() . '_feat.' . $ext;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], __DIR__ . '/uploads/properties/' . $fname)) {
                    $featured_image = $fname;
                }
            }
        }

        // Get/create user
        $chk = $pdo->prepare("SELECT id FROM users WHERE email=? AND is_deleted=0");
        $chk->execute([$email]);
        $user = $chk->fetch();
        if ($user) {
            $user_id = $user['id'];
            $pdo->prepare("UPDATE users SET role=?,phone=? WHERE id=?")->execute([$role,$phone,$user_id]);
        } else {
            $hash = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (name,email,phone,password,role,status) VALUES (?,?,?,?,?,'pending')")
                ->execute([$name,$email,$phone,$hash,$role]);
            $user_id = (int)$pdo->lastInsertId();
        }

        // Slug
        $slug_base = strtolower(preg_replace('/[^a-z0-9]+/i','-',$title));
        $slug = $slug_base; $i=1;
        while (true) {
            $s = $pdo->prepare("SELECT id FROM properties WHERE slug=?");
            $s->execute([$slug]);
            if (!$s->fetch()) break;
            $slug = $slug_base.'-'.(++$i);
        }

        // Insert property
        $pdo->prepare("
            INSERT INTO properties
              (title,slug,short_description,description,price,price_type,property_type,category,
               listing_type,bedrooms,bathrooms,area_sqft,floor,total_floors,furnishing,property_age,
               address,country,pincode,google_map,location_id,
               builder_name,contact_person,builder_phone,builder_email,
               featured_image,video_url,amenities,user_id,status,availability,publish_status)
            VALUES
              (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'pending','available','draft')
        ")->execute([
            $title,$slug,$short_desc,$description,$price,$price_type,$prop_type,$category,
            $listing_type,$bedrooms,$bathrooms,$area_sqft,$floor,$total_floors,$furnishing,$property_age,
            $address,$country,$pincode,$google_map,$location_id,
            $builder_name,$contact_person,$builder_phone,$builder_email,
            $featured_image,$video_url,$amenities,$user_id
        ]);
        $property_id = (int)$pdo->lastInsertId();

        // Gallery images
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $k => $tmp) {
                if (!$_FILES['gallery']['error'][$k] && $tmp) {
                    $ext = strtolower(pathinfo($_FILES['gallery']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                        $gname = 'prop_' . $property_id . '_' . time() . '_' . $k . '.' . $ext;
                        if (move_uploaded_file($tmp, __DIR__ . '/uploads/properties/' . $gname)) {
                            $pdo->prepare("INSERT INTO property_images (property_id,image,is_primary) VALUES (?,?,0)")
                                ->execute([$property_id, $gname]);
                        }
                    }
                }
            }
        }

        $success = true;
    }
}

// Group locations by state
$states = [];
foreach ($locations as $loc) {
    $states[$loc['state']][] = $loc;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Post Your Property – MakaanDekho</title>
<link rel="icon" type="image/png" href="/makaandekho.in/favicon.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{box-sizing:border-box}
body{background:#f0f2f5;font-family:'Segoe UI',system-ui,sans-serif;}
.hero{background:linear-gradient(135deg,#0f172a,#1e40af);padding:44px 20px 70px;text-align:center;color:#fff;}
.hero h1{font-size:30px;font-weight:800;margin-bottom:6px;}
.hero p{color:rgba(255,255,255,.7);font-size:14px;margin:0;}
.hero-logo{max-height:46px;object-fit:contain;filter:brightness(0) invert(1);margin-bottom:14px;display:block;margin-left:auto;margin-right:auto;}

.form-wrap{max-width:820px;margin:-38px auto 40px;padding:0 16px;}
.form-card{background:#fff;border-radius:14px;box-shadow:0 8px 40px rgba(0,0,0,.12);padding:36px 40px;}

/* Role tabs */
.role-tabs{display:flex;gap:10px;margin-bottom:10px;}
.role-tab{flex:1;padding:10px 6px;border:2px solid #e5e7eb;border-radius:9px;text-align:center;cursor:pointer;font-size:13px;font-weight:600;color:#6b7280;background:#f8fafc;transition:all .18s;user-select:none;}
.role-tab input{display:none;}
.role-tab i{display:block;font-size:20px;margin-bottom:3px;}
.role-tab.selected{border-color:#1e40af;background:#eff6ff;color:#1e40af;}

/* Section */
.sec-heading{font-size:13px;font-weight:700;color:#1e40af;text-transform:uppercase;letter-spacing:.8px;margin:28px 0 16px;padding-bottom:8px;border-bottom:2px solid #eff6ff;display:flex;align-items:center;gap:8px;}

/* Fields */
.form-label{font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px;}
.form-control{height:44px;font-size:14px;border-radius:8px;border:1.5px solid #e5e7eb;padding:0 13px;transition:border-color .18s;}
.form-control:focus{border-color:#1e40af;box-shadow:0 0 0 3px rgba(30,64,175,.08);}
textarea.form-control{height:auto;padding:11px 13px;}
select.form-control{background:#fff;}
.input-group-text{border-radius:8px 0 0 8px;background:#f1f5f9;border:1.5px solid #e5e7eb;border-right:none;font-weight:700;color:#374151;}
.input-group .form-control{border-radius:0 8px 8px 0;}

/* Amenities checkboxes */
.amenity-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
.amenity-item{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:all .18s;font-size:13px;font-weight:500;}
.amenity-item input{display:none;}
.amenity-item.checked{border-color:#1e40af;background:#eff6ff;color:#1e40af;}
.amenity-item i{font-size:16px;}

/* Image upload */
.upload-area{border:2px dashed #d1d5db;border-radius:10px;padding:28px;text-align:center;cursor:pointer;transition:all .18s;background:#fafafa;}
.upload-area:hover{border-color:#1e40af;background:#eff6ff;}
.upload-area i{font-size:32px;color:#9ca3af;margin-bottom:8px;}
.upload-area p{font-size:13px;color:#6b7280;margin:0;}
.upload-area input{display:none;}

/* Submit */
.btn-submit{height:50px;border-radius:10px;font-size:16px;font-weight:700;background:linear-gradient(135deg,#1e40af,#1d4ed8);border:none;width:100%;color:#fff;cursor:pointer;transition:opacity .2s;}
.btn-submit:hover{opacity:.9;}

/* Progress steps */
.steps{display:flex;justify-content:center;gap:6px;margin-bottom:28px;flex-wrap:wrap;}
.step-btn{padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;background:#f1f5f9;color:#6b7280;border:none;cursor:pointer;transition:all .18s;}
.step-btn.active{background:#1e40af;color:#fff;}

/* Success */
.success-box{text-align:center;padding:40px 20px;}
.success-box .check{width:76px;height:76px;border-radius:50%;background:#d1fae5;color:#059669;font-size:34px;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;}
</style>
</head>
<body>

<div class="hero">
  <?php if(file_exists(__DIR__.'/assets/img/logo.png')): ?>
  <img src="/makaandekho.in/assets/img/logo.png" class="hero-logo" alt="MakaanDekho">
  <?php else: ?>
  <h2 style="color:#fff;font-weight:800;margin-bottom:14px;">Makaan<span style="color:#f0a500;">Dekho</span></h2>
  <?php endif; ?>
  <h1>Post Your Property</h1>
  <p>Fill in the details below – our team will review and publish your listing</p>
</div>

<div class="form-wrap">
<div class="form-card">

<?php if ($success): ?>
<div class="success-box">
  <div class="check"><i class="fas fa-check"></i></div>
  <h4 style="font-weight:800;color:#1a2332;">Property Submitted!</h4>
  <p class="text-muted">Your property is under review. We'll publish it after verification.</p>
  <div class="alert alert-info mt-3" style="border-radius:10px;font-size:13.5px;">
    <i class="fas fa-clock mr-2"></i>Approval usually takes <strong>24–48 hours</strong>.
  </div>
  <a href="post-property.php" class="btn btn-primary mt-3" style="border-radius:8px;">Post Another Property</a>
</div>

<?php else: ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger" style="border-radius:10px;font-size:13px;">
  <strong><i class="fas fa-exclamation-circle mr-1"></i>Please fix:</strong>
  <ul class="mb-0 mt-1 pl-3">
    <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

  <!-- ── I am ── -->
  <div class="sec-heading"><i class="fas fa-user"></i> I am a</div>
  <div class="role-tabs">
    <?php foreach(['owner'=>['fa-user','Owner'],'agent'=>['fa-id-badge','Agent'],'builder'=>['fa-hard-hat','Builder']] as $v=>[$ic,$lb]): ?>
    <label class="role-tab <?= ($_POST['role']??'owner')===$v?'selected':'' ?>">
      <input type="radio" name="role" value="<?=$v?>" <?= ($_POST['role']??'owner')===$v?'checked':'' ?>>
      <i class="fas <?=$ic?>"></i><?=$lb?>
    </label>
    <?php endforeach; ?>
  </div>

  <!-- ── Contact ── -->
  <div class="sec-heading"><i class="fas fa-address-card"></i> Your Contact Details</div>
  <div class="row">
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Full Name *</label>
      <input type="text" name="name" class="form-control" placeholder="Your full name" value="<?= htmlspecialchars($_POST['name']??'') ?>" required>
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Phone Number *</label>
      <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile" value="<?= htmlspecialchars($_POST['phone']??'') ?>" maxlength="10" required>
    </div></div>
    <div class="col-12"><div class="form-group">
      <label class="form-label">Email Address *</label>
      <input type="email" name="email" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
    </div></div>
  </div>

  <!-- ── Basic Details ── -->
  <div class="sec-heading"><i class="fas fa-home"></i> Basic Details</div>
  <div class="row">
    <div class="col-12"><div class="form-group">
      <label class="form-label">Property Title *</label>
      <input type="text" name="title" class="form-control" placeholder="e.g. 3 BHK Apartment in Gurgaon Sector 56" value="<?= htmlspecialchars($_POST['title']??'') ?>" required>
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Property Type</label>
      <select name="property_type" class="form-control">
        <?php foreach(['apartment'=>'Apartment','villa'=>'Villa','plot'=>'Plot/Land','commercial'=>'Commercial','office'=>'Office'] as $v=>$l): ?>
        <option value="<?=$v?>" <?= ($_POST['property_type']??'apartment')===$v?'selected':'' ?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Category</label>
      <select name="category" class="form-control">
        <option value="">– Select –</option>
        <?php foreach(['Residential','Commercial','Industrial','Agricultural','Other'] as $c): ?>
        <option value="<?=$c?>" <?= ($_POST['category']??'')===$c?'selected':'' ?>><?=$c?></option>
        <?php endforeach; ?>
      </select>
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Listing Type</label>
      <select name="listing_type" class="form-control">
        <option value="sale" <?= ($_POST['listing_type']??'sale')==='sale'?'selected':'' ?>>For Sale</option>
        <option value="rent" <?= ($_POST['listing_type']??'')==='rent'?'selected':'' ?>>For Rent</option>
      </select>
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Price (₹) *</label>
      <div class="input-group">
        <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
        <input type="number" name="price" class="form-control" placeholder="e.g. 5000000" value="<?= htmlspecialchars($_POST['price']??'') ?>" min="0" required>
      </div>
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Price Type</label>
      <select name="price_type" class="form-control">
        <option value="total"     <?= ($_POST['price_type']??'total')==='total'?'selected':'' ?>>Total Price</option>
        <option value="per_sqft"  <?= ($_POST['price_type']??'')==='per_sqft'?'selected':'' ?>>Per Sq.ft</option>
        <option value="per_month" <?= ($_POST['price_type']??'')==='per_month'?'selected':'' ?>>Per Month</option>
      </select>
    </div></div>
  </div>

  <!-- ── Location ── -->
  <div class="sec-heading"><i class="fas fa-map-marker-alt"></i> Location Details</div>
  <div class="row">
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Country</label>
      <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($_POST['country']??'India') ?>">
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">State</label>
      <input type="text" name="state" class="form-control" placeholder="e.g. Delhi" value="<?= htmlspecialchars($_POST['state']??'') ?>">
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">City</label>
      <input type="text" name="city" class="form-control" placeholder="e.g. New Delhi" value="<?= htmlspecialchars($_POST['city']??'') ?>">
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Area / Locality</label>
      <input type="text" name="area" class="form-control" placeholder="e.g. Sector 56" value="<?= htmlspecialchars($_POST['area']??'') ?>">
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Pincode</label>
      <input type="text" name="pincode" class="form-control" placeholder="e.g. 110001" maxlength="10" value="<?= htmlspecialchars($_POST['pincode']??'') ?>">
    </div></div>
    <div class="col-md-4"><div class="form-group">
      <label class="form-label">Location (from list)</label>
      <select name="location_id" class="form-control">
        <option value="">– Select –</option>
        <?php foreach ($locations as $loc): ?>
        <option value="<?=$loc['id']?>" <?= ($_POST['location_id']??'')==$loc['id']?'selected':'' ?>>
          <?= htmlspecialchars($loc['city'].($loc['area']?' – '.$loc['area']:'').', '.$loc['state']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div></div>
    <div class="col-12"><div class="form-group">
      <label class="form-label">Full Address</label>
      <input type="text" name="address" class="form-control" placeholder="Street, Landmark, Area, City" value="<?= htmlspecialchars($_POST['address']??'') ?>">
    </div></div>
    <div class="col-12"><div class="form-group">
      <label class="form-label"><i class="fas fa-map mr-1 text-danger"></i> Google Map Embed URL <small class="text-muted">(optional)</small></label>
      <input type="url" name="google_map" class="form-control" placeholder="https://www.google.com/maps/embed?..." value="<?= htmlspecialchars($_POST['google_map']??'') ?>">
    </div></div>
  </div>

  <!-- ── Property Details ── -->
  <div class="sec-heading"><i class="fas fa-building"></i> Property Details</div>
  <div class="row">
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">BHK / Bedrooms</label>
      <select name="bedrooms" class="form-control">
        <option value="">– Select –</option>
        <?php for($b=1;$b<=7;$b++): ?>
        <option value="<?=$b?>" <?= ($_POST['bedrooms']??'')==$b?'selected':'' ?>><?=$b?> BHK</option>
        <?php endfor; ?>
      </select>
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Bathrooms</label>
      <select name="bathrooms" class="form-control">
        <option value="">– Select –</option>
        <?php for($b=1;$b<=6;$b++): ?>
        <option value="<?=$b?>" <?= ($_POST['bathrooms']??'')==$b?'selected':'' ?>><?=$b?></option>
        <?php endfor; ?>
      </select>
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Area Size (sq.ft)</label>
      <input type="number" name="area_sqft" class="form-control" placeholder="e.g. 1200" value="<?= htmlspecialchars($_POST['area_sqft']??'') ?>">
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Floor No.</label>
      <input type="number" name="floor" class="form-control" placeholder="e.g. 3" value="<?= htmlspecialchars($_POST['floor']??'') ?>">
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Total Floors</label>
      <input type="number" name="total_floors" class="form-control" placeholder="e.g. 10" value="<?= htmlspecialchars($_POST['total_floors']??'') ?>">
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Furnishing</label>
      <select name="furnishing" class="form-control">
        <option value="">– Select –</option>
        <?php foreach(['unfurnished'=>'Unfurnished','semi-furnished'=>'Semi Furnished','furnished'=>'Fully Furnished'] as $v=>$l): ?>
        <option value="<?=$v?>" <?= ($_POST['furnishing']??'')===$v?'selected':'' ?>><?=$l?></option>
        <?php endforeach; ?>
      </select>
    </div></div>
    <div class="col-md-3"><div class="form-group">
      <label class="form-label">Property Age</label>
      <select name="property_age" class="form-control">
        <option value="">– Select –</option>
        <?php foreach(['New Construction','Less than 1 year','1-3 years','3-5 years','5-10 years','10+ years'] as $a): ?>
        <option value="<?=$a?>" <?= ($_POST['property_age']??'')===$a?'selected':'' ?>><?=$a?></option>
        <?php endforeach; ?>
      </select>
    </div></div>
  </div>

  <!-- ── Builder / Agent Info ── -->
  <div class="sec-heading"><i class="fas fa-hard-hat"></i> Builder / Agent Info <small class="text-muted font-weight-normal text-lowercase" style="letter-spacing:0;">(optional)</small></div>
  <div class="row">
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Builder / Company Name</label>
      <input type="text" name="builder_name" class="form-control" placeholder="Company name" value="<?= htmlspecialchars($_POST['builder_name']??'') ?>">
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Contact Person</label>
      <input type="text" name="contact_person" class="form-control" placeholder="Contact name" value="<?= htmlspecialchars($_POST['contact_person']??'') ?>">
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Contact Phone</label>
      <input type="tel" name="builder_phone" class="form-control" placeholder="10-digit number" value="<?= htmlspecialchars($_POST['builder_phone']??'') ?>">
    </div></div>
    <div class="col-md-6"><div class="form-group">
      <label class="form-label">Contact Email</label>
      <input type="email" name="builder_email" class="form-control" placeholder="contact@company.com" value="<?= htmlspecialchars($_POST['builder_email']??'') ?>">
    </div></div>
  </div>

  <!-- ── Media ── -->
  <div class="sec-heading"><i class="fas fa-images"></i> Media Upload</div>
  <div class="row">
    <div class="col-md-5">
      <div class="form-group">
        <label class="form-label">Featured Image</label>
        <label class="upload-area" id="featLabel">
          <input type="file" name="featured_image" accept="image/*" id="featInput">
          <i class="fas fa-camera" id="featIcon"></i>
          <p id="featText">Click to upload main photo<br><small>JPG, PNG · Max 5MB</small></p>
        </label>
        <img id="featPreview" src="" style="display:none;width:100%;height:140px;object-fit:cover;border-radius:8px;margin-top:8px;">
      </div>
    </div>
    <div class="col-md-7">
      <div class="form-group">
        <label class="form-label">Gallery Images <small class="text-muted">(select multiple)</small></label>
        <label class="upload-area" style="height:100%">
          <input type="file" name="gallery[]" accept="image/*" multiple id="galleryInput">
          <i class="fas fa-images"></i>
          <p>Click to upload gallery photos<br><small>Select multiple files · JPG, PNG · Max 5MB each</small></p>
        </label>
        <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
      </div>
    </div>
    <div class="col-12"><div class="form-group">
      <label class="form-label"><i class="fab fa-youtube mr-1 text-danger"></i> Video URL <small class="text-muted">(YouTube/Vimeo)</small></label>
      <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..." value="<?= htmlspecialchars($_POST['video_url']??'') ?>">
    </div></div>
  </div>

  <!-- ── Amenities ── -->
  <div class="sec-heading"><i class="fas fa-star"></i> Amenities</div>
  <?php $sel_amenities = $_POST['amenities'] ?? []; ?>
  <div class="amenity-grid mb-3">
    <?php
    $amenity_icons = [
      'parking'=>'fa-car','lift'=>'fa-elevator','security'=>'fa-shield-alt',
      'power_backup'=>'fa-bolt','gym'=>'fa-dumbbell','swimming_pool'=>'fa-swimming-pool'
    ];
    foreach ($amenity_list as $key => $label):
      $checked = in_array($key, $sel_amenities) ? 'checked' : '';
    ?>
    <label class="amenity-item <?= $checked?'checked':'' ?>" onclick="this.classList.toggle('checked')">
      <input type="checkbox" name="amenities[]" value="<?=$key?>" <?=$checked?>>
      <i class="fas <?= $amenity_icons[$key] ?>"></i> <?=$label?>
    </label>
    <?php endforeach; ?>
  </div>

  <!-- ── Description ── -->
  <div class="sec-heading"><i class="fas fa-align-left"></i> Description</div>
  <div class="form-group">
    <label class="form-label">Short Description</label>
    <textarea name="short_description" class="form-control" rows="2" placeholder="Brief 1-2 line summary of the property"><?= htmlspecialchars($_POST['short_description']??'') ?></textarea>
  </div>
  <div class="form-group">
    <label class="form-label">Full Description</label>
    <textarea name="description" class="form-control" rows="5" placeholder="Detailed property description – amenities, nearby places, highlights…"><?= htmlspecialchars($_POST['description']??'') ?></textarea>
  </div>

  <div class="alert alert-light border mt-3 mb-4" style="border-radius:10px;font-size:13px;">
    <i class="fas fa-shield-alt mr-2 text-primary"></i>
    By posting, you agree to our Terms. Your listing goes live after admin review.
  </div>

  <button type="submit" class="btn-submit">
    <i class="fas fa-paper-plane mr-2"></i>Submit Property for Review
  </button>

</form>
<?php endif; ?>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Role tab
$('.role-tab').on('click',function(){$('.role-tab').removeClass('selected');$(this).addClass('selected');$(this).find('input').prop('checked',true);});

// Featured image preview
$('#featInput').on('change',function(){
  var f=this.files[0];
  if(f){
    var r=new FileReader();
    r.onload=function(e){$('#featPreview').attr('src',e.target.result).show();$('#featIcon,#featText').hide();}
    r.readAsDataURL(f);
  }
});

// Gallery preview
$('#galleryInput').on('change',function(){
  $('#galleryPreview').empty();
  $.each(this.files,function(i,f){
    var r=new FileReader();
    r.onload=function(e){$('#galleryPreview').append('<img src="'+e.target.result+'" style="width:70px;height:70px;object-fit:cover;border-radius:6px;margin:3px;">');}
    r.readAsDataURL(f);
  });
});
</script>
</body>
</html>
