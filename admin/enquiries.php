<?php require_once 'includes/header.php'; ?>

<?php
// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $rows = $pdo->query("
        SELECT e.id, e.name, e.email, e.phone, e.message, e.status, e.created_at,
               p.title AS property_title
        FROM enquiries e
        LEFT JOIN properties p ON e.property_id = p.id
        ORDER BY e.created_at DESC
    ")->fetchAll();

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="enquiries_' . date('Ymd_His') . '.csv"');
    $f = fopen('php://output', 'w');
    fputcsv($f, ['ID','Name','Email','Phone','Property','Message','Status','Date']);
    foreach ($rows as $r) {
        fputcsv($f, [
            $r['id'], $r['name'], $r['email'], $r['phone'],
            $r['property_title'] ?? 'General', $r['message'],
            $r['status'], $r['created_at'],
        ]);
    }
    fclose($f);
    exit;
}

// Mark as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $stmt = $pdo->prepare("UPDATE enquiries SET status='read' WHERE id=?");
    $stmt->execute([(int)$_GET['mark_read']]);
    flash('success', 'Enquiry marked as read.');
    header('Location: ' . BASE_URL . 'enquiries.php');
    exit;
}

$enquiries = $pdo->query("
    SELECT e.*, p.title AS property_title
    FROM enquiries e
    LEFT JOIN properties p ON e.property_id = p.id
    ORDER BY e.created_at DESC
")->fetchAll();

$counts = $pdo->query("SELECT status, COUNT(*) FROM enquiries GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Enquiries</h4>
    <p>View and manage all property enquiries / leads</p>
  </div>
  <a href="<?= BASE_URL ?>enquiries.php?export=csv" class="btn btn-success btn-sm">
    <i class="fas fa-download mr-1"></i>Export CSV
  </a>
</div>

<!-- Summary badges -->
<div class="row mb-4">
  <?php
  $eq = [
    ['label'=>'New',     'key'=>'new',     'color'=>'#cfe2ff','text'=>'#084298'],
    ['label'=>'Read',    'key'=>'read',    'color'=>'#e2e3e5','text'=>'#41464b'],
    ['label'=>'Replied', 'key'=>'replied', 'color'=>'#d1e7dd','text'=>'#0a3622'],
    ['label'=>'Total',   'key'=>'__total', 'color'=>'#f0f2f5','text'=>'#1a2332'],
  ];
  foreach ($eq as $e):
    $val = $e['key'] === '__total' ? array_sum($counts) : ($counts[$e['key']] ?? 0);
  ?>
  <div class="col-md-3 col-6 mb-3">
    <div class="card text-center py-3" style="background:<?= $e['color'] ?>;border-radius:10px;">
      <div style="font-size:28px;font-weight:700;color:<?= $e['text'] ?>"><?= $val ?></div>
      <div style="font-size:12px;color:<?= $e['text'] ?>;opacity:.8;"><?= $e['label'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="card-header">
    <span><i class="fas fa-envelope mr-2 text-danger"></i>All Enquiries (<?= count($enquiries) ?>)</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover datatable mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Contact</th>
            <th>Phone</th>
            <th>Property</th>
            <th>Message</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($enquiries as $e): ?>
          <tr <?= $e['status']==='new' ? 'style="background:#fffbf0;"' : '' ?>>
            <td><?= $e['id'] ?></td>
            <td>
              <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($e['name']) ?></div>
              <div style="font-size:11.5px;color:#6c757d;"><?= htmlspecialchars($e['email']) ?></div>
            </td>
            <td style="font-size:13px;"><?= htmlspecialchars($e['phone'] ?? '–') ?></td>
            <td style="font-size:13px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              <?= htmlspecialchars($e['property_title'] ?? 'General Enquiry') ?>
            </td>
            <td style="max-width:200px;">
              <div style="font-size:12.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($e['message'] ?? '') ?>">
                <?= htmlspecialchars(mb_substr($e['message'] ?? '', 0, 60)) ?><?= mb_strlen($e['message'] ?? '') > 60 ? '…' : '' ?>
              </div>
            </td>
            <td>
              <span class="badge badge-<?= $e['status'] ?> px-2 py-1" style="font-size:11px;">
                <?= ucfirst($e['status']) ?>
              </span>
            </td>
            <td style="font-size:12px;white-space:nowrap;"><?= date('d M Y, g:i a', strtotime($e['created_at'])) ?></td>
            <td style="white-space:nowrap;">
              <button class="btn btn-outline-primary btn-xs view-enq"
                      data-toggle="modal" data-target="#enqModal"
                      data-name="<?= htmlspecialchars($e['name'], ENT_QUOTES) ?>"
                      data-email="<?= htmlspecialchars($e['email'], ENT_QUOTES) ?>"
                      data-phone="<?= htmlspecialchars($e['phone'] ?? '', ENT_QUOTES) ?>"
                      data-property="<?= htmlspecialchars($e['property_title'] ?? 'General', ENT_QUOTES) ?>"
                      data-message="<?= htmlspecialchars($e['message'] ?? '', ENT_QUOTES) ?>"
                      data-date="<?= date('d M Y, g:i a', strtotime($e['created_at'])) ?>">
                <i class="fas fa-eye"></i>
              </button>
              <?php if ($e['status'] === 'new'): ?>
              <a href="<?= BASE_URL ?>enquiries.php?mark_read=<?= $e['id'] ?>"
                 class="btn btn-outline-secondary btn-xs" title="Mark as read">
                <i class="fas fa-check"></i>
              </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Enquiry Modal -->
<div class="modal fade" id="enqModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;border:none;">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold">Enquiry Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted" style="width:100px;">Name</td><td id="m-name" style="font-weight:600;"></td></tr>
          <tr><td class="text-muted">Email</td><td id="m-email"></td></tr>
          <tr><td class="text-muted">Phone</td><td id="m-phone"></td></tr>
          <tr><td class="text-muted">Property</td><td id="m-property"></td></tr>
          <tr><td class="text-muted">Date</td><td id="m-date"></td></tr>
        </table>
        <hr>
        <div class="text-muted mb-1" style="font-size:12px;font-weight:600;text-transform:uppercase;">Message</div>
        <p id="m-message" style="font-size:13.5px;line-height:1.7;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$('#enqModal').on('show.bs.modal', function (e) {
  var b = $(e.relatedTarget);
  $('#m-name').text(b.data('name'));
  $('#m-email').text(b.data('email'));
  $('#m-phone').text(b.data('phone') || '–');
  $('#m-property').text(b.data('property'));
  $('#m-date').text(b.data('date'));
  $('#m-message').text(b.data('message') || '(no message)');
});
</script>

<?php require_once 'includes/footer.php'; ?>
