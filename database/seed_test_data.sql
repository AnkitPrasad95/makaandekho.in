-- ============================================================
-- Seed Test Data (safe to run on existing DB)
-- Skips users/locations already present
-- Database: makaan_dekho
-- ============================================================
USE `makaan_dekho`;

-- -------------------------------------------------------
-- 1. PROPERTIES (25 total covering all types)
-- Existing: ID 1 (Panchsheel Prime)
-- -------------------------------------------------------
INSERT INTO `properties` (`title`, `slug`, `short_description`, `availability`, `publish_status`, `description`, `price`, `price_type`, `property_type`, `category`, `listing_type`, `bedrooms`, `bathrooms`, `area_sqft`, `floor`, `total_floors`, `furnishing`, `property_age`, `builder_name`, `contact_person`, `builder_phone`, `builder_email`, `featured_image`, `video_url`, `amenities`, `address`, `country`, `pincode`, `google_map`, `location_id`, `user_id`, `status`, `rejection_reason`, `featured`, `is_trending`, `is_recommended`, `created_at`, `updated_at`, `meta_title`, `meta_keywords`, `rera_number`, `registry_number`, `is_verified`, `verified_at`, `views`, `meta_description`) VALUES

-- === APARTMENTS ===

-- 1BHK | Sale | Ready | Unfurnished | Budget
('Shanti Enclave 1BHK Budget Apartment', 'shanti-enclave-1bhk', 'Affordable 1BHK perfect for bachelors in Sector 62 Noida.', 'available', 'published', 'Compact 1BHK with balcony, modular kitchen platform, vitrified tiles. Near Sector 62 Metro. Gated society, 24/7 security, water supply, power backup.', 2200000.00, 'total', 'apartment', 'Residential', 'sale', 1, 1, 450.00, 3, 8, 'unfurnished', '3-5 years', NULL, 'Priya Sharma', '9876543210', NULL, NULL, NULL, '["parking","lift","security","power_backup"]', 'Block C, Sector 62, Noida', 'India', '201309', NULL, 9, 4, 'approved', NULL, 0, 0, 0, '2026-03-10 08:00:00', NULL, 'Budget 1BHK Noida', '1bhk,noida,budget', 'UPRERAPRJ2024-NOI-001', NULL, 1, '2026-03-11 09:00:00', 45, 'Affordable 1BHK in Noida'),

-- 2BHK | Rent | Ready | Furnished
('Maple Heights 2BHK Furnished Rental', 'maple-heights-2bhk-rent', 'Fully furnished 2BHK for rent in Koramangala Bangalore.', 'available', 'published', 'Move-in ready with AC, washing machine, fridge, LED TV, sofa, king beds. Pool, gym included. 5 min walk to Forum Mall.', 38000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 1100.00, 7, 14, 'furnished', '1-3 years', 'Maple Developers', 'Neha G', '9876543214', NULL, NULL, NULL, '["parking","lift","security","power_backup","gym","swimming_pool","ac","wifi","laundry"]', 'Koramangala 4th Cross, Bangalore', 'India', '560034', NULL, 4, 8, 'approved', NULL, 1, 1, 1, '2026-03-12 10:00:00', NULL, '2BHK Rental Bangalore', '2bhk,rent,bangalore,furnished', 'KARERA-PRJ-2023-456', NULL, 1, '2026-03-13 09:00:00', 132, '2BHK furnished rental Bangalore'),

-- 3BHK | Sale | Under Construction | Semi-Furnished
('Godrej Meridien 3BHK', 'godrej-meridien-3bhk', '3BHK in Dwarka Expressway. Possession 2027.', 'under_construction', 'published', 'Spacious 3BHK with balconies, servant room. Infinity pool, golf green, squash court, co-working lounge. Near proposed metro.', 14500000.00, 'total', 'apartment', 'Residential', 'sale', 3, 3, 1950.00, NULL, 30, 'semi-furnished', 'New Construction', 'Godrej Properties', 'Sales Team', '1800111222', 'sales@godrej.com', NULL, NULL, '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","playground","garden"]', 'Sector 106, Dwarka Expressway, Gurgaon', 'India', '122006', NULL, 3, 6, 'approved', NULL, 1, 1, 1, '2026-03-14 09:00:00', NULL, 'Godrej Meridien Gurgaon', '3bhk,godrej,gurgaon,under construction', 'HARERA-GGN-2024-0789', NULL, 1, '2026-03-15 10:00:00', 678, 'Premium 3BHK under construction'),

