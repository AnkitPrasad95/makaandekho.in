<?php
// Fetch dynamic data for footer
$siteName     = $settings['site_name'] ?? 'MakaanDekho';
$siteEmail    = $settings['email'] ?? 'info@makaandekho.in';
$sitePhone    = $settings['phone'] ?? '';
$siteWhatsapp = $settings['whatsapp_number'] ?? '';
$siteAddress  = $settings['address'] ?? '';
$footerText   = $settings['footer_text'] ?? '';
$facebook     = $settings['facebook'] ?? '';
$instagram    = $settings['instagram'] ?? '';
$twitter      = $settings['twitter'] ?? '';
$youtube      = $settings['youtube'] ?? '';
$linkedin     = $settings['linkedin'] ?? '';

// Fetch popular locations for footer links
$footerLocations = [];
try {
    $flStmt = $pdo->query("SELECT city, slug FROM locations WHERE is_deleted=0 GROUP BY city ORDER BY city ASC LIMIT 8");
    $footerLocations = $flStmt->fetchAll();
} catch(Exception $e) {}

// Fetch property type counts for popular searches
$footerPropertyTypes = [
    ['label' => 'Apartments for Sale',  'url' => SITE_URL . 'properties.php?type=apartment&listing=sale'],
    ['label' => 'Villas for Sale',      'url' => SITE_URL . 'properties.php?type=villa&listing=sale'],
    ['label' => 'Plots for Sale',       'url' => SITE_URL . 'properties.php?type=plot&listing=sale'],
    ['label' => 'Apartments for Rent',  'url' => SITE_URL . 'properties.php?type=apartment&listing=rent'],
    ['label' => 'Commercial Spaces',    'url' => SITE_URL . 'properties.php?type=commercial'],
    ['label' => 'Office Spaces',        'url' => SITE_URL . 'properties.php?type=office'],
];
?>

