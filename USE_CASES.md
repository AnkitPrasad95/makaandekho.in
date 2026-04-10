# MakaanDekho.in - Use Cases

## User Roles

| Role | Description |
|------|-------------|
| **Visitor** | Browse properties, search, view details (no login required) |
| **Owner** | Property owner who lists their own properties |
| **Agent** | Real estate agent who lists properties for clients |
| **Builder** | Development company with bulk property listings |
| **Admin** | Super admin with full management access |

---

## Visitor Use Cases

### UC-01: Search Properties
- Visit homepage or properties page
- Use search bar with Google Places autocomplete for location
- Apply filters: property type, BHK, price range, area, furnishing, availability
- Sort results by newest, price (low/high)
- View property cards with thumbnail, price, location, BHK

### UC-02: View Property Details
- Click any property card to view full details
- SEO-friendly URL: `/delhi-connaught-place/3bhk-apartment-cp`
- View gallery images, price, specifications, amenities
- See builder/owner contact info
- View Google Map embed location
- Browse similar properties in same location

### UC-03: Submit Enquiry
- Open property detail page
- Fill enquiry form: name, email, phone, message
- Rate limited: max 5 enquiries per email per hour
- Owner/agent receives email notification

### UC-04: Quick Property Posting (No Login)
- Click "Post Your Property" on homepage
- Fill modal: role (owner/agent/builder), name, phone, email, city, state
- System auto-creates user account with generated password
- Property created as "pending" for admin approval
- User receives email with login credentials

### UC-05: Browse Locations
- Visit `/locations` page to see all locations grouped by state
- Click any location to view market area page (`/delhi-connaught-place`)
- See all properties in that location with pagination

### UC-06: Read Blogs
- Browse blog articles on `/blogs`
- Filter by category: Real Estate, Tips & Guides, Legal, Investment, Lifestyle
- Search blogs by title or tags
- View full article with related posts

### UC-07: Contact Support
- Visit contact page
- Submit form: name, email, phone, subject, message
- Rate limited: max 3 messages per email per hour

### UC-08: Subscribe to Newsletter
- Enter email in newsletter form (homepage footer)
- Receive property updates and market news

---

## Registered User Use Cases

### UC-09: Register & Login
- Register via "Post Your Property" modal (auto-registration)
- Login with email and password
- Reset password via forgot password flow (email-based token, 48hr expiry)

### UC-10: Add Property (Full Form)
- Login and navigate to "Add Property"
- Multi-step form:
  1. Basic Details: title, type, listing type, price
  2. Location: state, city, area, address (Google autocomplete)
  3. RERA/Registry verification number
  4. Specifications: BHK, bathrooms, area, floor, furnishing
  5. Amenities: parking, lift, gym, pool, garden, etc.
  6. Gallery: featured image + multiple photos
  7. Documents: agreements, floor plans
- Property submitted as "pending" for admin review

### UC-11: Manage My Properties
- View all listed properties with status (approved/pending/rejected)
- Edit property details
- Track views on each property

### UC-12: View My Enquiries
- See all enquiries received on own properties
- View enquirer's name, email, phone, message
- Contact enquirer directly via call/email

### UC-13: Save Favorite Properties
- Click heart icon on any property card (requires login)
- Toggle favorite on/off via AJAX
- View all saved properties on "My Favorites" page
- Remove from favorites anytime

### UC-14: Edit Profile
- Update name, phone, city, state
- Change password with confirmation

---

## Admin Use Cases

### UC-15: Dashboard Overview
- View total properties, users, enquiries statistics
- See pending properties and users awaiting approval
- Quick access to recent properties and enquiries

### UC-16: Property Management
- View all properties with filter tabs (All/Pending/Approved/Rejected)
- Approve or reject pending properties with reason
- Mark properties as Featured/Verified, Trending, Recommended
- Edit any property details, images, documents
- Add properties directly (bypasses pending status)
- Assign agents to properties

### UC-17: User Management
- View all registered users with status tabs
- Approve pending user registrations
- Block/unblock users
- View user's property count

### UC-18: Content Management
- **Blogs**: Create, edit, publish/draft blog articles with SEO meta
- **CMS Pages**: Edit About, Terms, Privacy page content
- **Banners**: Manage homepage hero slider images
- **Testimonials**: Add/edit customer reviews with ratings
- **Mega Menu**: Configure navigation menu structure

### UC-19: Enquiry Management
- View all enquiries with status (New/Read/Replied)
- Mark enquiries as read
- Export enquiries to CSV
- Contact enquirers directly

### UC-20: Location Management
- Add new cities/areas with auto-generated slugs
- Edit existing locations
- Delete locations (soft delete)
- Each location generates SEO market area page

### UC-21: Newsletter Management
- View subscriber list
- Send bulk newsletter emails
- Export subscriber emails
- Manage active/inactive subscribers

### UC-22: Site Settings
- Update site name, logo, favicon
- Configure SMTP email settings
- Set social media links
- Update default SEO meta tags
- Manage contact information

---

## SEO Use Cases

### UC-23: Sitemap Generation
- Auto-generated XML sitemap at `/sitemap.xml`
- Includes: static pages, all locations, all properties, all blogs
- Properties use SEO URL format: `/location-slug/property-slug`
- Last modified dates from database

### UC-24: Market Area Pages
- Each location gets dedicated SEO page: `/delhi-connaught-place`
- Dynamic meta title, description, keywords per location
- Schema.org structured data (ItemList with RealEstateListing)
- Canonical URLs prevent duplicate content
- Internal linking from locations page and footer

### UC-25: Property SEO URLs
- Format: `/location-slug/property-slug`
- Old `/property/slug` URLs auto 301-redirect to new format
- Canonical tags on all property pages
- Open Graph tags for social sharing

---

## Data Flow Diagrams

### Property Listing Flow
```
Visitor/User submits property
    |
    v
Property created (status: pending)
    |
    v
Admin reviews property
    |
    +---> Approved --> Visible on site
    |
    +---> Rejected --> User notified with reason
```

### Enquiry Flow
```
Visitor views property
    |
    v
Submits enquiry form (name, email, phone, message)
    |
    v
Stored in database (status: new)
    |
    v
Email notification sent to property owner/agent
    |
    v
Admin can view, mark as read, export
Owner can view in "My Enquiries"
```

### User Registration Flow
```
Visitor clicks "Post Your Property"
    |
    v
Fills modal form (name, phone, email, city, state)
    |
    v
System checks if email exists
    |
    +---> New user: creates account, generates password, sends email
    |
    +---> Existing user: creates property under existing account
    |
    v
Property created (pending), user redirected to success screen
```
