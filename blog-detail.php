<?php
require_once __DIR__ . '/includes/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: ' . SITE_URL . 'blogs.php'); exit; }

// Fetch blog
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published' AND is_deleted=0 LIMIT 1");
$stmt->execute([$slug]);
$blog = $stmt->fetch();

if (!$blog) {
    include __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center"><h3>Article Not Found</h3><a href="'.SITE_URL.'blogs.php" class="btn btn-primary mt-3">Browse Articles</a></div>';
    include __DIR__ . '/includes/footer.php'; exit;
}

// Canonical: redirect blog/... to /blog/{slug}
if (strpos($_SERVER['REQUEST_URI'] ?? '', 'blog-detail.php') !== false) {
    header('Location: ' . SITE_URL . 'blog/' . $slug, true, 301);
    exit;
}

// Increment views
$pdo->prepare("UPDATE blogs SET views = views + 1 WHERE id = ?")->execute([$blog['id']]);

// Related posts (same category)
$related = [];
if ($blog['category']) {
    $relStmt = $pdo->prepare("SELECT * FROM blogs WHERE status='published' AND is_deleted=0 AND category=? AND id!=? ORDER BY created_at DESC LIMIT 3");
    $relStmt->execute([$blog['category'], $blog['id']]);
    $related = $relStmt->fetchAll();
}
if (count($related) < 3) {
    $ids = array_column($related, 'id');
    $ids[] = $blog['id'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $moreStmt = $pdo->prepare("SELECT * FROM blogs WHERE status='published' AND is_deleted=0 AND id NOT IN ($placeholders) ORDER BY views DESC LIMIT " . (3 - count($related)));
    $moreStmt->execute($ids);
    $related = array_merge($related, $moreStmt->fetchAll());
}

// Recent posts for sidebar
$recentPosts = $pdo->query("SELECT id, title, slug, featured_image, created_at FROM blogs WHERE status='published' AND is_deleted=0 ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Categories
$categories = $pdo->query("SELECT DISTINCT category FROM blogs WHERE status='published' AND is_deleted=0 AND category IS NOT NULL AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

// Tags
$tags = $blog['tags'] ? array_map('trim', explode(',', $blog['tags'])) : [];

// Featured image
$featImg = !empty($blog['featured_image'])
    ? UPLOAD_URL . 'blogs/' . $blog['featured_image']
    : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=900&q=80';

// ---- SEO ----
$pageTitle    = ($blog['meta_title'] ?: $blog['title']) . ' | MakaanDekho';
$pageDesc     = $blog['meta_description'] ?: $blog['short_description'] ?: mb_substr(strip_tags($blog['content'] ?? ''), 0, 160);
$pageKeywords = $blog['meta_keywords'] ?? trim(($blog['category'] ?? '') . ($blog['tags'] ? ', ' . $blog['tags'] : ''), ', ');
$pageCanonical = SITE_URL . 'blog/' . $blog['slug'];
$pageOgType    = 'article';
$pageOgImage   = !empty($blog['featured_image']) ? UPLOAD_URL . 'blogs/' . $blog['featured_image'] : null;
include __DIR__ . '/includes/header.php';
?>

<!-- ========== BLOG DETAIL ========== -->
<section class="blog-detail-page">
<div class="container">
<div class="row">

    <!-- Main Content -->
    <div class="col-lg-8">
        <article class="blog-article">
            <!-- Category + Meta -->
            <div class="blog-meta-row mb-3">
                <?php if ($blog['category']): ?>
                <a href="<?= SITE_URL ?>blogs.php?category=<?= urlencode($blog['category']) ?>" class="blog-cat-badge" style="position:static;"><?= htmlspecialchars($blog['category']) ?></a>
                <?php endif; ?>
                <span><i class="far fa-calendar"></i> <?= date('F d, Y', strtotime($blog['created_at'])) ?></span>
                <?php if ($blog['author_name']): ?>
                <span><i class="far fa-user"></i> <?= htmlspecialchars($blog['author_name']) ?></span>
                <?php endif; ?>
                <span><i class="far fa-eye"></i> <?= number_format($blog['views']) ?> views</span>
            </div>

            <!-- Title -->
            <h1 class="blog-article-title"><?= htmlspecialchars($blog['title']) ?></h1>

            <!-- Featured Image -->
            <div class="blog-article-img">
                <img src="<?= htmlspecialchars($featImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
            </div>

            <!-- Content -->
            <div class="blog-article-content">
                <?= $blog['content'] ?: nl2br(htmlspecialchars($blog['short_description'] ?? '')) ?>
            </div>

            <!-- Tags -->
            <?php if (!empty($tags)): ?>
            <div class="blog-article-tags">
                <i class="fas fa-tags"></i>
                <?php foreach ($tags as $tag): ?>
                <a href="<?= SITE_URL ?>blogs.php?q=<?= urlencode($tag) ?>" class="sidebar-tag"><?= htmlspecialchars($tag) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Share -->
            <div class="blog-share">
                <span>Share this article:</span>
                <div class="blog-share-btns">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . 'blog/' . $blog['slug']) ?>" target="_blank" class="share-btn fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL . 'blog/' . $blog['slug']) ?>&text=<?= urlencode($blog['title']) ?>" target="_blank" class="share-btn tw"><i class="fab fa-twitter"></i></a>
                    <a href="https://wa.me/?text=<?= urlencode($blog['title'] . ' ' . SITE_URL . 'blog/' . $blog['slug']) ?>" target="_blank" class="share-btn wa"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(SITE_URL . 'blog/' . $blog['slug']) ?>" target="_blank" class="share-btn li"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        <?php if (!empty($related)): ?>
        <div class="blog-related">
            <h4>Related Articles</h4>
            <div class="row g-4">
                <?php foreach ($related as $rp):
                    $rpImg = !empty($rp['featured_image']) ? UPLOAD_URL.'blogs/'.$rp['featured_image'] : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&q=60';
                ?>
                <div class="col-md-4">
                    <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($rp['slug']) ?>" class="blog-list-card">
                        <div class="blog-list-thumb">
                            <img src="<?= htmlspecialchars($rpImg) ?>" alt="">
                            <?php if ($rp['category']): ?><span class="blog-cat-badge"><?= htmlspecialchars($rp['category']) ?></span><?php endif; ?>
                        </div>
                        <div class="blog-list-body">
                            <h5><?= htmlspecialchars(mb_substr($rp['title'], 0, 60)) ?></h5>
                            <small class="text-muted"><i class="far fa-calendar"></i> <?= date('M d, Y', strtotime($rp['created_at'])) ?></small>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Recent Posts -->
        <div class="blog-sidebar-card">
            <h5 class="blog-sidebar-title">Recent Posts</h5>
            <?php foreach ($recentPosts as $rp):
                $rpImg = !empty($rp['featured_image']) ? UPLOAD_URL.'blogs/'.$rp['featured_image'] : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=100&q=60';
            ?>
            <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($rp['slug']) ?>" class="sidebar-post <?= $rp['id']==$blog['id']?'active':'' ?>">
                <img src="<?= htmlspecialchars($rpImg) ?>" alt="">
                <div>
                    <h6><?= htmlspecialchars(mb_substr($rp['title'], 0, 55)) ?></h6>
                    <small><i class="far fa-calendar"></i> <?= date('M d, Y', strtotime($rp['created_at'])) ?></small>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Categories -->
        <?php if (!empty($categories)): ?>
        <div class="blog-sidebar-card">
            <h5 class="blog-sidebar-title">Categories</h5>
            <ul class="sidebar-cat-list">
                <?php foreach ($categories as $cat): ?>
                <li class="<?= $blog['category']===$cat?'active':'' ?>">
                    <a href="<?= SITE_URL ?>blogs.php?category=<?= urlencode($cat) ?>"><?= htmlspecialchars($cat) ?> <i class="fas fa-chevron-right"></i></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- CTA -->
        <div class="blog-sidebar-card" style="background:var(--primary);border-color:var(--primary);text-align:center;">
            <h5 style="color:#fff;font-size:18px;font-weight:700;margin-bottom:8px;">Looking for Property?</h5>
            <p style="color:rgba(255,255,255,.8);font-size:13px;margin-bottom:16px;">Browse thousands of verified listings</p>
            <a href="<?= SITE_URL ?>properties.php" class="btn btn-light btn-sm" style="border-radius:20px;font-weight:600;padding:8px 24px;">Explore Properties</a>
        </div>
    </div>

</div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
