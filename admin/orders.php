<?php
/**
 * ==============================================================================
 * CMS ORDERS MANAGEMENT (admin/orders.php)
 * Manage book purchases, payment verification & direct WhatsApp customer chat
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Manage Book Orders";

// Handle status updates
if (isset($_GET['status_change']) && isset($_GET['order_id']) && verify_csrf($_GET['csrf_token'] ?? '')) {
    $order_id = (int)$_GET['order_id'];
    $new_status = trim($_GET['status_change']);
    if (in_array($new_status, ['Pending', 'Confirmed', 'Completed', 'Cancelled'])) {
        db_execute("UPDATE orders SET status = ? WHERE id = ?", [$new_status, $order_id]);
        header('Location: /admin/orders.php?msg=status_updated');
        exit;
    }
}

// Handle filter
$filter_status = trim($_GET['status'] ?? '');
$sql = "SELECT o.*, b.title as book_title FROM orders o LEFT JOIN books b ON o.book_id = b.id WHERE 1=1";
$params = [];

if (!empty($filter_status)) {
    $sql .= " AND o.status = ?";
    $params[] = $filter_status;
}

$sql .= " ORDER BY o.created_at DESC";

$orders = db_fetch_all($sql, $params);

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="admin-page-title">Customer Book Orders</h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Track mobile money payments and parcel dispatches.</p>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Order status updated successfully!</div>
<?php endif; ?>

<!-- Filter bar -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 24px;">
    <form action="/admin/orders.php" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <label style="font-size: 13px; color: #94a3b8;">Filter by Status:</label>
        <select name="status" class="admin-input" style="width: 160px; padding: 6px 12px;" onchange="this.form.submit()">
            <option value="">All Orders</option>
            <option value="Pending" <?= $filter_status === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Confirmed" <?= $filter_status === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
            <option value="Completed" <?= $filter_status === 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $filter_status === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <?php if (!empty($filter_status)): ?>
            <a href="/admin/orders.php" style="color: #94a3b8; font-size: 13px;">Clear Filter</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer Name &amp; Phone</th>
                <th>Book &amp; Quantity</th>
                <th>Total (TSh)</th>
                <th>Payment / Delivery</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><strong><?= h($o['order_number']) ?></strong></td>
                        <td>
                            <strong style="color: #fff; display: block;"><?= h($o['customer_name']) ?></strong>
                            <span style="color: #d4af37; font-size: 13px;"><?= h($o['customer_phone']) ?></span>
                            <?php if (!empty($o['customer_email'])): ?>
                                <div style="color: #94a3b8; font-size: 12px;"><?= h($o['customer_email']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= h($o['book_title'] ?: 'Book #' . $o['book_id']) ?></div>
                            <small style="color: #94a3b8;">Qty: <?= (int)$o['quantity'] ?></small>
                        </td>
                        <td><strong><?= format_tsh($o['total_tsh']) ?></strong></td>
                        <td>
                            <div><?= h($o['payment_method']) ?></div>
                            <small style="color: #94a3b8;"><?= h($o['delivery_address']) ?></small>
                        </td>
                        <td>
                            <span class="status-badge status-<?= strtolower($o['status']) ?>">
                                <?= h($o['status']) ?>
                            </span>
                        </td>
                        <td><?= format_date($o['created_at'], 'M d, Y') ?></td>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $o['customer_phone']) ?>?text=<?= urlencode("Hello " . $o['customer_name'] . "! We are contacting you regarding your book order #" . $o['order_number'] . " (" . format_tsh($o['total_tsh']) . ") from Kelvin Kibenje's website.") ?>" 
                                   target="_blank" class="btn btn-gold" style="padding: 4px 10px; font-size: 12px;" title="WhatsApp Customer">
                                    📱 Chat
                                </a>

                                <!-- Status change dropdown -->
                                <select onchange="if(this.value) window.location.href='/admin/orders.php?order_id=<?= (int)$o['id'] ?>&status_change='+this.value+'&csrf_token=<?= $_SESSION['csrf_token'] ?>'" 
                                        class="admin-input" style="padding: 4px 8px; font-size: 12px; width: 110px;">
                                    <option value="">Status...</option>
                                    <option value="Pending">Mark Pending</option>
                                    <option value="Confirmed">Mark Confirmed</option>
                                    <option value="Completed">Mark Completed</option>
                                    <option value="Cancelled">Mark Cancelled</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 32px; color: #94a3b8;">No orders match this filter.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
