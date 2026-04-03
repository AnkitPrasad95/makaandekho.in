-- ============================================================
-- Seed: All Property Types & Combinations for Full Testing
-- Database: makaan_dekho
-- Covers every property_type, listing_type, availability,
-- furnishing, price_type, role, status combination
-- ============================================================
USE `makaan_dekho`;

-- ============================================================
-- A. APARTMENTS (all variations)
-- ============================================================

-- 1BHK Apartment | Sale | Ready | Unfurnished | Budget
INSERT INTO `properties` (`title`, `slug`, `short_description`, `description`, `price`, `price_type`, `property_type`, `category`, `listing_type`, `bedrooms`, `bathrooms`, `area_sqft`, `floor`, `total_floors`, `furnishing`, `property_age`, `builder_name`, `contact_person`, `builder_phone`, `builder_email`, `amenities`, `address`, `country`, `pincode`, `location_id`, `user_id`, `status`, `featured`, `is_trending`, `is_recommended`, `availability`, `rera_number`, `registry_number`, `is_verified`, `views`, `created_at`) VALUES
('Shanti Enclave 1BHK - Budget Apartment', 'shanti-enclave-1bhk-budget', 'Affordable 1BHK apartment perfect for bachelors and small families in Sector 62, Noida.', 'Compact and well-designed 1BHK apartment with balcony, modular kitchen platform, and vitrified tiles. Located near Sector 62 Metro Station. Gated society with 24/7 security, water supply, and power backup. Schools and hospitals within 2 km radius.', 2200000.00, 'total', 'apartment', 'Residential', 'sale', 1, 1, 450.00, 3, 8, 'unfurnished', '3-5 years', NULL, 'Priya Sharma', '9876543210', 'priya@gmail.com', '["parking","lift","security","power_backup"]', 'Block C, Shanti Enclave, Sector 62, Noida', 'India', '201309', 9, 4, 'approved', 0, 0, 0, 'available', 'UPRERAPRJ2024-NOI-001', NULL, 1, 45, '2026-03-10 08:00:00'),

-- 2BHK Apartment | Rent | Ready | Furnished | Monthly
('Maple Heights 2BHK - Fully Furnished Rental', 'maple-heights-2bhk-furnished-rent', 'Luxuriously furnished 2BHK for rent in Koramangala with all modern appliances.', 'Move-in ready 2BHK with AC in both bedrooms, washing machine, microwave, refrigerator, LED TV, sofa set, dining table, and king-size beds. Covered parking, gym, pool access included. 5-minute walk to Forum Mall and Wipro office.', 38000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 1100.00, 7, 14, 'furnished', '1-3 years', 'Maple Developers', 'Neha Gupta', '9876543214', 'neha@gmail.com', '["parking","lift","security","power_backup","gym","swimming_pool","ac","wifi","laundry","intercom"]', 'Maple Heights, 4th Cross, Koramangala, Bangalore', 'India', '560034', 4, 8, 'approved', 1, 1, 1, 'available', 'KARERA-PRJ-2023-456', NULL, 1, 132, '2026-03-12 10:00:00'),

-- 3BHK Apartment | Sale | Under Construction | Semi-Furnished
('Godrej Meridien 3BHK - Under Construction', 'godrej-meridien-3bhk-under-construction', 'Premium 3BHK in Dwarka Expressway with 2027 possession. Early bird discount available.', 'Godrej Meridien offers spacious 3BHK apartments with large balconies, servant room, and utility area. Club-class amenities include infinity pool, golf putting green, squash court, and co-working lounge. 5 minutes from proposed metro station. RERA approved project.', 14500000.00, 'total', 'apartment', 'Residential', 'sale', 3, 3, 1950.00, NULL, 30, 'semi-furnished', 'New Construction', 'Godrej Properties', 'Sales Team', '1800111222', 'sales@godrej.com', '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","playground","garden"]', 'Sector 106, Dwarka Expressway, Gurgaon', 'India', '122006', 3, 6, 'approved', 1, 1, 1, 'under_construction', 'HARERA-GGN-2024-0789', NULL, 1, 678, '2026-03-14 09:00:00'),