<!-- ========== FOOTER ========== -->
<footer class="site-footer" style="padding:60px 0 0;">
    <div class="container">
        <div class="row" style="margin-bottom:-20px;">

            <!-- Brand & Contact Info -->
            <div class="col-lg-3 col-md-6" style="margin-bottom:30px;">
                <div class="footer-brand">
                    <?php if (!empty($settings['site_logo'])): ?>
                        <a href="<?= SITE_URL ?>" style="display:inline-block;margin-bottom:15px;">
                            <img src="<?= UPLOAD_URL . 'settings/' . $settings['site_logo'] ?>" alt="<?= htmlspecialchars($siteName) ?>" style="max-height:50px;filter:brightness(0) invert(1);">
                        </a>
                    <?php else: ?>
                        <div class="logo-text text-white">
                            <span class="logo-icon"><i class="fas fa-home"></i></span>
                            <span>makaan<br><strong>dekho</strong><small>.in</small></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($siteAddress): ?>
                    <p style="margin-top:12px;font-size:14px;line-height:1.7;"><i class="fas fa-map-marker-alt" style="margin-right:8px;color:#2a96e0;"></i><?= htmlspecialchars($siteAddress) ?></p>
                    <?php endif; ?>

                    <?php if ($siteEmail): ?>
                    <p style="font-size:14px;"><i class="fas fa-envelope" style="margin-right:8px;color:#2a96e0;"></i><a href="mailto:<?= htmlspecialchars($siteEmail) ?>" style="color:#aab;text-decoration:none;"><?= htmlspecialchars($siteEmail) ?></a></p>
                    <?php endif; ?>

                    <?php if ($sitePhone): ?>
                    <p style="font-size:14px;font-weight:600;"><i class="fas fa-phone" style="margin-right:8px;color:#2a96e0;"></i><a href="tel:+91<?= htmlspecialchars($sitePhone) ?>" style="color:#fff;text-decoration:none;">+91-<?= htmlspecialchars($sitePhone) ?></a></p>
                    <?php elseif ($siteWhatsapp): ?>
                    <p style="font-size:14px;font-weight:600;"><i class="fab fa-whatsapp" style="margin-right:8px;color:#25D366;"></i><a href="https://wa.me/91<?= htmlspecialchars($siteWhatsapp) ?>" target="_blank" style="color:#fff;text-decoration:none;">+91-<?= htmlspecialchars($siteWhatsapp) ?></a></p>
                    <?php endif; ?>

                    <p style="font-size:14px;"><i class="fas fa-globe" style="margin-right:8px;color:#2a96e0;"></i>www.makaandekho.in</p>
                </div>
            </div>

            <!-- Popular Searches -->
            <div class="col-lg-3 col-md-6" style="margin-bottom:30px;">
                <h5 class="footer-heading">Popular Searches</h5>
                <ul class="footer-links">
                    <?php foreach ($footerPropertyTypes as $fpt): ?>
                    <li><a href="<?= $fpt['url'] ?>"><?= $fpt['label'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6" style="margin-bottom:30px;">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="<?= SITE_URL ?>terms.php">Terms of Use</a></li>
                    <li><a href="<?= SITE_URL ?>privacy.php">Privacy Policy</a></li>
                    <li><a href="<?= SITE_URL ?>contact.php">Contact Support</a></li>
                    <li><a href="<?= SITE_URL ?>about.php">About Us</a></li>
                    <li><a href="<?= SITE_URL ?>blogs.php">Blog</a></li>
                    <li><a href="<?= SITE_URL ?>properties.php">All Properties</a></li>
                </ul>
            </div>

            <!-- Newsletter + Social -->
            <div class="col-lg-4 col-md-6" style="margin-bottom:30px;">
                <h5 class="footer-heading">Sign Up for Our Newsletter</h5>
                <p class="footer-text">Get the latest property updates, market trends and real estate news directly in your inbox.</p>
                <form class="newsletter-form" id="newsletterForm">
                    <div class="input-group" id="nlFormFields">
                        <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        <button class="btn btn-primary" type="submit" id="nlBtn">Subscribe</button>
                    </div>
                    <div id="nlMsg" style="display:none;margin-top:10px;font-size:13px;padding:10px 14px;border-radius:8px;line-height:1.5;"></div>
                </form>

                <!-- Social Icons -->
                <div class="social-icons" style="margin-top:20px;">
                    <?php if ($facebook): ?>
                    <a href="<?= htmlspecialchars($facebook) ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <?php else: ?>
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>

                    <?php if ($instagram): ?>
                    <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <?php else: ?>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>

                    <?php if ($twitter): ?>
                    <a href="<?= htmlspecialchars($twitter) ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <?php else: ?>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>

                    <?php if ($youtube): ?>
                    <a href="<?= htmlspecialchars($youtube) ?>" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <?php else: ?>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <?php endif; ?>

                    <?php if ($linkedin): ?>
                    <a href="<?= htmlspecialchars($linkedin) ?>" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <?php else: ?>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container" style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;">
            <div class="footer-bottom-links">
                <a href="<?= SITE_URL ?>terms.php">Terms of Use</a>
                <a href="<?= SITE_URL ?>privacy.php">Privacy Policy</a>
            </div>
            <p>
                <?php if ($footerText): ?>
                    <?= htmlspecialchars($footerText) ?>
                <?php else: ?>
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All Rights Reserved
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Scroll to Top -->
    <a href="#" class="scroll-top" id="scrollTop"><i class="fas fa-arrow-up"></i></a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Waypoints -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
<!-- Custom JS -->
<script src="<?= SITE_URL ?>assets/js/main.js"></script>

<script>
// ── Client-side Form Validator ──
var MKV = {
    email: function(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); },
    phone: function(v) { return /^[6-9][0-9]{9}$/.test(v.replace(/\D/g,'')); },
    name:  function(v) { return v.trim().length >= 2 && v.trim().length <= 100; },
    noScript: function(v) { return !/<script|javascript:|on\w+\s*=/i.test(v); },

    showErr: function(el, msg) {
        el.style.borderColor = '#dc2626';
        var e = el.parentNode.querySelector('.mk-err');
        if (!e) { e = document.createElement('small'); e.className='mk-err'; e.style.cssText='color:#dc2626;font-size:11px;display:block;margin-top:2px;'; el.parentNode.appendChild(e); }
        e.textContent = msg;
    },
    clearErr: function(el) {
        el.style.borderColor = '';
        var e = el.parentNode.querySelector('.mk-err');
        if (e) e.remove();
    },

    validate: function(form) {
        var ok = true, self = this;
        form.querySelectorAll('.mk-err').forEach(function(e){ e.remove(); });
        form.querySelectorAll('input,textarea,select').forEach(function(e){ e.style.borderColor=''; });

        form.querySelectorAll('[data-v]').forEach(function(el) {
            var rules = el.getAttribute('data-v').split('|'), val = el.value;
            for (var i=0; i<rules.length; i++) {
                var r = rules[i];
                if (r==='req' && !val.trim()) { self.showErr(el, el.getAttribute('data-msg')||'This field is required.'); ok=false; break; }
                if (r==='email' && val && !self.email(val)) { self.showErr(el, 'Enter a valid email.'); ok=false; break; }
                if (r==='phone' && val && !self.phone(val)) { self.showErr(el, 'Enter valid 10-digit mobile no.'); ok=false; break; }
                if (r==='name' && val && !self.name(val)) { self.showErr(el, 'Name must be 2-100 characters.'); ok=false; break; }
                if (r==='safe' && !self.noScript(val)) { self.showErr(el, 'Invalid characters detected.'); ok=false; break; }
            }
        });
        return ok;
    }
};
// Live validation on blur
document.addEventListener('focusout', function(e) {
    var el = e.target;
    if (el.hasAttribute && el.hasAttribute('data-v')) {
        MKV.clearErr(el);
        var rules = el.getAttribute('data-v').split('|'), val = el.value;
        for (var i=0; i<rules.length; i++) {
            var r = rules[i];
            if (r==='req' && !val.trim()) { MKV.showErr(el, el.getAttribute('data-msg')||'Required.'); break; }
            if (r==='email' && val && !MKV.email(val)) { MKV.showErr(el, 'Invalid email.'); break; }
            if (r==='phone' && val && !MKV.phone(val)) { MKV.showErr(el, 'Invalid phone.'); break; }
            if (r==='name' && val && !MKV.name(val)) { MKV.showErr(el, '2-100 chars required.'); break; }
            if (r==='safe' && !MKV.noScript(val)) { MKV.showErr(el, 'Invalid characters.'); break; }
        }
    }
});

