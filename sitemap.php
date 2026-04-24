<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = SITE_URL;
$today   = date('Y-m-d');

// Lastmod from a real file's mtime, falls back to today.
function fileLastmod(string $relPath): string {
    $full = __DIR__ . '/' . ltrim($relPath, '/');
    return is_file($full) ? date('Y-m-d', filemtime($full)) : date('Y-m-d');
}

// Static pages exposed to search engines (auth/dashboard/ajax pages excluded).
// Canonical form matches what the site links to (with .php where applicable).
$staticPages = [
    ['',              'daily',   '1.0'],
    ['about.php',     'monthly', '0.6'],
    ['contact.php',   'monthly', '0.6'],
    ['properties.php','daily',   '0.9'],
    ['blogs.php',     'weekly',  '0.7'],
    ['locations.php', 'weekly',  '0.8'],
    ['privacy.php',   'yearly',  '0.3'],
    ['terms.php',     'yearly',  '0.3'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
<?php foreach ($staticPages as [$path, $freq, $priority]):
    $lastmod = $path === '' ? fileLastmod('index.php') : fileLastmod($path);
?>
    <url>
        <loc><?= $baseUrl . htmlspecialchars($path) ?></loc>
        <lastmod><?= $lastmod ?></lastmod>
        <changefreq><?= $freq ?></changefreq>
        <priority><?= $priority ?></priority>
    </url>
<?php endforeach; ?>

    <!-- Market Areas (Location Pages) -->
<?php
    $locs = $pdo->query("
        SELECT l.slug, MAX(p.created_at) AS last_property
        FROM locations l
        LEFT JOIN properties p
               ON p.location_id = l.id
              AND p.status = 'approved'
              AND p.is_deleted = 0
        WHERE l.is_deleted = 0
          AND l.slug IS NOT NULL
          AND l.slug != ''
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

    <!-- Properties (SEO URL: /location-slug/property-slug, fallback /property/slug) -->
<?php
    $props = $pdo->query("
        SELECT p.slug, p.created_at, p.updated_at, l.slug AS location_slug
        FROM properties p
        LEFT JOIN locations l ON p.location_id = l.id
        WHERE p.status = 'approved'
          AND p.is_deleted = 0
          AND p.slug IS NOT NULL
          AND p.slug != ''
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

    <!-- Blogs (clean URL: /blog/slug, served by blog-detail.php via .htaccess) -->
<?php
    $blogs = $pdo->query("
        SELECT slug, COALESCE(updated_at, created_at) AS lastmod_ts
        FROM blogs
        WHERE status = 'published'
          AND is_deleted = 0
          AND slug IS NOT NULL
          AND slug != ''
        ORDER BY created_at DESC
    ");
    while ($b = $blogs->fetch()):
?>
    <url>
        <loc><?= $baseUrl ?>blog/<?= htmlspecialchars($b['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($b['lastmod_ts'])) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
<?php endwhile; ?>
</urlset>
