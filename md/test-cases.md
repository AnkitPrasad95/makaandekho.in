# MakaanDekho.in - Test Cases & Results (v3)

**Project:** MakaanDekho - Real Estate Portal  
**Test Date:** April 4, 2026 (Final)  
**Tested By:** Automated Test Suite (curl + PHP) + Code Verification  
**Environment:** localhost / XAMPP / PHP 8.x / MySQL  

---

## Test Summary

| # | Category | Tests | Pass | Fail |
|---|----------|-------|------|------|
| 1 | Signup Popup Validation | 12 | 12 | 0 |
| 2 | Enquiry Form Validation | 7 | 7 | 0 |
| 3 | Newsletter Validation | 8 | 8 | 0 |
| 4 | Contact Form Validation | 7 | 7 | 0 |
| 5 | Property Search Filters | 9 | 9 | 0 |
| 6 | Frontend Page Loads | 11 | 11 | 0 |
| 7 | Admin Auth Protection | 11 | 11 | 0 |
| 8 | Mega Menu Dynamic | 7 | 7 | 0 |
| 9 | Database Tables | 16 | 16 | 0 |
| 10 | Security Checks | 15 | 15 | 0 |
| 11 | Admin Modal JS Fix | 9 | 9 | 0 |
| 12 | Features Check | 9 | 9 | 0 |
| 13 | Email System | 5 | 5 | 0 |
| 14 | SQL Injection & XSS | 6 | 6 | 0 |
| | **TOTAL** | **132** | **132** | **0** |
| | **Pass Rate** | | **100%** | |

---

## 1. Signup Popup (ajax-post-property.php)

### Input Validation

| # | Test Case | Expected | Result |
|---|-----------|----------|--------|
| T01 | Empty all fields | Reject with errors | PASS |
| T02 | Invalid email format | Reject | PASS |
| T03 | Short phone (3 digits) | Reject | PASS |
| T04 | Non-Indian phone (starts 1) | Reject | PASS |
| T05 | Single char name | Reject (min 2) | PASS |
| T06 | SQL injection in name | Safe via prepared stmt | PASS |

### Success Flow

| # | Test Case | Result |
|---|-----------|--------|
| T06a | Valid new user: success + new_user=true + password | PASS |
| T06b | Existing user: new_user=false, no password | PASS |
| T06c | User status = pending in DB | PASS |
| T06d | Property status = pending in DB | PASS |
| T06e | Password bcrypt hashed ($2y$) | PASS |
| T06f | Registration email sent via SMTP | PASS |

---

## 2. Enquiry Form (ajax-enquiry.php)

| # | Test Case | Expected | Result |
|---|-----------|----------|--------|
| T07 | Empty fields | Reject | PASS |
| T08 | Invalid email | Reject | PASS |
| T09 | Invalid phone | Reject | PASS |
| T10 | Valid enquiry | Success, saved to DB | PASS |
| T10a | SQL injection in message | Safe | PASS |
| T10b | Rate limiting 5/hr per email | Works | PASS |
| T10c | strip_tags() on inputs | Applied | PASS |

---

## 3. Newsletter (ajax-newsletter.php + admin/newsletter.php)

| # | Test Case | Expected | Result |
|---|-----------|----------|--------|
| T11 | Empty email | Reject | PASS |
| T12 | Invalid email | Reject | PASS |
| T13 | Valid subscription | Success | PASS |
| T13a | Duplicate blocked | "Already subscribed" | PASS |
| T13b | Resubscribe after unsubscribe | Reactivated | PASS |
| T13c | Admin page shows subscriber list | DataTable | PASS |
| T13d | CSV export from admin | Downloads CSV | PASS |
| T13e | Success message hides form, auto-resets 4s | Animation | PASS |

---

## 4. Contact Form (contact.php)

| # | Test Case | Expected | Result |
|---|-----------|----------|--------|
| T14 | Empty fields | "Name is required" | PASS |
| T15 | Valid submission | "has been sent" | PASS |
| T15a | Invalid email rejected | Error shown | PASS |
| T15b | Invalid phone rejected | Error shown | PASS |
| T15c | Message min 10 chars | Enforced | PASS |
| T15d | Rate limiting 3/hr per email | Works | PASS |
| T15e | XSS stripped | strip_tags() | PASS |

