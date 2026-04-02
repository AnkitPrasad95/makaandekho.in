-- Add trending and recommended columns to properties table
USE `makaan_dekho`;

ALTER TABLE `properties`
  ADD COLUMN `is_trending`    TINYINT(1) NOT NULL DEFAULT 0 AFTER `featured`,
  ADD COLUMN `is_recommended` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_trending`;
