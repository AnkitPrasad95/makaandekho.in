-- Add SEO and favicon columns to settings table
USE `makaan_dekho`;

ALTER TABLE `settings`
  ADD COLUMN `favicon`           VARCHAR(255) DEFAULT NULL AFTER `site_logo`,
  ADD COLUMN `meta_title`        VARCHAR(255) DEFAULT NULL AFTER `favicon`,
  ADD COLUMN `meta_description`  TEXT         DEFAULT NULL AFTER `meta_title`,
  ADD COLUMN `meta_keywords`     TEXT         DEFAULT NULL AFTER `meta_description`;