-- 4BHK | Sale | New Launch | Unfurnished | Luxury
('DLF Ultima 4BHK Penthouse', 'dlf-ultima-4bhk', 'Ultra-premium penthouse by DLF. Just launched.', 'new_launch', 'published', 'Private terrace, double-height living, Italian marble, smart home automation. Concierge, helipad, rooftop restaurant.', 55000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 5, 5200.00, 28, 30, 'unfurnished', 'New Construction', 'DLF Ltd', 'Premium Sales', '1800333444', 'premium@dlf.com', NULL, NULL, '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","ac","intercom"]', 'DLF Ultima, Sector 56, Gurgaon', 'India', '122011', NULL, 3, 6, 'approved', NULL, 1, 1, 1, '2026-04-01 06:00:00', NULL, 'DLF Ultima Penthouse', '4bhk,dlf,luxury,penthouse', 'HARERA-GGN-2026-ULT-01', NULL, 1, '2026-04-01 12:00:00', 892, 'Luxury 4BHK penthouse by DLF'),

-- 2BHK | Rent | Semi-Furnished
('Supertech Cape Town 2BHK Rental', 'supertech-cape-town-2bhk-rent', 'Semi-furnished 2BHK for rent in Sector 74 Noida.', 'available', 'published', 'Wardrobes, kitchen cabinets, geyser. Pool, badminton, jogging track. Near Sector 71 Metro. Family preferred.', 18000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 980.00, 11, 22, 'semi-furnished', '5-10 years', 'Supertech Ltd', 'Rahul V', '9876543211', NULL, NULL, NULL, '["parking","lift","security","power_backup","swimming_pool","playground"]', 'Tower H, Sector 74, Noida', 'India', '201301', NULL, 8, 5, 'approved', NULL, 0, 0, 1, '2026-03-16 11:00:00', NULL, '2BHK Rent Noida', '2bhk,rent,noida', 'UPRERAPRJ2019-NOI-567', NULL, 1, NULL, 56, 'Semi-furnished 2BHK rental Noida'),

-- 2BHK | Sale | Ready | Affordable
('Pink City 2BHK Apartment', 'pink-city-2bhk-jaipur', 'Affordable 2BHK in Malviya Nagar Jaipur.', 'available', 'published', 'Vitrified tiles, modular kitchen, balcony with city view. Gardens, play area, community hall. Near World Trade Park and Metro.', 3500000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 950.00, 3, 7, 'unfurnished', 'Less than 1 year', 'Pink City Dev', NULL, '9855666666', NULL, NULL, NULL, '["parking","lift","security","power_backup","playground"]', 'Malviya Nagar, Jaipur', 'India', '302017', NULL, 16, 4, 'approved', NULL, 0, 0, 0, '2026-03-28 14:00:00', NULL, '2BHK Jaipur Affordable', '2bhk,jaipur,affordable', 'RAJRERA-2025-JAI-123', NULL, 1, '2026-03-29 08:00:00', 89, 'Affordable 2BHK in Jaipur'),

-- 3BHK | Sale | Under Construction | Smart Home
('Wave City 3BHK Smart Homes', 'wave-city-3bhk-smart', 'Smart homes in Wave City Greater Noida. Possession Dec 2027.', 'under_construction', 'published', 'Home automation, voice lighting, smart security. 12 acres, 70% open. Olympic pool, tennis, spa, organic farm. On NH-24.', 6500000.00, 'total', 'apartment', 'Residential', 'sale', 3, 2, 1450.00, NULL, 18, 'unfurnished', 'New Construction', 'Wave Infratech', 'Arun Mehta', '9844555555', 'arun@wave.com', NULL, NULL, '["parking","lift","security","swimming_pool","gym","playground","clubhouse","power_backup","cctv"]', 'Wave City, NH-24, Greater Noida', 'India', '201306', NULL, 10, 6, 'approved', NULL, 1, 1, 0, '2026-03-26 12:00:00', NULL, 'Wave City Smart Homes', 'wave city,smart home,greater noida', 'UPRERAPRJ2025-WAVE-456', NULL, 1, NULL, 423, 'Smart homes in Greater Noida'),

