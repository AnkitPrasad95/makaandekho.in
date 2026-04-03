<?php
require_once __DIR__ . '/includes/db.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name) $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (!$message) $errors[] = 'Message is required.';

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO enquiries (name, email, phone, message, status) VALUES (?,?,?,?,?)")
            ->execute([$name, $email, $phone, "[$subject] $message", 'new']);
        $success = true;
    }
}

$pageTitle = 'Contact Us | MakaanDekho';
include __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
    <div class="container"><h1>Contact Us</h1><p>Get in touch with our team</p></div>
</div>

<section class="static-page">
<div class="container">
    <div class="row g-4">
        <!-- Contact Info Cards -->
        <div class="col-md-4">
            <div class="contact-info-card">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h5>Our Office</h5>
                <p><?= htmlspecialchars($settings['address'] ?? 'India') ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="contact-info-card">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <h5>Call Us</h5>
                <p><?= !empty($settings['whatsapp_number']) ? '+91-' . $settings['whatsapp_number'] : '+91-XXXXXXXXXX' ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="contact-info-card">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h5>Email Us</h5>
                <p><?= htmlspecialchars($settings['email'] ?? 'info@makaandekho.in') ?></p>
            </div>
        </div>

        <!-- Contact Form + Map -->
        <div class="col-lg-7">
            <div class="page-content-card">
                <h3 style="margin-bottom:20px;">Send us a Message</h3>
                <?php if ($success): ?>
                <div class="alert alert-success" style="border-radius:10px;">
                    <i class="fas fa-check-circle me-2"></i>Thank you! Your message has been sent. We'll get back to you soon.
                </div>
                <?php else: ?>
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" style="border-radius:10px;font-size:13px;">
                    <?php foreach($errors as $e): ?><p class="mb-1"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
                </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6"><input type="text" name="name" class="pd-input" placeholder="Your Name *" value="<?= htmlspecialchars($_POST['name']??'') ?>" required></div>
                        <div class="col-md-6"><input type="email" name="email" class="pd-input" placeholder="Your Email *" value="<?= htmlspecialchars($_POST['email']??'') ?>" required></div>
                        <div class="col-md-6"><input type="tel" name="phone" class="pd-input" placeholder="Phone Number" value="<?= htmlspecialchars($_POST['phone']??'') ?>"></div>
                        <div class="col-md-6"><input type="text" name="subject" class="pd-input" placeholder="Subject" value="<?= htmlspecialchars($_POST['subject']??'') ?>"></div>
                        <div class="col-12"><textarea name="message" class="pd-input" rows="5" placeholder="Your Message *" required><?= htmlspecialchars($_POST['message']??'') ?></textarea></div>
                        <div class="col-12"><button type="submit" class="auth-submit-btn"><i class="fas fa-paper-plane me-2"></i>Send Message</button></div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="page-content-card" style="padding:0;overflow:hidden;height:100%;min-height:350px;">
                <iframe src="https://maps.google.com/maps?q=<?= urlencode($settings['address'] ?? 'India') ?>&output=embed" width="100%" height="100%" style="border:0;min-height:400px;" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>

        <!-- WhatsApp CTA -->
        <?php if (!empty($settings['whatsapp_number'])): ?>
        <div class="col-12 text-center" style="margin-top:20px;">
            <a href="https://wa.me/91<?= $settings['whatsapp_number'] ?>?text=<?= urlencode('Hi, I want to know more about MakaanDekho properties.') ?>"
               target="_blank" class="btn btn-success btn-lg" style="border-radius:30px;font-weight:600;padding:14px 40px;">
                <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
