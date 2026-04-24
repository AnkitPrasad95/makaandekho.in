<?php
require_once __DIR__ . '/includes/db.php';

// Filters
$category = $_GET['category'] ?? '';
$search   = trim($_GET['q'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 9;
$offset   = ($page - 1) * $per_page;

// Build query
$where  = ["status = 'published'", "is_deleted=0"];
$params = [];

if ($category) {
    $where[]  = "category = ?";
    $params[] = $category;
}
if ($search) {
    $where[]  = "(title LIKE ? OR short_description LIKE ? OR tags LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like; $params[] = $like;
}

$whereSQL = implode(' AND ', $where);

// Count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE $whereSQL");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));

// Fetch
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE $whereSQL ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$blogs = $stmt->fetchAll();

// Categories for filter
$catStmt = $pdo->query("SELECT DISTINCT category FROM blogs WHERE status='published' AND is_deleted=0 AND category IS NOT NULL AND category != '' ORDER BY category");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Recent / popular posts for sidebar
$recentPosts = $pdo->query("SELECT id, title, slug, featured_image, created_at FROM blogs WHERE status='published' AND is_deleted=0 ORDER BY created_at DESC LIMIT 5")->fetchAll();
$popularPosts = $pdo->query("SELECT id, title, slug, featured_image, views FROM blogs WHERE status='published' AND is_deleted=0 ORDER BY views DESC LIMIT 5")->fetchAll();

// Tags
$tagStmt = $pdo->query("SELECT tags FROM blogs WHERE status='published' AND is_deleted=0 AND tags IS NOT NULL AND tags != ''");
$allTags = [];
while ($row = $tagStmt->fetch()) {
    foreach (explode(',', $row['tags']) as $t) {
        $t = trim($t);
        if ($t) $allTags[$t] = ($allTags[$t] ?? 0) + 1;
    }
}
arsort($allTags);
$allTags = array_slice($allTags, 0, 15, true);

function buildBlogPageUrl(int $p): string {
    $params = $_GET; $params['page'] = $p;
    return '?' . http_build_query($params);
}

// ---- SEO ----
$siteName     = $settings['site_name'] ?? 'MakaanDekho';
$pageTitle    = 'Blog & News' . ($category ? " – $category" : ($search ? " – $search" : '')) . ' | ' . $siteName;
$pageDesc     = $category
    ? "Read the latest articles on $category from $siteName. Real estate insights, buying guides, market trends and expert tips."
    : 'Real estate insights, market trends, buying guides and expert tips from ' . $siteName . '. Stay informed about India property market.';
$pageKeywords = ($category ?: 'real estate blog') . ', property news, market trends, home buying guide, real estate India, ' . $siteName;
$pageCanonical = SITE_URL . 'blogs.php' . ($category ? '?category=' . urlencode($category) : '');
$pageOgType    = 'website';
include __DIR__ . '/includes/header.php';
?>

<!-- ========== BLOG HERO ========== -->
<div class="blog-hero">
    <div class="container">
        <h1>Blog & News</h1>
        <p>Stay updated with the latest real estate trends, tips and guides</p>
        <!-- Search -->
        <form method="GET" action="<?= SITE_URL ?>blogs.php" class="blog-search-form">
            <i class="fas fa-search"></i>
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search articles...">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<!-- ========== BLOG LISTING ========== -->
<section class="blog-listing-page">
<div class="container">

    <!-- Category pills -->
    <?php if (!empty($categories)): ?>
    <div class="blog-category-bar">
        <a href="<?= SITE_URL ?>blogs.php" class="blog-cat-pill <?= !$category ? 'active' : '' ?>">All</a>
        <?php foreach ($categories as $cat): ?>
        <a href="<?= SITE_URL ?>blogs.php?category=<?= urlencode($cat) ?>" class="blog-cat-pill <?= $category === $cat ? 'active' : '' ?>">
            <?= htmlspecialchars($cat) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <?php if (empty($blogs)): ?>
            <div class="no-results">
                <i class="fas fa-newspaper"></i>
                <h4>No Articles Found</h4>
                <p>Try a different search or category</p>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($blogs as $i => $blog):
                    $blogImg = !empty($blog['featured_image'])
                        ? UPLOAD_URL . 'blogs/' . $blog['featured_image']
                        : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=500&q=75';
                ?>
                <!-- First post = featured large card -->
                <?php if ($i === 0 && $page === 1 && !$search): ?>
                <div class="col-12">
                    <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>" class="blog-featured-card">
                        <div class="blog-featured-img">
                            <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                            <?php if ($blog['category']): ?>
                            <span class="blog-cat-badge"><?= htmlspecialchars($blog['category']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="blog-featured-body">
                            <div class="blog-meta-row">
                                <span><i class="far fa-calendar"></i> <?= date('M d, Y', strtotime($blog['created_at'])) ?></span>
                                <?php if ($blog['author_name']): ?>
                                <span><i class="far fa-user"></i> <?= htmlspecialchars($blog['author_name']) ?></span>
                                <?php endif; ?>
                                <span><i class="far fa-eye"></i> <?= number_format($blog['views']) ?> views</span>
                            </div>
                            <h2><?= htmlspecialchars($blog['title']) ?></h2>
                            <p><?= htmlspecialchars(mb_substr($blog['short_description'] ?? '', 0, 200)) ?>...</p>
                            <span class="read-more-link">Read More <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <?php else: ?>
                <!-- Regular card -->
                <div class="col-md-6">
                    <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($blog['slug']) ?>" class="blog-list-card">
                        <div class="blog-list-thumb">
                            <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                            <?php if ($blog['category']): ?>
                            <span class="blog-cat-badge"><?= htmlspecialchars($blog['category']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="blog-list-body">
                            <div class="blog-meta-row">
                                <span><i class="far fa-calendar"></i> <?= date('M d, Y', strtotime($blog['created_at'])) ?></span>
                                <span><i class="far fa-eye"></i> <?= number_format($blog['views']) ?></span>
                            </div>
                            <h5><?= htmlspecialchars($blog['title']) ?></h5>
                            <p><?= htmlspecialchars(mb_substr($blog['short_description'] ?? '', 0, 100)) ?>...</p>
                            <span class="read-more-link">Read More <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="pagination-wrap">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?= buildBlogPageUrl($page-1) ?>"><i class="fas fa-chevron-left"></i></a></li>
                    <?php endif;
                    for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
                    <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="<?= buildBlogPageUrl($i) ?>"><?= $i ?></a></li>
                    <?php endfor;
                    if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="<?= buildBlogPageUrl($page+1) ?>"><i class="fas fa-chevron-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
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
                <a href="<?= SITE_URL ?>blog/<?= htmlspecialchars($rp['slug']) ?>" class="sidebar-post">
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
                    <li><a href="<?= SITE_URL ?>blogs.php?category=<?= urlencode($cat) ?>"><?= htmlspecialchars($cat) ?> <i class="fas fa-chevron-right"></i></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Tags -->
            <?php if (!empty($allTags)): ?>
            <div class="blog-sidebar-card">
                <h5 class="blog-sidebar-title">Popular Tags</h5>
                <div class="sidebar-tags">
                    <?php foreach ($allTags as $tag => $cnt): ?>
                    <a href="<?= SITE_URL ?>blogs.php?q=<?= urlencode($tag) ?>" class="sidebar-tag"><?= htmlspecialchars($tag) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
