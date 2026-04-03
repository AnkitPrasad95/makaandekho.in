/* ============================================================
   MakaanDekho.in – Main JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // ---- Search Tab Toggle ----
    document.querySelectorAll('.search-tabs .tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.search-tabs .tab-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            var listingInput = document.getElementById('listingType');
            if (listingInput) {
                listingInput.value = this.getAttribute('data-type');
            }
        });
    });

    // ---- Location Slider ----
    if (document.querySelector('.location-slider')) {
        new Swiper('.location-slider', {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.loc-next',
                prevEl: '.loc-prev',
            },
            breakpoints: {
                0:   { slidesPerView: 1 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                992: { slidesPerView: 4 },
            }
        });
    }

    // ---- Property Sliders ----
    document.querySelectorAll('.property-slider').forEach(function (el, i) {
        new Swiper(el, {
            slidesPerView: 3,
            spaceBetween: 25,
            loop: true,
            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },
            breakpoints: {
                0:   { slidesPerView: 1 },
                576: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
            }
        });
    });

    // ---- Scroll To Top ----
    var scrollBtn = document.getElementById('scrollTop');
    if (scrollBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        scrollBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---- Testimonial Slider ----
    if (document.querySelector('.testimonial-slider')) {
        new Swiper('.testimonial-slider', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            pagination: { el: '.testimonial-slider .swiper-pagination', clickable: true },
            breakpoints: {
                0:   { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
            }
        });
    }

    // ---- Navbar shadow on scroll ----
    window.addEventListener('scroll', function () {
        var nav = document.querySelector('.navbar');
        if (nav) {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }
    });

    // ---- Post Property Modal: Role Tabs ----
    document.querySelectorAll('.pp-role').forEach(function (label) {
        label.addEventListener('click', function () {
            document.querySelectorAll('.pp-role').forEach(function (l) {
                l.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // ---- Post Property Modal: AJAX Submit ----
    var ppForm = document.getElementById('postPropertyForm');
    if (ppForm) {
        ppForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = document.getElementById('ppSubmitBtn');
            var errBox = document.getElementById('ppError');
            errBox.style.display = 'none';
            btn.disabled = true;
            btn.textContent = 'Submitting...';

            var formData = new FormData(ppForm);

            fetch(SITE_URL + 'ajax-post-property.php', {
                method: 'POST',
                body: formData,
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    ppForm.style.display = 'none';
                    document.getElementById('ppSuccess').style.display = 'block';
                } else {
                    errBox.textContent = data.message;
                    errBox.style.display = 'block';
                }
                btn.disabled = false;
                btn.textContent = 'Post Property Now';
            })
            .catch(function () {
                errBox.textContent = 'Something went wrong. Please try again.';
                errBox.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Post Property Now';
            });
        });
    }

});

// ---- Reset Post Property Form ----
function resetPostForm() {
    var form = document.getElementById('postPropertyForm');
    if (form) {
        form.reset();
        form.style.display = 'block';
        document.querySelectorAll('.pp-role').forEach(function (l, i) {
            l.classList.toggle('active', i === 0);
        });
    }
    document.getElementById('ppSuccess').style.display = 'none';
    document.getElementById('ppError').style.display = 'none';
}
