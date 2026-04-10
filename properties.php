<?php
   require_once __DIR__ . '/includes/db.php';
   
   // ---- Filters ----
   $listing_type  = $_GET['listing_type']  ?? $_GET['listing'] ?? '';
   $property_type = $_GET['property_type'] ?? $_GET['type'] ?? '';
   $bedrooms      = $_GET['bedrooms']      ?? '';
   $search        = trim($_GET['q']        ?? $_GET['search'] ?? '');
   $city          = trim($_GET['city']     ?? '');
   $location_id   = $_GET['location_id']   ?? '';
   $sort          = $_GET['sort']          ?? 'newest';
   $page          = max(1, (int)($_GET['page'] ?? 1));
   $per_page      = 10;
   $offset        = ($page - 1) * $per_page;
   
   // ---- Build query ----
   $where  = ["p.status = 'approved'", "p.is_deleted=0"];
   $params = [];
   
   if ($listing_type && in_array($listing_type, ['sale','rent'])) {
       $where[]  = "p.listing_type = ?";
       $params[] = $listing_type;
   }
   if ($property_type) {
       $where[]  = "p.property_type = ?";
       $params[] = $property_type;
   }
   if ($bedrooms && $bedrooms !== 'all') {
       if ($bedrooms === '5+') {
           $where[] = "p.bedrooms >= 5";
       } else {
           $where[]  = "p.bedrooms = ?";
           $params[] = (int)$bedrooms;
       }
   }
   if ($search) {
       $where[]  = "(p.title LIKE ? OR p.address LIKE ? OR l.city LIKE ? OR l.area LIKE ?)";
       $like     = "%$search%";
       $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
   }
   if ($city) {
       $where[]  = "l.city LIKE ?";
       $params[] = "%$city%";
   }
   if ($location_id) {
       $where[]  = "p.location_id = ?";
       $params[] = (int)$location_id;
   }
   if (!empty($_GET['price_min']) && is_numeric($_GET['price_min']) && $_GET['price_min'] > 0) {
       $where[]  = "p.price >= ?";
       $params[] = (float)$_GET['price_min'];
   }
   if (!empty($_GET['price_max']) && is_numeric($_GET['price_max']) && $_GET['price_max'] < 500000000) {
       $where[]  = "p.price <= ?";
       $params[] = (float)$_GET['price_max'];
   }
   if (!empty($_GET['category'])) {
       $where[]  = "p.category = ?";
       $params[] = $_GET['category'];
   }
   if (!empty($_GET['furnishing']) && in_array($_GET['furnishing'], ['furnished','semi-furnished','unfurnished'])) {
       $where[]  = "p.furnishing = ?";
       $params[] = $_GET['furnishing'];
   }
   if (!empty($_GET['availability'])) {
       $where[]  = "p.availability = ?";
       $params[] = $_GET['availability'];
   }
   if (!empty($_GET['area_min']) && is_numeric($_GET['area_min']) && $_GET['area_min'] > 0) {
       $where[]  = "p.area_sqft >= ?";
       $params[] = (float)$_GET['area_min'];
   }
   if (!empty($_GET['area_max']) && is_numeric($_GET['area_max']) && $_GET['area_max'] < 100000) {
       $where[]  = "p.area_sqft <= ?";
       $params[] = (float)$_GET['area_max'];
   }
   if (!empty($_GET['verified'])) {
       $where[] = "p.featured = 1";
   }
   
   $whereSQL = implode(' AND ', $where);
   $orderSQL = match($sort) {
       'price_low'  => 'p.price ASC',
       'price_high' => 'p.price DESC',
       'oldest'     => 'p.created_at ASC',
       default      => 'p.created_at DESC',
   };
   
   // Total count
   $countStmt = $pdo->prepare("SELECT COUNT(*) FROM properties p LEFT JOIN locations l ON p.location_id = l.id WHERE $whereSQL");
   $countStmt->execute($params);
   $total       = (int)$countStmt->fetchColumn();
   $total_pages = max(1, ceil($total / $per_page));
   if ($page > $total_pages) $page = $total_pages;
   
   // Fetch properties
   $sql = "SELECT p.*, l.city, l.area, l.state
           FROM properties p LEFT JOIN locations l ON p.location_id = l.id
           WHERE $whereSQL ORDER BY $orderSQL LIMIT $per_page OFFSET $offset";
   $stmt = $pdo->prepare($sql);
   $stmt->execute($params);
   $properties = $stmt->fetchAll();
   
   // Locations for filter + popular localities
   $locations = $pdo->query("SELECT * FROM locations WHERE is_deleted=0 ORDER BY city ASC")->fetchAll();
   
   // Popular localities (areas that have properties)
   $popLoc = $pdo->query("
       SELECT l.id, l.area, l.city, COUNT(p.id) as cnt
       FROM locations l
       JOIN properties p ON p.location_id = l.id AND p.status='approved' AND p.is_deleted=0
       WHERE l.area IS NOT NULL AND l.area != '' AND l.is_deleted=0
       GROUP BY l.id ORDER BY cnt DESC LIMIT 12
   ")->fetchAll();
   
   $pageTitle = 'Properties' . ($search ? " – $search" : '') . ' | MakaanDekho';
   
   function buildPageUrl(int $p): string {
       $params = $_GET; $params['page'] = $p;
       return '?' . http_build_query($params);
   }
   
   function formatPrice($price) {
       if (!$price) return '₹ N/A';
       if ($price >= 10000000) return '₹' . number_format($price / 10000000, 2) . ' Cr';
       if ($price >= 100000) return '₹' . number_format($price / 100000, 2) . ' Lac';
       return '₹' . number_format($price);
   }
   
   include __DIR__ . '/includes/header.php';
   ?>
<section class="pb-4 page-title shadow gt-breadcrumb-wrapper" style="background: url(assets/img/breadcrumb.jpg);">
   <div class="container">
      <nav aria-label="breadcrumb" class="breadcrumb_text position-relative">
         <ol class="breadcrumb pt-6 pt-lg-2 lh-15 pb-5 text-white">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listing</li>
         </ol>
         <h1 class="fs-30 lh-1 mb-0 text-heading font-weight-600 text-white">Listing Heading</h1>
      </nav>
   </div>
</section>
<!-- ========== SEARCH BAR + SORT + NEAR ME ========== -->
<div class="container">
   <div class="mt-6 form-search-01 featured_row">
      <div class="listing-search-bar">
         <div class="listing-search-ro">
            <div class="row">
               <div class="form-group p-1 col-md-8 search_input">
                  <form method="GET" action="<?= SITE_URL ?>properties.php" class="listing-search-for form-inline mx-n1">
                     <i class="fas fa-search"></i>
                     <input type="text" name="q" id="listingSearch" value="<?= htmlspecialchars($search) ?>" placeholder="Search Location, Builder or Project Name..." class="form-control border-0 shadow-xxs-1 bg-transparent font-weight-600" autocomplete="off">
                     <?php if ($property_type): ?><input type="hidden" name="property_type" value="<?= htmlspecialchars($property_type) ?>"><?php endif; ?>
                  </form>
               </div>
               <div class="form-group p-1 col-md-2">
                  <div class="listing-search-right">
                     <div class="listing-sort">
                        <span>Sort:</span>
                        <select onchange="window.location.href=updateParam('sort',this.value)">
                           <option value="newest"     <?= $sort==='newest'?'selected':'' ?>>Newest</option>
                           <option value="oldest"     <?= $sort==='oldest'?'selected':'' ?>>Oldest</option>
                           <option value="price_low"  <?= $sort==='price_low'?'selected':'' ?>>Price: Low to High</option>
                           <option value="price_high" <?= $sort==='price_high'?'selected':'' ?>>Price: High to Low</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="form-group p-1 col-md-2 text-right">
                  <button class="btn-near-me" onclick="getNearMe()">
                  <i class="fas fa-map-marker-alt"></i> Near Me
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- ========== MAIN LISTING AREA ========== -->
<div class="listing-page pt-15 pb-11 bg-gray-01">
   <div class="container">
      <div class="row">
         <!-- ===== LEFT SIDEBAR ===== -->
         <div class="col-lg-4 col-xl-4">
            <div class="filter-sidebar">
               <div class="filter-header">
                  <h5><i class="fas fa-sliders-h me-2"></i>FILTERS</h5>
                  <a href="<?= SITE_URL ?>properties.php" class="filter-reset">RESET</a>
               </div>
               <form method="GET" id="filterForm">
                  <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                  <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                  <!-- Category -->
                  <div class="filter-group">
                     <h6 class="filter-title">CATEGORY</h6>
                     <?php
                        $categories = ['apartment' => 'Residential', 'commercial' => 'Commercial', 'plot' => 'Plots'];
                        foreach ($categories as $val => $label):
                        ?>
                     <label class="filter-pill <?= $property_type === $val ? 'active' : '' ?>">
                     <input type="radio" name="property_type" value="<?= $val ?>" <?= $property_type === $val ? 'checked' : '' ?> onchange="this.form.submit()">
                     <?= $label ?>
                     <?php if ($property_type === $val): ?>
                     <i class="fas fa-check-circle ms-auto"></i>
                     <?php endif; ?>
                     </label>
                     <?php endforeach; ?>
                  </div>
                  <!-- Bedrooms -->
                  <div class="filter-group">
                     <h6 class="filter-title">BEDROOMS</h6>
                     <div class="bhk-grid">
                        <?php
                           $bhkOptions = ['all' => 'All BHK', '1' => '1 BHK', '2' => '2 BHK', '3' => '3 BHK', '4' => '4 BHK', '5+' => '5+ BHK'];
                           $activeBhk = $bedrooms ?: 'all';
                           foreach ($bhkOptions as $val => $label):
                           ?>
                        <label class="bhk-btn <?= $activeBhk === $val ? 'active' : '' ?>">
                        <input type="radio" name="bedrooms" value="<?= $val ?>" <?= $activeBhk === $val ? 'checked' : '' ?> onchange="this.form.submit()">
                        <?= $label ?>
                        </label>
                        <?php endforeach; ?>
                     </div>
                  </div>
                  <!-- All Residential Types -->
                  <div class="filter-group">
                     <select name="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Residential Types</option>
                        <?php foreach (['Studio Apartment','Flat','Independent House','Villa','Builder Floor','Penthouse','Farm House'] as $c): ?>
                        <option value="<?= $c ?>" <?= ($_GET['category']??'')===$c?'selected':'' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <!-- By Locality -->
                  <div class="filter-group">
                     <h6 class="filter-title">BY LOCALITY</h6>
                     <select name="location_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Localities</option>
                        <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>" <?= $location_id == $loc['id'] ? 'selected' : '' ?>>
                           <?= htmlspecialchars(($loc['area'] ? $loc['area'] . ', ' : '') . $loc['city']) ?>
                        </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <!-- Price Range -->
                  <div class="filter-group">
                     <div class="filter-range-header">
                        <h6 class="filter-title">PRICE RANGE</h6>
                        <span class="range-value" id="priceRangeLabel">
                        ₹<?= !empty($_GET['price_min']) ? number_format($_GET['price_min']/100000,0) . ' Lac' : '0' ?>
                        - ₹<?= !empty($_GET['price_max']) ? ($_GET['price_max']>=10000000 ? number_format($_GET['price_max']/10000000,1) . ' Cr' : number_format($_GET['price_max']/100000,0) . ' Lac') : '50 Cr' ?>
                        </span>
                     </div>
                     <div class="range-slider-wrap">
                        <input type="range" name="price_min" class="range-slider" min="0" max="500000000" step="500000"
                           value="<?= (int)($_GET['price_min'] ?? 0) ?>" id="priceMin"
                           oninput="updatePriceLabel()" onchange="this.form.submit()">
                        <input type="range" name="price_max" class="range-slider" min="0" max="500000000" step="500000"
                           value="<?= (int)($_GET['price_max'] ?? 500000000) ?>" id="priceMax"
                           oninput="updatePriceLabel()" onchange="this.form.submit()">
                     </div>
                     <div class="range-labels">
                        <span>0</span>
                        <span>50 Cr+</span>
                     </div>
                  </div>
                  <!-- Possession / Status -->
                  <div class="filter-group">
                     <h6 class="filter-title">POSSESSION</h6>
                     <select name="availability" class="filter-select" onchange="this.form.submit()">
                        <option value="">Any Status</option>
                        <option value="available" <?= ($_GET['availability']??'')==='available'?'selected':'' ?>>Ready to Move</option>
                        <option value="under_construction" <?= ($_GET['availability']??'')==='under_construction'?'selected':'' ?>>Under Construction</option>
                        <option value="new_launch" <?= ($_GET['availability']??'')==='new_launch'?'selected':'' ?>>New Launch</option>
                     </select>
                  </div>
                  <!-- Furnishing -->
                  <div class="filter-group">
                     <h6 class="filter-title">FURNISHING</h6>
                     <select name="furnishing" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Furnishings</option>
                        <option value="furnished" <?= ($_GET['furnishing']??'')==='furnished'?'selected':'' ?>>Furnished</option>
                        <option value="semi-furnished" <?= ($_GET['furnishing']??'')==='semi-furnished'?'selected':'' ?>>Semi-Furnished</option>
                        <option value="unfurnished" <?= ($_GET['furnishing']??'')==='unfurnished'?'selected':'' ?>>Unfurnished</option>
                     </select>
                  </div>
                  <!-- Area (SQ.FT) -->
                  <div class="filter-group">
                     <div class="filter-range-header">
                        <h6 class="filter-title">AREA (SQ.FT)</h6>
                        <span class="range-value" id="areaRangeLabel">
                        <?= (int)($_GET['area_min'] ?? 0) ?> - <?= htmlspecialchars($_GET['area_max'] ?? '1L+') ?>
                        </span>
                     </div>
                     <div class="range-slider-wrap">
                        <input type="range" name="area_min" class="range-slider" min="0" max="100000" step="100"
                           value="<?= (int)($_GET['area_min'] ?? 0) ?>" id="areaMin"
                           oninput="updateAreaLabel()" onchange="this.form.submit()">
                        <input type="range" name="area_max" class="range-slider" min="0" max="100000" step="100"
                           value="<?= (int)($_GET['area_max'] ?? 100000) ?>" id="areaMax"
                           oninput="updateAreaLabel()" onchange="this.form.submit()">
                     </div>
                     <div class="range-labels">
                        <span>0</span>
                        <span>1L Sq.ft+</span>
                     </div>
                  </div>
                  <!-- RERA Verified -->
                  <div class="filter-group">
                     <label class="filter-checkbox">
                     <input type="checkbox" name="verified" value="1" <?= !empty($_GET['verified'])?'checked':'' ?> onchange="this.form.submit()">
                     <span class="checkmark"></span>
                     ONLY RERA VERIFIED
                     </label>
                  </div>
               </form>
            </div>
         </div>
         <!-- ===== MAIN CONTENT ===== -->
         <div class="col-lg-8 col-xl-8">
            <!-- Results count + Map -->
            <div class="results-bar">
               <div class="results-count">
                  FOUND <strong><?= $total ?></strong> RESULTS
               </div>
               <button class="btn-map-toggle">
               <i class="fas fa-map"></i> Map
               </button>
            </div>
            <!-- Popular Localities -->
            <?php if (!empty($popLoc)): ?>
            <div class="popular-localities">
               <h6 class="localities-title">POPULAR LOCALITIES</h6>
               <div class="localities-tags">
                  <?php foreach ($popLoc as $pl): ?>
                  <a href="<?= SITE_URL ?>properties.php?location_id=<?= $pl['id'] ?><?= $property_type ? '&property_type='.$property_type : '' ?>"
                     class="locality-tag <?= $location_id == $pl['id'] ? 'active' : '' ?>">
                  <i class="fas fa-map-marker-alt"></i>
                  <?= htmlspecialchars($pl['area'] . ', ' . $pl['city']) ?>
                  </a>
                  <?php endforeach; ?>
               </div>
            </div>
            <?php endif; ?>
            <!-- Property List (Horizontal Cards) -->
            <?php if (empty($properties)): ?>
            <div class="no-results">
               <i class="fas fa-building"></i>
               <h4>No Properties Found</h4>
               <p>Try adjusting your filters or search criteria</p>
            </div>
            <?php else: ?>
            <?php foreach ($properties as $prop): ?>
            <div class="py-5 px-4 border rounded-lg shadow-hover-1 bg-white mb-4" data-animate="fadeInUp">
               <div class="media flex-column flex-sm-row no-gutters listing_imgslider">
                  <div class="col-sm-4 mr-sm-5 card border-0 hover-change-image bg-hover-overlay mb-sm-2">
                     <div class="prop-list-card">
                        <div class="prop-list-thumb">
                           <?php
                              $thumb = 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=500&q=75';
                              if (!empty($prop['featured_image'])) {
                                  $thumb = UPLOAD_URL . 'properties/' . $prop['featured_image'];
                              }
                              ?>
                           <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($prop['title']) ?>">
                           <?php if (!empty($prop['featured'])): ?>
                           <span class="verified-badge"><i class="fas fa-star"></i> VERIFIED</span>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
                  <div class="media-body mt-3 mt-sm-0">
                     <a href="<?= SITE_URL ?>property-detail.php?slug=<?= htmlspecialchars($prop['slug'] ?? '') ?>" class="fs-16 lh-2 text-dark hover-primary d-block">
                     <?= htmlspecialchars($prop['title']) ?>
                     </a>
                     <p class="mb-0 font-weight-500 text-gray-light">
                        <i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars(($prop['area'] ?? '') . ($prop['area'] && $prop['city'] ? ', ' : '') . ($prop['city'] ?? '')) ?>
                     </p>
                     <div class="prop-list-price fs-20 font-weight-bold text-heading mb-1">
                        <?= formatPrice($prop['price'] ?? 0) ?>
                        <?php
                           $pt = $prop['price_type'] ?? 'total';
                           if ($pt === 'per_month') echo ' <small>/month</small>';
                           elseif ($pt === 'per_sqft') echo ' <small>/sq.ft</small>';
                           ?>
                     </div>
                     <p class="mb-2 ml-0">Lorem ipsum dolor sit amet, sectetur cing elit uspe ndisse suscorem ipsum dolor sitorem sit amet, sectetur cing elit uspe ndisse suscorem</p>
                  </div>
               </div>
               <!-- Specs row: AREA | FURNISH | STATUS -->
               <div class="d-sm-flex justify-content-sm-between">
                  <div class="list-inline d-flex mb-0 flex-wrap">
                     <?php if ($prop['area_sqft']): ?>
                     <div class="list-inline-item text-gray font-weight-500 fs-13 d-flex align-items-center mr-5" data-toggle="tooltip" title="Size">
                        <span class="spec-icon"><i class="fas fa-ruler-combined"></i></span>
                        <div>
                           <strong><?= number_format($prop['area_sqft']) ?> <small>sq.ft</small></strong>
                        </div>
                     </div>
                     <?php endif; ?>
                     <div class="list-inline-item text-gray font-weight-500 fs-13 d-flex align-items-center mr-5" data-toggle="tooltip" title="Semi-Furnish">
                        <span class="spec-icon"><i class="fas fa-couch"></i></span>
                        <div>
                           <strong><?= ucfirst(str_replace('-', ' ', $prop['furnishing'] ?? 'N/A')) ?></strong>
                        </div>
                     </div>
                     <div class="list-inline-item text-gray font-weight-500 fs-13 d-flex align-items-center mr-5" data-toggle="tooltip" title="Ready To Move">
                        <span class="spec-icon"><i class="fas fa-info-circle"></i></span>
                        <div>
                           <strong><?= ucfirst($prop['availability'] ?? 'Available') ?></strong>
                        </div>
                     </div>
                  </div>
                  <!-- Price + Actions -->
                  <div class="prop-list-footer">
                     <div class="prop-list-actions">
                        <button class="btn-wishlist"><i class="far fa-heart"></i></button>
                        <a href="<?= SITE_URL ?>property-detail.php?slug=<?= htmlspecialchars($prop['slug'] ?? '') ?>" class="btn-enquire badge badge-primary mr-xl-2 mt-3 mt-sm-0 enquire_btn">
                        <i class="fas fa-phone-alt"></i> ENQUIRE
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="pagination-wrap">
               <ul class="pagination">
                  <?php if ($page > 1): ?>
                  <li class="page-item"><a class="page-link" href="<?= buildPageUrl($page - 1) ?>"><i class="fas fa-chevron-left"></i></a></li>
                  <?php endif; ?>
                  <?php
                     $range = 2; $start = max(1, $page - $range); $end = min($total_pages, $page + $range);
                     if ($start > 1): ?>
                  <li class="page-item"><a class="page-link" href="<?= buildPageUrl(1) ?>">1</a></li>
                  <?php if ($start > 2): ?>
                  <li class="page-item disabled"><span class="page-link">...</span></li>
                  <?php endif; endif;
                     for ($i = $start; $i <= $end; $i++): ?>
                  <li class="page-item <?= $i === $page ? 'active' : '' ?>"><a class="page-link" href="<?= buildPageUrl($i) ?>"><?= $i ?></a></li>
                  <?php endfor;
                     if ($end < $total_pages):
                         if ($end < $total_pages - 1): ?>
                  <li class="page-item disabled"><span class="page-link">...</span></li>
                  <?php endif; ?>
                  <li class="page-item"><a class="page-link" href="<?= buildPageUrl($total_pages) ?>"><?= $total_pages ?></a></li>
                  <?php endif; ?>
                  <?php if ($page < $total_pages): ?>
                  <li class="page-item"><a class="page-link" href="<?= buildPageUrl($page + 1) ?>"><i class="fas fa-chevron-right"></i></a></li>
                  <?php endif; ?>
               </ul>
               <p class="pagination-info">Showing <?= $offset + 1 ?>–<?= min($offset + $per_page, $total) ?> of <?= $total ?> properties</p>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script>
   function updateParam(key, val) {
       var url = new URL(window.location.href);
       url.searchParams.set(key, val);
       url.searchParams.delete('page');
       return url.toString();
   }
   function getNearMe() {
       if (navigator.geolocation) {
           navigator.geolocation.getCurrentPosition(function(pos) {
               alert('Location: ' + pos.coords.latitude.toFixed(4) + ', ' + pos.coords.longitude.toFixed(4) + '\nNearby search coming soon.');
           }, function() { alert('Please allow location access.'); });
       } else { alert('Geolocation not supported.'); }
   }
   
   function formatINR(val) {
       val = parseInt(val);
       if (val >= 10000000) return '₹' + (val / 10000000).toFixed(1) + ' Cr';
       if (val >= 100000) return '₹' + (val / 100000).toFixed(0) + ' Lac';
       if (val > 0) return '₹' + val.toLocaleString('en-IN');
       return '₹0';
   }
   
   function updatePriceLabel() {
       var min = document.getElementById('priceMin').value;
       var max = document.getElementById('priceMax').value;
       document.getElementById('priceRangeLabel').textContent =
           formatINR(min) + ' - ' + (max >= 500000000 ? '₹50 Cr+' : formatINR(max));
   }
   
   function updateAreaLabel() {
       var min = document.getElementById('areaMin').value;
       var max = document.getElementById('areaMax').value;
       document.getElementById('areaRangeLabel').textContent =
           parseInt(min).toLocaleString() + ' - ' + (max >= 100000 ? '1L+' : parseInt(max).toLocaleString()) + ' sq.ft';
   }
</script>