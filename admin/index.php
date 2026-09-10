<?php
/**
 * ==============================================================================
 * CMS ADMIN DASHBOARD (admin/index.php)
 * Real-time stats, quick actions, recent book orders & speaking inquiries
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Dashboard Overview";

// Fetch summary statistics
$stats = [
    'posts' => 0,
    'books' => 0,
    'orders' => 0,
    'revenue' => 0,
    'inquiries' => 0,
    'subscribers' => 0,
];

try {
    $stats['posts'] = (int)db_fetch_one("SELECT COUNT(*) as cnt FROM posts")['cnt'];
    $stats['books'] = (int)db_fetch_one("SELECT COUNT(*) as cnt FROM books")['cnt'];
    $stats['orders'] = (int)db_fetch_one("SELECT COUNT(*) as cnt FROM orders")['cnt'];
    $rev = db_fetch_one("SELECT SUM(total_tsh) as total FROM orders WHERE status != 'Cancelled'")['total'];
    $stats['revenue'] = (int)$rev;
    $stats['inquiries'] = (int)db_fetch_one("SELECT COUNT(*) as cnt FROM inquiries")['cnt'];
    $stats['subscribers'] = (int)db_fetch_one("SELECT COUNT(*) as cnt FROM subscribers WHERE status = 'active'")['cnt'];
} catch (Exception $e) {
    // Fallback
}

// Fetch recent orders
$recent_orders = [];
try {
    $recent_orders = db_fetch_all(
        "SELECT o.*, b.title as book_title FROM orders o 
         LEFT JOIN books b ON o.book_id = b.id 
         ORDER BY o.created_at DESC LIMIT 5"
    );
} catch (Exception $e) {
    // Fallback
}

// Fetch recent inquiries
$recent_inquiries = [];
try {
    $recent_inquiries = db_fetch_all("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5");
} catch (Exception $e) {
    // Fallback
}

include __DIR__ . '/includes/header.php';
?>

<h1 class="admin-page-title">Dashboard Overview</h1>
<p class="admin-page-subtitle">Manage Kelvin Kibenje's website content, books, and customer inquiries.</p>

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-label">Total Published Posts</div>
        <div class="stat-card-value"><?= number_format($stats['posts']) ?></div>
        <a href="/admin/blog.php" style="font-size: 12px; color: #d4af37; margin-top: 8px; display: inline-block;">Manage Blog →</a>
    </div>

    <div class="stat-card">
        <div class="stat-card-label">Total Books in Shop</div>
        <div class="stat-card-value"><?= number_format($stats['books']) ?></div>
        <a href="/admin/books.php" style="font-size: 12px; color: #d4af37; margin-top: 8px; display: inline-block;">Manage Books →</a>
    </div>

    <div class="stat-card">
        <div class="stat-card-label">Total Book Orders</div>
        <div class="stat-card-value"><?= number_format($stats['orders']) ?></div>
        <div style="font-size: 12px; color: #34d399; margin-top: 4px;">
            Total Value: <?= format_tsh($stats['revenue']) ?>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-label">Inquiries &amp; Bookings</div>
        <div class="stat-card-value"><?= number_format($stats['inquiries']) ?></div>
        <a href="/admin/inquiries.php" style="font-size: 12px; color: #d4af37; margin-top: 8px; display: inline-block;">View Messages →</a>
    </div>

    <div class="stat-card">
        <div class="stat-card-label">Newsletter Community</div>
        <div class="stat-card-value"><?= number_format($stats['subscribers']) ?></div>
        <a href="/admin/subscribers.php" style="font-size: 12px; color: #d4af37; margin-top: 8px; display: inline-block;">Export CSV →</a>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="admin-card" style="margin-bottom: 36px;">
    <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px;">⚡ Quick CMS Actions</h3>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="/admin/blog-edit.php" class="btn btn-gold">✍️ Write New Blog Post</a>
        <a href="/admin/book-edit.php" class="btn btn-outline-gold">📚 Add New Book</a>
        <a href="/admin/settings.php" class="btn btn-outline-gold">⚙️ Update Contact &amp; Payment Settings</a>
        <a href="/admin/orders.php" class="btn btn-outline-gold">🛒 Manage Customer Orders</a>
    </div>
</div>

<!-- RECENT BOOK ORDERS -->
<div class="admin-table-wrapper">
    <div style="padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #fff; font-size: 18px;">Recent Book Orders</h3>
        <a href="/admin/orders.php" style="font-size: 13px; color: #d4af37;">View All Orders →</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Book Title</th>
                <th>Total (TSh)</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><strong><?= h($order['order_number']) ?></strong></td>
                        <td>
                            <div><?= h($order['customer_name']) ?></div>
                            <small style="color: #94a3b8;"><?= h($order['customer_phone']) ?></small>
                        </td>
                        <td><?= h($order['book_title'] ?: 'Book #' . $order['book_id']) ?> (Qty: <?= (int)$order['quantity'] ?>)</td>
                        <td><strong><?= format_tsh($order['total_tsh']) ?></strong></td>
                        <td>
                            <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                <?= h($order['status']) ?>
                            </span>
                        </td>
                        <td><?= format_date($order['created_at'], 'M d, Y') ?></td>
                        <td>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>?text=<?= urlencode("Hello " . $order['customer_name'] . ", regarding your book order #" . $order['order_number'] . " on Kelvin Kibenje website...") ?>" 
                               target="_blank" class="btn btn-gold" style="padding: 4px 10px; font-size: 12px;">
                                📱 Chat on WhatsApp
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">No orders found yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- RECENT MESSAGES & SPEAKING INVITATIONS -->
<div class="admin-table-wrapper">
    <div style="padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #fff; font-size: 18px;">Recent Inquiries &amp; Speaking Bookings</h3>
        <a href="/admin/inquiries.php" style="font-size: 13px; color: #d4af37;">View All Messages →</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_inquiries)): ?>
                <?php foreach ($recent_inquiries as $inq): ?>
                    <tr>
                        <td>
                            <span style="background: <?= $inq['type'] === 'Speaking Booking' ? 'rgba(212,175,55,0.2)' : 'rgba(255,255,255,0.1)' ?>; color: <?= $inq['type'] === 'Speaking Booking' ? '#d4af37' : '#fff' ?>; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                <?= h($inq['type']) ?>
                            </span>
                        </td>
                        <td>
                            <div><?= h($inq['name']) ?></div>
                            <small style="color: #94a3b8;"><?= h($inq['email']) ?></small>
                        </td>
                        <td><?= h(truncate_text($inq['subject'] ?: $inq['message'], 60)) ?></td>
                        <td><?= format_date($inq['created_at'], 'M d, Y') ?></td>
                        <td>
                            <?= $inq['is_read'] ? '<span style="color:#94a3b8;">Read</span>' : '<span style="color:#34d399; font-weight:bold;">New</span>' ?>
                        </td>
                        <td>
                            <a href="/admin/inquiry-detail.php?id=<?= (int)$inq['id'] ?>" class="btn btn-outline-gold" style="padding: 4px 10px; font-size: 12px;">
                                Read Message
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">No messages found yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