-- 4BHK | Sale | New Launch | Penthouse
('Omaxe Royal 4BHK Penthouse Lucknow', 'omaxe-royal-penthouse', 'Ultra-luxury penthouse in Gomti Nagar. New launch.', 'new_launch', 'published', 'Double-height living, private terrace, jacuzzi, riverfront views. Smart home, private elevator, home theatre.', 25000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 5, 4500.00, 18, 20, 'unfurnished', 'New Construction', 'Omaxe Ltd', 'Raj Kapoor', '9866777777', 'raj@omaxe.com', NULL, NULL, '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","ac","intercom"]', 'Gomti Nagar Extension, Lucknow', 'India', '226010', NULL, 13, 6, 'approved', NULL, 1, 1, 1, '2026-03-29 09:00:00', NULL, 'Omaxe Penthouse Lucknow', 'penthouse,lucknow,luxury', 'UPRERAPRJ2026-LKO-789', NULL, 1, NULL, 534, 'Luxury penthouse in Lucknow'),

-- === VILLAS ===

-- 4BHK Villa | Sale | Ready | Furnished
('Royal Orchid Villa 4BHK', 'royal-orchid-villa-4bhk', 'Luxury 4BHK villa with pool and garden in Delhi.', 'available', 'published', 'Italian marble, home automation, private pool, landscaped garden, 3-car parking. Gated community with 24/7 security.', 45000000.00, 'total', 'villa', 'Residential', 'sale', 4, 4, 3500.00, NULL, 2, 'furnished', 'New Construction', 'Royal Builders', 'Rajesh Kumar', '9811111111', 'rajesh@royal.com', NULL, NULL, '["parking","security","swimming_pool","gym","garden","cctv","power_backup","clubhouse"]', 'Defence Colony, New Delhi', 'India', '110024', NULL, 1, 4, 'approved', NULL, 1, 1, 1, '2026-03-20 10:00:00', NULL, 'Luxury Villa Delhi', 'villa,delhi,luxury,4bhk', 'DLRERA2026001234', 'SD-2026-DL-0045', 1, '2026-03-21 08:00:00', 245, 'Luxury villa in Delhi'),

-- 5BHK Villa | Sale | Ready
('Heritage Bungalow 5BHK Patna', 'heritage-bungalow-5bhk', '5BHK independent house with garden in Patna.', 'available', 'published', 'Marble flooring, modular kitchen, servant quarter, study room, pooja room, 2-car porch. Premium Dang Bangla area.', 12000000.00, 'total', 'villa', 'Residential', 'sale', 5, 4, 4000.00, NULL, 2, 'semi-furnished', '5-10 years', NULL, 'Suresh Yadav', '9876543213', NULL, NULL, NULL, '["parking","security","power_backup","garden","gas_pipeline"]', 'Near Patna Junction, Dang Bangla', 'India', '800001', NULL, 6, 7, 'approved', NULL, 1, 0, 1, '2026-03-25 08:00:00', NULL, 'Bungalow Patna', 'villa,patna,5bhk', NULL, 'REG-BR-2020-5678', 1, '2026-03-26 09:00:00', 67, 'Independent house in Patna'),

-- 3BHK Villa | Sale | Gurgaon
('Palm Springs Villa 3BHK', 'palm-springs-villa-3bhk', 'Gated community villa on Golf Course Road Gurgaon.', 'available', 'published', 'Private garden, modular kitchen, marble flooring, wooden deck. Pool, tennis court, clubhouse. Near DLF Golf Course.', 32000000.00, 'total', 'villa', 'Residential', 'sale', 3, 3, 2800.00, NULL, 3, 'furnished', 'Less than 1 year', 'Palm Springs Estates', 'Vikram Malik', '9877111222', NULL, NULL, NULL, '["parking","security","swimming_pool","gym","clubhouse","garden","power_backup","cctv"]', 'Golf Course Road, Gurgaon', 'India', '122002', NULL, 3, 5, 'approved', NULL, 1, 1, 0, '2026-03-18 10:00:00', NULL, 'Villa Gurgaon', 'villa,gurgaon,3bhk', 'HARERA-GGN-2025-PS-111', 'SD-2026-HR-9876', 1, '2026-03-19 10:00:00', 234, 'Premium villa Gurgaon'),

-- 4BHK Villa | Rent
('Brigade Orchards 4BHK Villa Rent', 'brigade-orchards-villa-rent', '4BHK furnished villa for rent near Bangalore Airport.', 'available', 'published', 'All attached baths, modular kitchen, private garden, servant quarter. Pool, gym, cricket ground. Company lease preferred.', 120000.00, 'per_month', 'villa', 'Residential', 'rent', 4, 4, 3200.00, NULL, 2, 'furnished', '3-5 years', 'Brigade Group', NULL, '9878222333', NULL, NULL, NULL, '["parking","security","swimming_pool","gym","clubhouse","garden","power_backup","ac"]', 'Devanahalli, Bangalore', 'India', '562110', NULL, 4, 8, 'approved', NULL, 0, 0, 1, '2026-03-22 14:00:00', NULL, 'Villa Rent Bangalore', 'villa,rent,bangalore', 'KARERA-PRJ-2022-BO-789', NULL, 1, NULL, 78, 'Furnished villa for rent'),

