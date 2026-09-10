<?php
/**
 * ==============================================================================
 * SPEAKING & IMPACT PAGE (speaking.php)
 * Speaking Topics, Vodacom 25th Anniversary Training Spotlight & Booking Form
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "Speaking & Corporate Impact | Kelvin Kibenje";
$page_description = "Book Kelvin Kibenje for keynote speaking, corporate financial wellness training, and youth mentorship. Trusted by organizations including Vodacom Tanzania.";

// Handle speaking booking form submission
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_speaker') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please refresh and try again.";
    } else {
        $name = trim($_POST['client_name'] ?? '');
        $email = trim($_POST['client_email'] ?? '');
        $phone = trim($_POST['client_phone'] ?? '');
        $organization = trim($_POST['organization'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $location = trim($_POST['event_location'] ?? '');
        $details = trim($_POST['event_details'] ?? '');

        if (empty($name) || empty($email) || empty($details)) {
            $error_msg = "Please provide your name, email, and event details.";
        } else {
            $subject = "Speaking Booking Request from " . $organization . " (" . $name . ")";
            $full_message = "Organization: " . $organization . "\n"
                          . "Event Date: " . $event_date . "\n"
                          . "Location: " . $location . "\n"
                          . "Phone: " . $phone . "\n\n"
                          . "Details:\n" . $details;

            try {
                db_execute(
                    "INSERT INTO inquiries (type, name, email, phone, subject, message, is_read, created_at) 
                     VALUES ('Speaking Booking', ?, ?, ?, ?, ?, 0, NOW())",
                    [$name, $email, $phone, $subject, $full_message]
                );

                // Send email notification
                $html_email = "<h3>New Speaking Booking Invitation</h3>"
                            . "<p><strong>From:</strong> " . h($name) . " (" . h($organization) . ")</p>"
                            . "<p><strong>Email:</strong> " . h($email) . " | <strong>Phone:</strong> " . h($phone) . "</p>"
                            . "<p><strong>Proposed Date:</strong> " . h($event_date) . " | <strong>Location:</strong> " . h($location) . "</p>"
                            . "<h4>Event Summary:</h4><p>" . nl2br(h($details)) . "</p>";
                send_site_email(SITE_EMAIL, $subject, $html_email, $email, $name);

                $success_msg = "Thank you! Your speaking invitation has been received. Kelvin's team will contact you within 24 hours.";
            } catch (Exception $e) {
                $error_msg = "Could not save your inquiry. Please contact us via WhatsApp direct.";
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<!-- SPEAKING HERO -->
<section class="section section-alt" style="padding: 60px 0 70px;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 20px;">
            <div class="section-subtitle">Speaking, Training &amp; Impact</div>
            <h1 class="section-title">Turning Dreamers Into Winners</h1>
            <p class="section-desc">
                From corporate agent training to thousands of youth entrepreneurs—Kelvin Kibenje delivers BOT-certified financial literacy that moves audiences to action.
            </p>
        </div>
    </div>
</section>

<!-- VODACOM 25TH ANNIVERSARY M-PESA TRAINING FEATURE -->
<section class="section">
    <div class="container">
        <div class="spotlight-card" style="margin-bottom: 80px;">
            <div>
                <span class="spotlight-tag">Corporate Engagement • Temeke, Dar es Salaam</span>
                <h2 class="spotlight-title">Vodacom 25th Anniversary: M-Pesa Agent Financial Education &amp; Investment Training</h2>
                <p style="color: var(--slate-300); margin-bottom: 20px; font-size: 16px;">
                    As part of <strong>Vodacom Tanzania’s 25th Anniversary celebrations</strong>, Kelvin Kibenje was invited to conduct specialized financial literacy and investment seminars for Vodacom M-Pesa agents in Temeke and Greater Dar es Salaam.
                </p>
                <p style="color: var(--slate-300); margin-bottom: 20px; font-size: 15px;">
                    M-Pesa agents represent the frontline of Tanzania's digital economy. Kelvin's curriculum focused on:
                </p>
                <ul style="color: var(--white); margin-left: 20px; margin-bottom: 28px; line-height: 1.8;">
                    <li><strong>Capital Protection:</strong> Maintaining strict separation between working float and personal expenditure.</li>
                    <li><strong>Avoiding Loan Dependency:</strong> Navigating mobile credit responsibly without eroding agent commission margins.</li>
                    <li><strong>Wealth Compounding:</strong> Using monthly commission surpluses to invest in secure UTT AMIS funds and Treasury Bonds.</li>
                </ul>
                <a href="#bookingForm" class="btn btn-gold">Invite Kelvin for Your Organization</a>
            </div>
            <div>
                <img src="/images/event-vodacom.jpg" alt="Kelvin Kibenje training Vodacom M-Pesa agents" class="spotlight-img">
            </div>
        </div>

        <!-- SPEAKING TOPICS GRID -->
        <div class="section-header">
            <div class="section-subtitle">Keynote &amp; Seminar Programs</div>
            <h2 class="section-title">Signature Speaking Topics</h2>
            <p class="section-desc">
                Each program is tailored to the audience—from corporate executives and banking agents to university students and small business owners.
            </p>
        </div>

        <div class="books-grid">
            <div class="book-card" style="padding: 32px;">
                <span style="color: var(--gold-400); font-weight: 700; font-size: 13px; text-transform: uppercase;">Topic 1</span>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin: 12px 0;">
                    Financial Discipline for Corporate &amp; SME Networks
                </h3>
                <p style="color: var(--slate-300); font-size: 15px; margin-bottom: 20px;">
                    Designed for corporate networks, agency banking teams, and SME summits. Teaches cash flow governance, debt eradication, and sustainable profit management.
                </p>
                <div style="font-size: 13px; color: var(--gold-500); font-weight: 600;">
                    ★ Ideal for: Banks, Telecoms, Corporate Wellness
                </div>
            </div>

            <div class="book-card" style="padding: 32px;">
                <span style="color: var(--gold-400); font-weight: 700; font-size: 13px; text-transform: uppercase;">Topic 2</span>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin: 12px 0;">
                    The China-to-Tanzania Import Masterclass
                </h3>
                <p style="color: var(--slate-300); font-size: 15px; margin-bottom: 20px;">
                    An interactive training on international trade. How Tanzanian entrepreneurs source verified goods, manage logistics, avoid scams, and price for profit.
                </p>
                <div style="font-size: 13px; color: var(--gold-500); font-weight: 600;">
                    ★ Ideal for: Trade Forums, Business Seminars, Kariakoo Networks
                </div>
            </div>

            <div class="book-card" style="padding: 32px;">
                <span style="color: var(--gold-400); font-weight: 700; font-size: 13px; text-transform: uppercase;">Topic 3</span>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin: 12px 0;">
                    The Compound Effect: Safe Investment in Tanzania
                </h3>
                <p style="color: var(--slate-300); font-size: 15px; margin-bottom: 20px;">
                    Demystifying UTT AMIS, Government Bonds, and DSE dividend stocks. How young professionals and families can build generations of financial independence.
                </p>
                <div style="font-size: 13px; color: var(--gold-500); font-weight: 600;">
                    ★ Ideal for: Universities, Youth Leadership Forums, Conferences
                </div>
            </div>
        </div>
    </div>
</section>

<!-- IMPACT GALLERY -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Live Seminars &amp; Workshops</div>
            <h2 class="section-title">The Classroom of Action</h2>
            <p class="section-desc">
                Whether delivering keynote speeches to hundreds of delegates or intimate boardroom sessions, Kelvin brings warmth, clarity, and authority.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 32px; align-items: center;">
            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.15); background: var(--navy-950);">
                <img src="/images/event-seminar.jpg" alt="Vine Tanzania Seminar poster with Kelvin Kibenje" style="width: 100%; height: auto; display: block;">
                <div style="padding: 20px; font-size: 14px; color: var(--slate-300);">
                    <strong style="color: var(--white); font-size: 16px; display: block; margin-bottom: 4px;">Semina ya Elimu ya Fedha, Uwekezaji &amp; Afya</strong>
                    Keynote session empowering youth with integrated financial literacy and investment strategies in Dar es Salaam.
                </div>
            </div>

            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.15); background: var(--navy-950);">
                <img src="/images/speaker-1.jpg" alt="Kelvin speaking on stage" style="width: 100%; height: 380px; object-fit: cover; display: block;">
                <div style="padding: 20px; font-size: 14px; color: var(--slate-300);">
                    <strong style="color: var(--white); font-size: 16px; display: block; margin-bottom: 4px;">Grassroots &amp; Executive Communication</strong>
                    Two-Time Golden Man of the Year (22/23) recognized for communicating complex financial concepts with simplicity.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SPEAKING BOOKING FORM SECTION -->
<section class="section" id="bookingForm">
    <div class="container" style="max-width: 760px;">
        <div class="section-header">
            <div class="section-subtitle">Book For Your Event</div>
            <h2 class="section-title">Invite Kelvin Kibenje to Speak</h2>
            <p class="section-desc">
                Fill out the invitation form below. Our team will review your event details and respond within 24 hours.
            </p>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?= h($success_msg) ?></div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger"><?= h($error_msg) ?></div>
        <?php endif; ?>

        <form action="/speaking.php#bookingForm" method="POST" style="background: var(--navy-800); padding: 40px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.1);">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="book_speaker">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="client_name" required class="form-control" placeholder="e.g. David Lyimo">
                </div>
                <div class="form-group">
                    <label class="form-label">Organization / Company *</label>
                    <input type="text" name="organization" required class="form-control" placeholder="e.g. Dar Tech Solutions">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="client_email" required class="form-control" placeholder="e.g. d.lyimo@company.co.tz">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone / WhatsApp *</label>
                    <input type="tel" name="client_phone" required class="form-control" placeholder="e.g. +255 713 000 000">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Proposed Event Date *</label>
                    <input type="date" name="event_date" required class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Event Location / City *</label>
                    <input type="text" name="event_location" required class="form-control" placeholder="e.g. Dar es Salaam / Arusha">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Event Summary &amp; Expected Audience *</label>
                <textarea name="event_details" required class="form-control" placeholder="Please describe the event theme, target audience (e.g., 200 corporate staff or youth entrepreneurs), and specific learning goals..."></textarea>
            </div>

            <div style="text-align: right; margin-top: 10px;">
                <button type="submit" class="btn btn-gold" style="padding: 14px 36px;">Submit Speaking Invitation</button>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
