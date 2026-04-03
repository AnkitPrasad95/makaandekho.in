<?php require_once 'includes/header.php'; ?>

<?php
$id = (int) ($_GET['id'] ?? 0);
if (!$id) { flash('error', 'Invalid page.'); header('Location: ' . BASE_URL . 'pages.php'); exit; }

$page = $pdo->prepare("SELECT * FROM cms_pages WHERE id=? AND is_deleted=0");
$page->execute([$id]);
$page = $page->fetch();
if (!$page) { flash('error', 'Page not found.'); header('Location: ' . BASE_URL . 'pages.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $content      = $_POST['content']          ?? '';
    $meta_title   = trim($_POST['meta_title']   ?? '');
    $meta_desc    = trim($_POST['meta_description'] ?? '');
    $meta_keys    = trim($_POST['meta_keywords']    ?? '');

    $stmt = $pdo->prepare("
        UPDATE cms_pages
        SET content=?, meta_title=?, meta_description=?, meta_keywords=?, updated_at=NOW()
        WHERE id=?
    ");
    $stmt->execute([$content, $meta_title, $meta_desc, $meta_keys, $id]);

    flash('success', 'Page "' . $page['page_name'] . '" updated successfully.');
    header('Location: ' . BASE_URL . 'page-edit.php?id=' . $id);
    exit;
}
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Edit Page: <?= htmlspecialchars($page['page_name']) ?></h4>
    <p>
      <a href="<?= BASE_URL ?>pages.php" class="text-muted">CMS Pages</a>
      <i class="fas fa-angle-right mx-1 text-muted" style="font-size:11px;"></i>
      <?= htmlspecialchars($page['page_name']) ?>
    </p>
  </div>
  <a href="<?= BASE_URL ?>pages.php" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left mr-1"></i>Back
  </a>
</div>

<form method="POST">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

  <div class="row">

    <!-- Content -->
    <div class="col-lg-8 mb-4">
      <div class="card">
        <div class="card-header">Page Content</div>
        <div class="card-body">
          <div class="form-group mb-0">
            <label style="font-size:12px;font-weight:600;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">
              Content (HTML supported)
            </label>
            <textarea name="content" id="pageContent"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- SEO -->
    <div class="col-lg-4 mb-4">
      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-search mr-2 text-success"></i>SEO Settings
        </div>
        <div class="card-body">
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Meta Title</label>
            <input type="text" name="meta_title" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>"
                   placeholder="Page title for search engines"
                   maxlength="255">
            <small class="text-muted">Recommended: 50–60 characters</small>
          </div>
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Meta Description</label>
            <textarea name="meta_description" class="form-control form-control-sm" rows="3"
                      placeholder="Brief page description for search engines"
                      maxlength="320"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
            <small class="text-muted">Recommended: 120–160 characters</small>
          </div>
          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($page['meta_keywords'] ?? '') ?>"
                   placeholder="keyword1, keyword2, keyword3">
            <small class="text-muted">Comma-separated keywords</small>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">Page Info</div>
        <div class="card-body">
          <table class="table table-sm table-borderless mb-0">
            <tr>
              <td class="text-muted" style="font-size:12px;">Slug</td>
              <td><code style="font-size:12px;"><?= htmlspecialchars($page['page_slug']) ?></code></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size:12px;">Last Saved</td>
              <td style="font-size:12px;"><?= $page['updated_at'] ? date('d M Y, g:i a', strtotime($page['updated_at'])) : 'Never' ?></td>
            </tr>
          </table>
          <button type="submit" class="btn btn-primary btn-block mt-3">
            <i class="fas fa-save mr-2"></i>Save Changes
          </button>
        </div>
      </div>
    </div>

  </div>
</form>

<?php require_once 'includes/footer.php'; ?>
<script>
$('#pageContent').summernote({
  height: 400,
  placeholder: 'Enter page content here...',
  toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['table', ['table']],
    ['insert', ['link', 'picture', 'video', 'hr']],
    ['view', ['fullscreen', 'codeview']]
  ]
});
</script>
