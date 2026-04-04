<?php require_once 'includes/header.php'; ?>

<?php
// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $rows = $pdo->query("SELECT id, email, subscribed_at, is_active FROM newsletter_subscribers ORDER BY subscribed_at DESC")->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Ymd_His') . '.csv"');
    header('Pragma: no-cache');
    $f = fopen('php://output', 'w');
    fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($f, ['ID','Email','Subscribed Date','Status']);
    foreach ($rows as $r) {
        fputcsv($f, [$r['id'], $r['email'], $r['subscribed_at'], $r['is_active'] ? 'Active' : 'Unsubscribed']);
    }
    fclose($f);
    exit;
}

// Toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    verify_csrf();
    $sub = $pdo->prepare("SELECT id, is_active FROM newsletter_subscribers WHERE id=?");
    $sub->execute([(int)$_GET['toggle']]);
    $s = $sub->fetch();
    if ($s) {
        $new = $s['is_active'] ? 0 : 1;
        $pdo->prepare("UPDATE newsletter_subscribers SET is_active=? WHERE id=?")->execute([$new, $s['id']]);
        flash('success', $new ? 'Subscriber reactivated.' : 'Subscriber unsubscribed.');
    }
    header('Location: ' . BASE_URL . 'newsletter.php');
    exit;
}

// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    verify_csrf();
    $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id=?")->execute([(int)$_GET['delete']]);
    flash('success', 'Subscriber removed.');
    header('Location: ' . BASE_URL . 'newsletter.php');
    exit;
}

// Fetch all subscribers
$subscribers = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC")->fetchAll();
$totalActive = 0;
$totalInactive = 0;
foreach ($subscribers as $s) {
    if ($s['is_active']) $totalActive++; else $totalInactive++;
}
?>

<div class="page-header d-flex justify-content-between align-items-start flex-wrap">
    <div>
        <h4><i class="fas fa-newspaper mr-2"></i>Newsletter Subscribers</h4>
        <p>Manage email newsletter subscriptions</p>
    </div>
    <div>
        <a href="<?= BASE_URL ?>newsletter.php?export=csv" class="btn btn-success btn-sm">
            <i class="fas fa-download mr-1"></i>Export CSV
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center" style="border-left:4px solid #2196f3;">
            <div class="card-body py-3">
                <h3 style="font-weight:800;color:#2196f3;margin:0;"><?= count($subscribers) ?></h3>
                <small class="text-muted font-weight-600">Total Subscribers</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center" style="border-left:4px solid #4caf50;">
            <div class="card-body py-3">
                <h3 style="font-weight:800;color:#4caf50;margin:0;"><?= $totalActive ?></h3>
                <small class="text-muted font-weight-600">Active</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center" style="border-left:4px solid #ff9800;">
            <div class="card-body py-3">
                <h3 style="font-weight:800;color:#ff9800;margin:0;"><?= $totalInactive ?></h3>
                <small class="text-muted font-weight-600">Unsubscribed</small>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($subscribers)): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox text-muted" style="font-size:48px;"></i>
            <h5 class="mt-3 text-muted">No Subscribers Yet</h5>
            <p class="text-muted">Subscribers will appear here when users sign up via the newsletter form.</p>
        </div>
        <?php else: ?>
        <table class="table table-hover mb-0 datatable">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Email</th>
                    <th>Subscribed Date</th>
                    <th width="100">Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $i => $sub): ?>
                <tr>
                    <td><?= $sub['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($sub['email']) ?></strong>
                    </td>
                    <td><?= date('d M Y, h:i A', strtotime($sub['subscribed_at'])) ?></td>
                    <td>
                        <?php if ($sub['is_active']): ?>
                        <span class="badge badge-success" style="font-size:11px;padding:4px 10px;">Active</span>
                        <?php else: ?>
                        <span class="badge badge-warning" style="font-size:11px;padding:4px 10px;">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;">
                        <a href="<?= BASE_URL ?>newsletter.php?toggle=<?= $sub['id'] ?>&csrf_token=<?= csrf_token() ?>"
                           class="btn btn-<?= $sub['is_active'] ? 'warning' : 'success' ?> btn-xs"
                           title="<?= $sub['is_active'] ? 'Unsubscribe' : 'Reactivate' ?>">
                            <i class="fas fa-<?= $sub['is_active'] ? 'ban' : 'check' ?>"></i>
                        </a>
                        <a href="<?= BASE_URL ?>newsletter.php?delete=<?= $sub['id'] ?>&csrf_token=<?= csrf_token() ?>"
                           class="btn btn-danger btn-xs"
                           onclick="return confirm('Remove this subscriber?');"
                           title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
