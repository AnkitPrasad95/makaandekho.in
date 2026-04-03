<?php require_once 'includes/header.php'; ?>

<?php
$status_filter = $_GET['status'] ?? '';
$allowed = ['', 'pending', 'approved', 'rejected'];
if (!in_array($status_filter, $allowed)) $status_filter = '';

$where = $status_filter ? "WHERE p.status = " . $pdo->quote($status_filter) . " AND p.is_deleted=0" : 'WHERE p.is_deleted=0';

$properties = $pdo->query("
    SELECT p.id, p.title, p.property_type, p.listing_type, p.price,
           p.bedrooms, p.status, p.featured, p.created_at,
           u.name AS owner_name, u.phone AS owner_phone,
           l.city, l.state
    FROM properties p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN locations l ON p.location_id = l.id
    $where
    ORDER BY p.created_at DESC
")->fetchAll();

$counts = $pdo->query("
    SELECT status, COUNT(*) AS cnt FROM properties WHERE is_deleted=0 GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Properties</h4>
    <p>Manage all property listings</p>
  </div>
</div>

<!-- Filter tabs -->
<div class="mb-3">
  <?php
  $tabs = [
    '' => ['label' => 'All', 'count' => array_sum($counts)],
    'pending'  => ['label' => 'Pending',  'count' => $counts['pending']  ?? 0, 'class' => 'warning'],
    'approved' => ['label' => 'Approved', 'count' => $counts['approved'] ?? 0, 'class' => 'success'],
    'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected'] ?? 0, 'class' => 'danger'],
  ];
  foreach ($tabs as $key => $tab):
    $active = ($status_filter === $key) ? 'active' : '';
    $url = BASE_URL . 'properties.php' . ($key ? '?status=' . $key : '');
  ?>
  <a href="<?= $url ?>" class="btn btn-sm btn-outline-secondary mr-1 <?= $active ?>" style="<?= $active ? 'background:#1a2332;color:#fff;border-color:#1a2332;' : '' ?>">
    <?= $tab['label'] ?>
    <span class="badge badge-<?= $tab['class'] ?? 'secondary' ?> ml-1"><?= $tab['count'] ?></span>
  </a>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="card-header">
    <span><i class="fas fa-building mr-2 text-primary"></i>
      <?= $status_filter ? ucfirst($status_filter) . ' Properties' : 'All Properties' ?>
      (<?= count($properties) ?>)
    </span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover datatable mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Property</th>
            <th>Type</th>
            <th>Price</th>
            <th>Owner</th>
            <th>Location</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($properties as $i => $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td>
              <div style="font-weight:600;max-width:200px;">
                <?= htmlspecialchars($p['title']) ?>
                <?php if ($p['featured']): ?>
                  <span class="badge badge-warning ml-1" style="font-size:9px;">★ Featured</span>
                <?php endif; ?>
              </div>
              <small class="text-muted"><?= $p['bedrooms'] ? $p['bedrooms'] . ' BHK' : '' ?> &middot; <?= strtoupper($p['listing_type']) ?></small>
            </td>
            <td><?= ucfirst($p['property_type']) ?></td>
            <td><?= $p['price'] ? format_inr((float)$p['price']) : '–' ?></td>
            <td>
              <div style="font-size:13px;"><?= htmlspecialchars($p['owner_name'] ?? '–') ?></div>
              <small class="text-muted"><?= htmlspecialchars($p['owner_phone'] ?? '') ?></small>
            </td>
            <td style="font-size:13px;"><?= htmlspecialchars(($p['city'] ?? '') . ($p['state'] ? ', ' . $p['state'] : '')) ?></td>
            <td>
              <span class="badge badge-<?= $p['status'] ?> px-2 py-1" style="font-size:11.5px;">
                <?= ucfirst($p['status']) ?>
              </span>
            </td>
            <td style="font-size:12px;white-space:nowrap;"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
            <td style="white-space:nowrap;">
              <a href="<?= BASE_URL ?>property-view.php?id=<?= $p['id'] ?>"
                 class="btn btn-outline-secondary btn-xs" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="<?= BASE_URL ?>property-edit.php?id=<?= $p['id'] ?>"
                 class="btn btn-outline-primary btn-xs" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <?php if ($p['status'] !== 'approved'): ?>
              <form method="POST" action="<?= BASE_URL ?>property-action.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-success btn-xs" title="Approve"
                        onclick="return confirm('Approve this property?')">
                  <i class="fas fa-check"></i>
                </button>
              </form>
              <?php endif; ?>
              <?php if ($p['status'] !== 'rejected'): ?>
              <button class="btn btn-danger btn-xs" title="Reject"
                      data-toggle="modal" data-target="#rejectModal"
                      data-id="<?= $p['id'] ?>" data-title="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>">
                <i class="fas fa-times"></i>
              </button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;border:none;">
      <form method="POST" action="<?= BASE_URL ?>property-action.php">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="action" value="reject">
        <input type="hidden" name="id" id="rejectId">
        <div class="modal-header" style="border-bottom:1px solid #eee;">
          <h5 class="modal-title font-weight-bold">Reject Property</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">You are rejecting: <strong id="rejectTitle"></strong></p>
          <div class="form-group mb-0">
            <label class="font-weight-600" style="font-size:13px;">Rejection Reason <span class="text-danger">*</span></label>
            <textarea name="rejection_reason" class="form-control" rows="4"
                      placeholder="Explain why this property is being rejected…" required></textarea>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid #eee;">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">Reject Property</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$('#rejectModal').on('show.bs.modal', function (e) {
  var btn = $(e.relatedTarget);
  $('#rejectId').val(btn.data('id'));
  $('#rejectTitle').text(btn.data('title'));
});
</script>

<?php require_once 'includes/footer.php'; ?>