-- === PLOTS ===

-- Residential Plot
('Green Valley Plot 200 SqYd Noida', 'green-valley-plot-noida', 'Corner plot in Sector 150 near Jewar Airport.', 'available', 'published', 'On 60-ft road. Utility connections available. Near upcoming International Airport. Freehold with clear title.', 8500000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 1800.00, NULL, NULL, NULL, NULL, NULL, 'Suresh Y', '9876543213', NULL, NULL, NULL, '["parking","security"]', 'Plot 45, Sector 150, Noida', 'India', '201310', NULL, 8, 4, 'approved', NULL, 1, 1, 0, '2026-03-22 09:00:00', NULL, 'Plot Sector 150 Noida', 'plot,noida,sector 150', 'UPRERAPRJ2024-YEA-789', 'REG-UP-2025-7890', 1, '2026-03-23 10:00:00', 156, 'Residential plot near Jewar Airport'),

-- Commercial Plot
('Commercial Plot NH-24 Ghaziabad', 'commercial-plot-nh24', 'Highway-facing 500 SqYd commercial plot.', 'available', 'published', '100-ft frontage. GDA approved commercial zone. Suitable for showroom, hotel, warehouse. All NOCs in place.', 25000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 4500.00, NULL, NULL, NULL, NULL, NULL, 'Rahul V', '9876543211', NULL, NULL, NULL, '["parking"]', 'NH-24, Near Dasna Toll, Ghaziabad', 'India', '201009', NULL, 11, 5, 'approved', NULL, 0, 0, 1, '2026-03-21 09:00:00', NULL, 'Commercial Plot NH-24', 'commercial,plot,ghaziabad', NULL, 'REG-UP-2024-COMM-456', 1, NULL, 89, 'Commercial plot on NH-24'),

-- Farm Land
('10 Acre Farmland Jaipur', 'farmland-10acre-jaipur', 'Farmland with bore well and mango orchard near Jaipur.', 'available', 'published', '25 km from Jaipur on Ajmer Highway. Bore well, drip irrigation, 200+ mango trees, boundary wall, caretaker cottage.', 15000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 435600.00, NULL, NULL, NULL, NULL, NULL, NULL, '9855666666', NULL, NULL, NULL, '["garden"]', 'Ajmer Highway, 25 km from Jaipur', 'India', '303007', NULL, 16, 4, 'approved', NULL, 0, 0, 0, '2026-03-23 07:00:00', NULL, 'Farmland Jaipur', 'farmland,jaipur,agriculture', NULL, 'REG-RJ-2023-FARM-111', 1, NULL, 34, 'Farmland near Jaipur'),

-- === COMMERCIAL ===

-- Retail Shop | Sale
('High Street Retail Shop CP Delhi', 'retail-shop-cp-delhi', 'Ground floor retail in Connaught Place.', 'available', 'published', 'Double-height ceiling, glass facade, basement storage. Existing tenant paying 3.5L/month. Monthly footfall 50K+.', 65000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 1200.00, 0, 3, NULL, '10+ years', NULL, 'Priya S', '9876543210', NULL, NULL, NULL, '["parking","security","power_backup","cctv","ac"]', 'Block N, Connaught Place, Delhi', 'India', '110001', NULL, 1, 4, 'approved', NULL, 1, 0, 1, '2026-03-24 12:00:00', NULL, 'Retail Shop CP', 'shop,cp,delhi,retail', NULL, 'SD-2015-DL-CP-789', 1, NULL, 456, 'Retail shop in Connaught Place'),

-- Showroom | Rent
('Showroom MG Road Gurgaon Rent', 'showroom-mg-road-rent', 'Prime 2000 sqft showroom on MG Road.', 'available', 'published', 'Glass frontage, AC ducting, fire safety, 4 car parking. Near Metro. 3-year lock-in, 5% annual escalation.', 250000.00, 'per_month', 'commercial', 'Commercial', 'rent', NULL, NULL, 2000.00, 0, 4, NULL, '5-10 years', NULL, 'Rahul V', '9876543211', NULL, NULL, NULL, '["parking","security","power_backup","cctv","ac","lift"]', 'MG Road, Gurgaon', 'India', '122001', NULL, 3, 5, 'approved', NULL, 0, 1, 0, '2026-03-25 10:00:00', NULL, 'Showroom Rent Gurgaon', 'showroom,rent,gurgaon', 'HARERA-COMM-2024-333', NULL, 1, NULL, 198, 'Showroom for rent MG Road'),

