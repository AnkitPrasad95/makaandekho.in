-- Run this once to add 'pending' status to users table
USE `makaan_dekho`;

ALTER TABLE `users`
  MODIFY COLUMN `status` ENUM('pending','active','blocked') DEFAULT 'pending';
