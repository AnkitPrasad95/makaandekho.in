# MakaanDekho.in - Real Estate Platform

A full-featured real estate listing platform built with PHP, MySQL, and Bootstrap. Users can list, search, and enquire about properties across India.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x |
| Database | MySQL / MariaDB |
| Frontend | Bootstrap 5.3, Font Awesome 6.5, Swiper 11 |
| Mail | PHPMailer 7.0 (SMTP) |
| Server | Apache (XAMPP) |
| Maps | Google Places API (Autocomplete) |

---

## Directory Structure

```
makaandekho.in/
├── index.php                    # Homepage
├── properties.php               # Property listing with filters
├── property-detail.php          # Single property view
├── add-property.php             # User: multi-step property form
├── post-property.php            # User: property posting (logged in)
├── locations.php                # All locations page
├── market-area.php              # Location-based SEO pages
├── blogs.php                    # Blog listing
├── blog-detail.php              # Blog article
├── contact.php                  # Contact form
├── about.php / terms.php        # Static CMS pages
├── login.php / register.php     # Authentication
├── forgot-password.php          # Password reset
├── dashboard.php                # User dashboard
├── my-properties.php            # User property management
├── my-enquiries.php             # User enquiries
├── my-favourites.php            # User saved properties
├── profile.php                  # User profile
├── sitemap.php                  # XML sitemap generator
│
├── ajax-enquiry.php             # AJAX: submit enquiry
├── ajax-favourite.php           # AJAX: toggle favorite
├── ajax-newsletter.php          # AJAX: newsletter signup
├── ajax-post-property.php       # AJAX: quick property post
│
├── admin/
│   ├── dashboard.php            # Admin dashboard
│   ├── properties.php           # Property management
│   ├── property-add.php         # Add property (admin)
│   ├── property-edit.php        # Edit property
│   ├── property-view.php        # View property details
│   ├── users.php                # User management
│   ├── enquiries.php            # Enquiry management
│   ├── blogs.php                # Blog management
│   ├── locations.php            # Location management
│   ├── banners.php              # Banner management
│   ├── testimonials.php         # Testimonial management
│   ├── mega-menu.php            # Navigation menu
│   ├── newsletter.php           # Newsletter management
│   ├── pages.php                # CMS pages
│   ├── settings.php             # Site settings
│   └── includes/
│       ├── auth.php             # Admin auth
│       ├── header.php           # Admin layout
│       └── footer.php           # Admin layout
│
├── includes/
│   ├── db.php                   # Database connection & constants
│   ├── header.php               # Frontend header & nav
│   ├── footer.php               # Frontend footer
│   ├── user-auth.php            # User auth helpers
│   └── mailer.php               # PHPMailer config
│
├── assets/
│   ├── css/style.css            # Main stylesheet
│   ├── css/style2.css           # Additional styles
│   ├── js/main.js               # Main JavaScript
│   └── img/                     # Static images
│
├── uploads/                     # User-uploaded files
│   ├── properties/              # Property images
│   ├── blogs/                   # Blog images
│   ├── banners/                 # Banner images
│   ├── testimonials/            # Testimonial photos
│   ├── documents/               # Property documents
│   └── settings/                # Logo, favicon
│
├── database/
│   └── makaan_dekho.sql         # Database schema & seed data
│
├── vendor/                      # Composer (PHPMailer)
├── logs/email.log               # Email logs
├── .htaccess                    # URL rewriting
├── composer.json
├── USE_CASES.md                 # Use case documentation
└── PROJECT.md                   # This file
```

---

## Database Tables (16)

| Table | Purpose |
|-------|---------|
| `users` | Registered users (owner, agent, builder, filer) |
| `admin_users` | Admin accounts |
| `properties` | Property listings with all details |
| `property_images` | Gallery images per property |
| `property_documents` | Uploaded documents per property |
| `locations` | Cities, areas, states with SEO slugs |
| `favourites` | User saved properties |
| `enquiries` | Property & general enquiries |
| `blogs` | Blog articles with SEO meta |
| `cms_pages` | Editable content pages |
| `banners` | Homepage hero slider |
| `testimonials` | Customer reviews |
| `mega_menu_items` | Navigation menu structure |
| `newsletter_subscribers` | Email subscribers |
| `schedule_calls` | Call scheduling (future) |
| `settings` | Site configuration |

