<?php require_once 'includes/header.php'; ?>

<?php
$filter = $_GET['status'] ?? '';
$allowed = ['','pending','active','blocked'];
if (!in_array($filter, $allowed)) $filter = '';

$where = $filter ? "WHERE u.status = " . $pdo->quote($filter) : '';

$users = $pdo->query("
    SELECT u.*, COUNT(p.id) AS property_count
    FROM users u
    LEFT JOIN properties p ON p.user_id = u.id
    $where
    GROUP BY u.id
    ORDER BY
      CASE u.status WHEN 'pending' THEN 0 WHEN 'active' THEN 1 ELSE 2 END,
      u.created_at DESC
")->fetchAll();

$counts = $pdo->query("SELECT status, COUNT(*) AS cnt FROM users GROUP BY status")
              ->fetchAll(PDO::FETCH_KEY_PAIR);
$pending_count = (int)($counts['pending'] ?? 0);
?>

<div class="page-header">
  <h4>Users</h4>
  <p>Manage registered users – approve, block or assign roles</p>
</div>

<!-- Filter tabs -->
<div class="mb-3">
  <?php
  $tabs = [
    ''        => ['All Users',  array_sum($counts),          'secondary'],
    'pending' => ['Pending',    $counts['pending']  ?? 0,    'warning'],
    'active'  => ['Active',     $counts['active']   ?? 0,    'success'],
    'blocked' => ['Blocked',    $counts['blocked']  ?? 0,    'danger'],
  ];
  foreach ($tabs as $key => $tab):
    $active = ($filter === $key);
    $url    = BASE_URL . 'users.php' . ($key ? '?status=' . $key : '');
  ?>
  <a href="<?= $url ?>"
     class="btn btn-sm btn-outline-secondary mr-1 <?= $active ? 'active' : '' ?>"
     style="<?= $active ? 'background:#1a2332;color:#fff;border-color:#1a2332;' : '' ?>">
    <?= $tab[0] ?>
    <span class="badge badge-<?= $tab[2] ?> ml-1"><?= $tab[1] ?></span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Pending alert -->
<?php if ($pending_count > 0 && $filter !== 'pending'): ?>
<div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius:10px;">
  <i class="fas fa-user-clock fa-lg mr-3"></i>
  <div>
    <strong><?= $pending_count ?> user<?= $pending_count > 1 ? 's' : '' ?> waiting for approval.</strong>
    <a href="<?= BASE_URL ?>users.php?status=pending" class="btn btn-warning btn-sm ml-3">Review Now →</a>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <span>
      <i class="fas fa-users mr-2 text-primary"></i>
      <?= $filter ? ucfirst($filter) . ' Users' : 'All Users' ?> (<?= count($users) ?>)
    </span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover datatable mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>User</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Properties</th>
            <th>Status</th>
            <th>Registered</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr <?= $u['status']==='pending' ? 'style="background:#fffbf0;"' : '' ?>>
            <td><?= $u['id'] ?></td>
            <td>
              <div class="d-flex align-items-center">
                <div style="width:36px;height:36px;border-radius:50%;
                     background:<?= $u['status']==='pending'?'#f59e0b':($u['status']==='active'?'#0d6efd':'#6c757d') ?>;
                     color:#fff;display:flex;align-items:center;justify-content:center;
                     font-weight:700;font-size:13px;flex-shrink:0;margin-right:10px;">
                  <?= strtoupper(substr($u['name'], 0, 1)) ?>
                </div>
                <div>
                  <div style="font-weight:600;font-size:13.5px;"><?= htmlspecialchars($u['name']) ?></div>
                  <div style="font-size:12px;color:#6c757d;"><?= htmlspecialchars($u['email']) ?></div>
                </div>
              </div>
            </td>
            <td style="font-size:13px;"><?= htmlspecialchars($u['phone'] ?? '–') ?></td>
            <td>
              <!-- Role change -->
              <form method="POST" action="<?= BASE_URL ?>user-action.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="role">
                <select name="role" class="form-control form-control-sm d-inline-block"
                        style="width:100px;font-size:12px;" onchange="this.form.submit()">
                  <?php foreach (['owner','agent','builder'] as $r): ?>
                  <option value="<?=$r?>" <?= $u['role']===$r?'selected':'' ?>><?= ucfirst($r) ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
            <td style="font-size:13px;">
              <?= $u['property_count'] > 0
                ? '<a href="' . BASE_URL . 'properties.php" style="font-size:13px;">' . $u['property_count'] . '</a>'
                : '<span class="text-muted">0</span>' ?>
            </td>
            <td>
              <span class="badge badge-<?= $u['status'] ?> px-2 py-1" style="font-size:11px;">
                <?= $u['status'] === 'pending' ? '⏳ Pending' : ucfirst($u['status']) ?>
              </span>
            </td>
            <td style="font-size:12px;white-space:nowrap;">
              <?= date('d M Y', strtotime($u['created_at'])) ?>
            </td>
            <td style="white-space:nowrap;">

              <?php if ($u['status'] === 'pending'): ?>
              <!-- Approve -->
              <form method="POST" action="<?= BASE_URL ?>user-action.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-success btn-xs"
                        onclick="return confirm('Approve this user?')" title="Approve">
                  <i class="fas fa-check mr-1"></i>Approve
                </button>
              </form>
              <!-- Reject -->
              <form method="POST" action="<?= BASE_URL ?>user-action.php" class="d-inline ml-1">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="btn btn-danger btn-xs"
                        onclick="return confirm('Reject and block this user?')" title="Reject">
                  <i class="fas fa-times mr-1"></i>Reject
                </button>
              </form>

              <?php elseif ($u['status'] === 'active'): ?>
              <!-- Block -->
              <form method="POST" action="<?= BASE_URL ?>user-action.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="toggle_status">
                <button type="submit" class="btn btn-warning btn-xs"
                        onclick="return confirm('Block this user?')" title="Block">
                  <i class="fas fa-ban mr-1"></i>Block
                </button>
              </form>

              <?php else: ?>
              <!-- Unblock -->
              <form method="POST" action="<?= BASE_URL ?>user-action.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="id"     value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="toggle_status">
                <button type="submit" class="btn btn-success btn-xs"
                        onclick="return confirm('Unblock this user?')" title="Unblock">
                  <i class="fas fa-check mr-1"></i>Unblock
                </button>
              </form>
              <?php endif; ?>

            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
