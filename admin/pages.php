<?php require_once 'includes/header.php'; ?>

<?php
$pages = $pdo->query("SELECT * FROM cms_pages ORDER BY id ASC")->fetchAll();
?>

<div class="page-header">
  <h4>CMS Pages</h4>
  <p>Manage website content and SEO for each page</p>
</div>

<div class="card">
  <div class="card-header">
    <span><i class="fas fa-file-alt mr-2 text-primary"></i>All Pages (<?= count($pages) ?>)</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Page Name</th>
            <th>Slug</th>
            <th>Meta Title</th>
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pages as $pg): ?>
          <tr>
            <td><?= $pg['id'] ?></td>
            <td style="font-weight:600;"><?= htmlspecialchars($pg['page_name']) ?></td>
            <td><code style="font-size:12px;background:#f0f2f5;padding:2px 6px;border-radius:4px;"><?= htmlspecialchars($pg['page_slug']) ?></code></td>
            <td style="font-size:13px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              <?= htmlspecialchars($pg['meta_title'] ?? '–') ?>
            </td>
            <td style="font-size:12px;white-space:nowrap;">
              <?= $pg['updated_at'] ? date('d M Y, g:i a', strtotime($pg['updated_at'])) : '–' ?>
            </td>
            <td>
              <a href="<?= BASE_URL ?>page-edit.php?id=<?= $pg['id'] ?>"
                 class="btn btn-primary btn-xs">
                <i class="fas fa-edit mr-1"></i>Edit
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($pages)): ?>
          <tr><td colspan="6" class="text-center text-muted py-5">No pages found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
