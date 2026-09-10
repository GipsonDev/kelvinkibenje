<?php
/**
 * ==============================================================================
 * CMS INQUIRIES & SPEAKING BOOKINGS MANAGEMENT (admin/inquiries.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Inquiries & Bookings";

// Handle filter
$filter_type = trim($_GET['type'] ?? '');
$sql = "SELECT * FROM inquiries WHERE 1=1";
$params = [];

if (!empty($filter_type)) {
    $sql .= " AND type = ?";
    $params[] = $filter_type;
}

$sql .= " ORDER BY created_at DESC";

$inquiries = db_fetch_all($sql, $params);

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="admin-page-title">Messages &amp; Speaking Bookings</h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Review conference invitations and general questions.</p>
    </div>
</div>

<!-- Filter bar -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 24px;">
    <form action="/admin/inquiries.php" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <label style="font-size: 13px; color: #94a3b8;">Filter by Type:</label>
        <select name="type" class="admin-input" style="width: 200px; padding: 6px 12px;" onchange="this.form.submit()">
            <option value="">All Messages</option>
            <option value="Speaking Booking" <?= $filter_type === 'Speaking Booking' ? 'selected' : '' ?>>Speaking Bookings</option>
            <option value="General Contact" <?= $filter_type === 'General Contact' ? 'selected' : '' ?>>General Contact</option>
            <option value="Consultation" <?= $filter_type === 'Consultation' ? 'selected' : '' ?>>Consultations</option>
        </select>
        <?php if (!empty($filter_type)): ?>
            <a href="/admin/inquiries.php" style="color: #94a3b8; font-size: 13px;">Clear Filter</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Sender Name &amp; Contact</th>
                <th>Subject &amp; Excerpt</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inquiries)): ?>
                <?php foreach ($inquiries as $inq): ?>
                    <tr style="<?= $inq['is_read'] ? '' : 'background: rgba(212,175,55,0.06);' ?>">
                        <td>
                            <span style="background: <?= $inq['type'] === 'Speaking Booking' ? 'rgba(212,175,55,0.2)' : 'rgba(255,255,255,0.1)' ?>; color: <?= $inq['type'] === 'Speaking Booking' ? '#d4af37' : '#fff' ?>; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                <?= h($inq['type']) ?>
                            </span>
                        </td>
                        <td>
                            <strong style="color: #fff; display: block;"><?= h($inq['name']) ?></strong>
                            <div style="font-size: 12px; color: #d4af37;"><?= h($inq['email']) ?></div>
                            <div style="font-size: 12px; color: #94a3b8;"><?= h($inq['phone']) ?></div>
                        </td>
                        <td>
                            <strong style="color: #fff; display: block;"><?= h($inq['subject'] ?: 'No Subject') ?></strong>
                            <small style="color: #cbd5e1;"><?= h(truncate_text($inq['message'], 90)) ?></small>
                        </td>
                        <td><?= format_date($inq['created_at'], 'M d, Y H:i') ?></td>
                        <td>
                            <?= $inq['is_read'] ? '<span style="color:#94a3b8;">Read</span>' : '<span style="color:#34d399; font-weight:bold;">★ New</span>' ?>
                        </td>
                        <td>
                            <a href="/admin/inquiry-detail.php?id=<?= (int)$inq['id'] ?>" class="btn btn-outline-gold" style="padding: 6px 12px; font-size: 12px;">
                                Read &amp; Reply
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 32px; color: #94a3b8;">No inquiries found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
