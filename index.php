<?php
/**
 * ==============================================================================
 * HOMEPAGE (index.php)
 * Photography-Led Hero, Books Carousel, Vodacom Spotlight & Latest Articles
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "Kelvin Kibenje Kenedy Kyaluoko | Speaker • Author • Investor";
$page_description = get_setting('site_about_summary');

// Fetch featured books from database
$featured_books = [];
try {
    $featured_books = db_fetch_all("SELECT * FROM books WHERE is_available = 1 ORDER BY order_index ASC LIMIT 3");
} catch (Exception $e) {
    // Graceful fallback if database is not yet initialized
}

// Fetch latest blog articles from database
$latest_posts = [];
try {
    $latest_posts = db_fetch_all("SELECT p.*, c.name as category_name FROM posts p 
                                  LEFT JOIN categories c ON p.category_id = c.id 
                                  WHERE p.status = 'published' 
                                  ORDER BY p.published_at DESC LIMIT 3");
} catch (Exception $e) {
    // Graceful fallback
}

include __DIR__ . '/includes/header.php';
?>

<!-- 1. HERO SECTION -->
<section class="hero-section">
    <div class="container hero-grid">
        <!-- Hero Text Left -->
        <div class="hero-content">
            <div class="hero-badge-row">
                <span class="hero-badge">🇹🇿 Dar es Salaam, Tanzania</span>
                <span class="hero-badge">📜 BOT Certified Financial Educator</span>
                <span class="hero-badge">🏆 Golden Man of the Year 22/23</span>
            </div>

            <h1 class="hero-title">
                Helping <span class="highlight">Dreamers</span> Become Doers, and Doers Become <span class="highlight">Winners</span>.
            </h1>

            <p class="hero-tagline">
                I am <strong>Kelvin Kibenje Kenedy Kyaluoko</strong>—an author, speaker, investor, and financial literacy mentor. I empower everyday East Africans to master budgeting, eliminate bad debt, invest safely, and build profitable China-to-Tanzania import enterprises.
            </p>

            <div class="hero-buttons">
                <a href="/books.php" class="btn btn-gold">Explore Books (Shop)</a>
                <a href="/speaking.php" class="btn btn-outline-gold">Invite Kelvin to Speak</a>
                <a href="<?= h(get_setting('instagram_url', 'https://instagram.com/kelvinkibenje')) ?>" target="_blank" rel="noopener" class="btn btn-outline-gold" style="border-color: rgba(255,255,255,0.2); color: var(--slate-200);">
                    IG @kelvinkibenje (499K+)
                </a>
            </div>

            <div class="hero-stats-row">
                <div class="stat-item">
                    <span class="stat-num">499K+</span>
                    <span class="stat-label">Instagram Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">3</span>
                    <span class="stat-label">Bestselling Books</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">10,000+</span>
                    <span class="stat-label">Entrepreneurs Trained</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">BOT</span>
                    <span class="stat-label">Certified Educator</span>
                </div>
            </div>
        </div>

        <!-- Hero Photo Right -->
        <div class="hero-image-wrapper">
            <div class="hero-photo-frame">
                <img src="/images/hero.jpg" alt="Kelvin Kibenje Kenedy Kyaluoko - Speaker, Author, Investor" class="hero-photo">
                
                <div class="hero-floating-card">
                    <div class="floating-card-title">🏆 Golden Man of the Year</div>
                    <div class="floating-card-text">Awarded in 2022/2023 for Excellence in Youth Financial Mentorship</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. ABOUT KELVIN SNAPSHOT -->
<section class="section section-alt">
    <div class="container">
        <div class="about-preview-grid">
            <div class="about-img-group">
                <img src="/images/about.jpg" alt="Kelvin Kibenje speaking at Vijana Uongozi Forum" class="about-main-img">
                <div class="award-badge-card">
                    <div class="award-year">BOT</div>
                    <div class="award-text">Certified Educator</div>
                </div>
            </div>

            <div class="about-text-content">
                <div class="section-subtitle">About Kelvin Kibenje</div>
                <h3>Democratizing Financial Education Across East Africa</h3>
                
                <p>
                    Throughout Tanzania and Africa, millions of hard-working people earn an income every day—yet struggle to transform that income into permanent financial security. Bad loans, lack of budgeting discipline, and fear of investing hold back even the most ambitious entrepreneurs.
                </p>
                <p>
                    As a <strong>Certified Financial Educator by the Bank of Tanzania (BOT)</strong>, I bridge that gap. Through practical seminars, corporate training, and my three bestselling books, I provide actionable roadmaps that simplify complex financial principles into daily habits.
                </p>

                <ul class="credentials-list">
                    <li>
                        <span class="check-icon">✓</span>
                        <span><strong>Bank of Tanzania (BOT) Certified:</strong> Trusted institutional financial literacy curriculum.</span>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <span><strong>2-Time Golden Man of the Year (22/23):</strong> Honored for transformative youth impact.</span>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <span><strong>Corporate &amp; Agency Mentor:</strong> Trained Vodacom M-Pesa agents across Dar es Salaam.</span>
                    </li>
                </ul>

                <a href="/about.php" class="btn btn-gold" style="margin-top: 10px;">Read Full Biography &amp; Mission</a>
            </div>
        </div>
    </div>
</section>

<!-- 3. FEATURED BOOKS SECTION -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Books &amp; Practical Guides</div>
            <h2 class="section-title">The Financial Freedom Library</h2>
            <p class="section-desc">
                Three self-published, practical manuals designed for East African entrepreneurs and investors. Available in print via instant WhatsApp order or Makumbusho Plaza pickup.
            </p>
        </div>

        <div class="books-grid">
            <?php if (!empty($featured_books)): ?>
                <?php foreach ($featured_books as $book): ?>
                    <div class="book-card">
                        <div class="book-cover-container">
                            <span class="book-badge"><?= $book['is_featured'] ? 'Bestseller' : 'Guide' ?></span>
                            <a href="/book.php?slug=<?= h($book['slug']) ?>">
                                <img src="<?= h($book['cover_image']) ?>" alt="<?= h($book['title']) ?>" class="book-cover-img">
                            </a>
                        </div>
                        <div class="book-content">
                            <h3 class="book-title">
                                <a href="/book.php?slug=<?= h($book['slug']) ?>"><?= h($book['title']) ?></a>
                            </h3>
                            <?php if (!empty($book['subtitle'])): ?>
                                <div class="book-subtitle"><?= h($book['subtitle']) ?></div>
                            <?php endif; ?>
                            <p class="book-desc"><?= h(truncate_text($book['short_description'], 130)) ?></p>
                            
                            <div class="book-footer">
                                <span class="book-price"><?= format_tsh($book['price_tsh']) ?></span>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-outline-gold open-order-modal" style="padding: 8px 14px; font-size: 13px;"
                                            data-book-id="<?= (int)$book['id'] ?>"
                                            data-book-title="<?= h($book['title']) ?>"
                                            data-book-price="<?= (int)$book['price_tsh'] ?>">
                                        Order Online
                                    </button>
                                    <button type="button" class="btn btn-gold whatsapp-order-btn" style="padding: 8px 14px; font-size: 13px;"
                                            data-book-title="<?= h($book['title']) ?>"
                                            data-book-price="<?= (int)$book['price_tsh'] ?>"
                                            data-phone="<?= h(get_setting('whatsapp_number', '255677853595')) ?>">
                                        WhatsApp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--slate-400); text-align: center; grid-column: 1 / -1;">No books loaded yet. Please import <code>schema.sql</code>.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 48px;">
            <a href="/books.php" class="btn btn-outline-gold">View All Books &amp; Complete Library Bundle</a>
        </div>
    </div>
</section>

<!-- 4. SPEAKING & VODACOM TEMEKE SPOTLIGHT -->
<section class="section section-alt">
    <div class="container">
        <div class="spotlight-card">
            <div>
                <span class="spotlight-tag">Vodacom 25th Anniversary • Dar es Salaam</span>
                <h2 class="spotlight-title">Empowering M-Pesa Agents With Actionable Financial Discipline</h2>
                <p style="color: var(--slate-300); margin-bottom: 24px; font-size: 16px;">
                    During Vodacom Tanzania’s historic 25th Anniversary celebrations, Kelvin Kibenje delivered high-impact financial literacy and safe investment training to M-Pesa agents across Temeke and Greater Dar es Salaam.
                </p>
                <p style="color: var(--slate-300); margin-bottom: 28px; font-size: 15px;">
                    From separating business working float from personal expenditure, to avoiding high-interest consumer debt traps and compounding profits via Treasury Bonds—Kelvin equips corporate networks and grassroots entrepreneurs with lasting skills.
                </p>
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <a href="/speaking.php" class="btn btn-gold">View Speaking Topics</a>
                    <a href="/contact.php?type=speaking" class="btn btn-outline-gold">Book Kelvin for Your Conference</a>
                </div>
            </div>
            <div>
                <img src="/images/event-vodacom.jpg" alt="Kelvin Kibenje training Vodacom M-Pesa Agents in Temeke" class="spotlight-img">
            </div>
        </div>
    </div>
</section>

<!-- 5. LATEST ARTICLES FROM THE BLOG -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Financial Education &amp; Articles</div>
            <h2 class="section-title">Insights for Doers &amp; Winners</h2>
            <p class="section-desc">
                Read Kelvin's latest guides on China-to-Tanzania importing, budgeting discipline, and Tanzanian wealth compounding.
            </p>
        </div>

        <div class="blog-grid">
            <?php if (!empty($latest_posts)): ?>
                <?php foreach ($latest_posts as $post): ?>
                    <article class="post-card">
                        <div class="post-img-wrapper">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?= h($post['featured_image']) ?>" alt="<?= h($post['title']) ?>" class="post-img">
                            <?php else: ?>
                                <img src="/images/event-seminar.jpg" alt="<?= h($post['title']) ?>" class="post-img">
                            <?php endif; ?>
                            <?php if (!empty($post['category_name'])): ?>
                                <span class="post-category-tag"><?= h($post['category_name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="post-content">
                            <div class="post-meta">
                                <span><?= format_date($post['published_at']) ?></span>
                                <span> • <?= (int)$post['views'] ?> views</span>
                            </div>
                            <h3 class="post-title">
                                <a href="/post.php?slug=<?= h($post['slug']) ?>"><?= h($post['title']) ?></a>
                            </h3>
                            <p class="post-excerpt"><?= h(truncate_text($post['excerpt'], 135)) ?></p>
                            <a href="/post.php?slug=<?= h($post['slug']) ?>" class="read-more-link">
                                Read Full Article →
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--slate-400); text-align: center; grid-column: 1 / -1;">No blog articles published yet.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 48px;">
            <a href="/blog.php" class="btn btn-outline-gold">Explore All Financial Articles</a>
        </div>
    </div>
</section>

<!-- ORDER FORM MODAL (Shared across Home and Shop) -->
<div class="modal-overlay" id="orderModal">
    <div class="modal-card">
        <button type="button" class="modal-close" aria-label="Close modal">&times;</button>
        <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 6px;">
            Order Book Direct
        </h3>
        <p style="color: var(--gold-400); font-weight: 600; margin-bottom: 20px;" id="modalBookTitleDisplay">
            Selected Book
        </p>

        <form action="/books.php" method="POST" id="modalOrderForm">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="place_order">
            <input type="hidden" name="book_id" id="modalBookId" value="">

            <div class="form-group">
                <label class="form-label">Your Full Name *</label>
                <input type="text" name="customer_name" required class="form-control" placeholder="e.g. Juma Mussa">
            </div>

            <div class="form-group">
                <label class="form-label">WhatsApp / Mobile Number *</label>
                <input type="tel" name="customer_phone" required class="form-control" placeholder="e.g. +255 754 000 000">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address (Optional)</label>
                <input type="email" name="customer_email" class="form-control" placeholder="for order confirmation email">
            </div>

            <div class="form-group">
                <label class="form-label">Delivery Address / Location in Tanzania *</label>
                <input type="text" name="delivery_address" required class="form-control" placeholder="e.g. Sinza Palestine, Dar es Salaam / or Makumbusho Plaza Pickup">
            </div>

            <div class="form-group">
                <label class="form-label">Quantity *</label>
                <input type="number" name="quantity" id="orderQtyInput" required min="1" value="1" class="form-control" data-base-price="30000">
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method *</label>
                <select name="payment_method" class="form-control" required>
                    <option value="M-Pesa">M-Pesa (+255 677 853 595 - Kelvin Kyaluoko)</option>
                    <option value="Tigo Pesa">Tigo Pesa (+255 677 853 595 - Kelvin Kyaluoko)</option>
                    <option value="Cash on Pickup">Cash on Pickup (Makumbusho Plaza, 1st Floor)</option>
                </select>
            </div>

            <div class="payment-instructions-box" style="margin: 16px 0;">
                <div class="payment-title">📱 Mobile Money Payment Instructions</div>
                <div class="payment-row">
                    <span>M-Pesa / Tigo Pesa Number:</span>
                    <span class="payment-number">+255 677 853 595</span>
                </div>
                <div class="payment-row">
                    <span>Account Name:</span>
                    <span class="payment-number">Kelvin Kenedy Kyaluoko</span>
                </div>
                <p style="font-size: 12px; color: var(--slate-400); margin-top: 8px;">
                    Send payment via mobile money, then click "Submit Order" below. We will confirm your payment via WhatsApp immediately!
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                <div>
                    <span style="font-size: 13px; color: var(--slate-400);">Total to Pay:</span><br>
                    <strong style="font-size: 20px; color: var(--gold-400);" id="modalTotalPriceDisplay">TSh 30,000</strong>
                </div>
                <button type="submit" class="btn btn-gold">Submit Order</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
