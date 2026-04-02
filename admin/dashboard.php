<?php require_once 'includes/header.php'; ?>

<div class="page-header">
  <h4>Dashboard</h4>
  <p>Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></p>
</div>

<?php
$total_props    = (int) $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
$pending_props  = (int) $pdo->query("SELECT COUNT(*) FROM properties WHERE status='pending'")->fetchColumn();
$approved_props = (int) $pdo->query("SELECT COUNT(*) FROM properties WHERE status='approved'")->fetchColumn();
$rejected_props = (int) $pdo->query("SELECT COUNT(*) FROM properties WHERE status='rejected'")->fetchColumn();
$total_users    = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pending_users  = (int) $pdo->query("SELECT COUNT(*) FROM users WHERE status='pending'")->fetchColumn();
$total_enq      = (int) $pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();

$recent_props = $pdo->query("
    SELECT p.id, p.title, p.property_type, p.listing_type, p.price, p.status,
           u.name AS owner_name, l.city
    FROM properties p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN locations l ON p.location_id = l.id
    ORDER BY p.created_at DESC LIMIT 8
")->fetchAll();

$recent_enq = $pdo->query("
    SELECT e.id, e.name, e.phone, e.email, e.status, e.created_at,
           p.title AS prop_title
    FROM enquiries e
    LEFT JOIN properties p ON e.property_id = p.id
    ORDER BY e.created_at DESC LIMIT 6
")->fetchAll();
?>

<!-- ── Stat Cards ── -->
<div class="row mb-4">
  <?php
  $stats = [
    ['label'=>'Total Properties', 'value'=>$total_props,    'icon'=>'fa-building',     'grad'=>'#0d6efd,#0a58ca'],
    ['label'=>'Pending Review',   'value'=>$pending_props,  'icon'=>'fa-clock',        'grad'=>'#f0a500,#d18b00'],
    ['label'=>'Approved',         'value'=>$approved_props, 'icon'=>'fa-check-circle', 'grad'=>'#198754,#146c43'],
    ['label'=>'Rejected',         'value'=>$rejected_props, 'icon'=>'fa-times-circle', 'grad'=>'#dc3545,#b02a37'],
    ['label'=>'Registered Users', 'value'=>$total_users,    'icon'=>'fa-users',        'grad'=>'#6f42c1,#59359a'],
    ['label'=>'Pending Approvals','value'=>$pending_users, 'icon'=>'fa-user-clock',   'grad'=>'#f59e0b,#d97706'],
    ['label'=>'Total Enquiries',  'value'=>$total_enq,      'icon'=>'fa-envelope',     'grad'=>'#0dcaf0,#0aa2c0'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-xl-2 col-md-4 col-6 mb-3">
    <div class="stat-card" style="background:linear-gradient(135deg,<?= $s['grad'] ?>)">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-num"><?= number_format($s['value']) ?></div>
          <div class="stat-label"><?= $s['label'] ?></div>
        </div>
        <div class="stat-icon"><i class="fas <?= $s['icon'] ?>"></i></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if ($pending_props > 0): ?>
<div class="alert alert-warning d-flex align-items-center mb-3" style="border-radius:10px;">
  <i class="fas fa-building fa-lg mr-3"></i>
  <div>
    <strong><?= $pending_props ?> propert<?= $pending_props === 1 ? 'y needs' : 'ies need' ?> approval.</strong>
    <a href="<?= BASE_URL ?>properties.php?status=pending" class="btn btn-warning btn-sm ml-3">Review Properties →</a>
  </div>
</div>
<?php endif; ?>
<?php if ($pending_users > 0): ?>
<div class="alert alert-info d-flex align-items-center mb-4" style="border-radius:10px;">
  <i class="fas fa-user-clock fa-lg mr-3"></i>
  <div>
    <strong><?= $pending_users ?> user<?= $pending_users > 1 ? 's' : '' ?> waiting for account approval.</strong>
    <a href="<?= BASE_URL ?>users.php?status=pending" class="btn btn-info btn-sm ml-3">Review Users →</a>
  </div>
</div>
<?php endif; ?>

<div class="row">

  <!-- Recent Properties -->
  <div class="col-lg-7 mb-4">
    <div class="card">
      <div class="card-header">
        <span><i class="fas fa-building mr-2 text-primary"></i>Recent Properties</span>
        <a href="<?= BASE_URL ?>properties.php" class="btn btn-outline-primary btn-sm btn-xs">View All</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Property</th>
                <th>Owner</th>
                <th>Price</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_props as $p): ?>
              <tr>
                <td>
                  <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($p['title']) ?></div>
                  <div style="font-size:11.5px;color:#6c757d;">
                    <?= ucfirst($p['property_type']) ?> &middot; <?= strtoupper($p['listing_type']) ?>
                    <?= $p['city'] ? ' &middot; ' . htmlspecialchars($p['city']) : '' ?>
                  </div>
                </td>
                <td style="font-size:13px;"><?= htmlspecialchars($p['owner_name'] ?? '–') ?></td>
                <td style="font-size:13px;"><?= $p['price'] ? format_inr((float)$p['price']) : '–' ?></td>
                <td>
                  <span class="badge badge-<?= $p['status'] ?> px-2 py-1" style="font-size:11px;">
                    <?= ucfirst($p['status']) ?>
                  </span>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>property-view.php?id=<?= $p['id'] ?>"
                     class="btn btn-outline-secondary btn-xs">View</a>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($recent_props)): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">No properties yet</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Enquiries -->
  <div class="col-lg-5 mb-4">
    <div class="card">
      <div class="card-header">
        <span><i class="fas fa-envelope mr-2 text-danger"></i>Recent Enquiries</span>
        <a href="<?= BASE_URL ?>enquiries.php" class="btn btn-outline-primary btn-sm btn-xs">View All</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr><th>Contact</th><th>Property</th><th>Status</th></tr>
            </thead>
            <tbody>
              <?php foreach ($recent_enq as $e): ?>
              <tr>
                <td>
                  <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($e['name']) ?></div>
                  <div style="font-size:11.5px;color:#6c757d;"><?= htmlspecialchars($e['phone'] ?? $e['email']) ?></div>
                </td>
                <td style="font-size:12px;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  <?= htmlspecialchars($e['prop_title'] ?? 'General Enquiry') ?>
                </td>
                <td>
                  <span class="badge badge-<?= $e['status'] ?> px-2 py-1" style="font-size:11px;">
                    <?= ucfirst($e['status']) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($recent_enq)): ?>
              <tr><td colspan="3" class="text-center text-muted py-4">No enquiries yet</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
