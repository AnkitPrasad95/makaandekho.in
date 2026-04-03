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
        $name        = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $content     = trim($_POST['content'] ?? '');
        $rating      = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $sort_order  = (int)($_POST['sort_order'] ?? 0);
        $is_active   = isset($_POST['is_active']) ? 1 : 0;
        $photo       = '';

        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                @mkdir(UPLOAD_DIR . 'testimonials', 0755, true);
                $photo = 'testimonial_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_DIR . 'testimonials/' . $photo);
            } else {
                flash('error', 'Invalid image format. Use JPG, PNG, or WebP.');
                header("Location: " . BASE_URL . "testimonials.php");
                exit;
            }
        }

        if ($name && $content) {
            $pdo->prepare("INSERT INTO testimonials (name, designation, photo, content, rating, is_active, sort_order) VALUES (?,?,?,?,?,?,?)")
                ->execute([$name, $designation, $photo, $content, $rating, $is_active, $sort_order]);
            flash('success', 'Testimonial added successfully.');
        } else {
            flash('error', 'Name and content are required.');
        }
        header("Location: " . BASE_URL . "testimonials.php");
        exit;
    }

    if ($action === 'edit') {
        $id          = (int)($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $content     = trim($_POST['content'] ?? '');
        $rating      = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $sort_order  = (int)($_POST['sort_order'] ?? 0);
        $is_active   = isset($_POST['is_active']) ? 1 : 0;

        if ($id && $name && $content) {
            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    @mkdir(UPLOAD_DIR . 'testimonials', 0755, true);
                    $photo = 'testimonial_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_DIR . 'testimonials/' . $photo);

                    // Delete old photo
                    $old = $pdo->prepare("SELECT photo FROM testimonials WHERE id=?");
                    $old->execute([$id]);
                    $oldPhoto = $old->fetchColumn();
                    if ($oldPhoto && file_exists(UPLOAD_DIR . 'testimonials/' . $oldPhoto)) {
                        @unlink(UPLOAD_DIR . 'testimonials/' . $oldPhoto);
                    }

                    $pdo->prepare("UPDATE testimonials SET name=?, designation=?, photo=?, content=?, rating=?, is_active=?, sort_order=? WHERE id=?")
                        ->execute([$name, $designation, $photo, $content, $rating, $is_active, $sort_order, $id]);
                } else {
                    flash('error', 'Invalid image format. Use JPG, PNG, or WebP.');
                    header("Location: " . BASE_URL . "testimonials.php");
                    exit;
                }
            } else {
                $pdo->prepare("UPDATE testimonials SET name=?, designation=?, content=?, rating=?, is_active=?, sort_order=? WHERE id=?")
                    ->execute([$name, $designation, $content, $rating, $is_active, $sort_order, $id]);
            }
            flash('success', 'Testimonial updated successfully.');
        }
        header("Location: " . BASE_URL . "testimonials.php");
        exit;
    }

    if ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare("UPDATE testimonials SET is_active = NOT is_active WHERE id=?")->execute([$id]);
            flash('success', 'Testimonial status toggled.');
        }
        header("Location: " . BASE_URL . "testimonials.php");
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $old = $pdo->prepare("SELECT photo FROM testimonials WHERE id=?");
            $old->execute([$id]);
            $oldPhoto = $old->fetchColumn();
            if ($oldPhoto && file_exists(UPLOAD_DIR . 'testimonials/' . $oldPhoto)) {
                @unlink(UPLOAD_DIR . 'testimonials/' . $oldPhoto);
            }
            $pdo->prepare("UPDATE testimonials SET is_deleted=1, deleted_at=NOW() WHERE id=?")->execute([$id]);
            flash('success', 'Testimonial deleted successfully.');
        }
        header("Location: " . BASE_URL . "testimonials.php");
        exit;
    }
}

