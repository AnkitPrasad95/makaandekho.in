<?php
require_once __DIR__ . '/includes/db.php';

// ---- Fetch data for homepage ----

// Locations for "Explore Property Types"
$stmtLoc = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY city ASC LIMIT 8");
$locations = $stmtLoc->fetchAll();

// Trending properties (approved + is_trending)
$stmtTrending = $pdo->prepare("
    SELECT p.*, l.city, l.area
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_trending = 1 AND p.is_deleted=0 AND l.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtTrending->execute();
$trendingProperties = $stmtTrending->fetchAll();

// Recommended properties
$stmtRecommended = $pdo->prepare("
    SELECT p.*, l.city, l.area
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_recommended = 1 AND p.is_deleted=0 AND l.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtRecommended->execute();
$recommendedProperties = $stmtRecommended->fetchAll();

// High demand = featured properties
$stmtHighDemand = $pdo->prepare("
    SELECT p.*, l.city, l.area
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.featured = 1 AND p.is_deleted=0 AND l.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtHighDemand->execute();
$highDemandProperties = $stmtHighDemand->fetchAll();

// Newly launched = latest approved
$stmtNew = $pdo->prepare("
    SELECT p.*, l.city, l.area
    FROM properties p
    LEFT JOIN locations l ON p.location_id = l.id
    WHERE p.status = 'approved' AND p.is_deleted=0 AND l.is_deleted=0
    ORDER BY p.created_at DESC LIMIT 9
");
$stmtNew->execute();
$newProperties = $stmtNew->fetchAll();

// Blogs
$stmtBlogs = $pdo->prepare("SELECT * FROM blogs WHERE status = 'published' AND is_deleted=0 ORDER BY created_at DESC LIMIT 2");
$stmtBlogs->execute();
$blogs = $stmtBlogs->fetchAll();

// Property types for search dropdown
$propertyTypes = ['apartment' => 'Apartment', 'villa' => 'Villa', 'plot' => 'Plot', 'commercial' => 'Commercial', 'office' => 'Office'];

// Testimonials
$stmtTestimonials = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 AND is_deleted=0 ORDER BY sort_order ASC, id DESC LIMIT 6");
$testimonials = $stmtTestimonials->fetchAll();

// Banners (for hero slider)
$stmtBanners = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND is_deleted=0 ORDER BY sort_order ASC LIMIT 5");
$banners = $stmtBanners->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- ========== HERO SECTION ========== -->
<section class="d-flex flex-column p-0">
    <div style="background-image: url('assets/img/banner.jpg')" class="bg-cover d-flex align-items-center custom-vh-100">
        <div class="container pt-20 pb-15" data-animate="zoomIn">
            <p class="text-white fs-md-22 fs-18 font-weight-500 letter-spacing-367 mb-1 text-center text-uppercase">
                Let us guide your home
            </p>
            <h2 class="text-white display-2 text-center mb-sm-8 mb-8">Find Your Dream Home</h2>

            <!-- Desktop Search Form -->
            <form class="property-search py-lg-0 z-index-2 position-relative d-none d-lg-block" action="<?= SITE_URL ?>properties.php" method="GET">
                <input type="hidden" name="status" value="for-sale" id="listingType">

                <!-- Tabs -->
                <ul class="nav nav-pills property-search-status-tab">
                    <li class="nav-item bg-secondary rounded-top">
                        <a href="#" class="nav-link btn shadow-none rounded-bottom-0 text-white text-btn-focus-secondary text-uppercase d-flex align-items-center fs-13 rounded-bottom-0 bg-active-white text-active-secondary letter-spacing-087 flex-md-1 px-4 py-2 active" data-toggle="pill" data-value="for-sale"
                           onclick="document.getElementById('listingType').value='for-sale'; setActiveTab(this)">
                            <svg class="icon icon-villa fs-22 mr-2"><use xlink:href="#icon-villa"></use></svg>
                            For Sale
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link btn shadow-none rounded-bottom-0 text-white text-btn-focus-secondary text-uppercase d-flex align-items-center fs-13 rounded-bottom-0 bg-active-white text-active-secondary letter-spacing-087 flex-md-1 px-4 py-2" data-toggle="pill" data-value="for-rent"
                           onclick="document.getElementById('listingType').value='for-rent'; setActiveTab(this)">
                            <svg class="icon icon-building fs-22 mr-2"><use xlink:href="#icon-building"></use></svg>
                            For Rent
                        </a>
                    </li>
                </ul>

                <!-- Search Fields -->
                <div class="bg-white px-6 py-6 rounded-bottom rounded-top-right pb-6 pb-lg-0">
                    <div class="row align-items-center" id="accordion-4">
                        <!-- Home Type -->
                        <div class="col-md-6 col-lg-4 col-xl-4 pt-6 pt-lg-0 order-1">
                            <label class="text-uppercase font-weight-500 letter-spacing-093 mb-1">Home Type</label>
                            <select class="form-control selectpicker bg-transparent border-bottom rounded-0 border-color-input" name="type" title="Select">
                                <?php foreach ($propertyTypes as $val => $label): ?>
                                    <option value="<?= $val ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Keyword Search -->
                        <div class="col-md-6 col-lg-4 col-xl-5 pt-6 pt-lg-0 order-2">
                            <label class="text-uppercase font-weight-500 letter-spacing-093">Search</label>
                            <div class="position-relative">
                                <input type="text" name="search" class="form-control bg-transparent shadow-none border-top-0 border-right-0 border-left-0 border-bottom rounded-0 h-24 lh-17 pl-0 pr-4 font-weight-600 border-color-input placeholder-muted" placeholder="Find something...">
                                <i class="far fa-search position-absolute pos-fixed-right-center pr-0 fs-18 mt-n3"></i>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-sm pt-6 pt-lg-0 order-sm-4 order-5">
                            <button type="submit" class="btn btn-primary shadow-none fs-16 font-weight-600 w-100 py-lg-2 lh-213">Search</button>
                        </div>

                        <!-- Amenities / Features -->
                        <div class="col-12 pt-4 pb-sm-4 order-sm-5 order-4">
                            <div class="row pt-2">
                                <?php 
                                $amenities = ['ac'=>'Conditioning', 'laundry'=>'Laundry', 'washer'=>'Washer', 'refrigerator'=>'Refrigerator'];
                                $i = 1;
                                foreach($amenities as $val => $label): 
                                ?>
                                    <div class="col-sm-6 col-md-3 col-lg-2 py-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="check<?= $i ?>-4" name="features[]" value="<?= $val ?>">
                                            <label class="custom-control-label" for="check<?= $i ?>-4"><?= $label ?></label>
                                        </div>
                                    </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- ========== EXPLORE PROPERTY TYPES (Locations) ========== -->
<section>
    <div class="container">
        <div class="section-heading">
            <h2>Explore Property Types</h2>
            <p>Lorem ipsum dolor sit amet, consec tetur cing elit. Suspe ndisse suscipit</p>
        </div>
        <div class="swiper location-slider">
            <div class="swiper-wrapper">
                <?php
                $locationImages = [
                    'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=400&q=75',
                    'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=400&q=75',
                    'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=400&q=75',
                    'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&q=75',
                    'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=400&q=75',
                    'https://images.unsplash.com/photo-1444723121867-7a241cacace9?w=400&q=75',
                    'https://images.unsplash.com/photo-1514565131-fce0801e5785?w=400&q=75',
                    'https://images.unsplash.com/photo-1494526585095-c41746248156?w=400&q=75',
                ];
                if (!empty($locations)):
                    foreach ($locations as $i => $loc):
                        $img = $locationImages[$i % count($locationImages)];
                ?>
                <div class="swiper-slide">
                    <div class="location-card">
                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($loc['city']) ?>">
                        <div class="overlay">
                            <h4><?= htmlspecialchars($loc['city']) ?></h4>
                        </div>
                    </div>
                </div>
                <?php
                    endforeach;
                else:
                    $defaultCities = ['New York', 'Ghaziabad', 'San Jose', 'Fort Worth'];
                    foreach ($defaultCities as $i => $city):
                ?>
                <div class="swiper-slide">
                    <div class="location-card">
                        <img src="<?= $locationImages[$i] ?>" alt="<?= $city ?>">
                        <div class="overlay">
                            <h4><?= $city ?></h4>
                        </div>
                    </div>
                </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div class="swiper-button-prev loc-prev"></div>
            <div class="swiper-button-next loc-next"></div>
        </div>
    </div>
</section>

<!-- ========== ABOUT THIS PROPERTY ========== -->
<section class="about-property section-white">
    <div class="container">
        <h2>About This Property</h2>
        <div class="row g-3">
            <?php
            $stats = [
                ['icon' => 'fas fa-building',       'label' => 'TYPE',      'value' => 'Single Family'],
                ['icon' => 'fas fa-calendar-alt',    'label' => 'YEAR BUILT','value' => '2020'],
                ['icon' => 'fas fa-fire',            'label' => 'HEATING',   'value' => 'Radiant'],
                ['icon' => 'fas fa-ruler-combined',  'label' => 'SQFT',      'value' => '979.0'],
                ['icon' => 'fas fa-bed',             'label' => 'BEDROOMS',  'value' => '3'],
                ['icon' => 'fas fa-bath',            'label' => 'BATHROOMS', 'value' => '2'],
                ['icon' => 'fas fa-car',             'label' => 'GARAGE',    'value' => '1'],
                ['icon' => 'fas fa-check-circle',    'label' => 'STATUS',    'value' => 'Active'],
            ];
            foreach ($stats as $stat):
            ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="<?= $stat['icon'] ?>"></i></div>
                    <div class="stat-info">
                        <div class="stat-label"><?= $stat['label'] ?></div>
                        <div class="stat-value"><?= $stat['value'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
// ---- Helper: Render a property slider section ----
function renderPropertySection($title, $properties, $siteUrl, $uploadUrl, $showSeeAll = true) {
    if (empty($properties)) return;
    ?>
    <section>
        <div class="container">
            <div class="section-heading d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h2><?= htmlspecialchars($title) ?></h2>
                    <p>Lorem ipsum dolor sit amet, consec tetur cing elit. Suspe ndisse suscipit</p>
                </div>
                <?php if ($showSeeAll): ?>
                <a href="<?= $siteUrl ?>properties" class="see-all">See all properties <i class="fas fa-arrow-right ms-1"></i></a>
                <?php endif; ?>
            </div>
            <div class="swiper property-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($properties as $prop): ?>
                    <div class="swiper-slide">
                        <div class="property-card">
                            <div class="card-thumb">
                                <?php
                                $thumb = 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&q=75';
                                if (!empty($prop['featured_image'])) {
                                    $thumb = $uploadUrl . 'properties/' . $prop['featured_image'];
                                }
                                ?>
                                <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($prop['title']) ?>">
                                <div class="badges">
                                    <?php if (!empty($prop['featured'])): ?>
                                    <span class="badge-tag badge-featured">Featured</span>
                                    <?php endif; ?>
                                    <span class="badge-tag <?= ($prop['listing_type'] ?? 'sale') === 'rent' ? 'badge-rent' : 'badge-sale' ?>">
                                        FOR <?= strtoupper($prop['listing_type'] ?? 'SALE') ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5><?= htmlspecialchars($prop['title']) ?></h5>
                                <p class="location">
                                    <?= htmlspecialchars(($prop['area'] ?? '') . ($prop['area'] && $prop['city'] ? ', ' : '') . ($prop['city'] ?? '')) ?>
                                </p>
                                <div class="property-meta">
                                    <?php if ($prop['bedrooms']): ?>
                                    <span><i class="fas fa-bed"></i> <?= $prop['bedrooms'] ?> Br</span>
                                    <?php endif; ?>
                                    <?php if ($prop['bathrooms']): ?>
                                    <span><i class="fas fa-bath"></i> <?= $prop['bathrooms'] ?> Ba</span>
                                    <?php endif; ?>
                                    <?php if ($prop['area_sqft']): ?>
                                    <span><i class="fas fa-ruler-combined"></i> <?= number_format($prop['area_sqft']) ?> Sq.Ft</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer-row">
                                    <div class="price">
                                        <?php
                                        $price = $prop['price'] ?? 0;
                                        $priceType = $prop['price_type'] ?? 'total';
                                        if ($price >= 10000000) {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price / 10000000, 2) . ' Cr';
                                        } elseif ($price >= 100000) {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price / 100000, 2) . ' Lac';
                                        } else {
                                            echo '<i class="fas fa-rupee-sign" style="font-size:14px"></i>' . number_format($price);
                                        }
                                        if ($priceType === 'per_month') echo ' <small>/ month</small>';
                                        elseif ($priceType === 'per_sqft') echo ' <small>/ sq.ft</small>';
                                        ?>
                                    </div>
                                    <a href="<?= $siteUrl ?>property/<?= htmlspecialchars($prop['slug'] ?? '') ?>" class="arrow-link">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php
}
?>

<!-- ========== EXPLORE TRENDING LOCATIONS ========== -->
<?php renderPropertySection('Explore Trending Locations', $trendingProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== RECOMMENDED ========== -->
<?php renderPropertySection('Recommended in Ghaziabad', $recommendedProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== HIGH DEMAND ========== -->
<?php renderPropertySection('High Demand in Ghaziabad', $highDemandProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== EXPLORE BY POSSESSION ========== -->
<?php renderPropertySection('Explore by Possession', $newProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== NEWLY LAUNCHED ========== -->
<?php renderPropertySection('Newly Launched in Ghaziabad', $newProperties, SITE_URL, UPLOAD_URL); ?>

<!-- ========== FIND YOUR NEIGHBORHOOD ========== -->
<section class="neighborhood-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="neighborhood-img">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&q=80" alt="Neighborhood">
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="content">
                    <h2>Find your<br>neighborhood</h2>
                    <p>Lorem ipsum dolor sit amet, consec tetur cing elit. Suspe ndisse</p>
                    <div class="neighborhood-search">
                        <input type="text" placeholder="Enter an address, neighbourhood">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVICES ========== -->
<section class="services-section bg-patten-04">
    <div class="container">
        <div class="top-text">
            <h2>We have the most listings and constant updates.So you'll never miss out.</h2>
        </div>
        <div class="row g-4">
            <?php
            $services = [
                ['icon' => 'fas fa-home',       'title' => 'Buy a new home',  'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor'],
                ['icon' => 'fas fa-hand-holding-usd', 'title' => 'Sell a home', 'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor'],
                ['icon' => 'fas fa-key',         'title' => 'Rent a home',    'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor'],
            ];
            foreach ($services as $svc):
            ?>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="row">
                        <div class="col-md-3">
                    <div class="icon"><i class="<?= $svc['icon'] ?>"></i></div>
                </div>
                <div class="col-md-9">
                    <h5><?= $svc['title'] ?></h5>
                    <p><?= $svc['desc'] ?></p>
                </div>
                </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== BLOG & NEWS ========== -->
<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <h3>BLOG & NEWS</h3>
                    <h2>Interesting Articles Updated Daily</h2>
                    <a href="#" class="article-link"><i class="fas fa-arrow-right"></i> Opening a new store in NYC</a>
                    <a href="#" class="article-link"><i class="fas fa-arrow-right"></i> 5 Famous Progressive Web Apps</a>
                    <a href="#" class="article-link"><i class="fas fa-arrow-right"></i> How to Create an Effective Elevator Pitch</a>
                    <a href="#" class="article-link"><i class="fas fa-arrow-right"></i> Top Strategic Technology Trends for 2020</a>
                    <a href="#" class="article-link"><i class="fas fa-arrow-right"></i> The Quest for Better Web Accessibility</a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row g-4">
                    <?php
                    if (!empty($blogs)):
                        foreach ($blogs as $blog):
                            $blogImg = !empty($blog['featured_image'])
                                ? UPLOAD_URL . 'blogs/' . $blog['featured_image']
                                : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&q=75';
                    ?>
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-thumb hover-shine">
                                <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                                <span class="blog-category"><?= htmlspecialchars($blog['category'] ?? 'RENTAL') ?></span>
                            </div>
                            <div class="blog-body">
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> <?= date('jS M, Y', strtotime($blog['created_at'])) ?></span>
                                    <span><i class="far fa-eye"></i> <?= $blog['views'] ?? 0 ?> views</span>
                                </div>
                                <h5><?= htmlspecialchars($blog['title']) ?></h5>
                                <p><?= htmlspecialchars(mb_substr($blog['short_description'] ?? '', 0, 120)) ?>...</p>
                                <a href="<?= SITE_URL ?>blog-detail.php?slug=<?= htmlspecialchars($blog['slug']) ?>" class="read-more">
                                    Read more <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else:
                        // Default placeholder blogs
                        $placeholderBlogs = [
                            ['title' => 'Retail banks wake up to digital lending this year', 'img' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&q=75'],
                            ['title' => 'Within the construction industry as their overdraft', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&q=75'],
                        ];
                        foreach ($placeholderBlogs as $pb):
                    ?>
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-thumb hover-shine">
                                <img src="<?= $pb['img'] ?>" alt="Blog">
                                <span class="blog-category">RENTAL</span>
                            </div>
                            <div class="blog-body">
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> 30th Dec, 2020</span>
                                    <span><i class="far fa-eye"></i> 149 views</span>
                                </div>
                                <h5><?= $pb['title'] ?></h5>
                                <p>Lorem ipsum dolor sit amet, consecte tur cing elit. Suspe ndisse suscipit sagittis leo sit met condim entum, consecte tur cineoi</p>
                                <a href="#" class="read-more btn-accent">Read more <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CLIENT TESTIMONIALS ========== -->
<?php if (!empty($testimonials)): ?>
<section class="section-gray">
    <div class="container">
        <div class="section-heading text-center" style="text-align:center;">
            <h2 style="display:inline-block;">What Our Clients Say</h2>
            <p>Trusted by thousands of happy property buyers and sellers</p>
        </div>
        <div class="swiper testimonial-slider" style="padding-bottom:45px;">
            <div class="swiper-wrapper">
                <?php foreach ($testimonials as $t): ?>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                            <i class="fas fa-star" style="color:<?= $s <= $t['rating'] ? '#f59e0b' : '#ddd' ?>;font-size:14px;"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="testimonial-text"><?= htmlspecialchars($t['content']) ?></p>
                        <div class="testimonial-author">
                            <?php if (!empty($t['photo'])): ?>
                            <img src="<?= UPLOAD_URL ?>testimonials/<?= htmlspecialchars($t['photo']) ?>" alt="">
                            <?php else: ?>
                            <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <div>
                                <strong><?= htmlspecialchars($t['name']) ?></strong>
                                <?php if ($t['designation']): ?><small><?= htmlspecialchars($t['designation']) ?></small><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== CTA CONTACT ========== -->
<section class="bg-single-image-02 bg-accent py-lg-13 py-11" data-animated-id="13">
        <div class="container">
          <div class="row">
            <div class="col-ld-6 col-sm-7 fadeInLeft animated" data-animate="fadeInLeft">
              <div class="pl-6 border-4x border-left border-primary">
                <h2 class="text-heading lh-15 fs-md-32 fs-25">For more information about our services,<span class="text-primary"> get in touch</span> with our expert consultants</h2>
                <p class="lh-2 fs-md-15 mb-0">10 new offers every day. 350 offers on site, Trusted by a community of thousands of users.</p>
              </div>
            </div>
            <div class="col-ld-6 col-sm-5 text-center mt-sm-0 mt-8 fadeInRight animated" data-animate="fadeInRight">
              <i class="fa fa-phone fs-40 text-primary"></i>
              <p class="fs-13 font-weight-500 letter-spacing-173 text-uppercase lh-2 mt-3">Call for help now!</p>
              <p class="fs-md-42 fs-32 font-weight-600 text-secondary lh-1">9876543210</p>
              <a href="#" class="btn btn-lg btn-primary mt-2 px-10">Contact us</a>
            </div>
          </div>
        </div>
      </section>

<!-- ========== PARTNER LOGOS ========== -->
<section class="partners-section">
    <div class="container">
        <div class="row align-items-center">
            <?php
            $partners = [
                ['icon' => 'fas fa-shield-alt', 'name' => 'BASTILLE'],
                ['icon' => 'fas fa-home',       'name' => 'HOUSE'],
                ['icon' => 'fas fa-building',   'name' => 'REAL ESTATE'],
                ['icon' => 'fas fa-leaf',       'name' => 'ECOHOUSE'],
                ['icon' => 'fas fa-home',       'name' => 'Real Estate'],
            ];
            foreach ($partners as $p):
            ?>
            <div class="col">
                <div class="partner-logo">
                    <i class="<?= $p['icon'] ?>"></i>
                    <span><?= $p['name'] ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
