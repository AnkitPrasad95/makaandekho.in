<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

// ---- Handle Actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title      = trim($_POST['title'] ?? '');
        $subtitle   = trim($_POST['subtitle'] ?? '');
        $link       = trim($_POST['link'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active  = isset($_POST['is_active']) ? 1 : 0;
        $image      = '';

        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                @mkdir(UPLOAD_DIR . 'banners', 0755, true);
                $image = 'banner_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . 'banners/' . $image);
            } else {
                flash('error', 'Invalid image format. Use JPG, PNG, or WebP.');
                header("Location: " . BASE_URL . "banners.php");
                exit;
            }
        }

        if ($title) {
            $pdo->prepare("INSERT INTO banners (title, subtitle, image, link, is_active, sort_order) VALUES (?,?,?,?,?,?)")
                ->execute([$title, $subtitle, $image, $link, $is_active, $sort_order]);
            flash('success', 'Banner added successfully.');
        } else {
            flash('error', 'Title is required.');
        }
        header("Location: " . BASE_URL . "banners.php");
        exit;
    }

    if ($action === 'edit') {
        $id         = (int)($_POST['id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $subtitle   = trim($_POST['subtitle'] ?? '');
        $link       = trim($_POST['link'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active  = isset($_POST['is_active']) ? 1 : 0;

        if ($id && $title) {
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    @mkdir(UPLOAD_DIR . 'banners', 0755, true);
                    $image = 'banner_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . 'banners/' . $image);

                    // Delete old image
                    $old = $pdo->prepare("SELECT image FROM banners WHERE id=?");
                    $old->execute([$id]);
                    $oldImage = $old->fetchColumn();
                    if ($oldImage && file_exists(UPLOAD_DIR . 'banners/' . $oldImage)) {
                        @unlink(UPLOAD_DIR . 'banners/' . $oldImage);
                    }

                    $pdo->prepare("UPDATE banners SET title=?, subtitle=?, image=?, link=?, is_active=?, sort_order=? WHERE id=?")
                        ->execute([$title, $subtitle, $image, $link, $is_active, $sort_order, $id]);
                } else {
                    flash('error', 'Invalid image format. Use JPG, PNG, or WebP.');
                    header("Location: " . BASE_URL . "banners.php");
                    exit;
                }
            } else {
                $pdo->prepare("UPDATE banners SET title=?, subtitle=?, link=?, is_active=?, sort_order=? WHERE id=?")
                    ->execute([$title, $subtitle, $link, $is_active, $sort_order, $id]);
            }
            flash('success', 'Banner updated successfully.');
        }
        header("Location: " . BASE_URL . "banners.php");
        exit;
    }

    if ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare("UPDATE banners SET is_active = NOT is_active WHERE id=?")->execute([$id]);
            flash('success', 'Banner status toggled.');
        }
        header("Location: " . BASE_URL . "banners.php");
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $old = $pdo->prepare("SELECT image FROM banners WHERE id=?");
            $old->execute([$id]);
            $oldImage = $old->fetchColumn();
            if ($oldImage && file_exists(UPLOAD_DIR . 'banners/' . $oldImage)) {
                @unlink(UPLOAD_DIR . 'banners/' . $oldImage);
            }
            $pdo->prepare("UPDATE banners SET is_deleted=1, deleted_at=NOW() WHERE id=?")->execute([$id]);
            flash('success', 'Banner deleted successfully.');
        }
        header("Location: " . BASE_URL . "banners.php");
        exit;
    }
}

