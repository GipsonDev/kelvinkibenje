<?php
/**
 * ==============================================================================
 * CMS NEWSLETTER SUBSCRIBERS LIST (admin/subscribers.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Newsletter Subscribers";

$subs = db_fetch_all("SELECT * FROM subscribers ORDER BY created_at DESC");

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="admin-page-title">Newsletter Subscribers</h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Export your audience to CSV for email marketing.</p>
    </div>
    <a href="/admin/subscribers-export.php" class="btn btn-gold">📥 Export to CSV</a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Email Address</th>
                <th>Name</th>
                <th>Status</th>
                <th>Subscribed On</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subs)): ?>
                <?php foreach ($subs as $s): ?>
                    <tr>
                        <td><strong><?= h($s['email']) ?></strong></td>
                        <td><?= h($s['name'] ?: 'N/A') ?></td>
                        <td>
                            <?= $s['status'] === 'active' ? '<span class="status-badge status-published">Active</span>' : '<span class="status-badge status-cancelled">Unsubscribed</span>' ?>
                        </td>
                        <td><?= format_date($s['created_at'], 'M d, Y') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 32px; color: #94a3b8;">No subscribers yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
