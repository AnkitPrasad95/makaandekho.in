-- =====================================================================
-- Migration: Seed cms_pages rows for static pages so admin can edit
--            their meta_title / meta_description / meta_keywords from
--            /admin/pages.php.
--
-- Required by: terms.php, privacy.php, locations.php, contact.php,
--              about.php (now read SEO from cms_pages with fallback to
--              settings.meta_* and then hardcoded defaults).
--
-- Safe to re-run: uses INSERT IGNORE on the unique page_slug column.
-- =====================================================================
USE `makaan_dekho`;

INSERT IGNORE INTO `cms_pages` (`page_slug`, `page_name`) VALUES
  ('terms',     'Terms of Use'),
  ('privacy',   'Privacy Policy'),
  ('locations', 'All Locations');