-- Warehouse | Sale
('Warehouse 10000 SqFt Noida', 'warehouse-10000sqft-noida', 'Industrial warehouse with loading dock Sector 63.', 'available', 'published', '25-ft height, 2 loading docks, 200 KVA, fire suppression, office cabin. Easy access NH-24 and DND.', 35000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 10000.00, 0, 1, NULL, '5-10 years', NULL, NULL, '9877888999', NULL, NULL, NULL, '["parking","security","power_backup","cctv"]', 'Phase 3, Sector 63, Noida', 'India', '201301', NULL, 9, 7, 'approved', NULL, 0, 0, 0, '2026-03-26 08:00:00', NULL, 'Warehouse Noida', 'warehouse,noida,industrial', NULL, 'REG-UP-2020-IND-567', 1, NULL, 45, 'Industrial warehouse Noida'),

-- === OFFICES ===

-- IT Office | Rent
('IT Office 5000 SqFt Kolkata Rent', 'it-office-kolkata-rent', 'IT office for rent in Salt Lake Sector V.', 'available', 'published', '80 workstations, 4 cabins, boardroom, training room. 24/7 access, DG backup, housekeeping included. IT SEZ benefits.', 200000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 5000.00, 3, 6, 'furnished', '3-5 years', 'Salt Lake IT Hub', NULL, '9879333444', NULL, NULL, NULL, '["parking","lift","security","power_backup","ac","wifi","cctv","intercom"]', 'Sector V, Salt Lake, Kolkata', 'India', '700091', NULL, 17, 5, 'approved', NULL, 0, 0, 1, '2026-03-28 09:00:00', NULL, 'IT Office Kolkata', 'office,kolkata,rent,IT', 'WBRERA-P01500-2024-567', NULL, 1, NULL, 123, 'IT office Salt Lake Kolkata'),

-- Co-working | Sale
('CoWorking 50 Seats Noida Sale', 'coworking-50seats-noida', 'Running co-working business for sale.', 'available', 'published', '50 workstations, 3 cabins, 2 conference rooms, reception, pantry. Generating 4L/month. Sold as running business.', 12000000.00, 'total', 'office', 'Commercial', 'sale', NULL, NULL, 2800.00, 4, 8, 'furnished', '1-3 years', NULL, 'Neha G', '9876543214', NULL, NULL, NULL, '["parking","lift","security","power_backup","ac","wifi","cctv","intercom"]', 'Tech Park, Sector 62, Noida', 'India', '201309', NULL, 9, 8, 'approved', NULL, 0, 1, 1, '2026-03-27 11:00:00', NULL, 'CoWorking Noida', 'coworking,office,noida', NULL, 'REG-UP-2023-OFF-234', 1, NULL, 167, 'Co-working space Noida'),

-- Small Office | Rent | Budget
('Small Office Cabin Vaishali Rent', 'small-office-vaishali-rent', '200 sqft cabin for startup. Near Metro.', 'available', 'published', '4 workstations, AC, internet, shared conference room. Near Vaishali Metro. Electricity and housekeeping included.', 12000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 200.00, 2, 5, 'furnished', '5-10 years', NULL, NULL, '9880444555', NULL, NULL, NULL, '["parking","lift","security","power_backup","ac","wifi"]', 'Mahagun Metro Mall, Vaishali', 'India', '201010', NULL, 12, 4, 'approved', NULL, 0, 0, 0, '2026-03-29 14:00:00', NULL, 'Office Vaishali', 'office,vaishali,budget', NULL, NULL, 0, NULL, 28, 'Budget office cabin Vaishali'),

-- Office | Sale | Hyderabad
('IT Tower Office Hyderabad', 'it-tower-office-hyderabad', 'Plug & play office in Hitech City Hyderabad.', 'available', 'published', 'Fully equipped with workstations, conference room, pantry. 100 Mbps internet, power backup, housekeeping included.', 150000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 3000.00, 6, 12, 'furnished', 'Less than 1 year', 'IT Tower Mgmt', NULL, NULL, NULL, NULL, NULL, '["parking","lift","security","power_backup","ac","wifi","cctv"]', 'Hitech City, Hyderabad', 'India', '500081', NULL, 14, 5, 'approved', NULL, 0, 0, 1, '2026-03-27 10:00:00', NULL, 'Office Hyderabad', 'office,hyderabad,rent', 'TSRERA-P02100-2025-890', NULL, 1, NULL, 178, 'Office space Hitech City'),