// ---- Fetch testimonials ----
$testimonials = $pdo->query("SELECT * FROM testimonials WHERE is_deleted=0 ORDER BY sort_order ASC, id DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="fas fa-quote-left mr-2"></i>Testimonials</h4>
        <p>Manage customer testimonials and reviews</p>
    </div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTestimonialModal">
        <i class="fas fa-plus mr-1"></i> Add Testimonial
    </button>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="fas fa-quote-left mr-2 text-primary"></i>All Testimonials (<?= count($testimonials) ?>)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($testimonials)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox" style="font-size:40px;opacity:.3;"></i>
            <p class="mt-3">No testimonials yet.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Content</th>
                        <th>Rating</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonials as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td>
                            <?php if ($t['photo']): ?>
                                <img src="<?= UPLOAD_URL ?>testimonials/<?= htmlspecialchars($t['photo']) ?>"
                                     alt="" style="width:45px;height:45px;object-fit:cover;border-radius:50%;">
                            <?php else: ?>
                                <span class="text-muted" style="font-size:11px;">No photo</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:600;"><?= htmlspecialchars($t['name']) ?></td>
                        <td style="font-size:13px;"><?= htmlspecialchars($t['designation'] ?? '') ?></td>
                        <td style="font-size:13px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars($t['content']) ?>
                        </td>
                        <td style="white-space:nowrap;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $t['rating'] ? 'text-warning' : 'text-muted' ?>" style="font-size:12px;"></i>
                            <?php endfor; ?>
                        </td>
                        <td><?= $t['sort_order'] ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button type="submit" class="btn btn-xs <?= $t['is_active'] ? 'btn-success' : 'btn-secondary' ?>">
                                    <?= $t['is_active'] ? 'Active' : 'Inactive' ?>
                                </button>
                            </form>
                        </td>
                        <td style="font-size:12px;white-space:nowrap;">
                            <?= date('d M Y', strtotime($t['created_at'])) ?>
                        </td>
                        <td style="white-space:nowrap;">
                            <button class="btn btn-primary btn-xs edit-btn"
                                    data-id="<?= $t['id'] ?>"
                                    data-name="<?= htmlspecialchars($t['name']) ?>"
                                    data-designation="<?= htmlspecialchars($t['designation'] ?? '') ?>"
                                    data-content="<?= htmlspecialchars($t['content']) ?>"
                                    data-rating="<?= $t['rating'] ?>"
                                    data-sort-order="<?= $t['sort_order'] ?>"
                                    data-is-active="<?= $t['is_active'] ?>"
                                    data-toggle="modal" data-target="#editTestimonialModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-xs btn-delete"
                                    data-id="<?= $t['id'] ?>"
                                    data-name="<?= htmlspecialchars($t['name']) ?>">
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

<!-- ===== ADD TESTIMONIAL MODAL ===== -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2 text-primary"></i>Add Testimonial</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Customer name" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Designation</label>
            <input type="text" name="designation" class="form-control" placeholder="e.g. CEO, Homeowner">
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Photo</label>
            <input type="file" name="photo" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Accepted: JPG, PNG, WebP</small>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Content <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control" rows="4" placeholder="Testimonial content..." required></textarea>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Rating</label>
                    <select name="rating" class="form-control">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
            </div>
            <div class="col-4">
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Add Testimonial</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== EDIT TESTIMONIAL MODAL ===== -->
<div class="modal fade" id="editTestimonialModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content" id="editForm">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editId">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit mr-2 text-primary"></i>Edit Testimonial</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editName" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Designation</label>
            <input type="text" name="designation" id="editDesignation" class="form-control">
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Photo</label>
            <input type="file" name="photo" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Leave empty to keep current photo. Accepted: JPG, PNG, WebP</small>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="editContent" class="form-control" rows="4" required></textarea>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Rating</label>
                    <select name="rating" id="editRating" class="form-control">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" class="form-control" min="0">
                </div>
            </div>
            <div class="col-4">
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
        Are you sure you want to delete "<strong id="delete-name"></strong>"? This action cannot be undone.
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

<script>
// Populate edit modal
$(document).on('click', '.edit-btn', function () {
    $('#editId').val($(this).data('id'));
    $('#editName').val($(this).data('name'));
    $('#editDesignation').val($(this).data('designation'));
    $('#editContent').val($(this).data('content'));
    $('#editRating').val($(this).data('rating'));
    $('#editSortOrder').val($(this).data('sort-order'));
    $('#editIsActive').prop('checked', $(this).data('is-active') == 1);
});

// Delete confirmation
$(document).on('click', '.btn-delete', function () {
    $('#delete-id').val($(this).data('id'));
    $('#delete-name').text($(this).data('name'));
    $('#deleteModal').modal('show');
});
</script>

<?php require_once 'includes/footer.php'; ?>
