<?php
/**
 * ==============================================================================
 * BOOKS SHOP PAGE (books.php)
 * Complete Bookshop, Order Processor & Complete Library Bundle
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "Books & Practical Guides | Kelvin Kibenje";
$page_description = "Order Kelvin Kibenje's self-published books: China to Tanzania, Elimu ya Fedha na Biashara, and M-Wekeza. Pay via M-Pesa or Tigo Pesa with nationwide delivery across Tanzania.";

$order_success = false;
$order_error = '';
$placed_order = null;

// Process Order Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $order_error = "Security token mismatch. Please try again.";
    } else {
        $book_id = (int)($_POST['book_id'] ?? 0);
        $customer_name = trim($_POST['customer_name'] ?? '');
        $customer_phone = trim($_POST['customer_phone'] ?? '');
        $customer_email = trim($_POST['customer_email'] ?? '');
        $delivery_address = trim($_POST['delivery_address'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);
        $payment_method = trim($_POST['payment_method'] ?? 'M-Pesa / Tigo Pesa');

        if ($quantity < 1) {
            $quantity = 1;
        }

        if (empty($customer_name) || empty($customer_phone) || empty($delivery_address) || $book_id <= 0) {
            $order_error = "Please fill in all required fields (Name, Phone, Delivery Location).";
        } else {
            // Retrieve book price
            $book = db_fetch_one("SELECT * FROM books WHERE id = ?", [$book_id]);
            if (!$book) {
                $order_error = "Selected book was not found.";
            } else {
                $total_tsh = $book['price_tsh'] * $quantity;
                $order_number = 'ORD-' . date('Ym') . '-' . str_pad(rand(100, 9999), 4, '0', STR_PAD_LEFT);

                try {
                    db_execute(
                        "INSERT INTO orders (order_number, book_id, customer_name, customer_phone, customer_email, delivery_address, quantity, total_tsh, payment_method, status, notes, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Order submitted via Website Shop', NOW())",
                        [
                            $order_number,
                            $book_id,
                            $customer_name,
                            $customer_phone,
                            $customer_email,
                            $delivery_address,
                            $quantity,
                            $total_tsh,
                            $payment_method
                        ]
                    );

                    $order_success = true;
                    $placed_order = [
                        'order_number' => $order_number,
                        'book_title' => $book['title'],
                        'quantity' => $quantity,
                        'total_tsh' => $total_tsh,
                        'payment_method' => $payment_method,
                        'customer_name' => $customer_name,
                        'customer_phone' => $customer_phone,
                        'delivery_address' => $delivery_address
                    ];

                    // Email Kelvin
                    $html_email = "<h3>New Book Order Received! (#" . h($order_number) . ")</h3>"
                                . "<p><strong>Book:</strong> " . h($book['title']) . " (Qty: " . $quantity . ")</p>"
                                . "<p><strong>Total Amount:</strong> " . format_tsh($total_tsh) . "</p>"
                                . "<p><strong>Customer Name:</strong> " . h($customer_name) . "</p>"
                                . "<p><strong>Phone (WhatsApp):</strong> " . h($customer_phone) . "</p>"
                                . "<p><strong>Delivery Address:</strong> " . h($delivery_address) . "</p>"
                                . "<p><strong>Payment Method Selected:</strong> " . h($payment_method) . "</p>";
                    send_site_email(SITE_EMAIL, "New Book Order #" . $order_number, $html_email, $customer_email ?: null, $customer_name);

                } catch (Exception $e) {
                    $order_error = "Could not save order. Please order via WhatsApp directly.";
                }
            }
        }
    }
}

// Fetch all books from DB
$all_books = [];
try {
    $all_books = db_fetch_all("SELECT * FROM books ORDER BY order_index ASC");
} catch (Exception $e) {
    // Fallback
}

include __DIR__ . '/includes/header.php';
?>

<!-- BOOKS HERO -->
<section class="section section-alt" style="padding: 60px 0 70px;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 20px;">
            <div class="section-subtitle">Official Bookshop</div>
            <h1 class="section-title">Practical Guides for East African Entrepreneurs</h1>
            <p class="section-desc">
                Written by Bank of Tanzania (BOT) Certified Financial Educator Kelvin Kibenje. Delivered nationwide across Tanzania or available for instant pickup at Makumbusho Plaza, Dar es Salaam.
            </p>
        </div>
    </div>
</section>

<!-- ORDER SUCCESS NOTIFICATION -->
<?php if ($order_success && $placed_order): ?>
<section class="section" style="padding: 40px 0 0;">
    <div class="container" style="max-width: 720px;">
        <div style="background: var(--navy-800); border: 2px solid #10b981; border-radius: var(--radius-lg); padding: 36px; text-align: center; box-shadow: var(--shadow-lg);">
            <div style="font-size: 48px; margin-bottom: 12px;">🎉</div>
            <h2 style="font-family: var(--font-heading); color: var(--white); margin-bottom: 8px;">Order Successfully Placed!</h2>
            <p style="color: #34d399; font-weight: 600; font-size: 18px; margin-bottom: 20px;">
                Order Number: <?= h($placed_order['order_number']) ?>
            </p>
            <p style="color: var(--slate-300); font-size: 15px; margin-bottom: 24px;">
                Thank you, <strong><?= h($placed_order['customer_name']) ?></strong>! Your order for <strong><?= h($placed_order['book_title']) ?></strong> (Total: <strong style="color: var(--gold-400);"><?= format_tsh($placed_order['total_tsh']) ?></strong>) has been logged.
            </p>

            <div class="payment-instructions-box" style="max-width: 480px; margin: 0 auto 28px; text-align: left;">
                <div class="payment-title">📱 Next Step: Send Your Mobile Money Payment</div>
                <div class="payment-row">
                    <span>M-Pesa / Tigo Pesa Number:</span>
                    <span class="payment-number">+255 677 853 595</span>
                </div>
                <div class="payment-row">
                    <span>Account Name:</span>
                    <span class="payment-number">Kelvin Kenedy Kyaluoko</span>
                </div>
                <div class="payment-row">
                    <span>Amount to Send:</span>
                    <span class="payment-number" style="color: var(--gold-400);"><?= format_tsh($placed_order['total_tsh']) ?></span>
                </div>
            </div>

            <p style="font-size: 14px; color: var(--slate-400); margin-bottom: 24px;">
                Once you send the payment, click the button below to confirm with Kelvin on WhatsApp so we dispatch your parcel immediately!
            </p>

            <a href="https://wa.me/<?= h(get_setting('whatsapp_number', '255677853595')) ?>?text=<?= urlencode("Hello Kelvin! I have placed Order #" . $placed_order['order_number'] . " for " . $placed_order['book_title'] . " (" . format_tsh($placed_order['total_tsh']) . "). Here is my payment receipt screenshot!") ?>" 
               target="_blank" class="btn btn-gold" style="padding: 14px 28px; font-size: 16px;">
                📱 Send Receipt on WhatsApp to Confirm
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($order_error)): ?>
<section class="section" style="padding: 30px 0 0;">
    <div class="container" style="max-width: 720px;">
        <div class="alert alert-danger"><?= h($order_error) ?></div>
    </div>
</section>
<?php endif; ?>

<!-- COMPLETE LIBRARY BUNDLE BANNER -->
<section class="section" style="padding-bottom: 40px;">
    <div class="container">
        <div style="background: linear-gradient(135deg, var(--navy-800), #1a2850); border: 2px solid var(--gold-500); border-radius: var(--radius-lg); padding: 40px; display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 32px; align-items: center; box-shadow: var(--shadow-lg);">
            <div>
                <span style="display: inline-block; background: var(--gold-500); color: var(--navy-950); font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 50px; text-transform: uppercase; margin-bottom: 14px;">
                    ⭐ Special Value Bundle
                </span>
                <h2 style="font-family: var(--font-heading); color: var(--white); font-size: 32px; margin-bottom: 12px;">
                    The Complete Financial Freedom Library
                </h2>
                <p style="color: var(--slate-300); font-size: 16px; margin-bottom: 20px;">
                    Get all three of Kelvin Kibenje’s bestselling books: <strong>China to Tanzania</strong>, <strong>Elimu ya Fedha na Biashara</strong>, and <strong>M-Wekeza</strong> in one complete set. Save TSh 10,000 instantly!
                </p>
                <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <span style="font-size: 14px; color: var(--slate-400); text-decoration: line-through;">TSh 75,000</span>
                        <span style="font-family: var(--font-heading); font-size: 32px; font-weight: 700; color: var(--gold-400); display: block;">
                            TSh 65,000
                        </span>
                    </div>
                    <button type="button" class="btn btn-gold open-order-modal" style="padding: 14px 32px; font-size: 16px;"
                            data-book-id="1"
                            data-book-title="Complete Library Bundle (All 3 Books)"
                            data-book-price="65000">
                        Order Complete Library (TSh 65,000)
                    </button>
                    <a href="https://wa.me/<?= h(get_setting('whatsapp_number', '255677853595')) ?>?text=<?= urlencode("Hello Kelvin! I want to order the COMPLETE LIBRARY BUNDLE (All 3 Books for TSh 65,000). Please share payment details!") ?>" 
                       target="_blank" class="btn btn-outline-gold" style="padding: 14px 24px;">
                        Order via WhatsApp
                    </a>
                </div>
            </div>
            <div style="display: flex; gap: -20px; justify-content: center; align-items: center;">
                <img src="/uploads/books/china-to-tanzania.jpg" alt="China to Tanzania" style="width: 140px; border-radius: 4px; box-shadow: -10px 10px 20px rgba(0,0,0,0.6); transform: rotate(-5deg); z-index: 1;">
                <img src="/uploads/books/elimu-ya-fedha.jpg" alt="Elimu ya Fedha" style="width: 150px; border-radius: 4px; box-shadow: 0 15px 30px rgba(0,0,0,0.7); z-index: 3; margin: 0 -20px;">
                <img src="/uploads/books/m-wekeza.jpg" alt="M-Wekeza" style="width: 140px; border-radius: 4px; box-shadow: 10px 10px 20px rgba(0,0,0,0.6); transform: rotate(5deg); z-index: 2;">
            </div>
        </div>
    </div>
</section>

<!-- ALL BOOKS LISTING -->
<section class="section">
    <div class="container">
        <div class="books-grid">
            <?php if (!empty($all_books)): ?>
                <?php foreach ($all_books as $book): ?>
                    <div class="book-card">
                        <div class="book-cover-container">
                            <span class="book-badge"><?= $book['price_tsh'] == 30000 ? 'Bestseller' : 'BOT Certified' ?></span>
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
                            <p class="book-desc"><?= h(truncate_text($book['short_description'], 140)) ?></p>
                            
                            <div class="book-footer">
                                <span class="book-price"><?= format_tsh($book['price_tsh']) ?></span>
                                <div style="display: flex; gap: 8px;">
                                    <a href="/book.php?slug=<?= h($book['slug']) ?>" class="btn btn-outline-gold" style="padding: 8px 14px; font-size: 13px;">
                                        View Details
                                    </a>
                                    <button type="button" class="btn btn-gold open-order-modal" style="padding: 8px 14px; font-size: 13px;"
                                            data-book-id="<?= (int)$book['id'] ?>"
                                            data-book-title="<?= h($book['title']) ?>"
                                            data-book-price="<?= (int)$book['price_tsh'] ?>">
                                        Order Online
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- HOW DELIVERY WORKS IN TANZANIA -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Simple Delivery</div>
            <h2 class="section-title">How Book Orders &amp; Delivery Work</h2>
            <p class="section-desc">
                We make it easy to get physical copies anywhere in Tanzania.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px; text-align: center;">
            <div style="background: var(--navy-800); padding: 36px 24px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 36px; margin-bottom: 16px;">📱</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 20px; margin-bottom: 12px;">
                    1. Place Your Order
                </h3>
                <p style="color: var(--slate-300); font-size: 14px;">
                    Click "Order Online" or "WhatsApp" on any book. Fill in your delivery location in Dar es Salaam or upcountry (Arusha, Mwanza, Dodoma, Mbeya, etc.).
                </p>
            </div>

            <div style="background: var(--navy-800); padding: 36px 24px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 36px; margin-bottom: 16px;">💳</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 20px; margin-bottom: 12px;">
                    2. Pay via Mobile Money
                </h3>
                <p style="color: var(--slate-300); font-size: 14px;">
                    Send your payment via M-Pesa or Tigo Pesa to <strong>+255 677 853 595 (Kelvin Kyaluoko)</strong> and share your receipt on WhatsApp.
                </p>
            </div>

            <div style="background: var(--navy-800); padding: 36px 24px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 36px; margin-bottom: 16px;">🚚</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 20px; margin-bottom: 12px;">
                    3. Rapid Dispatch / Pickup
                </h3>
                <p style="color: var(--slate-300); font-size: 14px;">
                    Dar es Salaam orders are dispatched via courier within hours. Upcountry parcels are sent safely via express regional coach buses. Or pick up in person at Makumbusho Plaza!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- MODAL INCLUDED VIA JS/FOOTER -->
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