-- === PENDING (4 for admin testing) ===

('Nirala Estate 2BHK Pending', 'nirala-estate-2bhk-pending', '2BHK awaiting admin approval.', 'under_construction', 'draft', 'Budget 2BHK near Jewar Airport. Dec 2027 possession.', 3200000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 850.00, NULL, 16, 'unfurnished', 'New Construction', 'Nirala Group', 'Rahul', '9876543211', NULL, NULL, NULL, '["parking","lift","security"]', 'Greater Noida West', 'India', '201306', NULL, 10, 5, 'pending', NULL, 0, 0, 0, '2026-04-02 09:00:00', NULL, NULL, NULL, 'UPRERAPRJ2025-GNW-999', NULL, 0, NULL, 0, NULL),

('ATS Knightsbridge 4BHK Pending', 'ats-knightsbridge-pending', '4BHK by ATS pending review.', 'new_launch', 'draft', 'Luxury 4BHK with servant room, study, 3 balconies.', 22000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 4, 3200.00, NULL, 35, 'semi-furnished', 'New Construction', 'ATS Infrastructure', 'ATS Sales', '1800999888', NULL, NULL, NULL, '["parking","lift","security","swimming_pool","gym","clubhouse"]', 'Sector 124, Noida', 'India', '201301', NULL, 8, 6, 'pending', NULL, 0, 0, 0, '2026-04-03 07:00:00', NULL, NULL, NULL, 'UPRERAPRJ2026-ATS-001', NULL, 0, NULL, 0, NULL),

('Owner Villa Patna Pending', 'owner-villa-patna-pending', 'Owner selling 3BHK villa.', 'available', 'draft', 'Well-maintained with garden and documents.', 8500000.00, 'total', 'villa', 'Residential', 'sale', 3, 2, 2200.00, NULL, 2, 'semi-furnished', '5-10 years', NULL, 'Suresh Y', '9876543213', NULL, NULL, NULL, '["parking","security","garden"]', 'Bailey Road, Patna', 'India', '800001', NULL, 6, 7, 'pending', NULL, 0, 0, 0, '2026-04-03 08:00:00', NULL, NULL, NULL, NULL, 'REG-BR-2019-222', 0, NULL, 0, NULL),

('Quick Sale 2BHK Chennai Pending', 'quick-sale-chennai-pending', 'Urgent sale pending approval.', 'available', 'draft', 'Owner relocating. Price negotiable.', 5500000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 1050.00, 5, 12, 'unfurnished', '3-5 years', NULL, NULL, '9876543213', NULL, NULL, NULL, '["parking","lift"]', 'Anna Nagar, Chennai', 'India', '600040', NULL, 15, 7, 'pending', NULL, 0, 0, 0, '2026-04-03 09:00:00', NULL, NULL, NULL, 'TNRERA-2023-456', NULL, 0, NULL, 0, NULL),

-- === REJECTED (2 for rejection reason testing) ===

('Fake Villa Rejected', 'fake-villa-rejected', 'Fraudulent listing.', 'available', 'draft', 'Invalid documents.', 50000000.00, 'total', 'villa', 'Residential', 'sale', 5, 5, 5000.00, NULL, 3, 'furnished', 'New Construction', 'Ghost Builders', NULL, NULL, NULL, NULL, NULL, '["parking"]', 'Delhi', 'India', '110001', NULL, 1, 10, 'rejected', 'RERA number FAKE-999 does not exist. Documents appear fabricated. Account flagged for review.', 0, 0, 0, '2026-03-28 10:00:00', NULL, NULL, NULL, 'FAKE-999', NULL, 0, NULL, 0, NULL),

('Disputed Land Rejected', 'disputed-land-rejected', 'Under legal dispute.', 'available', 'draft', 'Court case ongoing.', 3000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, '9880444555', NULL, NULL, NULL, '[]', 'Ghaziabad', 'India', '201001', NULL, 11, 4, 'rejected', 'Property under litigation (Case GZB-2024-1234). Cannot list until court clearance obtained.', 0, 0, 0, '2026-03-29 09:00:00', NULL, NULL, NULL, NULL, 'REG-DISPUTED', 0, NULL, 0, NULL);

