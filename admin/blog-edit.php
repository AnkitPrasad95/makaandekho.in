<?php
ob_start();
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { flash('error', 'Invalid blog.'); header('Location: ' . BASE_URL . 'blogs.php'); exit; }

$blog = $pdo->prepare("SELECT * FROM blogs WHERE id=? AND is_deleted=0");
$blog->execute([$id]);
$blog = $blog->fetch();
if (!$blog) { flash('error', 'Blog not found.'); header('Location: ' . BASE_URL . 'blogs.php'); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $title             = trim($_POST['title']             ?? '');
    $category          = trim($_POST['category']          ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $content           = $_POST['content']                ?? '';
    $author_name       = trim($_POST['author_name']       ?? '');
    $tags              = trim($_POST['tags']              ?? '');
    $status            = $_POST['status']                 ?? 'draft';
    $meta_title        = trim($_POST['meta_title']        ?? '');
    $meta_description  = trim($_POST['meta_description']  ?? '');
    $meta_keywords     = trim($_POST['meta_keywords']     ?? '');

    if (!$title) $errors[] = 'Blog title is required.';

    if (empty($errors)) {

        // Regenerate slug only if title changed
        $slug = $blog['slug'];
        if ($title !== $blog['title']) {
            $slug_base = slugify($title);
            $slug = $slug_base;
            $i = 1;
            while (true) {
                $s = $pdo->prepare("SELECT id FROM blogs WHERE slug=? AND id!=?");
                $s->execute([$slug, $id]);
                if (!$s->fetch()) break;
                $slug = $slug_base . '-' . (++$i);
            }
        }

        // Featured image upload
        $featured_image = $blog['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                $fname = 'blog_' . time() . '_' . mt_rand(100,999) . '.' . $ext;
                $dest  = UPLOAD_DIR . 'blogs/' . $fname;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $dest)) {
                    // Delete old image
                    if ($blog['featured_image'] && file_exists(UPLOAD_DIR . 'blogs/' . $blog['featured_image'])) {
                        unlink(UPLOAD_DIR . 'blogs/' . $blog['featured_image']);
                    }
                    $featured_image = $fname;
                }
            } else {
                $errors[] = 'Invalid image format. Use JPG, PNG, WebP or GIF.';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE blogs SET
              title=?, slug=?, category=?, featured_image=?, short_description=?, content=?,
              author_name=?, tags=?, status=?, meta_title=?, meta_description=?, meta_keywords=?,
              updated_at=NOW()
            WHERE id=?
        ");
        $stmt->execute([
            $title, $slug, $category, $featured_image, $short_description, $content,
            $author_name, $tags, $status, $meta_title, $meta_description, $meta_keywords,
            $id
        ]);

        flash('success', 'Blog "' . $title . '" updated successfully.');
        header('Location: ' . BASE_URL . 'blog-edit.php?id=' . $id);
        exit;
    }
}

