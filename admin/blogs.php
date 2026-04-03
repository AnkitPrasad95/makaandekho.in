<?php require_once 'includes/header.php'; ?>

<?php
$filter = $_GET['status'] ?? '';
$where  = '';
$params = [];

if ($filter === 'published') {
    $where  = 'WHERE status = ? AND is_deleted=0';
    $params = ['published'];
} elseif ($filter === 'draft') {
    $where  = 'WHERE status = ? AND is_deleted=0';
    $params = ['draft'];
} else {
    $where  = 'WHERE is_deleted=0';
}

$stmt = $pdo->prepare("SELECT * FROM blogs {$where} ORDER BY created_at DESC");
$stmt->execute($params);
$blogs = $stmt->fetchAll();

// Counts for tabs
$total_count     = (int) $pdo->query("SELECT COUNT(*) FROM blogs WHERE is_deleted=0")->fetchColumn();
$published_count = (int) $pdo->query("SELECT COUNT(*) FROM blogs WHERE status='published' AND is_deleted=0")->fetchColumn();
$draft_count     = (int) $pdo->query("SELECT COUNT(*) FROM blogs WHERE status='draft' AND is_deleted=0")->fetchColumn();
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Blogs</h4>
    <p>Manage blog posts and articles</p>
  </div>
  <a href="<?= BASE_URL ?>blog-add.php" class="btn btn-primary btn-sm">
    <i class="fas fa-plus mr-1"></i>Add New Blog
  </a>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-3" style="gap:6px;">
  <li class="nav-item">
    <a class="nav-link <?= $filter === '' ? 'active' : '' ?>" href="<?= BASE_URL ?>blogs.php">
      All <span class="badge badge-light ml-1"><?= $total_count ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $filter === 'published' ? 'active' : '' ?>" href="<?= BASE_URL ?>blogs.php?status=published">
      Published <span class="badge badge-light ml-1"><?= $published_count ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $filter === 'draft' ? 'active' : '' ?>" href="<?= BASE_URL ?>blogs.php?status=draft">
      Draft <span class="badge badge-light ml-1"><?= $draft_count ?></span>
    </a>
  </li>
</ul>

<div class="card">
  <div class="card-header">
    <span><i class="fas fa-blog mr-2 text-primary"></i>Blog Posts (<?= count($blogs) ?>)</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover datatable mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Views</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($blogs as $blog): ?>
          <tr>
            <td><?= $blog['id'] ?></td>
            <td>
              <?php if ($blog['featured_image']): ?>
                <img src="<?= UPLOAD_URL ?>blogs/<?= htmlspecialchars($blog['featured_image']) ?>"
                     alt="" style="width:50px;height:36px;object-fit:cover;border-radius:4px;">
              <?php else: ?>
                <span class="text-muted" style="font-size:11px;">No image</span>
              <?php endif; ?>
            </td>
            <td style="font-weight:600;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              <?= htmlspecialchars($blog['title']) ?>
            </td>
            <td style="font-size:13px;"><?= htmlspecialchars($blog['category'] ?? '–') ?></td>
            <td>
              <?php if ($blog['status'] === 'published'): ?>
                <span class="badge badge-approved">Published</span>
              <?php else: ?>
                <span class="badge badge-pending">Draft</span>
              <?php endif; ?>
            </td>
            <td style="font-size:13px;"><?= number_format($blog['views']) ?></td>
            <td style="font-size:12px;white-space:nowrap;">
              <?= date('d M Y', strtotime($blog['created_at'])) ?>
            </td>
            <td style="white-space:nowrap;">
              <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>" target="_blank"
                 class="btn btn-info btn-xs" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="<?= BASE_URL ?>blog-edit.php?id=<?= $blog['id'] ?>"
                 class="btn btn-primary btn-xs" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <button type="button" class="btn btn-danger btn-xs btn-delete"
                      data-id="<?= $blog['id'] ?>"
                      data-title="<?= htmlspecialchars($blog['title']) ?>"
                      title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
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
        <form method="POST" action="<?= BASE_URL ?>blog-delete.php" id="deleteForm">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" id="delete-id">
          <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash mr-1"></i>Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('delete-id').value = this.dataset.id;
      document.getElementById('delete-title').textContent = this.dataset.title;
      $('#deleteModal').modal('show');
    });
  });
});
</script>

<?php require_once 'includes/footer.php'; ?>
