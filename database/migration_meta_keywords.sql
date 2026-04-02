-- Add meta_keywords column to properties table
USE `makaan_dekho`;

ALTER TABLE `properties`
  ADD COLUMN `meta_keywords` TEXT DEFAULT NULL AFTER `meta_description`;
