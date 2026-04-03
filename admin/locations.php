<?php require_once 'includes/header.php'; ?>

<?php
// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("UPDATE locations SET is_deleted=1, deleted_at=NOW() WHERE id=?");
    $stmt->execute([(int)$_GET['delete']]);
    flash('success', 'Location deleted.');
    header('Location: ' . BASE_URL . 'locations.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $edit_id = (int) ($_POST['edit_id'] ?? 0);
    $city    = trim($_POST['city']  ?? '');
    $state   = trim($_POST['state'] ?? '');
    $area    = trim($_POST['area']  ?? '');

    if (!$city || !$state) {
        flash('error', 'City and State are required.');
    } else {
        $slug_base = slugify(($area ? $city . '-' . $area : $city));
        $slug = $slug_base;
        $i = 1;

        if ($edit_id) {
            // Slug uniqueness excluding self
            while (true) {
                $chk = $pdo->prepare("SELECT id FROM locations WHERE slug=? AND id!=?");
                $chk->execute([$slug, $edit_id]);
                if (!$chk->fetch()) break;
                $slug = $slug_base . '-' . (++$i);
            }
            $pdo->prepare("UPDATE locations SET city=?,state=?,area=?,slug=? WHERE id=?")
                ->execute([$city, $state, $area ?: null, $slug, $edit_id]);
            flash('success', 'Location updated.');
        } else {
            while (true) {
                $chk = $pdo->prepare("SELECT id FROM locations WHERE slug=?");
                $chk->execute([$slug]);
                if (!$chk->fetch()) break;
                $slug = $slug_base . '-' . (++$i);
            }
            $pdo->prepare("INSERT INTO locations (city,state,area,slug) VALUES (?,?,?,?)")
                ->execute([$city, $state, $area ?: null, $slug]);
            flash('success', 'Location added.');
        }
    }
    header('Location: ' . BASE_URL . 'locations.php');
    exit;
}

// Fetch for edit
$editing = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM locations WHERE id=? AND is_deleted=0");
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

$locations = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY state, city, area")->fetchAll();
?>

<div class="page-header">
  <h4>Locations</h4>
  <p>Manage cities, states and areas for property listings</p>
</div>

<div class="row">

  <!-- Add / Edit Form -->
  <div class="col-lg-4 mb-4">
    <div class="card">
      <div class="card-header">
        <?= $editing ? '<i class="fas fa-edit mr-2 text-warning"></i>Edit Location' : '<i class="fas fa-plus mr-2 text-success"></i>Add Location' ?>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="edit_id" value="<?= $editing['id'] ?? 0 ?>">

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($editing['city'] ?? '') ?>"
                   placeholder="e.g. Delhi" required>
          </div>
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">State <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($editing['state'] ?? '') ?>"
                   placeholder="e.g. Delhi" required>
          </div>
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Area / Locality <small class="text-muted">(optional)</small></label>
            <input type="text" name="area" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($editing['area'] ?? '') ?>"
                   placeholder="e.g. Sector 56">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fas fa-save mr-1"></i><?= $editing ? 'Update' : 'Add Location' ?>
            </button>
            <?php if ($editing): ?>
            <a href="<?= BASE_URL ?>locations.php" class="btn btn-outline-secondary btn-sm ml-2">Cancel</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header"><i class="fas fa-info-circle mr-2 text-info"></i>SEO URL Preview</div>
      <div class="card-body">
        <p class="text-muted mb-2" style="font-size:12px;">URLs are auto-generated as:</p>
        <code style="font-size:12px;background:#f0f2f5;padding:4px 8px;border-radius:4px;display:block;">
          /delhi<br>/mumbai-bandra<br>/gurgaon-sector-56
        </code>
      </div>
    </div>
  </div>

  <!-- Location List -->
  <div class="col-lg-8 mb-4">
    <div class="card">
      <div class="card-header">
        <span><i class="fas fa-map-marker-alt mr-2 text-primary"></i>All Locations (<?= count($locations) ?>)</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover datatable mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>City</th>
                <th>State</th>
                <th>Area</th>
                <th>SEO Slug</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($locations as $loc): ?>
              <tr class="<?= ($editing && $editing['id'] == $loc['id']) ? 'table-warning' : '' ?>">
                <td><?= $loc['id'] ?></td>
                <td style="font-weight:600;"><?= htmlspecialchars($loc['city']) ?></td>
                <td><?= htmlspecialchars($loc['state']) ?></td>
                <td><?= htmlspecialchars($loc['area'] ?? '–') ?></td>
                <td><code style="font-size:11px;background:#f0f2f5;padding:2px 5px;border-radius:3px;"><?= htmlspecialchars($loc['slug']) ?></code></td>
                <td style="white-space:nowrap;">
                  <a href="<?= BASE_URL ?>locations.php?edit=<?= $loc['id'] ?>"
                     class="btn btn-outline-warning btn-xs">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="<?= BASE_URL ?>locations.php?delete=<?= $loc['id'] ?>"
                     class="btn btn-outline-danger btn-xs"
                     onclick="return confirm('Delete this location?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
