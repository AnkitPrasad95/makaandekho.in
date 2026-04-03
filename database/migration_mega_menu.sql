-- ============================================================
-- Migration: Mega Menu System
-- Database: makaan_dekho
-- ============================================================
USE `makaan_dekho`;

CREATE TABLE IF NOT EXISTS `mega_menu_items` (
  `id`             INT NOT NULL AUTO_INCREMENT,
  `menu_slug`      VARCHAR(50)  NOT NULL COMMENT 'for_buyers, for_owners, insights, builders_agents',
  `column_heading` VARCHAR(100) NOT NULL COMMENT 'Column header e.g. RESIDENTIAL',
  `item_title`     VARCHAR(150) NOT NULL,
  `item_url`       VARCHAR(500) DEFAULT '#',
  `column_order`   INT NOT NULL DEFAULT 1 COMMENT 'Order of the column within menu',
  `item_order`     INT NOT NULL DEFAULT 1 COMMENT 'Order of item within column',
  `is_active`      TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_slug` (`menu_slug`, `is_active`, `column_order`, `item_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Seed: For Buyers
-- -------------------------------------------------------
INSERT INTO `mega_menu_items` (`menu_slug`, `column_heading`, `item_title`, `item_url`, `column_order`, `item_order`) VALUES
('for_buyers', 'RESIDENTIAL',   'Studio Apartments',     '#', 1, 1),
('for_buyers', 'RESIDENTIAL',   'Flats in Greater Noida','#', 1, 2),
('for_buyers', 'RESIDENTIAL',   'Independent Houses',    '#', 1, 3),
('for_buyers', 'RESIDENTIAL',   'Villas & Penthouses',   '#', 1, 4),
('for_buyers', 'RESIDENTIAL',   'Builder Floors',        '#', 1, 5),
('for_buyers', 'RESIDENTIAL',   'Farm Houses',           '#', 1, 6),
('for_buyers', 'COMMERCIAL',    'Office Spaces',         '#', 2, 1),
('for_buyers', 'COMMERCIAL',    'Retail Shops',          '#', 2, 2),
('for_buyers', 'COMMERCIAL',    'Showrooms',             '#', 2, 3),
('for_buyers', 'COMMERCIAL',    'Coworking Spaces',      '#', 2, 4),
('for_buyers', 'COMMERCIAL',    'Warehouses',            '#', 2, 5),
('for_buyers', 'COMMERCIAL',    'Industrial Buildings',  '#', 2, 6),
('for_buyers', 'PLOTS & LAND',  'Industrial Plots',      '#', 3, 1),
('for_buyers', 'PLOTS & LAND',  'Warehouse Land',        '#', 3, 2),
('for_buyers', 'PLOTS & LAND',  'Factory Land',          '#', 3, 3),
('for_buyers', 'PLOTS & LAND',  'Villa Plots',           '#', 3, 4),
('for_buyers', 'PLOTS & LAND',  'Corner Plots',          '#', 3, 5),
('for_buyers', 'PLOTS & LAND',  'Authority Approved',    '#', 3, 6);

-- -------------------------------------------------------
-- Seed: For Owners
-- -------------------------------------------------------
INSERT INTO `mega_menu_items` (`menu_slug`, `column_heading`, `item_title`, `item_url`, `column_order`, `item_order`) VALUES
('for_owners', 'POST & SELL',         'Post Property Free',   '#', 1, 1),
('for_owners', 'POST & SELL',         'Premium Seller Ad',    '#', 1, 2),
('for_owners', 'POST & SELL',         'Seller Packages',      '#', 1, 3),
('for_owners', 'FINANCIAL SERVICES',  'Home Loan Leads',      '#', 2, 1),
('for_owners', 'FINANCIAL SERVICES',  'EMI Calculator',       '#', 2, 2),
('for_owners', 'FINANCIAL SERVICES',  'Tax Benefits',         '#', 2, 3),
('for_owners', 'LEGAL & SUPPORT',     'Property Valuation',   '#', 3, 1),
('for_owners', 'LEGAL & SUPPORT',     'Legal Assistance',     '#', 3, 2),
('for_owners', 'LEGAL & SUPPORT',     'Tenant Verification',  '#', 3, 3);

-- -------------------------------------------------------
-- Seed: Insights
-- -------------------------------------------------------
INSERT INTO `mega_menu_items` (`menu_slug`, `column_heading`, `item_title`, `item_url`, `column_order`, `item_order`) VALUES
('insights', 'MARKET & NEWS',   'Real Estate News',  '#', 1, 1),
('insights', 'MARKET & NEWS',   'Market Trends',     '#', 1, 2),
('insights', 'MARKET & NEWS',   'Latest Articles',   '#', 1, 3),
('insights', 'EXPERT GUIDES',   'Buyer Guides',      '#', 2, 1),
('insights', 'EXPERT GUIDES',   'Investment Tips',   '#', 2, 2);

-- -------------------------------------------------------
-- Seed: Builders & Agents
-- -------------------------------------------------------
INSERT INTO `mega_menu_items` (`menu_slug`, `column_heading`, `item_title`, `item_url`, `column_order`, `item_order`) VALUES
('builders_agents', 'FOR BUILDERS', 'Search Builders',      '#', 1, 1),
('builders_agents', 'FOR BUILDERS', 'Advertise Projects',   '#', 1, 2),
('builders_agents', 'FOR BUILDERS', 'Branding Solutions',   '#', 1, 3),
('builders_agents', 'FOR AGENTS',   'Search Agents',        '#', 2, 1),
('builders_agents', 'FOR AGENTS',   'Join as Agent',        '#', 2, 2),
('builders_agents', 'FOR AGENTS',   'Premium Packages',     '#', 2, 3),
('builders_agents', 'FOR AGENTS',   'Get Verified Leads',   '#', 2, 4);
