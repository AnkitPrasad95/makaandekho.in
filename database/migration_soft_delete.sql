-- ============================================================
-- Migration: Add Soft Delete to ALL tables
-- Database: makaan_dekho
-- Adds is_deleted + deleted_at to every table
-- ============================================================
USE `makaan_dekho`;

ALTER TABLE `properties`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `meta_description`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `users`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `blogs`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `updated_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `enquiries`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `locations`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `testimonials`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `banners`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `mega_menu_items`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `property_images`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_primary`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `property_documents`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `uploaded_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `favourites`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `schedule_calls`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `cms_pages`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `updated_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;

ALTER TABLE `admin_users`
  ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`,
  ADD COLUMN `deleted_at` DATETIME DEFAULT NULL AFTER `is_deleted`;
