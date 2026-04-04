# MakaanDekho.in - Complete Use Case Documentation (v3)

**Project:** MakaanDekho - India Ka Apna Real Estate Portal  
**Version:** 3.0  
**Last Updated:** April 4, 2026  
**Tech Stack:** PHP 8.x, PDO/MySQL, Bootstrap 5 (Frontend), Bootstrap 4 (Admin), PHPMailer, Tailwind (Homepage)

---

## Table of Contents

1. [User Roles & Permissions](#1-user-roles--permissions)
2. [Frontend Use Cases](#2-frontend-use-cases)
3. [Admin Panel Use Cases](#3-admin-panel-use-cases)
4. [AJAX Endpoints](#4-ajax-endpoints)
5. [Email System](#5-email-system)
6. [Key Workflows](#6-key-workflows)
7. [Database Tables](#7-database-tables)
8. [File Structure](#8-file-structure)
9. [Security Features](#9-security-features)

---

## 1. User Roles & Permissions

### Frontend Users
| Role | Description | Capabilities |
|------|-------------|-------------|
| **Owner** | Property owner | Post own properties for sale/rent |
| **Agent** | Real estate broker | List multiple properties for clients |
| **Builder** | Developer/Constructor | List projects and developments |
| **Visitor** | Unauthenticated | Browse, search, enquiry, quick signup |

### Admin
| Role | Capabilities |
|------|-------------|
| **Admin** | Full access: properties, users, enquiries, content, settings |

### User Lifecycle
```
Register (popup) → Pending → Admin Approves → Active (can login)
                          → Admin Rejects  → Blocked
Active → Admin Blocks → Blocked → Admin Unblocks → Active
```

---

## 2. Frontend Use Cases

### Authentication (4 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-F01 | User Signup | Popup via `ajax-post-property.php` | Guest fills name/phone/email/city/state, auto-creates account + property, shows password |
| UC-F02 | User Login | `login.php` | Email + password, checks status (active/pending/blocked) |
| UC-F03 | Forgot Password | `forgot-password.php` | Email → reset link (48hr) → set new password |
| UC-F04 | User Logout | `logout-user.php` | Clears user session only (admin stays) |

### Property Browsing (6 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-F05 | Homepage | `index.php` | Dynamic: banner, trending/recommended/featured properties, agents, cities, blogs, testimonials, stats |
| UC-F06 | Property Search | `properties.php` | Filters: type, city, bedrooms, price, furnishing, keywords. Supports `?type=` and `?city=` params |
| UC-F07 | Property Detail | `property-detail.php` | Gallery, specs, amenities, map, agent card (Listing Propreneur), enquiry form |
| UC-F08 | Browse by Location | `market-area.php` | Properties by city/locality |
| UC-F09 | Dynamic Mega Menu | `includes/header.php` | For Buyers: latest 8 property titles per category (Residential/Commercial/Plots) |
| UC-F10 | Property Post (Quick) | Popup modal | Guest posts property without login, account auto-created |

### User Dashboard (5 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-F11 | Dashboard | `dashboard.php` | Stats: properties, enquiries, views |
| UC-F12 | My Properties | `my-properties.php` | List, filter by status, edit, delete |
| UC-F13 | My Enquiries | `my-enquiries.php` | Enquiries received on user's properties |
| UC-F14 | My Favourites | `my-favourites.php` | Saved/bookmarked properties |
| UC-F15 | Profile | `profile.php` | Edit name/phone/city, change password |

### Content Pages (6 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-F16 | Blog Listing | `blogs.php` | Filter by category, search, pagination, sidebar |
| UC-F17 | Blog Detail | `blog-detail.php` | Full article, related posts, view count |
| UC-F18 | Contact Us | `contact.php` | Form (validated, rate limited), Google Maps, WhatsApp |
| UC-F19 | About Us | `about.php` | CMS-managed content |
| UC-F20 | Terms of Use | `terms.php` | CMS or default content, dynamic contact info |
| UC-F21 | Privacy Policy | `privacy.php` | CMS or default content, dynamic contact info |

### Lead Capture (3 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-F22 | Property Enquiry | `ajax-enquiry.php` | AJAX form on property detail, validated, rate limited |
| UC-F23 | Newsletter Subscribe | `ajax-newsletter.php` | Footer form, AJAX, duplicate check, success animation |
| UC-F24 | Favourite Toggle | `ajax-favourite.php` | Save/unsave property (logged in users) |

---

## 3. Admin Panel Use Cases

### Dashboard (1 use case)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A01 | Dashboard | `admin/dashboard.php` | KPIs: properties (pending/approved/rejected), users, enquiries |

### Property Management (5 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A02 | List Properties | `admin/properties.php` | Tabs: All/Pending/Approved/Rejected, reject modal |
| UC-A03 | View Property | `admin/property-view.php` | Full details, images, owner info |
| UC-A04 | Add Property | `admin/property-add.php` | Admin-side property add |
| UC-A05 | Edit Property | `admin/property-edit.php` | All fields + agent assignment + featured/trending toggles |
| UC-A06 | Approve/Reject | `admin/property-action.php` | Change status, rejection reason |

### User Management (2 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A07 | List Users | `admin/users.php` | Tabs: All/Pending/Active/Blocked, role dropdown |
| UC-A08 | User Actions | `admin/user-action.php` | Approve (sends email), reject, block, unblock, role change |

### Lead Management (2 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A09 | Enquiries | `admin/enquiries.php` | View modal, mark read, CSV export (fixed: before HTML) |
| UC-A10 | Newsletter | `admin/newsletter.php` | Subscriber list, toggle active, delete, CSV export, stats |

### Content Management (7 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A11 | Blog List | `admin/blogs.php` | All/Published/Draft tabs |
| UC-A12 | Blog Add | `admin/blog-add.php` | Summernote editor, SEO fields, categories |
| UC-A13 | Blog Edit | `admin/blog-edit.php` | All fields editable |
| UC-A14 | CMS Pages | `admin/pages.php` + `page-edit.php` | Edit About, Contact, etc. |
| UC-A15 | Banners | `admin/banners.php` | Hero slider: title, subtitle, image, sort, active |
| UC-A16 | Testimonials | `admin/testimonials.php` | Name, rating, photo, content |
| UC-A17 | Mega Menu | `admin/mega-menu.php` | Navigation dropdown builder |

### System (2 use cases)
| # | Use Case | File | Description |
|---|----------|------|-------------|
| UC-A18 | Settings | `admin/settings.php` | Site name, logo, favicon, contact, SMTP, social media, SEO |
| UC-A19 | Locations | `admin/locations.php` | Add/edit cities, states, areas |

---

## 4. AJAX Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `ajax-post-property.php` | POST | No | Signup + property post, validates, sends email in background |
| `ajax-enquiry.php` | POST | No | Property enquiry, validates, rate limited (5/hr) |
| `ajax-newsletter.php` | POST | No | Newsletter subscribe, duplicate check |
| `ajax-favourite.php` | POST | Yes | Toggle save/unsave property |

---

## 5. Email System

### Email Triggers
| Trigger | Template | Sent To |
|---------|----------|---------|
| New signup (popup) | "Registration Received" | User email |
| Admin approves user | "Account Approved" + set password link (48hr) | User email |
| Forgot password | "Password Reset" + reset link (48hr) | User email |

### Technical Details
| Feature | Implementation |
|---------|---------------|
| Library | PHPMailer 7.x via Composer |
| SMTP | Gmail (configurable from Admin Settings) |
| Credentials | Database only, no hardcoded values |
| Fallback | Graceful fail + log if SMTP not configured |
| Logging | `logs/email.log` — all sends logged |
| Background | Signup email sent after HTTP response (no delay) |
| Templates | HTML email with branded header/footer |

---

## 6. Key Workflows

### A: Guest → Signup → Approval → Login
```
1. Guest clicks "Add listing"         → Popup opens
2. Fills form (validated client+server) → Submits
3. User created (status=pending)       → Password shown in popup
4. Registration email sent             → "Account under review"
5. Admin approves in Users page        → Status → active
6. Approval email sent                 → "Set Password & Login" link
7. User clicks link                    → Sets own password
8. User logs in                        → Dashboard
9. Clicks "Add listing"               → add-property.php (direct)
```

### B: Property Lifecycle
```
User/Guest posts property → status=pending →
Admin reviews → Approves (status=approved, visible on site)
             → Rejects (status=rejected, reason shown to user)
```

### C: Enquiry Flow
```
Visitor views property → Fills enquiry form (validated) →
Saved to DB (status=new) → Admin sees in Enquiries →
View detail modal → Mark read → Export CSV
```

### D: Mega Menu (Dynamic)
```
Header loads → Fetches latest properties per category →
Residential: 8 latest apartment/villa titles + city →
Commercial: 8 latest commercial/office titles + city →
Plots: 8 latest plot titles + city →
Each column has "View All →" link to filtered properties page
```

### E: Newsletter
```
Footer form (all pages) → Email validated → AJAX submit →
Success: form hides, green message, auto-resets 4s →
Duplicate: "Already subscribed" error →
Admin: subscriber list, toggle, delete, CSV export
```

---

## 7. Database Tables (16)

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `admin_users` | Admin login | id, email, password, name |
| `users` | Frontend users | id, name, email, phone, city, state, role, status, password, reset_token |
| `properties` | Property listings | id, title, slug, type, price, status, user_id, **agent_id**, featured, is_trending |
| `property_images` | Gallery | id, property_id, image, is_primary |
| `property_documents` | Docs | id, property_id, document |
| `locations` | Cities/areas | id, city, state, area, slug |
| `enquiries` | Leads | id, property_id, name, email, phone, message, status |
| `favourites` | Saved props | id, user_id, property_id |
| `blogs` | Blog posts | id, title, slug, content, category, status, views |
| `cms_pages` | Static pages | id, page_name, slug, content, meta_title |
| `banners` | Hero sliders | id, title, subtitle, image, link, sort_order, is_active |
| `testimonials` | Reviews | id, name, designation, content, rating, photo |
| `mega_menu_items` | Nav menus | id, menu_slug, column_heading, item_title, item_url |
| `settings` | Config | site_name, logo, favicon, email, phone, whatsapp, smtp_*, social_*, meta_* |
| `newsletter_subscribers` | Newsletter | id, email, subscribed_at, is_active |
| `schedule_calls` | Call requests | id, name, phone, date, time |

---

## 8. File Structure

```
makaandekho.in/
├── index.php                    # Dynamic homepage
├── login.php                    # User login
├── register.php                 # Redirects to popup (?register=1)
├── forgot-password.php          # Password reset (email + token)
├── logout-user.php              # Session isolation logout
├── dashboard.php                # User dashboard
├── profile.php                  # User profile
├── add-property.php             # Add property (authenticated)
├── post-property.php            # Property post form
├── properties.php               # Search/listing (type, city, filters)
├── property-detail.php          # Detail + agent card + enquiry
├── my-properties.php            # User's properties
├── my-enquiries.php             # User's enquiries
├── my-favourites.php            # Saved properties
├── blogs.php                    # Blog listing
├── blog-detail.php              # Blog post
├── contact.php                  # Contact form (validated, rate limited)
├── about.php                    # About page (CMS)
├── market-area.php              # Location-based properties
├── terms.php                    # Terms of Use
├── privacy.php                  # Privacy Policy
├── sitemap.php                  # XML sitemap
├── ajax-enquiry.php             # AJAX: enquiry (validated)
├── ajax-favourite.php           # AJAX: toggle favourite
├── ajax-post-property.php       # AJAX: signup + property post
├── ajax-newsletter.php          # AJAX: newsletter subscribe
│
├── includes/
│   ├── db.php                   # PDO connection + settings
│   ├── header.php               # Frontend header + mega menu + popup
│   ├── footer.php               # Dynamic footer + newsletter + MKV validator
│   ├── user-auth.php            # User auth helpers
│   └── mailer.php               # PHPMailer SMTP (creds from DB)
│
├── admin/
│   ├── login.php / logout.php   # Admin auth (session isolation)
│   ├── dashboard.php            # KPI dashboard
│   ├── properties.php           # Manage properties + reject modal
│   ├── property-view.php        # View property
│   ├── property-add.php         # Add property
│   ├── property-edit.php        # Edit + agent assignment
│   ├── property-action.php      # Approve/reject
│   ├── users.php                # Manage users
│   ├── user-action.php          # Approve/block + send email
│   ├── enquiries.php            # Enquiries + CSV export
│   ├── newsletter.php           # Newsletter subscribers + CSV
│   ├── blogs.php / blog-add/edit/delete.php  # Blog CRUD
│   ├── pages.php / page-edit.php # CMS pages
│   ├── locations.php            # City/area management
│   ├── banners.php              # Hero sliders
│   ├── testimonials.php         # Testimonials
│   ├── mega-menu.php            # Navigation builder
│   ├── settings.php             # Site config + SMTP + social + SEO
│   └── includes/
│       ├── auth.php             # Admin auth + CSRF + flash
│       ├── header.php           # Admin sidebar + topbar + favicon
│       └── footer.php           # Admin scripts (jQuery, DataTables, Summernote)
│
├── uploads/
│   ├── properties/              # Property images
│   ├── blogs/                   # Blog images
│   ├── banners/                 # Banner images
│   ├── testimonials/            # Photos
│   ├── settings/                # Logo + favicon
│   └── users/                   # Agent photos
│
├── assets/
│   ├── css/                     # style.css, style2.css, animate.css
│   ├── js/                      # main.js
│   ├── fonts/                   # Font Awesome Pro 5
│   └── img/                     # Static images
│
├── vendor/                      # Composer (PHPMailer)
├── logs/                        # email.log
├── md/                          # Documentation
│   ├── use-cases.md             # This document
│   └── test-cases.md            # Test report (132 tests, 100%)
└── database/                    # SQL migrations
```

---

## 9. Security Features

| Feature | Implementation |
|---------|---------------|
| SQL Injection | PDO Prepared Statements on all queries |
| XSS Prevention | `strip_tags()` on input + `htmlspecialchars()` on output |
| Password Storage | `password_hash()` with bcrypt |
| Reset Tokens | `bin2hex(random_bytes(32))`, 48-hour expiry |
| CSRF Protection | Token-based on all admin POST forms |
| Phone Validation | Indian regex `/^[6-9][0-9]{9}$/` |
| Email Validation | `FILTER_VALIDATE_EMAIL` |
| Rate Limiting | Enquiry: 5/hr, Contact: 3/hr per email |
| Session Isolation | Frontend/Admin logout independent |
| SMTP Security | Credentials from DB only, no hardcoded values |
| Admin Auth | `require_auth()` on all 11 admin pages (302 redirect) |
| File Uploads | Extension whitelist (jpg, png, webp) |
| Client Validation | MKV JavaScript validator (req, email, phone, name, safe) |
| Admin Modals | jQuery loads before inline scripts (9 files fixed) |

---

## Summary Statistics

| Metric | Count |
|--------|-------|
| Frontend Pages | 22 |
| Admin Pages | 21 |
| AJAX Endpoints | 4 |
| Include Files | 8 |
| **Total PHP Files** | **~55** |
| Database Tables | 16 |
| User Roles | 3 (Owner, Agent, Builder) |
| Property Types | 5 (Apartment, Villa, Plot, Commercial, Office) |
| Use Cases (Frontend) | 24 |
| Use Cases (Admin) | 19 |
| **Total Use Cases** | **43** |
| Test Cases | 132 (100% pass) |

---

*Document v3 — April 4, 2026 — MakaanDekho.in*