---

## User Roles

| Role | Access |
|------|--------|
| **Visitor** | Browse, search, enquire, read blogs |
| **Owner** | List properties, manage listings, view enquiries |
| **Agent** | Same as owner, represents clients |
| **Builder** | Same as owner, bulk listings |
| **Admin** | Full management panel access |

---

## Key Features

### Property Management
- Multi-step property submission with image & document uploads
- Admin approval workflow (pending → approved/rejected)
- RERA/Registry verification
- Featured, Trending, Recommended flags
- View counter per property
- 15+ amenities selection

### Search & Discovery
- Full-text search (title, address, city, area)
- Filters: type, BHK, price, area, furnishing, availability
- Google Places autocomplete on location fields
- Sort by newest, price (low/high)
- Pagination (10 per page)

### SEO
- Clean URLs: `/location-slug/property-slug`
- Auto-generated XML sitemap (`/sitemap.xml`)
- Dynamic meta tags (title, description, keywords, canonical)
- Open Graph tags for social sharing
- Schema.org structured data (RealEstateListing, ItemList)
- Market area pages per location
- 301 redirects from old to new URLs

### User Features
- Dashboard with stats (properties, enquiries, views)
- Favorites/wishlist with AJAX toggle
- Enquiry management
- Profile editing & password change
- Quick property posting without login (auto-registration)

### Admin Panel
- Dashboard with overview statistics
- Property CRUD with approval workflow
- User management (approve/block)
- Blog CMS with rich editor
- Location, banner, testimonial, mega menu management
- Newsletter subscriber management
- Enquiry management with CSV export
- Site settings (logo, SMTP, social links, SEO defaults)

### Email System
- PHPMailer with SMTP
- Configurable from admin settings
- Triggers: registration, password reset, enquiry notification
- Error logging to `/logs/email.log`

---

## URL Routing (.htaccess)

| URL Pattern | Destination |
|-------------|------------|
| `/sitemap.xml` | `sitemap.php` |
| `/property/{slug}` | `property-detail.php?slug={slug}` (301 redirects to location URL) |
| `/blog/{slug}` | `blog-detail.php?slug={slug}` |
| `/{location-slug}/{property-slug}` | `property-detail.php?location={loc}&slug={prop}` |
| `/{location-slug}` | `market-area.php?slug={slug}` (falls back to `{slug}.php`) |

---

## Security

- **SQL Injection**: All queries use PDO prepared statements
- **XSS**: Output escaped with `htmlspecialchars()`, numeric inputs cast to `(int)`
- **CSRF**: Token-based protection on admin forms
- **Password**: bcrypt hashing (`PASSWORD_DEFAULT`)
- **Rate Limiting**: Enquiry & contact forms limited per email per hour
- **Input Validation**: Server-side validation on all forms (email, phone, name, length)
- **File Upload**: Type validation (JPG, PNG, WebP only)
- **Soft Delete**: `is_deleted` flag + `deleted_at` timestamp on all tables
- **Security Headers**: X-Content-Type-Options, X-Frame-Options, X-XSS-Protection
- **File Access Block**: `.sql`, `.log`, `.env`, `.md` files blocked via `.htaccess`

---

## Setup

1. Import `database/makaan_dekho.sql` into MySQL
2. Update `includes/db.php` with database credentials
3. Configure SMTP settings from Admin → Settings
4. Set Google Maps API key in `includes/footer.php`
5. Ensure `uploads/` directories are writable
6. Run `composer install` for PHPMailer

---

## Environment

- **Local**: `http://localhost/makaandekho.in/`
- **Admin**: `http://localhost/makaandekho.in/admin/`
- **Sitemap**: `http://localhost/makaandekho.in/sitemap.xml`