// Post Property Modal AJAX
document.addEventListener('DOMContentLoaded', function() {
    var ppForm = document.getElementById('postPropertyForm');
    if (ppForm) {
        // Role tab toggle
        ppForm.querySelectorAll('.pp-role').forEach(function(r) {
            r.addEventListener('click', function() {
                ppForm.querySelectorAll('.pp-role').forEach(function(x) { x.classList.remove('active'); });
                this.classList.add('active');
            });
        });

        ppForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('ppSubmitBtn');
            var errBox = document.getElementById('ppError');

            // Client-side validation
            if (!MKV.validate(ppForm)) return;

            btn.disabled = true;
            btn.textContent = 'Submitting...';
            errBox.style.display = 'none';

            var fd = new FormData(ppForm);
            fetch(SITE_URL + 'ajax-post-property.php', { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    btn.disabled = false;
                    btn.textContent = 'Post Property Now';
                    if (data.success) {
                        ppForm.style.display = 'none';
                        if (data.new_user && data.password) {
                            // New user — show registration success with credentials
                            document.getElementById('ppCredEmail').textContent = data.email;
                            document.getElementById('ppCredPass').textContent = data.password;
                            document.getElementById('ppSuccessNew').style.display = 'block';
                            document.getElementById('ppSuccessExisting').style.display = 'none';
                        } else {
                            // Existing user — show property submitted
                            document.getElementById('ppSuccessNew').style.display = 'none';
                            document.getElementById('ppSuccessExisting').style.display = 'block';
                        }
                    } else {
                        errBox.textContent = data.message || 'Something went wrong.';
                        errBox.style.display = 'block';
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.textContent = 'Post Property Now';
                    errBox.textContent = 'Network error. Please try again.';
                    errBox.style.display = 'block';
                });
        });
    }

    // Copy password to clipboard
    window.copyPassword = function() {
        var pass = document.getElementById('ppCredPass').textContent;
        navigator.clipboard.writeText(pass).then(function() {
            alert('Password copied!');
        });
    };

    // Reset post form
    window.resetPostForm = function() {
        var ppForm = document.getElementById('postPropertyForm');
        ppForm.reset();
        ppForm.style.display = '';
        document.getElementById('ppSuccessNew').style.display = 'none';
        document.getElementById('ppSuccessExisting').style.display = 'none';
        ppForm.querySelectorAll('.pp-role').forEach(function(x) { x.classList.remove('active'); });
        ppForm.querySelector('.pp-role').classList.add('active');
    };

    // Newsletter form
    var nlForm = document.getElementById('newsletterForm');
    if (nlForm) {
        nlForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('nlBtn');
            var msg = document.getElementById('nlMsg');
            var emailInput = nlForm.querySelector('input[name="email"]');
            var email = emailInput.value.trim();

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                msg.style.display = 'block';
                msg.style.background = '#fef2f2';
                msg.style.color = '#dc2626';
                msg.textContent = 'Please enter a valid email address.';
                return;
            }

            btn.disabled = true;
            btn.textContent = '...';
            msg.style.display = 'none';

            var fd = new FormData();
            fd.append('email', email);

            var fields = document.getElementById('nlFormFields');

            fetch(SITE_URL + 'ajax-newsletter.php', { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    btn.disabled = false;
                    btn.textContent = 'Subscribe';
                    msg.style.display = 'block';
                    if (data.success) {
                        fields.style.display = 'none';
                        msg.style.background = 'rgba(16,185,129,.15)';
                        msg.style.color = '#10b981';
                        msg.style.border = '1px solid rgba(16,185,129,.3)';
                        msg.innerHTML = '<i class="fas fa-check-circle" style="margin-right:6px;"></i>' + data.message;
                        setTimeout(function() {
                            fields.style.display = '';
                            msg.style.display = 'none';
                            emailInput.value = '';
                        }, 4000);
                    } else {
                        msg.style.background = 'rgba(239,68,68,.1)';
                        msg.style.color = '#ef4444';
                        msg.style.border = '1px solid rgba(239,68,68,.2)';
                        msg.innerHTML = '<i class="fas fa-info-circle" style="margin-right:6px;"></i>' + data.message;
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.textContent = 'Subscribe';
                    msg.style.display = 'block';
                    msg.style.background = '#fef2f2';
                    msg.style.color = '#dc2626';
                    msg.textContent = 'Network error. Please try again.';
                });
        });
    }

    // Scroll to top
    var scrollBtn = document.getElementById('scrollTop');
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            scrollBtn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });
        scrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
</body>
</html>
