<?php require_once 'includes/header.php'; ?>

<?php
$id = (int) ($_GET['id'] ?? 0);
if (!$id) { flash('error', 'Invalid property ID.'); header('Location: ' . BASE_URL . 'properties.php'); exit; }

$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name, u.email AS owner_email, u.phone AS owner_phone, u.role AS owner_role,
           l.city, l.state, l.area
    FROM properties p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.id = ? AND p.is_deleted=0
");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { flash('error', 'Property not found.'); header('Location: ' . BASE_URL . 'properties.php'); exit; }

$images = $pdo->prepare("SELECT * FROM property_images WHERE property_id = ? AND is_deleted=0 ORDER BY is_primary DESC");
$images->execute([$id]);
$images = $images->fetchAll();
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4><?= htmlspecialchars($p['title']) ?></h4>
    <p>
      <a href="<?= BASE_URL ?>properties.php" class="text-muted">Properties</a>
      <i class="fas fa-angle-right mx-1 text-muted" style="font-size:11px;"></i>
      Property #<?= $p['id'] ?>
    </p>
  </div>
  <div>
    <a href="<?= BASE_URL ?>properties.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i>Back
    </a>
    <a href="<?= BASE_URL ?>property-edit.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">
      <i class="fas fa-edit mr-1"></i>Edit
    </a>
    <?php if ($p['status'] !== 'approved'): ?>
    <form method="POST" action="<?= BASE_URL ?>property-action.php" class="d-inline">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="id"     value="<?= $p['id'] ?>">
      <input type="hidden" name="action" value="approve">
      <input type="hidden" name="redirect" value="view">
      <button type="submit" class="btn btn-success btn-sm"
              onclick="return confirm('Approve this property?')">
        <i class="fas fa-check mr-1"></i>Approve
      </button>
    </form>
    <?php endif; ?>
    <?php if ($p['status'] !== 'rejected'): ?>
    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal">
      <i class="fas fa-times mr-1"></i>Reject
    </button>
    <?php endif; ?>
  </div>
</div>

<div class="row">

  <!-- Left: Details -->
  <div class="col-lg-8 mb-4">

    <!-- Images -->
    <?php if ($images): ?>
    <div class="card mb-4">
      <div class="card-header">Property Images</div>
      <div class="card-body">
        <div class="row">
          <?php foreach ($images as $img): ?>
          <div class="col-md-4 col-6 mb-3">
            <img src="<?= UPLOAD_URL ?>properties/<?= htmlspecialchars($img['image']) ?>"
                 class="img-fluid rounded" style="height:160px;width:100%;object-fit:cover;"
                 alt="Property image"
                 onerror="this.src='https://via.placeholder.com/320x160?text=No+Image'">
            <?php if ($img['is_primary']): ?>
            <span class="badge badge-primary mt-1" style="font-size:10px;">Primary</span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Description -->
    <div class="card mb-4">
      <div class="card-header">Property Details</div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Type</div>
            <div style="font-weight:600;"><?= ucfirst($p['property_type']) ?></div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Listing</div>
            <div style="font-weight:600;"><?= ucfirst($p['listing_type']) ?></div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Price</div>
            <div style="font-weight:600;color:#0d6efd;"><?= $p['price'] ? format_inr((float)$p['price']) : '–' ?></div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Area</div>
            <div style="font-weight:600;"><?= $p['area_sqft'] ? number_format($p['area_sqft']) . ' sq.ft' : '–' ?></div>
          </div>
          <?php if ($p['bedrooms']): ?>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Bedrooms</div>
            <div style="font-weight:600;"><?= $p['bedrooms'] ?></div>
          </div>
          <?php endif; ?>
          <?php if ($p['bathrooms']): ?>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Bathrooms</div>
            <div style="font-weight:600;"><?= $p['bathrooms'] ?></div>
          </div>
          <?php endif; ?>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Featured</div>
            <div style="font-weight:600;"><?= $p['featured'] ? '✅ Yes' : 'No' ?></div>
          </div>
          <div class="col-md-3 col-6 mb-3">
            <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">Posted On</div>
            <div style="font-weight:600;"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
          </div>
        </div>

        <?php if ($p['address']): ?>
        <div class="mb-3">
          <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Address</div>
          <div><?= htmlspecialchars($p['address']) ?></div>
        </div>
        <?php endif; ?>

        <?php if ($p['description']): ?>
        <div>
          <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Description</div>
          <p style="line-height:1.7;color:#495057;"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($p['status'] === 'rejected' && $p['rejection_reason']): ?>
    <div class="alert alert-danger" style="border-radius:10px;">
      <strong><i class="fas fa-times-circle mr-2"></i>Rejection Reason:</strong>
      <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($p['rejection_reason'])) ?></p>
    </div>
    <?php endif; ?>

  </div>

  <!-- Right: Meta -->
  <div class="col-lg-4">

    <!-- Status card -->
    <div class="card mb-4">
      <div class="card-header">Status</div>
      <div class="card-body text-center py-4">
        <?php
        $sc = ['pending'=>'warning','approved'=>'success','rejected'=>'danger'];
        $si = ['pending'=>'fa-clock','approved'=>'fa-check-circle','rejected'=>'fa-times-circle'];
        ?>
        <div style="font-size:48px;color:<?= $p['status']==='approved'?'#198754':($p['status']==='rejected'?'#dc3545':'#f0a500') ?>;">
          <i class="fas <?= $si[$p['status']] ?>"></i>
        </div>
        <h5 class="mt-2 font-weight-700"><?= ucfirst($p['status']) ?></h5>
      </div>
    </div>

    <!-- Owner info -->
    <?php if ($p['owner_name']): ?>
    <div class="card mb-4">
      <div class="card-header">Owner Details</div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div style="width:42px;height:42px;border-radius:50%;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;flex-shrink:0;">
            <?= strtoupper(substr($p['owner_name'], 0, 1)) ?>
          </div>
          <div class="ml-3">
            <div style="font-weight:600;"><?= htmlspecialchars($p['owner_name']) ?></div>
            <span class="badge badge-<?= $p['owner_role'] ?>" style="font-size:10px;"><?= ucfirst($p['owner_role']) ?></span>
          </div>
        </div>
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted" style="font-size:12px;">Email</td><td style="font-size:13px;"><?= htmlspecialchars($p['owner_email']) ?></td></tr>
          <tr><td class="text-muted" style="font-size:12px;">Phone</td><td style="font-size:13px;"><?= htmlspecialchars($p['owner_phone'] ?? '–') ?></td></tr>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Location -->
    <?php if ($p['city']): ?>
    <div class="card mb-4">
      <div class="card-header">Location</div>
      <div class="card-body">
        <p class="mb-1"><i class="fas fa-city mr-2 text-primary"></i><?= htmlspecialchars($p['city']) ?></p>
        <p class="mb-1"><i class="fas fa-map mr-2 text-primary"></i><?= htmlspecialchars($p['state']) ?></p>
        <?php if ($p['area']): ?>
        <p class="mb-0"><i class="fas fa-map-pin mr-2 text-primary"></i><?= htmlspecialchars($p['area']) ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;border:none;">
      <form method="POST" action="<?= BASE_URL ?>property-action.php">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="id"       value="<?= $p['id'] ?>">
        <input type="hidden" name="action"   value="reject">
        <input type="hidden" name="redirect" value="view">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold">Reject Property</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-0">
            <label style="font-size:13px;font-weight:600;">Rejection Reason <span class="text-danger">*</span></label>
            <textarea name="rejection_reason" class="form-control" rows="4"
                      placeholder="Explain the reason for rejection…" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Reject Property</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
