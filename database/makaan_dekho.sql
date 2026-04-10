-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 10:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `makaan_dekho`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `password`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Super Admin', 'admin@makaandekho.in', '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', '2026-03-27 17:33:34', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(500) DEFAULT '#',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `is_active`, `sort_order`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Find Your Dream Home', 'Discover verified properties across India', 'banner_default_1.jpg', '#', 1, 1, '2026-04-03 15:43:07', 0, NULL),
(2, 'Verified Properties Only', 'Every listing is RERA verified', 'banner_default_2.jpg', '#', 1, 2, '2026-04-03 15:43:07', 0, NULL),
(3, 'List Your Property Free', 'Reach thousands of genuine buyers', 'banner_default_3.jpg', '#', 1, 3, '2026-04-03 15:43:07', 0, NULL),
(4, 'test Banner', 'Banner', 'banner_1775324855_5052.jpg', '', 1, 4, '2026-04-04 23:17:35', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `category`, `featured_image`, `short_description`, `content`, `author_name`, `tags`, `status`, `meta_title`, `meta_description`, `meta_keywords`, `views`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Test Blog', 'test-blog', 'Real Estate', 'blog_1774780351_237.webp', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '<h2 style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'Ankit', 'property', 'published', 'Test Blog', 'Test Blog', 'Test Blog', 3, '2026-03-29 16:02:31', '2026-03-29 16:02:53', 0, NULL),
(2, 'Top 10 Tips for First-Time Home Buyers', 'top-10-tips-home-buyers', 'Tips & Guides', NULL, 'Essential tips every first-time buyer should know.', '<h2>1. Fix Your Budget</h2><p>EMI should not exceed 40% of income.</p><h2>2. Check RERA</h2><p>Always verify on state RERA portal.</p><h2>3. Location Matters</h2><p>Proximity to work, schools, transport is key.</p><h2>4. Visit Personally</h2><p>Never buy on photos alone.</p><h2>5. Know All Costs</h2><p>Registration, stamp duty, GST, maintenance add up.</p>', 'MakaanDekho Team', 'home buying,tips,first time', 'published', 'Tips for First-Time Buyers', 'Home buying tips for first timers.', NULL, 343, '2026-03-25 10:00:00', NULL, 0, NULL),
(3, 'RERA: Why It Matters for Buyers', 'rera-why-it-matters', 'Legal', NULL, 'Understanding RERA and how it protects you.', '<h2>What is RERA?</h2><p>Real Estate Regulation Act 2016 protects buyers.</p><h2>Benefits</h2><ul><li>Timely delivery</li><li>No false promises</li><li>Carpet area pricing</li><li>5-year defect liability</li></ul><p>Every MakaanDekho listing is RERA verified.</p>', 'Ankit Prasad', 'RERA,legal,verification', 'published', 'RERA Guide', 'RERA Act explained for buyers.', NULL, 570, '2026-03-27 14:00:00', NULL, 0, NULL),
(4, 'Best Localities in Delhi-NCR 2026', 'best-localities-delhi-ncr', 'Real Estate', NULL, 'Top investment areas from Gurgaon to Noida.', '<h2>1. Sector 150 Noida</h2><p>Near Jewar Airport. 30-40% growth expected.</p><h2>2. Dwarka Expressway</h2><p>Premium projects from 80L.</p><h2>3. Indirapuram</h2><p>Developed with metro connectivity.</p><h2>4. Knowledge Park</h2><p>Affordable with rental yield.</p>', 'MakaanDekho Team', 'Delhi NCR,investment,localities', 'published', 'Best Delhi-NCR Areas', 'Top investment localities Delhi-NCR.', NULL, 894, '2026-03-30 11:00:00', NULL, 0, NULL),
(5, 'Home Loan EMI Calculator Guide', 'emi-calculator-guide', 'Investment', NULL, 'Calculate EMI and plan your budget.', '<h2>EMI Formula</h2><p>EMI = P x r x (1+r)^n / ((1+r)^n - 1)</p><h2>Tips</h2><ul><li>Larger down payment</li><li>Compare bank rates</li><li>750+ credit score</li></ul><p>Use MakaanDekho EMI calculator on every property page!</p>', 'Ankit Prasad', 'EMI,home loan,finance', 'published', 'EMI Guide', 'How to calculate EMI.', NULL, 237, '2026-04-01 09:00:00', NULL, 0, NULL),
(6, 'Interior Design Trends 2026', 'interior-trends-2026', 'Lifestyle', NULL, 'Minimalist to smart home trends.', '<h2>1. Minimalist Luxury</h2><p>Clean lines, neutral colors.</p><h2>2. Smart Homes</h2><p>Voice control, automation.</p><h2>3. Biophilic Design</h2><p>Indoor plants, natural materials.</p><h2>4. WFH Spaces</h2><p>Flexible multipurpose rooms.</p>', 'MakaanDekho Team', 'interior,design,trends', 'published', 'Interior Trends 2026', 'Design trends for Indian homes.', NULL, 160, '2026-04-02 15:00:00', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `page_slug` varchar(100) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `page_name`, `page_slug`, `content`, `meta_title`, `meta_description`, `meta_keywords`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Home', 'home', '<h2 style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 'MakaanDekho – Find Your Dream Home', 'Search properties for sale and rent across India.', '', '2026-03-29 16:04:46', 0, NULL),
(2, 'About Us', 'about', '<h2>Welcome to MakaanDekho</h2>\r\n<p>India\'s trusted real estate portal connecting buyers, sellers, owners, agents, and builders. Every property is RERA verified.</p>\r\n<h3>Why Choose Us?</h3>\r\n<ul>\r\n<li><strong>RERA Verified</strong> — Mandatory verification for all listings</li>\r\n<li><strong>Multi-Role</strong> — Owners, Agents, Builders, Filers can list</li>\r\n<li><strong>Free to List</strong> — Post properties at no cost</li>\r\n<li><strong>EMI Calculator</strong> — Budget planning on every page</li>\r\n</ul>', 'About MakaanDekho', 'Learn about MakaanDekho and our mission.', NULL, '2026-04-03 15:43:07', 0, NULL),
(3, 'Contact Us', 'contact', NULL, 'Contact MakaanDekho', 'Get in touch with us for any property enquiries.', NULL, '2026-03-27 17:29:30', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `property_id`, `name`, `email`, `phone`, `message`, `status`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'Ravi Kumar', 'ravi@email.com', '9898989898', 'Interested in Panchsheel Prime. Share floor plan?', 'read', '2026-04-01 09:30:00', 0, NULL),
(2, 1, 'Anita Singh', 'anita@email.com', '9797979797', 'Any offers? Want to visit this weekend.', 'read', '2026-04-01 14:15:00', 0, NULL),
(3, 1, 'Deepika S', 'deepika@email.com', '9595959595', 'Share brochure and price list for investment.', 'read', '2026-04-02 11:30:00', 0, NULL),
(4, 1, 'Sanjay G', 'sanjay@email.com', '9494949494', 'Is home loan available? Please call me.', 'replied', '2026-04-02 16:00:00', 0, NULL),
(5, NULL, 'General Visitor', 'visitor@email.com', '9090909090', 'Want to know more about your services.', 'read', '2026-04-03 11:00:00', 0, NULL),
(6, 1, 'Arun T', 'arun@email.com', '9189189189', 'Visiting next week. Please arrange site visit.', 'read', '2026-04-03 12:00:00', 0, NULL),
(9, 1, 'TestEnq', 'enq@test.com', '9876543210', 'interested', 'read', '2026-04-04 21:24:09', 0, NULL),
(10, NULL, 'TestContact', 'ct@test.com', '9876543210', '[Test] This is a test message for contact', 'read', '2026-04-04 21:24:10', 0, NULL),
(11, 1, 'TestEnq', 'enq1775325583@t.com', '9876543210', 'test', 'read', '2026-04-04 23:29:43', 0, NULL),
(12, NULL, 'Test', 't@t.com', '9876543210', '[Hi] This is a test message contact', 'read', '2026-04-04 23:29:44', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`id`, `user_id`, `property_id`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 1, '2026-04-03 15:43:07', 1, '2026-04-04 14:11:18'),
(2, 4, 1, '2026-04-03 15:43:07', 0, NULL),
(3, 5, 1, '2026-04-03 15:43:07', 0, NULL),
(4, 8, 1, '2026-04-03 15:43:07', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `city`, `state`, `area`, `slug`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Delhi', 'Delhi', 'Connaught Place', 'delhi-connaught-place', '2026-03-27 17:29:30', 0, NULL),
(2, 'Mumbai', 'Maharashtra', 'Bandra', 'mumbai-bandra', '2026-03-27 17:29:30', 0, NULL),
(3, 'Gurgaon', 'Haryana', 'Sector 56', 'gurgaon-sector-56', '2026-03-27 17:29:30', 0, NULL),
(4, 'Bangalore', 'Karnataka', 'Koramangala', 'bangalore-koramangala', '2026-03-27 17:29:30', 0, NULL),
(5, 'Pune', 'Maharashtra', 'Wakad', 'pune-wakad', '2026-03-27 17:29:30', 0, NULL),
(6, 'Patna', 'Bihar', 'Dang Bangla', 'patna-dang-bangla', '2026-03-27 17:56:51', 0, NULL),
(7, 'Patna', 'Bihar', 'Patna', 'patna-patna-2', '2026-03-27 17:58:03', 0, NULL),
(8, 'Noida', 'Uttar Pradesh', 'Sector 150', 'noida-sector-150', '2026-03-20 10:00:00', 0, NULL),
(9, 'Noida', 'Uttar Pradesh', 'Sector 62', 'noida-sector-62', '2026-03-20 10:01:00', 0, NULL),
(10, 'Greater Noida', 'Uttar Pradesh', 'Knowledge Park', 'greater-noida-knowledge-park', '2026-03-20 10:02:00', 0, NULL),
(11, 'Ghaziabad', 'Uttar Pradesh', 'Indirapuram', 'ghaziabad-indirapuram', '2026-03-20 10:03:00', 0, NULL),
(12, 'Ghaziabad', 'Uttar Pradesh', 'Vaishali', 'ghaziabad-vaishali', '2026-03-20 10:04:00', 0, NULL),
(13, 'Lucknow', 'Uttar Pradesh', 'Gomti Nagar', 'lucknow-gomti-nagar', '2026-03-20 10:05:00', 0, NULL),
(14, 'Hyderabad', 'Telangana', 'Hitech City', 'hyderabad-hitech-city', '2026-03-20 10:06:00', 0, NULL),
(15, 'Chennai', 'Tamil Nadu', 'Anna Nagar', 'chennai-anna-nagar', '2026-03-20 10:07:00', 0, NULL),
(16, 'Jaipur', 'Rajasthan', 'Malviya Nagar', 'jaipur-malviya-nagar', '2026-03-20 10:08:00', 0, NULL),
(17, 'Kolkata', 'West Bengal', 'Salt Lake', 'kolkata-salt-lake', '2026-03-20 10:09:00', 0, NULL),
(18, 'Nautan', 'Bihar', NULL, 'nautan-bihar', '2026-04-04 18:14:50', 0, NULL),
(19, 'Noida', 'UP', NULL, 'noida-up', '2026-04-04 20:49:55', 0, NULL),
(20, 'TestFlowCity', 'Uttar Pradesh', NULL, 'testflowcity-uttar-pradesh', '2026-04-04 20:51:27', 0, NULL),
(21, 'TestFlowCity', 'UP', NULL, 'testflowcity-up', '2026-04-04 20:51:28', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mega_menu_items`
--

CREATE TABLE `mega_menu_items` (
  `id` int(11) NOT NULL,
  `menu_slug` varchar(50) NOT NULL COMMENT 'for_buyers, for_owners, insights, builders_agents',
  `column_heading` varchar(100) NOT NULL COMMENT 'Column header e.g. RESIDENTIAL',
  `item_title` varchar(150) NOT NULL,
  `item_url` varchar(500) DEFAULT '#',
  `column_order` int(11) NOT NULL DEFAULT 1 COMMENT 'Order of the column within menu',
  `item_order` int(11) NOT NULL DEFAULT 1 COMMENT 'Order of item within column',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mega_menu_items`
--

INSERT INTO `mega_menu_items` (`id`, `menu_slug`, `column_heading`, `item_title`, `item_url`, `column_order`, `item_order`, `is_active`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'for_buyers', 'RESIDENTIAL', 'Studio Apartments', 'properties.php?type=apartment&q=studio', 1, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(2, 'for_buyers', 'RESIDENTIAL', 'Flats in Greater Noida', 'properties.php?type=apartment&city=Greater+Noida', 1, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(3, 'for_buyers', 'RESIDENTIAL', 'Independent Houses', 'properties.php?type=villa&q=independent', 1, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(4, 'for_buyers', 'RESIDENTIAL', 'Villas & Penthouses', 'properties.php?type=villa', 1, 4, 1, '2026-04-03 14:10:41', 0, NULL),
(5, 'for_buyers', 'RESIDENTIAL', 'Builder Floors', 'properties.php?type=apartment&q=builder+floor', 1, 5, 1, '2026-04-03 14:10:41', 0, NULL),
(6, 'for_buyers', 'RESIDENTIAL', 'Farm Houses', 'properties.php?type=villa&q=farm+house', 1, 6, 1, '2026-04-03 14:10:41', 0, NULL),
(7, 'for_buyers', 'COMMERCIAL', 'Office Spaces', 'properties.php?type=office', 2, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(8, 'for_buyers', 'COMMERCIAL', 'Retail Shops', 'properties.php?type=commercial&q=shop', 2, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(9, 'for_buyers', 'COMMERCIAL', 'Showrooms', 'properties.php?type=commercial&q=showroom', 2, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(10, 'for_buyers', 'COMMERCIAL', 'Coworking Spaces', 'properties.php?type=office&q=coworking', 2, 4, 1, '2026-04-03 14:10:41', 0, NULL),
(11, 'for_buyers', 'COMMERCIAL', 'Warehouses', 'properties.php?type=commercial&q=warehouse', 2, 5, 1, '2026-04-03 14:10:41', 0, NULL),
(12, 'for_buyers', 'COMMERCIAL', 'Industrial Buildings', 'properties.php?type=commercial&q=industrial', 2, 6, 1, '2026-04-03 14:10:41', 0, NULL),
(13, 'for_buyers', 'PLOTS & LAND', 'Industrial Plots', 'properties.php?type=plot&q=industrial', 3, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(14, 'for_buyers', 'PLOTS & LAND', 'Warehouse Land', 'properties.php?type=plot&q=warehouse', 3, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(15, 'for_buyers', 'PLOTS & LAND', 'Factory Land', 'properties.php?type=plot&q=factory', 3, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(16, 'for_buyers', 'PLOTS & LAND', 'Villa Plots', 'properties.php?type=plot&q=villa', 3, 4, 1, '2026-04-03 14:10:41', 0, NULL),
(17, 'for_buyers', 'PLOTS & LAND', 'Corner Plots', 'properties.php?type=plot&q=corner', 3, 5, 1, '2026-04-03 14:10:41', 0, NULL),
(18, 'for_buyers', 'PLOTS & LAND', 'Authority Approved', 'properties.php?type=plot&q=approved', 3, 6, 1, '2026-04-03 14:10:41', 0, NULL),
(19, 'for_owners', 'POST & SELL', 'Post Property Free', '?register=1', 1, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(20, 'for_owners', 'POST & SELL', 'Premium Seller Ad', 'contact.php', 1, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(21, 'for_owners', 'POST & SELL', 'Seller Packages', 'contact.php', 1, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(22, 'for_owners', 'FINANCIAL SERVICES', 'Home Loan Leads', 'contact.php', 2, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(23, 'for_owners', 'FINANCIAL SERVICES', 'EMI Calculator', 'properties.php', 2, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(24, 'for_owners', 'FINANCIAL SERVICES', 'Tax Benefits', 'blogs.php?q=tax', 2, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(25, 'for_owners', 'LEGAL & SUPPORT', 'Property Valuation', 'contact.php', 3, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(26, 'for_owners', 'LEGAL & SUPPORT', 'Legal Assistance', 'contact.php', 3, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(27, 'for_owners', 'LEGAL & SUPPORT', 'Tenant Verification', 'contact.php', 3, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(28, 'insights', 'MARKET & NEWS', 'Real Estate News', 'blogs.php?category=Real+Estate', 1, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(29, 'insights', 'MARKET & NEWS', 'Market Trends', 'blogs.php?category=Investment', 1, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(30, 'insights', 'MARKET & NEWS', 'Latest Articles', 'blogs.php', 1, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(31, 'insights', 'EXPERT GUIDES', 'Buyer Guides', 'blogs.php?category=Tips+%26+Guides', 2, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(32, 'insights', 'EXPERT GUIDES', 'Investment Tips', 'blogs.php?category=Investment', 2, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(33, 'builders_agents', 'FOR BUILDERS', 'Search Builders', 'properties.php?search=builder', 1, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(34, 'builders_agents', 'FOR BUILDERS', 'Advertise Projects', 'contact.php', 1, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(35, 'builders_agents', 'FOR BUILDERS', 'Branding Solutions', 'contact.php', 1, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(36, 'builders_agents', 'FOR AGENTS', 'Search Agents', 'properties.php?search=agent', 2, 1, 1, '2026-04-03 14:10:41', 0, NULL),
(37, 'builders_agents', 'FOR AGENTS', 'Join as Agent', '?register=1', 2, 2, 1, '2026-04-03 14:10:41', 0, NULL),
(38, 'builders_agents', 'FOR AGENTS', 'Premium Packages', 'contact.php', 2, 3, 1, '2026-04-03 14:10:41', 0, NULL),
(39, 'builders_agents', 'FOR AGENTS', 'Get Verified Leads', 'contact.php', 2, 4, 1, '2026-04-03 14:10:41', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`, `is_active`) VALUES
(2, 'nltest1775318049@test.com', '2026-04-04 21:24:09', 1),
(3, 'augmentum@gmail.com', '2026-04-04 23:09:17', 1),
(4, 'ak981993@gmail.com', '2026-04-04 23:13:27', 1),
(5, 'nlv31775325584@test.com', '2026-04-04 23:29:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `availability` enum('available','sold','under_construction','new_launch') DEFAULT 'available',
  `publish_status` enum('draft','published') DEFAULT 'draft',
  `description` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `price_type` varchar(255) DEFAULT NULL,
  `property_type` enum('apartment','villa','plot','commercial','office') DEFAULT 'apartment',
  `category` varchar(100) DEFAULT NULL,
  `listing_type` enum('sale','rent') DEFAULT 'sale',
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `area_sqft` decimal(10,2) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `total_floors` int(11) DEFAULT NULL,
  `furnishing` enum('unfurnished','semi-furnished','furnished') DEFAULT NULL,
  `property_age` varchar(50) DEFAULT NULL,
  `builder_name` varchar(200) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `builder_phone` varchar(20) DEFAULT NULL,
  `builder_email` varchar(150) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(10) DEFAULT NULL,
  `google_map` text DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `is_recommended` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `rera_number` varchar(100) DEFAULT NULL,
  `registry_number` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` datetime DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `meta_description` text DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `title`, `slug`, `short_description`, `availability`, `publish_status`, `description`, `price`, `price_type`, `property_type`, `category`, `listing_type`, `bedrooms`, `bathrooms`, `area_sqft`, `floor`, `total_floors`, `furnishing`, `property_age`, `builder_name`, `contact_person`, `builder_phone`, `builder_email`, `featured_image`, `video_url`, `amenities`, `address`, `country`, `pincode`, `google_map`, `location_id`, `user_id`, `agent_id`, `status`, `rejection_reason`, `featured`, `is_trending`, `is_recommended`, `created_at`, `updated_at`, `meta_title`, `meta_keywords`, `rera_number`, `registry_number`, `is_verified`, `verified_at`, `views`, `meta_description`, `is_deleted`, `deleted_at`) VALUES
(1, 'Panchsheel Prime 390', 'panchsheel-prime-390', 'Panchsheel Prime 390 is a unique low-density residential development spread across 6.67 acres. Unlike high-rise towers, this project features G+4 independent floors with a dedicated lift for every block. There are only 2 apartments per floor, ensuring massive cross-ventilation and privacy. It is a gated community designed for those who prefer the feel of an independent house with the security of a township.', 'available', 'published', '', 15000000.00, 'total', 'apartment', 'Residential', 'sale', 3, 2, 1640.00, NULL, NULL, 'semi-furnished', '3-5 years', 'ABC Builder', '888888888', '', 'abcbuilder@gmail.com', 'prop_1774779484_feat.webp', '', '[\"parking\"]', 'Rajiv chowk New Delhi', 'India', '110001', '', 1, 2, NULL, 'approved', NULL, 1, 1, 1, '2026-03-29 15:48:04', '2026-03-29 15:48:51', 'Panchsheel Prime 390', 'Panchsheel Prime 390', NULL, NULL, 0, NULL, 0, 'Panchsheel Prime 390', 0, NULL),
(2, 'Shanti Enclave 1BHK Budget Apartment', 'shanti-enclave-1bhk', 'Affordable 1BHK perfect for bachelors in Sector 62 Noida.', 'available', 'published', 'Compact 1BHK with balcony, modular kitchen platform, vitrified tiles. Near Sector 62 Metro. Gated society, 24/7 security, water supply, power backup.', 2200000.00, 'total', 'apartment', 'Residential', 'sale', 1, 1, 450.00, 3, 8, 'unfurnished', '3-5 years', NULL, 'Priya Sharma', '9876543210', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\"]', 'Block C, Sector 62, Noida', 'India', '201309', NULL, 9, 4, NULL, 'approved', NULL, 0, 0, 0, '2026-03-10 08:00:00', NULL, 'Budget 1BHK Noida', '1bhk,noida,budget', 'UPRERAPRJ2024-NOI-001', NULL, 1, '2026-03-11 09:00:00', 45, 'Affordable 1BHK in Noida', 0, NULL),
(3, 'Maple Heights 2BHK Furnished Rental', 'maple-heights-2bhk-rent', 'Fully furnished 2BHK for rent in Koramangala Bangalore.', 'available', 'published', 'Move-in ready with AC, washing machine, fridge, LED TV, sofa, king beds. Pool, gym included. 5 min walk to Forum Mall.', 38000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 1100.00, 7, 14, 'furnished', '1-3 years', 'Maple Developers', 'Neha G', '9876543214', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"gym\",\"swimming_pool\",\"ac\",\"wifi\",\"laundry\"]', 'Koramangala 4th Cross, Bangalore', 'India', '560034', NULL, 4, 8, NULL, 'approved', NULL, 1, 1, 1, '2026-03-12 10:00:00', NULL, '2BHK Rental Bangalore', '2bhk,rent,bangalore,furnished', 'KARERA-PRJ-2023-456', NULL, 1, '2026-03-13 09:00:00', 132, '2BHK furnished rental Bangalore', 0, NULL),
(4, 'Godrej Meridien 3BHK', 'godrej-meridien-3bhk', '3BHK in Dwarka Expressway. Possession 2027.', 'under_construction', 'published', 'Spacious 3BHK with balconies, servant room. Infinity pool, golf green, squash court, co-working lounge. Near proposed metro.', 14500000.00, 'total', 'apartment', 'Residential', 'sale', 3, 3, 1950.00, NULL, 30, 'semi-furnished', 'New Construction', 'Godrej Properties', 'Sales Team', '1800111222', 'sales@godrej.com', NULL, NULL, '[\"parking\",\"lift\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\",\"power_backup\",\"cctv\",\"playground\",\"garden\"]', 'Sector 106, Dwarka Expressway, Gurgaon', 'India', '122006', NULL, 3, 6, NULL, 'approved', NULL, 1, 1, 1, '2026-03-14 09:00:00', NULL, 'Godrej Meridien Gurgaon', '3bhk,godrej,gurgaon,under construction', 'HARERA-GGN-2024-0789', NULL, 1, '2026-03-15 10:00:00', 678, 'Premium 3BHK under construction', 0, NULL),
(5, 'DLF Ultima 4BHK Penthouse', 'dlf-ultima-4bhk', 'Ultra-premium penthouse by DLF. Just launched.', 'new_launch', 'published', 'Private terrace, double-height living, Italian marble, smart home automation. Concierge, helipad, rooftop restaurant.', 55000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 5, 5200.00, 28, 30, 'unfurnished', 'New Construction', 'DLF Ltd', 'Premium Sales', '1800333444', 'premium@dlf.com', NULL, NULL, '[\"parking\",\"lift\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\",\"power_backup\",\"cctv\",\"ac\",\"intercom\"]', 'DLF Ultima, Sector 56, Gurgaon', 'India', '122011', NULL, 3, 6, NULL, 'approved', NULL, 1, 1, 1, '2026-04-01 06:00:00', NULL, 'DLF Ultima Penthouse', '4bhk,dlf,luxury,penthouse', 'HARERA-GGN-2026-ULT-01', NULL, 1, '2026-04-01 12:00:00', 892, 'Luxury 4BHK penthouse by DLF', 0, NULL),
(6, 'Supertech Cape Town 2BHK Rental', 'supertech-cape-town-2bhk-rent', 'Semi-furnished 2BHK for rent in Sector 74 Noida.', 'available', 'published', 'Wardrobes, kitchen cabinets, geyser. Pool, badminton, jogging track. Near Sector 71 Metro. Family preferred.', 18000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 980.00, 11, 22, 'semi-furnished', '5-10 years', 'Supertech Ltd', 'Rahul V', '9876543211', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"swimming_pool\",\"playground\"]', 'Tower H, Sector 74, Noida', 'India', '201301', NULL, 8, 5, NULL, 'approved', NULL, 0, 0, 1, '2026-03-16 11:00:00', NULL, '2BHK Rent Noida', '2bhk,rent,noida', 'UPRERAPRJ2019-NOI-567', NULL, 1, NULL, 56, 'Semi-furnished 2BHK rental Noida', 0, NULL),
(7, 'Pink City 2BHK Apartment', 'pink-city-2bhk-jaipur', 'Affordable 2BHK in Malviya Nagar Jaipur.', 'available', 'published', 'Vitrified tiles, modular kitchen, balcony with city view. Gardens, play area, community hall. Near World Trade Park and Metro.', 3500000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 950.00, 3, 7, 'unfurnished', 'Less than 1 year', 'Pink City Dev', NULL, '9855666666', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"playground\"]', 'Malviya Nagar, Jaipur', 'India', '302017', NULL, 16, 4, NULL, 'approved', NULL, 0, 0, 0, '2026-03-28 14:00:00', NULL, '2BHK Jaipur Affordable', '2bhk,jaipur,affordable', 'RAJRERA-2025-JAI-123', NULL, 1, '2026-03-29 08:00:00', 89, 'Affordable 2BHK in Jaipur', 0, NULL),
(8, 'Wave City 3BHK Smart Homes', 'wave-city-3bhk-smart', 'Smart homes in Wave City Greater Noida. Possession Dec 2027.', 'under_construction', 'published', 'Home automation, voice lighting, smart security. 12 acres, 70% open. Olympic pool, tennis, spa, organic farm. On NH-24.', 6500000.00, 'total', 'apartment', 'Residential', 'sale', 3, 2, 1450.00, NULL, 18, 'unfurnished', 'New Construction', 'Wave Infratech', 'Arun Mehta', '9844555555', 'arun@wave.com', NULL, NULL, '[\"parking\",\"lift\",\"security\",\"swimming_pool\",\"gym\",\"playground\",\"clubhouse\",\"power_backup\",\"cctv\"]', 'Wave City, NH-24, Greater Noida', 'India', '201306', NULL, 10, 6, NULL, 'approved', NULL, 1, 1, 0, '2026-03-26 12:00:00', NULL, 'Wave City Smart Homes', 'wave city,smart home,greater noida', 'UPRERAPRJ2025-WAVE-456', NULL, 1, NULL, 423, 'Smart homes in Greater Noida', 0, NULL),
(9, 'Omaxe Royal 4BHK Penthouse Lucknow', 'omaxe-royal-penthouse', 'Ultra-luxury penthouse in Gomti Nagar. New launch.', 'new_launch', 'published', 'Double-height living, private terrace, jacuzzi, riverfront views. Smart home, private elevator, home theatre.', 25000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 5, 4500.00, 18, 20, 'unfurnished', 'New Construction', 'Omaxe Ltd', 'Raj Kapoor', '9866777777', 'raj@omaxe.com', NULL, NULL, '[\"parking\",\"lift\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\",\"power_backup\",\"cctv\",\"ac\",\"intercom\"]', 'Gomti Nagar Extension, Lucknow', 'India', '226010', NULL, 13, 6, NULL, 'approved', NULL, 1, 1, 1, '2026-03-29 09:00:00', NULL, 'Omaxe Penthouse Lucknow', 'penthouse,lucknow,luxury', 'UPRERAPRJ2026-LKO-789', NULL, 1, NULL, 534, 'Luxury penthouse in Lucknow', 0, NULL),
(10, 'Royal Orchid Villa 4BHK', 'royal-orchid-villa-4bhk', 'Luxury 4BHK villa with pool and garden in Delhi.', 'available', 'published', 'Italian marble, home automation, private pool, landscaped garden, 3-car parking. Gated community with 24/7 security.', 45000000.00, 'total', 'villa', 'Residential', 'sale', 4, 4, 3500.00, NULL, 2, 'furnished', 'New Construction', 'Royal Builders', 'Rajesh Kumar', '9811111111', 'rajesh@royal.com', NULL, NULL, '[\"parking\",\"security\",\"swimming_pool\",\"gym\",\"garden\",\"cctv\",\"power_backup\",\"clubhouse\"]', 'Defence Colony, New Delhi', 'India', '110024', NULL, 1, 4, NULL, 'approved', NULL, 1, 1, 1, '2026-03-20 10:00:00', NULL, 'Luxury Villa Delhi', 'villa,delhi,luxury,4bhk', 'DLRERA2026001234', 'SD-2026-DL-0045', 1, '2026-03-21 08:00:00', 245, 'Luxury villa in Delhi', 0, NULL),
(11, 'Heritage Bungalow 5BHK Patna', 'heritage-bungalow-5bhk', '5BHK independent house with garden in Patna.', 'available', 'published', 'Marble flooring, modular kitchen, servant quarter, study room, pooja room, 2-car porch. Premium Dang Bangla area.', 12000000.00, 'total', 'villa', 'Residential', 'sale', 5, 4, 4000.00, NULL, 2, 'semi-furnished', '5-10 years', NULL, 'Suresh Yadav', '9876543213', NULL, NULL, NULL, '[\"parking\",\"security\",\"power_backup\",\"garden\",\"gas_pipeline\"]', 'Near Patna Junction, Dang Bangla', 'India', '800001', NULL, 6, 7, NULL, 'approved', NULL, 1, 0, 1, '2026-03-25 08:00:00', NULL, 'Bungalow Patna', 'villa,patna,5bhk', NULL, 'REG-BR-2020-5678', 1, '2026-03-26 09:00:00', 67, 'Independent house in Patna', 0, NULL),
(12, 'Palm Springs Villa 3BHK', 'palm-springs-villa-3bhk', 'Gated community villa on Golf Course Road Gurgaon.', 'available', 'published', 'Private garden, modular kitchen, marble flooring, wooden deck. Pool, tennis court, clubhouse. Near DLF Golf Course.', 32000000.00, 'total', 'villa', 'Residential', 'sale', 3, 3, 2800.00, NULL, 3, 'furnished', 'Less than 1 year', 'Palm Springs Estates', 'Vikram Malik', '9877111222', NULL, NULL, NULL, '[\"parking\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\",\"garden\",\"power_backup\",\"cctv\"]', 'Golf Course Road, Gurgaon', 'India', '122002', NULL, 3, 5, NULL, 'approved', NULL, 1, 1, 0, '2026-03-18 10:00:00', NULL, 'Villa Gurgaon', 'villa,gurgaon,3bhk', 'HARERA-GGN-2025-PS-111', 'SD-2026-HR-9876', 1, '2026-03-19 10:00:00', 234, 'Premium villa Gurgaon', 0, NULL),
(13, 'Brigade Orchards 4BHK Villa Rent', 'brigade-orchards-villa-rent', '4BHK furnished villa for rent near Bangalore Airport.', 'available', 'published', 'All attached baths, modular kitchen, private garden, servant quarter. Pool, gym, cricket ground. Company lease preferred.', 120000.00, 'per_month', 'villa', 'Residential', 'rent', 4, 4, 3200.00, NULL, 2, 'furnished', '3-5 years', 'Brigade Group', NULL, '9878222333', NULL, NULL, NULL, '[\"parking\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\",\"garden\",\"power_backup\",\"ac\"]', 'Devanahalli, Bangalore', 'India', '562110', NULL, 4, 8, NULL, 'approved', NULL, 0, 0, 1, '2026-03-22 14:00:00', NULL, 'Villa Rent Bangalore', 'villa,rent,bangalore', 'KARERA-PRJ-2022-BO-789', NULL, 1, NULL, 78, 'Furnished villa for rent', 0, NULL),
(14, 'Green Valley Plot 200 SqYd Noida', 'green-valley-plot-noida', 'Corner plot in Sector 150 near Jewar Airport.', 'available', 'published', 'On 60-ft road. Utility connections available. Near upcoming International Airport. Freehold with clear title.', 8500000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 1800.00, NULL, NULL, NULL, NULL, NULL, 'Suresh Y', '9876543213', NULL, NULL, NULL, '[\"parking\",\"security\"]', 'Plot 45, Sector 150, Noida', 'India', '201310', NULL, 8, 4, NULL, 'approved', NULL, 1, 1, 0, '2026-03-22 09:00:00', NULL, 'Plot Sector 150 Noida', 'plot,noida,sector 150', 'UPRERAPRJ2024-YEA-789', 'REG-UP-2025-7890', 1, '2026-03-23 10:00:00', 156, 'Residential plot near Jewar Airport', 0, NULL),
(15, 'Commercial Plot NH-24 Ghaziabad', 'commercial-plot-nh24', 'Highway-facing 500 SqYd commercial plot.', 'available', 'published', '100-ft frontage. GDA approved commercial zone. Suitable for showroom, hotel, warehouse. All NOCs in place.', 25000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 4500.00, NULL, NULL, NULL, NULL, NULL, 'Rahul V', '9876543211', NULL, NULL, NULL, '[\"parking\"]', 'NH-24, Near Dasna Toll, Ghaziabad', 'India', '201009', NULL, 11, 5, NULL, 'approved', NULL, 0, 0, 1, '2026-03-21 09:00:00', NULL, 'Commercial Plot NH-24', 'commercial,plot,ghaziabad', NULL, 'REG-UP-2024-COMM-456', 1, NULL, 89, 'Commercial plot on NH-24', 0, NULL),
(16, '10 Acre Farmland Jaipur', 'farmland-10acre-jaipur', 'Farmland with bore well and mango orchard near Jaipur.', 'available', 'published', '25 km from Jaipur on Ajmer Highway. Bore well, drip irrigation, 200+ mango trees, boundary wall, caretaker cottage.', 15000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 435600.00, NULL, NULL, NULL, NULL, NULL, NULL, '9855666666', NULL, NULL, NULL, '[\"garden\"]', 'Ajmer Highway, 25 km from Jaipur', 'India', '303007', NULL, 16, 4, NULL, 'approved', NULL, 0, 0, 0, '2026-03-23 07:00:00', NULL, 'Farmland Jaipur', 'farmland,jaipur,agriculture', NULL, 'REG-RJ-2023-FARM-111', 1, NULL, 34, 'Farmland near Jaipur', 0, NULL),
(17, 'High Street Retail Shop CP Delhi', 'retail-shop-cp-delhi', 'Ground floor retail in Connaught Place.', 'available', 'published', 'Double-height ceiling, glass facade, basement storage. Existing tenant paying 3.5L/month. Monthly footfall 50K+.', 65000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 1200.00, 0, 3, NULL, '10+ years', NULL, 'Priya S', '9876543210', NULL, NULL, NULL, '[\"parking\",\"security\",\"power_backup\",\"cctv\",\"ac\"]', 'Block N, Connaught Place, Delhi', 'India', '110001', NULL, 1, 4, NULL, 'approved', NULL, 1, 0, 1, '2026-03-24 12:00:00', NULL, 'Retail Shop CP', 'shop,cp,delhi,retail', NULL, 'SD-2015-DL-CP-789', 1, NULL, 456, 'Retail shop in Connaught Place', 0, NULL),
(18, 'Showroom MG Road Gurgaon Rent', 'showroom-mg-road-rent', 'Prime 2000 sqft showroom on MG Road.', 'available', 'published', 'Glass frontage, AC ducting, fire safety, 4 car parking. Near Metro. 3-year lock-in, 5% annual escalation.', 250000.00, 'per_month', 'commercial', 'Commercial', 'rent', NULL, NULL, 2000.00, 0, 4, NULL, '5-10 years', NULL, 'Rahul V', '9876543211', NULL, NULL, NULL, '[\"parking\",\"security\",\"power_backup\",\"cctv\",\"ac\",\"lift\"]', 'MG Road, Gurgaon', 'India', '122001', NULL, 3, 5, NULL, 'approved', NULL, 0, 1, 0, '2026-03-25 10:00:00', NULL, 'Showroom Rent Gurgaon', 'showroom,rent,gurgaon', 'HARERA-COMM-2024-333', NULL, 1, NULL, 198, 'Showroom for rent MG Road', 0, NULL),
(19, 'Warehouse 10000 SqFt Noida', 'warehouse-10000sqft-noida', 'Industrial warehouse with loading dock Sector 63.', 'available', 'published', '25-ft height, 2 loading docks, 200 KVA, fire suppression, office cabin. Easy access NH-24 and DND.', 35000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 10000.00, 0, 1, NULL, '5-10 years', NULL, NULL, '9877888999', NULL, NULL, NULL, '[\"parking\",\"security\",\"power_backup\",\"cctv\"]', 'Phase 3, Sector 63, Noida', 'India', '201301', NULL, 9, 7, NULL, 'approved', NULL, 0, 0, 0, '2026-03-26 08:00:00', NULL, 'Warehouse Noida', 'warehouse,noida,industrial', NULL, 'REG-UP-2020-IND-567', 1, NULL, 45, 'Industrial warehouse Noida', 0, NULL),
(20, 'IT Office 5000 SqFt Kolkata Rent', 'it-office-kolkata-rent', 'IT office for rent in Salt Lake Sector V.', 'available', 'published', '80 workstations, 4 cabins, boardroom, training room. 24/7 access, DG backup, housekeeping included. IT SEZ benefits.', 200000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 5000.00, 3, 6, 'furnished', '3-5 years', 'Salt Lake IT Hub', NULL, '9879333444', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"ac\",\"wifi\",\"cctv\",\"intercom\"]', 'Sector V, Salt Lake, Kolkata', 'India', '700091', NULL, 17, 5, NULL, 'approved', NULL, 0, 0, 1, '2026-03-28 09:00:00', NULL, 'IT Office Kolkata', 'office,kolkata,rent,IT', 'WBRERA-P01500-2024-567', NULL, 1, NULL, 123, 'IT office Salt Lake Kolkata', 0, NULL),
(21, 'CoWorking 50 Seats Noida Sale', 'coworking-50seats-noida', 'Running co-working business for sale.', 'available', 'published', '50 workstations, 3 cabins, 2 conference rooms, reception, pantry. Generating 4L/month. Sold as running business.', 12000000.00, 'total', 'office', 'Commercial', 'sale', NULL, NULL, 2800.00, 4, 8, 'furnished', '1-3 years', NULL, 'Neha G', '9876543214', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"ac\",\"wifi\",\"cctv\",\"intercom\"]', 'Tech Park, Sector 62, Noida', 'India', '201309', NULL, 9, 8, NULL, 'approved', NULL, 0, 1, 1, '2026-03-27 11:00:00', NULL, 'CoWorking Noida', 'coworking,office,noida', NULL, 'REG-UP-2023-OFF-234', 1, NULL, 167, 'Co-working space Noida', 0, NULL),
(22, 'Small Office Cabin Vaishali Rent', 'small-office-vaishali-rent', '200 sqft cabin for startup. Near Metro.', 'available', 'published', '4 workstations, AC, internet, shared conference room. Near Vaishali Metro. Electricity and housekeeping included.', 12000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 200.00, 2, 5, 'furnished', '5-10 years', NULL, NULL, '9880444555', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"ac\",\"wifi\"]', 'Mahagun Metro Mall, Vaishali', 'India', '201010', NULL, 12, 4, NULL, 'approved', NULL, 0, 0, 0, '2026-03-29 14:00:00', NULL, 'Office Vaishali', 'office,vaishali,budget', NULL, NULL, 0, NULL, 28, 'Budget office cabin Vaishali', 0, NULL),
(23, 'IT Tower Office Hyderabad', 'it-tower-office-hyderabad', 'Plug & play office in Hitech City Hyderabad.', 'available', 'published', 'Fully equipped with workstations, conference room, pantry. 100 Mbps internet, power backup, housekeeping included.', 150000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 3000.00, 6, 12, 'furnished', 'Less than 1 year', 'IT Tower Mgmt', NULL, NULL, NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"power_backup\",\"ac\",\"wifi\",\"cctv\"]', 'Hitech City, Hyderabad', 'India', '500081', NULL, 14, 5, NULL, 'approved', NULL, 0, 0, 1, '2026-03-27 10:00:00', NULL, 'Office Hyderabad', 'office,hyderabad,rent', 'TSRERA-P02100-2025-890', NULL, 1, NULL, 178, 'Office space Hitech City', 0, NULL),
(24, 'Nirala Estate 2BHK Pending', 'nirala-estate-2bhk-pending', '2BHK awaiting admin approval.', 'under_construction', 'draft', 'Budget 2BHK near Jewar Airport. Dec 2027 possession.', 3200000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 850.00, NULL, 16, 'unfurnished', 'New Construction', 'Nirala Group', 'Rahul', '9876543211', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\"]', 'Greater Noida West', 'India', '201306', NULL, 10, 5, NULL, 'approved', NULL, 0, 0, 0, '2026-04-02 09:00:00', NULL, NULL, NULL, 'UPRERAPRJ2025-GNW-999', NULL, 0, NULL, 0, NULL, 0, NULL),
(25, 'ATS Knightsbridge 4BHK Pending', 'ats-knightsbridge-pending', '4BHK by ATS pending review.', 'new_launch', 'draft', 'Luxury 4BHK with servant room, study, 3 balconies.', 22000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 4, 3200.00, NULL, 35, 'semi-furnished', 'New Construction', 'ATS Infrastructure', 'ATS Sales', '1800999888', NULL, NULL, NULL, '[\"parking\",\"lift\",\"security\",\"swimming_pool\",\"gym\",\"clubhouse\"]', 'Sector 124, Noida', 'India', '201301', NULL, 8, 6, NULL, 'approved', NULL, 0, 0, 0, '2026-04-03 07:00:00', NULL, NULL, NULL, 'UPRERAPRJ2026-ATS-001', NULL, 0, NULL, 0, NULL, 0, NULL),
(26, 'Owner Villa Patna Pending', 'owner-villa-patna-pending', 'Owner selling 3BHK villa.', 'available', 'draft', 'Well-maintained with garden and documents.', 8500000.00, 'total', 'villa', 'Residential', 'sale', 3, 2, 2200.00, NULL, 2, 'semi-furnished', '5-10 years', NULL, 'Suresh Y', '9876543213', NULL, NULL, NULL, '[\"parking\",\"security\",\"garden\"]', 'Bailey Road, Patna', 'India', '800001', NULL, 6, 7, NULL, 'approved', NULL, 0, 0, 0, '2026-04-03 08:00:00', NULL, NULL, NULL, NULL, 'REG-BR-2019-222', 0, NULL, 0, NULL, 0, NULL),
(27, 'Quick Sale 2BHK Chennai Pending', 'quick-sale-chennai-pending', 'Urgent sale pending approval.', 'available', 'draft', 'Owner relocating. Price negotiable.', 5500000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 1050.00, 5, 12, 'unfurnished', '3-5 years', NULL, NULL, '9876543213', NULL, NULL, NULL, '[\"parking\",\"lift\"]', 'Anna Nagar, Chennai', 'India', '600040', NULL, 15, 7, NULL, 'approved', NULL, 0, 0, 0, '2026-04-03 09:00:00', NULL, NULL, NULL, 'TNRERA-2023-456', NULL, 0, NULL, 0, NULL, 0, NULL),
(28, 'Fake Villa Rejected', 'fake-villa-rejected', 'Fraudulent listing.', 'available', 'draft', 'Invalid documents.', 50000000.00, 'total', 'villa', 'Residential', 'sale', 5, 5, 5000.00, NULL, 3, 'furnished', 'New Construction', 'Ghost Builders', NULL, NULL, NULL, NULL, NULL, '[\"parking\"]', 'Delhi', 'India', '110001', NULL, 1, 10, NULL, 'rejected', 'RERA number FAKE-999 does not exist. Documents appear fabricated. Account flagged for review.', 0, 0, 0, '2026-03-28 10:00:00', NULL, NULL, NULL, 'FAKE-999', NULL, 0, NULL, 0, NULL, 0, NULL),
(29, 'Disputed Land Rejected', 'disputed-land-rejected', 'Under legal dispute.', 'available', 'draft', 'Court case ongoing.', 3000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, '9880444555', NULL, NULL, NULL, '[]', 'Ghaziabad', 'India', '201001', NULL, 11, 4, NULL, 'rejected', 'Property under litigation (Case GZB-2024-1234). Cannot list until court clearance obtained.', 0, 0, 0, '2026-03-29 09:00:00', NULL, NULL, NULL, NULL, 'REG-DISPUTED', 0, NULL, 0, NULL, 0, NULL),
(30, 'Property in Gurgaon by Ankit', 'property-in-gurgaon-by-ankit', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gurgaon, Haryana', 'India', NULL, NULL, 3, 12, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 17:57:07', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(31, 'Property in Gurgaon by Ankit', 'property-in-gurgaon-by-ankit-2', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gurgaon, Haryana', 'India', NULL, NULL, 3, 14, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 18:05:46', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(32, 'Property in Nautan by Ankit Prasad', 'property-in-nautan-by-ankit-prasad', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nautan, Bihar', 'India', NULL, NULL, 18, 16, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 18:14:50', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(33, 'Property in Nautan by Ankit Prasad', 'property-in-nautan-by-ankit-prasad-2', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nautan, Bihar', 'India', NULL, NULL, 18, 18, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 20:29:57', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(34, 'Property in Nautan by Ankit Prasad', 'property-in-nautan-by-ankit-prasad-3', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nautan, Bihar', 'India', NULL, NULL, 18, 20, NULL, 'rejected', 'price not available', 0, 0, 0, '2026-04-04 20:33:36', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(35, 'Property in Noida by TestUser', 'property-in-noida-by-testuser', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 22, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 20:49:55', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(36, 'Property in Noida by \'; DROP TABLE users; --', 'property-in-noida-by-drop-table-users-', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 22, NULL, 'pending', NULL, 0, 0, 0, '2026-04-04 20:49:55', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(37, 'Property in Noida by <script>alert(1)</script>', 'property-in-noida-by-script-alert-1-script-', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 23, NULL, 'rejected', 'price not available', 0, 0, 0, '2026-04-04 20:49:56', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(38, 'Property in Noida by alert', 'property-in-noida-by-alert', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 24, NULL, 'rejected', 'testinf', 0, 0, 0, '2026-04-04 20:51:27', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(39, 'Property in Noida by Robert\'; DROP TABLE users;--', 'property-in-noida-by-robert-drop-table-users-', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 24, NULL, 'rejected', 'testing', 0, 0, 0, '2026-04-04 20:51:27', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(42, 'Property in Gurgaon by Gautam Gupta', 'property-in-gurgaon-by-gautam-gupta', 'This land for Agriculture', 'available', 'published', 'This land for Agriculture', 2000000.00, 'total', 'plot', 'Agricultural', 'sale', NULL, NULL, 5000.00, 0, 0, '', '', 'ABC Builder', 'Ankit Prasad', '9709906537', 'ankit.prasad@interactive12.com', 'prop_1775326227_feat.jpg', '', '[]', 'Gurgaon, Haryana', 'India', '841243', '', 3, 26, 2, 'approved', NULL, 1, 1, 1, '2026-04-04 21:11:35', '2026-04-04 23:47:22', 'Agriculture  land in Patna', 'Agriculture  land in Patna', NULL, NULL, 0, NULL, 0, 'Agriculture  land in Patna', 0, NULL),
(43, 'Property in Noida by \'; DROP TABLE users;--', 'property-in-noida-by-drop-table-users--2', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 28, NULL, 'rejected', 'testing', 0, 0, 0, '2026-04-04 21:24:08', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL),
(44, 'Property in Noida by SQL Test\';DROP TABLE--', 'property-in-noida-by-sql-test-drop-table-', NULL, 'available', 'draft', NULL, NULL, NULL, 'apartment', NULL, 'sale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Noida, UP', 'India', NULL, NULL, 19, 28, NULL, 'rejected', 'data not complete', 0, 0, 0, '2026-04-04 23:29:42', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_documents`
--

CREATE TABLE `property_documents` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `doc_type` varchar(100) DEFAULT 'registration' COMMENT 'registration, sale_deed, other',
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image`, `is_primary`, `is_deleted`, `deleted_at`) VALUES
(1, 1, 'prop_1_1774779484_0.jpg', 0, 0, NULL),
(2, 1, 'prop_1_1774779484_1.jpeg', 0, 0, NULL),
(3, 1, 'prop_1_1774779484_2.webp', 0, 0, NULL),
(4, 42, 'prop_42_1775326227_0.jpg', 0, 0, NULL),
(5, 42, 'prop_42_1775326227_1.webp', 0, 0, NULL),
(6, 42, 'prop_42_1775326227_2.jpg', 0, 0, NULL),
(7, 42, 'prop_42_1775326227_3.png', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_calls`
--

CREATE TABLE `schedule_calls` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `status` enum('new','contacted','completed') DEFAULT 'new',
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_calls`
--

INSERT INTO `schedule_calls` (`id`, `name`, `phone`, `email`, `preferred_date`, `preferred_time`, `message`, `property_id`, `status`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Ravi Kumar', '9898989898', 'ravi@email.com', '2026-04-05', '10 AM - 12 PM', 'Visit Panchsheel Prime', 1, 'new', '2026-04-03 15:43:07', 0, NULL),
(2, 'Anita Singh', '9797979797', 'anita@email.com', '2026-04-06', '2 PM - 4 PM', 'Site visit request', 1, 'new', '2026-04-03 15:43:07', 0, NULL),
(3, 'Sanjay G', '9494949494', 'sanjay@email.com', '2026-04-07', '11 AM - 1 PM', 'Discuss payment plan', 1, 'contacted', '2026-04-03 15:43:07', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(200) DEFAULT 'MakaanDekho',
  `site_logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_user` varchar(255) DEFAULT NULL,
  `smtp_pass` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT 587,
  `address` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_logo`, `favicon`, `meta_title`, `meta_description`, `meta_keywords`, `whatsapp_number`, `phone`, `email`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `address`, `footer_text`, `facebook`, `instagram`, `twitter`, `youtube`, `linkedin`, `updated_at`) VALUES
(1, 'MakaanDekho', 'logo_1775291967.png', 'favicon_1775298936.png', 'MakaanDekho – Find Your Dream Home | Verified Properties', 'India\'s trusted real estate portal. RERA verified apartments, villas, plots for sale and rent.', 'real estate,property,buy home,rent,RERA verified,MakaanDekho', '9999999999', NULL, 'info@makaandekho.in', 'smtp.gmail.com', 'ankitakp1995@gmail.com', 'zkymlbfeobnshzkd', 587, 'MakaanDekho.in, Tower B, Sector 62, Noida, UP 201301', '© 2026 MakaanDekho. All Rights Reserved.', NULL, NULL, NULL, NULL, NULL, '2026-04-04 17:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(4) DEFAULT 5,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `photo`, `content`, `rating`, `is_active`, `sort_order`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Rajesh Malhotra', 'Home Buyer, Delhi', NULL, 'MakaanDekho made finding my dream home incredibly easy. The verified listings gave me confidence, and the EMI calculator helped me plan my budget perfectly!', 5, 1, 1, '2026-04-03 15:43:07', 0, NULL),
(2, 'Priya Nair', 'Property Owner, Mumbai', NULL, 'I listed my apartment and got 15 genuine enquiries within the first week. The verification process ensures only serious buyers contact you.', 5, 1, 2, '2026-04-03 15:43:07', 0, NULL),
(3, 'Amit Choudhary', 'Real Estate Agent, Gurgaon', NULL, 'As an agent, MakaanDekho has become my primary platform. Dashboard is intuitive, leads are genuine. My sales increased 40%.', 5, 1, 3, '2026-04-03 15:43:07', 0, NULL),
(4, 'Sunita Devi', 'First-time Buyer, Patna', NULL, 'First-time buyer here. MakaanDekho guided me through everything. RERA verification helped me avoid fraud.', 4, 1, 4, '2026-04-03 15:43:07', 0, NULL),
(5, 'Vikram Builders', 'Builder, Noida', NULL, 'Listed 12 projects, response has been phenomenal. Well-designed platform with professional admin.', 5, 1, 5, '2026-04-03 15:43:07', 0, NULL),
(6, 'Neha Kapoor', 'Tenant, Bangalore', NULL, 'Found perfect rental apartment. Detailed filters and direct owner contact saved me from broker fees.', 4, 1, 6, '2026-04-03 15:43:07', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `role` enum('owner','agent','builder','filer') DEFAULT 'owner',
  `status` enum('pending','active','blocked') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `city`, `state`, `profile_image`, `password`, `reset_token`, `reset_expires`, `role`, `status`, `created_at`, `is_deleted`, `deleted_at`) VALUES
(1, 'Ankir Owner', 'owner@gmail.com', '9709906537', NULL, NULL, NULL, '$2y$10$GrojBPI8RC/nlIN6NII6OemCwnrBZDEs1wD9WUgZZT6R5jjemfYlS', NULL, NULL, 'owner', 'active', '2026-03-27 17:52:45', 0, NULL),
(2, 'Ankit Agent', 'agent@gmail.com', '9709906537', NULL, NULL, NULL, '$2y$10$btUzmR/XvuMy6bYV7aQS4e3rPbAn4yxwq.x/lmd/7Vc4k8wCyrI2e', NULL, NULL, 'agent', 'active', '2026-03-27 17:53:19', 0, NULL),
(3, 'Ankit Builder', 'builder@gmail.com', '9709906537', NULL, NULL, NULL, '$2y$10$nlqkCYUWgnaJ8V2/oaclbuCikyd6Ma9lf.HqASPIyI4nt6qhbelsW', NULL, NULL, 'builder', 'active', '2026-03-27 17:54:17', 0, NULL),
(4, 'Priya Sharma', 'priya@gmail.com', '9876543210', 'Delhi', 'Delhi', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'owner', 'active', '2026-03-15 10:00:00', 0, NULL),
(5, 'Rahul Verma', 'rahul@gmail.com', '9876543211', 'Gurgaon', 'Haryana', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'agent', 'active', '2026-03-16 11:00:00', 0, NULL),
(6, 'Meera Constructions', 'meera@builders.com', '9876543212', 'Mumbai', 'Maharashtra', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'builder', 'active', '2026-03-17 09:00:00', 0, NULL),
(7, 'Suresh Yadav', 'suresh@gmail.com', '9876543213', 'Patna', 'Bihar', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'filer', 'active', '2026-03-18 14:00:00', 0, NULL),
(8, 'Neha Gupta', 'neha@gmail.com', '9876543214', 'Bangalore', 'Karnataka', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'owner', 'active', '2026-03-19 16:00:00', 0, NULL),
(9, 'Vikram Singh', 'vikram@gmail.com', '9876543215', 'Pune', 'Maharashtra', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'agent', 'pending', '2026-03-20 12:00:00', 0, NULL),
(10, 'Amit Blocked', 'amit.blocked@gmail.com', '9876543216', 'Delhi', 'Delhi', NULL, '$2y$10$0pc7Vaizp810MBu9IiaBkuwTPMxQAcJ0CrzXehEYYvUieQYXAwKYi', NULL, NULL, 'owner', 'blocked', '2026-03-21 08:00:00', 0, NULL),
(20, 'Ankit Prasad', 'ankit.prasad@interactive12.com', '9709906537', 'Nautan', 'Bihar', NULL, '$2y$10$lRzorZYkO/DwiwrkycRDP.NReIrVbLQ8AeRob4VsiZGfP5DuISoM2', '45144485add17bf6b42e3dee6e867a784641d1b4cc98ceede1450616f73152c6', '2026-04-06 17:05:14', 'owner', 'active', '2026-04-04 20:33:36', 0, NULL),
(26, 'Gautam Gupta', 'gautam786hansh@gmail.com', '7778889996', 'Gurgaon', 'Haryana', NULL, '$2y$10$XK10HgDudxi6IyhigrQMMeYgN3U6gpKt02LTvxXROdZCzrMfL8CMe', '4e24ef814ad4c9f319f63c1d4c2d18e64196fe55f9e6ab0932479ab8ef2f6d79', '2026-04-06 17:43:00', 'builder', 'active', '2026-04-04 21:11:35', 0, NULL),
(28, '\'; DROP TABLE users;--', 'sqli@test.com', '9876543210', 'Noida', 'UP', NULL, '$2y$10$R.Q.KRlIepXNVybAmxMG9eVT8nMvsD4c/0Ofa1fn9Sz7a7PHZfTAW', NULL, NULL, 'owner', 'pending', '2026-04-04 21:24:08', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_slug` (`page_slug`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_property` (`user_id`,`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `mega_menu_items`
--
ALTER TABLE `mega_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_slug` (`menu_slug`,`is_active`,`column_order`,`item_order`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `property_documents`
--
ALTER TABLE `property_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `schedule_calls`
--
ALTER TABLE `schedule_calls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `mega_menu_items`
--
ALTER TABLE `mega_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `property_documents`
--
ALTER TABLE `property_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schedule_calls`
--
ALTER TABLE `schedule_calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