---

## 5. Property Search Filters (properties.php)

| # | Filter | Results | Result |
|---|--------|---------|--------|
| T16 | type=apartment | 9 | PASS |
| T17 | type=villa | 4 | PASS |
| T18 | type=plot | 3 | PASS |
| T19 | type=commercial | 3 | PASS |
| T20 | type=office | 4 | PASS |
| T21 | city=Noida | 6 | PASS |
| T22 | city=Gurgaon | 4 | PASS |
| T23 | type=apartment&city=Noida | 3 | PASS |
| T24 | No PHP warnings | 0 errors | PASS |

### Supported Parameters

| Param | Alt Param | Description |
|-------|-----------|-------------|
| `property_type` | `type` | apartment, villa, plot, commercial, office |
| `listing_type` | `listing` | sale, rent |
| `city` | - | City name (LIKE match) |
| `q` | `search` | Keyword (title, address, city, area) |
| `bedrooms` | - | 1, 2, 3, 4, 5+ |
| `price_min` / `price_max` | - | Price range |
| `category` | - | Residential, Commercial, Plots |
| `furnishing` | - | furnished, semi-furnished, unfurnished |

---

## 6. Frontend Page Loads

| # | Page | HTTP | Result |
|---|------|------|--------|
| T25 | Homepage `/` | 200 | PASS |
| T26 | `/login.php` | 200 | PASS |
| T27 | `/forgot-password.php` | 200 | PASS |
| T28 | `/contact.php` | 200 | PASS |
| T29 | `/about.php` | 200 | PASS |
| T30 | `/blogs.php` | 200 | PASS |
| T31 | `/properties.php` | 200 | PASS |
| T32 | `/terms.php` | 200 | PASS |
| T33 | `/privacy.php` | 200 | PASS |
| T34 | `/property-detail.php?slug=test` | 200 | PASS |
| T35 | `/register.php` → redirect | 302 | PASS |

---

## 7. Admin Auth Protection

| # | Page | Status | Result |
|---|------|--------|--------|
| T36 | admin/dashboard.php | 302 | PASS |
| T37 | admin/users.php | 302 | PASS |
| T38 | admin/properties.php | 302 | PASS |
| T39 | admin/enquiries.php | 302 | PASS |
| T40 | admin/settings.php | 302 | PASS |
| T41 | admin/blogs.php | 302 | PASS |
| T42 | admin/locations.php | 302 | PASS |
| T43 | admin/banners.php | 302 | PASS |
| T44 | admin/testimonials.php | 302 | PASS |
| T45 | admin/mega-menu.php | 302 | PASS |
| T46 | admin/newsletter.php | 302 | PASS |

---

## 8. Mega Menu Dynamic

| # | Test Case | Result |
|---|-----------|--------|
| T47 | For Buyers shows property title links | PASS |
| T47a | Residential column: property names + city | PASS |
| T47b | Commercial column: property names + city | PASS |
| T47c | Plots column: property names + city | PASS |
| T47d | "View All" link per category | PASS |
| T47e | Links to property-detail.php?slug= | PASS |
| T47f | Auto-updates with new properties | PASS |

---

## 9. Database (16 Tables)

| Table | Rows | Status |
|-------|------|--------|
| admin_users | 1 | PASS |
| banners | 4 | PASS |
| blogs | 6 | PASS |
| cms_pages | 3 | PASS |
| enquiries | 10 | PASS |
| favourites | 4 | PASS |
| locations | 21 | PASS |
| mega_menu_items | 39 | PASS |
| newsletter_subscribers | 4 | PASS |
| properties | 42 | PASS |
| property_documents | 0 | PASS |
| property_images | 3 | PASS |
| schedule_calls | 3 | PASS |
| settings | 1 | PASS |
| testimonials | 6 | PASS |
| users | 13 | PASS |

---

## 10. Security

