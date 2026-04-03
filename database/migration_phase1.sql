-- ============================================================
-- Phase 1 Migration: User System, RERA, Documents, Favourites
-- Database: makaan_dekho
-- ============================================================
USE `makaan_dekho`;

-- 1. Update users table: add filer/seller role + extra fields
ALTER TABLE `users`
  MODIFY COLUMN `role` ENUM('owner','agent','builder','filer') DEFAULT 'owner',
  ADD COLUMN `city` VARCHAR(100) DEFAULT NULL AFTER `phone`,
  ADD COLUMN `state` VARCHAR(100) DEFAULT NULL AFTER `city`,
  ADD COLUMN `profile_image` VARCHAR(255) DEFAULT NULL AFTER `state`,
  ADD COLUMN `reset_token` VARCHAR(100) DEFAULT NULL AFTER `password`,
  ADD COLUMN `reset_expires` DATETIME DEFAULT NULL AFTER `reset_token`;

-- 2. Add RERA fields to properties
ALTER TABLE `properties`
  ADD COLUMN `rera_number` VARCHAR(100) DEFAULT NULL AFTER `meta_keywords`,
  ADD COLUMN `registry_number` VARCHAR(100) DEFAULT NULL AFTER `rera_number`,
  ADD COLUMN `is_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `registry_number`,
  ADD COLUMN `verified_at` DATETIME DEFAULT NULL AFTER `is_verified`,
  ADD COLUMN `views` INT NOT NULL DEFAULT 0 AFTER `verified_at`;

-- 3. Property documents table
CREATE TABLE IF NOT EXISTS `property_documents` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `property_id` INT NOT NULL,
  `doc_type` VARCHAR(100) DEFAULT 'registration' COMMENT 'registration, sale_deed, other',
  `file_name` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) DEFAULT NULL,
  `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Favourites table
CREATE TABLE IF NOT EXISTS `favourites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `property_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_property` (`user_id`, `property_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Testimonials table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `designation` VARCHAR(150) DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `content` TEXT NOT NULL,
  `rating` TINYINT DEFAULT 5,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Banner slides table
CREATE TABLE IF NOT EXISTS `banners` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) DEFAULT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `link` VARCHAR(500) DEFAULT '#',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Schedule calls table
CREATE TABLE IF NOT EXISTS `schedule_calls` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `preferred_date` DATE DEFAULT NULL,
  `preferred_time` VARCHAR(50) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `property_id` INT DEFAULT NULL,
  `status` ENUM('new','contacted','completed') DEFAULT 'new',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