-- 4BHK Apartment | Sale | New Launch | Unfurnished
('DLF The Ultima 4BHK - New Launch', 'dlf-ultima-4bhk-new-launch', 'Ultra-premium 4BHK penthouse by DLF in Sector 56. Just launched with introductory pricing.', 'DLF The Ultima presents an exclusive collection of 4BHK penthouses with private terrace, double-height living room, imported Italian marble, German modular kitchen, VRV AC, and smart home automation. Dedicated concierge service, helipad, and rooftop restaurant.', 55000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 5, 5200.00, 28, 30, 'unfurnished', 'New Construction', 'DLF Ltd', 'Premium Sales', '1800333444', 'premium@dlf.com', '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","ac","intercom","garden"]', 'DLF The Ultima, Sector 56, Gurgaon', 'India', '122011', 3, 6, 'approved', 1, 1, 1, 'new_launch', 'HARERA-GGN-2026-ULTIMA-01', NULL, 1, 892, '2026-04-01 06:00:00'),

-- 2BHK Apartment | Rent | Ready | Semi-Furnished | Per Month
('Supertech Cape Town 2BHK Rental', 'supertech-cape-town-2bhk-rental', 'Semi-furnished 2BHK available for immediate rent in Sector 74, Noida.', 'Well-maintained 2BHK with wardrobes, kitchen cabinets, geyser, and exhaust fans. Society has swimming pool, badminton court, and jogging track. Near Sector 71 Metro. Preferred: Family or working professionals. No pets. 11-month lease with 2-month security.', 18000.00, 'per_month', 'apartment', 'Residential', 'rent', 2, 2, 980.00, 11, 22, 'semi-furnished', '5-10 years', 'Supertech Ltd', 'Rahul Verma', '9876543211', 'rahul@gmail.com', '["parking","lift","security","power_backup","swimming_pool","playground"]', 'Tower H, Supertech Cape Town, Sector 74, Noida', 'India', '201301', 8, 5, 'approved', 0, 0, 1, 'available', 'UPRERAPRJ2019-NOI-567', NULL, 1, 56, '2026-03-16 11:00:00'),

-- ============================================================
-- B. VILLAS
-- ============================================================

-- 3BHK Villa | Sale | Ready | Furnished
('Palm Springs Villa 3BHK - Gated Community', 'palm-springs-villa-3bhk-gurgaon', 'Elegant 3BHK villa in a gated community on Golf Course Road, Gurgaon.', 'Beautifully designed 3BHK villa with private garden, modular kitchen with chimney, marble flooring, and wooden deck. Gated community with 24/7 security patrol, swimming pool, tennis court, and clubhouse. Walking distance to DLF Golf Course.', 32000000.00, 'total', 'villa', 'Residential', 'sale', 3, 3, 2800.00, NULL, 3, 'furnished', 'Less than 1 year', 'Palm Springs Estates', 'Vikram Malik', '9877111222', 'vikram@palmsprings.com', '["parking","security","swimming_pool","gym","clubhouse","garden","power_backup","cctv"]', 'Palm Springs, Golf Course Road, Gurgaon', 'India', '122002', 3, 5, 'approved', 1, 1, 0, 'available', 'HARERA-GGN-2025-PS-111', 'SD-2026-HR-9876', 1, 234, '2026-03-18 10:00:00'),