| # | Check | Result |
|---|-------|--------|
| T48 | No hardcoded email in mailer | PASS |
| T49 | No hardcoded password in mailer | PASS |
| T50 | Frontend logout: session isolation | PASS |
| T51 | Admin logout: session isolation | PASS |
| T52 | SQL injection: prepared statements | PASS |
| T53 | XSS: strip_tags + htmlspecialchars | PASS |
| T54 | Password: bcrypt hashed | PASS |
| T55 | Reset tokens: random_bytes, 48hr expiry | PASS |
| T56 | Phone: Indian regex ^[6-9]\d{9}$ | PASS |
| T57 | Email: FILTER_VALIDATE_EMAIL | PASS |
| T58 | Rate limiting: enquiry 5/hr | PASS |
| T59 | Rate limiting: contact 3/hr | PASS |
| T60 | CSRF tokens on admin forms | PASS |
| T61 | Admin auth on all pages | PASS |
| T62 | SMTP creds from DB only | PASS |

---

## 11. Admin Modal JS Fix

| # | File | Modal Type | Result |
|---|------|-----------|--------|
| T63 | banners.php | Edit/Delete banner | PASS |
| T64 | blog-add.php | Summernote editor | PASS |
| T65 | blog-edit.php | Summernote editor | PASS |
| T66 | enquiries.php | View enquiry detail | PASS |
| T67 | mega-menu.php | Edit menu item | PASS |
| T68 | properties.php | Reject with reason | PASS |
| T69 | property-add.php | Image preview, toggles | PASS |
| T70 | property-edit.php | Image preview, agent | PASS |
| T71 | testimonials.php | Edit/Delete testimonial | PASS |

---

## 12. Features

| # | Feature | Result |
|---|---------|--------|
| T72 | agent_id column in properties | PASS |
| T73 | Agent/Propreneur card on property detail | PASS |
| T74 | Agent dropdown in admin property edit | PASS |
| T75 | Newsletter admin page | PASS |
| T76 | Dynamic mega menu (property titles) | PASS |
| T77 | Newsletter AJAX in footer | PASS |
| T78 | Client-side MKV validator | PASS |
| T79 | Admin favicon from DB settings | PASS |
| T80 | Newsletter in admin sidebar with badge | PASS |

---

## 13. Email System

| # | Test | Result |
|---|------|--------|
| T81 | SMTP from DB settings | PASS |
| T82 | Registration email on signup | PASS |
| T83 | Approval email with set-password link | PASS |
| T84 | Password reset email | PASS |
| T85 | All emails logged | PASS |

---

## 14. SQL Injection & XSS

| # | Attack | Target | Result |
|---|--------|--------|--------|
| T86 | `'; DROP TABLE users;--` | Signup | PASS |
| T87 | `'; DROP TABLE enquiries;--` | Enquiry | PASS |
| T88 | `<script>alert(1)</script>` | Signup | PASS |
| T89 | `<img onerror=alert(1)>` | Contact | PASS |
| T90 | All 16 tables intact after attacks | DB check | PASS |
| T91 | All INSERT/UPDATE use prepared stmts | Code audit | PASS |

---

## End-to-End Flows

### Flow A: Guest → Signup → Approval → Login
```
Guest visits → Clicks "Add listing" → Popup opens →
Validation (client + server) → User created (pending) →
Password shown → Email sent → Admin approves →
Approval email → User sets password → Logs in →
"Add listing" → add-property.php
```

### Flow B: Property Search via Mega Menu
```
Hover "For Buyers" → See property titles by category →
Click property → property-detail.php →
OR Click "View All Residential →" → properties.php?type=apartment
City filter, type filter, combined filters all work
```

### Flow C: Enquiry → Admin
```
Property detail → Enquiry form → Validated → Saved →
Admin sees in Enquiries → View modal shows data →
Export CSV downloads properly
```

### Flow D: Newsletter
```
Footer form → Email validated → AJAX submit →
Success animation → Admin page shows subscribers →
Toggle active/inactive → CSV export
```

### Flow E: Admin Modals
```
All 9 admin pages with popups → jQuery loads first →
$(document).ready() → Data populates correctly
```

---

*Test report v3 — April 4, 2026 | 132 tests | 100% pass rate*
