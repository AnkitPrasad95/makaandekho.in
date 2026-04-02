-- ============================================================
-- Migration v2: Extended property fields
-- Run in phpMyAdmin on database: makaan_dekho
-- ============================================================
USE `makaan_dekho`;

ALTER TABLE `properties`
  ADD COLUMN `category`          VARCHAR(100)                              DEFAULT NULL        AFTER `property_type`,
  ADD COLUMN `price_type`        ENUM('total','per_sqft','per_month')      DEFAULT 'total'     AFTER `price`,
  ADD COLUMN `country`           VARCHAR(100)                              DEFAULT 'India'     AFTER `address`,
  ADD COLUMN `pincode`           VARCHAR(10)                               DEFAULT NULL        AFTER `country`,
  ADD COLUMN `google_map`        TEXT                                      DEFAULT NULL        AFTER `pincode`,
  ADD COLUMN `floor`             INT(11)                                   DEFAULT NULL        AFTER `area_sqft`,
  ADD COLUMN `total_floors`      INT(11)                                   DEFAULT NULL        AFTER `floor`,
  ADD COLUMN `furnishing`        ENUM('unfurnished','semi-furnished','furnished') DEFAULT NULL AFTER `total_floors`,
  ADD COLUMN `property_age`      VARCHAR(50)                               DEFAULT NULL        AFTER `furnishing`,
  ADD COLUMN `builder_name`      VARCHAR(200)                              DEFAULT NULL        AFTER `property_age`,
  ADD COLUMN `contact_person`    VARCHAR(100)                              DEFAULT NULL        AFTER `builder_name`,
  ADD COLUMN `builder_phone`     VARCHAR(20)                               DEFAULT NULL        AFTER `contact_person`,
  ADD COLUMN `builder_email`     VARCHAR(150)                              DEFAULT NULL        AFTER `builder_phone`,
  ADD COLUMN `featured_image`    VARCHAR(255)                              DEFAULT NULL        AFTER `builder_email`,
  ADD COLUMN `video_url`         VARCHAR(500)                              DEFAULT NULL        AFTER `featured_image`,
  ADD COLUMN `amenities`         TEXT                                      DEFAULT NULL        AFTER `video_url`,
  ADD COLUMN `short_description` TEXT                                      DEFAULT NULL        AFTER `amenities`,
  ADD COLUMN `availability`      ENUM('available','sold')                  DEFAULT 'available' AFTER `short_description`,
  ADD COLUMN `publish_status`    ENUM('draft','published')                 DEFAULT 'draft'     AFTER `availability`,
  ADD COLUMN `meta_title`        VARCHAR(255)                              DEFAULT NULL        AFTER `publish_status`,
  ADD COLUMN `meta_description`  TEXT                                      DEFAULT NULL        AFTER `meta_title`;