-- 5BHK Villa | Sale | Under Construction | Unfurnished
('Prestige Golfshire 5BHK - Premium Villa', 'prestige-golfshire-5bhk-villa', 'Ultra-luxury 5BHK villa in Nandi Hills Road, Bangalore with private pool. Possession 2028.', 'Prestige Golfshire offers premium 5BHK villas spread across 6000 sq.ft with a private infinity pool, landscaped garden, home theatre, wine cellar, and 4-car garage. Overlooks an 18-hole golf course. Smart home with Crestron automation.', 85000000.00, 'total', 'villa', 'Residential', 'sale', 5, 6, 6000.00, NULL, 2, 'unfurnished', 'New Construction', 'Prestige Group', 'Elite Sales', '1800555666', 'elite@prestige.com', '["parking","security","swimming_pool","gym","clubhouse","garden","power_backup","cctv","ac","intercom"]', 'Prestige Golfshire, Nandi Hills Road, Bangalore', 'India', '562157', 4, 6, 'approved', 1, 1, 1, 'under_construction', 'KARERA-PRJ-2025-PG-001', NULL, 1, 445, '2026-03-20 08:00:00'),

-- 4BHK Villa | Rent | Ready | Furnished
('Brigade Orchards 4BHK Villa for Rent', 'brigade-orchards-4bhk-villa-rent', 'Fully furnished 4BHK villa available for rent in Devanahalli, near Bangalore Airport.', 'Premium furnished villa with all bedrooms having attached baths, modular kitchen, car parking for 2, private garden, and servant quarter. Community features include pool, gym, clubhouse, cricket ground, and cycling track. 10 min from airport. Company lease preferred.', 120000.00, 'per_month', 'villa', 'Residential', 'rent', 4, 4, 3200.00, NULL, 2, 'furnished', '3-5 years', 'Brigade Group', NULL, '9878222333', NULL, '["parking","security","swimming_pool","gym","clubhouse","garden","power_backup","cctv","ac"]', 'Brigade Orchards, Devanahalli, Bangalore', 'India', '562110', 4, 8, 'approved', 0, 0, 1, 'available', 'KARERA-PRJ-2022-BO-789', NULL, 1, 78, '2026-03-22 14:00:00'),

-- ============================================================
-- C. PLOTS / LAND
-- ============================================================

-- Residential Plot | Sale | Ready | Authority Approved
('YEIDA Plot 300 Sq.Yd - Sector 22D', 'yeida-plot-300sqyd-sector-22d', 'YEIDA authority approved residential plot in Sector 22D, Yamuna Expressway.', 'Prime residential plot allotted by YEIDA (Yamuna Expressway Industrial Development Authority). Plot is on a 40-ft wide road with all utility connections available. Near proposed Jewar International Airport. Freehold property with clear title. Ideal for building 2-3 floor independent house.', 4500000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 2700.00, NULL, NULL, NULL, NULL, NULL, 'Suresh Yadav', '9876543213', 'suresh@gmail.com', '["parking","security"]', 'Plot 456, Sector 22D, Yamuna Expressway, Greater Noida', 'India', '203135', 10, 7, 'approved', 0, 1, 0, 'available', 'UPRERAPRJ2024-YEA-789', 'REG-UP-2025-7890', 1, 167, '2026-03-19 11:00:00'),

-- Commercial Plot | Sale
('Commercial Plot NH-24 - 500 Sq.Yd', 'commercial-plot-nh24-500sqyd', 'Prime commercial plot on NH-24 highway, Ghaziabad. Ideal for showroom, warehouse, or office.', 'Highway-facing commercial plot on NH-24 near Dasna Toll. 500 sq.yd with 100-ft road frontage. Suitable for petrol pump, showroom, hotel, or warehouse. Commercial zone approved by GDA. All NOCs in place. Immediate possession available.', 25000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 4500.00, NULL, NULL, NULL, NULL, NULL, 'Rahul Verma', '9876543211', 'rahul@gmail.com', '["parking"]', 'NH-24, Near Dasna Toll, Ghaziabad', 'India', '201009', 11, 5, 'approved', 0, 0, 1, 'available', NULL, 'REG-UP-2024-COMM-456', 1, 89, '2026-03-21 09:00:00'),

