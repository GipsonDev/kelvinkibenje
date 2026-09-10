<?php
/**
 * ==============================================================================
 * SINGLE BOOK DETAIL PAGE (book.php)
 * Deep-dive description, learning outcomes, table of contents & hybrid checkout
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    header('Location: /books.php');
    exit;
}

$book = false;
try {
    $book = db_fetch_one("SELECT * FROM books WHERE slug = ? AND is_available = 1", [$slug]);
} catch (Exception $e) {
    // Fallback
}

if (!$book) {
    header('Location: /books.php');
    exit;
}

$page_title = h($book['title']) . " | Kelvin Kibenje Bookshop";
$page_description = h(truncate_text($book['short_description'], 160));
$page_image = SITE_URL . $book['cover_image'];

include __DIR__ . '/includes/header.php';
?>

<!-- BOOK HERO BREADCRUMB -->
<section class="top-bar" style="background: var(--navy-900); padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <a href="/books.php" style="font-size: 14px; color: var(--slate-400);">← Back to Complete Bookshop</a>
    </div>
</section>

<!-- BOOK DETAIL MAIN CONTENT -->
<section class="section">
    <div class="container">
        <div class="single-book-layout">
            <!-- Left Sticky Cover Column -->
            <div class="single-book-cover-wrap">
                <span class="book-badge" style="position: static; display: inline-block; margin-bottom: 14px;">
                    <?= $book['price_tsh'] == 30000 ? 'Bestselling Guide' : 'BOT Certified Content' ?>
                </span>
                <img src="<?= h($book['cover_image']) ?>" alt="<?= h($book['title']) ?>" class="single-book-cover">
                
                <div style="font-family: var(--font-heading); font-size: 28px; font-weight: 700; color: var(--gold-400); margin-bottom: 16px;">
                    <?= format_tsh($book['price_tsh']) ?>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <button type="button" class="btn btn-gold btn-block open-order-modal"
                            data-book-id="<?= (int)$book['id'] ?>"
                            data-book-title="<?= h($book['title']) ?>"
                            data-book-price="<?= (int)$book['price_tsh'] ?>">
                        Order Online (Mobile Money / Cash)
                    </button>
                    
                    <a href="https://wa.me/<?= h(get_setting('whatsapp_number', '255677853595')) ?>?text=<?= urlencode("Hello Kelvin! I want to order your book: \"" . $book['title'] . "\" (" . format_tsh($book['price_tsh']) . "). Please let me know how to make my payment and arrange delivery!") ?>" 
                       target="_blank" class="btn btn-outline-gold btn-block">
                        📱 Instant WhatsApp Order
                    </a>
                </div>

                <div class="payment-instructions-box" style="text-align: left; margin-top: 28px;">
                    <div class="payment-title">📱 Payment Details</div>
                    <div class="payment-row">
                        <span>M-Pesa / Tigo Pesa:</span>
                        <span class="payment-number">+255 677 853 595</span>
                    </div>
                    <div class="payment-row">
                        <span>Account Name:</span>
                        <span class="payment-number">Kelvin Kenedy Kyaluoko</span>
                    </div>
                    <p style="font-size: 12px; color: var(--slate-400); margin-top: 8px;">
                        Delivery across Dar es Salaam within hours. Upcountry delivery via express coach buses.
                    </p>
                </div>
            </div>

            <!-- Right Detail Text Column -->
            <div class="single-book-details">
                <h1 style="font-family: var(--font-heading); font-size: 36px; color: var(--white); line-height: 1.25; margin-bottom: 12px;">
                    <?= h($book['title']) ?>
                </h1>

                <?php if (!empty($book['subtitle'])): ?>
                    <p style="font-size: 18px; color: var(--gold-400); font-weight: 500; margin-bottom: 24px;">
                        <?= h($book['subtitle']) ?>
                    </p>
                <?php endif; ?>

                <div style="display: flex; gap: 16px; border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 14px 0; margin-bottom: 32px; flex-wrap: wrap; font-size: 14px; color: var(--slate-300);">
                    <span><strong>Author:</strong> Kelvin Kibenje Kenedy Kyaluoko</span>
                    <span>•</span>
                    <span><strong>Format:</strong> High-Quality Print Edition</span>
                    <span>•</span>
                    <span><strong>Language:</strong> English &amp; Practical Swahili terminology</span>
                    <span>•</span>
                    <span><strong>Delivery:</strong> Nationwide Tanzania</span>
                </div>

                <!-- Rich Text Description -->
                <div style="font-size: 16px; color: var(--slate-200); line-height: 1.8;">
                    <?= $book['long_description'] ?>
                </div>

                <!-- Author Guarantee Box -->
                <div style="background: var(--navy-800); border: 1px solid var(--gold-500); border-radius: var(--radius-lg); padding: 28px; margin-top: 40px; display: flex; gap: 20px; align-items: center;">
                    <img src="/images/avatar.jpg" alt="Kelvin Kibenje" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-500);">
                    <div>
                        <h4 style="font-family: var(--font-heading); color: var(--white); font-size: 18px; margin-bottom: 4px;">
                            Author's Promise to You
                        </h4>
                        <p style="font-size: 14px; color: var(--slate-300); margin: 0;">
                            "I wrote this book to be an action manual, not theoretical reading. Apply these principles in your daily budgeting, importing, and investing, and you will see tangible results."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL INCLUDED VIA FOOTER / JS -->
<div class="modal-overlay" id="orderModal">
    <div class="modal-card">
        <button type="button" class="modal-close" aria-label="Close modal">&times;</button>
        <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 6px;">
            Order Book Direct
        </h3>
        <p style="color: var(--gold-400); font-weight: 600; margin-bottom: 20px;" id="modalBookTitleDisplay">
            <?= h($book['title']) ?>
        </p>

        <form action="/books.php" method="POST" id="modalOrderForm">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="place_order">
            <input type="hidden" name="book_id" id="modalBookId" value="<?= (int)$book['id'] ?>">

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
                <input type="number" name="quantity" id="orderQtyInput" required min="1" value="1" class="form-control" data-base-price="<?= (int)$book['price_tsh'] ?>">
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
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                <div>
                    <span style="font-size: 13px; color: var(--slate-400);">Total to Pay:</span><br>
                    <strong style="font-size: 20px; color: var(--gold-400);" id="modalTotalPriceDisplay"><?= format_tsh($book['price_tsh']) ?></strong>
                </div>
                <button type="submit" class="btn btn-gold">Submit Order</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