-- -------------------------------------------------------
-- 2. TESTIMONIALS
-- -------------------------------------------------------
INSERT INTO `testimonials` (`name`, `designation`, `photo`, `content`, `rating`, `is_active`, `sort_order`) VALUES
('Rajesh Malhotra', 'Home Buyer, Delhi', NULL, 'MakaanDekho made finding my dream home incredibly easy. The verified listings gave me confidence, and the EMI calculator helped me plan my budget perfectly!', 5, 1, 1),
('Priya Nair', 'Property Owner, Mumbai', NULL, 'I listed my apartment and got 15 genuine enquiries within the first week. The verification process ensures only serious buyers contact you.', 5, 1, 2),
('Amit Choudhary', 'Real Estate Agent, Gurgaon', NULL, 'As an agent, MakaanDekho has become my primary platform. Dashboard is intuitive, leads are genuine. My sales increased 40%.', 5, 1, 3),
('Sunita Devi', 'First-time Buyer, Patna', NULL, 'First-time buyer here. MakaanDekho guided me through everything. RERA verification helped me avoid fraud.', 4, 1, 4),
('Vikram Builders', 'Builder, Noida', NULL, 'Listed 12 projects, response has been phenomenal. Well-designed platform with professional admin.', 5, 1, 5),
('Neha Kapoor', 'Tenant, Bangalore', NULL, 'Found perfect rental apartment. Detailed filters and direct owner contact saved me from broker fees.', 4, 1, 6);

-- -------------------------------------------------------
-- 3. BANNERS
-- -------------------------------------------------------
INSERT INTO `banners` (`title`, `subtitle`, `image`, `link`, `is_active`, `sort_order`) VALUES
('Find Your Dream Home', 'Discover verified properties across India', 'banner_default_1.jpg', '#', 1, 1),
('Verified Properties Only', 'Every listing is RERA verified', 'banner_default_2.jpg', '#', 1, 2),
('List Your Property Free', 'Reach thousands of genuine buyers', 'banner_default_3.jpg', '#', 1, 3);