-- Farm Land | Sale
('10 Acre Farmland - Jaipur Outskirts', 'farmland-10acre-jaipur', 'Beautiful 10-acre farmland with bore well, boundary wall, and mango orchard near Jaipur.', 'Picturesque farmland located 25 km from Jaipur city on Ajmer Highway. Features include bore well, drip irrigation system, 200+ mango trees, boundary wall on all sides, caretaker cottage, and electricity connection. Ideal for farmhouse development or agricultural investment. Clear mutation records.', 15000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 435600.00, NULL, NULL, NULL, NULL, NULL, NULL, '9855666666', NULL, '["garden"]', 'Ajmer Highway, 25 km from Jaipur', 'India', '303007', 17, 4, 'approved', 0, 0, 0, 'available', NULL, 'REG-RJ-2023-FARM-111', 1, 34, '2026-03-23 07:00:00'),

-- ============================================================
-- D. COMMERCIAL PROPERTIES
-- ============================================================

-- Retail Shop | Sale
('High Street Retail Shop - Connaught Place', 'high-street-retail-shop-cp', 'Ground floor retail shop in Connaught Place, Delhi. Prime location with heavy footfall.', 'Ground floor retail space in the iconic Connaught Place. Double-height ceiling (14 ft), glass facade, attached washroom, and storage area in basement. Ideal for fashion brand, electronics showroom, or restaurant. Monthly footfall: 50,000+. Existing brand tenant with 5-year lease generating ₹3.5L/month rent.', 65000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 1200.00, 0, 3, NULL, '10+ years', NULL, 'Priya Sharma', '9876543210', 'priya@gmail.com', '["parking","security","power_backup","cctv","ac"]', 'Block N, Connaught Place, New Delhi', 'India', '110001', 1, 4, 'approved', 1, 0, 1, 'available', NULL, 'SD-2015-DL-CP-789', 1, 456, '2026-03-24 12:00:00'),

-- Showroom | Rent
('Showroom for Rent - MG Road, Gurgaon', 'showroom-rent-mg-road-gurgaon', 'Prime showroom space on MG Road, Gurgaon. Glass facade, high visibility, 2000 sq.ft.', 'Ready-to-use showroom on MG Road with glass frontage, AC ducting done, false ceiling, fire safety compliant, and 4 dedicated car parking. Near MG Road Metro. Suitable for automobile, furniture, electronics, or fashion brand. Lease: 3-year lock-in with 5% annual escalation.', 250000.00, 'per_month', 'commercial', 'Commercial', 'rent', NULL, NULL, 2000.00, 0, 4, NULL, '5-10 years', NULL, 'Rahul Verma', '9876543211', 'rahul@gmail.com', '["parking","security","power_backup","cctv","ac","lift"]', 'MG Road, Gurgaon', 'India', '122001', 3, 5, 'approved', 0, 1, 0, 'available', 'HARERA-GGN-COMM-2024-333', NULL, 1, 198, '2026-03-25 10:00:00'),

-- Warehouse | Sale
('Industrial Warehouse - 10000 Sq.Ft', 'industrial-warehouse-10000sqft-noida', '10,000 sq.ft industrial warehouse in Sector 63, Noida with loading dock and high ceiling.', 'Industrial warehouse with 25-ft clear height, 2 loading docks, 3-phase power (200 KVA), fire suppression system, office cabin, pantry, and security cabin. Located in Noida Phase 3 Industrial Area. Easy access to NH-24 and DND Flyway. NSEZ approved.', 35000000.00, 'total', 'commercial', 'Commercial', 'sale', NULL, NULL, 10000.00, 0, 1, NULL, '5-10 years', NULL, NULL, '9877888999', NULL, '["parking","security","power_backup","cctv"]', 'Block A, Phase 3, Industrial Area, Sector 63, Noida', 'India', '201301', 9, 7, 'approved', 0, 0, 0, 'available', NULL, 'REG-UP-2020-IND-567', 1, 45, '2026-03-26 08:00:00'),

