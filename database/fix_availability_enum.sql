-- Fix: Expand availability enum to match code values
USE `makaan_dekho`;

ALTER TABLE `properties`
  MODIFY COLUMN `availability` ENUM('available','sold','under_construction','new_launch') DEFAULT 'available';
