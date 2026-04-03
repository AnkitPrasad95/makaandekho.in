<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = SITE_URL;

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url><loc><?= $baseUrl ?></loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc><?= $baseUrl ?>about</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc><?= $baseUrl ?>contact</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc><?= $baseUrl ?>properties.php</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc><?= $baseUrl ?>blogs</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>

    <!-- Properties -->
    <?php
    $props = $pdo->query("SELECT slug, created_at FROM properties WHERE status='approved' AND is_deleted=0 ORDER BY created_at DESC");
    while ($p = $props->fetch()):
    ?>
    <url>
        <loc><?= $baseUrl ?>property/<?= htmlspecialchars($p['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($p['created_at'])) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endwhile; ?>

    <!-- Blogs -->
    <?php
    $blogs = $pdo->query("SELECT slug, created_at FROM blogs WHERE status='published' AND is_deleted=0 ORDER BY created_at DESC");
    while ($b = $blogs->fetch()):
    ?>
    <url>
        <loc><?= $baseUrl ?>blog/<?= htmlspecialchars($b['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($b['created_at'])) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endwhile; ?>

    <!-- Market Areas (Locations) -->
    <?php
    $locs = $pdo->query("SELECT slug FROM locations WHERE is_deleted=0 ORDER BY city");
    while ($l = $locs->fetch()):
    ?>
    <url>
        <loc><?= $baseUrl ?><?= htmlspecialchars($l['slug']) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endwhile; ?>
</urlset>