-- ============================================================
-- E. OFFICE SPACES
-- ============================================================

-- Office | Sale | Ready | Furnished
('Co-working Office - 50 Seats Ready', 'coworking-office-50-seats-noida', 'Fully set up 50-seat co-working space for sale in Sector 62, Noida. Running business.', 'Turn-key co-working office with 50 workstations, 3 cabins, 2 conference rooms, reception, pantry with coffee machine, server room, and lounge area. High-speed 100 Mbps internet, EPABX, CCTV, and biometric access. Currently generating ₹4L/month revenue. Sold as running business with existing members.', 12000000.00, 'total', 'office', 'Commercial', 'sale', NULL, NULL, 2800.00, 4, 8, 'furnished', '1-3 years', NULL, 'Neha Gupta', '9876543214', 'neha@gmail.com', '["parking","lift","security","power_backup","ac","wifi","cctv","intercom"]', 'Tech Park, Sector 62, Noida', 'India', '201309', 9, 8, 'approved', 0, 1, 1, 'available', NULL, 'REG-UP-2023-OFF-234', 1, 167, '2026-03-27 11:00:00'),

-- Office | Rent | Ready | Plug & Play
('IT Office Space 5000 Sq.Ft - Salt Lake, Kolkata', 'it-office-5000sqft-salt-lake-kolkata', 'Premium IT office space for rent in Salt Lake, Kolkata. Plug and play with 80 workstations.', 'Grade B+ IT office space in Salt Lake Sector V IT hub. 5000 sq.ft with 80 modular workstations, 4 manager cabins, boardroom for 20, training room, pantry, and server room. 24/7 access, DG backup, housekeeping, and security included in rent. Tax benefits under IT SEZ.', 200000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 5000.00, 3, 6, 'furnished', '3-5 years', 'Salt Lake IT Hub', NULL, '9879333444', NULL, '["parking","lift","security","power_backup","ac","wifi","cctv","intercom","fire_safety"]', 'Block EP, Sector V, Salt Lake, Kolkata', 'India', '700091', 18, 5, 'approved', 0, 0, 1, 'available', 'WBRERA-P01500-2024-567', NULL, 1, 123, '2026-03-28 09:00:00'),

-- Small Office | Rent | Budget
('Small Office Cabin 200 Sq.Ft - Vaishali', 'small-office-cabin-vaishali-ghaziabad', 'Compact furnished office cabin in Vaishali, Ghaziabad. Ideal for startup or freelancer.', 'Ready-to-use private office cabin with 4 workstations, AC, internet, and shared conference room access. Building has lift, parking, security, and power backup. Near Vaishali Metro Station. Includes electricity, water, and housekeeping.', 12000.00, 'per_month', 'office', 'Commercial', 'rent', NULL, NULL, 200.00, 2, 5, 'furnished', '5-10 years', NULL, NULL, '9880444555', NULL, '["parking","lift","security","power_backup","ac","wifi"]', 'Mahagun Metro Mall, Vaishali, Ghaziabad', 'India', '201010', 13, 4, 'approved', 0, 0, 0, 'available', NULL, NULL, 0, 28, '2026-03-29 14:00:00'),

-- ============================================================
-- F. PENDING PROPERTIES (for admin approval testing)
-- ============================================================

-- Pending: Agent submitted apartment
('Nirala Estate 2BHK - Noida Extension', 'nirala-estate-2bhk-noida-ext', 'Affordable 2BHK in Noida Extension by Nirala Group.', 'Budget 2BHK near upcoming Jewar Airport. Under construction with Dec 2027 possession.', 3200000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 850.00, NULL, 16, 'unfurnished', 'New Construction', 'Nirala Group', 'Agent Rahul', '9876543211', 'rahul@gmail.com', '["parking","lift","security","power_backup"]', 'Techzone 4, Greater Noida West', 'India', '201306', 10, 5, 'pending', 0, 0, 0, 'under_construction', 'UPRERAPRJ2025-GNW-999', NULL, 0, 0, '2026-04-02 09:00:00'),

