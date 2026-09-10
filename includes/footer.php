<?php
/**
 * ==============================================================================
 * SHARED SITE FOOTER (footer.php)
 * Responsive footer with newsletter form, social proof & mobile menu JS
 * ==============================================================================
 */
?>
</main> <!-- End of main-content -->

<!-- Newsletter / Community Section -->
<section class="newsletter-banner">
    <div class="container newsletter-inner">
        <div class="newsletter-text">
            <h3>Join 499K+ Winners on the Financial Freedom Journey</h3>
            <p>Get Kelvin Kibenje's latest insights on budgeting, China importing, and Tanzanian wealth compounding delivered to your inbox.</p>
        </div>
        <form action="/contact.php" method="POST" class="newsletter-form">
            <?= csrf_field() ?>
            <input type="hidden" name="form_type" value="newsletter">
            <input type="email" name="subscriber_email" placeholder="Enter your email address..." required class="newsletter-input">
            <button type="submit" class="btn btn-gold">Subscribe Free</button>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container footer-grid">
        <!-- Col 1: Bio -->
        <div class="footer-col">
            <a href="/" class="footer-brand">
                <span class="logo-name">KELVIN KIBENJE</span>
                <span class="logo-tagline">Speaker • Author • Investor</span>
            </a>
            <p class="footer-bio">
                Certified Financial Educator by Bank of Tanzania (BOT) and two-time Golden Man of the Year Award recipient. Helping dreamers become doers, and doers become winners.
            </p>
            <div class="footer-socials">
                <a href="<?= h(get_setting('instagram_url')) ?>" target="_blank" rel="noopener" class="social-icon" title="Instagram">IG</a>
                <a href="<?= h(get_setting('youtube_url')) ?>" target="_blank" rel="noopener" class="social-icon" title="YouTube">YT</a>
                <a href="<?= h(get_setting('facebook_url')) ?>" target="_blank" rel="noopener" class="social-icon" title="Facebook">FB</a>
                <a href="<?= h(get_setting('threads_url')) ?>" target="_blank" rel="noopener" class="social-icon" title="Threads">TH</a>
            </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="footer-col">
            <h4 class="footer-title">Navigation</h4>
            <ul class="footer-links">
                <li><a href="/">Home</a></li>
                <li><a href="/about.php">About Kelvin</a></li>
                <li><a href="/speaking.php">Speaking &amp; Impact</a></li>
                <li><a href="/books.php">Books &amp; Guides</a></li>
                <li><a href="/blog.php">Financial Articles</a></li>
                <li><a href="/contact.php">Contact &amp; Bookings</a></li>
            </ul>
        </div>

        <!-- Col 3: Books -->
        <div class="footer-col">
            <h4 class="footer-title">Featured Books</h4>
            <ul class="footer-links">
                <li><a href="/book.php?slug=china-to-tanzania">China to Tanzania</a></li>
                <li><a href="/book.php?slug=elimu-ya-fedha-na-biashara">Elimu ya Fedha na Biashara</a></li>
                <li><a href="/book.php?slug=m-wekeza">M-Wekeza (Safe Investment)</a></li>
                <li><a href="/books.php">View Complete Bookshop</a></li>
            </ul>
        </div>

        <!-- Col 4: Direct Contact -->
        <div class="footer-col">
            <h4 class="footer-title">Office &amp; Contact</h4>
            <p class="footer-contact-item">
                <strong>Location:</strong><br>
                <?= h(get_setting('office_location', 'Makumbusho Plaza, 1st Floor, Dar es Salaam, Tanzania')) ?>
            </p>
            <p class="footer-contact-item">
                <strong>WhatsApp / Phone:</strong><br>
                <a href="https://wa.me/<?= h(get_setting('whatsapp_number', '255677853595')) ?>" target="_blank" class="contact-link">
                    <?= h(get_setting('contact_phone', '+255 677 853 595')) ?>
                </a>
            </p>
            <p class="footer-contact-item">
                <strong>Email:</strong><br>
                <a href="mailto:<?= h(get_setting('contact_email', 'info@kelvinkibenje.com')) ?>" class="contact-link">
                    <?= h(get_setting('contact_email', 'info@kelvinkibenje.com')) ?>
                </a>
            </p>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p class="copyright">
                <?= h(get_setting('footer_copyright', '© 2026 Kelvin Kibenje Kenedy Kyaluoko. All rights reserved.')) ?>
            </p>
            <p class="footer-disclaimer">
                Certified Financial Educator by BOT (Bank of Tanzania) • Golden Man of the Year (22/23)
            </p>
            <p class="footer-admin-link">
                <a href="/admin/login.php">Staff Login</a>
            </p>
        </div>
    </div>
</footer>

<!-- JavaScript for Interactive UI -->
<script src="/assets/js/main.js?v=2026.1"></script>
</body>
</html>
