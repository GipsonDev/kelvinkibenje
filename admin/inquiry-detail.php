<?php
/**
 * ==============================================================================
 * CMS INQUIRY DETAIL VIEW (admin/inquiry-detail.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$id = (int)($_GET['id'] ?? 0);
$inq = db_fetch_one("SELECT * FROM inquiries WHERE id = ?", [$id]);

if (!$inq) {
    header('Location: /admin/inquiries.php');
    exit;
}

// Mark as read
if (!$inq['is_read']) {
    db_execute("UPDATE inquiries SET is_read = 1 WHERE id = ?", [$id]);
    $inq['is_read'] = 1;
}

$page_title = "Message Details: " . $inq['subject'];

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="admin-page-title"><?= h($inq['subject'] ?: 'Inquiry Details') ?></h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Received on <?= format_date($inq['created_at'], 'M d, Y H:i') ?></p>
    </div>
    <a href="/admin/inquiries.php" class="btn btn-outline-gold">← Back to Inquiries</a>
</div>

<div class="admin-card" style="max-width: 800px;">
    <div style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <span style="background: rgba(212,175,55,0.2); color: #d4af37; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                <?= h($inq['type']) ?>
            </span>
        </div>
        <div style="font-size: 13px; color: #94a3b8;">
            Status: <strong style="color: #34d399;">Read</strong>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="admin-label">Sender Name:</span>
            <div style="font-size: 16px; color: #fff; font-weight: bold;"><?= h($inq['name']) ?></div>
        </div>
        <div>
            <span class="admin-label">Email Address:</span>
            <div><a href="mailto:<?= h($inq['email']) ?>" style="color: #d4af37;"><?= h($inq['email']) ?></a></div>
        </div>
        <div>
            <span class="admin-label">Phone / WhatsApp:</span>
            <div>
                <?php if (!empty($inq['phone'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $inq['phone']) ?>" target="_blank" style="color: #34d399; font-weight: bold;">
                        <?= h($inq['phone']) ?> (Open WhatsApp)
                    </a>
                <?php else: ?>
                    <span style="color: #94a3b8;">Not provided</span>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <span class="admin-label">Subject:</span>
            <div style="color: #fff; font-weight: 500;"><?= h($inq['subject'] ?: 'N/A') ?></div>
        </div>
    </div>

    <div style="background: #0b1329; padding: 24px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); font-size: 15px; color: #e2e8f0; line-height: 1.8; white-space: pre-wrap; margin-bottom: 28px;">
        <?= h($inq['message']) ?>
    </div>

    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <?php if (!empty($inq['phone'])): ?>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $inq['phone']) ?>?text=<?= urlencode("Hello " . $inq['name'] . "! We are replying to your message on Kelvin Kibenje website regarding: " . $inq['subject']) ?>" 
               target="_blank" class="btn btn-gold">
                📱 Reply via WhatsApp
            </a>
        <?php endif; ?>

        <a href="mailto:<?= h($inq['email']) ?>?subject=<?= urlencode("Re: " . $inq['subject']) ?>&body=<?= urlencode("Hello " . $inq['name'] . ",\n\nThank you for reaching out to Kelvin Kibenje.\n\n") ?>" 
           class="btn btn-outline-gold">
            ✉️ Reply via Email
        </a>

        <a href="/admin/inquiry-delete.php?id=<?= (int)$inq['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" 
           class="btn btn-outline-gold confirm-delete" style="border-color: #ef4444; color: #f87171; margin-left: auto;">
            Delete Message
        </a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