require_once 'includes/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h4>Edit Blog: <?= htmlspecialchars($blog['title']) ?></h4>
    <p>
      <a href="<?= BASE_URL ?>blogs.php" class="text-muted">Blogs</a>
      <i class="fas fa-angle-right mx-1 text-muted" style="font-size:11px;"></i>
      Edit
    </p>
  </div>
  <a href="<?= BASE_URL ?>blogs.php" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left mr-1"></i>Back
  </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger alert-dismissible fade show mb-3">
  <i class="fas fa-exclamation-circle mr-2"></i>
  <ul class="mb-0" style="padding-left:18px;">
    <?php foreach ($errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
  </ul>
  <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

  <div class="row">

    <!-- LEFT COLUMN -->
    <div class="col-lg-8 mb-4">
      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit mr-2 text-primary"></i>Blog Details</div>
        <div class="card-body">
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required
                   value="<?= htmlspecialchars($blog['title']) ?>"
                   placeholder="Enter blog title" maxlength="255">
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Category</label>
                <select name="category" class="form-control form-control-sm">
                  <option value="">-- Select Category --</option>
                  <?php
                  $categories = ['Technology','Real Estate','Home Decor','Investment','Legal','Lifestyle','News','Tips & Guides'];
                  foreach ($categories as $cat):
                    $sel = ($blog['category'] === $cat) ? 'selected' : '';
                  ?>
                    <option value="<?= $cat ?>" <?= $sel ?>><?= $cat ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label style="font-size:12.5px;font-weight:600;">Author Name</label>
                <input type="text" name="author_name" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($blog['author_name'] ?? '') ?>"
                       placeholder="Author name" maxlength="100">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Featured Image</label>
            <?php if ($blog['featured_image']): ?>
              <div class="mb-2">
                <img src="<?= UPLOAD_URL ?>blogs/<?= htmlspecialchars($blog['featured_image']) ?>"
                     alt="Current image" style="max-height:140px;border-radius:6px;border:1px solid #dee2e6;">
                <small class="d-block text-muted mt-1">Current image: <?= htmlspecialchars($blog['featured_image']) ?></small>
              </div>
            <?php endif; ?>
            <div class="custom-file">
              <input type="file" name="featured_image" class="custom-file-input" id="featuredImage" accept="image/*">
              <label class="custom-file-label" for="featuredImage"><?= $blog['featured_image'] ? 'Replace image...' : 'Choose image...' ?></label>
            </div>
            <small class="text-muted">Accepted: JPG, PNG, WebP, GIF. Leave empty to keep current image.</small>
            <div id="imagePreview" class="mt-2" style="display:none;">
              <img id="previewImg" src="" alt="Preview" style="max-height:160px;border-radius:6px;border:1px solid #dee2e6;">
            </div>
          </div>

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Short Description</label>
            <textarea name="short_description" class="form-control form-control-sm" rows="3"
                      placeholder="Brief summary for listing pages"><?= htmlspecialchars($blog['short_description'] ?? '') ?></textarea>
          </div>

          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Full Content</label>
            <textarea name="content" id="blogContent"><?= htmlspecialchars($blog['content'] ?? '') ?></textarea>
          </div>

          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">Tags</label>
            <input type="text" name="tags" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($blog['tags'] ?? '') ?>"
                   placeholder="e.g. real estate, investment, tips (comma separated)">
            <small class="text-muted">Comma-separated tags</small>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4 mb-4">

      <!-- Status Card -->
      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-cog mr-2 text-secondary"></i>Publish</div>
        <div class="card-body">
          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">Status</label>
            <select name="status" class="form-control form-control-sm">
              <option value="draft" <?= $blog['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= $blog['status'] === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
          </div>
        </div>
      </div>

      <!-- SEO Card -->
      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-search mr-2 text-success"></i>SEO Settings</div>
        <div class="card-body">
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Meta Title</label>
            <input type="text" name="meta_title" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($blog['meta_title'] ?? '') ?>"
                   placeholder="SEO title" maxlength="255">
            <small class="text-muted">Recommended: 50-60 characters</small>
          </div>
          <div class="form-group">
            <label style="font-size:12.5px;font-weight:600;">Meta Description</label>
            <textarea name="meta_description" class="form-control form-control-sm" rows="3"
                      placeholder="SEO description" maxlength="320"><?= htmlspecialchars($blog['meta_description'] ?? '') ?></textarea>
            <small class="text-muted">Recommended: 120-160 characters</small>
          </div>
          <div class="form-group mb-0">
            <label style="font-size:12.5px;font-weight:600;">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control form-control-sm"
                   value="<?= htmlspecialchars($blog['meta_keywords'] ?? '') ?>"
                   placeholder="keyword1, keyword2, keyword3">
            <small class="text-muted">Comma-separated keywords</small>
          </div>
        </div>
      </div>

      <!-- Blog Info Card -->
      <div class="card mb-4">
        <div class="card-header">Blog Info</div>
        <div class="card-body">
          <table class="table table-sm table-borderless mb-0">
            <tr>
              <td class="text-muted" style="font-size:12px;">ID</td>
              <td style="font-size:12px;">#<?= $blog['id'] ?></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size:12px;">Slug</td>
              <td><code style="font-size:12px;"><?= htmlspecialchars($blog['slug']) ?></code></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size:12px;">Views</td>
              <td style="font-size:12px;"><?= number_format($blog['views']) ?></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size:12px;">Created</td>
              <td style="font-size:12px;"><?= date('d M Y, g:i a', strtotime($blog['created_at'])) ?></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size:12px;">Updated</td>
              <td style="font-size:12px;"><?= $blog['updated_at'] ? date('d M Y, g:i a', strtotime($blog['updated_at'])) : 'Never' ?></td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn btn-primary btn-block">
        <i class="fas fa-save mr-2"></i>Save Changes
      </button>

    </div>

  </div>
</form>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Image preview
  document.getElementById('featuredImage').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
      this.nextElementSibling.textContent = file.name;
      var reader = new FileReader();
      reader.onload = function(ev) {
        document.getElementById('previewImg').src = ev.target.result;
        document.getElementById('imagePreview').style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>

<script>
$(document).ready(function() {
  $('#blogContent').summernote({
    height: 300,
    placeholder: 'Write your blog content here...',
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
});
</script>