-- Pending: Builder submitted project
('ATS Knightsbridge 4BHK', 'ats-knightsbridge-4bhk', 'Premium 4BHK by ATS in Sector 124, Noida.', 'Luxury 4BHK with servant room, study, and 3 balconies. ATS signature quality.', 22000000.00, 'total', 'apartment', 'Residential', 'sale', 4, 4, 3200.00, NULL, 35, 'semi-furnished', 'New Construction', 'ATS Infrastructure', 'ATS Sales', '1800999888', 'sales@ats.com', '["parking","lift","security","swimming_pool","gym","clubhouse","power_backup","cctv","ac","intercom","garden","playground"]', 'Sector 124, Noida', 'India', '201301', 8, 6, 'pending', 0, 0, 0, 'new_launch', 'UPRERAPRJ2026-NOI-ATS-001', NULL, 0, 0, '2026-04-03 07:00:00'),

-- Pending: Owner submitted villa
('My Personal Villa - Patna', 'personal-villa-patna-owner', 'Owner selling 3BHK villa in Dang Bangla, Patna.', 'Well-maintained villa with garden and car parking. Ready to move. All documents available.', 8500000.00, 'total', 'villa', 'Residential', 'sale', 3, 2, 2200.00, NULL, 2, 'semi-furnished', '5-10 years', NULL, 'Suresh Yadav', '9876543213', 'suresh@gmail.com', '["parking","security","garden","power_backup"]', 'Near Bailey Road, Dang Bangla, Patna', 'India', '800001', 6, 7, 'pending', 0, 0, 0, 'available', NULL, 'REG-BR-2019-VILLA-222', 0, 0, '2026-04-03 08:00:00'),

-- Pending: Filer submitted flat
('Quick Sale 2BHK - Chennai', 'quick-sale-2bhk-chennai', 'Urgent sale: 2BHK flat in Anna Nagar, Chennai.', 'Owner relocating abroad. Price negotiable for quick deal. 2BHK with car parking.', 5500000.00, 'total', 'apartment', 'Residential', 'sale', 2, 2, 1050.00, 5, 12, 'unfurnished', '3-5 years', NULL, 'Suresh Yadav', '9876543213', 'suresh@gmail.com', '["parking","lift","security"]', 'Anna Nagar West, Chennai', 'India', '600040', 16, 7, 'pending', 0, 0, 0, 'available', 'TNRERA-P02400-2023-456', NULL, 0, 0, '2026-04-03 09:00:00'),

-- ============================================================
-- G. REJECTED PROPERTIES (for rejection testing)
-- ============================================================

('Fake Luxury Villa - Delhi', 'fake-luxury-villa-delhi', 'This listing was found to have fraudulent documents.', 'Listing with invalid RERA and fake documents.', 50000000.00, 'total', 'villa', 'Residential', 'sale', 5, 5, 5000.00, NULL, 3, 'furnished', 'New Construction', 'Ghost Builders', NULL, NULL, NULL, '["parking","swimming_pool"]', 'Somewhere in Delhi', 'India', '110001', 1, 10, 'rejected', 'RERA number FAKE-RERA-VILLA-999 does not exist in the UP RERA database. Uploaded documents appear to be fabricated. This is a serious violation. Account has been flagged for review.', 0, 0, 0, 'available', 'FAKE-RERA-VILLA-999', NULL, 0, 0, '2026-03-28 10:00:00'),

