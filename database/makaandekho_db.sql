-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2023 at 06:53 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hpj_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `eid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `edate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`eid`, `name`, `email`, `phone`, `subject`, `message`, `edate`) VALUES
(7, 'Robertcam', 'mymail@mymails.con', '87651176616', 'Interesting news: a student from Australia earned $ 30,000,000 in 1.5 months', 'A student from Australia earned $ 30,000,000 in 1.5 months https://telegra.ph/Interesting-news-a-student-from-Australia-earned--30000000-in-15-months-09-22?news-id-218283', '2022-09-22 18:31:52'),
(8, 'Aryan Bajaj', 'ducksndrakesab@gmail.com', '8090547645', 'Requirement of Nylon sportswear fabrics ', 'We are the exporter of horse riding apparels based in Kanpur. ', '2022-09-23 06:47:46'),
(9, 'Aryan Bajaj', 'ducksndrakesab@gmail.com', '8090547645', 'Requirement of Nylon sportswear fabrics ', 'We are the exporter of horse riding apparels based in Kanpur. ', '2022-09-23 06:48:11'),
(10, 'Aryan Bajaj', 'ducksndrakesab@gmail.com', '8090547645', 'Requirement of Nylon sportswear fabrics ', 'We are the exporter of horse riding apparels based in Kanpur. ', '2022-09-23 06:48:12'),
(11, 'Josephmer', 'aldanabocconi@gmail.com', '82385962288', 'OpenSea: Get a lot of NFT right now, details in your account', 'You have a NFT gift in the amount of $50,000, details in your personal account http://how-can-i-get-nft-for-free.lcloud-report.com/news-8706', '2022-09-23 17:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery`
--

CREATE TABLE `tbl_gallery` (
  `photo_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `photo_name` varchar(255) NOT NULL,
  `file_path` varchar(750) NOT NULL,
  `p_category_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_gallery`
--

INSERT INTO `tbl_gallery` (`photo_id`, `title`, `photo_name`, `file_path`, `p_category_id`, `created_at`, `modified_at`) VALUES
(1, 'test', 'img-1-0.png', 'assets/uploads/product_photo/', 4, '2022-09-24 22:29:17', '2022-09-24 22:29:17'),
(2, 'test', 'img-1-1.png', 'assets/uploads/product_photo/', 4, '2022-09-24 22:29:17', '2022-09-24 22:29:17'),
(3, 'test', 'img-1-2.png', 'assets/uploads/product_photo/', 4, '2022-09-24 22:29:17', '2022-09-24 22:29:17'),
(4, 'test', 'img-1-3.png', 'assets/uploads/product_photo/', 4, '2022-09-24 22:29:17', '2022-09-24 22:29:17'),
(5, 'test', 'img-5-0.png', 'assets/uploads/product_photo/', 2, '2022-09-24 23:04:25', '2022-09-24 23:04:25'),
(6, 'test', 'img-5-1.png', 'assets/uploads/product_photo/', 2, '2022-09-24 23:04:25', '2022-09-24 23:04:25'),
(7, 'test', 'img-5-2.png', 'assets/uploads/product_photo/', 2, '2022-09-24 23:04:25', '2022-09-24 23:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery_list`
--

CREATE TABLE `tbl_gallery_list` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_gallery_list`
--

INSERT INTO `tbl_gallery_list` (`id`, `title`, `file_path`, `photo`, `created_at`) VALUES
(2, 'test', 'assets/uploads/gallaries/', 'gallary-240922025109.png', '2022-09-24 02:51:09'),
(3, 'test', 'assets/uploads/gallaries/', 'gallary-240922025124.png', '2022-09-24 02:51:24'),
(4, 'test', 'assets/uploads/gallaries/', 'gallary-240922025140.png', '2022-09-24 02:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `name`, `value`) VALUES
(1, 'ABOUT_US', 'About Us'),
(2, 'LATEST_NEWS', 'Latest News'),
(3, 'POPULAR_NEWS', 'Popular News'),
(4, 'CONTACT_US', 'Contact Us'),
(5, 'CONTACT_FORM', 'Contact Form'),
(6, 'FULL_NAME', 'Full Name'),
(7, 'EMAIL_ADDRESS', 'Email Address'),
(8, 'PHONE_NUMBER', 'Phone Number'),
(9, 'MESSAGE', 'Message'),
(10, 'SEND_MESSAGE', 'Send Message'),
(11, 'CATEGORY', 'Category'),
(12, 'POSTED_ON', 'Posted on'),
(13, 'READ_MORE', 'Read More'),
(14, 'CATEGORIES', 'Categories'),
(15, 'SEARCH', 'Search'),
(16, 'SEARCH_BY_COLON', 'Search By:'),
(17, 'DATE', 'Date'),
(18, 'SHARE_THIS', 'Share This'),
(19, 'COMMENTS', 'Comments'),
(20, 'ENTER_YOUR_EMAIL', 'Enter Your Email'),
(21, 'SUBMIT', 'Submit'),
(22, 'CATEGORY_COLON', 'Category:'),
(23, 'SERVICE_COLON', 'Service:'),
(24, 'SERVICES', 'Services'),
(26, 'EMAIL_VALID_CHECK', 'Email Address must be valid'),
(27, 'SUBSCRIPTION_SUCCESS_MESSAGE', 'Please check your email and confirm your subscription.'),
(28, 'FULL_NAME_EMPTY_CHECK', 'Name can not be empty'),
(29, 'PHONE_EMPTY_CHECK', 'Phone Number can not be empty'),
(30, 'EMAIL_EMPTY_CHECK', 'Email Address can not be empty'),
(31, 'COMMENT_EMPTY_CHECK', 'Comment can not be empty'),
(33, 'ADDRESS', 'Address'),
(34, 'WEBSITE', 'Website'),
(35, 'ABOUT', 'About'),
(36, 'CONTACT', 'Contact'),
(37, 'SOCIAL_MEDIA_HEADLINE', 'Social Media Activities'),
(38, 'SEE_FULL_PROFILE', 'See Full Profile'),
(39, 'TEAM_MEMBER_COLON', 'Team Member:'),
(40, 'NEWS_EMPTY_CHECK', 'Sorry! No News is found.'),
(41, 'PREVIOUS', 'Previous'),
(42, 'NEXT', 'Next'),
(43, 'EMAIL_EXIST_CHECK', 'Email Address already exists');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_market_area`
--

CREATE TABLE `tbl_market_area` (
  `id` int(11) NOT NULL,
  `area_type` varchar(1000) DEFAULT NULL,
  `name` varchar(755) DEFAULT NULL,
  `slug` varchar(755) DEFAULT NULL,
  `meta_title` varchar(755) DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_market_area`
--

INSERT INTO `tbl_market_area` (`id`, `area_type`, `name`, `slug`, `meta_title`, `meta_keyword`, `meta_description`, `created_at`, `modified_at`) VALUES
(1, 'City', 'Adilabad', 'adilabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(2, 'City', 'Agra', 'agra', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(3, 'City', 'Ahmedabad', 'ahmedabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(4, 'City', 'Ahmednagar', 'ahmednagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(5, 'City', 'Aizawl', 'aizawl', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(6, 'City', 'Ajmer', 'ajmer', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(7, 'City', 'Akola', 'akola', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(8, 'City', 'Alappuzha', 'alappuzha', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(9, 'City', 'Aligarh', 'aligarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(10, 'City', 'Alirajpur', 'alirajpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(11, 'City', 'Allahabad', 'allahabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(12, 'City', 'Almora', 'almora', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(13, 'City', 'Alwar', 'alwar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(14, 'City', 'Ambala', 'ambala', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(15, 'City', 'Ambedkar Nagar', 'ambedkar-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(16, 'City', 'Amrawati', 'amrawati', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(17, 'City', 'Amreli District', 'amreli-district', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(18, 'City', 'Amritsar', 'amritsar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(19, 'City', 'Anand', 'anand', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(20, 'City', 'Anantapur', 'anantapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(21, 'City', 'Anantnag', 'anantnag', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(22, 'City', 'Angul', 'angul', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(23, 'City', 'Anjaw', 'anjaw', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(24, 'City', 'Anuppur', 'anuppur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(25, 'City', 'Araria', 'araria', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(26, 'City', 'Ariyalur', 'ariyalur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(27, 'City', 'Ashok Nagar', 'ashok-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(28, 'City', 'Auraiya', 'auraiya', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(29, 'City', 'Aurangabad', 'aurangabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(30, 'City', 'Aurangabad', 'aurangabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(31, 'City', 'Azamgarh', 'azamgarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(32, 'City', 'Badaun', 'badaun', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(33, 'City', 'Badgam', 'badgam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(34, 'City', 'Bagalkot', 'bagalkot', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(35, 'City', 'Bageshwar', 'bageshwar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(36, 'City', 'Bagpat', 'bagpat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(37, 'City', 'Bahraich', 'bahraich', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(38, 'City', 'Balaghat', 'balaghat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(39, 'City', 'Baleswar', 'baleswar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(40, 'City', 'Ballia', 'ballia', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(41, 'City', 'Balrampur', 'balrampur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(42, 'City', 'Banaskantha', 'banaskantha', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(43, 'City', 'Banda', 'banda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(44, 'City', 'Bandipore', 'bandipore', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(45, 'City', 'Bangalore Rural District', 'bangalore-rural-district', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(46, 'City', 'Bangalore Urban District', 'bangalore-urban-district', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(47, 'City', 'Banka', 'banka', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(48, 'City', 'Bankura', 'bankura', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(49, 'City', 'Banswara', 'banswara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(50, 'City', 'Barabanki', 'barabanki', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(51, 'City', 'Baramula', 'baramula', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(52, 'City', 'Baran', 'baran', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(53, 'City', 'Bardhaman', 'bardhaman', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(54, 'City', 'Bareilly', 'bareilly', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(55, 'City', 'Bargarh', 'bargarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(56, 'City', 'Barmer', 'barmer', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(57, 'City', 'Barpeta', 'barpeta', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(58, 'City', 'Barwani', 'barwani', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(59, 'City', 'Bastar', 'bastar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(60, 'City', 'Basti', 'basti', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(61, 'City', 'Bathinda', 'bathinda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(62, 'City', 'Beed', 'beed', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(63, 'City', 'Begusarai', 'begusarai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(64, 'City', 'Belgaum', 'belgaum', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(65, 'City', 'Bellary', 'bellary', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(66, 'City', 'Betul', 'betul', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(67, 'City', 'Bhadrak', 'bhadrak', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(68, 'City', 'Bhagalpur', 'bhagalpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(69, 'City', 'Bhandara', 'bhandara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(70, 'City', 'Bharatpur', 'bharatpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(71, 'City', 'Bharuch', 'bharuch', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(72, 'City', 'Bhavnagar', 'bhavnagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(73, 'City', 'Bhilwara', 'bhilwara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(74, 'City', 'Bhind', 'bhind', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(75, 'City', 'Bhiwani', 'bhiwani', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(76, 'City', 'Bhojpur', 'bhojpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(77, 'City', 'Bhopal', 'bhopal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(78, 'City', 'Bidar', 'bidar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(79, 'City', 'Bijapur', 'bijapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(80, 'City', 'Bijnor', 'bijnor', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(81, 'City', 'Bikaner', 'bikaner', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(82, 'City', 'Bilaspur', 'bilaspur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(83, 'City', 'Bilaspur', 'bilaspur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(84, 'City', 'Birbhum', 'birbhum', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(85, 'City', 'Bishnupur', 'bishnupur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(86, 'City', 'Bokaro', 'bokaro', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(87, 'City', 'Bolangir', 'bolangir', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(88, 'City', 'Bongaigaon', 'bongaigaon', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(89, 'City', 'Boudh', 'boudh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(90, 'City', 'Bulandshahr', 'bulandshahr', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(91, 'City', 'Buldhana', 'buldhana', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(92, 'City', 'Bundi', 'bundi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(93, 'City', 'Burhanpur', 'burhanpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(94, 'City', 'Buxar', 'buxar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(95, 'City', 'Cachar', 'cachar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(96, 'City', 'Central Delhi', 'central-delhi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(97, 'City', 'Chamarajnagar', 'chamarajnagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(98, 'City', 'Chamba', 'chamba', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(99, 'City', 'Chamoli', 'chamoli', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(100, 'City', 'Champawat', 'champawat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(101, 'City', 'Champhai', 'champhai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(102, 'City', 'Chandauli', 'chandauli', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(103, 'City', 'Chandel', 'chandel', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(104, 'City', 'Chandrapur', 'chandrapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(105, 'City', 'Changlang', 'changlang', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(106, 'City', 'Chatra', 'chatra', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(107, 'City', 'Chennai', 'chennai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(108, 'City', 'Chhatarpur', 'chhatarpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(109, 'City', 'Chhindwara', 'chhindwara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(110, 'City', 'Chikballapur', 'chikballapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(111, 'City', 'Chikmagalur', 'chikmagalur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(112, 'City', 'Chitradurga', 'chitradurga', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(113, 'City', 'Chitrakoot', 'chitrakoot', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(114, 'City', 'Chittoor', 'chittoor', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(115, 'City', 'Chittorgarh', 'chittorgarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(116, 'City', 'Churachandpur', 'churachandpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(117, 'City', 'Churu', 'churu', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(118, 'City', 'Coimbatore', 'coimbatore', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(119, 'City', 'Cooch Behar', 'cooch-behar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(120, 'City', 'Cuddalore', 'cuddalore', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(121, 'City', 'Cuttack', 'cuttack', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(122, 'City', 'Dahod', 'dahod', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(123, 'City', 'Dakshin Dinajpur', 'dakshin-dinajpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(124, 'City', 'Dakshina Kannada', 'dakshina-kannada', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(125, 'City', 'Daman', 'daman', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(126, 'City', 'Damoh', 'damoh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(127, 'City', 'Dantewada', 'dantewada', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(128, 'City', 'Darbhanga', 'darbhanga', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(129, 'City', 'Darjeeling', 'darjeeling', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(130, 'City', 'Darrang', 'darrang', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(131, 'City', 'Datia', 'datia', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(132, 'City', 'Dausa', 'dausa', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(133, 'City', 'Davanagere', 'davanagere', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(134, 'City', 'Debagarh', 'debagarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(135, 'City', 'Dehradun', 'dehradun', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(136, 'City', 'Deoghar', 'deoghar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(137, 'City', 'Deoria', 'deoria', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(138, 'City', 'Dewas', 'dewas', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(139, 'City', 'Dhalai', 'dhalai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(140, 'City', 'Dhamtari', 'dhamtari', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(141, 'City', 'Dhanbad', 'dhanbad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(142, 'City', 'Dhar', 'dhar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(143, 'City', 'Dharmapuri', 'dharmapuri', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(144, 'City', 'Dharwad', 'dharwad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(145, 'City', 'Dhemaji', 'dhemaji', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(146, 'City', 'Dhenkanal', 'dhenkanal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(147, 'City', 'Dholpur', 'dholpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(148, 'City', 'Dhubri', 'dhubri', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(149, 'City', 'Dhule', 'dhule', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(150, 'City', 'Dibang Valley', 'dibang-valley', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(151, 'City', 'Dibrugarh', 'dibrugarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(152, 'City', 'Dimapur', 'dimapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(153, 'City', 'Dindigul', 'dindigul', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(154, 'City', 'Dindori', 'dindori', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(155, 'City', 'Diu', 'diu', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(156, 'City', 'Doda', 'doda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(157, 'City', 'Dumka', 'dumka', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(158, 'City', 'Dungapur', 'dungapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(159, 'City', 'Durg', 'durg', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(160, 'City', 'East Delhi', 'east-delhi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(161, 'City', 'East Garo Hills', 'east-garo-hills', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(162, 'City', 'East Godavari', 'east-godavari', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(163, 'City', 'East Kameng', 'east-kameng', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(164, 'City', 'East Khasi Hills', 'east-khasi-hills', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(165, 'City', 'East Sikkim', 'east-sikkim', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(166, 'City', 'Ernakulam', 'ernakulam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(167, 'City', 'Erode', 'erode', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(168, 'City', 'Etah', 'etah', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(169, 'City', 'Etawah', 'etawah', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(170, 'City', 'Faizabad', 'faizabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(171, 'City', 'Faridabad', 'faridabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(172, 'City', 'Faridkot', 'faridkot', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(173, 'City', 'Farrukhabad', 'farrukhabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(174, 'City', 'Fatehabad', 'fatehabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(175, 'City', 'Fatehgarh Sahib', 'fatehgarh-sahib', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(176, 'City', 'Fatehpur', 'fatehpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(177, 'City', 'Firozabad', 'firozabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(178, 'City', 'Firozpur', 'firozpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(179, 'City', 'Gadag', 'gadag', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(180, 'City', 'Gadchiroli', 'gadchiroli', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(181, 'City', 'Gajapati', 'gajapati', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(182, 'City', 'Gandhinagar', 'gandhinagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(183, 'City', 'Ganganagar', 'ganganagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(184, 'City', 'Ganjam', 'ganjam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(185, 'City', 'Garhwa', 'garhwa', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(186, 'City', 'Gautam Buddha Nagar', 'gautam-buddha-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(187, 'City', 'Gaya', 'gaya', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(188, 'City', 'Ghaziabad', 'ghaziabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(189, 'City', 'Ghazipur', 'ghazipur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(190, 'City', 'Giridih', 'giridih', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(191, 'City', 'Goalpara', 'goalpara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(192, 'City', 'Godda', 'godda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(193, 'City', 'Golaghat', 'golaghat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(194, 'City', 'Gonda', 'gonda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(195, 'City', 'Gondiya', 'gondiya', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(196, 'City', 'Gopalganj', 'gopalganj', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(197, 'City', 'Gorkakhpur', 'gorkakhpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(198, 'City', 'Gulbarga', 'gulbarga', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(199, 'City', 'Gumla', 'gumla', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(200, 'City', 'Guna', 'guna', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(201, 'City', 'Guntur', 'guntur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(202, 'City', 'Gurdaspur', 'gurdaspur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(203, 'City', 'Gurgaon', 'gurgaon', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(204, 'City', 'Gwalior', 'gwalior', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(205, 'City', 'Hailakandi', 'hailakandi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(206, 'City', 'Hamirpur', 'hamirpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(207, 'City', 'Hamirpur', 'hamirpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(208, 'City', 'Hanumangarh', 'hanumangarh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(209, 'City', 'Harda', 'harda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(210, 'City', 'Hardoi', 'hardoi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(211, 'City', 'Haridwar', 'haridwar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(212, 'City', 'Hassan', 'hassan', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(213, 'City', 'Haveri District', 'haveri-district', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(214, 'City', 'Hazaribagh', 'hazaribagh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(215, 'City', 'Hingoli', 'hingoli', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(216, 'City', 'Hissar', 'hissar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(217, 'City', 'Hooghly', 'hooghly', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(218, 'City', 'Hoshangabad', 'hoshangabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(219, 'City', 'Hoshiarpur', 'hoshiarpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(220, 'City', 'Howrah', 'howrah', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(221, 'City', 'Hyderabad', 'hyderabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(222, 'City', 'Idukki', 'idukki', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(223, 'City', 'Imphal East', 'imphal-east', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(224, 'City', 'Imphal West', 'imphal-west', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(225, 'City', 'Indore', 'indore', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(226, 'City', 'Jabalpur', 'jabalpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(227, 'City', 'Jagatsinghpur', 'jagatsinghpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(228, 'City', 'Jaintia Hills', 'jaintia-hills', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(229, 'City', 'Jaipur', 'jaipur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(230, 'City', 'Jaisalmer', 'jaisalmer', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(231, 'City', 'Jajapur', 'jajapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(232, 'City', 'Jalandhar', 'jalandhar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(233, 'City', 'Jalaun', 'jalaun', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(234, 'City', 'Jalgaon', 'jalgaon', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(235, 'City', 'Jalna', 'jalna', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(236, 'City', 'Jalore', 'jalore', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(237, 'City', 'Jalpaiguri', 'jalpaiguri', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(238, 'City', 'Jammu', 'jammu', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(239, 'City', 'Jamnagar', 'jamnagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(240, 'City', 'Jamui', 'jamui', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(241, 'City', 'Janjgir-Champa', 'janjgir-champa', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(242, 'City', 'Jashpur', 'jashpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(243, 'City', 'Jaunpur District', 'jaunpur-district', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(244, 'City', 'Jehanabad', 'jehanabad', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(245, 'City', 'Jhabua', 'jhabua', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(246, 'City', 'Jhajjar', 'jhajjar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(247, 'City', 'Jhalawar', 'jhalawar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(248, 'City', 'Jhansi', 'jhansi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(249, 'City', 'Jharsuguda', 'jharsuguda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(250, 'City', 'Jind', 'jind', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(251, 'City', 'Jodhpur', 'jodhpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(252, 'City', 'Jorhat', 'jorhat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(253, 'City', 'Juhnjhunun', 'juhnjhunun', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(254, 'City', 'Junagadh', 'junagadh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(255, 'City', 'Jyotiba Phule Nagar', 'jyotiba-phule-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(256, 'City', 'Kadapa', 'kadapa', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(257, 'City', 'Kaimur', 'kaimur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(258, 'City', 'Kaithal', 'kaithal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(259, 'City', 'Kalahandi', 'kalahandi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(260, 'City', 'Kanchipuram', 'kanchipuram', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(261, 'City', 'Kandhamal', 'kandhamal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(262, 'City', 'Kangra', 'kangra', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(263, 'City', 'Kanker', 'kanker', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(264, 'City', 'Kannauj', 'kannauj', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(265, 'City', 'Kannur', 'kannur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(266, 'City', 'Kanpur Dehat', 'kanpur-dehat', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(267, 'City', 'Kanpur Nagar', 'kanpur-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(268, 'City', 'Kanshiram Nagar', 'kanshiram-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(269, 'City', 'Kanyakumari', 'kanyakumari', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(270, 'City', 'Kapurthala', 'kapurthala', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(271, 'City', 'Karaikal', 'karaikal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(272, 'City', 'Karauli', 'karauli', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(273, 'City', 'Karbi Anglong', 'karbi-anglong', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(274, 'City', 'Kargil', 'kargil', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(275, 'City', 'Karimganj', 'karimganj', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(276, 'City', 'Karimnagar', 'karimnagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(277, 'City', 'Karnal', 'karnal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(278, 'City', 'Karur', 'karur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(279, 'City', 'Kasaragod', 'kasaragod', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(280, 'City', 'Kathua', 'kathua', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(281, 'City', 'Katihar', 'katihar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(282, 'City', 'Katni', 'katni', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(283, 'City', 'Kaushambi', 'kaushambi', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(284, 'City', 'Kawardha', 'kawardha', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(285, 'City', 'Kendrapara', 'kendrapara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(286, 'City', 'Kendujhar', 'kendujhar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(287, 'City', 'Khagaria', 'khagaria', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(288, 'City', 'Khammam', 'khammam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(289, 'City', 'Khandwa', 'khandwa', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(290, 'City', 'Khargone', 'khargone', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(291, 'City', 'Kheda', 'kheda', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(292, 'City', 'Khordha', 'khordha', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(293, 'City', 'Kinnaur', 'kinnaur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(294, 'City', 'Kishanganj', 'kishanganj', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(295, 'City', 'Kodagu', 'kodagu', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(296, 'City', 'Koderma', 'koderma', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(297, 'City', 'Kohima', 'kohima', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(298, 'City', 'Kokrajhar', 'kokrajhar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(299, 'City', 'Kolar', 'kolar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(300, 'City', 'Kolasib', 'kolasib', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(301, 'City', 'Kolhapur', 'kolhapur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(302, 'City', 'Kolkata', 'kolkata', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(303, 'City', 'Kollam', 'kollam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(304, 'City', 'Koppal', 'koppal', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(305, 'City', 'Koraput', 'koraput', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(306, 'City', 'Korba', 'korba', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(307, 'City', 'Koriya', 'koriya', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(308, 'City', 'Kota', 'kota', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(309, 'City', 'Kottayam', 'kottayam', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(310, 'City', 'Kozhikode', 'kozhikode', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(311, 'City', 'Krishna', 'krishna', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(312, 'City', 'Kulu', 'kulu', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(313, 'City', 'Kupwara', 'kupwara', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(314, 'City', 'Kurnool', 'kurnool', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(315, 'City', 'Kurukshetra', 'kurukshetra', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(316, 'City', 'Kushinagar', 'kushinagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(317, 'City', 'Kutch', 'kutch', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(318, 'City', 'Lahaul and Spiti', 'lahaul-and-spiti', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(319, 'City', 'Lakhimpur', 'lakhimpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(320, 'City', 'Lakhimpur Kheri', 'lakhimpur-kheri', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(321, 'City', 'Lakhisarai', 'lakhisarai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(322, 'City', 'Lalitpur', 'lalitpur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(323, 'City', 'Latur', 'latur', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(324, 'City', 'Lawngtlai', 'lawngtlai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(325, 'City', 'Leh', 'leh', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(326, 'City', 'Lohardaga', 'lohardaga', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(327, 'City', 'Lohit', 'lohit', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(328, 'City', 'Lower Subansiri', 'lower-subansiri', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(329, 'City', 'Lucknow', 'lucknow', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(330, 'City', 'Ludhiana', 'ludhiana', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(331, 'City', 'Lunglei', 'lunglei', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(332, 'City', 'Madhepura', 'madhepura', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(333, 'City', 'Madhubani', 'madhubani', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(334, 'City', 'Madurai', 'madurai', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(335, 'City', 'Mahamaya Nagar', 'mahamaya-nagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(336, 'City', 'Maharajganj', 'maharajganj', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(337, 'City', 'Mahasamund', 'mahasamund', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(338, 'City', 'Mahbubnagar', 'mahbubnagar', NULL, NULL, NULL, '2022-12-15 20:40:04', '2022-12-15 20:40:04'),
(339, 'City', 'Mahe', 'mahe', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(340, 'City', 'Mahendragarh', 'mahendragarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(341, 'City', 'Mahoba', 'mahoba', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(342, 'City', 'Mainpuri', 'mainpuri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(343, 'City', 'Malappuram', 'malappuram', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(344, 'City', 'Malda', 'malda', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(345, 'City', 'Malkangiri', 'malkangiri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(346, 'City', 'Mamit', 'mamit', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(347, 'City', 'Mandi', 'mandi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(348, 'City', 'Mandla', 'mandla', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(349, 'City', 'Mandsaur', 'mandsaur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(350, 'City', 'Mandya', 'mandya', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(351, 'City', 'Mansa', 'mansa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(352, 'City', 'Marigaon', 'marigaon', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(353, 'City', 'Mathura', 'mathura', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(354, 'City', 'Mau', 'mau', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(355, 'City', 'Mayurbhanj', 'mayurbhanj', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(356, 'City', 'Medak', 'medak', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(357, 'City', 'Meerut', 'meerut', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(358, 'City', 'Mehsana', 'mehsana', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(359, 'City', 'Mewat', 'mewat', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(360, 'City', 'Midnapore', 'midnapore', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(361, 'City', 'Mirzapur', 'mirzapur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(362, 'City', 'Moga', 'moga', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(363, 'City', 'Mokokchung', 'mokokchung', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(364, 'City', 'Mon', 'mon', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(365, 'City', 'Moradabad', 'moradabad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(366, 'City', 'Morena', 'morena', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(367, 'City', 'Mukatsar', 'mukatsar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(368, 'City', 'Mumbai City', 'mumbai-city', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(369, 'City', 'Mumbai suburban', 'mumbai-suburban', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(370, 'City', 'Munger', 'munger', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(371, 'City', 'Murshidabad', 'murshidabad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(372, 'City', 'Muzaffarnagar', 'muzaffarnagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(373, 'City', 'Muzaffarpur', 'muzaffarpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(374, 'City', 'Mysore', 'mysore', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(375, 'City', 'Nabarangpur', 'nabarangpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(376, 'City', 'Nadia', 'nadia', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(377, 'City', 'Nagaon', 'nagaon', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(378, 'City', 'Nagapattinam', 'nagapattinam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(379, 'City', 'Nagaur', 'nagaur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(380, 'City', 'Nagpur', 'nagpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(381, 'City', 'Nainital', 'nainital', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(382, 'City', 'Nalanda', 'nalanda', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(383, 'City', 'Nalbari', 'nalbari', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(384, 'City', 'Nalgonda', 'nalgonda', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(385, 'City', 'Namakkal', 'namakkal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(386, 'City', 'Nanded', 'nanded', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(387, 'City', 'Nandurbar', 'nandurbar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(388, 'City', 'Narmada', 'narmada', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(389, 'City', 'Narsinghpur', 'narsinghpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(390, 'City', 'Nashik', 'nashik', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(391, 'City', 'Navsari', 'navsari', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(392, 'City', 'Nawada', 'nawada', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(393, 'City', 'Nawan Shehar', 'nawan-shehar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(394, 'City', 'Nayagarh', 'nayagarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(395, 'City', 'Neemuch', 'neemuch', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(396, 'City', 'Nellore', 'nellore', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(397, 'City', 'New Delhi', 'new-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(398, 'City', 'Nicobar', 'nicobar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(399, 'City', 'Nizamabad', 'nizamabad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(400, 'City', 'North 24 Parganas', 'north-24-parganas', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(401, 'City', 'North and Middle Andaman', 'north-and-middle-andaman', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(402, 'City', 'North Cachar Hills', 'north-cachar-hills', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(403, 'City', 'North Delhi', 'north-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(404, 'City', 'North East Delhi', 'north-east-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(405, 'City', 'North Goa', 'north-goa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(406, 'City', 'North Sikkim', 'north-sikkim', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(407, 'City', 'North Tripura', 'north-tripura', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(408, 'City', 'North West Delhi', 'north-west-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(409, 'City', 'Nuapada', 'nuapada', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(410, 'City', 'Osmanabad', 'osmanabad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(411, 'City', 'Pakur', 'pakur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(412, 'City', 'Palakkad', 'palakkad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(413, 'City', 'Palamu', 'palamu', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(414, 'City', 'Pali', 'pali', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(415, 'City', 'Palwal', 'palwal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(416, 'City', 'Panchkula', 'panchkula', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(417, 'City', 'Panchmahal', 'panchmahal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(418, 'City', 'Panipat', 'panipat', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(419, 'City', 'Panna', 'panna', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(420, 'City', 'Papum Pare', 'papum-pare', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(421, 'City', 'Parbhani', 'parbhani', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(422, 'City', 'Pashchim Champaran', 'pashchim-champaran', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(423, 'City', 'Pashchim Singhbhum', 'pashchim-singhbhum', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(424, 'City', 'Patan', 'patan', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(425, 'City', 'Pathanamthitta', 'pathanamthitta', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(426, 'City', 'Patiala', 'patiala', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(427, 'City', 'Patna', 'patna', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(428, 'City', 'Pauri Garhwal', 'pauri-garhwal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(429, 'City', 'Perambalur', 'perambalur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(430, 'City', 'Phek', 'phek', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(431, 'City', 'Pilibhit', 'pilibhit', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(432, 'City', 'Pithoragharh', 'pithoragharh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(433, 'City', 'Poonch', 'poonch', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(434, 'City', 'Porbandar', 'porbandar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(435, 'City', 'Prakasam', 'prakasam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(436, 'City', 'Pratapgarh', 'pratapgarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(437, 'City', 'Pratapgarh', 'pratapgarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(438, 'City', 'Puducherry', 'puducherry', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(439, 'City', 'Pudukkottai', 'pudukkottai', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(440, 'City', 'Pulwama', 'pulwama', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(441, 'City', 'Pune', 'pune', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(442, 'City', 'Purba Champaran', 'purba-champaran', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(443, 'City', 'Purba Singhbhum', 'purba-singhbhum', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(444, 'City', 'Puri', 'puri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(445, 'City', 'Purnia', 'purnia', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(446, 'City', 'Purulia', 'purulia', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(447, 'City', 'Rae Bareli', 'rae-bareli', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(448, 'City', 'Raichur', 'raichur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(449, 'City', 'Raigad', 'raigad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(450, 'City', 'Raigarh', 'raigarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(451, 'City', 'Raipur', 'raipur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(452, 'City', 'Raisen', 'raisen', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(453, 'City', 'Rajauri', 'rajauri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(454, 'City', 'Rajgarh', 'rajgarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(455, 'City', 'Rajkot', 'rajkot', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(456, 'City', 'Rajnandgaon', 'rajnandgaon', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(457, 'City', 'Rajsamand', 'rajsamand', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(458, 'City', 'Ramanagara', 'ramanagara', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(459, 'City', 'Ramanathapuram', 'ramanathapuram', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(460, 'City', 'Ramgarh', 'ramgarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(461, 'City', 'Rampur', 'rampur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(462, 'City', 'Ranchi', 'ranchi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(463, 'City', 'Rangareddi', 'rangareddi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(464, 'City', 'Ratlam', 'ratlam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(465, 'City', 'Ratnagiri', 'ratnagiri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(466, 'City', 'Rayagada', 'rayagada', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(467, 'City', 'Rewa', 'rewa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(468, 'City', 'Rewari', 'rewari', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(469, 'City', 'Ri-Bhoi', 'ri-bhoi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(470, 'City', 'Rohtak', 'rohtak', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(471, 'City', 'Rohtas', 'rohtas', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(472, 'City', 'Rudraprayag', 'rudraprayag', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(473, 'City', 'Rupnagar', 'rupnagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(474, 'City', 'Sabarkantha', 'sabarkantha', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(475, 'City', 'Sagar', 'sagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(476, 'City', 'Saharanpur', 'saharanpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(477, 'City', 'Saharsa', 'saharsa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(478, 'City', 'Sahibganj', 'sahibganj', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(479, 'City', 'Saiha', 'saiha', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(480, 'City', 'Salem', 'salem', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(481, 'City', 'Samastipur', 'samastipur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(482, 'City', 'Samba', 'samba', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(483, 'City', 'Sambalpur', 'sambalpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(484, 'City', 'Sangli', 'sangli', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(485, 'City', 'Sangrur', 'sangrur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(486, 'City', 'Sant Kabir Nagar', 'sant-kabir-nagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(487, 'City', 'Sant Ravidas Nagar', 'sant-ravidas-nagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(488, 'City', 'Saran', 'saran', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(489, 'City', 'Satara', 'satara', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(490, 'City', 'Satna', 'satna', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(491, 'City', 'Sawai Madhopur', 'sawai-madhopur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(492, 'City', 'Sehore', 'sehore', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05');
INSERT INTO `tbl_market_area` (`id`, `area_type`, `name`, `slug`, `meta_title`, `meta_keyword`, `meta_description`, `created_at`, `modified_at`) VALUES
(493, 'City', 'Senapati', 'senapati', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(494, 'City', 'Seoni', 'seoni', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(495, 'City', 'Seraikela and Kharsawan', 'seraikela-and-kharsawan', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(496, 'City', 'Serchhip', 'serchhip', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(497, 'City', 'Shahdol', 'shahdol', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(498, 'City', 'Shahjahanpur', 'shahjahanpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(499, 'City', 'Shajapur', 'shajapur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(500, 'City', 'Sheikhpura', 'sheikhpura', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(501, 'City', 'Sheohar', 'sheohar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(502, 'City', 'Sheopur', 'sheopur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(503, 'City', 'Shimla', 'shimla', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(504, 'City', 'Shimoga', 'shimoga', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(505, 'City', 'Shivpuri', 'shivpuri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(506, 'City', 'Shravasti', 'shravasti', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(507, 'City', 'Sibsagar', 'sibsagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(508, 'City', 'Siddharthnagar', 'siddharthnagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(509, 'City', 'Sidhi', 'sidhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(510, 'City', 'Sikar', 'sikar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(511, 'City', 'Sindhudurg', 'sindhudurg', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(512, 'City', 'Singrauli', 'singrauli', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(513, 'City', 'Sirmaur', 'sirmaur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(514, 'City', 'Sirohi', 'sirohi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(515, 'City', 'Sirsa', 'sirsa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(516, 'City', 'Sitamarhi', 'sitamarhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(517, 'City', 'Sitapur', 'sitapur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(518, 'City', 'Sivagangai', 'sivagangai', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(519, 'City', 'Siwan', 'siwan', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(520, 'City', 'Solan', 'solan', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(521, 'City', 'Solapur', 'solapur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(522, 'City', 'Sonbhadra', 'sonbhadra', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(523, 'City', 'Sonepat', 'sonepat', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(524, 'City', 'Sonitpur', 'sonitpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(525, 'City', 'South 24 Parganas', 'south-24-parganas', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(526, 'City', 'South Andaman', 'south-andaman', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(527, 'City', 'South Delhi', 'south-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(528, 'City', 'South Garo Hills', 'south-garo-hills', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(529, 'City', 'South Goa', 'south-goa', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(530, 'City', 'South Sikkim', 'south-sikkim', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(531, 'City', 'South Tripura', 'south-tripura', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(532, 'City', 'South West Delhi', 'south-west-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(533, 'City', 'Srikakulam', 'srikakulam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(534, 'City', 'Srinagar', 'srinagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(535, 'City', 'Subarnapur', 'subarnapur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(536, 'City', 'Sultanpur', 'sultanpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(537, 'City', 'Sundargarh', 'sundargarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(538, 'City', 'Supaul', 'supaul', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(539, 'City', 'Surat', 'surat', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(540, 'City', 'Surendranagar', 'surendranagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(541, 'City', 'Surguja', 'surguja', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(542, 'City', 'Tamenglong', 'tamenglong', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(543, 'City', 'Tehri Garhwal', 'tehri-garhwal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(544, 'City', 'Thane', 'thane', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(545, 'City', 'Thanjavur', 'thanjavur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(546, 'City', 'The Dangs', 'the-dangs', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(547, 'City', 'The Nilgiris', 'the-nilgiris', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(548, 'City', 'Theni', 'theni', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(549, 'City', 'Thiruvallur', 'thiruvallur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(550, 'City', 'Thiruvananthapuram', 'thiruvananthapuram', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(551, 'City', 'Thiruvarur', 'thiruvarur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(552, 'City', 'Thoothukudi', 'thoothukudi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(553, 'City', 'Thoubal', 'thoubal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(554, 'City', 'Thrissur', 'thrissur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(555, 'City', 'Tikamgarh', 'tikamgarh', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(556, 'City', 'Tinsukia', 'tinsukia', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(557, 'City', 'Tirap', 'tirap', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(558, 'City', 'Tiruchirappalli', 'tiruchirappalli', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(559, 'City', 'Tirunelveli', 'tirunelveli', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(560, 'City', 'Tiruppur', 'tiruppur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(561, 'City', 'Tiruvannamalai', 'tiruvannamalai', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(562, 'City', 'Tonk', 'tonk', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(563, 'City', 'Tuensang', 'tuensang', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(564, 'City', 'Tumkur', 'tumkur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(565, 'City', 'Udaipur', 'udaipur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(566, 'City', 'Udham Singh Nagar', 'udham-singh-nagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(567, 'City', 'Udhampur', 'udhampur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(568, 'City', 'Udupi', 'udupi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(569, 'City', 'Ujjain', 'ujjain', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(570, 'City', 'Ukhrul', 'ukhrul', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(571, 'City', 'Umaria', 'umaria', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(572, 'City', 'Una', 'una', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(573, 'City', 'Unnao', 'unnao', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(574, 'City', 'Upper Subansiri', 'upper-subansiri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(575, 'City', 'Uttar Dinajpur', 'uttar-dinajpur', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(576, 'City', 'Uttara Kannada', 'uttara-kannada', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(577, 'City', 'Uttarkashi', 'uttarkashi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(578, 'City', 'Vadodara', 'vadodara', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(579, 'City', 'Vaishali', 'vaishali', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(580, 'City', 'Valsad', 'valsad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(581, 'City', 'Varanasi', 'varanasi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(582, 'City', 'Vellore', 'vellore', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(583, 'City', 'Vidisha', 'vidisha', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(584, 'City', 'Villupuram', 'villupuram', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(585, 'City', 'Vishakhapatnam', 'vishakhapatnam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(586, 'City', 'Vizianagaram', 'vizianagaram', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(587, 'City', 'Warangal', 'warangal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(588, 'City', 'Wardha', 'wardha', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(589, 'City', 'Washim', 'washim', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(590, 'City', 'Wayanad', 'wayanad', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(591, 'City', 'West Delhi', 'west-delhi', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(592, 'City', 'West Garo Hills', 'west-garo-hills', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(593, 'City', 'West Godavari', 'west-godavari', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(594, 'City', 'West Kameng', 'west-kameng', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(595, 'City', 'West Khasi Hills', 'west-khasi-hills', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(596, 'City', 'West Sikkim', 'west-sikkim', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(597, 'City', 'West Tripura', 'west-tripura', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(598, 'City', 'Wokha', 'wokha', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(599, 'City', 'Yadagiri', 'yadagiri', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(600, 'City', 'Yamuna Nagar', 'yamuna-nagar', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(601, 'City', 'Yanam', 'yanam', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(602, 'City', 'Yavatmal', 'yavatmal', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05'),
(603, 'City', 'Zunheboto', 'zunheboto', NULL, NULL, NULL, '2022-12-15 20:40:05', '2022-12-15 20:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category`
--

CREATE TABLE `tbl_product_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(755) DEFAULT NULL,
  `slug` varchar(755) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `index_value` varchar(100) DEFAULT NULL,
  `file_path` varchar(755) DEFAULT NULL,
  `photo` varchar(755) DEFAULT NULL,
  `banner` varchar(1000) DEFAULT NULL,
  `meta_title` varchar(755) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modufied_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_category`
--

INSERT INTO `tbl_product_category` (`id`, `name`, `slug`, `short_description`, `index_value`, `file_path`, `photo`, `banner`, `meta_title`, `meta_description`, `meta_keyword`, `created_at`, `modufied_at`) VALUES
(1, 'Jeggings', 'jeggings', '', '1', 'assets/uploads/product/', 'productCat-1.png', 'productCatBanner-1.jpg', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', '2022-09-20 17:47:15', '2022-09-20 17:47:15'),
(3, 'Pants', 'pants', '', '2', 'assets/uploads/product/', 'productCat-3.png', 'productCatBanner-3.jpg', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', 'Jeggings Fabric Manufacturer & Supplier in [{area}]', '2022-09-20 21:58:42', '2022-09-20 21:58:42'),
(4, 'Sports Bra', 'sports-bra', '', '3', 'assets/uploads/product/', 'productCat-4.png', 'productCatBanner-4.jpg', 'Sports Bra in [{area}]', 'Sports Bra in [{area}]', 'Sports Bra in [{area}]', '2022-09-21 15:37:09', '2022-09-21 15:37:09'),
(5, 'Gymwear', 'gymwear', '', '4', 'assets/uploads/product/', 'productCat-5.png', 'productCatBanner-5.jpg', 'Gymwear in [{area}]', 'Gymwear in [{area}]', 'Gymwear in [{area}]', '2022-09-21 15:38:43', '2022-09-21 15:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_list`
--

CREATE TABLE `tbl_product_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` varchar(255) DEFAULT NULL,
  `name` varchar(755) DEFAULT NULL,
  `slug` varchar(755) DEFAULT NULL,
  `show_on_index` int(11) NOT NULL DEFAULT 0,
  `short_description` varchar(755) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(755) DEFAULT NULL,
  `photo` varchar(755) DEFAULT NULL,
  `banner1` varchar(500) DEFAULT NULL,
  `meta_title` varchar(755) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_list`
--

INSERT INTO `tbl_product_list` (`id`, `cat_id`, `name`, `slug`, `show_on_index`, `short_description`, `description`, `file_path`, `photo`, `banner1`, `meta_title`, `meta_description`, `meta_keyword`, `created_at`, `modified_at`) VALUES
(2, '1,3', 'Product', 'product', 1, 'A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in Delhi, this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.', '<p class=\"Default\" style=\"outline: none; -webkit-font-smoothing: antialiased; color: var(--paragraph-color); hyphens: auto; font-size: 16px; font-family: Muli, sans-serif;\"><span style=\"outline: none; -webkit-font-smoothing: antialiased; font-size: 11.5pt;\">A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in Delhi, this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.</span></p><div><span style=\"outline: none; -webkit-font-smoothing: antialiased; font-size: 11.5pt;\"><br></span></div>', 'assets/uploads/product-list/', 'display-2.png', 'banner1-2.jpg', '', '', '', '2022-09-20 22:02:18', '2022-09-20 22:02:18'),
(3, '5,1,3,4', 'test product', 'test-product', 1, 'A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in [{area}], this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.', '<p>A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in [{area}], this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.<br></p>', 'assets/uploads/product-list/', 'display-3.png', 'banner1-3.jpg', '', '', '', '2022-09-20 23:10:40', '2022-09-20 23:10:40'),
(4, '5,1,3,4', 'Test Product 3', 'test-product-3', 1, 'A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in [{area}], this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.', '<p>A premium award for any occasion, the Jeggings Fabric is crafted with sophistication and bears the hallmark of quality and rarity. Exclusively crafted by one of the leading trophy manufacturers in [{area}], this Jeggings Fabric comes with enchanting designs within the slab and oozes a rich and premium feel. Customizable for a range of options and designs, all engraving takes place with the help of special laser that creates specific patterns using high power laser beams pointed at precision points within the crystal structure. The product is highly durable as the laser engraving is within the crystal, ensuring perfect memories and pleasing visuals.<br></p>', 'assets/uploads/product-list/', 'display-4.png', '', '', '', '', '2022-09-24 01:03:54', '2022-09-24 01:03:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `application_name` varchar(100) DEFAULT NULL,
  `logo` varchar(100) NOT NULL,
  `footer_logo` varchar(100) NOT NULL,
  `favicon` varchar(100) NOT NULL,
  `header_banner` varchar(50) DEFAULT NULL,
  `footer_banner` varchar(50) DEFAULT NULL,
  `cse_result_year` varchar(10) DEFAULT NULL,
  `footer_about` text NOT NULL,
  `footer_copyright` text NOT NULL,
  `contact_address` text NOT NULL,
  `contact_address2` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(100) NOT NULL,
  `contact_phone2` varchar(100) NOT NULL,
  `contact_phone3` varchar(100) DEFAULT NULL,
  `contact_fax` varchar(50) NOT NULL,
  `contact_map_iframe` text NOT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `youtube` varchar(100) DEFAULT NULL,
  `pintrest` varchar(100) DEFAULT NULL,
  `receive_email` varchar(100) NOT NULL,
  `receive_email_subject` varchar(100) NOT NULL,
  `receive_email_thank_you_message` text NOT NULL,
  `total_recent_news_footer` int(10) NOT NULL,
  `total_popular_news_footer` int(10) NOT NULL,
  `total_recent_news_sidebar` int(11) NOT NULL,
  `total_popular_news_sidebar` int(11) NOT NULL,
  `total_recent_news_home_page` int(11) NOT NULL,
  `meta_title_home` text NOT NULL,
  `meta_keyword_home` text NOT NULL,
  `meta_description_home` text NOT NULL,
  `home_title_service` varchar(50) NOT NULL,
  `home_subtitle_service` varchar(50) NOT NULL,
  `home_status_service` varchar(10) NOT NULL,
  `home_title_team_member` varchar(50) NOT NULL,
  `home_subtitle_team_member` varchar(50) NOT NULL,
  `home_status_team_member` varchar(10) NOT NULL,
  `home_title_testimonial` varchar(50) NOT NULL,
  `home_subtitle_testimonial` varchar(50) NOT NULL,
  `home_status_testimonial` varchar(10) NOT NULL,
  `home_photo_testimonial` varchar(50) NOT NULL,
  `home_title_news` varchar(50) NOT NULL,
  `home_subtitle_news` varchar(50) NOT NULL,
  `home_status_news` varchar(10) NOT NULL,
  `home_title_partner` varchar(255) NOT NULL,
  `home_subtitle_partner` varchar(255) NOT NULL,
  `home_status_partner` varchar(10) NOT NULL,
  `mod_rewrite` varchar(10) NOT NULL,
  `newsletter_title` varchar(255) NOT NULL,
  `newsletter_text` text NOT NULL,
  `newsletter_photo` varchar(255) NOT NULL,
  `newsletter_status` varchar(10) NOT NULL,
  `banner_search` varchar(255) NOT NULL,
  `banner_category` varchar(255) NOT NULL,
  `counter_1_title` varchar(255) NOT NULL,
  `counter_1_value` varchar(10) NOT NULL,
  `counter_2_title` varchar(255) NOT NULL,
  `counter_2_value` varchar(10) NOT NULL,
  `counter_3_title` varchar(255) NOT NULL,
  `counter_3_value` varchar(10) NOT NULL,
  `counter_4_title` varchar(255) NOT NULL,
  `counter_4_value` varchar(10) NOT NULL,
  `counter_photo` varchar(255) NOT NULL,
  `counter_status` varchar(10) NOT NULL,
  `color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `application_name`, `logo`, `footer_logo`, `favicon`, `header_banner`, `footer_banner`, `cse_result_year`, `footer_about`, `footer_copyright`, `contact_address`, `contact_address2`, `contact_email`, `contact_phone`, `contact_phone2`, `contact_phone3`, `contact_fax`, `contact_map_iframe`, `facebook`, `twitter`, `linkedin`, `youtube`, `pintrest`, `receive_email`, `receive_email_subject`, `receive_email_thank_you_message`, `total_recent_news_footer`, `total_popular_news_footer`, `total_recent_news_sidebar`, `total_popular_news_sidebar`, `total_recent_news_home_page`, `meta_title_home`, `meta_keyword_home`, `meta_description_home`, `home_title_service`, `home_subtitle_service`, `home_status_service`, `home_title_team_member`, `home_subtitle_team_member`, `home_status_team_member`, `home_title_testimonial`, `home_subtitle_testimonial`, `home_status_testimonial`, `home_photo_testimonial`, `home_title_news`, `home_subtitle_news`, `home_status_news`, `home_title_partner`, `home_subtitle_partner`, `home_status_partner`, `mod_rewrite`, `newsletter_title`, `newsletter_text`, `newsletter_photo`, `newsletter_status`, `banner_search`, `banner_category`, `counter_1_title`, `counter_1_value`, `counter_2_title`, `counter_2_value`, `counter_3_title`, `counter_3_value`, `counter_4_title`, `counter_4_value`, `counter_photo`, `counter_status`, `color`) VALUES
(1, 'HPJ', 'logo-22092022072443.png', 'footer_logo-20092022113701.png', 'favicon-20092022113709.png', 'header-banner-270121081137.jpg', 'footer_banner270121081137.jpg', '2020', '', 'Â© HpJ 2022 all rights reserved', '455/106 khara kalan New Delhi 110082', '', 'info@hpj.co.in', '+91 8586900400', '', '', '123-456-7890', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3497.22816526105!2d77.112735!3d28.772452999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d00c8151ff271%3A0x5912074f713555d3!2sHANUMAN%20PRASAD%20%26%20SONS%20(%20Imported%20Fabrics%20in%20Delhi)!5e0!3m2!1sen!2sin!4v1651384290725!5m2!1sen!2sin\" width=\"100%\" height=\"450\" style=\"border: 0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '#', '#', '#', '#', '#', 'info@yourwebsite.com', '', 'Thank you for sending email. We will contact you shortly.', 3, 3, 4, 4, 7, 'Consultine - Consulting, Business and Finance Website CMS', 'business, insurance, finance, economics, construction, agency, agent, consultant, consultancy, marketing', 'Consultine is a nice and clean responsive website CMS', 'Our Services', 'Check Out All Our Consulting Services', 'Show', 'Team Members', 'Meet with All Our Qualified Team Members', 'Show', 'Testimonial', 'Our Happy Clients Tell About Us', 'Show', 'testimonial.jpg', 'Latest News', 'See All Our Updated and Latest News', 'Show', 'Our Partners', 'All Our Company Partners are Listed Below', 'Show', 'On', 'Newsletter', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid fugit expedita, iure ullam cum vero ex sint aperiam maxime.', 'newsletter.jpg', 'Show', 'banner_search.jpg', 'banner_category.jpg', 'PROJECTS', '150', 'REVIEWS', '300', 'CLIENTS', '250', 'IDEAS', '500', 'counter.png', 'Show', 'D04B2A');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `email`, `password`, `photo`, `role`, `status`) VALUES
(1, 'admin@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'user-1.jpg', 'Admin', 'Active'),
(2, 'ankit@gmail.com', '09bd8fde3d166ea0143d2e37913c5ddc', 'user-1.jpg', 'Admin', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  ADD PRIMARY KEY (`photo_id`);

--
-- Indexes for table `tbl_gallery_list`
--
ALTER TABLE `tbl_gallery_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_market_area`
--
ALTER TABLE `tbl_market_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_list`
--
ALTER TABLE `tbl_product_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_gallery_list`
--
ALTER TABLE `tbl_gallery_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tbl_market_area`
--
ALTER TABLE `tbl_market_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=604;

--
-- AUTO_INCREMENT for table `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_product_list`
--
ALTER TABLE `tbl_product_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
