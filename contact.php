<?php
/**
 * ==============================================================================
 * CONTACT & INQUIRIES PAGE (contact.php)
 * Contact form, speaking inquiry selector, newsletter signup & social cards
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "Contact Kelvin Kibenje | Speaker • Author • Investor";
$page_description = "Get in touch with Kelvin Kibenje for keynote speaking invitations, corporate financial literacy training, book inquiries, or mentorship in Dar es Salaam, Tanzania.";

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $form_type = trim($_POST['form_type'] ?? 'contact');

        if ($form_type === 'newsletter') {
            $email = trim($_POST['subscriber_email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_msg = "Please enter a valid email address.";
            } else {
                try {
                    db_execute(
                        "INSERT INTO subscribers (email, status, created_at) VALUES (?, 'active', NOW())
                         ON DUPLICATE KEY UPDATE status = 'active'",
                        [$email]
                    );
                    $success_msg = "Welcome! You have successfully subscribed to Kelvin's financial freedom newsletter.";
                } catch (Exception $e) {
                    $error_msg = "Could not subscribe at this time.";
                }
            }
        } else {
            // General Contact Form
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $subject = trim($_POST['subject'] ?? 'General Inquiry');
            $message = trim($_POST['message'] ?? '');
            $inquiry_type = trim($_POST['inquiry_type'] ?? 'General Contact');

            if (empty($name) || empty($email) || empty($message)) {
                $error_msg = "Please provide your name, email, and message.";
            } else {
                try {
                    db_execute(
                        "INSERT INTO inquiries (type, name, email, phone, subject, message, is_read, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, 0, NOW())",
                        [$inquiry_type, $name, $email, $phone, $subject, $message]
                    );

                    // Send email notification to Kelvin
                    $html_email = "<h3>New Website Message (" . h($inquiry_type) . ")</h3>"
                                . "<p><strong>From:</strong> " . h($name) . " (" . h($email) . ")</p>"
                                . "<p><strong>Phone:</strong> " . h($phone) . "</p>"
                                . "<p><strong>Subject:</strong> " . h($subject) . "</p>"
                                . "<h4>Message:</h4><p>" . nl2br(h($message)) . "</p>";
                    send_site_email(SITE_EMAIL, "[" . $inquiry_type . "] " . $subject, $html_email, $email, $name);

                    $success_msg = "Thank you, " . $name . "! Your message has been sent to Kelvin's office. We will reply shortly.";
                } catch (Exception $e) {
                    $error_msg = "Could not send your message. Please reach out via WhatsApp.";
                }
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- CONTACT HERO -->
<section class="section section-alt" style="padding: 60px 0 60px;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 20px;">
            <div class="section-subtitle">Get In Touch</div>
            <h1 class="section-title">Connect With Kelvin Kibenje</h1>
            <p class="section-desc">
                We welcome speaking invitations, media interviews, book orders, and partnership opportunities across Tanzania and East Africa.
            </p>
        </div>
    </div>
</section>

<!-- CONTACT GRID -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start;" class="contact-layout">
            <!-- Left: Contact Information Cards -->
            <div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 26px; margin-bottom: 16px;">
                    Office &amp; Direct Channels
                </h3>
                <p style="color: var(--slate-300); margin-bottom: 32px;">
                    Whether you want to order books for your bookstore, invite Kelvin to speak at your company, or discuss financial education consulting—here is how to reach us.
                </p>

                <!-- Location Card -->
                <div style="background: var(--navy-800); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-md); padding: 24px; margin-bottom: 20px; display: flex; gap: 16px; align-items: center;">
                    <div style="font-size: 28px; color: var(--gold-500);">📍</div>
                    <div>
                        <strong style="color: var(--white); display: block;">Office Location</strong>
                        <span style="color: var(--slate-300); font-size: 14px;">
                            <?= h(get_setting('office_location', 'Makumbusho Plaza, 1st Floor, Dar es Salaam, Tanzania')) ?>
                        </span>
                    </div>
                </div>

                <!-- WhatsApp Card -->
                <div style="background: var(--navy-800); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-md); padding: 24px; margin-bottom: 20px; display: flex; gap: 16px; align-items: center;">
                    <div style="font-size: 28px; color: #25d366;">📱</div>
                    <div>
                        <strong style="color: var(--white); display: block;">WhatsApp &amp; Phone (Orders &amp; Inquiries)</strong>
                        <a href="https://wa.me/<?= h(get_setting('whatsapp_number', '255677853595')) ?>" target="_blank" style="color: var(--gold-400); font-size: 15px; font-weight: 600;">
                            <?= h(get_setting('contact_phone', '+255 677 853 595')) ?>
                        </a>
                    </div>
                </div>

                <!-- Email Card -->
                <div style="background: var(--navy-800); border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-md); padding: 24px; margin-bottom: 20px; display: flex; gap: 16px; align-items: center;">
                    <div style="font-size: 28px; color: var(--gold-500);">✉️</div>
                    <div>
                        <strong style="color: var(--white); display: block;">Official Email Address</strong>
                        <a href="mailto:<?= h(get_setting('contact_email', 'info@kelvinkibenje.com')) ?>" style="color: var(--slate-300); font-size: 14px;">
                            <?= h(get_setting('contact_email', 'info@kelvinkibenje.com')) ?>
                        </a>
                    </div>
                </div>

                <!-- Social Proof Card -->
                <div style="background: var(--navy-950); border: 1px solid var(--gold-500); border-radius: var(--radius-md); padding: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <strong style="color: var(--gold-400); display: block;">499K+ Instagram Community</strong>
                        <span style="font-size: 13px; color: var(--slate-300);">Follow daily financial &amp; business lessons</span>
                    </div>
                    <a href="<?= h(get_setting('instagram_url', 'https://instagram.com/kelvinkibenje')) ?>" target="_blank" class="btn btn-gold" style="padding: 10px 18px; font-size: 13px;">
                        @kelvinkibenje
                    </a>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div>
                <div style="background: var(--navy-800); padding: 36px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.1);">
                    <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 24px; margin-bottom: 20px;">
                        Send Us a Message
                    </h3>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success"><?= h($success_msg) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger"><?= h($error_msg) ?></div>
                    <?php endif; ?>

                    <form action="/contact.php" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_type" value="contact">

                        <div class="form-group">
                            <label class="form-label">Inquiry Type *</label>
                            <select name="inquiry_type" class="form-control" required>
                                <option value="General Contact">General Question / Feedback</option>
                                <option value="Speaking Booking" <?= (isset($_GET['type']) && $_GET['type'] === 'speaking') ? 'selected' : '' ?>>Speaking Invitation / Conference</option>
                                <option value="Consultation">Business / Financial Consultation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Your Full Name *</label>
                            <input type="text" name="name" required class="form-control" placeholder="e.g. Amina Bakari">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" required class="form-control" placeholder="e.g. amina@gmail.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone / WhatsApp</label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +255 784 000 000">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject *</label>
                            <input type="text" name="subject" required class="form-control" placeholder="e.g. Book Order in Arusha / Keynote Speaking">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Your Message *</label>
                            <textarea name="message" required class="form-control" placeholder="How can we help you win today?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-gold btn-block" style="padding: 14px;">
                            Send Message to Kelvin's Office
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
