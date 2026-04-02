-- ============================================================
-- MakaanDekho Real Estate Admin Panel – Database Schema
-- Database: makaan_dekho
-- ============================================================

CREATE DATABASE IF NOT EXISTS `makaandekho_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `makaandekho_db`;

-- -------------------------------------------------------
-- Admin users
-- -------------------------------------------------------
CREATE TABLE `admin_users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Website users (owners / agents / builders)
-- -------------------------------------------------------
CREATE TABLE `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `phone`      VARCHAR(20)  DEFAULT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('owner','agent','builder') DEFAULT 'owner',
  `status`     ENUM('active','blocked')        DEFAULT 'active',
  `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Locations (City / State / Area)
-- -------------------------------------------------------
CREATE TABLE `locations` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `city`       VARCHAR(100) NOT NULL,
  `state`      VARCHAR(100) NOT NULL,
  `area`       VARCHAR(100) DEFAULT NULL,
  `slug`       VARCHAR(255) NOT NULL,
  `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Properties
-- -------------------------------------------------------
CREATE TABLE `properties` (
  `id`               INT(11)        NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255)   NOT NULL,
  `slug`             VARCHAR(255)   NOT NULL,
  `description`      TEXT           DEFAULT NULL,
  `price`            DECIMAL(15,2)  DEFAULT NULL,
  `property_type`    ENUM('apartment','villa','plot','commercial','office') DEFAULT 'apartment',
  `listing_type`     ENUM('sale','rent') DEFAULT 'sale',
  `bedrooms`         INT(11)        DEFAULT NULL,
  `bathrooms`        INT(11)        DEFAULT NULL,
  `area_sqft`        DECIMAL(10,2)  DEFAULT NULL,
  `address`          TEXT           DEFAULT NULL,
  `location_id`      INT(11)        DEFAULT NULL,
  `user_id`          INT(11)        DEFAULT NULL,
  `status`           ENUM('pending','approved','rejected') DEFAULT 'pending',
  `rejection_reason` TEXT           DEFAULT NULL,
  `featured`         TINYINT(1)     DEFAULT 0,
  `created_at`       DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `location_id` (`location_id`),
  KEY `user_id`     (`user_id`),
  KEY `status`      (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Property images
-- -------------------------------------------------------
CREATE TABLE `property_images` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `property_id` INT(11)      NOT NULL,
  `image`       VARCHAR(255) NOT NULL,
  `is_primary`  TINYINT(1)   DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Enquiries / Leads
-- -------------------------------------------------------
CREATE TABLE `enquiries` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `property_id` INT(11)      DEFAULT NULL,
  `name`        VARCHAR(100) NOT NULL,
  `email`       VARCHAR(150) NOT NULL,
  `phone`       VARCHAR(20)  DEFAULT NULL,
  `message`     TEXT         DEFAULT NULL,
  `status`      ENUM('new','read','replied') DEFAULT 'new',
  `created_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `status`      (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- CMS Pages
-- -------------------------------------------------------
CREATE TABLE `cms_pages` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `page_name`        VARCHAR(100) NOT NULL,
  `page_slug`        VARCHAR(100) NOT NULL,
  `content`          LONGTEXT     DEFAULT NULL,
  `meta_title`       VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT         DEFAULT NULL,
  `meta_keywords`    TEXT         DEFAULT NULL,
  `updated_at`       DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Site settings
-- -------------------------------------------------------
CREATE TABLE `settings` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `site_name`       VARCHAR(200) DEFAULT 'MakaanDekho',
  `site_logo`       VARCHAR(255) DEFAULT NULL,
  `whatsapp_number` VARCHAR(20)  DEFAULT NULL,
  `email`           VARCHAR(150) DEFAULT NULL,
  `smtp_host`       VARCHAR(255) DEFAULT NULL,
  `smtp_user`       VARCHAR(255) DEFAULT NULL,
  `smtp_pass`       VARCHAR(255) DEFAULT NULL,
  `smtp_port`       INT(11)      DEFAULT 587,
  `address`         TEXT         DEFAULT NULL,
  `footer_text`     TEXT         DEFAULT NULL,
  `updated_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Seed data
-- -------------------------------------------------------

-- Default settings row
INSERT INTO `settings` (`site_name`, `email`, `whatsapp_number`, `footer_text`) VALUES
('MakaanDekho', 'info@makaandekho.in', '9999999999', '© 2024 MakaanDekho. All rights reserved.');

-- Default CMS pages
INSERT INTO `cms_pages` (`page_name`, `page_slug`, `meta_title`, `meta_description`) VALUES
('Home',       'home',    'MakaanDekho – Find Your Dream Home',  'Search properties for sale and rent across India.'),
('About Us',   'about',   'About MakaanDekho',                   'Learn about MakaanDekho and our mission.'),
('Contact Us', 'contact', 'Contact MakaanDekho',                 'Get in touch with us for any property enquiries.');

-- Sample locations
INSERT INTO `locations` (`city`, `state`, `area`, `slug`) VALUES
('Delhi',    'Delhi',     'Connaught Place', 'delhi-connaught-place'),
('Mumbai',   'Maharashtra', 'Bandra',        'mumbai-bandra'),
('Gurgaon',  'Haryana',   'Sector 56',       'gurgaon-sector-56'),
('Bangalore','Karnataka', 'Koramangala',     'bangalore-koramangala'),
('Pune',     'Maharashtra','Wakad',          'pune-wakad');

-- NOTE: Admin user is created by setup.php
-- Default credentials after running setup.php:
--   Email:    admin@makaandekho.in
--   Password: Admin@123
