<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = SITE_URL;
$today = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url><loc><?= $baseUrl ?></loc><lastmod><?= $today ?></lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc><?= $baseUrl ?>about</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc><?= $baseUrl ?>contact</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc><?= $baseUrl ?>properties</loc><lastmod><?= $today ?></lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc><?= $baseUrl ?>blogs</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
    <url><loc><?= $baseUrl ?>locations</loc><lastmod><?= $today ?></lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?= $baseUrl ?>terms</loc><changefreq>yearly</changefreq><priority>0.3</priority></url>

    <!-- Market Areas (Location Pages) -->
<?php
    $locs = $pdo->query("
        SELECT l.slug, MAX(p.created_at) as last_property
        FROM locations l
        LEFT JOIN properties p ON p.location_id = l.id AND p.status='approved' AND p.is_deleted=0
        WHERE l.is_deleted=0
        GROUP BY l.id, l.slug
        ORDER BY l.city
    ");
    while ($l = $locs->fetch()):
        $lastmod = $l['last_property'] ? date('Y-m-d', strtotime($l['last_property'])) : $today;
?>
    <url>
        <loc><?= $baseUrl . htmlspecialchars($l['slug']) ?></loc>
        <lastmod><?= $lastmod ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endwhile; ?>

    <!-- Properties (SEO URLs: /location-slug/property-slug) -->
<?php
    $props = $pdo->query("
        SELECT p.slug, p.created_at, p.updated_at, l.slug AS location_slug
        FROM properties p
        LEFT JOIN locations l ON p.location_id = l.id
        WHERE p.status='approved' AND p.is_deleted=0
        ORDER BY p.created_at DESC
    ");
    while ($p = $props->fetch()):
        $lastmod = date('Y-m-d', strtotime($p['updated_at'] ?? $p['created_at']));
        $propUrl = !empty($p['location_slug'])
            ? $baseUrl . htmlspecialchars($p['location_slug']) . '/' . htmlspecialchars($p['slug'])
            : $baseUrl . 'property/' . htmlspecialchars($p['slug']);
?>
    <url>
        <loc><?= $propUrl ?></loc>
        <lastmod><?= $lastmod ?></lastmod>
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

    <!-- CMS Pages -->
<?php
    try {
        $pages = $pdo->query("SELECT slug, updated_at FROM pages WHERE status='published' AND is_deleted=0 ORDER BY title");
        while ($pg = $pages->fetch()):
?>
    <url>
        <loc><?= $baseUrl ?>page/<?= htmlspecialchars($pg['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($pg['updated_at'])) ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
<?php
        endwhile;
    } catch(Exception $e) {}
?>
</urlset>
