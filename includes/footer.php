<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <div class="container">
        <div class="row gy-4">
            <!-- Brand -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-brand">
                    <div class="logo-text text-white">
                        <span class="logo-icon"><i class="fas fa-home"></i></span>
                        <span>makaan<br><strong>dekho</strong><small>.in</small></span>
                    </div>
                    <p class="mt-3"><?= htmlspecialchars($settings['address'] ?? 'Your address will come here') ?></p>
                    <p><?= htmlspecialchars($settings['email'] ?? 'info@makaandekho.in') ?></p>
                    <p class="fw-bold"><?= !empty($settings['whatsapp_number']) ? '+91-' . htmlspecialchars($settings['whatsapp_number']) : '+91-9876543210' ?></p>
                    <p>www.makaandekho.com</p>
                </div>
            </div>
            <!-- Popular Searches -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Popular Searches</h5>
                <ul class="footer-links">
                    <li><a href="#">Apartment for Rent</a></li>
                    <li><a href="#">Apartment Low to hide</a></li>
                    <li><a href="#">Offices for Buy</a></li>
                    <li><a href="#">Offices for Rent</a></li>
                </ul>
            </div>
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-heading">Quick links</h5>
                <ul class="footer-links">
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Contact Support</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <!-- Newsletter -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-heading">Sign Up for Our Newsletter</h5>
                <p class="footer-text">Lorem ipsum dolor sit amet, consecte tur cing elit. Suspe ndisse suscipit sagittis</p>
                <form class="newsletter-form" onsubmit="return false;">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
                <div class="social-icons mt-3">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-skype"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <div class="footer-bottom-links">
                <a href="#">Terms of Use</a>
                <a href="#">Privacy Policy</a>
            </div>
            <p>&copy; <?= date('Y') ?> Makaandekho. All Rights Reserved</p>
        </div>
    </div>
    <!-- Scroll to Top -->
    <a href="#" class="scroll-top" id="scrollTop"><i class="fas fa-arrow-up"></i></a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Custom JS -->

<!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Waypoints plugin (needed for your code) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

<script src="<?= SITE_URL ?>assets/js/main.js"></script>
</body>
</html>