-- -------------------------------------------------------
-- 4. ENQUIRIES
-- -------------------------------------------------------
INSERT INTO `enquiries` (`property_id`, `name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
(1, 'Ravi Kumar', 'ravi@email.com', '9898989898', 'Interested in Panchsheel Prime. Share floor plan?', 'new', '2026-04-01 09:30:00'),
(1, 'Anita Singh', 'anita@email.com', '9797979797', 'Any offers? Want to visit this weekend.', 'read', '2026-04-01 14:15:00'),
(1, 'Deepika S', 'deepika@email.com', '9595959595', 'Share brochure and price list for investment.', 'new', '2026-04-02 11:30:00'),
(1, 'Sanjay G', 'sanjay@email.com', '9494949494', 'Is home loan available? Please call me.', 'replied', '2026-04-02 16:00:00'),
(NULL, 'General Visitor', 'visitor@email.com', '9090909090', 'Want to know more about your services.', 'new', '2026-04-03 11:00:00'),
(1, 'Arun T', 'arun@email.com', '9189189189', 'Visiting next week. Please arrange site visit.', 'new', '2026-04-03 12:00:00');

-- -------------------------------------------------------
-- 5. SCHEDULE CALLS
-- -------------------------------------------------------
INSERT INTO `schedule_calls` (`name`, `phone`, `email`, `preferred_date`, `preferred_time`, `message`, `property_id`, `status`) VALUES
('Ravi Kumar', '9898989898', 'ravi@email.com', '2026-04-05', '10 AM - 12 PM', 'Visit Panchsheel Prime', 1, 'new'),
('Anita Singh', '9797979797', 'anita@email.com', '2026-04-06', '2 PM - 4 PM', 'Site visit request', 1, 'new'),
('Sanjay G', '9494949494', 'sanjay@email.com', '2026-04-07', '11 AM - 1 PM', 'Discuss payment plan', 1, 'contacted');

-- -------------------------------------------------------
-- 6. FAVOURITES (existing user IDs: 1-10)
-- -------------------------------------------------------
INSERT INTO `favourites` (`user_id`, `property_id`) VALUES
(1, 1),
(4, 1),
(5, 1),
(8, 1);

-- -------------------------------------------------------
-- 7. MORE BLOGS
-- -------------------------------------------------------
INSERT INTO `blogs` (`title`, `slug`, `category`, `short_description`, `content`, `author_name`, `tags`, `status`, `meta_title`, `meta_description`, `views`, `created_at`) VALUES

('Top 10 Tips for First-Time Home Buyers', 'top-10-tips-home-buyers', 'Tips & Guides', 'Essential tips every first-time buyer should know.', '<h2>1. Fix Your Budget</h2><p>EMI should not exceed 40% of income.</p><h2>2. Check RERA</h2><p>Always verify on state RERA portal.</p><h2>3. Location Matters</h2><p>Proximity to work, schools, transport is key.</p><h2>4. Visit Personally</h2><p>Never buy on photos alone.</p><h2>5. Know All Costs</h2><p>Registration, stamp duty, GST, maintenance add up.</p>', 'MakaanDekho Team', 'home buying,tips,first time', 'published', 'Tips for First-Time Buyers', 'Home buying tips for first timers.', 342, '2026-03-25 10:00:00'),

('RERA: Why It Matters for Buyers', 'rera-why-it-matters', 'Legal', 'Understanding RERA and how it protects you.', '<h2>What is RERA?</h2><p>Real Estate Regulation Act 2016 protects buyers.</p><h2>Benefits</h2><ul><li>Timely delivery</li><li>No false promises</li><li>Carpet area pricing</li><li>5-year defect liability</li></ul><p>Every MakaanDekho listing is RERA verified.</p>', 'Ankit Prasad', 'RERA,legal,verification', 'published', 'RERA Guide', 'RERA Act explained for buyers.', 567, '2026-03-27 14:00:00'),

('Best Localities in Delhi-NCR 2026', 'best-localities-delhi-ncr', 'Real Estate', 'Top investment areas from Gurgaon to Noida.', '<h2>1. Sector 150 Noida</h2><p>Near Jewar Airport. 30-40% growth expected.</p><h2>2. Dwarka Expressway</h2><p>Premium projects from 80L.</p><h2>3. Indirapuram</h2><p>Developed with metro connectivity.</p><h2>4. Knowledge Park</h2><p>Affordable with rental yield.</p>', 'MakaanDekho Team', 'Delhi NCR,investment,localities', 'published', 'Best Delhi-NCR Areas', 'Top investment localities Delhi-NCR.', 891, '2026-03-30 11:00:00'),

('Home Loan EMI Calculator Guide', 'emi-calculator-guide', 'Investment', 'Calculate EMI and plan your budget.', '<h2>EMI Formula</h2><p>EMI = P x r x (1+r)^n / ((1+r)^n - 1)</p><h2>Tips</h2><ul><li>Larger down payment</li><li>Compare bank rates</li><li>750+ credit score</li></ul><p>Use MakaanDekho EMI calculator on every property page!</p>', 'Ankit Prasad', 'EMI,home loan,finance', 'published', 'EMI Guide', 'How to calculate EMI.', 234, '2026-04-01 09:00:00'),

('Interior Design Trends 2026', 'interior-trends-2026', 'Lifestyle', 'Minimalist to smart home trends.', '<h2>1. Minimalist Luxury</h2><p>Clean lines, neutral colors.</p><h2>2. Smart Homes</h2><p>Voice control, automation.</p><h2>3. Biophilic Design</h2><p>Indoor plants, natural materials.</p><h2>4. WFH Spaces</h2><p>Flexible multipurpose rooms.</p>', 'MakaanDekho Team', 'interior,design,trends', 'published', 'Interior Trends 2026', 'Design trends for Indian homes.', 156, '2026-04-02 15:00:00');

-- -------------------------------------------------------
-- 8. UPDATE SETTINGS & CMS
-- -------------------------------------------------------
UPDATE `settings` SET
  `meta_title` = 'MakaanDekho – Find Your Dream Home | Verified Properties',
  `meta_description` = 'India''s trusted real estate portal. RERA verified apartments, villas, plots for sale and rent.',
  `meta_keywords` = 'real estate,property,buy home,rent,RERA verified,MakaanDekho',
  `address` = 'MakaanDekho.in, Tower B, Sector 62, Noida, UP 201301',
  `footer_text` = '© 2026 MakaanDekho. All Rights Reserved.'
WHERE `id` = 1;

UPDATE `cms_pages` SET `content` = '<h2>Welcome to MakaanDekho</h2>
<p>India''s trusted real estate portal connecting buyers, sellers, owners, agents, and builders. Every property is RERA verified.</p>
<h3>Why Choose Us?</h3>
<ul>
<li><strong>RERA Verified</strong> — Mandatory verification for all listings</li>
<li><strong>Multi-Role</strong> — Owners, Agents, Builders, Filers can list</li>
<li><strong>Free to List</strong> — Post properties at no cost</li>
<li><strong>EMI Calculator</strong> — Budget planning on every page</li>
</ul>' WHERE `page_slug` = 'about';