// ---- Fetch banners ----
$banners = $pdo->query("SELECT * FROM banners WHERE is_deleted=0 ORDER BY sort_order ASC, id DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="fas fa-images mr-2"></i>Banners</h4>
        <p>Manage homepage banners and sliders</p>
    </div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addBannerModal">
        <i class="fas fa-plus mr-1"></i> Add Banner
    </button>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="fas fa-images mr-2 text-primary"></i>All Banners (<?= count($banners) ?>)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($banners)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox" style="font-size:40px;opacity:.3;"></i>
            <p class="mt-3">No banners yet.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Link</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $banner): ?>
                    <tr>
                        <td><?= $banner['id'] ?></td>
                        <td>
                            <?php if ($banner['image']): ?>
                                <img src="<?= UPLOAD_URL ?>banners/<?= htmlspecialchars($banner['image']) ?>"
                                     alt="" style="height:60px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted" style="font-size:11px;">No image</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:600;"><?= htmlspecialchars($banner['title']) ?></td>
                        <td style="font-size:13px;"><?= htmlspecialchars($banner['subtitle'] ?? '') ?></td>
                        <td style="font-size:12px;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars($banner['link'] ?? '') ?>
                        </td>
                        <td><?= $banner['sort_order'] ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                <button type="submit" class="btn btn-xs <?= $banner['is_active'] ? 'btn-success' : 'btn-secondary' ?>">
                                    <?= $banner['is_active'] ? 'Active' : 'Inactive' ?>
                                </button>
                            </form>
                        </td>
                        <td style="font-size:12px;white-space:nowrap;">
                            <?= date('d M Y', strtotime($banner['created_at'])) ?>
                        </td>
                        <td style="white-space:nowrap;">
                            <button class="btn btn-primary btn-xs edit-btn"
                                    data-id="<?= $banner['id'] ?>"
                                    data-title="<?= htmlspecialchars($banner['title']) ?>"
                                    data-subtitle="<?= htmlspecialchars($banner['subtitle'] ?? '') ?>"
                                    data-link="<?= htmlspecialchars($banner['link'] ?? '') ?>"
                                    data-sort-order="<?= $banner['sort_order'] ?>"
                                    data-is-active="<?= $banner['is_active'] ?>"
                                    data-toggle="modal" data-target="#editBannerModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-xs btn-delete"
                                    data-id="<?= $banner['id'] ?>"
                                    data-title="<?= htmlspecialchars($banner['title']) ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ===== ADD BANNER MODAL ===== -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2 text-primary"></i>Add Banner</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Banner title" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Subtitle</label>
            <input type="text" name="subtitle" class="form-control" placeholder="Banner subtitle">
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Image</label>
            <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Accepted: JPG, PNG, WebP</small>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Link URL</label>
            <input type="text" name="link" class="form-control" placeholder="https://...">
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Active</label>
                    <div class="custom-control custom-switch mt-1">
                        <input type="checkbox" class="custom-control-input" id="addIsActive" name="is_active" value="1" checked>
                        <label class="custom-control-label" for="addIsActive">Visible</label>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Add Banner</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== EDIT BANNER MODAL ===== -->
<div class="modal fade" id="editBannerModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content" id="editForm">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editId">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit mr-2 text-primary"></i>Edit Banner</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="editTitle" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Subtitle</label>
            <input type="text" name="subtitle" id="editSubtitle" class="form-control">
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Image</label>
            <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Leave empty to keep current image. Accepted: JPG, PNG, WebP</small>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Link URL</label>
            <input type="text" name="link" id="editLink" class="form-control">
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Active</label>
                    <div class="custom-control custom-switch mt-1">
                        <input type="checkbox" class="custom-control-input" id="editIsActive" name="is_active" value="1">
                        <label class="custom-control-label" for="editIsActive">Visible</label>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== DELETE CONFIRMATION MODAL ===== -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger mr-2"></i>Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete "<strong id="delete-title"></strong>"? This action cannot be undone.
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <form method="POST" id="deleteForm">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" id="delete-id">
          <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash mr-1"></i>Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Populate edit modal
    $(document).on('click', '.edit-btn', function () {
        $('#editId').val($(this).data('id'));
        $('#editTitle').val($(this).data('title'));
        $('#editSubtitle').val($(this).data('subtitle'));
        $('#editLink').val($(this).data('link'));
        $('#editSortOrder').val($(this).data('sort-order'));
        $('#editIsActive').prop('checked', $(this).data('is-active') == 1);
    });

    // Delete confirmation
    $(document).on('click', '.btn-delete', function () {
        $('#delete-id').val($(this).data('id'));
        $('#delete-title').text($(this).data('title'));
        $('#deleteModal').modal('show');
    });
});
</script>