('Disputed Land - Ghaziabad', 'disputed-land-ghaziabad', 'Land under legal dispute.', 'Property has ongoing court case.', 3000000.00, 'total', 'plot', 'Plots', 'sale', NULL, NULL, 2000.00, NULL, NULL, NULL, NULL, NULL, NULL, '9880444555', NULL, '[]', 'Disputed Area, Ghaziabad', 'India', '201001', 11, 4, 'rejected', 'Property is under active litigation (Case No. GZB-2024-CV-1234). Properties with ongoing disputes cannot be listed. Please resolve the legal matter and resubmit with court clearance certificate.', 0, 0, 0, 'available', NULL, 'REG-UP-DISPUTED-999', 0, 0, '2026-03-29 09:00:00');

-- ============================================================
-- H. Additional ENQUIRIES for new properties
-- ============================================================
INSERT INTO `enquiries` (`property_id`, `name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
-- Use property IDs that will be assigned (starting from where existing data ends)
((SELECT id FROM properties WHERE slug='godrej-meridien-3bhk-under-construction'), 'Rajat Mehta', 'rajat.mehta@email.com', '9871234567', 'I want to book a 3BHK. What is the payment plan? Any bank tie-ups for home loan?', 'new', '2026-04-03 09:00:00'),
((SELECT id FROM properties WHERE slug='godrej-meridien-3bhk-under-construction'), 'Sneha Kapoor', 'sneha.k@email.com', '9872345678', 'Is sample flat ready for visit? I am available this Saturday.', 'new', '2026-04-03 10:00:00'),
((SELECT id FROM properties WHERE slug='dlf-ultima-4bhk-new-launch'), 'Vikash Agarwal', 'vikash@email.com', '9873456789', 'What are the maintenance charges? Is there any pre-launch offer?', 'new', '2026-04-03 11:00:00'),
((SELECT id FROM properties WHERE slug='palm-springs-villa-3bhk-gurgaon'), 'Ritu Jain', 'ritu.j@email.com', '9874567890', 'We want to visit the villa this weekend. Please arrange a site visit.', 'new', '2026-04-03 12:00:00'),
((SELECT id FROM properties WHERE slug='maple-heights-2bhk-furnished-rent'), 'Karthik R', 'karthik.r@email.com', '9875678901', 'I need the flat from May 1st. Is it available? Any pets allowed?', 'new', '2026-04-03 13:00:00'),
((SELECT id FROM properties WHERE slug='high-street-retail-shop-cp'), 'Sagar Enterprises', 'sagar@enterprise.com', '9876789012', 'Interested in buying the CP shop. What is the current tenant lease term remaining?', 'new', '2026-04-03 14:00:00'),
((SELECT id FROM properties WHERE slug='yeida-plot-300sqyd-sector-22d'), 'Manish Pandey', 'manish.p@email.com', '9877890123', 'Is registry done? What are the development charges? Can I visit the plot?', 'new', '2026-04-03 15:00:00'),
((SELECT id FROM properties WHERE slug='coworking-office-50-seats-noida'), 'StartupHub India', 'ceo@startuphub.in', '9878901234', 'We are interested in acquiring the co-working space. What is the current occupancy rate?', 'new', '2026-04-03 16:00:00');

-- ============================================================
-- I. FAVOURITES for new properties
-- ============================================================
INSERT INTO `favourites` (`user_id`, `property_id`, `created_at`) VALUES
(4, (SELECT id FROM properties WHERE slug='godrej-meridien-3bhk-under-construction'), '2026-04-03 09:00:00'),
(4, (SELECT id FROM properties WHERE slug='dlf-ultima-4bhk-new-launch'), '2026-04-03 09:30:00'),
(5, (SELECT id FROM properties WHERE slug='maple-heights-2bhk-furnished-rent'), '2026-04-03 10:00:00'),
(5, (SELECT id FROM properties WHERE slug='palm-springs-villa-3bhk-gurgaon'), '2026-04-03 10:30:00'),
(8, (SELECT id FROM properties WHERE slug='yeida-plot-300sqyd-sector-22d'), '2026-04-03 11:00:00'),
(8, (SELECT id FROM properties WHERE slug='high-street-retail-shop-cp'), '2026-04-03 11:30:00');
