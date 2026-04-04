# MakaanDekho.in - Complete Use Case Documentation

**Project:** MakaanDekho - India Ka Apna Real Estate Portal  
**Version:** 1.0  
**Last Updated:** April 2025  
**Tech Stack:** PHP 8.x, MySQLi, Bootstrap 5 (Frontend), Bootstrap 4 + AdminLTE (Admin), Tailwind CSS (Homepage)

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [User Roles & Permissions](#2-user-roles--permissions)
3. [Frontend Use Cases](#3-frontend-use-cases)
4. [Admin Panel Use Cases](#4-admin-panel-use-cases)
5. [AJAX Endpoints](#5-ajax-endpoints)
6. [Key Workflows](#6-key-workflows)
7. [Database Tables](#7-database-tables)
8. [File Structure](#8-file-structure)
9. [Configuration & Constants](#9-configuration--constants)
10. [Security Features](#10-security-features)

---

## 1. Project Overview

MakaanDekho is a full-featured real estate web portal that allows users to buy, sell, and rent properties across India. The platform supports three user roles (Owner, Agent, Builder) with an admin approval workflow. Properties go through a moderation process before going live.

### Key Features
- Property listing with advanced search & filters
- User registration with admin approval
- Property posting with multi-step form
- Enquiry/Lead management system
- Blog/Content management
- SEO management per page
- Responsive design (mobile + desktop)
- WhatsApp integration
- Favourite/Save properties
- Location-based property browsing

---

## 2. User Roles & Permissions

### Frontend Users
| Role | Description | Permissions |
|------|-------------|-------------|
| **Owner** | Individual property owners | Post own properties for sale/rent |
| **Agent** | Real estate agents/brokers | List multiple properties for clients |
| **Builder** | Construction companies/developers | List projects and developments |
| **Visitor** | Unauthenticated user | Browse, search, submit enquiry, quick post |

### Admin Users
| Role | Description | Permissions |
|------|-------------|-------------|
| **Admin** | Site administrator | Full access to all admin features |

### User Status Lifecycle
```
Registered (pending) --> Admin Approves --> Active (can login & post)
                    --> Admin Rejects  --> Blocked (cannot login)
Active              --> Admin Blocks   --> Blocked
Blocked             --> Admin Unblocks --> Active
```

---

## 3. Frontend Use Cases

### 3.1 Authentication Module

#### UC-F01: User Registration
- **File:** `register.php`
- **Actor:** Visitor
- **Description:** New user creates an account by providing name, email, phone, password, and selecting a role (Owner/Agent/Builder).
- **Flow:**
  1. Visitor fills registration form
  2. System validates input (email uniqueness, password strength)
  3. Account created with status = `pending`
  4. User sees success message: "Registration successful. Awaiting admin approval."
- **Post-condition:** User cannot login until admin approves

#### UC-F02: User Login
- **File:** `login.php`
- **Actor:** Registered User
- **Description:** User logs into the platform with email and password.
- **Flow:**
  1. User enters email and password
  2. System checks credentials against `users` table
  3. System checks account status (active/pending/blocked)
  4. If pending: "Your account is awaiting approval"
  5. If blocked: "Your account has been blocked"
  6. If active: Session created, redirect to dashboard
- **Session Variables Set:** `user_id`, `user_name`, `user_email`, `user_role`

#### UC-F03: Forgot Password
- **File:** `forgot-password.php`
- **Actor:** Registered User
- **Description:** User resets password via email token.
- **Flow:**
  1. User enters registered email
  2. System generates reset token (valid 1 hour)
  3. Reset link displayed (dev mode) / sent via email (production)
  4. User clicks link, enters new password
  5. Password updated, token invalidated

#### UC-F04: User Logout
- **File:** `logout-user.php`
- **Actor:** Logged-in User
- **Description:** Destroys session and redirects to homepage.

---

### 3.2 Property Browsing

#### UC-F05: Homepage
- **File:** `index.php`
- **Actor:** Any Visitor
- **Description:** Landing page showing property highlights and site features.
- **Sections Displayed:**
  - Hero banner with search (tabs: For Sale / For Rent)
  - Banners slider (from `banners` table)
  - Trending properties (`is_trending = 1`)
  - Recommended properties (`is_recommended = 1`)
  - Featured/High demand properties (`featured = 1`)
  - Newly launched (latest approved)
  - Explore property types (cards)
  - Top agents & builders
  - Trending locations/cities
  - Explore by possession year
  - Top developers
  - Blog articles (latest 2-6)
  - Testimonials
  - Post property CTA
  - Newsletter subscription

#### UC-F06: Property Search & Listing
- **File:** `properties.php`
- **Actor:** Any Visitor
- **Description:** Advanced property search with multiple filters.
- **Available Filters:**
  - Listing type: Sale / Rent
  - Property type: Apartment, Villa, Plot, Commercial, Office
  - Bedrooms: 1, 2, 3, 4, 5+
  - Price range: Min - Max
  - Location / City / Area
  - Furnishing: Furnished, Semi-Furnished, Unfurnished
  - Area (sq ft): Min - Max
  - Featured only / Verified only
- **Sorting Options:** Newest first, Price low-to-high, Price high-to-low, Oldest
- **Pagination:** 10 properties per page
- **Display:** Grid/List view with property cards

#### UC-F07: Property Detail View
- **File:** `property-detail.php`
- **Actor:** Any Visitor
- **Description:** Full property information page.
- **Content Displayed:**
  - Image gallery (main image + thumbnails)
  - Title, price, listing type (Sale/Rent)
  - Location (city, state, area)
  - Bedrooms, bathrooms, area (sq ft)
  - Full description
  - Amenities with icons (parking, gym, pool, etc.)
  - Builder/Owner contact information
  - RERA number, registry details
  - Similar properties in same location
  - Enquiry form (modal popup)
  - Favourite/Save button (toggle)
  - WhatsApp contact button
- **Auto-action:** Increments `views` count on each visit

#### UC-F08: Browse by Location/Market Area
- **File:** `market-area.php`
- **Actor:** Any Visitor
- **Description:** View properties filtered by specific city or locality.
- **Features:**
  - Properties count for the area
  - Pagination support
  - Schema.org structured data for SEO

---

### 3.3 Property Posting

#### UC-F09: Add Property (Authenticated)
- **File:** `add-property.php`
- **Actor:** Logged-in User (Owner/Agent/Builder)
- **Description:** Multi-step property listing form.
- **Steps:**
  1. **Basic Details:** Title, property type, listing type (sale/rent), price, location (city, state, area)
  2. **Property Details:** Bedrooms, bathrooms, area (sq ft), furnishing, floor, total floors, facing, age
  3. **Verification:** RERA number, registry number, possession date
  4. **Amenities:** Checkboxes (parking, gym, swimming pool, garden, security, lift, power backup, etc.)
  5. **Images:** Featured image upload + additional gallery images
- **Post-condition:** Property created with status = `pending`, awaits admin approval
- **Auto-generated:** URL-friendly slug from title

#### UC-F10: Quick Post Property (No Login Required)
- **File:** `post-property.php` + `ajax-post-property.php`
- **Actor:** Any Visitor
- **Description:** Quick property posting via modal form without requiring login.
- **Fields:** Name, phone, email, role, city, state
- **Flow:**
  1. Visitor fills quick form
  2. System creates user record (if new email)
  3. Creates basic property record (pending)
  4. Shows success message
- **Post-condition:** Admin follows up for full property details

---

### 3.4 User Dashboard

#### UC-F11: User Dashboard Overview
- **File:** `dashboard.php`
- **Actor:** Logged-in User
- **Description:** Personal dashboard with statistics and quick navigation.
- **Stats Shown:**
  - Total properties posted
  - Approved properties count
  - Pending properties count
  - Total enquiries received
  - Total views on properties
- **Quick Links:** My Properties, Enquiries, Saved, Profile

#### UC-F12: My Properties
- **File:** `my-properties.php`
- **Actor:** Logged-in User
- **Description:** List of all properties posted by the user.
- **Features:**
  - Filter by status: All, Approved, Pending, Rejected
  - Shows rejection reason (if rejected)
  - Edit property action
  - Delete property action
  - View property on frontend

#### UC-F13: My Enquiries
- **File:** `my-enquiries.php`
- **Actor:** Logged-in User
- **Description:** All enquiries received on user's listed properties.
- **Columns:** Enquirer name, email, phone, property name, message, date
- **Actions:** View property, respond to enquiry

#### UC-F14: My Favourites
- **File:** `my-favourites.php`
- **Actor:** Logged-in User
- **Description:** Properties saved/bookmarked by the user.
- **Features:**
  - Remove from favourites
  - View property detail
  - Shows price, location, bedrooms

#### UC-F15: Edit Profile
- **File:** `profile.php`
- **Actor:** Logged-in User
- **Description:** Update personal information and password.
- **Editable Fields:** Name, phone, city, state
- **Change Password:** Current password verification + new password + confirm
- **Display:** Profile avatar with initials, role badge, member since date

---

### 3.5 Content Pages

#### UC-F16: Blog Listing
- **File:** `blogs.php`
- **Actor:** Any Visitor
- **Description:** Browse all published blog articles.
- **Features:**
  - Filter by category
  - Search blog posts
  - Pagination (9 per page)
  - Sidebar: Recent posts, popular posts, tags
  - Cards with featured image, excerpt, date, view count

#### UC-F17: Blog Detail
- **File:** `blog-detail.php`
- **Actor:** Any Visitor
- **Description:** Read full blog article.
- **Content:** Full article, metadata, related posts, social sharing
- **Auto-action:** Increments view count

#### UC-F18: Contact Us
- **File:** `contact.php`
- **Actor:** Any Visitor
- **Description:** Contact form with company information.
- **Form Fields:** Name, email, phone, subject, message
- **Additional:** Google Maps embed, company address/phone/email from settings, WhatsApp CTA

#### UC-F19: About Us
- **File:** `about.php`
- **Actor:** Any Visitor
- **Description:** Company information page (CMS-managed content)

#### UC-F20: XML Sitemap
- **File:** `sitemap.php`
- **Actor:** Search Engines
- **Description:** Auto-generated XML sitemap for SEO indexing

---

## 4. Admin Panel Use Cases

### 4.1 Authentication

#### UC-A01: Admin Login
- **File:** `admin/login.php`
- **Description:** Admin authentication using email and password.
- **Session Variables:** `admin_id`, `admin_email`, `admin_name`, `role = admin`

#### UC-A02: Admin Logout
- **File:** `admin/logout.php`
- **Description:** Destroys admin session, redirects to login page.

#### UC-A03: Initial Setup
- **File:** `admin/setup.php`
- **Description:** First-time admin account creation. Only works if no admin exists.

---

### 4.2 Dashboard

#### UC-A04: Admin Dashboard
- **File:** `admin/dashboard.php`
- **Description:** Overview page with key performance indicators.
- **KPIs Displayed:**
  - Total properties (by status: pending, approved, rejected)
  - Total registered users
  - Pending user approvals
  - Total enquiries (new, read, replied)
- **Recent Activity:**
  - Latest 8 properties (with status badges)
  - Latest 6 enquiries
- **Alerts:** Pending approvals needing attention

---

### 4.3 Property Management

#### UC-A05: List All Properties
- **File:** `admin/properties.php`
- **Description:** View all properties with status tabs.
- **Tabs:** All | Pending | Approved | Rejected
- **Table Columns:** ID, Property Name, Type, Price (INR), Owner Name, Location, Status Badge, Date
- **Actions per row:** View, Edit, Approve, Reject
- **Reject Modal:** Requires rejection reason text

#### UC-A06: View Property Details
- **File:** `admin/property-view.php`
- **Description:** Full property details view for admin review.
- **Shows:** All property fields, images gallery, owner/agent info, verification docs
- **Actions:** Approve / Reject with reason

#### UC-A07: Add Property (Admin-side)
- **File:** `admin/property-add.php`
- **Description:** Admin can directly add properties (auto-approved).
- **Same form as frontend but with admin-only fields:** Featured toggle, trending toggle, recommended toggle

#### UC-A08: Edit Property
- **File:** `admin/property-edit.php`
- **Description:** Full property editing capability.
- **Editable:** All property fields, status, featured/trending/recommended flags
- **Image Management:** Add new images, delete existing, set primary image, reorder

#### UC-A09: Approve/Reject Property
- **File:** `admin/property-action.php`
- **Description:** Change property status.
- **Actions:**
  - Approve: status = `approved`, property becomes visible on frontend
  - Reject: status = `rejected`, requires rejection_reason text

---

### 4.4 User Management

#### UC-A10: List All Users
- **File:** `admin/users.php`
- **Description:** View all registered users with status filtering.
- **Tabs:** All Users | Pending | Active | Blocked
- **Table Columns:** ID, Name/Email, Phone, Role (dropdown), Properties Count, Status, Actions
- **Role Dropdown:** Can change role (Owner/Agent/Builder) inline

#### UC-A11: User Actions
- **File:** `admin/user-action.php`
- **Description:** Manage user account status.
- **Actions:**
  - **Approve** (pending → active): User can now login
  - **Reject** (pending → blocked): Account denied
  - **Block** (active → blocked): Suspend account
  - **Unblock** (blocked → active): Reactivate account
  - **Change Role:** Update user's role

---

### 4.5 Enquiry/Lead Management

#### UC-A12: View All Enquiries
- **File:** `admin/enquiries.php`
- **Description:** All enquiries/leads from the website.
- **Summary Badges:** New count, Read count, Replied count, Total
- **Table Columns:** ID, Name, Email, Phone, Property, Message (truncated), Status, Date
- **Actions:**
  - View full enquiry (modal popup)
  - Mark as read
  - Mark as replied
  - Delete enquiry
- **Export:** CSV download of all enquiries
- **Filters:** Status filter (new/read/replied)

---

### 4.6 Content Management

#### UC-A13: Blog Management - List
- **File:** `admin/blogs.php`
- **Description:** Manage all blog posts.
- **Tabs:** All | Published | Draft
- **Table Columns:** ID, Featured Image, Title, Category, Status, Views, Created Date
- **Actions:** View on frontend, Edit, Delete (soft delete)

#### UC-A14: Blog Management - Add
- **File:** `admin/blog-add.php`
- **Description:** Create new blog post.
- **Fields:**
  - Title (auto-generates slug)
  - Category (dropdown)
  - Featured image upload
  - Short description / Excerpt
  - Full content (Summernote WYSIWYG editor)
  - Tags (comma separated)
  - SEO: Meta title, Meta description, Meta keywords
  - Status: Draft / Published

#### UC-A15: Blog Management - Edit
- **File:** `admin/blog-edit.php`
- **Description:** Edit existing blog post. All fields modifiable.

#### UC-A16: Blog Management - Delete
- **File:** `admin/blog-delete.php`
- **Description:** Soft delete blog post (sets `is_deleted = 1`, preserves data).

#### UC-A17: CMS Pages - List
- **File:** `admin/pages.php`
- **Description:** View all CMS-managed pages.
- **Columns:** ID, Page Name, Slug, Meta Title, Last Updated

#### UC-A18: CMS Pages - Edit
- **File:** `admin/page-edit.php`
- **Description:** Edit static page content.
- **Editable:** Page name, slug, content (WYSIWYG), meta title, meta description, meta keywords

---

### 4.7 Website Management

#### UC-A19: Homepage Banners
- **File:** `admin/banners.php`
- **Description:** Manage hero section sliders/banners.
- **Fields:** Title, subtitle, image upload, link URL, sort order, active toggle
- **Actions:** Add, Edit, Delete, Toggle active/hidden
- **Image:** Supports JPG, PNG, WebP

#### UC-A20: Testimonials
- **File:** `admin/testimonials.php`
- **Description:** Manage client testimonials shown on homepage.
- **Fields:** Name, designation, photo upload, content/message, rating (1-5 stars), sort order, active toggle
- **Display:** Star rating, circular photo, quote text

#### UC-A21: Location Management
- **File:** `admin/locations.php`
- **Description:** Add/edit/delete cities, states, and areas.
- **Fields:** City, State, Area name
- **Auto-generated:** URL slug from area name
- **Used by:** Property forms (location dropdown), market area pages

#### UC-A22: Mega Menu Builder
- **File:** `admin/mega-menu.php`
- **Description:** Customize navigation dropdown menus.
- **Menu Groups:** For Buyers, For Owners, Insights, Builders & Agents
- **Per Item:** Column heading, item title, URL, column order, item order, active toggle
- **Display:** Frontend header mega dropdown menus

---

### 4.8 System Settings

#### UC-A23: Site Settings
- **File:** `admin/settings.php`
- **Description:** Global site configuration.
- **Settings:**
  - **General:** Site name, contact email, WhatsApp number, address
  - **Branding:** Logo upload, favicon upload
  - **Footer:** Footer text/copyright
  - **Email:** SMTP host, username, password, port
  - **SEO:** Default meta title, meta description, meta keywords
- **Used by:** Header, footer, contact page, email system

---

## 5. AJAX Endpoints

#### UC-AX01: Submit Property Enquiry
- **File:** `ajax-enquiry.php`
- **Method:** POST (JSON response)
- **Input:** property_id, name, email, phone, message
- **Output:** `{success: true/false, message: "..."}`
- **Action:** Creates record in `enquiries` table

#### UC-AX02: Toggle Favourite
- **File:** `ajax-favourite.php`
- **Method:** POST (JSON response)
- **Input:** property_id
- **Requires:** User login (session check)
- **Output:** `{success: true, action: "added"/"removed", message: "..."}`
- **Action:** Adds/removes from `favourites` table

#### UC-AX03: Quick Post Property
- **File:** `ajax-post-property.php`
- **Method:** POST (JSON response)
- **Input:** role, name, phone, email, city, state
- **Output:** `{success: true/false, message: "..."}`
- **Action:** Creates user (if new) + property record, auto-creates location

---

## 6. Key Workflows

### 6.1 User Registration & Approval
```
Visitor --> Register (name, email, phone, password, role)
       --> Account status = PENDING
       --> Admin reviews in Users page
       --> Admin Approves --> status = ACTIVE (user can login)
       --> Admin Rejects  --> status = BLOCKED
```

### 6.2 Property Listing Lifecycle
```
User posts property --> status = PENDING
                   --> Admin reviews in Properties page
                   --> Admin Approves --> status = APPROVED (visible on site)
                   --> Admin Rejects  --> status = REJECTED (with reason)
                   --> User can edit and resubmit
```

### 6.3 Enquiry Flow
```
Visitor views property --> Clicks "Enquire Now"
                       --> Fills form (name, email, phone, message)
                       --> Enquiry saved (status = NEW)
                       --> Admin sees in Enquiries page
                       --> Property owner sees in My Enquiries
                       --> Admin marks as Read / Replied
```

### 6.4 Property Search Flow
```
Visitor --> Homepage search OR Properties page
       --> Applies filters (type, price, location, bedrooms, etc.)
       --> System shows APPROVED properties only
       --> Click property card --> Property detail page
       --> Can: Submit enquiry, Save to favourites, WhatsApp contact
```

### 6.5 Content Publishing
```
Admin --> Blog Add page --> Write post (Summernote editor)
     --> Save as Draft (not visible) OR Publish (visible on blog page)
     --> Edit anytime --> Change status, content, images
     --> Delete --> Soft delete (is_deleted = 1, data preserved)
```

---

## 7. Database Tables

| # | Table Name | Purpose | Key Fields |
|---|-----------|---------|------------|
| 1 | `admin_users` | Admin login accounts | id, email, password, name |
| 2 | `users` | Frontend users | id, name, email, phone, password, role, status |
| 3 | `properties` | Property listings | id, title, slug, type, listing_type, price, status, user_id |
| 4 | `property_images` | Property gallery | id, property_id, image_path, is_primary |
| 5 | `locations` | Cities/areas | id, city, state, area, slug |
| 6 | `enquiries` | Contact/property enquiries | id, property_id, name, email, phone, message, status |
| 7 | `favourites` | Saved properties | id, user_id, property_id |
| 8 | `blogs` | Blog posts | id, title, slug, content, category, status, views |
| 9 | `cms_pages` | Static pages | id, page_name, slug, content, meta_title |
| 10 | `banners` | Homepage sliders | id, title, subtitle, image, link, sort_order, is_active |
| 11 | `testimonials` | Client testimonials | id, name, designation, content, rating, photo |
| 12 | `mega_menu_items` | Navigation menus | id, menu_group, column_heading, title, url, sort_order |
| 13 | `settings` | Site configuration | id, key, value |

### Property Status Values
- `pending` - Awaiting admin review
- `approved` - Live on website
- `rejected` - Declined by admin (has rejection_reason)

### User Status Values
- `pending` - Awaiting admin approval
- `active` - Can login and use platform
- `blocked` - Account suspended

### Enquiry Status Values
- `new` - Unread enquiry
- `read` - Viewed by admin
- `replied` - Responded to

---

## 8. File Structure

```
makaandekho.in/
|
|-- index.php                    # Homepage
|-- login.php                    # User login
|-- register.php                 # User registration
|-- forgot-password.php          # Password reset
|-- logout-user.php              # User logout
|-- dashboard.php                # User dashboard
|-- profile.php                  # User profile
|-- add-property.php             # Add property form
|-- post-property.php            # Quick post property
|-- properties.php               # Property listing/search
|-- property-detail.php          # Single property page
|-- my-properties.php            # User's properties
|-- my-enquiries.php             # User's enquiries
|-- my-favourites.php            # Saved properties
|-- blogs.php                    # Blog listing
|-- blog-detail.php              # Single blog post
|-- contact.php                  # Contact page
|-- about.php                    # About page
|-- market-area.php              # Location-based properties
|-- sitemap.php                  # XML sitemap
|-- ajax-enquiry.php             # AJAX: submit enquiry
|-- ajax-favourite.php           # AJAX: toggle favourite
|-- ajax-post-property.php       # AJAX: quick post
|-- home-page.html               # Static homepage (client review)
|
|-- includes/
|   |-- db.php                   # Database connection & constants
|   |-- header.php               # Frontend header/nav
|   |-- footer.php               # Frontend footer
|   |-- user-auth.php            # User auth helpers
|
|-- admin/
|   |-- login.php                # Admin login
|   |-- logout.php               # Admin logout
|   |-- setup.php                # Initial admin setup
|   |-- dashboard.php            # Admin dashboard
|   |-- properties.php           # Manage properties
|   |-- property-view.php        # View property
|   |-- property-add.php         # Add property
|   |-- property-edit.php        # Edit property
|   |-- property-action.php      # Approve/reject
|   |-- users.php                # Manage users
|   |-- user-action.php          # User actions
|   |-- enquiries.php            # View enquiries
|   |-- blogs.php                # Blog list
|   |-- blog-add.php             # Add blog
|   |-- blog-edit.php            # Edit blog
|   |-- blog-delete.php          # Delete blog
|   |-- pages.php                # CMS pages
|   |-- page-edit.php            # Edit CMS page
|   |-- locations.php            # Manage locations
|   |-- banners.php              # Manage banners
|   |-- testimonials.php         # Manage testimonials
|   |-- mega-menu.php            # Menu builder
|   |-- settings.php             # Site settings
|   |-- includes/
|       |-- auth.php             # Admin auth & utilities
|       |-- header.php           # Admin layout header
|       |-- footer.php           # Admin layout footer
|
|-- uploads/
|   |-- properties/              # Property images
|   |-- blogs/                   # Blog images
|   |-- banners/                 # Banner images
|   |-- testimonials/            # Testimonial photos
|   |-- settings/                # Logo & favicon
|
|-- assets/
|   |-- css/                     # Stylesheets
|   |-- js/                      # JavaScript files
|
|-- database/
|   |-- makaan_dekho.sql          # Main database schema
|   |-- migration_*.sql           # Schema migrations
|
|-- md/
    |-- use-cases.md              # This document
```

---

## 9. Configuration & Constants

Defined in `includes/db.php`:

| Constant | Value | Purpose |
|----------|-------|---------|
| `SITE_ROOT` | `/makaandekho.in` | Root path |
| `ADMIN_PATH` | `/makaandekho.in/admin/` | Admin URL path |
| `BASE_URL` | `http://localhost/makaandekho.in/admin/` | Admin base URL |
| `SITE_URL` | `http://localhost/makaandekho.in` | Frontend base URL |
| `UPLOAD_URL` | `http://localhost/makaandekho.in/uploads/` | Upload directory URL |
| `UPLOAD_DIR` | `D:/xampp/htdocs/makaandekho.in/uploads/` | Upload filesystem path |

### External Dependencies

**Frontend:**
- Bootstrap 5.3.3
- Font Awesome 6.5.1
- Swiper 11
- Google Fonts (Poppins)
- Tailwind CSS (CDN - homepage only)

**Admin Panel:**
- Bootstrap 4.6.2
- Font Awesome 6.4.0
- DataTables 1.13.6
- Summernote 0.8.20 (WYSIWYG editor)

---

## 10. Security Features

| Feature | Implementation |
|---------|---------------|
| CSRF Protection | Token-based forms (`csrf_token()` / `verify_csrf()`) |
| Password Hashing | `password_hash()` with `PASSWORD_DEFAULT` |
| SQL Injection Prevention | Prepared statements (PDO/MySQLi) |
| Input Validation | Email format, phone format, numeric fields |
| Auth Checks | `require_auth()` on all admin pages, `require_user_auth()` on user pages |
| Soft Deletes | `is_deleted` flag instead of permanent deletion |
| Status-based Access | Pending users cannot login, blocked users denied |
| Session Management | Server-side sessions, proper destroy on logout |
| File Upload Validation | Extension whitelist (jpg, png, webp), size limits |

---

## Summary Statistics

| Metric | Count |
|--------|-------|
| Frontend Pages | 18 |
| Admin Pages | 22 |
| AJAX Endpoints | 3 |
| Include Files | 6 |
| Total PHP Files | ~49 |
| Database Tables | 13 |
| User Roles | 3 (Owner, Agent, Builder) |
| Property Types | 5 (Apartment, Villa, Plot, Commercial, Office) |
| Use Cases (Frontend) | 20 |
| Use Cases (Admin) | 23 |
| Use Cases (AJAX) | 3 |
| **Total Use Cases** | **46** |

---

*Document generated for MakaanDekho.in project*
